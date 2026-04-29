# JuLiangStarMap 巨量星图 API 结构

> 调用格式：`$client->JuLiangStarMap()->{子模块}->{方法}()`

## MassiveStarMap 巨量星图

| 方法名称                         | 调用方法                                             |
| -------------------------------- | ---------------------------------------------------- |
| 获取星广联投(星图版)任务列表     | MassiveStarMap->StarStarAdUniteTaskList()           |
| 获取星广联投(星图版)视频维度数据 | MassiveStarMap->StarStarAdUniteTaskItemList()       |
| 获取星广联投(星图版)任务详情     | MassiveStarMap->StarStarAdUniteTaskDetail()         |
| 获取订单投后分析报表             | MassiveStarMap->StarReportOrderOverviewGet()        |
| 获取任务下累计可查询的数据指标   | MassiveStarMap->StarReportDataTopicConfig()         |
| 获取投后数据主题累计数据         | MassiveStarMap->StarReportCustomDataTopicReport()   |
| 获取投后每日趋势数据（短视频）   | MassiveStarMap->StarReportCustomDataTopicDailyReport() |
| 获取星图客户任务订单列表         | MassiveStarMap->StarDemandOrderList()               |
| 获取星图订单投后线索             | MassiveStarMap->StarClueGet()                       |
| 获取星图需求列表                 | MassiveStarMap->StarDemandList()                    |
| 获取星图订单用户分布报表         | MassiveStarMap->StarReportOrderUserDistributionGet() |
