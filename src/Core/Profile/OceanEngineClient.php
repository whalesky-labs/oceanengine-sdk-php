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

namespace OceanEngineSDK;

use Core\Exception\InvalidParamException;
use Core\Exception\OceanEngineException;
use Core\Http\HttpRequest;
use Core\Http\HttpResponse;
use Core\Profile\ChainProxy;
use Core\Profile\RequestInterface;

/**
 * SDK client.
 */
class OceanEngineClient
{
    private string $accessToken;

    private string $serverUrl;

    private string $boxUrl;

    private bool $isSandbox;

    private int $connectTimeout;

    private int $defaultReadTimeout;

    private bool $retryEnabled;

    private int $maxRetries;

    private int $retryDelay;

    /**
     * @var array<int, int>
     */
    private array $retryableStatusCodes;

    /**
     * @var array<int, int>
     */
    private array $retryableBusinessCodes;

    /**
     * 顶层模块映射缓存（模块名 => 命名空间）.
     *
     * @var null|array<string, string>
     */
    private static ?array $moduleMap = null;

    /**
     * 构造函数，支持直接实例化.
     */
    public function __construct(
        string $accessToken,
        bool $isSandbox = false,
        ?string $serverUrl = null,
        ?string $boxUrl = null
    ) {
        $this->accessToken = $accessToken;
        $this->isSandbox = $isSandbox;
        $this->serverUrl = $serverUrl ?? 'https://api.oceanengine.com/open_api';
        $this->boxUrl = $boxUrl ?? 'https://api.oceanengine.com/open_api';
        $this->connectTimeout = HttpRequest::$connectTimeout;
        $this->defaultReadTimeout = HttpRequest::$readTimeout;
        $this->retryEnabled = HttpRequest::$enableRetry;
        $this->maxRetries = HttpRequest::$maxRetries;
        $this->retryDelay = HttpRequest::$retryDelay;
        $this->retryableStatusCodes = HttpRequest::$retryableStatusCodes;
        $this->retryableBusinessCodes = HttpRequest::$retryableBusinessCodes;
    }

    /**
     * 魔术方法，动态调用模块，返回模块实例.
     *
     * @param string $name 模块方法名
     * @param array<int, mixed> $arguments 传入参数（保留）
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call(string $name, array $arguments): mixed
    {
        $moduleMap = self::getModuleMap();

        if (! isset($moduleMap[$name])) {
            throw new \BadMethodCallException("未定义的方法 '{$name}'。");
        }

        return new ChainProxy($this, $moduleMap[$name]);
    }

    /**
     * 静态魔术方法，禁止调用（建议使用实例化调用模块）.
     *
     * @param string $name 模块方法名
     * @param array<int, mixed> $arguments 传入参数（保留）
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        throw new \BadMethodCallException("请先实例化客户端再调用模块，例如：\$client = new OceanEngineClient(TOKEN); \$client->{$name}();");
    }

    /**
     * 执行 HTTP 请求并返回响应.
     *
     * @throws OceanEngineException
     */
    public function execute(RequestInterface $request, ?string $url = null): HttpResponse
    {
        $request->check();
        $params = $request->getParams();
        $headers = [
            'Access-Token' => $this->accessToken,
            'Content-Type' => $request->getContentType(),
        ];

        if ($url === null) {
            $url = $request->getUrl();
            if ($url === '') {
                throw new InvalidParamException('HTTP URL 不能为空');
            }
            if (! str_starts_with($url, 'http')) {
                $url = ($this->isSandbox ? $this->boxUrl : $this->serverUrl) . $url;
            }
        }

        if ($request->getMethod() === 'GET') {
            $url .= '?' . http_build_query($params);
            $params = null;
        }

        if (str_contains($request->getContentType(), 'json')) {
            $encodedParams = json_encode($params);
            if ($encodedParams === false) {
                throw new InvalidParamException('请求参数 JSON 编码失败: ' . json_last_error_msg(), 400);
            }
            $params = $encodedParams;
        }

        return HttpRequest::curl(
            $url,
            $request->getMethod(),
            $params,
            $headers,
            $this->buildRuntimeHttpConfig($request->getTimeout())
        );
    }

    /**
     * 备用调用模块接口方法（非静态）.
     */
    public function module(string $name): ChainProxy
    {
        $moduleMap = self::getModuleMap();

        if (! isset($moduleMap[$name])) {
            throw new \InvalidArgumentException("模块 {$name} 不存在。");
        }

        return new ChainProxy($this, $moduleMap[$name]);
    }

    public function Account(): ChainProxy
    {
        return $this->module('Account');
    }

    public function DataReports(): ChainProxy
    {
        return $this->module('DataReports');
    }

    public function EnterpriseAccount(): ChainProxy
    {
        return $this->module('EnterpriseAccount');
    }

    public function JuLiangAds(): ChainProxy
    {
        return $this->module('JuLiangAds');
    }

    public function JuLiangLocalPush(): ChainProxy
    {
        return $this->module('JuLiangLocalPush');
    }

    public function JuLiangQianChuan(): ChainProxy
    {
        return $this->module('JuLiangQianChuan');
    }

    public function JuLiangStarMap(): ChainProxy
    {
        return $this->module('JuLiangStarMap');
    }

    public function Materials(): ChainProxy
    {
        return $this->module('Materials');
    }

    public function Tools(): ChainProxy
    {
        return $this->module('Tools');
    }

    /**
     * 配置当前客户端实例的重试机制.
     */
    public function setRetryConfig(
        int $maxRetries = 3,
        int $retryDelay = 1000,
        array $retryableStatusCodes = [408, 429, 500, 502, 503, 504],
        bool $enableRetry = true,
        array $retryableBusinessCodes = [40100, 40110, 50000]
    ): self {
        $this->maxRetries = $maxRetries;
        $this->retryDelay = $retryDelay;
        $this->retryEnabled = $enableRetry;
        $this->retryableStatusCodes = $retryableStatusCodes;
        $this->retryableBusinessCodes = $retryableBusinessCodes;
        return $this;
    }

    /**
     * 设置当前客户端实例的重试开关.
     */
    public function setRetryEnabled(bool $enabled): self
    {
        $this->retryEnabled = $enabled;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildRuntimeHttpConfig(int $requestTimeout): array
    {
        return [
            'connect_timeout' => $this->connectTimeout,
            'read_timeout' => $requestTimeout > 0 ? $requestTimeout : $this->defaultReadTimeout,
            'enable_retry' => $this->retryEnabled,
            'max_retries' => $this->maxRetries,
            'retry_delay' => $this->retryDelay,
            'retryable_status_codes' => $this->retryableStatusCodes,
            'retryable_business_codes' => $this->retryableBusinessCodes,
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function getModuleMap(): array
    {
        if (self::$moduleMap !== null) {
            return self::$moduleMap;
        }

        $apiDir = dirname(__DIR__, 2) . '/Api';
        $moduleMap = [];

        $items = scandir($apiDir);
        if ($items === false) {
            self::$moduleMap = $moduleMap;
            return $moduleMap;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $moduleDir = $apiDir . '/' . $item;
            if (! is_dir($moduleDir)) {
                continue;
            }

            // 限制为合法模块名，避免特殊目录污染模块入口。
            if (! preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $item)) {
                continue;
            }

            $moduleMap[$item] = 'Api\\' . $item;
        }

        ksort($moduleMap);
        self::$moduleMap = $moduleMap;

        return $moduleMap;
    }
}
