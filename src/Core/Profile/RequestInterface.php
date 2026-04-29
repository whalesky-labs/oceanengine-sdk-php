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

interface RequestInterface
{
    /**
     * 获取请求 URL。
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * 设置请求 URL。
     *
     * @param string $url 请求 URL
     * @return static
     */
    public function setUrl(string $url): static;

    /**
     * 获取请求方法。
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * 获取请求超时时间（秒）。
     *
     * @return int
     */
    public function getTimeout(): int;

    /**
     * 批量设置请求参数。
     *
     * @param array<string, mixed> $array
     * @return static
     */
    public function setParams(array $array): static;

    /**
     * 获取请求参数。
     *
     * @return array<string, mixed>
     */
    public function getParams(): array;

    /**
     * 添加单个请求参数。
     *
     * @param string $key 参数名
     * @param mixed $value 参数值
     * @return static
     */
    public function addParam(string $key, mixed $value): static;

    /**
     * 获取请求 Content-Type。
     *
     * @return string
     */
    public function getContentType(): string;

    /**
     * 校验请求参数。
     *
     * @return void
     */
    public function check(): void;
}
