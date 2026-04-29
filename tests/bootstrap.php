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
error_reporting(E_ALL);

require_once __DIR__ . '/../index.php';

$configuredRuntimeMode = getenv('OCEANENGINE_RUNTIME_MODE');
$configuredRuntimeMode = $configuredRuntimeMode === false || $configuredRuntimeMode === ''
    ? '(not set)'
    : $configuredRuntimeMode;

fwrite(
    STDOUT,
    sprintf(
        "[测试环境]\n- PHP版本: %s\n- SAPI类型: %s\n- SDK运行模式: %s\n- OCEANENGINE运行模式: %s\n",
        PHP_VERSION,
        PHP_SAPI,
        \Core\Http\HttpRequest::getRuntimeMode(),
        $configuredRuntimeMode
    )
);
