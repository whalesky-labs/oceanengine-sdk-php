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

use Api\JuLiangAds\ProductManagement\WorkspaceUpgrade\DpaLibraryList;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class JuLiangAdsModuleTest extends TestCase
{
    public function testWorkspaceUpgradeRequestCanBeBuilt(): void
    {
        $client = new OceanEngineClient('token');

        $request = $client->JuLiangAds()
            ->WorkspaceUpgrade
            ->DpaLibraryList();

        self::assertInstanceOf(DpaLibraryList::class, $request);
        self::assertSame('/v3.0/dpa/ebp/library/list/', $request->getUrl());
        self::assertSame('GET', $request->getMethod());
    }
}
