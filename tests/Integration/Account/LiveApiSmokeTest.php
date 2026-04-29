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

namespace Tests\Integration\Account;

use Core\Http\HttpResponse;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Concerns\LoadsEnvConfig;

/**
 * @internal
 * @coversNothing
 */
final class LiveApiSmokeTest extends TestCase
{
    use LoadsEnvConfig;

    public function testAccountInfoLiveRequestSmoke(): void
    {
        [$token, $advertiserId] = $this->resolveTokenAndAdvertiserId();

        if ($token === '' || $advertiserId === '') {
            self::markTestSkipped('Set TOKEN and ADVERTISER_ID (or ADVERTISER_IDS) in .env.');
        }

        $response = $this->runWithNetworkGuard(function () use ($token, $advertiserId): HttpResponse {
            $client = new OceanEngineClient($token);

            return $client->Account()
                ->AccountInfo
                ->AdvertiserInfo()
                ->setParams([
                    'account_ids' => [(int) $advertiserId],
                ])
                ->send();
        });

        self::assertInstanceOf(HttpResponse::class, $response);
        $payload = $this->assertIntegrationHttpResponse($response, '获取千川广告账户全量信息');
        self::assertArrayHasKey('code', $payload);
    }
}
