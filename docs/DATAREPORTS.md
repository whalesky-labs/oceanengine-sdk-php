# DataReports 数据报表 API 结构

> 该接口千川&广告&本地推&星图通用。
> 调用格式：`$client->DataReports()->{子模块}->{方法}()`

## AdsProDataReports 广告升级版数据报表

| 方法名称                     | 调用方法                                         | 支持平台             |
| ---------------------------- | ------------------------------------------------ | -------------------- |
| 自定义报表                   | AdsProDataReports->ReportCustomGet()             | 巨量广告             |
| 获取自定义报表可用指标和维度 | AdsProDataReports->ReportCustomConfigGet()       | 巨量广告             |
| 广告主数据                   | AdsProDataReports->ReportAdvertiserGet()         | 巨量广告             |
| 代理商数据                   | AdsProDataReports->ReportAgentGetV2()            | 巨量广告             |
| 代理商消耗报表数据           | AdsProDataReports->AgentAdvCostReportListQuery() | 巨量广告、巨量千川   |
| 代理商竞价投放数据           | AdsProDataReports->AgentAdvBiddingListQuery()    | 巨量广告、巨量本地推 |
| 代理商品牌投放数据           | AdsProDataReports->AgentAdvBrandListQuery()      | 巨量广告、巨量本地推 |

## LiveStreamReports 直播分析数据报表

| 方法名称           | 调用方法                                               | 支持平台 |
| ------------------ | ------------------------------------------------------ | -------- |
| 直播间属性报表     | LiveStreamReports->ReportLiveRoomAttributeGet()        | 巨量广告 |
| 直播间分析报表     | LiveStreamReports->ReportLiveRoomAnalysisGet()         | 巨量广告 |
| 直播间流量来源报表 | LiveStreamReports->ReportLiveRoomFlowCategoryGet()     | 巨量广告 |
| 直播受众分析报表   | LiveStreamReports->ReportLiveRoomAudiencePortraitGet() | 巨量广告 |

## LandingPageReports 落地页数据报表

| 方法名称       | 调用方法                             | 支持平台 |
| -------------- | ------------------------------------ | -------- |
| 落地页属性报表 | LandingPageReports->ReportSitePage() | 巨量广告 |

## AgentRebateReports 【代理】返点核算相关数据

| 方法名称                               | 调用方法                                                            | 支持平台             |
| -------------------------------------- | ------------------------------------------------------------------- | -------------------- |
| 创建【核算明细数据】下载任务           | AgentRebateReports->FileRebateRebateDownloadCreateTask()            | 巨量广告、巨量本地推 |
| （2024）创建【明点化素材数据】下载任务 | AgentRebateReports->FileRebateMaterialDownloadCreateTask()          | 巨量广告、巨量本地推 |
| （2024）查询下载任务                   | AgentRebateReports->FileRebateMaterialDownloadGetDownloadTaskList() | 巨量广告、巨量本地推 |
| （2024）下载任务结果                   | AgentRebateReports->FileRebateMaterialDownloadDownloadFile()        | 巨量广告、巨量本地推 |
| 【代理返点】创建下载任务-通用          | AgentRebateReports->FileRebateCommonDownloadCreateTask()            | 巨量广告、巨量本地推 |
| 【代理返点】查询下载任务-通用          | AgentRebateReports->FileRebateCommonDownloadGetDownloadTaskList()   | 巨量广告、巨量本地推 |
| 【代理返点】下载任务结果-通用          | AgentRebateReports->FileRebateCommonDownloadDownloadFile()          | 巨量广告、巨量本地推 |

## AdsDataReport 广告数据报表

| 方法名称                     | 调用方法                                         | 支持平台 |
| ---------------------------- | ------------------------------------------------ | -------- |
| 获取广告账户数据             | AdsDataReport->QianChuanReportAdvertiserGet()    | 巨量千川 |
| 获取广告计划数据             | AdsDataReport->QianChuanReportAdGet()            | 巨量千川 |
| 获取计划下素材数据           | AdsDataReport->QianChuanReportAdMaterialGet()    | 巨量千川 |
| 获取广告素材数据             | AdsDataReport->QianChuanReportMaterialGet()      | 巨量千川 |
| 获取搜索词/关键词数据        | AdsDataReport->QianChuanReportSearchWordGet()    | 巨量千川 |
| 视频互动流失数据             | AdsDataReport->QianChuanReportVideoUserLoseGet() | 巨量千川 |
| 获取自定义报表可用指标和维度 | AdsDataReport->QianChuanReportCustomConfigGet()  | 巨量千川 |
| 自定义报表                   | AdsDataReport->QianChuanReportCustomGet()        | 巨量千川 |

## LiveReport 直播间报表

| 方法名称                   | 调用方法                                               | 支持平台 |
| -------------------------- | ------------------------------------------------------ | -------- |
| 获取今日直播数据           | LiveReport->QianChuanReportLiveGet()                   | 巨量千川 |
| 获取今日直播间列表         | LiveReport->QianChuanTodayLiveRoomGet()                | 巨量千川 |
| 获取直播间详情             | LiveReport->QianChuanTodayLiveRoomDetailGet()          | 巨量千川 |
| 获取直播间流量表现         | LiveReport->QianChuanTodayLiveRoomFlowPerformanceGet() | 巨量千川 |
| 获取直播间商品列表         | LiveReport->QianChuanTodayLiveRoomProductListGet()     | 巨量千川 |
| 获取直播大屏可用指标和维度 | LiveReport->QianChuanReportTodayLiveRoomConfigGet()    | 巨量千川 |
| 获取直播大屏数据           | LiveReport->QianChuanReportTodayLiveRoomDataGet()      | 巨量千川 |
| 获取直播间用户洞察         | LiveReport->QianChuanTodayLiveRoomUserGet()            | 巨量千川 |

## ProdCompAnalysis 商品竞争分析

| 方法名称                  | 调用方法                                                    | 支持平台 |
| ------------------------- | ----------------------------------------------------------- | -------- |
| 获取商品竞争分析列表      | ProdCompAnalysis->QianChuanProductAnalyseList()             | 巨量千川 |
| 商品竞争分析详情-效果对比 | ProdCompAnalysis->QianChuanProductAnalyseCompareCreative()  | 巨量千川 |
| 商品竞争分析详情-创意比对 | ProdCompAnalysis->QianChuanProductAnalyseCompareStatsData() | 巨量千川 |

## SceneValue 场景价值

| 方法名称                         | 调用方法                                                | 支持平台 |
| -------------------------------- | ------------------------------------------------------- | -------- |
| 获取长周期订单明细可用指标和维度 | SceneValue->QianChuanReportLongTransferOrderConfigGet() | 巨量千川 |
| 获取长周期订单数据               | SceneValue->QianChuanReportLongTransferOrderDataGet()   | 巨量千川 |

## GlobalData 全域数据

| 方法名称                        | 调用方法                                                        | 支持平台 |
| ------------------------------- | --------------------------------------------------------------- | -------- |
| 获取全域推广账户维度数据        | GlobalData->QianChuanReportUniPromotionGet()                    | 巨量千川 |
| 获取全域数据-可用维度和指标     | GlobalData->QianChuanReportUniPromotionConfigGet()              | 巨量千川 |
| 获取全域数据                    | GlobalData->QianChuanReportUniPromotionDataGet()                | 巨量千川 |
| 获取全域推广直播间维度数据      | GlobalData->QianChuanReportUniPromotionDimensionDataRoomGet()   | 巨量千川 |
| 获取全域推广抖音号维度数据      | GlobalData->QianChuanReportUniPromotionDimensionDataAuthorGet() | 巨量千川 |
| 获取全域素材数据-可用指标和维度 | GlobalData->QianChuanReportCustomConfigGet()                    | 巨量千川 |
| 获取全域素材数据                | GlobalData->QianChuanReportCustomGet()                          | 巨量千川 |
