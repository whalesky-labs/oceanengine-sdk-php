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

use Api\Materials\ImageVideoMgmt\FileImageAdGet;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class MaterialsModuleTest extends TestCase
{
    public function testImageVideoRequestCanBeBuilt(): void
    {
        $client = new OceanEngineClient('token');

        $request = $client->Materials()
            ->ImageVideoMgmt
            ->FileImageAdGet();

        self::assertInstanceOf(FileImageAdGet::class, $request);
        self::assertSame('/2/file/image/ad/get/', $request->getUrl());
        self::assertSame('GET', $request->getMethod());
    }
}
