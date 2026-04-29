# 配置说明

本 SDK 不依赖 `.env` 文件。生产环境建议通过配置中心、容器密钥、KMS/Vault 或系统环境变量注入配置，再在代码中显式传入。

## 一、客户端配置

```php
use OceanEngineSDK\OceanEngineClient;

$client = new OceanEngineClient(
    accessToken: $accessToken,
    isSandbox: false,
    serverUrl: 'https://api.oceanengine.com/open_api',
    boxUrl: 'https://api.oceanengine.com/open_api'
);
```

参数说明：

- `accessToken`：业务请求令牌
- `isSandbox`：是否走沙箱
- `serverUrl`：正式环境网关
- `boxUrl`：沙箱环境网关

## 二、授权客户端配置

```php
use OceanEngineSDK\OceanEngineAuth;

$auth = new OceanEngineAuth(
    app_id: $appId,
    secret: $secret,
    is_sandbox: false
);
```

参数说明：

- `app_id`：应用 ID
- `secret`：应用密钥
- `is_sandbox`：是否走沙箱

## 三、HTTP 与重试配置

```php
$client->setRetryConfig(
    maxRetries: 3,
    retryDelay: 1000,
    retryableStatusCodes: [408, 429, 500, 502, 503, 504],
    enableRetry: true,
    retryableBusinessCodes: [40100, 40110, 50000]
);

$client->setRetryEnabled(true);

// TLS 证书校验（默认 false）
$client->setVerify(false);                          // 关闭校验
$client->setVerify(true);                           // 启用系统 CA 校验
$client->setVerify(true, '/etc/ssl/custom-ca.pem'); // 启用并指定 CA 文件
```

说明：

- 重试与超时配置作用于**当前 `OceanEngineClient` 实例**。
- 多实例并发（多账号/多租户）场景下配置互不影响。
- `setVerify(bool $enabled, ?string $caPath = null)` 作用于当前实例：
  - `enabled=false`：关闭证书校验
  - `enabled=true` 且 `caPath` 为空：使用系统 CA 校验
  - `enabled=true` 且 `caPath` 非空：使用指定 CA 文件校验

## 四、生产配置建议

- 密钥不入库，不写死在代码中
- 凭据按环境隔离（dev/staging/prod）
- 统一做凭据轮换与审计
- 业务层自行管理 `advertiser_id` 等上下文参数
