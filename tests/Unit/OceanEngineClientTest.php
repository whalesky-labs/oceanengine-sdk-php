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
use Api\Account\AccountRel\QianChuanUniPromotionAuthInit;
use Core\Exception\InvalidParamException;
use Core\Http\HttpRequest;
use Core\Profile\ChainProxy;
use Core\Profile\RpcRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use OceanEngineSDK\OceanEngineAuth;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @internal
 * @coversNothing
 */
final class OceanEngineClientTest extends TestCase
{
    /**
     * @dataProvider topLevelModuleProvider
     */
    public function testTopLevelModulesAreDiscovered(string $moduleName): void
    {
        $client = new OceanEngineClient('token');

        $proxy = $client->module($moduleName);

        self::assertInstanceOf(ChainProxy::class, $proxy);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public function topLevelModuleProvider(): array
    {
        return [
            'Account' => ['Account'],
            'DataReports' => ['DataReports'],
            'EnterpriseAccount' => ['EnterpriseAccount'],
            'JuLiangAds' => ['JuLiangAds'],
            'JuLiangLocalPush' => ['JuLiangLocalPush'],
            'JuLiangQianChuan' => ['JuLiangQianChuan'],
            'JuLiangStarMap' => ['JuLiangStarMap'],
            'Materials' => ['Materials'],
            'Tools' => ['Tools'],
        ];
    }

    public function testUnknownModuleThrowsInvalidArgumentException(): void
    {
        $client = new OceanEngineClient('token');

        $this->expectException(\InvalidArgumentException::class);
        $client->module('NotExists');
    }

    public function testUnknownMagicCallThrowsBadMethodCallException(): void
    {
        $client = new OceanEngineClient('token');

        $this->expectException(\BadMethodCallException::class);
        $method = 'NotExists';
        $client->{$method}();
    }

    public function testMagicTopLevelCallReturnsChainProxy(): void
    {
        $client = new OceanEngineClient('token');

        $proxy = $client->JuLiangAds();

        self::assertInstanceOf(ChainProxy::class, $proxy);
    }

    public function testLegacyAliasClassCanStillBeInstantiated(): void
    {
        $legacyClass = 'Account\AccountRel\QianChuanUniPromotionAuthInit';
        self::assertTrue(class_exists($legacyClass));

        $legacy = new $legacyClass();

        self::assertInstanceOf(QianChuanUniPromotionAuthInit::class, $legacy);
    }

    public function testRetryConfigIsIsolatedPerClientInstance(): void
    {
        $clientA = new OceanEngineClient('token-a');
        $clientB = new OceanEngineClient('token-b');

        $clientA->setRetryConfig(
            maxRetries: 1,
            retryDelay: 123,
            retryableStatusCodes: [429],
            enableRetry: false,
            retryableBusinessCodes: [40100]
        );

        self::assertSame(1, $this->readPrivateProperty($clientA, 'maxRetries'));
        self::assertSame(123, $this->readPrivateProperty($clientA, 'retryDelay'));
        self::assertFalse($this->readPrivateProperty($clientA, 'retryEnabled'));
        self::assertSame([429], $this->readPrivateProperty($clientA, 'retryableStatusCodes'));

        self::assertSame(3, $this->readPrivateProperty($clientB, 'maxRetries'));
        self::assertSame(1000, $this->readPrivateProperty($clientB, 'retryDelay'));
        self::assertTrue($this->readPrivateProperty($clientB, 'retryEnabled'));
        self::assertSame([408, 429, 500, 502, 503, 504], $this->readPrivateProperty($clientB, 'retryableStatusCodes'));
    }

    /**
     * @throws \ReflectionException
     */
    public function testRetryEnabledSwitchOnlyAffectsCurrentClient(): void
    {
        $clientA = new OceanEngineClient('token-a');
        $clientB = new OceanEngineClient('token-b');

        $clientA->setRetryEnabled(false);

        self::assertFalse($this->readPrivateProperty($clientA, 'retryEnabled'));
        self::assertTrue($this->readPrivateProperty($clientB, 'retryEnabled'));
    }

    public function testExecuteThrowsInvalidParamExceptionWhenJsonEncodeFails(): void
    {
        $client = new OceanEngineClient('token');
        $request = new class($client) extends RpcRequest {
            protected string $url = 'http://127.0.0.1:65535/test';

            protected string $method = 'POST';

            protected string $content_type = 'application/json';
        };
        $request->addParam('invalid_utf8', "\xB1\x31");

        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('请求参数 JSON 编码失败');

        $client->execute($request);
    }

    public function testInjectedHttpHandlersAreIsolatedAcrossClients(): void
    {
        $url = 'http://127.0.0.1:1';
        $handlerA = new MockHandler([new Response(200, [], '{"source":"a"}')]);
        $handlerB = new MockHandler([new Response(200, [], '{"source":"b"}')]);
        $oauthHandler = new MockHandler([new Response(200, [], '{"source":"oauth"}')]);
        $clientA = new OceanEngineClient('fixture-token-a', serverUrl: $url, httpHandler: $handlerA);
        $clientB = new OceanEngineClient('fixture-token-b', serverUrl: $url, httpHandler: $handlerB);
        $auth = new OceanEngineAuth('1', 'fixture-secret', server_url: $url, httpHandler: $oauthHandler);

        self::assertSame(['source' => 'a'], (new Oauth2AdvertiserGet($clientA))->send()->getJsonBody());
        self::assertSame(['source' => 'oauth'], $auth->getAccessToken('fixture-code'));
        self::assertSame(['source' => 'b'], (new Oauth2AdvertiserGet($clientB))->send()->getJsonBody());
        self::assertSame('fixture-token-a', $handlerA->getLastRequest()->getHeaderLine('Access-Token'));
        self::assertSame('fixture-token-b', $handlerB->getLastRequest()->getHeaderLine('Access-Token'));
        self::assertSame('POST', $oauthHandler->getLastRequest()->getMethod());
    }

    public function testMalformedJsonRemainsAnErrorWithRedactedResponseLogging(): void
    {
        $body = '{"secret":"fixture-response-body' . "\xff" . '"}';
        $handler = new MockHandler([new Response(200, [
            'Content-Length' => (string) strlen($body),
            'X-Encoded-Content-Length' => '123',
            'X-Encoded-Content-Encoding' => 'fixture-sensitive-value',
            'Set-Cookie' => 'fixture-cookie',
        ], $body)]);
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('debug')->with('OceanEngine HTTP response', [
            'http_status' => 200,
            'content_encoding' => 'other',
            'content_length' => 123,
            'body_bytes' => strlen($body),
        ]);
        $client = new OceanEngineClient('fixture-access-token', serverUrl: 'http://127.0.0.1:1', httpHandler: $handler, responseLogger: $logger);
        $response = (new Oauth2AdvertiserGet($client))->send();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Malformed UTF-8');

        $response->getJsonBody();
    }

    public function testClientVerifyConfigCanBeOverriddenIndependently(): void
    {
        $clientA = new OceanEngineClient('token-a');
        $clientB = new OceanEngineClient('token-b');

        $clientA->setVerify(true);
        $clientB->setVerify(true, '/etc/ssl/custom-ca.pem');

        self::assertTrue($this->readPrivateProperty($clientA, 'verify'));
        self::assertSame('/etc/ssl/custom-ca.pem', $this->readPrivateProperty($clientB, 'verify'));
    }

    public function testClientUsesHttpRequestDefaultVerifyValue(): void
    {
        $originalVerify = HttpRequest::$verify;

        try {
            HttpRequest::setVerify(true);
            $client = new OceanEngineClient('token');

            self::assertTrue($this->readPrivateProperty($client, 'verify'));
        } finally {
            if (is_string($originalVerify)) {
                HttpRequest::setVerify(true, $originalVerify);
            } else {
                HttpRequest::setVerify($originalVerify);
            }
        }
    }

    public function testRequestLevelRetryOverrideOnlyDisablesRetryForCurrentRequest(): void
    {
        $client = new OceanEngineClient('token');

        $uploadRequest = new class($client) extends RpcRequest {
            protected string $url = 'http://127.0.0.1:65535/upload';

            public function shouldEnableRetry(): bool
            {
                return false;
            }
        };

        $normalRequest = new class($client) extends RpcRequest {
            protected string $url = 'http://127.0.0.1:65535/default';
        };

        self::assertFalse($this->readRuntimeHttpConfig($client, $uploadRequest)['enable_retry']);
        self::assertTrue($this->readRuntimeHttpConfig($client, $normalRequest)['enable_retry']);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @return mixed
     * @throws \ReflectionException
     */
    private function readPrivateProperty(object $object, string $propertyName): mixed
    {
        $ref = new \ReflectionObject($object);
        $property = $ref->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * @return array<string, mixed>
     */
    private function readRuntimeHttpConfig(OceanEngineClient $client, RpcRequest $request): array
    {
        $method = new \ReflectionMethod($client, 'buildRuntimeHttpConfig');
        $method->setAccessible(true);

        return $method->invoke($client, $request->getTimeout(), $request);
    }
}
