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

namespace Api\Account\AccountRel;

use Core\Profile\RpcRequest;

/**
 * Name 全域授权初始化
 * 全域授权初始化接口，用以解决部分在拉取达人/机构广告主可投的商品列表时返回商品不可投case（当前账户无使用该抖音号投放所选商品的全域推广权限）。
 * 每个 ADV 在 10 分钟内仅可调用一次，请控制请求频率，若滥用将限制接口调用。
 * Class QianChuanUniPromotionAuthInit.
 */
class QianChuanUniPromotionAuthInit extends RpcRequest
{
    protected string $url = '/v1.0/qianchuan/uni_promotion/auth/init/';

    protected string $method = 'POST';

    protected string $content_type = 'application/json';

    /**
     * 操作的广告主id.
     */
    protected int $advertiser_id;
}

// 兼容历史命名空间：Account\AccountRel\QianChuanUniPromotionAuthInit
if (! class_exists('Account\AccountRel\QianChuanUniPromotionAuthInit', false)) {
    class_alias(__NAMESPACE__ . '\QianChuanUniPromotionAuthInit', 'Account\AccountRel\QianChuanUniPromotionAuthInit');
}
