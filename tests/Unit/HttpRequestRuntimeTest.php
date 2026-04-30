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

        $buildFieldsMethod = new \ReflectionMethod(HttpRequest::class, 'buildUploadCurlFields');
        $buildFieldsMethod->setAccessible(true);

        $tempFile = tempnam(sys_get_temp_dir(), 'oe-video-');
        self::assertNotFalse($tempFile);
        file_put_contents($tempFile, 'test');

        try {
            $file = new \CURLFile($tempFile, 'video/mp4', 'sample.mp4');

            self::assertTrue($containsFileMethod->invoke(null, [
                'advertiser_id' => 123,
                'video_file' => $file,
            ]));

            $fields = $buildFieldsMethod->invoke(null, [
                'advertiser_id' => 123,
                'video_file' => $file,
            ]);

            self::assertCount(2, $fields);
            self::assertSame(123, $fields['advertiser_id']);
            self::assertInstanceOf(\CURLFile::class, $fields['video_file']);
            self::assertSame($tempFile, $fields['video_file']->getFilename());
            self::assertSame('sample.mp4', $fields['video_file']->getPostFilename());
            self::assertSame('video/mp4', $fields['video_file']->getMimeType());
        } finally {
            @unlink($tempFile);
        }
    }

    public function testMissingAtPathUploadFailsFast(): void
    {
        $containsFileMethod = new \ReflectionMethod(HttpRequest::class, 'containsFile');
        $containsFileMethod->setAccessible(true);

        $buildFieldsMethod = new \ReflectionMethod(HttpRequest::class, 'buildUploadCurlFields');
        $buildFieldsMethod->setAccessible(true);

        self::assertTrue($containsFileMethod->invoke(null, [
            'video_file' => '@/tmp/definitely-not-found-video.mp4',
        ]));

        $this->expectException(\Core\Exception\InvalidParamException::class);
        $this->expectExceptionMessage('client-check-error:Invalid Arguments: the file of "video_file" does not exist: /tmp/definitely-not-found-video.mp4');

        $buildFieldsMethod->invoke(null, [
            'video_file' => '@/tmp/definitely-not-found-video.mp4',
        ]);
    }

    public function testAtPathUploadIsConvertedToCurlFile(): void
    {
        $buildFieldsMethod = new \ReflectionMethod(HttpRequest::class, 'buildUploadCurlFields');
        $buildFieldsMethod->setAccessible(true);

        $tempFile = tempnam(sys_get_temp_dir(), 'oe-video-');
        self::assertNotFalse($tempFile);
        file_put_contents($tempFile, 'test');

        try {
            $fields = $buildFieldsMethod->invoke(null, [
                'advertiser_id' => 123,
                'video_file' => '@' . $tempFile,
            ]);

            self::assertSame(123, $fields['advertiser_id']);
            self::assertInstanceOf(\CURLFile::class, $fields['video_file']);
            self::assertSame($tempFile, $fields['video_file']->getFilename());
            self::assertSame(basename($tempFile), $fields['video_file']->getPostFilename());
        } finally {
            @unlink($tempFile);
        }
    }

    public function testUploadRequestOptionsDisableRedirectsAndHttpErrors(): void
    {
        $method = new \ReflectionMethod(HttpRequest::class, 'buildUploadCurlOptions');
        $method->setAccessible(true);

        $tempFile = tempnam(sys_get_temp_dir(), 'oe-video-');
        self::assertNotFalse($tempFile);
        file_put_contents($tempFile, 'test');

        try {
            $options = $method->invoke(null, 'https://example.com/upload', 'POST', [
                'advertiser_id' => 123,
                'video_file' => '@' . $tempFile,
            ], ['Content-Type' => 'multipart/form-data', 'Access-Token' => 'token'], [
                'read_timeout' => 30,
                'connect_timeout' => 20,
                'verify' => true,
            ]);

            self::assertFalse($options[CURLOPT_FOLLOWLOCATION]);
            self::assertSame(20, $options[CURLOPT_CONNECTTIMEOUT]);
            self::assertSame(30, $options[CURLOPT_TIMEOUT]);
            self::assertSame(['Access-Token: token'], $options[CURLOPT_HTTPHEADER]);
            self::assertArrayHasKey(CURLOPT_POSTFIELDS, $options);
            self::assertInstanceOf(\CURLFile::class, $options[CURLOPT_POSTFIELDS]['video_file']);
        } finally {
            @unlink($tempFile);
        }
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
