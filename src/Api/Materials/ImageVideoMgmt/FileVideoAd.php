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

namespace Api\Materials\ImageVideoMgmt;

use Core\Exception\InvalidParamException;
use Core\Helper\RequestCheckUtil;
use Core\Profile\RpcRequest;

/**
 * Name 上传视频素材.
 *
 * 通过此接口，用户可以上传和广告相关的素材视频。
 *
 * 建议：
 * 对于连山url链接上传视频，请及时切换至异步上传接口。
 * Class FileVideoAd.
 */
class FileVideoAd extends RpcRequest
{
    protected string $url = '/2/file/video/ad/';

    protected string $method = 'POST';

    protected string $content_type = 'multipart/form-data';

    /**
     * 广告主ID.
     */
    protected int $advertiser_id;

    /**
     * @throws InvalidParamException
     */
    public function check(): void
    {
        RequestCheckUtil::checkNotNull($this->params['advertiser_id'] ?? null, 'advertiser_id');

        $uploadType = (string) ($this->params['upload_type'] ?? 'UPLOAD_BY_FILE');
        $this->params['upload_type'] = $uploadType;

        RequestCheckUtil::checkAllowField($uploadType, ['UPLOAD_BY_FILE', 'UPLOAD_BY_URL'], 'upload_type');

        if ($uploadType === 'UPLOAD_BY_FILE') {
            RequestCheckUtil::checkNotNull($this->params['video_file'] ?? null, 'video_file');
            RequestCheckUtil::checkNotNull($this->params['video_signature'] ?? null, 'video_signature');
            RequestCheckUtil::checkUploadFile($this->params['video_file'], 'video_file');
            RequestCheckUtil::checkMd5($this->params['video_signature'], 'video_signature');
            return;
        }

        RequestCheckUtil::checkNotNull($this->params['video_url'] ?? null, 'video_url');

        if (isset($this->params['filename'])) {
            RequestCheckUtil::checkMaxLength((string) $this->params['filename'], 255, 'filename');
        }
    }
}
