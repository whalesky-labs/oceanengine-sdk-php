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

namespace Tests\Unit;

use Core\Exception\InvalidParamException;
use OceanEngineSDK\OceanEngineAuth;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class OceanEngineAuthTest extends TestCase
{
    public function testGetAccessTokenThrowsInvalidParamExceptionWhenJsonEncodeFails(): void
    {
        $auth = new OceanEngineAuth('app-id', 'secret');

        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('请求参数 JSON 编码失败');

        $auth->getAccessToken("\xB1\x31");
    }

    public function testRefreshTokenThrowsInvalidParamExceptionWhenJsonEncodeFails(): void
    {
        $auth = new OceanEngineAuth('app-id', 'secret');

        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('请求参数 JSON 编码失败');

        $auth->refreshToken("\xB1\x31");
    }
}
