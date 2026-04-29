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
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class OceanEngineClientVerifyTest extends TestCase
{
    protected function tearDown(): void
    {
        HttpRequest::setVerify(true);
        parent::tearDown();
    }

    public function testClientDefaultsToTlsVerificationEnabled(): void
    {
        $client = new OceanEngineClient('token');

        self::assertTrue($this->readPrivateProperty($client, 'verify'));
    }

    public function testCanEnableTlsVerifyOnClient(): void
    {
        $client = new OceanEngineClient('token');
        $client->setVerify(true);

        self::assertTrue($this->readPrivateProperty($client, 'verify'));
    }

    public function testCanExplicitlyDisableTlsVerifyOnClient(): void
    {
        $client = new OceanEngineClient('token');
        $client->setVerify(false);

        self::assertFalse($this->readPrivateProperty($client, 'verify'));
    }

    /**
     * @throws \ReflectionException
     */
    private function readPrivateProperty(object $object, string $propertyName): mixed
    {
        $ref = new \ReflectionObject($object);
        $property = $ref->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}
