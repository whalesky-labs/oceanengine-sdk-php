# Marketing PHP SDK

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.0-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Composer](https://img.shields.io/badge/Composer-Ready-orange.svg)](https://getcomposer.org/)

> 🚀 **巨量引擎开放平台 PHP SDK** - 为 PHP 开发者提供完整的巨量引擎 API 集成解决方案

## 📖 概述

在技术蓬勃发展的当下，巨量引擎开放平台的 Marketing API SDK，本应是普惠所有开发者的得力工具，涵盖巨量广告、巨量千川、巨量本地推、巨量星图及企业号相关功能，从 Token 获取到请求封装、响应解释，每个环节都暗藏助力高效开发的玄机。

其本地化设计，理应为开发者开辟便捷通道，无论经验如何，都能借它在 API 调用之路上畅行无阻。可现实却令人咋舌，面对 PHP 这片高手云集、活力满满的领域，官方竟然缺失 PHP 版本的 SDK！

这简直荒谬至极。PHP 开发者们为互联网立下汗马功劳，如今却像被抛弃的孩子。看着其他语言开发者仗着官方 SDK 大步快跑，自己只能徒手在荆棘中挣扎，太不公平！好比马拉松赛场，别人装备精良、补给充足，自己却赤脚前行、无水可饮。

幸有补救性 SDK，让 PHP 开发者不至于掉队，能凭本事搭起投放管理系统，但背后是他们付出的诸多额外心血。官方这种"偏心"做法，实在该反省，给 PHP 开发者们一个交代！

## ✨ 特性

- 🎯 **完整覆盖**：支持巨量广告、千川、本地推、星图等全平台 API
- ⚡ **高性能**：基于 GuzzleHttp，支持连接复用和智能重试
- 🧠 **运行时自适配**：自动识别 FPM/CLI/Swoole 运行环境并调整连接复用策略
- 🔧 **易配置**：支持环境变量、配置文件等多种配置方式
- 🛡️ **强健性**：内置错误处理、重试机制、频控处理
- 📚 **文档完善**：详细的使用文档和示例代码
- 🔄 **现代化**：支持 PHP 8.0+，使用现代 PHP 特性

## 🚀 快速开始

### 环境要求

- **PHP >= 8.0**
- **GuzzleHttp 7.0+** (自动安装)
- **推荐使用 Composer** 安装依赖

### 安装

```bash
composer require westng/oceanengine-sdk-php
```

### 基础使用

```php
<?php

use Core\Exception\OceanEngineException;
use OceanEngineSDK\OceanEngineClient;

// 创建客户端
$client = new OceanEngineClient('your_access_token');

// 配置重试机制
$client->setRetryConfig(
    maxRetries: 3,
    retryDelay: 1000,
    enableRetry: true
);

// 可选：显式配置 TLS 证书校验（默认已开启）
$client->setVerify(true);

// 调用 API
try {
    $response = $client->Account()
        ->AccountInfo
        ->AdvertiserInfo()
        ->setParams(['account_ids' => ['123456789']])
        ->send();

    $data = json_decode($response->getBody(), true);
    print_r($data);
} catch (OceanEngineException $e) {
    echo "错误: {$e->getErrorMessage()}";
}
```

## 📋 使用条件

### 运行环境自适配

- 默认 `auto`：自动识别 `fpm`/`cli`/`swoole`
- 在 Swoole 协程环境下默认关闭全局 Client 池复用，降低跨协程共享风险
- 可通过 `Core\Http\HttpRequest::setRuntimeMode('fpm|cli|swoole|auto')` 手动覆盖
- 也可通过环境变量 `OCEANENGINE_RUNTIME_MODE` 指定运行模式

### 开发者条件

- 使用 SDK 需要首先注册成为巨量引擎开发者，请参考[开发者快速入门文档](https://open.oceanengine.com/labels/7/docs/1696710498372623)
- 使用 SDK 需要先拥有 API 的访问权限，所有 SDK 的使用与应用拥有的权限组相关联

## 📁 项目结构

```
oceanengine-sdk-php/
├── 📚 docs/                                    # 接口文档
│   ├── ACCOUNT.md                              # 账号服务文档
│   ├── DATAREPORTS.md                          # 数据报表文档
│   ├── JULIANGADS.md                           # 巨量广告文档
│   ├── JULIANGQIANCHUAN.md                     # 巨量千川文档
│   ├── JULIANGLOCALPUSH.md                     # 巨量本地推文档
│   ├── JULIANGSTARMAP.md                       # 巨量星图文档
│   ├── MATERIALS.md                            # 素材管理文档
│   └── TOOLS.md                                # 工具文档
├── 🔧 src/                                     # 源代码
│   ├── Oauth/                                  # OAuth 认证
│   │   ├── GetAccessToken.php                  # 获取 AccessToken
│   │   └── RefreshToken.php                    # 刷新 AccessToken
│   ├── Core/                                   # 核心包
│   │   ├── Autoloader/                         # 自动加载器
│   │   ├── Exception/                          # 异常处理
│   │   ├── Helper/                             # 助手函数
│   │   ├── Http/                               # HTTP请求 (GuzzleHttp)
│   │   └── Profile/                            # 核心配置
│   └── Api/                                    # API 模块
│       ├── Account/                            # 账号管理
│       ├── JuLiangAds/                         # 巨量广告
│       ├── JuLiangQianChuan/                   # 巨量千川
│       ├── JuLiangStarMap/                     # 巨量星图
│       ├── JuLiangLocalPush/                   # 巨量本地推
│       ├── EnterpriseAccount/                  # 企业号
│       ├── Materials/                          # 素材管理
│       ├── DataReports/                        # 数据报表
│       └── Tools/                              # 工具
├── 🧪 tests/                                   # 测试文件
│   ├── config/                                 # 测试配置
│   │   ├── ConfigManager.php                   # 配置管理器
│   │   └── load_config.php                     # 配置加载器
│   └── ...                                     # 各模块测试文件
├── 📄 .env.example                             # 环境变量示例
├── 📄 composer.json                            # Composer 配置
├── 📄 LICENSE                                  # 开源协议
├── 📄 README.md                                # 项目说明
├── 📄 HTTP_MIGRATION.md                        # HTTP迁移文档
├── 📄 CONFIG_GUIDE.md                          # 配置管理指南
├── 📄 ERROR_CODES_GUIDE.md                     # 错误码说明
└── 📄 TEST_MIGRATION.md                        # 测试文件迁移指南
```

## 📚 详细使用指南

### 1. 用户授权（获取 Access Token）

#### 获取授权 URL

| 参数     | 说明                           | 默认值             | 示例值              |
| -------- | ------------------------------ | ------------------ | ------------------- |
| `cb_url` | 回调链接                       | -                  | https://xxx.xxx.xxx |
| `scope`  | 授权范围（全部权限 null 即可） | -                  | -                   |
| `state`  | 自定义参数                     | your_custom_params | -                   |
| `type`   | 授权类型                       | QC                 | `QC`｜`AD`          |

```php
<?php

use Core\Exception\OceanEngineException;
use OceanEngineSDK\OceanEngineAuth;

$auth = new OceanEngineAuth(APPID, SECRET);
$url = $auth->getAuthCodeUrl(CALLBACK_URL, '', 'auth_code', 'AD');
echo "授权链接: {$url}";
```

#### 获取 Access Token

```php
<?php

use Core\Exception\OceanEngineException;
use OceanEngineSDK\OceanEngineAuth;

$auth = new OceanEngineAuth(APPID, SECRET);

// 使用授权码获取 token
$tokenData = $auth->getAccessToken($authCode);

// 刷新 token
$newTokenData = $auth->refreshToken($refreshToken);
```

### 2. API 调用

#### 配置重试机制

| 参数                     | 说明               | 默认值                         | 示例值          |
| ------------------------ | ------------------ | ------------------------------ | --------------- |
| `maxRetries`             | 最大重试次数       | 3                              | 5               |
| `retryDelay`             | 重试延迟（毫秒）   | 1000                           | 2000            |
| `retryableStatusCodes`   | 可重试 HTTP 状态码 | [408, 429, 500, 502, 503, 504] | [408, 429, 500] |
| `enableRetry`            | 是否启用重试       | true                           | false           |
| `retryableBusinessCodes` | 可重试业务错误码   | [40100, 40110, 50000]          | [40100, 50000]  |

```php
<?php

$client = new OceanEngineClient(TOKEN);

// 配置重试机制
$client->setRetryConfig(
    maxRetries: 3,
    retryDelay: 1000,
    retryableStatusCodes: [408, 429, 500, 502, 503, 504],
    enableRetry: true,
    retryableBusinessCodes: [40100, 40110, 50000]
);

// 动态控制重试开关
$client->setRetryEnabled(true);   // 启用重试
$client->setRetryEnabled(false);  // 禁用重试
```

说明：重试与超时配置只作用于当前 `OceanEngineClient` 实例，多实例并发互不影响。

#### 配置 TLS 证书校验

| 方法参数 | 说明 | 示例 |
| -------- | ---- | ---- |
| `enabled` | 是否启用证书校验 | `true` / `false` |
| `caPath` | CA 证书路径（仅 `enabled=true` 时生效） | `/etc/ssl/custom-ca.pem` |

#### 文件上传请求

对于素材、资质、券码等文件上传接口，不要使用 `application/json` 传本地文件。SDK 支持以下两种文件参数写法，并会自动按 `multipart/form-data` 发送：

```php
<?php

$videoPath = '/absolute/path/to/video.mp4';

$response = $client->Materials()
    ->ImageVideoMgmt
    ->FileVideoAd()
    ->setParams([
        'advertiser_id' => 123456789,
        'upload_type' => 'UPLOAD_BY_FILE',
        'video_file' => '@' . $videoPath,
        'video_signature' => md5_file($videoPath),
    ])
    ->send();
```

也支持：

```php
'video_file' => new \CURLFile($videoPath, 'video/mp4', basename($videoPath))
```

其中 `FileVideoAd` 在 `UPLOAD_BY_FILE` 模式下必须传 `video_file` 和 `video_signature`；在 `UPLOAD_BY_URL` 模式下必须传 `video_url`。

```php
<?php

$client = new OceanEngineClient(TOKEN);

$client->setVerify(false);                          // 显式关闭证书校验（不推荐，仅兼容历史环境）
$client->setVerify(true);                           // 默认行为：启用系统 CA 校验
$client->setVerify(true, '/etc/ssl/custom-ca.pem'); // 启用并指定 CA 文件
```

说明：

- 当前默认值为 `true`，默认使用系统 CA 进行证书校验。
- 仅在明确需要兼容历史环境时再关闭证书校验，避免中间人攻击风险。

#### 获取广告主信息

```php
<?php

use Core\Exception\OceanEngineException;
use OceanEngineSDK\OceanEngineClient;

try {
    $client = new OceanEngineClient(TOKEN);

    $response = $client->Account()
        ->AccountInfo
        ->AdvertiserInfo()
        ->setParams([
            'account_ids' => ['123456789'],
            'fields' => ['id', 'name', 'status']
        ])
        ->send();

    $data = json_decode($response->getBody(), true);
    print_r($data);
} catch (OceanEngineException $e) {
    echo "错误类型: {$e->getErrorType()}\n";
    echo "错误码: {$e->getErrorCode()}\n";
    echo "错误信息: {$e->getErrorMessage()}\n";
}
```

## 🔧 配置说明

### 环境变量配置

创建 `.env` 文件：

```env
# API 配置
APPID=your_app_id
SECRET=your_app_secret
TOKEN=your_access_token
REFRESH_TOKEN=your_refresh_token

# 广告主配置
ADVERTISER_IDS=123456789,987654321

# HTTP 配置
HTTP_CONNECT_TIMEOUT=20
HTTP_READ_TIMEOUT=30
HTTP_MAX_RETRIES=3
HTTP_RETRY_DELAY=1000
HTTP_ENABLE_RETRY=true
HTTP_RETRYABLE_BUSINESS_CODES=40100,40110,50000
```

### 配置优先级

1. **系统环境变量** (最高优先级)
2. **.env 文件**
3. **默认配置** (最低优先级)

## 🚨 错误处理

SDK 提供了完善的错误处理机制：

```php
try {
    $response = $client->Account()->AccountInfo->AdvertiserInfo()
        ->setParams($args)
        ->send();
} catch (OceanEngineException $e) {
    // 处理业务错误
    echo "错误类型: {$e->getErrorType()}\n";
    echo "错误码: {$e->getErrorCode()}\n";
    echo "错误信息: {$e->getErrorMessage()}\n";
} catch (Exception $e) {
    // 处理其他错误
    echo "系统错误: {$e->getMessage()}\n";
}
```

## 📊 开发进度

| **模块**   | **链式调用**                     | **状态**  | **文档**                             |
| ---------- | -------------------------------- | --------- | ------------------------------------ |
| 账户管理   | `$client->Account()`             | ✅ 已完成 | [查看文档](docs/ACCOUNT.md)          |
| 素材管理   | `$client->Materials()`           | ✅ 已完成 | [查看文档](docs/MATERIALS.md)        |
| 数据报表   | `$client->DataReports()`         | ✅ 已完成 | [查看文档](docs/DATAREPORTS.md)      |
| 工具       | `$client->Tools()`               | ✅ 已完成 | [查看文档](docs/TOOLS.md)            |
| 巨量广告   | `$client->JuLiangAds()`          | ✅ 已完成 | [查看文档](docs/JULIANGADS.md)       |
| 巨量千川   | `$client->JuLiangQianChuan()`    | ✅ 已完成 | [查看文档](docs/JULIANGQIANCHUAN.md) |
| 巨量星图   | `$client->JuLiangStarMap()`      | ✅ 已完成 | [查看文档](docs/JULIANGSTARMAP.md)   |
| 巨量本地推 | `$client->JuLiangLocalPush()`    | ✅ 已完成 | [查看文档](docs/JULIANGLOCALPUSH.md) |
| 企业号     | `$client->EnterpriseAccount()`   | ⏳ 未开始 | -                                    |

> ⚠️ 上述进度仅供参考，实际以源码为准。  
> 🧠 欢迎查看源码深入探索，接口比文档更诚实！

## 📚 相关文档

- [🚀 HTTP 迁移指南](HTTP_MIGRATION.md) - 了解从 cURL 到 GuzzleHttp 的迁移
- [⚙️ 配置管理指南](CONFIG_GUIDE.md) - 学习如何使用.env 文件和配置管理
- [🚨 错误码说明](ERROR_CODES_GUIDE.md) - 了解 HTTP 状态码和业务错误码的处理
- [🧪 测试文件迁移指南](TEST_MIGRATION.md) - 了解测试文件的现代化改进

## 🤝 贡献指南

欢迎提交 Issue 和 Pull Request，建议遵循以下规范。

### 1 分支规范

- 基于最新 `4.x` 分支开发。
- 分支命名建议：`feat/xxx`、`fix/xxx`、`refactor/xxx`、`docs/xxx`。

### 2 提交信息规范（Conventional Commits）

提交格式：

```text
type(scope): subject
```

常用 `type`：

- `feat`：新增功能
- `fix`：缺陷修复
- `refactor`：重构（不改行为）
- `docs`：文档更新
- `test`：测试补充/调整
- `perf`：性能优化
- `chore`：工程维护（依赖、脚本、配置等）

示例：

```text
feat(account): 新增广告主账户查询接口
fix(http): 修复并发场景下重试配置互相污染
docs(readme): 补充链式调用与模块说明
test(integration): 增加 OAuth 刷新 token 用例
```

### 3 提交前检查

- 本地执行：`composer test`
- 格式检查：`vendor/bin/php-cs-fixer fix --dry-run --sequential`
- 若涉及行为变更，请同步更新 `README.md` 或 `docs/*`

### 4 Pull Request 要求

- 标题清晰说明“做了什么、为什么做”
- 描述中包含影响范围（模块/接口）与验证方式
- 若是破坏性变更，请明确标注 `BREAKING CHANGE`

## 📄 开源协议

本项目采用 MIT 协议 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 📞 问题反馈

如在使用过程中遇到问题、建议或灵感……请无视（开玩笑的）。

> 📨 实在憋不住可以提 Issue 或 PR ～

---

**如果这个项目对您有帮助，请给个 ⭐️ 支持一下！**

Made with ❤️ by [westng](https://github.com/westng)
