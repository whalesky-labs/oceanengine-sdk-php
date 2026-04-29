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

namespace OceanEngineSDK;

use Core\Exception\InvalidParamException;
use Core\Exception\OceanEngineException;
use Core\Http\HttpRequest;
use Core\Http\HttpResponse;
use Core\Profile\RequestInterface;
use Oauth\GetAccessToken;
use Oauth\RefreshToken;

class OceanEngineAuth
{
    public string $app_id;

    public string $secret;

    public string $server_url = 'https://ad.oceanengine.com/open_api';

    public string $box_url = 'https://api.oceanengine.com/open_api';

    public string $qc_auth_url = 'https://qianchuan.jinritemai.com/openapi/qc/audit/oauth.html';

    public string $ad_auth_url = 'https://open.oceanengine.com/audit/oauth.html';

    public bool $is_sandbox = false;

    /**
     * AuthClient constructor.
     *
     * @param string $app_id 应用 ID
     * @param string $secret 应用密钥
     * @param bool $is_sandbox 是否沙箱环境
     * @param null|string $server_url 生产环境地址
     * @param null|string $box_url 沙箱环境地址
     */
    public function __construct(
        string $app_id,
        string $secret,
        bool $is_sandbox = false,
        ?string $server_url = null,
        ?string $box_url = null
    ) {
        $this->app_id = $app_id;
        $this->secret = $secret;
        $this->is_sandbox = $is_sandbox;
        if ($server_url) {
            $this->server_url = $server_url;
        }
        if ($box_url) {
            $this->box_url = $box_url;
        }
    }

    /**
     * 获取 access_token.
     *
     * @param string $auth_code 授权码
     * @return array 解码后的 JSON 数组
     * @throws OceanEngineException
     */
    public function getAccessToken(string $auth_code): array
    {
        $request = new GetAccessToken();
        $request->setParams([
            'grant_type' => 'auth_code',
            'app_id' => $this->app_id,
            'secret' => $this->secret,
            'auth_code' => $auth_code,
        ]);
        return $this->execute($request)->getJsonBody();
    }

    /**
     * 刷新 access_token.
     *
     * @param string $refresh_token 刷新令牌
     * @return array 解码后的响应数据
     * @throws OceanEngineException
     */
    public function refreshToken(string $refresh_token): array
    {
        $request = new RefreshToken();
        $request->setParams([
            'grant_type' => 'refresh_token',
            'app_id' => $this->app_id,
            'secret' => $this->secret,
            'refresh_token' => $refresh_token,
        ]);
        return $this->execute($request)->getJsonBody();
    }

    /**
     * 构建授权 URL.
     *
     * @param string $cb_url 回调地址
     * @param string $scope 授权范围（未使用）
     * @param string $state 自定义状态参数
     * @param string $type 授权类型：QC（千川）或 AD（广告）
     * @return string 构建后的授权 URL
     */
    public function getAuthCodeUrl(string $cb_url, string $scope, string $state = 'your_custom_params', string $type = 'qc'): string
    {
        $auth_url = strtoupper($type) === 'QC' ? $this->qc_auth_url : $this->ad_auth_url;
        return "{$auth_url}?app_id={$this->app_id}&state={$state}&material_auth=1&redirect_uri=" . urlencode($cb_url);
    }

    /**
     * 执行 HTTP 请求.
     *
     * @param RequestInterface $request 请求对象
     * @param null|string $url 可选请求地址
     * @return HttpResponse 响应对象
     * @throws OceanEngineException
     */
    private function execute(RequestInterface $request, ?string $url = null): HttpResponse
    {
        $params = $request->getParams();
        $headers = ['Content-Type' => $request->getContentType()];
        $encodedParams = json_encode($params);
        if ($encodedParams === false) {
            throw new InvalidParamException('请求参数 JSON 编码失败: ' . json_last_error_msg(), 400);
        }

        $targetUrl = $url ?? ($this->is_sandbox ? $this->box_url : $this->server_url) . $request->getUrl();
        return HttpRequest::curl(
            $targetUrl,
            $request->getMethod(),
            $encodedParams,
            $headers,
            ['read_timeout' => $request->getTimeout()]
        );
    }
}
