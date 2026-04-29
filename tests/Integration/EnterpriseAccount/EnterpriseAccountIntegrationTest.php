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

namespace Tests\Integration\EnterpriseAccount;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class EnterpriseAccountIntegrationTest extends TestCase
{
    public function testEnterpriseAccountInterfacesPending(): void
    {
        self::markTestSkipped('EnterpriseAccount 模块尚未接入具体 API 接口，暂不提供集成烟雾用例。');
    }
}
