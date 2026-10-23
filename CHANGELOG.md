# 更新日志

本文件记录项目的主要功能、修复、重构和文档更新。版本内容依据 Git 标签及其对应提交整理。

## [未发布] - 2026-10-23

- **工具接口**：新增工作台视频素材相关 API。
- **发布流程**：规范版本标签解析，统一处理带 `v` 和不带 `v` 前缀的语义化版本，避免版本排序错误。

## [4.0.3] - 2026-04-30

- **上传**：隔离上传请求与共享 HTTP 客户端，改用 cURL 文件 postfields 传输文件。
- **发布自动化**：新增支持标签触发、手动发布和自动递增版本的 GitHub Actions 发布流程。
- **发布自动化**：增加 Composer 校验、依赖安装和测试步骤，并支持创建、推送标签及发布 GitHub Release。
- **发布自动化**：增加发布并发控制，补充中文化的工作流描述和运行名称。

## [4.0.2] - 2026-04-30

- **Composer**：移除 `composer.json` 中显式维护的包版本号，改由发布标签管理版本。

## [4.0.1] - 2026-04-30

- **HTTP**：本地文件上传请求关闭自动重试，避免文件流在重试过程中产生不可预期行为。
- **文档**：更新 README 头部信息及上传重试说明。

## [4.0.0] - 2026-04-29

- **4.0 重构**：统一 PHP 8.0+ 类型声明、方法签名和接口定义。
- **HTTP 客户端**：将重试配置隔离到客户端实例，避免并发或多实例之间相互污染。
- **运行环境**：增加 FPM、CLI、Swoole 等运行环境的自动适配，并支持手动指定运行模式。
- **TLS**：支持通过客户端配置 TLS 证书校验开关及 CA 路径。
- **异常处理**：修复 JSON 编码失败时的异常类型问题，并同步异常对象的 code/message。
- **上传**：强化视频素材上传参数和文件校验。
- **测试**：补充 HTTP、OAuth、认证、接口请求结构和按模块执行的集成测试。
- **工程化**：改善 CI 中的模块发现、Composer 校验和 GitHub 自动化配置。
- **文档**：更新 README、配置说明、贡献指南和测试说明。

## [3.0.8] - 2026-02-12

- **升级版工作台**：新增工作台升级版接口能力，并将资产管理能力迁移到对应升级版模块。
- **商品管理**：新增 15 个 EBP 商品库接口。
- **小程序管理**：新增 14 个 EBP 小程序和小游戏接口。
- **应用管理**：新增 8 个 EBP 应用接口。
- **模块调用**：支持通过 `WorkspaceUpgrade()` 调用升级版工作台子模块。
- **文档与测试**：同步补充接口清单、调用说明和可执行测试脚本。

## [3.0.7] - 2026-01-07

- **全域计划调控**：新增 GlobalPlanningTool 相关接口，包括调控任务设置、预算、投放时长、直播一键控量计划创建及状态更新。
- **事件管理资产**：修复事件管理资产接口 URL 路径错误。
- **测试**：补充事件管理资产相关接口测试。

## [3.0.6] - 2025-10-24

- **千川全域推广**：新增获取商品全域可授权店铺列表接口。
- **千川全域推广**：新增广告主申请全域推广授权接口。
- **文档**：同步更新千川接口文档。

## [3.0.5] - 2025-10-21

- **随心推全域**：新增全域订单数据查询接口。
- **随心推全域**：新增全域订单素材列表查询接口。
- **文档**：同步更新千川接口文档。

## [3.0.4] - 2025-09-04

- **千川全域推广**：新增全域推广授权初始化接口，用于处理商品全域投放授权场景。
- **文档**：同步更新账号相关接口说明。

## [3.0.3] - 2025-08-29

- **千川全域推广**：新增 PC 端全域推广计划、素材、商品、抖音号授权和商品选择等管理接口。
- **随心推全域**：新增全域订单预算追加及续费建议延长投放时长接口。
- **文档**：同步更新千川全域推广接口说明。

## [3.0.2] - 2025-08-20

- **核心修复**：修复命名空间及空间命名问题。

## [3.0.1] - 2025-08-20

- **巨量千川**：新增财务相关接口。

## [3.0.0] - 2025-08-01

- **HTTP 客户端**：升级到 GuzzleHttp，并优化客户端配置管理。
- **随心推全域**：新增随心推全域 API。
- **核心修复**：修复 `OceanEngineException` 类型问题。
- **配置与文档**：补充安全的 `env.example`、配置指南，并修正文档路径、描述及过时内容。

## [2.0.0] - 2025-06-10

- **项目重构**：重构核心模块、模块自动加载、OAuth、请求参数处理和异常处理。
- **账号管理**：抽离通用 Account 模块，统一支持巨量广告、巨量千川、巨量本地推和巨量星图的账号能力。
- **巨量广告**：新增或完善广告主、代理商账号、工作台、资金流水、升级版广告、Dou+、订阅服务、数据报表、DMP 人群、商品、素材、建站、飞鱼线索、青鸟线索、数据上报和工具等 API。
- **巨量本地推**：新增本地推 API，并将其纳入通用模块结构。
- **工具与素材**：合并巨量广告、千川和本地推的工具及素材管理模块。
- **数据报表**：重构数据报表模块。
- **巨量星图与千川**：补充星图、千川文档及千川代理商账号和工作台账号管理接口。
- **测试与文档**：增加测试文件，优化测试示例、异常信息、README 和各模块接口文档。
- **兼容性**：优化 API 参数配置和核心类型安全，移除冗余参数方法及不再需要的依赖。

## [1.0.6] - 2026-04-30（历史标签）

> `1.0.6` 与 `4.0.3` 指向同一个 Git 提交。当前版本线应优先参考 `4.x` 标签。

- **上传**：隔离上传请求与共享 HTTP 客户端，改用 cURL 文件 postfields 传输文件。
- **发布自动化**：新增支持手动和自动版本发布的 GitHub Actions 工作流，并补充测试、标签和 Release 发布步骤。

## [v1.0.5] - 2025-06-02

- **OAuth**：修复 `OceanEngineAuth` 类型问题。
- **巨量千川**：新增获取全域建议预算和全域预估效果接口。

## [v1.0.4] - 2025-03-14

- **接口**：补充部分已上线接口能力。

## [v1.0.3] - 2025-02-23

- **OAuth**：修复 `OceanEngineAuth` 引用方式错误。

## [v1.0.2] - 2025-01-10

- **巨量星图**：新增巨量星图 API 和配套文档。
- **授权**：区分巨量千川与巨量广告的授权链接。
- **修复**：修正千川接口文件路径、方法名、账号信息说明和 README 内容。

## [v1.0.1] - 2025-01-10

- **项目元信息**：修正项目名称。

## [v1.0.0] - 2025-01-10

- **初始版本**：建立项目基础结构、测试配置和 README。
- **巨量千川**：完成账号管理相关接口及资金管理、资产管理、工具、数据推送、数据报表和广告管理等基础能力。
- **巨量星图**：补充基础 API 能力。
- **依赖**：移除不再需要的 Hyperf 和 GuzzleHttp 依赖。

[未发布]: https://github.com/westng/oceanengine-sdk-php/compare/4.0.3...HEAD
[4.0.3]: https://github.com/westng/oceanengine-sdk-php/releases/tag/4.0.3
[4.0.2]: https://github.com/westng/oceanengine-sdk-php/releases/tag/4.0.2
[4.0.1]: https://github.com/westng/oceanengine-sdk-php/releases/tag/4.0.1
[4.0.0]: https://github.com/westng/oceanengine-sdk-php/releases/tag/4.0.0
[3.0.8]: https://github.com/westng/oceanengine-sdk-php/releases/tag/3.0.8
[3.0.7]: https://github.com/westng/oceanengine-sdk-php/releases/tag/3.0.7
[3.0.6]: https://github.com/westng/oceanengine-sdk-php/releases/tag/3.0.6
[3.0.5]: https://github.com/westng/oceanengine-sdk-php/releases/tag/3.0.5
[3.0.4]: https://github.com/westng/oceanengine-sdk-php/releases/tag/3.0.4
[3.0.3]: https://github.com/westng/oceanengine-sdk-php/releases/tag/3.0.3
[3.0.2]: https://github.com/westng/oceanengine-sdk-php/releases/tag/3.0.2
[3.0.1]: https://github.com/westng/oceanengine-sdk-php/releases/tag/3.0.1
[3.0.0]: https://github.com/westng/oceanengine-sdk-php/releases/tag/3.0.0
[2.0.0]: https://github.com/westng/oceanengine-sdk-php/releases/tag/2.0.0
[1.0.6]: https://github.com/westng/oceanengine-sdk-php/releases/tag/1.0.6
[v1.0.5]: https://github.com/westng/oceanengine-sdk-php/releases/tag/v1.0.5
[v1.0.4]: https://github.com/westng/oceanengine-sdk-php/releases/tag/v1.0.4
[v1.0.3]: https://github.com/westng/oceanengine-sdk-php/releases/tag/v1.0.3
[v1.0.2]: https://github.com/westng/oceanengine-sdk-php/releases/tag/v1.0.2
[v1.0.1]: https://github.com/westng/oceanengine-sdk-php/releases/tag/v1.0.1
[v1.0.0]: https://github.com/westng/oceanengine-sdk-php/releases/tag/v1.0.0
