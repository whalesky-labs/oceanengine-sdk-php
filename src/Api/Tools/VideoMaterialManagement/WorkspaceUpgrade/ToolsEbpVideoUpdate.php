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

namespace Api\Tools\VideoMaterialManagement\WorkspaceUpgrade;

use Core\Profile\RpcRequest;

/**
 * 升级版工作台更新视频.
 */
class ToolsEbpVideoUpdate extends RpcRequest
{
    protected string $url = '/v3.0/tools/ebp/video/update/';

    protected string $method = 'POST';

    protected string $content_type = 'application/json';
}
