# Tools 工具 API 结构

> 该接口千川&广告&本地推&星图通用。
> 调用格式：`$client->Tools()->{子模块}[->{子模块}]->{方法}()`

## GlobalPlanningTool 全域计划调控工具

| 方法名称                       | 调用方法                                                            | 支持平台 |
| ------------------------------ | ------------------------------------------------------------------- | -------- |
| 创建调控任务                   | GlobalPlanningTool->QianChuanAdControlTaskCreate()                  | 巨量千川 |
| 获取调控任务列表               | GlobalPlanningTool->QianChuanAdControlTaskList()                    | 巨量千川 |
| 修改任务调控状态               | GlobalPlanningTool->QianChuanAdControlTaskStatusUpdate()            | 巨量千川 |
| 修改调控任务设置               | GlobalPlanningTool->QianChuanAdControlTaskUpdate()                  | 巨量千川 |
| 修改调控任务预算               | GlobalPlanningTool->QianChuanAdControlTaskBudgetUpdate()            | 巨量千川 |
| 修改调控任务投放时长           | GlobalPlanningTool->QianChuanAdControlTaskDurationUpdate()          | 巨量千川 |
| 创建直播-一键控量计划          | GlobalPlanningTool->QianChuanAdControlTaskSmartControlCreate()      | 巨量千川 |
| 修改直播-一键控量计划调控状态 | GlobalPlanningTool->QianChuanAdControlTaskSmartControlStatusUpdate() | 巨量千川 |

## QueryTool 查询工具

| 方法名称                       | 调用方法                                         | 支持平台                       |
| ------------------------------ | ------------------------------------------------ | ------------------------------ |
| 获取行业列表                   | QueryTool->ToolsIndustryGet()                    | 巨量广告、巨量千川             |
| 操作日志查询                   | QueryTool->ToolsLogSearch()                      | 巨量广告、巨量千川、巨量本地推 |
| 获取千川操作日志               | QueryTool->QianChuanToolsLogSearch()             | 巨量千川                       |
| 获取定向受众预估               | QueryTool->QianChuanToolsEstimateAudience()      | 巨量广告、巨量千川             |
| 获取在投计划配额信息           | QueryTool->QianChuanAdQuotaGet()                 | 巨量千川                       |
| 获取白名单能力                 | QueryTool->QianChuanToolsGray()                  | 巨量千川                       |
| 智能优惠券白名单               | QueryTool->QianChuanToolsAllowCoupon()           | 巨量千川                       |
| 查询违规积分明细               | QueryTool->SecurityScoreViolationEventGet()      | 巨量广告、巨量千川             |
| 查询账户累计积分               | QueryTool->SecurityScoreTotalGet()               | 巨量广告、巨量千川             |
| 查看积分处置详情               | QueryTool->SecurityScoreDisposalInfoGet()        | 巨量广告、巨量千川             |
| 积分处置详情列表               | QueryTool->SecurityScoreDisposalListGet()        | 巨量千川                       |
| 查询在投计划配额               | QueryTool->ToolsQuotaGet()                       | 巨量广告                       |
| 查询受众预估结果               | QueryTool->ToolsEstimateAudience()               | 巨量广告                       |
| 查询应用信息                   | QueryTool->ToolsAppSearch()                      | 巨量广告                       |
| 获取绑定的抖音号               | QueryTool->ToolsIesAccountSearch()               | 巨量广告                       |
| 行动号召字段内容获取           | QueryTool->ToolsActionTextGet()                  | 巨量广告                       |
| 查询推广卡片推荐内容（新版）   | QueryTool->ToolsPromotionCardRecommendTitleGet() | 巨量广告                       |
| 获取预估点击成本               | QueryTool->ToolsEstimatedPriceGet()              | 巨量广告                       |
| 获取抖音授权关系               | QueryTool->ToolsAwemeAuthList()                  | 巨量广告                       |
| 获取创编可用的抖音图文素材     | QueryTool->FileCarouselAwemeGet()                | 巨量广告                       |
| 获取推荐使用的视频素材         | QueryTool->RecommendVideoList()                  | 巨量广告                       |
| 查询视频是否挂载下载类锚点     | QueryTool->ToolsVideoCheckAvailableAnchor()      | 巨量广告                       |
| 获取快投推荐出价系数           | QueryTool->ToolsSearchBidRatioGet()              | 巨量广告                       |
| 获取广告预览二维码（升级版）   | QueryTool->ToolsAdPreviewQrcodeGet()             | 巨量广告                       |
| 查询白名单能力                 | QueryTool->ToolsGrayGet()                        | 巨量广告                       |
| 查询建议出价（巨量广告升级版） | QueryTool->ToolsBidsSuggest()                    | 巨量广告                       |
| 【代理商】查询广告违规信息     | QueryTool->AgentQueryRiskPromotionList()         | 巨量广告                       |

## DouYinInfluencerMgmt 抖音达人定向管理

| 方法名称                     | 调用方法                                                | 支持平台           |
| ---------------------------- | ------------------------------------------------------- | ------------------ |
| 查询抖音号 id 对应的达人信息 | DouYinInfluencerMgmt->ToolsAwemeAuthorInfoGet()         | 巨量广告、巨量千川 |
| 查询抖音类目下的推荐达人     | DouYinInfluencerMgmt->ToolsAwemeCategoryTopAuthorGet()  | 巨量广告、巨量千川 |
| 查询抖音帐号和类目信息       | DouYinInfluencerMgmt->ToolsAwemeInfoSearch()            | 巨量广告、巨量千川 |
| 查询抖音类目列表             | DouYinInfluencerMgmt->ToolsAwemeMultiLevelCategoryGet() | 巨量广告、巨量千川 |
| 查询抖音类似帐号             | DouYinInfluencerMgmt->ToolsAwemeSimilarAuthorSearch()   | 巨量广告、巨量千川 |
| 查询授权直播抖音达人列表     | DouYinInfluencerMgmt->ToolsLiveAuthorizeList()          | 巨量广告、巨量千川 |

## BehavioralIntMgmt 行为兴趣词管理

| 方法名称                   | 调用方法                                                 | 支持平台           |
| -------------------------- | -------------------------------------------------------- | ------------------ |
| 兴趣行为类目关键词 id 转词 | BehavioralIntMgmt->ToolsInterestActionId2Word()          | 巨量广告、巨量千川 |
| 行为类目查询               | BehavioralIntMgmt->ToolsInterestActionActionCategory()   | 巨量广告、巨量千川 |
| 行为关键词查询             | BehavioralIntMgmt->ToolsInterestActionActionKeyword()    | 巨量广告、巨量千川 |
| 兴趣类目查询               | BehavioralIntMgmt->ToolsInterestActionInterestCategory() | 巨量广告、巨量千川 |
| 兴趣关键词查询             | BehavioralIntMgmt->ToolsInterestActionInterestKeyword()  | 巨量广告、巨量千川 |
| 获取行为兴趣推荐关键词     | BehavioralIntMgmt->ToolsInterestActionKeywordSuggest()   | 巨量广告、巨量千川 |

## DynamicCreativeWordPackMgmt 动态创意词包管理

| 方法名称         | 调用方法                                               | 支持平台           |
| ---------------- | ------------------------------------------------------ | ------------------ |
| 查询动态创意词包 | DynamicCreativeWordPackMgmt->ToolsCreativeWordSelect() | 巨量广告、巨量千川 |
| 更新动态创意词包 | DynamicCreativeWordPackMgmt->ToolsCreativeWordUpdate() | 巨量广告           |
| 删除动态创意词包 | DynamicCreativeWordPackMgmt->ToolsCreativeWordDelete() | 巨量广告           |

## AudienceMgmt 人群管理

| 方法名称         | 调用方法                                        | 支持平台 |
| ---------------- | ----------------------------------------------- | -------- |
| 查询创编可用人群 | AudienceMgmt->DmpAudiencesGet()                 | 巨量千川 |
| 获取定向包列表   | AudienceMgmt->QianChuanOrientationPackageGet()  | 巨量千川 |
| 获取人群管理列表 | AudienceMgmt->QianChuanAudienceListGet()        | 巨量千川 |
| 获取人群分组     | AudienceMgmt->QianChuanAudienceGroupGet()       | 巨量千川 |
| 上传人群         | AudienceMgmt->QianChuanAudienceCreateByFile()   | 巨量千川 |
| 推送人群         | AudienceMgmt->QianChuanAudiencePush()           | 巨量千川 |
| 删除人群         | AudienceMgmt->QianChuanAudienceDelete()         | 巨量千川 |
| 小文件直接上传   | AudienceMgmt->QianChuanAudienceFileUpload()     | 巨量千川 |
| 大文件分片上传   | AudienceMgmt->QianChuanAudienceFilePartUpload() | 巨量千川 |

## OneClickCampaignScaling 计划一键起量

| 方法名称             | 调用方法                                                             | 支持平台 |
| -------------------- | -------------------------------------------------------------------- | -------- |
| 设置计划一键起量任务 | OneClickCampaignScaling->QianchuanToolsSmartBoostAdBoostSet()        | 巨量千川 |
| 获取计划一键起量状态 | OneClickCampaignScaling->QianchuanToolsSmartBoostAdBoostStatusGet()  | 巨量千川 |
| 获取计划一键起量版本 | OneClickCampaignScaling->QianchuanToolsSmartBoostAdBoostVersionGet() | 巨量千川 |
| 获取计划一键起量报告 | OneClickCampaignScaling->QianchuanToolsSmartBoostAdBoostReportGet()  | 巨量千川 |

## CommentMgmt 评论管理

| 方法名称             | 调用方法                              | 支持平台           |
| -------------------- | ------------------------------------- | ------------------ |
| 获取评论列表         | CommentMgmt->ToolsCommentGet()        | 巨量广告、巨量千川 |
| 获取评论回复         | CommentMgmt->ToolsCommentReplyGet()   | 巨量广告、巨量千川 |
| 获取评论统计指标     | CommentMgmt->ToolsCommentMetricsGet() | 巨量广告、巨量千川 |
| 回复评论             | CommentMgmt->ToolsCommentReply()      | 巨量广告、巨量千川 |
| 隐藏评论             | CommentMgmt->ToolsCommentHide()       | 巨量广告、巨量千川 |
| 获取评论视频 ID 列表 | CommentMgmt->ToolsCommentMid2ItemId() | 巨量广告、巨量千川 |
| 置顶/取消置顶评论    | CommentMgmt->ToolsCommentStickOnTop() | 巨量千川           |

### 屏蔽词/屏蔽用户

| 方法名称         | 调用方法                                     | 支持平台           |
| ---------------- | -------------------------------------------- | ------------------ |
| 批量添加屏蔽词   | CommentMgmt->ToolsCommentTermsBannedAdd()    | 巨量广告、巨量千川 |
| 批量删除屏蔽词   | CommentMgmt->ToolsCommentTermsBannedDelete() | 巨量广告、巨量千川 |
| 更新屏蔽词       | CommentMgmt->ToolsCommentTermsBannedUpdate() | 巨量广告、巨量千川 |
| 获取屏蔽词       | CommentMgmt->ToolsCommentTermsBannedGet()    | 巨量广告、巨量千川 |
| 添加屏蔽用户     | CommentMgmt->ToolsAwemeBannedCreate()        | 巨量广告、巨量千川 |
| 删除屏蔽用户     | CommentMgmt->ToolsAwemeBannedDelete()        | 巨量广告、巨量千川 |
| 获取屏蔽用户列表 | CommentMgmt->ToolsAwemeBannedList()          | 巨量广告、巨量千川 |

## GeographicInformationMgmt 地理信息管理

| 方法名称          | 调用方法                                        | 支持平台             |
| ----------------- | ----------------------------------------------- | -------------------- |
| 查询国家/区域信息 | GeographicInformationMgmt->OpenApiFileImageAd() | 巨量千川、巨量本地推 |
| 获取行政信息      | GeographicInformationMgmt->ToolsAdminInfo()     | 巨量千川、巨量本地推 |
| 获取地域列表      | GeographicInformationMgmt->ToolsRegionGet()     | 巨量广告             |

## AdsDiagnosisPro 获取广告诊断信息升级版

| 方法名称         | 调用方法                                                   | 支持平台 |
| ---------------- | ---------------------------------------------------------- | -------- |
| 获取广告诊断建议 | AdsDiagnosisPro->ToolsPromotionDiagnosisSuggestionGet()    | 巨量广告 |
| 采纳广告诊断建议 | AdsDiagnosisPro->ToolsPromotionDiagnosisSuggestionAccept() | 巨量广告 |

## InteractiveAds 互动广告管理

| 方法名称         | 调用方法                                | 支持平台 |
| ---------------- | --------------------------------------- | -------- |
| 获取互动作品信息 | InteractiveAds->ToolsRubeexGet()        | 巨量广告 |
| 获取作品场景     | InteractiveAds->ToolsRubeexRemark()     | 巨量广告 |
| 获取作品版本信息 | InteractiveAds->ToolsRubeexVersionGet() | 巨量广告 |

## PangolinTrafficPacks 穿山甲流量包

| 方法名称         | 调用方法                                                     | 支持平台 |
| ---------------- | ------------------------------------------------------------ | -------- |
| 获取穿山甲流量包 | PangolinTrafficPacks->ToolsUnionFlowPackageGet()             | 巨量广告 |
| 创建穿山甲流量包 | PangolinTrafficPacks->ToolsUnionFlowPackageCreate()          | 巨量广告 |
| 修改穿山甲流量包 | PangolinTrafficPacks->ToolsUnionFlowPackageUpdate()          | 巨量广告 |
| 删除穿山甲流量包 | PangolinTrafficPacks->ToolsUnionFlowPackageDelete()          | 巨量广告 |
| 查看 rit 数据    | PangolinTrafficPacks->ToolsUnionFlowPackageReport()          | 巨量广告 |
| 查看 2.0rit 数据 | PangolinTrafficPacks->ToolsUnionFlowPackagePromotionReport() | 巨量广告 |

## ConversionGoals 转化目标管理

| 方法名称             | 调用方法                                | 支持平台 |
| -------------------- | --------------------------------------- | -------- |
| 引流下单转化信息获取 | ConversionGoals->AdvConvertOleConvert() | 巨量广告 |

## TargetingPackages 定向包管理

| 方法名称               | 调用方法                                        | 支持平台 |
| ---------------------- | ----------------------------------------------- | -------- |
| 定向包查询关联项目信息 | TargetingPackages->AudiencePackageBindinfoGet() | 巨量广告 |
| 获取定向包             | TargetingPackages->AudiencePackageGet()         | 巨量广告 |
| 创建定向包             | TargetingPackages->AudiencePackageCreate()      | 巨量广告 |
| 更新定向包             | TargetingPackages->AudiencePackageUpdate()      | 巨量广告 |
| 删除定向包             | TargetingPackages->AudiencePackageDelete()      | 巨量广告 |

## BoostOneClick 一键起量（巨量广告升级版）

| 方法名称             | 调用方法                                                | 支持平台 |
| -------------------- | ------------------------------------------------------- | -------- |
| 获取广告建议起量预算 | BoostOneClick->ToolsSuggestBudgetGet()                  | 巨量广告 |
| 开启/更新一键起量    | BoostOneClick->ToolsPromotionRaiseSet()                 | 巨量广告 |
| 获取一键起量方案列表 | BoostOneClick->ToolsPromotionRaiseStatusGet()           | 巨量广告 |
| 获取广告起量状态     | BoostOneClick->ToolsPromotionRaiseStatusCurrentIdsGet() | 巨量广告 |
| 获取起量版本信息     | BoostOneClick->ToolsPromotionRaiseVersionGet()          | 巨量广告 |
| 关停正在起量的广告   | BoostOneClick->ToolsPromotionRaiseStop()                | 巨量广告 |

## QuickAppManagement 快应用管理

| 方法名称       | 调用方法                                                | 支持平台 |
| -------------- | ------------------------------------------------------- | -------- |
| 查询快应用信息 | QuickAppManagement->ToolQuickAppManagementQuickAppGet() | 巨量广告 |

## AppManagement 应用管理

| 方法名称                                 | 调用方法                                                 | 支持平台 |
| ---------------------------------------- | -------------------------------------------------------- | -------- |
| 查询游戏预约信息                         | AppManagement->ToolsAppManagementBookingGet()            | 巨量广告 |
| 查询游戏预约记录详情                     | AppManagement->ToolsAppManagementBookingRecordsGet()     | 巨量广告 |
| 查询安卓应用信息                         | AppManagement->ToolsAppManagementAppGet()                | 巨量广告 |
| 查询安卓应用信息（支持所有账户体系）     | AppManagement->ToolsAppManagementAndroidAppList()        | 巨量广告 |
| 提交解析应用包任务                       | AppManagement->ToolsAppManagementPackageParse()          | 巨量广告 |
| 查询包解析状态                           | AppManagement->ToolsAppManagementPackageGet()            | 巨量广告 |
| 查询安卓应用分包列表（支持所有账户体系） | AppManagement->ToolsAppManagementExtendPackageListV2()   | 巨量广告 |
| 创建安卓应用分包                         | AppManagement->ToolsAppManagementExtendPackageCreate()   | 巨量广告 |
| 创建安卓应用分包（支持所有账户体系）     | AppManagement->ToolsAppManagementExtendPackageCreateV2() | 巨量广告 |
| 更新安卓应用分包版本                     | AppManagement->ToolsAppManagementExtendPackageUpdate()   | 巨量广告 |
| 更新安卓应用分包版本（支持所有账户体系） | AppManagement->ToolsAppManagementExtendPackageUpdateV2() | 巨量广告 |
| 查看应用共享范围                         | AppManagement->ToolsAppManagementShareAccountList()      | 巨量广告 |
| 设置应用共享                             | AppManagement->ToolsAppManagementBpShare()               | 巨量广告 |
| 取消应用共享关系                         | AppManagement->ToolsAppManagementBpShareCancel()         | 巨量广告 |
| 更新应用共享关系                         | AppManagement->ToolsAppManagementUpdateAuthorization()   | 巨量广告 |

### WorkspaceUpgrade 升级版工作台（EBP）

> 调用入口：`AppManagement->WorkspaceUpgrade`

| 方法名称             | 调用方法                                 | 支持平台 |
| -------------------- | ---------------------------------------- | -------- |
| 获取安卓应用列表     | WorkspaceUpgrade->ToolsEbpAppList()       | 巨量广告 |
| 查询安卓应用母包详情 | WorkspaceUpgrade->ToolsEbpAppDetail()     | 巨量广告 |
| 查询安卓应用分包列表 | WorkspaceUpgrade->ToolsEbpAppExtendList() | 巨量广告 |
| 发布安卓应用母包     | WorkspaceUpgrade->ToolsEbpAppPublish()    | 巨量广告 |
| 更新安卓应用母包     | WorkspaceUpgrade->ToolsEbpAppUpdate()     | 巨量广告 |
| 获取游戏预约列表     | WorkspaceUpgrade->ToolsEbpAppGameBookList() | 巨量广告 |
| 创建安卓应用分包     | WorkspaceUpgrade->ToolsEbpAppExtendCreate() | 巨量广告 |
| 更新安卓应用分包     | WorkspaceUpgrade->ToolsEbpAppExtendUpdate() | 巨量广告 |

## AppBasePackageUpdate 应用母包更新

| 方法名称                   | 调用方法                                                             | 支持平台 |
| -------------------------- | -------------------------------------------------------------------- | -------- |
| 创建异步文件上传任务       | AppBasePackageUpdate->ToolsAppManagementUploadTaskCreate()           | 巨量广告 |
| 查询文件异步上传任务       | AppBasePackageUpdate->ToolsAppManagementUploadTaskList()             | 巨量广告 |
| 获取应用细分分类及题材标签 | AppBasePackageUpdate->ToolsAppManagementIndustryInfoList()           | 巨量广告 |
| 查询安卓应用母包           | AppBasePackageUpdate->ToolsAppManagementAndroidBasicPackageGet()     | 巨量广告 |
| 更新安卓应用母包           | AppBasePackageUpdate->ToolsAppManagementAndroidBasicPackageUpdate()  | 巨量广告 |
| 发布安卓应用母包           | AppBasePackageUpdate->ToolsAppManagementAndroidBasicPackagePublish() | 巨量广告 |

## RTAStrategy RTA 策略管理

| 方法名称                                  | 调用方法                                  | 支持平台 |
| ----------------------------------------- | ----------------------------------------- | -------- |
| 获取 RTA 策略数据                         | RTAStrategy->ToolsRtaGetInfo()            | 巨量广告 |
| 获取可用的 RTA 策略                       | RTAStrategy->ToolsRtaGet()                | 巨量广告 |
| 批量启停账户下 RTA 策略                   | RTAStrategy->ToolsRtaStatusUpdate()       | 巨量广告 |
| 设置账户下 RTA 策略生效范围               | RTAStrategy->ToolsRtaSetScope()           | 巨量广告 |
| 获取穿山甲渠道 RTA 联合实验数据           | RTAStrategy->ReportRtaExpGet()            | 巨量广告 |
| 获取穿山甲广告主分流联合实验数据          | RTAStrategy->ReportRtaCusExpGet()         | 巨量广告 |
| 获取站内媒体 RTA 联合实验数据（分时 t+5） | RTAStrategy->ReportRtaExpLocalHourlyGet() | 巨量广告 |
| 获取站内媒体 RTA 联合实验数据（分天 t+1） | RTAStrategy->ReportRtaExpLocalDailyGet()  | 巨量广告 |
| 获取 RTA 策略绑定信息列表                 | RTAStrategy->ToolsRtaScopeGet()           | 巨量广告 |

## PreferredBoosting 账户优选起量

| 方法名称             | 调用方法                                              | 支持平台 |
| -------------------- | ----------------------------------------------------- | -------- |
| 新建优选起量任务     | PreferredBoosting->ToolsTaskRaiseCreate()             | 巨量广告 |
| 查询优选起量任务     | PreferredBoosting->ToolsTaskRaiseGet()                | 巨量广告 |
| 关闭优选起量任务     | PreferredBoosting->ToolsTaskRaiseStatusStop()         | 巨量广告 |
| 查询优选起量状态     | PreferredBoosting->ToolsTaskRaiseOptimizationIdsGet() | 巨量广告 |
| 查询优选起量任务数据 | PreferredBoosting->ToolsTaskRaiseDataGet()            | 巨量广告 |

## NativeAnchorManagement 原生锚点管理

| 方法名称             | 调用方法                                               | 支持平台 |
| -------------------- | ------------------------------------------------------ | -------- |
| 获取原生锚点列表     | NativeAnchorManagement->NativeAnchorGet()              | 巨量广告 |
| 获取原生锚点详情     | NativeAnchorManagement->NativeAnchorGetDetail()        | 巨量广告 |
| 批量获取锚点预览 url | NativeAnchorManagement->NativeAnchorQrcodePreviewGet() | 巨量广告 |
| 创建原生锚点         | NativeAnchorManagement->NativeAnchorCreate()           | 巨量广告 |
| 更新原生锚点         | NativeAnchorManagement->NativeAnchorUpdate()           | 巨量广告 |
| 删除原生锚点         | NativeAnchorManagement->NativeAnchorDelete()           | 巨量广告 |

## WechatMiniAppManagement 微信小程序/小游戏管理

| 方法名称           | 调用方法                                           | 支持平台 |
| ------------------ | -------------------------------------------------- | -------- |
| 获取微信小程序列表 | WechatMiniAppManagement->ToolsWechatAppletList()   | 巨量广告 |
| 获取微信小游戏列表 | WechatMiniAppManagement->ToolsWechatGameList()     | 巨量广告 |
| 创建微信小游戏     | WechatMiniAppManagement->ToolsWechatGameCreate()   | 巨量广告 |
| 创建微信小程序     | WechatMiniAppManagement->ToolsWechatAppletCreate() | 巨量广告 |
| 更新微信小程序     | WechatMiniAppManagement->ToolsWechatAppletUpdate() | 巨量广告 |

## ByteMiniAppManagement 字节小程序/小游戏管理

| 方法名称                      | 调用方法                                                   | 支持平台 |
| ----------------------------- | ---------------------------------------------------------- | -------- |
| 创建字节小程序                | ByteMiniAppManagement->ToolsMicroAppCreate()               | 巨量广告 |
| 更新字节小程序                | ByteMiniAppManagement->ToolsMicroAppUpdate()               | 巨量广告 |
| 获取字节小程序                | ByteMiniAppManagement->ToolsMicroAppList()                 | 巨量广告 |
| 创建字节小游戏                | ByteMiniAppManagement->ToolsMicroGameCreate()              | 巨量广告 |
| 更新字节小游戏                | ByteMiniAppManagement->ToolsMicroGameUpdate()              | 巨量广告 |
| 获取字节小游戏                | ByteMiniAppManagement->ToolsMicroGameList()                | 巨量广告 |
| 获取字节小程序/小游戏详情内容 | ByteMiniAppManagement->ToolsAssetLinkList()                | 巨量广告 |
| 查询字节小游戏归因激活时间窗  | ByteMiniAppManagement->ToolsMicroGameConvertWindowGet()    | 巨量广告 |
| 修改字节小游戏归因激活时间窗  | ByteMiniAppManagement->ToolsMicroGameConvertWindowUpdate() | 巨量广告 |

### WorkspaceUpgrade 升级版工作台（EBP）

> 调用入口：`ByteMiniAppManagement->WorkspaceUpgrade`

| 方法名称             | 调用方法                                          | 支持平台 |
| -------------------- | ------------------------------------------------- | -------- |
| 获取字节小程序列表   | WorkspaceUpgrade->ToolsEbpMicroAppletList()       | 巨量广告 |
| 获取字节小程序详情   | WorkspaceUpgrade->ToolsEbpMicroAppletLinkList()   | 巨量广告 |
| 新建字节小程序       | WorkspaceUpgrade->ToolsEbpMicroAppletCreate()     | 巨量广告 |
| 更新字节小程序       | WorkspaceUpgrade->ToolsEbpMicroAppletUpdate()     | 巨量广告 |
| 获取微信小程序列表   | WorkspaceUpgrade->ToolsEbpWechatAppletList()      | 巨量广告 |
| 获取微信小游戏列表   | WorkspaceUpgrade->ToolsEbpWechatGameList()        | 巨量广告 |
| 新建微信小程序       | WorkspaceUpgrade->ToolsEbpWechatAppletCreate()    | 巨量广告 |
| 更新微信小程序       | WorkspaceUpgrade->ToolsEbpWechatAppletUpdate()    | 巨量广告 |
| 新建微信小游戏       | WorkspaceUpgrade->ToolsEbpWechatGameCreate()      | 巨量广告 |
| 更新微信小游戏       | WorkspaceUpgrade->ToolsEbpWechatGameUpdate()      | 巨量广告 |
| 获取字节小游戏列表   | WorkspaceUpgrade->ToolsEbpMicroGameList()         | 巨量广告 |
| 获取字节小游戏详情   | WorkspaceUpgrade->ToolsEbpMicroGameLinkList()     | 巨量广告 |
| 新建字节小游戏       | WorkspaceUpgrade->ToolsEbpMicroGameCreate()       | 巨量广告 |
| 更新字节小游戏       | WorkspaceUpgrade->ToolsEbpMicroGameUpdate()       | 巨量广告 |

## MiniAppSharing 小程序/小游戏共享管理（微信&字节）

| 方法名称                  | 调用方法                                            | 支持平台 |
| ------------------------- | --------------------------------------------------- | -------- |
| 设置小游戏&小程序共享     | MiniAppSharing->ToolsBpAssetManagementShare()       | 巨量广告 |
| 取消小游戏/小程序共享关系 | MiniAppSharing->ToolsBpAssetManagementShareCancel() | 巨量广告 |
| 查看小游戏/小程序共享范围 | MiniAppSharing->ToolsBpAssetManagementShareGet()    | 巨量广告 |

## CreativeRepairTool 素材修复工具

| 方法名称                              | 调用方法                                                     | 支持平台 |
| ------------------------------------- | ------------------------------------------------------------ | -------- |
| 获取拒审素材修复建议                  | CreativeRepairTool->RejectMaterialAiRepairGet()              | 巨量广告 |
| 创建采纳「拒审素材修复建议」任务      | CreativeRepairTool->RejectMaterialAiRepairAcceptTaskCreate() | 巨量广告 |
| 获取采纳素材修复建议任务结果          | CreativeRepairTool->RejectMaterialAiRepairAcceptTaskList()   | 巨量广告 |
| 根据 mid 查询同主体账户下修复建议列表 | CreativeRepairTool->RejectMaterialAiRepairCrossAccountGet()  | 巨量广告 |
