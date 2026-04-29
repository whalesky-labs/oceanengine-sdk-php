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

namespace Tests\Integration\Account\AccountInfo;

use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class AccountInfoIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    /**
     * @dataProvider accountInfoRequestProvider
     *
     * @param callable(OceanEngineClient,int,array<int,int>):object $requestBuilder
     */
    public function testAccountInfoInterfacesSmoke(string $label, callable $requestBuilder): void
    {
        [$token, $advertiserId] = $this->resolveTokenAndAdvertiserId();
        $shopIds = $this->resolveShopIds();

        if ($token === '' || $advertiserId === '' || $shopIds === []) {
            self::markTestSkipped('Set TOKEN, ADVERTISER_ID (or ADVERTISER_IDS), and SHOP_ID (or SHOP_IDS) in .env.');
        }

        $payload = $this->runModuleSmokeRequest(
            $token,
            (int) $advertiserId,
            $label,
            static fn (OceanEngineClient $client, int $advId) => $requestBuilder($client, $advId, $shopIds)
        );

        self::assertArrayHasKey('code', $payload);
    }

    /**
     * @return array<string, array{0:string,1:callable(OceanEngineClient,int,array<int,int>):object}>
     */
    public function accountInfoRequestProvider(): array
    {
        return [
            'qianchuan_shop_get' => [
                '获取店铺账户信息',
                static fn (OceanEngineClient $client, int $advertiserId, array $shopIds) => $client->Account()
                    ->AccountInfo
                    ->QianchuanShopGet()
                    ->setParams([
                        'shop_ids' => json_encode($shopIds),
                    ]),
            ],
        ];
    }

    /**
     * @return array<int,int>
     */
    private function resolveShopIds(): array
    {
        $dotenv = $this->loadDotEnvValues();
        $rawValue = $this->firstAvailableEnvValue(['SHOP_IDS', 'SHOP_ID'], $dotenv);
        if ($rawValue === '') {
            return [];
        }

        $values = [];
        $trimmed = trim($rawValue);

        if (str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']')) {
            $decoded = json_decode($trimmed, true);
            if (is_array($decoded)) {
                $values = $decoded;
            }
        }

        if ($values === []) {
            $parts = str_contains($trimmed, ',') ? explode(',', $trimmed) : [$trimmed];
            $values = array_map('trim', $parts);
        }

        $shopIds = [];
        foreach ($values as $value) {
            if (! is_numeric((string) $value)) {
                continue;
            }

            $shopIds[] = (int) $value;
        }

        return array_values(array_unique($shopIds));
    }
}
