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

namespace Tests\Integration\Tools\DouYinInfluencerMgmt;

use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class DouYinInfluencerMgmtIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    /**
     * @dataProvider influencerRequestProvider
     *
     * @param callable(OceanEngineClient,int):object $requestBuilder
     */
    public function testInfluencerInterfacesSmoke(string $label, callable $requestBuilder): void
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
    public function influencerRequestProvider(): array
    {
        return [
            'tools_aweme_info_search' => [
                '查询抖音帐号和类目信息',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->Tools()
                    ->DouYinInfluencerMgmt
                    ->ToolsAwemeInfoSearch()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'tools_aweme_author_info_get' => [
                '查询抖音号ID对应的达人信息',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->Tools()
                    ->DouYinInfluencerMgmt
                    ->ToolsAwemeAuthorInfoGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'tools_aweme_similar_author_search' => [
                '查询抖音类似帐号',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->Tools()
                    ->DouYinInfluencerMgmt
                    ->ToolsAwemeSimilarAuthorSearch()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
        ];
    }
}
