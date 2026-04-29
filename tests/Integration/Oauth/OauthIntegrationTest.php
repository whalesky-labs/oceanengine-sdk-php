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

namespace Tests\Integration\Oauth;

use OceanEngineSDK\OceanEngineAuth;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class OauthIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    /**
     * 输出并校验授权 URL 的拼装结果。
     */
    public function testGetAuthCodeUrlOutput(): void
    {
        [$appId, $secret] = $this->resolveGetAuthCodeUrlEnv();

        if ($appId === '' || $secret === '') {
            self::markTestSkipped('Set APPID and SECRET in .env.');
        }

        $auth = new OceanEngineAuth($appId, $secret);
        $url = $auth->getAuthCodeUrl(
            'https://example.com/callback?foo=bar',
            'unused_scope',
            'custom_state'
        );

        $this->printIntegrationOutput('获取应用授权URL', $url);

        $parts = parse_url($url);
        self::assertSame('https', $parts['scheme'] ?? null);
        self::assertSame('qianchuan.jinritemai.com', $parts['host'] ?? null);
        self::assertSame('/openapi/qc/audit/oauth.html', $parts['path'] ?? null);

        parse_str($parts['query'] ?? '', $query);
        self::assertSame($appId, $query['app_id'] ?? null);
        self::assertSame('custom_state', $query['state'] ?? null);
        self::assertSame('1', $query['material_auth'] ?? null);
        self::assertSame('https://example.com/callback?foo=bar', $query['redirect_uri'] ?? null);
    }

    /**
     * 调用授权码换取 access_token 并输出接口响应。
     */
    public function testGetAccessTokenResponse(): void
    {
        [$appId, $secret, $authCode] = $this->resolveGetAccessTokenEnv();

        if ($appId === '' || $secret === '' || $authCode === '') {
            self::markTestSkipped('Set APPID, SECRET and AUTH_CODE in .env.');
        }

        $payload = $this->runWithNetworkGuard(function () use ($appId, $secret, $authCode): array {
            $auth = new OceanEngineAuth($appId, $secret);
            return $auth->getAccessToken($authCode);
        });

        $this->printIntegrationOutput('获取Access Token', $payload);

        self::assertIsArray($payload);
        self::assertArrayHasKey('code', $payload);
    }

    /**
     * 调用刷新 token 接口并输出响应。
     */
    public function testRefreshTokenResponse(): void
    {
        [$appId, $secret, $refreshToken] = $this->resolveRefreshTokenEnv();

        if ($appId === '' || $secret === '' || $refreshToken === '') {
            self::markTestSkipped('Set APPID, SECRET and REFRESH_TOKEN in .env.');
        }

        $payload = $this->runWithNetworkGuard(function () use ($appId, $secret, $refreshToken): array {
            $auth = new OceanEngineAuth($appId, $secret);
            return $auth->refreshToken($refreshToken);
        });

        $this->printIntegrationOutput('刷新Refresh Token', $payload);

        self::assertIsArray($payload);
        self::assertArrayHasKey('code', $payload);
    }

    /**
     * 读取 getAccessToken 测试所需环境变量。
     *
     * @return array{0:string,1:string,2:string}
     */
    private function resolveGetAccessTokenEnv(): array
    {
        [$appId, $secret, $authCode] = $this->resolveOauthCredentials();
        return [$appId, $secret, $authCode];
    }

    /**
     * 读取 getAuthCodeUrl 测试所需环境变量。
     *
     * @return array{0:string,1:string}
     */
    private function resolveGetAuthCodeUrlEnv(): array
    {
        [$appId, $secret] = $this->resolveOauthCredentials();
        return [$appId, $secret];
    }

    /**
     * 读取 refreshToken 测试所需环境变量。
     *
     * @return array{0:string,1:string,2:string}
     */
    private function resolveRefreshTokenEnv(): array
    {
        [$appId, $secret, , $refreshToken] = $this->resolveOauthCredentials();
        return [$appId, $secret, $refreshToken];
    }
}
