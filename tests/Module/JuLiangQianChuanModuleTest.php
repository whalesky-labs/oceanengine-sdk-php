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

use Api\JuLiangQianChuan\AdvertisingMgmt\AccountBudget\AccountBudgetGet;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class JuLiangQianChuanModuleTest extends TestCase
{
    public function testAccountBudgetRequestCanBeBuilt(): void
    {
        $client = new OceanEngineClient('token');

        $request = $client->JuLiangQianChuan()
            ->AccountBudget
            ->AccountBudgetGet();

        self::assertInstanceOf(AccountBudgetGet::class, $request);
        self::assertSame('/v1.0/qianchuan/account/budget/get/', $request->getUrl());
        self::assertSame('GET', $request->getMethod());
    }
}
