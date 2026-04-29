# HTTP 说明

SDK 当前使用 Guzzle 实现 HTTP 请求（`Core\Http\HttpRequest`）。

> 重试/超时配置按 `OceanEngineClient` 实例隔离，不再是全局静态共享配置。

## 支持能力

- GET / POST / PUT / PATCH
- JSON、表单、文件上传
- 可配置重试策略
- 超时控制

## 重试配置

```php
$client->setRetryConfig(
    maxRetries: 3,
    retryDelay: 1000,
    retryableStatusCodes: [408, 429, 500, 502, 503, 504],
    enableRetry: true,
    retryableBusinessCodes: [40100, 40110, 50000]
);
```

该配置仅影响当前客户端实例，适用于并发多实例场景。

## 关闭重试

```php
$client->setRetryEnabled(false);
```

## 并发多实例示例

```php
use OceanEngineSDK\OceanEngineClient;

$clientA = new OceanEngineClient($tokenA);
$clientA->setRetryConfig(
    maxRetries: 1,
    retryDelay: 200,
    enableRetry: false
);

$clientB = new OceanEngineClient($tokenB);
$clientB->setRetryConfig(
    maxRetries: 5,
    retryDelay: 1500,
    enableRetry: true
);

// A 和 B 的重试配置互不影响，可在并发场景安全共存
```
