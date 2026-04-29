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

namespace Tests\Module;

use Api\DataReports\AdsProDataReports\ReportAdvertiserGet;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class DataReportsModuleTest extends TestCase
{
    public function testAdsProReportRequestCanBeBuilt(): void
    {
        $client = new OceanEngineClient('token');

        $request = $client->DataReports()
            ->AdsProDataReports
            ->ReportAdvertiserGet();

        self::assertInstanceOf(ReportAdvertiserGet::class, $request);
        self::assertSame('/2/report/advertiser/get/', $request->getUrl());
        self::assertSame('GET', $request->getMethod());
    }
}
