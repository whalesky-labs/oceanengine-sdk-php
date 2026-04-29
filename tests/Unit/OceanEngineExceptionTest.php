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

use Core\Exception\OceanEngineException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class OceanEngineExceptionTest extends TestCase
{
    public function testConstructorSynchronizesDefaultExceptionCode(): void
    {
        $exception = new OceanEngineException('oops', 123);

        self::assertSame(123, $exception->getCode());
        self::assertSame(123, $exception->getErrorCode());
    }

    public function testSetErrorCodeUpdatesDefaultExceptionCode(): void
    {
        $exception = new OceanEngineException('oops', 123);
        $exception->setErrorCode(456);

        self::assertSame(456, $exception->getCode());
        self::assertSame(456, $exception->getErrorCode());
    }

    public function testSetErrorMessageSynchronizesDefaultMessage(): void
    {
        $exception = new OceanEngineException('oops', 123);
        $exception->setErrorMessage('changed');

        self::assertSame('changed', $exception->getMessage());
        self::assertSame('changed', $exception->getErrorMessage());
    }

    public function testCanStoreHttpStatusAndResponseBody(): void
    {
        $exception = new OceanEngineException('oops', 123);
        $exception->setHttpStatus(429);
        $exception->setResponseBody('{"code":40100,"message":"rate limited"}');

        self::assertSame(429, $exception->getHttpStatus());
        self::assertSame('{"code":40100,"message":"rate limited"}', $exception->getResponseBody());
    }
}
