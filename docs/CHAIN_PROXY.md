# ChainProxy 机制

## 目的

移除大量重复 `Module.php`，统一由 `ChainProxy` 处理链式路由。

## 工作方式

- 顶层：`OceanEngineClient::module()` / 模块同名方法
- 中间层：`__get` 根据目录递归发现子节点
- 叶子层：`__call` 实例化请求类（`RpcRequest` 子类）

## 兼容行为

- 支持跨层短链（历史调用）
- 支持部分子目录请求类的兼容路由
- 旧命名空间别名兼容（例如 `Account\...`）

## 示例

```php
$client->JuLiangAds()->WorkspaceUpgrade->DpaLibraryList();
$client->Tools()->CommentMgmt->ToolsCommentTermsBannedAdd();
```
