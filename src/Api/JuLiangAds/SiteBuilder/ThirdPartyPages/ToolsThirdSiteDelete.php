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

namespace Api\JuLiangAds\SiteBuilder\ThirdPartyPages;

use Core\Exception\InvalidParamException;
use Core\Profile\RpcRequest;

/**
 * 删除第三方落地页站点.
 *
 * 通过此接口，用户可以删除第三方落地页站点。
 */
class ToolsThirdSiteDelete extends RpcRequest
{
    protected string $url = '/2/tools/third_site/delete/';

    protected string $method = 'POST';

    protected string $content_type = 'application/json';

    /**
     * 广告主ID.
     */
    protected int $advertiser_id;

    /**
     * 站点ID.
     */
    protected int $site_id;

    /**
     * @throws InvalidParamException
     */
    public function check(): void
    {
        if (! isset($this->advertiser_id)) {
            throw new InvalidParamException('client-check-error:Missing Required Arguments: advertiser_id', 400);
        }

        if (! isset($this->site_id)) {
            throw new InvalidParamException('client-check-error:Missing Required Arguments: site_id', 400);
        }
    }
}
