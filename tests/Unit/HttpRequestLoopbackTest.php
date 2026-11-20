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

use Api\Account\AccountRel\Oauth2AdvertiserGet;
use Api\Materials\ImageVideoMgmt\FileVideoAd;
use Core\Http\HttpRequest;
use OceanEngineSDK\OceanEngineAuth;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Swoole\Coroutine;
use Swoole\Runtime;

/**
 * @internal
 * @coversNothing
 */
final class HttpRequestLoopbackTest extends TestCase
{
    /**
     * @dataProvider transportModes
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSharedHttpTransport(?int $hookFlags, bool $expectIdentity): void
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0');
        self::assertIsResource($socket);
        $address = stream_socket_get_name($socket, false);
        fclose($socket);
        self::assertIsString($address);

        $server = proc_open(
            [PHP_BINARY, '-n', '-S', $address, __DIR__ . '/../Fixtures/http-router.php'],
            [2 => ['pipe', 'w']],
            $pipes
        );
        self::assertIsResource($server);

        try {
            stream_set_timeout($pipes[2], 5);
            self::assertStringContainsString('Development Server', (string) fgets($pipes[2]));
            $url = 'http://' . $address;

            if ($hookFlags === null) {
                $this->assertHttpRoundTrips($url, $expectIdentity);
                return;
            }

            Coroutine::set(['hook_flags' => $hookFlags]);
            \Swoole\Coroutine\run(function () use ($url, $hookFlags, $expectIdentity): void {
                self::assertSame($hookFlags, Runtime::getHookFlags());
                $this->assertHttpRoundTrips($url, $expectIdentity);
            });
        } finally {
            proc_terminate($server);
            fclose($pipes[2]);
            proc_close($server);
        }
    }

    /**
     * @return array<string, array{null|int, bool}>
     */
    public static function transportModes(): array
    {
        $modes = ['cli' => [null, false]];
        if (extension_loaded('swoole')) {
            $modes['unhooked coroutine'] = [0, false];
            if (defined('SWOOLE_HOOK_CURL')) {
                $modes['legacy curl'] = [SWOOLE_HOOK_CURL, true];
            }
            if (defined('SWOOLE_HOOK_NATIVE_CURL')) {
                $modes['native curl'] = [SWOOLE_HOOK_NATIVE_CURL, false];
            }
        }
        return $modes;
    }

    private function assertHttpRoundTrips(string $url, bool $expectIdentity): void
    {
        $logs = [];
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::exactly(2))->method('debug')->willReturnCallback(
            static function (string $message, array $context) use (&$logs): void {
                $logs[] = [$message, $context];
            }
        );
        $client = new OceanEngineClient('fixture-token', serverUrl: $url, responseLogger: $logger);
        $client->setRetryEnabled(false);

        $business = (new Oauth2AdvertiserGet($client))->setParams(['page' => 2])->send();
        $json = $business->getJsonBody();
        self::assertSame('GET', $json['method']);
        self::assertSame('/oauth2/advertiser/get/', $json['path']);
        self::assertSame(['page' => '2'], $json['query']);
        self::assertSame($expectIdentity, $json['accept_encoding'] === 'identity');

        $auth = new OceanEngineAuth('1', 'fixture-secret', server_url: $url);
        $oauth = $auth->getAccessToken('fixture-code');
        self::assertSame('POST', $oauth['method']);
        self::assertSame('/oauth2/access_token/', $oauth['path']);
        self::assertSame('application/json', $oauth['content_type']);
        self::assertSame([
            'grant_type' => 'auth_code',
            'app_id' => '1',
            'secret' => 'fixture-secret',
            'auth_code' => 'fixture-code',
        ], $oauth['json']);
        self::assertSame($expectIdentity, $oauth['accept_encoding'] === 'identity');

        $explicit = HttpRequest::curl($url . '/?plain=1', headers: ['aCcEpT-eNcOdInG' => 'gzip']);
        self::assertSame('gzip', $explicit->getJsonBody()['accept_encoding']);

        $uploadPath = tempnam(sys_get_temp_dir(), 'oe-loopback-');
        self::assertIsString($uploadPath);
        try {
            $bytes = "local\x00file\xff";
            file_put_contents($uploadPath, $bytes);
            $upload = (new FileVideoAd($client))->setParams([
                'advertiser_id' => 123,
                'video_file' => new \CURLFile($uploadPath, 'video/mp4', 'loopback.mp4'),
                'video_signature' => md5($bytes),
            ])->send();
            $uploaded = $upload->getJsonBody();
            self::assertSame('POST', $uploaded['method']);
            self::assertSame($expectIdentity, $uploaded['accept_encoding'] === 'identity');
            self::assertSame('123', $uploaded['fields']['advertiser_id']);
            self::assertSame([
                'name' => 'loopback.mp4',
                'type' => 'video/mp4',
                'sha256' => hash('sha256', $bytes),
            ], $uploaded['file']);
        } finally {
            unlink($uploadPath);
        }

        foreach ([$business, $upload] as $index => $response) {
            $body = $response->getBody();
            self::assertSame(['OceanEngine HTTP response', [
                'http_status' => 200,
                'content_encoding' => $expectIdentity ? null : 'deflate',
                'content_length' => strlen($expectIdentity ? $body : gzcompress($body)),
                'body_bytes' => strlen($body),
            ]], $logs[$index]);
        }
    }
}
