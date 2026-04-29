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

use Api\JuLiangLocalPush\LocalPushAds\ProjectManagement\LocalProjectList;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class JuLiangLocalPushModuleTest extends TestCase
{
    public function testProjectManagementRequestCanBeBuilt(): void
    {
        $client = new OceanEngineClient('token');

        $request = $client->JuLiangLocalPush()
            ->ProjectManagement
            ->LocalProjectList();

        self::assertInstanceOf(LocalProjectList::class, $request);
        self::assertSame('/v3.0/local/project/list/', $request->getUrl());
        self::assertSame('GET', $request->getMethod());
    }
}
