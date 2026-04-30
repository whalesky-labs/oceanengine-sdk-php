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

use Api\Materials\ImageVideoMgmt\FileVideoAd;
use Api\JuLiangAds\SiteBuilder\ThirdPartyPages\ToolsThirdSiteDelete;
use Core\Exception\InvalidParamException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class RequestValidationTest extends TestCase
{
    public function testToolsThirdSiteDeleteMissingAdvertiserIdThrowsInvalidParamException(): void
    {
        $request = new ToolsThirdSiteDelete();

        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('client-check-error:Missing Required Arguments: advertiser_id');

        $request->check();
    }

    public function testToolsThirdSiteDeleteMissingSiteIdThrowsInvalidParamException(): void
    {
        $request = new ToolsThirdSiteDelete();
        $request->setParams(['advertiser_id' => 123]);

        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('client-check-error:Missing Required Arguments: site_id');

        $request->check();
    }

    public function testToolsThirdSiteDeletePassesWhenRequiredParamsArePresent(): void
    {
        $request = new ToolsThirdSiteDelete();
        $request->setParams([
            'advertiser_id' => 123,
            'site_id' => 456,
        ]);

        $request->check();

        self::assertTrue(true);
    }

    public function testFileVideoAdMissingVideoSignatureThrowsInvalidParamException(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'oe-video-');
        self::assertNotFalse($tempFile);
        file_put_contents($tempFile, 'video');

        try {
            $request = new FileVideoAd();
            $request->setParams([
                'advertiser_id' => 123,
                'upload_type' => 'UPLOAD_BY_FILE',
                'video_file' => '@' . $tempFile,
            ]);

            $this->expectException(InvalidParamException::class);
            $this->expectExceptionMessage('client-check-error:Missing Required Arguments: video_signature');

            $request->check();
        } finally {
            @unlink($tempFile);
        }
    }

    public function testFileVideoAdRejectsInvalidUploadType(): void
    {
        $request = new FileVideoAd();
        $request->setParams([
            'advertiser_id' => 123,
            'upload_type' => 'INVALID_TYPE',
        ]);

        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('client-check-error: AllowField of upload_type is not allowed.');

        $request->check();
    }

    public function testFileVideoAdRejectsInvalidVideoSignature(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'oe-video-');
        self::assertNotFalse($tempFile);
        file_put_contents($tempFile, 'video');

        try {
            $request = new FileVideoAd();
            $request->setParams([
                'advertiser_id' => 123,
                'upload_type' => 'UPLOAD_BY_FILE',
                'video_file' => '@' . $tempFile,
                'video_signature' => 'not-md5',
            ]);

            $this->expectException(InvalidParamException::class);
            $this->expectExceptionMessage('client-check-error:Invalid Arguments: the value of video_signature is not a valid md5 string.');

            $request->check();
        } finally {
            @unlink($tempFile);
        }
    }

    public function testFileVideoAdRejectsMissingUploadFile(): void
    {
        $request = new FileVideoAd();
        $request->setParams([
            'advertiser_id' => 123,
            'upload_type' => 'UPLOAD_BY_FILE',
            'video_file' => '@/tmp/not-found-video.mp4',
            'video_signature' => md5('video'),
        ]);

        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('client-check-error:Invalid Arguments: the file of "video_file" does not exist: /tmp/not-found-video.mp4');

        $request->check();
    }

    public function testFileVideoAdRequiresVideoUrlWhenUploadingByUrl(): void
    {
        $request = new FileVideoAd();
        $request->setParams([
            'advertiser_id' => 123,
            'upload_type' => 'UPLOAD_BY_URL',
        ]);

        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('client-check-error:Missing Required Arguments: video_url');

        $request->check();
    }

    public function testFileVideoAdPassesWhenUploadingByFile(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'oe-video-');
        self::assertNotFalse($tempFile);
        file_put_contents($tempFile, 'video');

        try {
            $request = new FileVideoAd();
            $request->setParams([
                'advertiser_id' => 123,
                'video_file' => '@' . $tempFile,
                'video_signature' => md5_file($tempFile),
            ]);

            $request->check();

            self::assertSame('UPLOAD_BY_FILE', $request->getParams()['upload_type']);
            self::assertFalse($request->shouldEnableRetry());
        } finally {
            @unlink($tempFile);
        }
    }

    public function testFileVideoAdKeepsRetryEnabledWhenUploadingByUrl(): void
    {
        $request = new FileVideoAd();
        $request->setParams([
            'advertiser_id' => 123,
            'upload_type' => 'UPLOAD_BY_URL',
            'video_url' => 'https://example.com/video.mp4',
        ]);

        $request->check();

        self::assertTrue($request->shouldEnableRetry());
    }

    public function testGenericRpcRequestDisablesRetryWhenParamsContainCurlFile(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'oe-upload-');
        self::assertNotFalse($tempFile);
        file_put_contents($tempFile, 'payload');

        try {
            $request = new class() extends \Core\Profile\RpcRequest {
            };
            $request->setParams([
                'file' => new \CURLFile($tempFile, 'text/plain', 'payload.txt'),
            ]);

            self::assertFalse($request->shouldEnableRetry());
        } finally {
            @unlink($tempFile);
        }
    }
}
