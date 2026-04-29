# JuLiangLocalPush 巨量本地推 API 结构

> 账号服务、数据报表、素材管理、工具 API 请移步对应的独立文档。
> 调用格式：`$client->JuLiangLocalPush()->{子模块}[->{子模块}]->{方法}()`

## LocalPushAds 本地推投放能力

### ProjectManagement 项目管理模块

| 方法名称                  | 调用方法                                      |
| ------------------------- | --------------------------------------------- |
| 获取本地推创编可用抖音号  | ProjectManagement->LocalAwemeAuthorizedGet()  |
| 查询本地推创编可用人群包  | ProjectManagement->LocalCustomAudienceGet()   |
| 根据多门店 ID 拉取门店 ID | ProjectManagement->LocalMultiPoiIdPoiIdsGet() |
| 获取可投门店列表          | ProjectManagement->LocalPoiGet()              |
| 获取可投商品列表          | ProjectManagement->LocalProductGet()          |
| 创建项目                  | ProjectManagement->LocalProjectCreate()       |
| 获取项目详情              | ProjectManagement->LocalProjectDetail()       |
| 获取项目列表              | ProjectManagement->LocalProjectList()         |
| 批量更新项目状态          | ProjectManagement->LocalProjectStatusUpdate() |
| 更新项目                  | ProjectManagement->LocalProjectUpdate()       |

### AdsManagement 广告管理模块

| 方法名称                      | 调用方法                                    |
| ----------------------------- | ------------------------------------------- |
| 根据门店 ID 查询门店下商品 ID | AdsManagement->LocalProductGetByPoiids()    |
| 创建广告                      | AdsManagement->LocalPromotionCreate()       |
| 获取广告详情                  | AdsManagement->LocalPromotionDetail()       |
| 获取广告列表                  | AdsManagement->LocalPromotionStatusUpdate() |
| 批量更新广告状态              | AdsManagement->getProjectList()             |
| 更新广告                      | AdsManagement->LocalPromotionUpdate()       |

## LocalPushReports 本地推数据报表

| 方法名称               | 调用方法                                    |
| ---------------------- | ------------------------------------------- |
| 查询账户数据           | LocalPushReports->LocalReportAccountGet()   |
| 获取本地推受众分析数据 | LocalPushReports->LocalReportAudienceGet()  |
| 获取素材数据           | LocalPushReports->LocalReportMaterialGet()  |
| 获取项目数据           | LocalPushReports->LocalReportProjectGet()   |
| 获取广告数据           | LocalPushReports->LocalReportPromotionGet() |

## LocalPushMaterials 本地推素材管理

| 方法名称                   | 调用方法                                           |
| -------------------------- | -------------------------------------------------- |
| 异步上传本地推视频         | LocalPushMaterials->LocalFileUploadTaskCreate()    |
| 获取抖音主页视频           | LocalPushMaterials->LocalFileVideoAwemeGet()       |
| 获取素材库视频             | LocalPushMaterials->LocalFileVideoGet()            |
| 上传视频                   | LocalPushMaterials->LocalFileVideoUpload()         |
| 查询异步上传本地推视频结果 | LocalPushMaterials->LocalFileVideoUploadTaskList() |

## LocalPushLeads 本地推线索管理

| 方法名称           | 调用方法                                |
| ------------------ | --------------------------------------- |
| 本地推线索回传     | LocalPushLeads->ToolsClueLifeCallback() |
| 获取本地推线索列表 | LocalPushLeads->ToolsClueLifeGet()      |
