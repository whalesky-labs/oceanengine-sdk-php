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

class OceanEngineException extends \Exception
{
    private int $errorCode;

    private string $errorMessage;

    private string $errorType;

    private ?int $httpStatus = null;

    private ?string $responseBody = null;

    /**
     * 构造 SDK 基础异常。
     *
     * @param string $errorMessage 错误消息
     * @param int $errorCode 错误码
     */
    public function __construct(string $errorMessage, int $errorCode = 500)
    {
        parent::__construct($errorMessage, $errorCode);
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
        $this->setErrorType('OceanEngine');
    }

    /**
     * 获取错误码。
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * 设置错误码。
     *
     * @param int $errorCode 错误码
     * @return void
     */
    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;
        $this->code = $errorCode;
    }

    /**
     * 获取错误消息。
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * 设置错误消息。
     *
     * @param string $errorMessage 错误消息
     * @return void
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
        $this->message = $errorMessage;
    }

    /**
     * 获取错误类型。
     *
     * @return string
     */
    public function getErrorType(): string
    {
        return $this->errorType;
    }

    /**
     * 设置错误类型。
     *
     * @param string $errorType 错误类型
     * @return void
     */
    public function setErrorType(string $errorType): void
    {
        $this->errorType = $errorType;
    }

    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }

    public function setHttpStatus(?int $httpStatus): void
    {
        $this->httpStatus = $httpStatus;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    public function setResponseBody(?string $responseBody): void
    {
        $this->responseBody = $responseBody;
    }
}
