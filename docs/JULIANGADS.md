# JuLiangAds 巨量广告 API 结构

> 调用格式：`$client->JuLiangAds()->{子模块}[->{子模块}]->{方法}()`

## AdsPro 巨量广告升级版

### AdAccountBudget 广告账户预算

| 方法名称       | 调用方法                                  |
| -------------- | ----------------------------------------- |
| 更新账户日预算 | AdAccountBudget->AdvertiserUpdateBudget() |
| 获取账户日预算 | AdAccountBudget->AdvertiserBudgetGet()    |

### AdManagement 广告管理模块

| 方法名称             | 调用方法                                      |
| -------------------- | --------------------------------------------- |
| 删除广告下素材       | AdManagement->PromotionMaterialDelete()       |
| 批量更新广告投放时段 | AdManagement->PromotionScheduleTimeUpdate()   |
| 获取品牌信息         | AdManagement->CdpBrandGet()                   |
| 获取成本保障状态     | AdManagement->PromotionCostProtectStatusGet() |
| 更新素材状态         | AdManagement->MaterialStatusUpdate()          |
| 获取广告审核拒绝原因 | AdManagement->PromotionRejectReasonGet()      |
| 删除广告             | AdManagement->PromotionDelete()               |
| 更新广告状态         | AdManagement->PromotionStatusUpdate()         |
| 更新广告深度出价     | AdManagement->PromotionDeepBidUpdate()        |
| 更新广告出价         | AdManagement->PromotionBidUpdate()            |
| 更新广告预算         | AdManagement->PromotionBudgetUpdate()         |
| 获取广告列表         | AdManagement->PromotionList()                 |
| 更新广告             | AdManagement->PromotionUpdate()               |
| 创建广告             | AdManagement->PromotionCreate()               |

### ProjectManagement 项目管理模块

| 方法名称             | 调用方法                                         |
| -------------------- | ------------------------------------------------ |
| 批量更新项目投放时段 | ProjectManagement->ProjectWeekScheduleUpdate()   |
| 更新项目             | ProjectManagement->ProjectUpdate()               |
| 更新项目状态         | ProjectManagement->ProjectStatusUpdate()         |
| 更新项目投放时段     | ProjectManagement->ProjectScheduleTimeUpdate()   |
| 更新项目 ROI 目标    | ProjectManagement->ProjectRoiGoalUpdate()        |
| 获取项目列表         | ProjectManagement->ProjectList()                 |
| 删除项目             | ProjectManagement->ProjectDelete()               |
| 创建项目             | ProjectManagement->ProjectCreate()               |
| 获取项目成本保障状态 | ProjectManagement->ProjectCostProtectStatusGet() |
| 更新项目预算         | ProjectManagement->ProjectBudgetUpdate()         |
| 更新预算组           | ProjectManagement->BudgetGroupUpdate()           |
| 获取预算组列表       | ProjectManagement->BudgetGroupList()             |
| 删除预算组           | ProjectManagement->BudgetGroupDelete()           |
| 创建预算组           | ProjectManagement->BudgetGroupCreate()           |

### SearchAdTool 搜索广告投放工具

#### 基础工具

| 方法名称                 | 调用方法                                 |
| ------------------------ | ---------------------------------------- |
| 获取推荐关键词           | SearchAdTool->ToolsSuggWords()           |
| 获取蓝海流量包           | SearchAdTool->ToolsBlueFlowPackageList() |
| 获取广告下可用蓝海关键词 | SearchAdTool->ToolsBlueFlowKeywordList() |

#### KeywordManagement 关键词管理

| 方法名称       | 调用方法                                         |
| -------------- | ------------------------------------------------ |
| 获取关键词列表 | SearchAdTool->KeywordManagement->KeywordList()   |
| 删除关键词     | SearchAdTool->KeywordManagement->KeywordDelete() |
| 更新关键词     | SearchAdTool->KeywordManagement->KeywordUpdate() |
| 创建关键词     | SearchAdTool->KeywordManagement->KeywordCreate() |

#### AccountKeywordBoost 账户关键词提升

| 方法名称           | 调用方法                                                         |
| ------------------ | ---------------------------------------------------------------- |
| 获取关键词项目信息 | SearchAdTool->AccountKeywordBoost->ToolsKeywordsProjectInfoGet() |
| 获取关键词出价比例 | SearchAdTool->AccountKeywordBoost->ToolsKeywordsBidRatioGet()    |
| 删除关键词出价比例 | SearchAdTool->AccountKeywordBoost->ToolsKeywordsBidRatioDelete() |
| 更新关键词出价比例 | SearchAdTool->AccountKeywordBoost->ToolsKeywordsBidRatioUpdate() |
| 创建关键词出价比例 | SearchAdTool->AccountKeywordBoost->ToolsKeywordsBidRatioCreate() |

#### NegativeKeywords 否定词管理

| 方法名称       | 调用方法                                                            |
| -------------- | ------------------------------------------------------------------- |
| 添加广告否定词 | SearchAdTool->NegativeKeywords->ToolsPrivativeWordPromotionAdd()    |
| 更新项目否定词 | SearchAdTool->NegativeKeywords->ToolsPrivativeWordProjectUpdate()   |
| 批量获取否定词 | SearchAdTool->NegativeKeywords->ToolsPrivativeWordBatchGet()        |
| 更新广告否定词 | SearchAdTool->NegativeKeywords->ToolsPrivativeWordPromotionUpdate() |
| 添加项目否定词 | SearchAdTool->NegativeKeywords->ToolsPrivativeWordProjectAdd()      |

### AssetManagement 资产管理

| 方法名称                                 | 调用方法                                               |
| ---------------------------------------- | ------------------------------------------------------ |
| 广告素材预审结果查询（连山云视频点播版） | AssetManagement->OpenMaterialAuditProGet()             |
| 广告素材预审提交接口（连山云视频点播版） | AssetManagement->OpenMaterialAuditProSubmit()          |
| 广告主轮询任务结果                       | AssetManagement->DiagnosisTaskAdvGet()                 |
| 广告主获取前测任务列表                   | AssetManagement->DiagnosisTaskAdvList()                |
| 创建广告主诊断任务                       | AssetManagement->DiagnosisTaskAdvCreate()              |
| 获取代理商诊断任务                       | AssetManagement->DiagnosisTaskAgentGet()               |
| 获取代理商诊断任务列表                   | AssetManagement->DiagnosisTaskAgentList()              |
| 创建代理商诊断任务                       | AssetManagement->DiagnosisTaskAgentCreate()            |
| 代理商批量暂停明点无效素材               | AssetManagement->FileVideoPause()                      |
| 上传代理商视频                           | AssetManagement->FileVideoAgent()                      |
| 获取代理商视频                           | AssetManagement->FileVideoAgentGet()                   |
| 获取轮播图信息                           | AssetManagement->CarouselAdGet()                       |
| 更新轮播图                               | AssetManagement->CarouselUpdate()                      |
| 获取轮播图列表                           | AssetManagement->CarouselList()                        |
| 创建轮播图                               | AssetManagement->CarouselCreate()                      |
| 上传广告音频                             | AssetManagement->FileAudioAd()                         |
| 获取音频信息                             | AssetManagement->FileAudioGet()                        |
| 获取素材清理任务结果                     | AssetManagement->FileVideoMaterialClearTaskResultGet() |
| 获取素材清理任务                         | AssetManagement->FileVideoMaterialClearTaskGet()       |
| 创建素材清理任务                         | AssetManagement->FileVideoMaterialClearTaskCreate()    |
| 获取素材属性列表                         | AssetManagement->FileMaterialAttributesList()          |
| 获取素材详情                             | AssetManagement->FileMaterialDetail()                  |
| 获取素材列表                             | AssetManagement->FileMaterialList()                    |
| 获取视频效率数据                         | AssetManagement->FileVideoEfficiencyGet()              |
| 获取视频抖音数据                         | AssetManagement->FileVideoAwemeGet()                   |
| 更新视频信息                             | AssetManagement->FileVideoUpdate()                     |
| 删除轮播图                               | AssetManagement->CarouselDelete()                      |
| 删除图片                                 | AssetManagement->FileImageDelete()                     |
| 删除视频                                 | AssetManagement->FileVideoDelete()                     |
| 绑定素材                                 | AssetManagement->FileMaterialBind()                    |
| 获取广告视频                             | AssetManagement->FileVideoAdGet()                      |
| 获取视频封面建议                         | AssetManagement->ToolsVideoCoverSuggest()              |
| 获取视频上传任务列表                     | AssetManagement->FileVideoUploadTaskList()             |
| 创建上传任务                             | AssetManagement->FileUploadTaskCreate()                |
| 上传广告视频                             | AssetManagement->FileVideoAd()                         |
| 上传广告图片                             | AssetManagement->FileImageAd()                         |
| 获取广告图片                             | AssetManagement->FileImageAdGet()                      |
| 获取视频信息                             | AssetManagement->FileVideoGet()                        |
| 获取图片信息                             | AssetManagement->FileImageGet()                        |

### ProductManagement 商品管理

#### 基础接口

| 方法名称                     | 调用方法                                        |
| ---------------------------- | ----------------------------------------------- |
| 上传短剧剧目                 | ProductManagement->DpaAlbumCreate()             |
| 查询短剧可投状态             | ProductManagement->DpaAlbumStatusGet()          |
| 获取商品投放条件列表（线索版） | ProductManagement->DpaAssetV2List()             |
| 获取投放条件详情（通用版）   | ProductManagement->DpaAssetsDetailRead()        |
| 获取投放条件列表（通用版）   | ProductManagement->DpaAssetsList()              |
| 获取DPA分类                  | ProductManagement->DpaCategoryGet()             |
| 删除升级版商品               | ProductManagement->DpaClueProductDelete()       |
| 获取升级版商品详情           | ProductManagement->DpaClueProductDetail()       |
| 获取升级版商品列表           | ProductManagement->DpaClueProductList()         |
| 创建/编辑升级版商品          | ProductManagement->DpaClueProductSave()         |
| 获取商品列表                 | ProductManagement->DpaDetailGet()               |
| 获取DPA词包                  | ProductManagement->DpaDictGet()                 |
| 获取商品库元信息             | ProductManagement->DpaMetaGet()                 |
| 查询短剧商品原片授权申请状态 | ProductManagement->DpaPlayletAuthGet()          |
| 获取商品库可用商品           | ProductManagement->DpaProductAvailables()       |
| 【新版】创建DPA商品（无商品id） | ProductManagement->DpaProductCreateGet()        |
| 删除通用版商品               | ProductManagement->DpaProductDeleteGet()        |
| 获取商品详情                 | ProductManagement->DpaProductDetailGet()        |
| 获取商品库商品状态           | ProductManagement->DpaProductStatus()           |
| 批量修改DPA商品状态          | ProductManagement->DpaProductStatusBatchUpdate() |
| 更新商品库商品               | ProductManagement->DpaProductUpdate()           |
| 获取商品库商品列表           | ProductManagement->DpaProductV2List()           |
| 获取商品库商品状态（v2）     | ProductManagement->DpaProductV2Status()         |
| 更新商品库商品（v2）         | ProductManagement->DpaProductV2Update()         |
| 更新商品库商品状态（v2）     | ProductManagement->DpaProductV2UpdateStatus()   |
| 获取DPA私有模板              | ProductManagement->DpaTemplateGet()             |
| 获取DPA商品库视频模板        | ProductManagement->DpaVideoGet()                |

#### WorkspaceUpgrade 升级版巨量引擎工作台相关能力

> 调用入口：`$client->module('JuLiangAds')->WorkspaceUpgrade`

| 方法名称                   | 调用方法                                  |
| -------------------------- | ----------------------------------------- |
| 查询短剧商品原片授权申请状态 | WorkspaceUpgrade->DpaPlayletAuthGet()    |
| 获取商品库DPA词包          | WorkspaceUpgrade->DpaDictGet()           |
| 获取商品库DPA分类          | WorkspaceUpgrade->DpaCategoryGet()       |
| 获取升级版商品详情         | WorkspaceUpgrade->DpaClueProductGet()    |
| 获取通用版商品详情         | WorkspaceUpgrade->DpaProductDetailGet()  |
| 获取通用版商品列表         | WorkspaceUpgrade->DpaProductList()       |
| 获取商品库元信息           | WorkspaceUpgrade->DpaMetaGet()           |
| 获取商品库列表             | WorkspaceUpgrade->DpaLibraryList()       |
| 获取升级版商品列表         | WorkspaceUpgrade->DpaClueProductList()   |
| 删除升级版商品             | WorkspaceUpgrade->DpaClueProductDelete() |
| 批量修改DPA商品状态        | WorkspaceUpgrade->DpaProductStatusBatchUpdate() |
| 删除通用版商品             | WorkspaceUpgrade->DpaProductDelete()     |
| 编辑通用版商品库商品       | WorkspaceUpgrade->DpaProductUpdate()     |
| 创建/编辑升级版商品        | WorkspaceUpgrade->DpaClueProductSave()   |
| 新建通用版商品             | WorkspaceUpgrade->DpaProductCreate()     |
