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

namespace Core\Exception;

class InvalidParamException extends OceanEngineException
{
    /**
     * 构造参数异常对象。
     *
     * @param string $errorMessage 参数错误消息
     * @param int $errorCode 错误码
     */
    public function __construct(string $errorMessage, int $errorCode = 500)
    {
        parent::__construct($errorMessage, $errorCode);
    }
}
