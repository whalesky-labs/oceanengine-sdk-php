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

use Api\Tools\DouYinInfluencerMgmt\ToolsAwemeInfoSearch;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ToolsModuleTest extends TestCase
{
    public function testDouYinInfluencerRequestCanBeBuilt(): void
    {
        $client = new OceanEngineClient('token');

        $request = $client->Tools()
            ->DouYinInfluencerMgmt
            ->ToolsAwemeInfoSearch();

        self::assertInstanceOf(ToolsAwemeInfoSearch::class, $request);
        self::assertSame('/2/tools/aweme_info_search/', $request->getUrl());
        self::assertSame('GET', $request->getMethod());
    }
}
