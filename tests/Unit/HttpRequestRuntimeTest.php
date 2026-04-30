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
        HttpRequest::setVerify(true);
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
        HttpRequest::setVerify(true);

        $method = new \ReflectionMethod(HttpRequest::class, 'normalizeRuntimeConfig');
        $method->setAccessible(true);

        $configWithBoolVerify = $method->invoke(null, ['verify' => true]);
        $configWithPathVerify = $method->invoke(null, ['verify' => '/etc/ssl/custom-ca.pem']);
        $configWithInvalidVerify = $method->invoke(null, ['verify' => 123]);

        self::assertTrue($configWithBoolVerify['verify']);
        self::assertSame('/etc/ssl/custom-ca.pem', $configWithPathVerify['verify']);
        self::assertTrue($configWithInvalidVerify['verify']);
    }

    public function testCanRemovePresetContentTypeHeader(): void
    {
        $method = new \ReflectionMethod(HttpRequest::class, 'withoutHeader');
        $method->setAccessible(true);

        $headers = $method->invoke(
            null,
            [
                'Access-Token' => 'token',
                'Content-Type' => 'multipart/form-data',
            ],
            'Content-Type'
        );

        self::assertSame(['Access-Token' => 'token'], $headers);
    }

    public function testCurlFileIsRecognizedAsMultipartUpload(): void
    {
        $containsFileMethod = new \ReflectionMethod(HttpRequest::class, 'containsFile');
        $containsFileMethod->setAccessible(true);

        $buildMultipartMethod = new \ReflectionMethod(HttpRequest::class, 'buildUploadMultipartData');
        $buildMultipartMethod->setAccessible(true);

        $tempFile = tempnam(sys_get_temp_dir(), 'oe-video-');
        self::assertNotFalse($tempFile);
        file_put_contents($tempFile, 'test');

        try {
            $file = new \CURLFile($tempFile, 'video/mp4', 'sample.mp4');

            self::assertTrue($containsFileMethod->invoke(null, [
                'advertiser_id' => 123,
                'video_file' => $file,
            ]));

            $multipart = $buildMultipartMethod->invoke(null, [
                'advertiser_id' => 123,
                'video_file' => $file,
            ]);

            self::assertCount(2, $multipart);
            self::assertSame('advertiser_id', $multipart[0]['name']);
            self::assertSame(123, $multipart[0]['contents']);
            self::assertSame('video_file', $multipart[1]['name']);
            self::assertSame('sample.mp4', $multipart[1]['filename']);
            self::assertSame(['Content-Type' => 'video/mp4'], $multipart[1]['headers']);
            self::assertIsResource($multipart[1]['contents']);
            fclose($multipart[1]['contents']);
        } finally {
            @unlink($tempFile);
        }
    }

    public function testMissingAtPathUploadFailsFast(): void
    {
        $containsFileMethod = new \ReflectionMethod(HttpRequest::class, 'containsFile');
        $containsFileMethod->setAccessible(true);

        $buildMultipartMethod = new \ReflectionMethod(HttpRequest::class, 'buildUploadMultipartData');
        $buildMultipartMethod->setAccessible(true);

        self::assertTrue($containsFileMethod->invoke(null, [
            'video_file' => '@/tmp/definitely-not-found-video.mp4',
        ]));

        $this->expectException(\Core\Exception\InvalidParamException::class);
        $this->expectExceptionMessage('client-check-error:Invalid Arguments: the file of "video_file" does not exist: /tmp/definitely-not-found-video.mp4');

        $buildMultipartMethod->invoke(null, [
            'video_file' => '@/tmp/definitely-not-found-video.mp4',
        ]);
    }

    public function testAtPathUploadIsConvertedToMultipartStream(): void
    {
        $buildMultipartMethod = new \ReflectionMethod(HttpRequest::class, 'buildUploadMultipartData');
        $buildMultipartMethod->setAccessible(true);

        $tempFile = tempnam(sys_get_temp_dir(), 'oe-video-');
        self::assertNotFalse($tempFile);
        file_put_contents($tempFile, 'test');

        try {
            $multipart = $buildMultipartMethod->invoke(null, [
                'advertiser_id' => 123,
                'video_file' => '@' . $tempFile,
            ]);

            self::assertSame(123, $multipart[0]['contents']);
            self::assertSame('video_file', $multipart[1]['name']);
            self::assertSame(basename($tempFile), $multipart[1]['filename']);
            self::assertIsResource($multipart[1]['contents']);
            fclose($multipart[1]['contents']);
        } finally {
            @unlink($tempFile);
        }
    }

    public function testUploadRequestOptionsDisableRedirectsAndHttpErrors(): void
    {
        $method = new \ReflectionMethod(HttpRequest::class, 'buildUploadRequestOptions');
        $method->setAccessible(true);

        $multipart = [
            ['name' => 'advertiser_id', 'contents' => 123],
        ];

        $options = $method->invoke(null, $multipart, ['Content-Type' => 'multipart/form-data'], [
            'read_timeout' => 30,
            'connect_timeout' => 20,
        ]);

        self::assertFalse($options['http_errors']);
        self::assertFalse($options['allow_redirects']);
        self::assertSame([], $options['headers']);
        self::assertSame($multipart, $options['multipart']);
    }

    public function testCliRuntimeIsNotMisdetectedAsSwooleWhenNoCoroutineIsActive(): void
    {
        HttpRequest::setRuntimeMode('auto');

        if (PHP_SAPI !== 'cli') {
            self::markTestSkipped('Only relevant for CLI runtime.');
        }

        if (! class_exists(\Swoole\Coroutine::class)) {
            self::assertSame('cli', HttpRequest::getRuntimeMode());
            return;
        }

        if (\Swoole\Coroutine::getCid() > -1) {
            self::markTestSkipped('Current process is already inside a Swoole coroutine.');
        }

        self::assertSame('cli', HttpRequest::getRuntimeMode());
    }
}
