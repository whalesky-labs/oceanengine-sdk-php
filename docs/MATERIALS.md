# Materials 素材管理 API 结构

> 该接口千川&广告&本地推&星图通用。
> 调用格式：`$client->Materials()->{子模块}->{方法}()`

## ImageVideoMgmt 图片与视频管理

| 方法名称                                     | 调用方法                                              | 支持平台                       |
| -------------------------------------------- | ----------------------------------------------------- | ------------------------------ |
| 上传图片素材                                 | ImageVideoMgmt->FileImageAd()                         | 巨量广告、巨量千川             |
| 上传视频素材                                 | ImageVideoMgmt->FileVideoAd()                         | 巨量广告、巨量千川             |
| 上传图文素材                                 | ImageVideoMgmt->CarouselCreate()                      | 巨量广告、巨量千川             |
| 上传图文内的音频素材                         | ImageVideoMgmt->FileAudioAd()                         | 巨量广告、巨量千川             |
| 批量删除图片素材                             | ImageVideoMgmt->QianchuanFileImageDelete()            | 巨量千川                       |
| 批量删除视频素材                             | ImageVideoMgmt->QianchuanFileVideoDelete()            | 巨量千川                       |
| 获取千川素材库图片                           | ImageVideoMgmt->QianchuanImageGet()                   | 巨量千川                       |
| 获取千川素材库视频                           | ImageVideoMgmt->QianchuanVideoGet()                   | 巨量千川                       |
| 获取抖音号下的视频                           | ImageVideoMgmt->QianchuanFileVideoAwemeGet()          | 巨量千川                       |
| 获取抖音主页视频对应素材库视频               | ImageVideoMgmt->QianchuanVideoByAwemeGet()            | 巨量千川                       |
| 获取千川素材库图文                           | ImageVideoMgmt->QianchuanCarouselGet()                | 巨量千川                       |
| 获取抖音号下的图文                           | ImageVideoMgmt->QianchuanCarouselAwemeGet()           | 巨量千川                       |
| 获取首发素材                                 | ImageVideoMgmt->QianchuanFileVideoOriginalGet()       | 巨量千川                       |
| 获取低效素材                                 | ImageVideoMgmt->QianchuanFileVideoEfficiencyGet()     | 巨量千川                       |
| 代理商获取视频素材                           | ImageVideoMgmt->FileVideoAgentGet()                   | 巨量广告、巨量千川             |
| 广告素材预审提交接口（连山云视频点播版）     | ImageVideoMgmt->OpenMaterialAuditProSubmit()          | 巨量广告、巨量千川、巨量本地推 |
| 广告素材预审结果查询（连山云视频点播版）     | ImageVideoMgmt->OpenMaterialAuditProGet()             | 巨量广告、巨量千川、巨量本地推 |
| 异步上传视频文件                             | ImageVideoMgmt->FileUploadTaskCreate()                | 巨量广告                       |
| 获取异步上传视频文件结果                     | ImageVideoMgmt->FileVideoUploadTaskList()             | 巨量广告                       |
| 获取图片素材                                 | ImageVideoMgmt->FileImageGet()                        | 巨量广告                       |
| 获取视频素材                                 | ImageVideoMgmt->FileVideoGet()                        | 巨量广告                       |
| 获取视频智能封面                             | ImageVideoMgmt->ToolsVideoCoverSuggest()              | 巨量广告                       |
| 获取同主体下广告主图片素材                   | ImageVideoMgmt->FileImageAdGet()                      | 巨量广告                       |
| 获取同主体下广告主视频素材                   | ImageVideoMgmt->FileVideoAdGet()                      | 巨量广告                       |
| 素材推送                                     | ImageVideoMgmt->FileMaterialBind()                    | 巨量广告                       |
| 批量删除视频素材                             | ImageVideoMgmt->FileVideoDelete()                     | 巨量广告                       |
| 批量删除图片素材                             | ImageVideoMgmt->FileImageDelete()                     | 巨量广告                       |
| 批量删除图文                                 | ImageVideoMgmt->CarouselDelete()                      | 巨量广告                       |
| 更新视频                                     | ImageVideoMgmt->FileVideoUpdate()                     | 巨量广告                       |
| 获取抖音主页视频                             | ImageVideoMgmt->FileVideoAwemeGet()                   | 巨量广告                       |
| 获取低效素材                                 | ImageVideoMgmt->FileVideoEfficiencyGet()              | 巨量广告                       |
| 获取素材标签列表                             | ImageVideoMgmt->FileMaterialList()                    | 巨量广告                       |
| 查询素材标签信息                             | ImageVideoMgmt->FileMaterialDetail()                  | 巨量广告                       |
| 获取视频素材评估标签（新版）                 | ImageVideoMgmt->FileMaterialAttributesList()          | 巨量广告                       |
| 创建素材清理任务                             | ImageVideoMgmt->FileVideoMaterialClearTaskCreate()    | 巨量广告                       |
| 获取清理任务列表                             | ImageVideoMgmt->FileVideoMaterialClearTaskGet()       | 巨量广告                       |
| 下载清理任务结果                             | ImageVideoMgmt->FileVideoMaterialClearTaskResultGet() | 巨量广告                       |
| 获取音频素材（用于图文新建）                 | ImageVideoMgmt->FileAudioGet()                        | 巨量广告                       |
| 获取图文素材                                 | ImageVideoMgmt->CarouselList()                        | 巨量广告                       |
| 更新图文信息                                 | ImageVideoMgmt->CarouselUpdate()                      | 巨量广告                       |
| 获取同主体下广告主图文素材                   | ImageVideoMgmt->CarouselAdGet()                       | 巨量广告                       |
| 【代理商】上传自产首发素材至方舟（搬运治理） | ImageVideoMgmt->FileVideoAgent()                      | 巨量广告                       |
| 【代理商】批量暂停明点无效素材               | ImageVideoMgmt->FileVideoPause()                      | 巨量广告                       |
| 代理商创建前测任务                           | ImageVideoMgmt->DiagnosisTaskAgentCreate()            | 巨量广告                       |
| 代理商获取任务列表                           | ImageVideoMgmt->DiagnosisTaskAgentList()              | 巨量广告                       |
| 代理商轮询任务结果                           | ImageVideoMgmt->DiagnosisTaskAgentGet()               | 巨量广告                       |
| Adv 创建前测任务                             | ImageVideoMgmt->DiagnosisTaskAdvCreate()              | 巨量广告                       |
| Adv 获取任务列表                             | ImageVideoMgmt->DiagnosisTaskAdvList()                | 巨量广告                       |
| Adv 轮询任务结果                             | ImageVideoMgmt->DiagnosisTaskAdvGet()                 | 巨量广告                       |
