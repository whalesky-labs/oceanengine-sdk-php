# GitHub 机器人工人接入设计

日期：2026-03-18
仓库：`westng/oceanengine-sdk-php`
参考项目：`mineadmin/MineAdmin`

## 目标

为当前 SDK 仓库增加一套与 `MineAdmin` 思路一致的 GitHub 机器人与自动化能力，重点覆盖以下场景：

1. PR 自动代码审查
2. Issue 标准化收集
3. Issue / PR 自动欢迎与标签分流
4. Composer 依赖自动更新
5. 基础 CI 自动测试

本次设计不包含以下内容：

- 不自建 AI 服务
- 不直接接入 OpenAI 等大模型 API
- 不让机器人自动改代码或自动合并 PR
- 不做复杂的 issue 智能诊断工作流

## 采用方案

整体采用与 `MineAdmin` 接近的组合：

- `CodeRabbit`：PR 智能审查
- `boring-cyborg`：首次 issue / PR 欢迎评论、按路径打标签
- `GitHub Issue Template`：规范 issue 输入
- `GitHub Actions`：基础 CI
- `Dependabot`：Composer 依赖自动更新

## 设计原则

1. 优先复用现成 GitHub App 与 GitHub 原生能力
2. 配置尽量简单，先让流程跑通
3. 与当前仓库现状保持一致，优先复用已有 `composer test`
4. 不引入仓库外的服务端部署
5. 输出体验尽量贴近 `MineAdmin`

## 目标文件

计划新增以下文件：

- `.coderabbit.yaml`
- `.github/boring-cyborg.yml`
- `.github/dependabot.yml`
- `.github/workflows/ci.yml`
- `.github/ISSUE_TEMPLATE/bug-report.md`
- `.github/ISSUE_TEMPLATE/feature-request.md`
- `.github/ISSUE_TEMPLATE/support-question.md`
- `.github/ISSUE_TEMPLATE/config.yml`

## 组件设计

### 1. CodeRabbit

用途：

- 自动审查 PR
- 输出中文 review 建议
- 自动生成 PR 摘要与 review 状态
- 对评论进行自动回复

配置方向与 `MineAdmin` 保持一致：

- `language: zh-CN`
- 自动 review 开启
- 针对默认分支开启自动审查
- review 摘要开启
- review 状态开启
- auto reply 开启

适配当前仓库时仅做最小调整：

- 默认分支名称从 `master` 调整为当前实际主分支时再确认
- 检查重点聚焦 PHP SDK 代码、测试、文档、Composer 相关变更

### 2. boring-cyborg

用途：

- 首次 issue 自动欢迎
- 首次 PR 自动欢迎
- 按文件路径给 PR 自动打标签

当前仓库的路径标签将按模块归类，保持轻量：

- `Account`
- `DataReports`
- `Tools`
- `Materials`
- `Core`
- `Oauth`
- `EnterpriseAccount`
- `JuLiangAds`
- `JuLiangLocalPush`
- `JuLiangQianChuan`
- `JuLiangStarMap`
- `Tests`
- `Documentation`
- `Composer`
- `GitHub`

欢迎评论延续 `MineAdmin` 风格，使用简短的中英混合提示即可。

### 3. Issue Templates

采用与 `MineAdmin` 相同的三类入口：

#### bug-report

用于收集缺陷类问题，要求提供：

- PHP 版本
- SDK 版本
- 操作系统或运行环境
- 复现步骤
- 实际结果
- 期望结果
- 报错信息或返回内容

#### feature-request

用于收集需求建议，要求提供：

- 当前问题
- 希望的解决方式
- 可接受替代方案
- 其他补充背景

#### support-question

用于收集使用咨询，要求提供：

- 是否已阅读文档
- 是否已搜索现有 issue
- 具体问题描述
- 已尝试的排查动作

### 4. Issue Template 配置

通过 `config.yml` 收敛入口，并引导用户优先查看：

- `README.md`
- `CONFIG_GUIDE.md`
- `ERROR_CODES_GUIDE.md`

必要时可以补充“提问前检查文档与历史 issue”的提示。

### 5. CI 工作流

新增最小可用的 `ci.yml`：

- 触发事件：`push`、`pull_request`
- 运行环境：`ubuntu-latest`
- PHP 版本：优先单版本起步
- 安装 Composer 依赖
- 执行 `composer test`

理由：

- 当前仓库已经定义了 `composer test`
- 该脚本只运行 `unit` 和 `module`，比直接跑 `integration` 更稳定
- 适合先作为基础质量门禁

后续如需增强，可在后续迭代中增加：

- 多 PHP 版本矩阵
- `composer test:all`
- 代码风格检查

### 6. Dependabot

使用与 `MineAdmin` 相同的简洁策略：

- 生态：`composer`
- 目录：`/`
- 周期：默认每日
- 限制同时打开的 PR 数量

## 数据流

### PR 流程

1. 开发者提交 PR
2. GitHub Actions 运行 `ci.yml`
3. CodeRabbit 自动审查 PR
4. boring-cyborg 根据改动路径添加标签
5. 维护者结合 CI 与 review 建议进行人工决策

### Issue 流程

1. 用户通过模板创建 issue
2. boring-cyborg 处理首次 issue 欢迎
3. GitHub 根据模板自带标签完成基础分类
4. 维护者根据模板收集到的信息继续分析问题

## 错误处理与风险

### 风险 1：CodeRabbit 未安装或仓库未授权

表现：

- `.coderabbit.yaml` 已存在，但 PR 无自动审查

处理：

- 在 GitHub 仓库安装并授权 CodeRabbit App
- 安装前仓库内配置不会自动生效

### 风险 2：boring-cyborg 未安装

表现：

- 标签与欢迎评论不生效

处理：

- 在 GitHub 仓库安装 boring-cyborg App

### 风险 3：CI 依赖外部配置

表现：

- 如果某些测试依赖 `.env` 或外部账号，可能导致 CI 失败

处理：

- 初版仅运行当前稳定的 `composer test`
- 暂不将 integration 测试接入必跑流程

## 测试与验收

验收标准：

1. 新建 PR 后，CI 自动触发
2. 新建 PR 后，CodeRabbit 能自动给出 review
3. 首次 PR 用户能收到欢迎评论
4. 首次 issue 用户能收到欢迎评论
5. 按不同 issue 模板创建 issue 时，标签与内容结构正确
6. Dependabot 能按计划创建 Composer 更新 PR

## 实施顺序

1. 新增 `.github` 配置文件
2. 提交到仓库
3. 在 GitHub 安装 CodeRabbit
4. 在 GitHub 安装 boring-cyborg
5. 观察首个 PR / issue 的执行效果

## 参考

- MineAdmin `.coderabbit.yaml`
- MineAdmin `.github/boring-cyborg.yml`
- MineAdmin `.github/ISSUE_TEMPLATE/*`
- MineAdmin `.github/dependabot.yml`
