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

namespace Core\Profile;

use OceanEngineSDK\OceanEngineClient;

class BaseModule
{
    protected OceanEngineClient $client;

    /**
     * 注入 SDK 客户端实例。
     *
     * @param OceanEngineClient $client SDK 客户端实例
     */
    public function __construct(OceanEngineClient $client)
    {
        $this->client = $client;
    }
}
