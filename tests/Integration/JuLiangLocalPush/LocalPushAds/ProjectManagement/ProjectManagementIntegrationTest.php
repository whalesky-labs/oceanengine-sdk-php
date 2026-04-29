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

namespace Tests\Integration\JuLiangLocalPush\LocalPushAds\ProjectManagement;

use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class ProjectManagementIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    /**
     * @dataProvider projectManagementRequestProvider
     *
     * @param callable(OceanEngineClient,int):object $requestBuilder
     */
    public function testProjectManagementInterfacesSmoke(string $label, callable $requestBuilder): void
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
    public function projectManagementRequestProvider(): array
    {
        return [
            'local_project_list' => [
                '获取项目列表',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangLocalPush()
                    ->ProjectManagement
                    ->LocalProjectList()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                        'page' => 1,
                        'page_size' => 10,
                    ]),
            ],
            'local_poi_get' => [
                '获取可投门店列表',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangLocalPush()
                    ->ProjectManagement
                    ->LocalPoiGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'local_aweme_authorized_get' => [
                '获取本地推创编可用抖音号',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->JuLiangLocalPush()
                    ->ProjectManagement
                    ->LocalAwemeAuthorizedGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
        ];
    }
}
