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

use Api\JuLiangStarMap\MassiveStarMap\StarClueGet;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class JuLiangStarMapModuleTest extends TestCase
{
    public function testMassiveStarMapRequestCanBeBuilt(): void
    {
        $client = new OceanEngineClient('token');

        $request = $client->JuLiangStarMap()
            ->MassiveStarMap
            ->StarClueGet();

        self::assertInstanceOf(StarClueGet::class, $request);
        self::assertSame('/2/star/clue/get/', $request->getUrl());
        self::assertSame('GET', $request->getMethod());
    }
}
