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

namespace Core\Http;

class HttpResponse
{
    private string $body;

    private int $status;

    /**
     * 获取原始响应体。
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * 设置原始响应体。
     *
     * @param string $body 原始响应体
     * @return void
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * 获取 HTTP 状态码。
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置 HTTP 状态码。
     *
     * @param int $status HTTP 状态码
     * @return void
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 判断 HTTP 状态是否成功（2xx）。
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return 200 <= $this->status && 300 > $this->status;
    }

    /**
     * 将响应体解码为 JSON 数组。
     *
     * @return array<string, mixed>
     * @throws \RuntimeException
     */
    public function getJsonBody(): array
    {
        $data = json_decode($this->body, true);
        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($data)) {
            throw new \RuntimeException('Failed to decode JSON body: ' . json_last_error_msg());
        }
        return $data;
    }
}
