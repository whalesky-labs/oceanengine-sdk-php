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

namespace Tests\Integration\DataReports\AdsProDataReports;

use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class AdsProDataReportsIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    /**
     * @dataProvider adsProReportRequestProvider
     *
     * @param callable(OceanEngineClient,int,string):object $requestBuilder
     */
    public function testAdsProReportInterfacesSmoke(string $label, callable $requestBuilder): void
    {
        [$token, $advertiserId] = $this->resolveTokenAndAdvertiserId();

        if ($token === '' || $advertiserId === '') {
            self::markTestSkipped('Set TOKEN and ADVERTISER_ID (or ADVERTISER_IDS) in .env.');
        }

        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $payload = $this->runModuleSmokeRequest(
            $token,
            (int) $advertiserId,
            $label,
            static fn (OceanEngineClient $client, int $advId) => $requestBuilder($client, $advId, $yesterday)
        );

        self::assertArrayHasKey('code', $payload);
    }

    /**
     * @return array<string, array{0:string,1:callable(OceanEngineClient,int,string):object}>
     */
    public function adsProReportRequestProvider(): array
    {
        return [
            'report_advertiser_get' => [
                '广告主数据',
                static fn (OceanEngineClient $client, int $advertiserId, string $date) => $client->DataReports()
                    ->AdsProDataReports
                    ->ReportAdvertiserGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                        'start_date' => $date,
                        'end_date' => $date,
                    ]),
            ],
            'report_custom_config_get' => [
                '获取自定义报表可用指标和维度',
                static fn (OceanEngineClient $client, int $advertiserId, string $date) => $client->DataReports()
                    ->AdsProDataReports
                    ->ReportCustomConfigGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                        'date' => $date,
                    ]),
            ],
            'report_custom_get' => [
                '自定义报表',
                static fn (OceanEngineClient $client, int $advertiserId, string $date) => $client->DataReports()
                    ->AdsProDataReports
                    ->ReportCustomGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                        'start_date' => $date,
                        'end_date' => $date,
                    ]),
            ],
        ];
    }
}
