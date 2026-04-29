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

namespace Tests\Integration\JuLiangStarMap\MassiveStarMap;

use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class MassiveStarMapIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    /**
     * @dataProvider massiveStarMapRequestProvider
     *
     * @param callable(OceanEngineClient,int):object $requestBuilder
     */
    public function testMassiveStarMapInterfacesSmoke(string $label, callable $requestBuilder): void
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
    public function massiveStarMapRequestProvider(): array
    {
        return [
            'star_clue_get' => [
                '获取星图订单投后线索',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangStarMap()
                    ->MassiveStarMap
                    ->StarClueGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'star_demand_list' => [
                '获取星图客户任务列表',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangStarMap()
                    ->MassiveStarMap
                    ->StarDemandList()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'star_demand_order_list' => [
                '获取星图客户任务订单列表',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangStarMap()
                    ->MassiveStarMap
                    ->StarDemandOrderList()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
        ];
    }
}
