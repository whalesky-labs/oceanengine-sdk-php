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

namespace Tests\Integration\JuLiangQianChuan\AdvertisingMgmt\AccountBudget;

use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class AccountBudgetIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    /**
     * @dataProvider accountBudgetRequestProvider
     *
     * @param callable(OceanEngineClient,int):object $requestBuilder
     */
    public function testAccountBudgetInterfacesSmoke(string $label, callable $requestBuilder): void
    {
        [$token, $advertiserId] = $this->resolveTokenAndAdvertiserId();

        if ($token === '' || $advertiserId === '') {
            self::markTestSkipped('Set TOKEN and ADVERTISER_ID (or ADVERTISER_IDS) in .env.');
        }

        $payload = $this->runModuleSmokeRequest($token, (int) $advertiserId, $label, $requestBuilder);
        self::assertArrayHasKey('code', $payload);
    }

    public function testAccountBudgetUpdateIsSkippedForSafety(): void
    {
        self::markTestSkipped('更新账户日预算属于写操作，默认不在集成烟雾测试中执行。');
    }

    /**
     * @return array<string, array{0:string,1:callable(OceanEngineClient,int):object}>
     */
    public function accountBudgetRequestProvider(): array
    {
        return [
            'account_budget_get' => [
                '获取账户日预算',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangQianChuan()
                    ->AccountBudget
                    ->AccountBudgetGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
        ];
    }
}
