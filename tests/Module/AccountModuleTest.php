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

use Api\Account\AccountInfo\AdvertiserInfo;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class AccountModuleTest extends TestCase
{
    public function testAccountInfoRequestCanBeBuilt(): void
    {
        $client = new OceanEngineClient('token');

        $request = $client->Account()
            ->AccountInfo
            ->AdvertiserInfo();

        self::assertInstanceOf(AdvertiserInfo::class, $request);
        self::assertSame('/2/advertiser/info/', $request->getUrl());
        self::assertSame('GET', $request->getMethod());
    }
}
