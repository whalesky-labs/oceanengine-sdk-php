<?php

declare(strict_types=1);
/**
 * This file is part of Marketing PHP SDK.
 *
 * @link     https://github.com/westng/oceanengine-sdk-php
 * @document https://github.com/westng/oceanengine-sdk-php
 * @contact  westng
 * @license  https://github.com/westng/oceanengine-sdk-php/blob/main/LICENSE
 */

namespace Core\Http;

use Core\Exception\InvalidParamException;
use Core\Exception\OceanEngineException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\NullLogger;
use Swoole\Coroutine;

class HttpRequest
{
    private const RUNTIME_MODE_AUTO = 'auto';

    private const RUNTIME_MODE_FPM = 'fpm';

    private const RUNTIME_MODE_CLI = 'cli';

    private const RUNTIME_MODE_SWOOLE = 'swoole';

    private const CLIENT_POOL_MAX_SIZE = 32;

    public static int $connectTimeout = 20;

    public static int $readTimeout = 30;

    // Default retry settings (used when runtime config is not passed).
    public static bool $enableRetry = true;

    public static int $maxRetries = 3;

    public static int $retryDelay = 1000;

    public static array $retryableStatusCodes = [408, 429, 500, 502, 503, 504];

    public static array $retryableBusinessCodes = [40100, 40110, 50000];

    /**
     * 是否校验证书，或指定 CA 证书文件路径。
     */
    public static bool|string $verify = true;

    /**
     * @var array<string, Client>
     */
    private static array $clientPool = [];

    /**
     * Runtime mode override.
     */
    private static string $runtimeMode = self::RUNTIME_MODE_AUTO;

    /**
     * 发送 HTTP 请求.
     *
     * @param string $url 请求地址
     * @param string $method HTTP 方法
     * @param null|array<string, mixed>|string $postFields 请求体
     * @param array<string, string> $headers 请求头
     * @param array<string, mixed> $runtimeConfig
     */
    public static function curl(
        string $url,
        string $method = 'GET',
        null|array|string $postFields = null,
        array $headers = [],
        array $runtimeConfig = []
    ): HttpResponse {
        $config = self::normalizeRuntimeConfig($runtimeConfig);
        $method = strtoupper($method);

        if (self::shouldUseUploadRequestPath($method, $postFields)) {
            return self::sendUploadRequest($url, $method, $postFields, $headers, $config);
        }

        $runtimeMode = self::resolveRuntimeMode($config);
        $client = self::getClient($config, $runtimeMode);
        $options = self::buildStandardRequestOptions($method, $postFields, $headers, $config);

        try {
            $response = $client->request($method, $url, $options);

            $httpResponse = new HttpResponse();
            $httpResponse->setBody((string) $response->getBody());
            $httpResponse->setStatus($response->getStatusCode());

            return $httpResponse;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response?->getStatusCode();
            $responseBody = $response !== null ? (string) $response->getBody() : null;
            $message = 'HTTP Request Error: ' . $e->getMessage();

            if ($statusCode !== null) {
                $message .= ' (HTTP ' . $statusCode . ')';
            }

            if ($responseBody !== null && $responseBody !== '') {
                $message .= ' Response: ' . $responseBody;
            }

            $exception = new OceanEngineException(
                $message,
                $statusCode ?? ($e->getCode() ?: 400)
            );
            $exception->setHttpStatus($statusCode);
            $exception->setResponseBody($responseBody);

            throw $exception;
        }
    }

    /**
     * 发送上传请求。
     *
     * @param array<string, mixed>|string|null $postFields
     * @param array<string, string> $headers
     * @param array<string, mixed> $config
     */
    private static function sendUploadRequest(
        string $url,
        string $method,
        null|array|string $postFields,
        array $headers,
        array $config
    ): HttpResponse {
        if (! is_array($postFields)) {
            throw new InvalidParamException('Upload request post fields must be an array.', 400);
        }

        $multipart = self::buildUploadMultipartData($postFields);
        $client = self::createUploadClient($config);
        $options = self::buildUploadRequestOptions($multipart, $headers, $config);

        try {
            $response = $client->request($method, $url, $options);
            $httpResponse = new HttpResponse();
            $httpResponse->setBody((string) $response->getBody());
            $httpResponse->setStatus($response->getStatusCode());

            return $httpResponse;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response?->getStatusCode();
            $responseBody = $response !== null ? (string) $response->getBody() : null;
            $message = 'HTTP Request Error: ' . $e->getMessage();

            if ($statusCode !== null) {
                $message .= ' (HTTP ' . $statusCode . ')';
            }

            if ($responseBody !== null && $responseBody !== '') {
                $message .= ' Response: ' . $responseBody;
            }

            $exception = new OceanEngineException(
                $message,
                $statusCode ?? ($e->getCode() ?: 400)
            );
            $exception->setHttpStatus($statusCode);
            $exception->setResponseBody($responseBody);

            throw $exception;
        } finally {
            self::closeMultipartStreams($multipart);
        }
    }

    /**
     * 构建 JSON 返回体（可用于测试或 API 输出）.
     *
     * @param null|array<string, mixed> $data 数据体
     * @param string $msg 提示消息
     * @param int $code 状态码
     */
    public static function renderJSON(?array $data = [], string $msg = 'ok', int $code = 200): string
    {
        return json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 设置默认重试配置（仅作用于未传 runtimeConfig 的请求）.
     *
     * @param int $maxRetries 最大重试次数
     * @param int $retryDelay 重试基础间隔（毫秒）
     * @param array<int, int> $retryableStatusCodes 可重试 HTTP 状态码
     * @param bool $enableRetry 是否启用重试
     * @param array<int, int> $retryableBusinessCodes 可重试业务状态码
     */
    public static function setRetryConfig(
        int $maxRetries,
        int $retryDelay,
        array $retryableStatusCodes = [],
        bool $enableRetry = true,
        array $retryableBusinessCodes = []
    ): void {
        self::$enableRetry = $enableRetry;
        self::$maxRetries = $maxRetries;
        self::$retryDelay = $retryDelay;
        if ($retryableStatusCodes !== []) {
            self::$retryableStatusCodes = $retryableStatusCodes;
        }
        if ($retryableBusinessCodes !== []) {
            self::$retryableBusinessCodes = $retryableBusinessCodes;
        }

        self::$clientPool = [];
    }

    /**
     * 启用或禁用默认重试机制.
     *
     * @param bool $enabled 是否启用重试
     */
    public static function setRetryEnabled(bool $enabled): void
    {
        self::$enableRetry = $enabled;
        self::$clientPool = [];
    }

    /**
     * 设置 TLS 证书校验策略。
     *
     * @param bool $enabled 是否启用证书校验
     * @param null|string $caPath CA 证书文件路径（仅在 enabled=true 时生效）
     */
    public static function setVerify(bool $enabled, ?string $caPath = null): void
    {
        self::$verify = self::buildVerifyValue($enabled, $caPath);
        self::$clientPool = [];
    }

    /**
     * 设置运行环境模式。
     *
     * 支持 auto/fpm/cli/swoole，默认 auto（自动识别）。
     *
     * @param string $mode 运行模式
     */
    public static function setRuntimeMode(string $mode): void
    {
        $normalizedMode = self::normalizeRuntimeMode($mode);
        if ($normalizedMode === null) {
            throw new \InvalidArgumentException('Unsupported runtime mode. Allowed values: auto, fpm, cli, swoole.');
        }

        self::$runtimeMode = $normalizedMode;
        self::$clientPool = [];
    }

    /**
     * 获取当前生效的运行模式。
     */
    public static function getRuntimeMode(): string
    {
        return self::resolveRuntimeMode();
    }

    /**
     * @param array<string, mixed> $config
     */
    private static function getClient(array $config, string $runtimeMode): Client
    {
        if (! self::shouldUseClientPool($config, $runtimeMode)) {
            return self::createClient($config);
        }

        $cacheKey = self::buildClientCacheKey($config, $runtimeMode);

        if (! isset(self::$clientPool[$cacheKey])) {
            if (count(self::$clientPool) >= self::CLIENT_POOL_MAX_SIZE) {
                array_shift(self::$clientPool);
            }

            self::$clientPool[$cacheKey] = self::createClient($config);
        }

        return self::$clientPool[$cacheKey];
    }

    /**
     * @param array<string, mixed> $config
     */
    private static function createClient(array $config): Client
    {
        $stack = HandlerStack::create();

        if ($config['enable_retry']) {
            $stack->push(self::createRetryMiddleware($config));
        }

        $stack->push(self::createLogMiddleware());

        return new Client([
            'handler' => $stack,
            'verify' => $config['verify'],
        ]);
    }

    /**
     * 上传请求使用独立的 Guzzle Client，避免复用连接、流和重试中间件。
     *
     * @param array<string, mixed> $config
     */
    private static function createUploadClient(array $config): Client
    {
        $stack = HandlerStack::create();
        $stack->push(self::createLogMiddleware());

        return new Client([
            'handler' => $stack,
            'verify' => $config['verify'],
            'http_errors' => false,
            'allow_redirects' => false,
        ]);
    }

    /**
     * @param array<string, mixed> $config
     */
    private static function buildClientCacheKey(array $config, string $runtimeMode): string
    {
        return md5((string) json_encode([
            'runtime_mode' => $runtimeMode,
            'enable_retry' => $config['enable_retry'],
            'max_retries' => $config['max_retries'],
            'retry_delay' => $config['retry_delay'],
            'retryable_status_codes' => $config['retryable_status_codes'],
            'retryable_business_codes' => $config['retryable_business_codes'],
            'verify' => $config['verify'],
        ], JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param array<string, mixed> $config
     */
    private static function shouldUseClientPool(array $config, string $runtimeMode): bool
    {
        if (array_key_exists('reuse_client', $config)) {
            return (bool) $config['reuse_client'];
        }

        // 协程常驻环境下默认禁用全局复用，避免跨协程共享状态。
        if ($runtimeMode === self::RUNTIME_MODE_SWOOLE) {
            return false;
        }

        return true;
    }

    /**
     * @param array<string, mixed> $config
     */
    private static function createRetryMiddleware(array $config): callable
    {
        return Middleware::retry(
            function (
                $retries,
                RequestInterface $request,
                ?ResponseInterface $response = null,
                ?\Exception $exception = null
            ) use ($config): bool {
                if ($retries >= $config['max_retries']) {
                    return false;
                }

                if ($exception instanceof ConnectException) {
                    return true;
                }

                if ($exception instanceof ServerException) {
                    return true;
                }

                if ($response && in_array($response->getStatusCode(), $config['retryable_status_codes'], true)) {
                    return true;
                }

                if ($response && $response->getStatusCode() === 200) {
                    try {
                        $body = (string) $response->getBody();
                        $data = json_decode($body, true);

                        if (is_array($data) && isset($data['code']) && is_numeric($data['code'])) {
                            $businessCode = (int) $data['code'];

                            if ($businessCode === 0) {
                                return false;
                            }

                            if (in_array($businessCode, $config['retryable_business_codes'], true)) {
                                return true;
                            }

                            if ($businessCode >= 50000 && $businessCode < 60000) {
                                return true;
                            }
                        }
                    } catch (\Exception $e) {
                        return false;
                    }
                }

                return false;
            },
            function ($retries) use ($config): int {
                return (int) ($config['retry_delay'] * pow(2, max(0, $retries - 1)));
            }
        );
    }

    /**
     * 创建日志中间件.
     */
    private static function createLogMiddleware(): callable
    {
        return Middleware::log(
            new NullLogger(),
            new MessageFormatter(
                "HTTP Request: {method} {uri}\n"
                . "HTTP Response: {code} {phrase}\n"
                . "Request Time: {req_time}s\n"
                . 'Response Time: {res_time}s'
            ),
            'info'
        );
    }

    /**
     * @param null|array<string, mixed>|string $postFields
     * @param array<string, string> $headers
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    private static function buildStandardRequestOptions(
        string $method,
        null|array|string $postFields,
        array $headers,
        array $config
    ): array {
        $options = [
            'headers' => $headers,
            'timeout' => $config['read_timeout'],
            'connect_timeout' => $config['connect_timeout'],
        ];

        if (! in_array($method, ['POST', 'PUT', 'PATCH'], true) || $postFields === null) {
            return $options;
        }

        if (is_array($postFields)) {
            $options['form_params'] = $postFields;
            return $options;
        }

        $options['body'] = $postFields;

        return $options;
    }

    /**
     * @param array<int, array<string, mixed>> $multipart
     * @param array<string, string> $headers
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    private static function buildUploadRequestOptions(array $multipart, array $headers, array $config): array
    {
        return [
            'headers' => self::withoutHeader($headers, 'Content-Type'),
            'multipart' => $multipart,
            'timeout' => $config['read_timeout'],
            'connect_timeout' => $config['connect_timeout'],
            'http_errors' => false,
            'allow_redirects' => false,
        ];
    }

    /**
     * @param array<string, mixed> $runtimeConfig
     * @return array<string, mixed>
     */
    private static function normalizeRuntimeConfig(array $runtimeConfig): array
    {
        $readTimeout = self::$readTimeout;
        if (array_key_exists('read_timeout', $runtimeConfig)) {
            $readTimeout = max(1, (int) $runtimeConfig['read_timeout']);
        }

        $connectTimeout = self::$connectTimeout;
        if (array_key_exists('connect_timeout', $runtimeConfig)) {
            $connectTimeout = max(1, (int) $runtimeConfig['connect_timeout']);
        }

        $enableRetry = self::$enableRetry;
        if (array_key_exists('enable_retry', $runtimeConfig)) {
            $enableRetry = (bool) $runtimeConfig['enable_retry'];
        }

        $maxRetries = self::$maxRetries;
        if (array_key_exists('max_retries', $runtimeConfig)) {
            $maxRetries = max(0, (int) $runtimeConfig['max_retries']);
        }

        $retryDelay = self::$retryDelay;
        if (array_key_exists('retry_delay', $runtimeConfig)) {
            $retryDelay = max(0, (int) $runtimeConfig['retry_delay']);
        }

        $runtimeMode = self::normalizeRuntimeMode($runtimeConfig['runtime_mode'] ?? null);

        $result = [
            'read_timeout' => $readTimeout,
            'connect_timeout' => $connectTimeout,
            'enable_retry' => $enableRetry,
            'max_retries' => $maxRetries,
            'retry_delay' => $retryDelay,
            'verify' => self::normalizeVerifyValue($runtimeConfig['verify'] ?? null, self::$verify),
            'retryable_status_codes' => self::normalizeIntArray(
                $runtimeConfig['retryable_status_codes'] ?? null,
                self::$retryableStatusCodes
            ),
            'retryable_business_codes' => self::normalizeIntArray(
                $runtimeConfig['retryable_business_codes'] ?? null,
                self::$retryableBusinessCodes
            ),
        ];

        if ($runtimeMode !== null) {
            $result['runtime_mode'] = $runtimeMode;
        }

        if (array_key_exists('reuse_client', $runtimeConfig)) {
            $result['reuse_client'] = (bool) $runtimeConfig['reuse_client'];
        }

        return $result;
    }

    /**
     * @param mixed $value
     * @param array<int, int> $default
     * @return array<int, int>
     */
    private static function normalizeIntArray($value, array $default): array
    {
        if (! is_array($value)) {
            return $default;
        }

        $normalized = [];
        foreach ($value as $item) {
            if (is_int($item) || (is_string($item) && is_numeric($item))) {
                $normalized[] = (int) $item;
            }
        }

        return $normalized === [] ? $default : array_values(array_unique($normalized));
    }

    /**
     * @param mixed $value
     * @param bool|string $default
     */
    private static function normalizeVerifyValue(mixed $value, bool|string $default): bool|string
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            return $trimmed === '' ? $default : $trimmed;
        }

        return $default;
    }

    private static function buildVerifyValue(bool $enabled, ?string $caPath = null): bool|string
    {
        if (! $enabled) {
            return false;
        }

        if (is_string($caPath)) {
            $trimmed = trim($caPath);
            if ($trimmed !== '') {
                return $trimmed;
            }
        }

        return true;
    }

    /**
     * 从请求头中移除指定字段名（大小写不敏感）。
     *
     * @param array<string, string> $headers
     * @return array<string, string>
     */
    private static function withoutHeader(array $headers, string $headerName): array
    {
        foreach ($headers as $name => $_value) {
            if (strcasecmp($name, $headerName) === 0) {
                unset($headers[$name]);
            }
        }

        return $headers;
    }

    /**
     * @param null|array<string, mixed> $runtimeConfig
     */
    private static function resolveRuntimeMode(?array $runtimeConfig = null): string
    {
        if ($runtimeConfig !== null && isset($runtimeConfig['runtime_mode'])) {
            return (string) $runtimeConfig['runtime_mode'];
        }

        if (self::$runtimeMode !== self::RUNTIME_MODE_AUTO) {
            return self::$runtimeMode;
        }

        $envRuntimeMode = self::normalizeRuntimeMode(getenv('OCEANENGINE_RUNTIME_MODE') ?: null);
        if ($envRuntimeMode !== null && $envRuntimeMode !== self::RUNTIME_MODE_AUTO) {
            return $envRuntimeMode;
        }

        if (self::isSwooleRuntime()) {
            return self::RUNTIME_MODE_SWOOLE;
        }

        if (self::isFpmRuntime()) {
            return self::RUNTIME_MODE_FPM;
        }

        return self::RUNTIME_MODE_CLI;
    }

    private static function normalizeRuntimeMode(mixed $mode): ?string
    {
        if (! is_string($mode)) {
            return null;
        }

        $normalized = strtolower(trim($mode));
        $allowed = [
            self::RUNTIME_MODE_AUTO,
            self::RUNTIME_MODE_FPM,
            self::RUNTIME_MODE_CLI,
            self::RUNTIME_MODE_SWOOLE,
        ];

        if (! in_array($normalized, $allowed, true)) {
            return null;
        }

        return $normalized;
    }

    private static function isFpmRuntime(): bool
    {
        return PHP_SAPI === 'fpm-fcgi' || PHP_SAPI === 'cgi-fcgi';
    }

    private static function isSwooleRuntime(): bool
    {
        if (! class_exists(Coroutine::class)) {
            return false;
        }

        try {
            return Coroutine::getCid() > -1;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * 构建 multipart 上传数据，每次请求都重新 fopen，避免常驻进程中复用 stream。
     *
     * @param array<string, mixed> $data
     * @return array<int, array<string, mixed>>
     */
    private static function buildUploadMultipartData(array $data): array
    {
        $multipart = [];

        foreach ($data as $key => $value) {
            if ($value instanceof \CURLFile) {
                $filename = $value->getFilename();
                if ($filename === '') {
                    throw new InvalidParamException(
                        'client-check-error:Invalid Arguments: the file of "' . $key . '" must provide a filename.',
                        41
                    );
                }

                if (! file_exists($filename)) {
                    throw new InvalidParamException(
                        'client-check-error:Invalid Arguments: the file of "' . $key . '" does not exist: ' . $filename,
                        41
                    );
                }

                $stream = fopen($filename, 'rb');
                if ($stream === false) {
                    throw new InvalidParamException(
                        'client-check-error:Invalid Arguments: the file of "' . $key . '" is not readable: ' . $filename,
                        41
                    );
                }

                $multipart[] = [
                    'name' => $key,
                    'contents' => $stream,
                    'filename' => $value->getPostFilename() !== '' ? $value->getPostFilename() : basename($filename),
                    'headers' => $value->getMimeType() !== '' ? [
                        'Content-Type' => $value->getMimeType(),
                    ] : [],
                ];
                continue;
            }

            if (is_string($value) && str_starts_with($value, '@')) {
                $path = substr($value, 1);
                if ($path === '') {
                    throw new InvalidParamException(
                        'client-check-error:Invalid Arguments: the file of "' . $key . '" can not be empty.',
                        41
                    );
                }

                if (! file_exists($path)) {
                    throw new InvalidParamException(
                        'client-check-error:Invalid Arguments: the file of "' . $key . '" does not exist: ' . $path,
                        41
                    );
                }

                $stream = fopen($path, 'rb');
                if ($stream === false) {
                    throw new InvalidParamException(
                        'client-check-error:Invalid Arguments: the file of "' . $key . '" is not readable: ' . $path,
                        41
                    );
                }

                $multipart[] = [
                    'name' => $key,
                    'contents' => $stream,
                    'filename' => basename($path),
                ];
                continue;
            }

            $multipart[] = [
                'name' => $key,
                'contents' => is_bool($value) ? ($value ? '1' : '0') : $value,
            ];
        }

        return $multipart;
    }

    /**
     * @param array<int, array<string, mixed>> $multipart
     */
    private static function closeMultipartStreams(array $multipart): void
    {
        foreach ($multipart as $part) {
            if (isset($part['contents']) && is_resource($part['contents'])) {
                fclose($part['contents']);
            }
        }
    }

    /**
     * @param null|array<string, mixed>|string $postFields
     */
    private static function shouldUseUploadRequestPath(string $method, null|array|string $postFields): bool
    {
        return in_array($method, ['POST', 'PUT', 'PATCH'], true)
            && is_array($postFields)
            && self::containsFile($postFields);
    }

    /**
     * 是否包含文件上传.
     *
     * @param array<string, mixed> $data
     */
    private static function containsFile(array $data): bool
    {
        foreach ($data as $value) {
            if ($value instanceof \CURLFile) {
                return true;
            }

            if (is_string($value) && str_starts_with($value, '@')) {
                return true;
            }
        }
        return false;
    }
}
