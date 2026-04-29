# Account 账号服务 API 结构

> 该接口千川&广告&本地推&星图通用。
> 调用格式：`$client->Account()->{子模块}->{方法}()`

## AccountInfo 账户信息

| 方法名称                 | 调用方法                                  | 支持平台                       |
| ------------------------ | ----------------------------------------- | ------------------------------ |
| 获取千川广告账户全量信息 | AccountInfo->AdvertiserInfo()             | 巨量广告、巨量千川、巨量本地推 |
| 获取千川广告账户基础信息 | AccountInfo->AdvertiserPublicInfo()       | 巨量千川                       |
| 广告主账户信息查询       | AccountInfo->AgentAdvertiserInfoQuery()   | 巨量千川、巨量广告、巨量本地推 |
| 获取千川账户类型         | AccountInfo->QianchuanAdvertiserTypeGet() | 巨量千川                       |
| 获取店铺账户信息         | AccountInfo->QianchuanShopGet()           | 巨量千川                       |
| 获取授权时登录用户信息   | AccountInfo->UserInfo()                   | 巨量千川                       |

## AccountRel 账户关系

| 方法名称                             | 调用方法                                    | 支持平台 |
| ------------------------------------ | ------------------------------------------- | -------- |
| 获取已授权的账户（店铺/代理商/组织） | AccountRel->Oauth2AdvertiserGet()           | 巨量千川 |
| 获取千川账户下抖音号授权列表         | AccountRel->QianChuanAwemeAuthListGet()     | 巨量千川 |
| 获取千川账户下可投广抖音号           | AccountRel->QianChuanAwemeAuthorizedGet()   | 巨量千川 |
| 获取店铺账户关联的广告账户列表       | AccountRel->QianChuanShopAdvertiserList()   | 巨量千川 |
| 广告主添加抖音号                     | AccountRel->QianChuanToolsAwemeAuth()       | 巨量千川 |
| 店铺新客定向授权                     | AccountRel->QianChuanToolsShopAuth()        | 巨量千川 |
| 全域授权初始化                       | AccountRel->QianChuanUniPromotionAuthInit() | 巨量千川 |

## AdQualification 广告主信息与资质管理

| 方法名称                  | 调用方法                                                 | 支持平台             |
| ------------------------- | -------------------------------------------------------- | -------------------- |
| 获取星图账户信息          | AdQualification->AdvertiserAvatarGet()                   | 巨量广告             |
| 更新广告主账户头像        | AdQualification->AdvertiserAvatarSubmit()                | 巨量广告             |
| 获取广告主账户头像 ID     | AdQualification->AdvertiserAvatarUpload()                | 巨量广告             |
| 获取推广产品资质规则配置  | AdQualification->AdvertiserDeliveryPkgConfig()           | 巨量广告             |
| 批量删除推广产品资质      | AdQualification->AdvertiserDeliveryPkgDelete()           | 巨量广告             |
| 获取推广产品资质          | AdQualification->AdvertiserDeliveryPkgGet()              | 巨量广告             |
| 上传/更新推广产品资质     | AdQualification->AdvertiserDeliveryPkgSubmit()           | 巨量广告             |
| 批量删除投放资质          | AdQualification->AdvertiserDeliveryQualificationDelete() | 巨量广告             |
| 获取投放资质（新版）      | AdQualification->AdvertiserDeliveryQualificationList()   | 巨量广告             |
| 上传/更新投放资质（新版） | AdQualification->AdvertiserDeliveryQualificationSubmit() | 巨量广告             |
| 获取广告主公开信息        | AdQualification->AdvertiserPublicInfoGet()               | 巨量广告、巨量本地推 |
| 上传投放资质（旧版）      | AdQualification->AdvertiserQualificationCreateV2()       | 巨量广告             |
| 获取主体资质（新版）      | AdQualification->AdvertiserQualificationGet()            | 巨量广告             |
| 获取投放资质（旧版）      | AdQualification->AdvertiserQualificationSelectV2()       | 巨量广告             |
| 上传主体资质（新版）      | AdQualification->AdvertiserQualificationSubmit()         | 巨量广告             |
| 上传资质图片              | AdQualification->FileImageAdvertiser()                   | 巨量广告             |
| 获取星图账户信息          | AdQualification->StarInfo()                              | 巨量星图             |

## Agency 代理商账户管理

| 方法名称           | 调用方法                               | 支持平台                                 |
| ------------------ | -------------------------------------- | ---------------------------------------- |
| 更新广告主所属销售 | Agency->AgentAdvAdvertiserUpdateSale() | 巨量广告                                 |
| 广告主账户复制     | Agency->AgentAdvertiserCopy()          | 巨量广告                                 |
| 代理商管理账户列表 | Agency->AgentAdvertiserSelect()        | 巨量广告、巨量千川、巨量本地推、巨量星图 |
| 更新广告主信息     | Agency->AgentAdvertiserUpdate()        | 巨量广告                                 |
| 二级代理商列表     | Agency->AgentChildAgentSelect()        | 巨量广告、巨量本地推、巨量星图           |
| 获取代理商账户信息 | Agency->AgentInfo()                    | 巨量广告、巨量千川、巨量本地推、巨量星图 |

## Finance 资金和流水管理

| 方法名称                              | 调用方法                                              | 支持平台           |
| ------------------------------------- | ----------------------------------------------------- | ------------------ |
| 获取账户余额                          | Finance->QianChuanAccountBalanceGet()                 | 巨量千川           |
| 获取财务流水信息                      | Finance->QianChuanFinanceDetailGet()                  | 巨量千川           |
| 获取账户钱包信息                      | Finance->QianChuanFinanceWalletGet()                  | 巨量千川           |
| 共享钱包-查询账户对应公司下的钱包关系 | Finance->SharedWalletAccountRelationGet()             | 巨量广告           |
| 资金共享-查询共享钱包日流水           | Finance->SharedWalletDailyStatGet()                   | 巨量广告           |
| 资金共享-共享钱包信息查询             | Finance->SharedWalletMainWalletGet()                  | 巨量广告           |
| 资金共享-查询共享钱包流水明细         | Finance->SharedWalletTransactionDetailGet()           | 巨量广告           |
| 资金共享-批量查询钱包余额             | Finance->SharedWalletWalletBalanceGet()               | 巨量广告           |
| 资金共享-查询共享钱包信息             | Finance->SharedWalletWalletInfoGet()                  | 巨量广告           |
| 资金共享-查询共享钱包关系             | Finance->SharedWalletWalletRelationGet()              | 巨量广告           |
| 批量查询账户余额                      | Finance->AccountFundGet()                             | 巨量广告、巨量星图 |
| 查询账户日流水                        | Finance->AdvertiserFundDailyStat()                    | 巨量广告、巨量星图 |
| 查询账号余额                          | Finance->AdvertiserFundGet()                          | 巨量广告           |
| 查询账号流水明细                      | Finance->AdvertiserFundTransactionGet()               | 巨量广告、巨量星图 |
| 查询代理商转账记录                    | Finance->AgentTransferTransactionRecord()             | 巨量广告、巨量星图 |
| 工作台转账-获取最大可转余额           | Finance->CgTransferCanTransferBalanceGet()            | 巨量广告           |
| 工作台转账-获取可转列表               | Finance->CgTransferCanTransferTargetList()            | 巨量广告           |
| 转账-发起转账（代理）                 | Finance->CgTransferCreateTransfer()                   | 巨量广告           |
| 转账-获取最大可转余额（代理）         | Finance->CgTransferQueryCanTransferBalance()          | 巨量广告           |
| 转账-查询账户转账余额（代理）         | Finance->CgTransferQueryTransferBalance()             | 巨量广告           |
| 转账-查询转账单信息（代理）           | Finance->CgTransferQueryTransferDetail()              | 巨量广告           |
| 工作台转账-查询账户转账余额           | Finance->CgTransferTransferBalanceGet()               | 巨量广告           |
| 工作台转账-发起转账                   | Finance->CgTransferTransferCreate()                   | 巨量广告           |
| 工作台转账-查询转账单信息             | Finance->CgTransferTransferDetailGet()                | 巨量广告           |
| 资金共享-最大可转余额查询             | Finance->CgTransferWalletTransferCanTransferBalance() | 巨量广告           |
| 资金共享-发起转账                     | Finance->CgTransferWalletTransferCreate()             | 巨量广告           |
| 资金共享-查询转账单信息               | Finance->CgTransferWalletTransferDetail()             | 巨量广告           |
| 资金共享-查询转账列表                 | Finance->CgTransferWalletTransferList()               | 巨量广告           |
| 开票-新建开票申请单（代理商版）       | Finance->CreateStatementInvoice()                     | 巨量广告           |
| 获取返货共享钱包余额                  | Finance->FundSharedWalletBalanceGet()                 | 巨量广告           |
| 排期—查询业务实体 ID                  | Finance->QueryBookingBusinessEntityIdGet()            | 巨量广告           |
| 开票-查询开票单数据（代理商版）       | Finance->QueryInvoice()                               | 巨量广告           |
| 开票-获取电子发票文件接口（代理商版） | Finance->QueryInvoiceElectronicUrl()                  | 巨量广告           |
| 查询项目信息                          | Finance->QueryProject()                               | 巨量广告           |
| 返点-查询返点核算流水                 | Finance->QueryRebateAccountingInfo()                  | 巨量广告           |
| 返点-查询返点流水                     | Finance->QueryRebateBalance()                         | 巨量广告           |
| 查询项目关联结算单信息                | Finance->QueryStatement()                             | 巨量广告           |

## Workbench 工作台账户管理

| 方法名称                               | 调用方法                                             | 支持平台                       |
| -------------------------------------- | ---------------------------------------------------- | ------------------------------ |
| 获取工作台下账户列表                   | Workbench->CustomerCenterAdvertiserList()            | 巨量广告、巨量千川、巨量本地推 |
| 查询合作组织                           | Workbench->BusinessPlatformPartnerOrganizationList() | 巨量广告                       |
| 获取工作台（原纵横组织）下所有主体信息 | Workbench->BusinessPlatformCompanyInfoGet()          | 巨量广告                       |
| 获取主体下的账户列表                   | Workbench->BusinessPlatformCompanyAccountGet()       | 巨量广告                       |
