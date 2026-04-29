# 错误处理说明

## 主要异常

- `Core\Exception\InvalidParamException`：参数不合法
- `Core\Exception\OceanEngineException`：SDK 或请求过程异常
- `\BadMethodCallException`：链式方法/模块不存在
- `\InvalidArgumentException`：模块名无效

## 建议写法

```php
try {
    $resp = $client->Account()->AccountInfo->AdvertiserInfo()->setParams($params)->send();
} catch (\Core\Exception\OceanEngineException $e) {
    echo $e->getMessage();
} catch (\Throwable $e) {
    echo $e->getMessage();
}
```

## 常见排查

- 报“模块不存在”：检查 `src/Api` 下目录名
- 报“方法不存在”：检查链路中的目录/类名
- 报网络错误：检查 DNS、代理、出网权限
