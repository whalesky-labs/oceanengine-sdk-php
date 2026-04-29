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

namespace Tests\Integration\Materials\ImageVideoMgmt;

use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class ImageVideoMgmtIntegrationTest extends TestCase
{
    use LoadsEnvConfig;

    private const DEFAULT_VIDEO_FILE_PATH = '/Volumes/HX-Sync/资源库/成片库/4.15-屈敏洁-纤维饮-1（口播）.mp4';

    /**
     * @dataProvider imageVideoRequestProvider
     *
     * @param callable(OceanEngineClient,int):object $requestBuilder
     */
    public function testImageVideoInterfacesSmoke(string $label, callable $requestBuilder): void
    {
        [$token, $advertiserId] = $this->resolveTokenAndAdvertiserId();

        if ($token === '' || $advertiserId === '') {
            self::markTestSkipped('Set TOKEN and ADVERTISER_ID (or ADVERTISER_IDS) in .env.');
        }

        $payload = $this->runModuleSmokeRequest($token, (int) $advertiserId, $label, $requestBuilder);
        self::assertArrayHasKey('code', $payload);
    }

    /**
     * @return array<string, array{0:string,1:callable(OceanEngineClient,int):object}>
     */
    public function imageVideoRequestProvider(): array
    {
        return [
            'file_image_get' => [
                '获取图片素材',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->Materials()
                    ->ImageVideoMgmt
                    ->FileImageGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'file_image_ad_get' => [
                '获取同主体下广告主图片素材',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->Materials()
                    ->ImageVideoMgmt
                    ->FileImageAdGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
            'file_video_ad_get' => [
                '获取同主体下广告主视频素材',
                static fn (OceanEngineClient $client, int $advertiserId) => $client->Materials()
                    ->ImageVideoMgmt
                    ->FileVideoAdGet()
                    ->setParams([
                        'advertiser_id' => $advertiserId,
                    ]),
            ],
        ];
    }

    public function testUploadVideoMaterial(): void
    {
        [$token, $advertiserId] = $this->resolveTokenAndAdvertiserId();
        $videoFilePath = $this->resolveVideoFilePath();
        if ($videoFilePath === '') {
            $videoFilePath = self::DEFAULT_VIDEO_FILE_PATH;
        }

        if ($token === '' || $advertiserId === '') {
            self::markTestSkipped('Set TOKEN and ADVERTISER_ID (or ADVERTISER_IDS) in .env.');
        }

        if (! is_file($videoFilePath)) {
            self::markTestSkipped(sprintf('Video file not found: %s', $videoFilePath));
        }

        $payload = $this->runWithNetworkGuard(function () use ($token, $advertiserId, $videoFilePath): array {
            $client = new OceanEngineClient($token);
            $response = $client->Materials()
                ->ImageVideoMgmt
                ->FileVideoAd()
                ->setParams([
                    'advertiser_id' => (int) $advertiserId,
                    'upload_type' => 'UPLOAD_BY_FILE',
                    'video_file' => '@' . $videoFilePath,
                    'video_signature' => md5_file($videoFilePath),
                ])
                ->send();

            return $this->assertIntegrationHttpResponse($response, '上传视频素材');
        });

        self::assertArrayHasKey('code', $payload);
    }
}
