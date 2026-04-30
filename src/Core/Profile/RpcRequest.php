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

use Core\Exception\InvalidParamException;
use Core\Exception\OceanEngineException;
use Core\Helper\RequestCheckUtil;
use Core\Http\HttpResponse;
use OceanEngineSDK\OceanEngineClient;

class RpcRequest implements RequestInterface
{
    protected ?OceanEngineClient $client = null;

    /**
     * request url.
     */
    protected string $url = '';

    /**
     * request method.
     */
    protected string $method = 'GET';

    /**
     * request timeout.
     */
    protected int $timeout = 60;

    /**
     * request query params or raw body.
     */
    protected array $params = [];

    /**
     * request header Content-Type.
     */
    protected string $content_type = 'application/json';

    /**
     * 构造通用 RPC 请求对象。
     *
     * @param null|OceanEngineClient $client SDK 客户端实例
     */
    public function __construct(?OceanEngineClient $client = null)
    {
        $this->client = $client;
    }

    /**
     * 设置请求 URL。
     *
     * @param string $url 请求 URL
     * @return static
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 获取请求 URL。
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * 获取 HTTP 请求方法。
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * 获取请求超时时间（秒）。
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * 获取请求参数。
     *
     * @return array<string, mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * 添加单个请求参数。
     *
     * @param string $key 参数名
     * @param mixed $value 参数值
     * @return static
     */
    public function addParam(string $key, mixed $value): static
    {
        $this->params[$key] = $value;
        if (property_exists($this, $key)) {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * 批量设置请求参数。
     *
     * @param array<string, mixed> $array
     * @return static
     * @throws InvalidParamException
     */
    public function setParams(array $array): static
    {
        foreach ($array as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
            $this->params[$key] = $value;
        }
        return $this;
    }

    /**
     * 获取请求 Content-Type。
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->content_type;
    }

    /**
     * 当前请求是否允许自动重试。
     */
    public function shouldEnableRetry(): bool
    {
        return ! RequestCheckUtil::containsUploadFile($this->params);
    }

    /**
     * 校验当前请求参数。
     *
     * @return void
     * @throws InvalidParamException
     */
    public function check(): void
    {
        $reflection = new \ReflectionObject($this);
        foreach ($reflection->getProperties() as $property) {
            $type = $property->getType();
            if ($type !== null && ! $type->allowsNull()) {
                // PHP 8.1+ 检查 typed property 是否已初始化
                if (method_exists($property, 'isInitialized') && ! $property->isInitialized($this)) {
                    throw new InvalidParamException(sprintf(
                        '参数 %s 不能为空',
                        $property->getName()
                    ), 400);
                }
            }
        }
    }

    /**
     * 发送请求并返回 HTTP 响应。
     *
     * @return HttpResponse
     * @throws OceanEngineException
     */
    public function send(): HttpResponse
    {
        if (! $this->client instanceof OceanEngineClient) {
            throw new OceanEngineException('Request can not be send by null, OceanEngineClient instance should be set before send', 500);
        }

        $this->check();

        return $this->client->execute($this);
    }
}
