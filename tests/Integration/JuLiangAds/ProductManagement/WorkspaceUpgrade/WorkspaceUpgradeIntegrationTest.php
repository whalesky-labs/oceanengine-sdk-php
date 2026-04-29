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

namespace Tests\Integration\JuLiangAds\ProductManagement\WorkspaceUpgrade;

use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class WorkspaceUpgradeIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    /**
     * @dataProvider workspaceUpgradeRequestProvider
     *
     * @param callable(OceanEngineClient,int):object $requestBuilder
     */
    public function testWorkspaceUpgradeInterfacesSmoke(string $label, callable $requestBuilder): void
    {
        [$token, $advertiserId] = $this->resolveTokenAndAdvertiserId();

        if ($token === '' || $advertiserId === '') {
            self::markTestSkipped('Set TOKEN and ADVERTISER_ID (or ADVERTISER_IDS) in .env.');
        }

        $payload = $this->runModuleSmokeRequest($token, (int) $advertiserId, $label, $requestBuilder);
        self::assertArrayHasKey('code', $payload);
    }

    /**
     * @return array<string, array{0:string,1:callable(OceanEngineClient,int):object}>
     */
    public function workspaceUpgradeRequestProvider(): array
    {
        return [
            'dpa_library_list' => [
                '获取商品库列表（升级版工作台）',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangAds()
                    ->WorkspaceUpgrade
                    ->DpaLibraryList()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'dpa_meta_get' => [
                '获取商品库元数据（升级版工作台）',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangAds()
                    ->WorkspaceUpgrade
                    ->DpaMetaGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'dpa_dict_get' => [
                '获取商品库字典（升级版工作台）',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangAds()
                    ->WorkspaceUpgrade
                    ->DpaDictGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
        ];
    }
}
