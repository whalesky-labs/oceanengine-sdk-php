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

use Core\Profile\ChainProxy;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class EnterpriseAccountModuleTest extends TestCase
{
    public function testEnterpriseAccountModuleProxyCanBeCreated(): void
    {
        $client = new OceanEngineClient('token');

        $proxy = $client->EnterpriseAccount();

        self::assertInstanceOf(ChainProxy::class, $proxy);
    }

    public function testUndefinedEnterpriseAccountRequestThrows(): void
    {
        $client = new OceanEngineClient('token');

        $this->expectException(\BadMethodCallException::class);

        $client->EnterpriseAccount()->MethodNotExists();
    }
}
