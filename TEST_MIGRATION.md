# 测试体系说明

当前测试分为 3 层：

## 1) Unit

路径：`tests/Unit`

目标：验证核心机制（模块发现、链式分发、异常分支、兼容别名）。

## 2) Module

路径：`tests/Module`

目标：按模块验证“请求对象构建正确”（URL/Method/Params）。

## 3) Integration

路径：`tests/Integration`

目标：真实请求烟雾测试（会输出接口响应数据）。

## 运行命令

```bash
composer test            # unit + module
composer test:unit
composer test:module
composer test:integration
composer test:all
composer test:integration:file -- tests/Integration/Account/AccountInfo/AccountInfoIntegrationTest.php
composer test:integration:file -- tests/Integration/JuLiangQianChuan/AdvertisingMgmt
```

`test:integration:file` 支持传入任意集成测试文件或目录，方便按模块逐步验证。

## 集成测试配置

读取项目根目录 `.env`：

- `TOKEN`
- `ADVERTISER_ID` / `ADVERTISER_IDS`
- `APPID`
- `SECRET`
- `AUTH_CODE`
- `REFRESH_TOKEN`
