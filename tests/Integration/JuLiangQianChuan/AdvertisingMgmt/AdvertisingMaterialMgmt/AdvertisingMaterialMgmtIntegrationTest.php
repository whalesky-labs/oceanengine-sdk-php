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

namespace Tests\Integration\JuLiangQianChuan\AdvertisingMgmt\AdvertisingMaterialMgmt;

use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class AdvertisingMaterialMgmtIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    /**
     * @dataProvider materialRequestProvider
     *
     * @param callable(OceanEngineClient,int):object $requestBuilder
     */
    public function testAdvertisingMaterialInterfacesSmoke(string $label, callable $requestBuilder): void
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
    public function materialRequestProvider(): array
    {
        return [
            'material_get' => [
                '获取账户下素材列表',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangQianChuan()
                    ->AdvertisingMaterialMgmt
                    ->MaterialGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'material_ad_get' => [
                '获取素材关联计划',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangQianChuan()
                    ->AdvertisingMaterialMgmt
                    ->MaterialAdGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'ad_material_get' => [
                '获取计划下素材列表',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangQianChuan()
                    ->AdvertisingMaterialMgmt
                    ->AdMaterialGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
        ];
    }
}
