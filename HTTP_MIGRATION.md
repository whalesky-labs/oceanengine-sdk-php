# HTTP 说明

SDK 当前使用 Guzzle 实现 HTTP 请求（`Core\Http\HttpRequest`）。

> 重试/超时配置按 `OceanEngineClient` 实例隔离，不再是全局静态共享配置。

## 支持能力

- GET / POST / PUT / PATCH
- JSON、表单、文件上传
- 可配置重试策略
- 超时控制

## Swoole 与传输配置

SDK 检测到协程启用 legacy cURL Hook（`SWOOLE_HOOK_CURL`）时，默认发送 `Accept-Encoding: identity` 请求未压缩响应。CLI 和 Native cURL Hook 保持 Guzzle 的常规编码协商。`getJsonBody()` 会严格校验 JSON。

`OceanEngineClient` 和 `OceanEngineAuth` 构造函数都支持可选的 `httpHandler` 和 `responseLogger` 参数。`httpHandler` 接收 Guzzle Handler，适用于常规请求，SDK 仍负责请求选项、重试和 HTTP 错误处理。包含本地文件的上传沿用独立的 cURL 传输。

```php
$client = new \OceanEngineSDK\OceanEngineClient(
    $token,
    httpHandler: $handler,
    responseLogger: $logger
);
```

`responseLogger` 接收 PSR-3 Logger。启用后，SDK 以 `debug` 级别记录 HTTP 状态、响应编码、响应声明的长度和交给调用方的响应体字节数。没有声明的编码或长度记录为 `null`。

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
