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
use Core\Http\HttpRequest;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class HttpRequestErrorMappingTest extends TestCase
{
    public function testRequestExceptionCanBeMappedWithHttpStatusAndResponseBody(): void
    {
        $request = new Request('GET', 'https://example.com/test');
        $response = new Response(
            429,
            ['Content-Type' => 'application/json'],
            '{"code":40100,"message":"rate limited"}'
        );
        $requestException = new RequestException('Too Many Requests', $request, $response);

        $mapped = $this->mapRequestException($requestException);

        self::assertSame(429, $mapped->getCode());
        self::assertSame(429, $mapped->getHttpStatus());
        self::assertSame('{"code":40100,"message":"rate limited"}', $mapped->getResponseBody());
        self::assertStringContainsString('HTTP 429', $mapped->getMessage());
        self::assertStringContainsString('rate limited', $mapped->getMessage());
    }

    public function testRequestExceptionWithoutResponseKeepsNullResponseMetadata(): void
    {
        $request = new Request('GET', 'https://example.com/test');
        $requestException = new RequestException('Connection failed', $request);

        $mapped = $this->mapRequestException($requestException);

        self::assertNull($mapped->getHttpStatus());
        self::assertNull($mapped->getResponseBody());
        self::assertSame(400, $mapped->getCode());
    }

    private function mapRequestException(RequestException $e): OceanEngineException
    {
        try {
            HttpRequest::curl((string) $e->getRequest()->getUri(), runtimeConfig: [
                'enable_retry' => false,
                'handler' => new MockHandler([$e]),
            ]);
            self::fail('Expected the SDK to map the Guzzle exception.');
        } catch (OceanEngineException $exception) {
            return $exception;
        }
    }
}
