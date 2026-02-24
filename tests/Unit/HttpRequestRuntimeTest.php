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

use Core\Http\HttpRequest;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class HttpRequestRuntimeTest extends TestCase
{
    protected function tearDown(): void
    {
        HttpRequest::setRuntimeMode('auto');
        HttpRequest::setVerify(false);
        parent::tearDown();
    }

    public function testCanOverrideRuntimeMode(): void
    {
        HttpRequest::setRuntimeMode('cli');
        self::assertSame('cli', HttpRequest::getRuntimeMode());

        HttpRequest::setRuntimeMode('fpm');
        self::assertSame('fpm', HttpRequest::getRuntimeMode());
    }

    public function testInvalidRuntimeModeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        HttpRequest::setRuntimeMode('invalid');
    }

    public function testSwooleDefaultsToNonPooledClientReuse(): void
    {
        $method = new \ReflectionMethod(HttpRequest::class, 'shouldUseClientPool');
        $method->setAccessible(true);

        $usePool = $method->invoke(null, ['enable_retry' => true], 'swoole');
        self::assertFalse($usePool);
    }

    public function testReuseClientFlagCanOverridePoolStrategy(): void
    {
        $method = new \ReflectionMethod(HttpRequest::class, 'shouldUseClientPool');
        $method->setAccessible(true);

        $forceReuse = $method->invoke(null, ['reuse_client' => true], 'swoole');
        $disableReuse = $method->invoke(null, ['reuse_client' => false], 'fpm');

        self::assertTrue($forceReuse);
        self::assertFalse($disableReuse);
    }

    public function testCanSetGlobalTlsVerifyStrategy(): void
    {
        HttpRequest::setVerify(true);
        self::assertTrue(HttpRequest::$verify);

        HttpRequest::setVerify(true, '/etc/ssl/custom-ca.pem');
        self::assertSame('/etc/ssl/custom-ca.pem', HttpRequest::$verify);
    }

    public function testNormalizeRuntimeConfigSupportsVerifyOverride(): void
    {
        $method = new \ReflectionMethod(HttpRequest::class, 'normalizeRuntimeConfig');
        $method->setAccessible(true);

        $configWithBoolVerify = $method->invoke(null, ['verify' => true]);
        $configWithPathVerify = $method->invoke(null, ['verify' => '/etc/ssl/custom-ca.pem']);
        $configWithInvalidVerify = $method->invoke(null, ['verify' => 123]);

        self::assertTrue($configWithBoolVerify['verify']);
        self::assertSame('/etc/ssl/custom-ca.pem', $configWithPathVerify['verify']);
        self::assertFalse($configWithInvalidVerify['verify']);
    }
}
