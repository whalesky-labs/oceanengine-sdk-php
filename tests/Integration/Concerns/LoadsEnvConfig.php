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

namespace Tests\Integration\Concerns;

use Core\Http\HttpResponse;
use OceanEngineSDK\OceanEngineClient;

trait LoadsEnvConfig
{
    /**
     * 统一输出集成测试信息，JSON 内容会自动美化。
     *
     * @param array<mixed>|string $payload
     */
    private function printIntegrationOutput(string $label, array|string $payload): void
    {
        fwrite(
            STDOUT,
            sprintf(
                "\n[Integration] %s:\n%s\n",
                $label,
                $this->formatIntegrationPayload($payload)
            )
        );
    }

    /**
     * 读取 TOKEN 与广告主 ID 配置。
     *
     * @return array{0:string,1:string}
     */
    private function resolveTokenAndAdvertiserId(): array
    {
        $dotenv = $this->loadDotEnvValues();

        $token = $this->firstAvailableEnvValue(['TOKEN'], $dotenv);
        $advertiserId = $this->normalizeAdvertiserId($this->firstAvailableEnvValue(
            ['ADVERTISER_ID', 'ADVERTISER_IDS'],
            $dotenv
        ));

        return [$token, $advertiserId];
    }

    /**
     * 读取 OAuth 相关配置。
     *
     * @return array{0:string,1:string,2:string,3:string}
     */
    private function resolveOauthCredentials(): array
    {
        $dotenv = $this->loadDotEnvValues();

        $appId = $this->firstAvailableEnvValue(['APPID'], $dotenv);
        $secret = $this->firstAvailableEnvValue(['SECRET'], $dotenv);
        $authCode = $this->firstAvailableEnvValue(['AUTH_CODE'], $dotenv);
        $refreshToken = $this->firstAvailableEnvValue(['REFRESH_TOKEN'], $dotenv);

        return [$appId, $secret, $authCode, $refreshToken];
    }

    /**
     * 读取视频上传测试文件路径。
     */
    private function resolveVideoFilePath(): string
    {
        $dotenv = $this->loadDotEnvValues();

        return $this->firstAvailableEnvValue(['VIDEO_FILE_PATH'], $dotenv);
    }

    /**
     * 从运行时环境变量或 .env 中按顺序取值。
     *
     * @param array<string> $keys
     * @param array<string, string> $dotenv
     */
    private function firstAvailableEnvValue(array $keys, array $dotenv): string
    {
        foreach ($keys as $key) {
            $runtime = getenv($key);
            if (is_string($runtime) && trim($runtime) !== '') {
                return trim($runtime);
            }

            if (isset($dotenv[$key]) && trim($dotenv[$key]) !== '') {
                return trim($dotenv[$key]);
            }
        }

        return '';
    }

    /**
     * 加载项目根目录 .env 文件。
     *
     * @return array<string, string>
     */
    private function loadDotEnvValues(): array
    {
        $path = dirname(__DIR__, 3) . '/.env';
        if (! is_file($path)) {
            return [];
        }

        $values = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return [];
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (str_starts_with($line, 'export ')) {
                $line = trim(substr($line, 7));
            }

            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            $value = trim($parts[1]);
            if ($key === '') {
                continue;
            }

            if ((str_starts_with($value, '"') && str_ends_with($value, '"'))
                || (str_starts_with($value, '\'') && str_ends_with($value, '\''))) {
                $value = substr($value, 1, -1);
            }

            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * 归一化广告主 ID，兼容数组和逗号分隔格式。
     */
    private function normalizeAdvertiserId(string $rawValue): string
    {
        $rawValue = trim($rawValue);
        if ($rawValue === '') {
            return '';
        }

        // Support JSON array style, e.g. [1805994941666500]
        if (str_starts_with($rawValue, '[') && str_ends_with($rawValue, ']')) {
            $decoded = json_decode($rawValue, true);
            if (is_array($decoded) && isset($decoded[0])) {
                return trim((string) $decoded[0]);
            }

            $rawValue = trim($rawValue, '[]');
        }

        if (str_contains($rawValue, ',')) {
            $parts = array_filter(array_map('trim', explode(',', $rawValue)));
            if ($parts === []) {
                return '';
            }
            return (string) array_values($parts)[0];
        }

        return $rawValue;
    }

    /**
     * 包装网络调用，遇到网络不可用时跳过测试。
     *
     * @template T
     * @param callable():T $callback
     * @return T
     */
    private function runWithNetworkGuard(callable $callback): mixed
    {
        try {
            return $callback();
        } catch (\Throwable $e) {
            $message = strtolower($e->getMessage());
            $networkKeywords = [
                'could not resolve host',
                'connection timed out',
                'failed to connect',
                'connection refused',
                'network is unreachable',
                'operation timed out',
            ];

            foreach ($networkKeywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    self::markTestSkipped('Network unavailable for integration test: ' . $e->getMessage());
                }
            }

            throw $e;
        }
    }

    /**
     * @param array<mixed>|string $payload
     */
    private function formatIntegrationPayload(array|string $payload): string
    {
        if (is_array($payload)) {
            return $this->encodeJsonForDisplay($payload);
        }

        $normalized = str_replace(["\r\n", "\r"], "\n", trim($payload));
        if ($normalized === '') {
            return '(empty)';
        }

        $decoded = json_decode($normalized, true);
        if (is_array($decoded)) {
            return $this->encodeJsonForDisplay($decoded);
        }

        return $normalized;
    }

    /**
     * @param array<mixed> $payload
     */
    private function encodeJsonForDisplay(array $payload): string
    {
        $encoded = json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );

        return is_string($encoded) ? $encoded : '[Unable to encode payload]';
    }

    /**
     * 输出并校验集成测试 HTTP 响应。
     *
     * @return array<string, mixed>
     */
    private function assertIntegrationHttpResponse(HttpResponse $response, string $label): array
    {
        $this->printIntegrationOutput($label, $response->getBody());

        $body = trim($response->getBody());
        self::assertNotSame('', $body, '响应体不应为空。');

        $payload = json_decode($body, true);
        self::assertIsArray($payload, '响应体应为 JSON 对象。');
        self::assertArrayHasKey('code', $payload, '响应体应包含 code 字段。');

        return $payload;
    }

    /**
     * @param callable(OceanEngineClient,int):object $requestBuilder
     * @return array<string, mixed>
     */
    private function runModuleSmokeRequest(
        string $token,
        int $advertiserId,
        string $label,
        callable $requestBuilder
    ): array {
        $response = $this->runWithNetworkGuard(function () use ($token, $advertiserId, $requestBuilder): HttpResponse {
            $client = new OceanEngineClient($token);
            $request = $requestBuilder($client, $advertiserId);

            return $request->send();
        });

        return $this->assertIntegrationHttpResponse($response, $label);
    }
}
