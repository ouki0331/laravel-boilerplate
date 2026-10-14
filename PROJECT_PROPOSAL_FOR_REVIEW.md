# 项目背景与商业提案评审指南 (For AI Agent Review)

> 💡 **给评审 Agent 的说明 (Prompt)**：
> 请你扮演一名**资深的系统架构师与日本 IT/SaaS 商业顾问**，对以下项目方案进行严格的评审（Review）、可行性验证、架构挑刺与查漏补缺。

---

## 一、 开发者背景与核心诉求 (Background & Goal)
* **开发者画像**：在日华人开发者，具备 5 年 C# 经验（3年前）及近 1.5 年现代 PHP/Laravel 经验。
* **核心目标**：开发一款极具说服力、可落地的作品，上传至 GitHub 作为个人代表作（Portfolio）。一方面面向日本自由职业接单市场（如 CrowdWorks、本地中小企业私活），另一方面展示企业级工程化素养。
* **选型策略**：放弃过于沉重且难落地的医疗/牙医核心系统或大型房产门户，选择周期可控、贴合日本线下商铺痛点、易变现的 **LINE 私域预约与客户 CRM 闭环系统**。

---

## 二、 已完成的底层种子模板 (Existing Infrastructure)
* **GitHub 仓库**：[https://github.com/ouki0331/laravel-boilerplate](https://github.com/ouki0331/laravel-boilerplate)
* **环境编排**：Docker Compose (Nginx + PHP 8.3 FPM + PostgreSQL 16 + Redis 7)。
* **工程化与质量门禁 (CI/CD)**：
  * GitHub Actions 自动化流水线 (`.github/workflows/ci.yml`)
  * `composer audit` 漏洞审计 + `Laravel Pint` 代码风格
  * `Larastan (PHPStan)` 静态类型检查 (Level 5)
  * PostgreSQL + Redis 数据库双向迁移与回滚测试 (`migrate` & `migrate:rollback`)
  * PHPUnit 自动化功能与单元测试
* **封装组件 (IoC Service Contracts)**：
  * **RBAC 权限**：`spatie/laravel-permission` (已就绪)
  * **Excel 导出**：`ExcelServiceInterface` (Maatwebsite Excel 封装)
  * **PDF 生成**：`PdfServiceInterface` (DomPDF 模板渲染)
  * **二维码生成**：`QrCodeServiceInterface` (SVG / Base64 无落盘流)
  * **统一邮件体系**：`MailServiceInterface`
  * **标准 RESTful**：`ApiResponse` (统一 JSON 结构规范) 与 `CookieHelper`
  * **异常与视图**：标准化 404/500 处理。

---

## 三、 待验证的业务方案：LINE 预约与客户 CRM 系统 (Business Proposal)

### 1. 目标客户与痛点
* **目标行业**：个人私教健身房（パーソナルジム）、美业沙龙（美容院・ネイルサロン）、小型咨询/个人事务所。
* **核心痛点**：
  1. 苦于 HotPepper Beauty 等中心化平台的高昂上架费与订单抽成。
  2. 极度希望将老客沉淀至商户自己的 **LINE 官方账号（LINE 公式アカウント）**。
  3. 爽约率高（No-show）导致工位与人力闲置。

### 2. 业务全流程架构 (User Journey)
* **C 端顾客 (LINE LIFF)**：
  * 扫码关注商户 LINE 官方号。
  * 底部点击 Rich Menu 打开 LIFF 网页，免密授权获取 LINE UID 与头像。
  * 选择服务项目（时长/价格） ➔ 选择教练/技师 ➔ 日历选择空闲时隙（Time Slot）。
  * 提交预约 ➔ 后端防并发排他锁 ➔ 预约成功。
  * 顾客在 LINE 聊天框秒级收到 **Flex Message 卡片** 确认通知，且预约日前一天自动收到定时提醒。
* **B 端商户 (Laravel Admin Dashboard)**：
  * 日历排班看盘（查看各工位、各教练每天的日程状态）。
  * 规则设定（排班上下班时间、服务时长、每单前后的休息缓冲间隔 buffer time）。
  * 顾客 CRM 档案（LINE 昵称、历史出勤次数、健康特记事项）。
  * 营业额与报表导出（一键导出月度排班与收入 PDF/Excel）。

### 3. 核心数据表设计草案 (Schema Draft)
* `line_customers`：`id`, `line_user_id` (UK), `display_name`, `picture_url`, `phone`, `memo`
* `staffs`：`id`, `name`, `role`, `is_active`
* `services`：`id`, `title`, `duration_minutes`, `price`, `buffer_minutes`
* `schedules`：`id`, `staff_id`, `day_of_week`, `start_time`, `end_time`
* `appointments`：`id`, `appointment_no` (UK), `line_customer_id`, `staff_id`, `service_id`, `start_at`, `end_at`, `status` (`pending`, `confirmed`, `cancelled`, `completed`), `customer_note`, `staff_memo`

---

## 四、 请评审 Agent 重点评估并回答以下 4 个问题 (Review Prompts)

1. **商业与落地性评估**：
   * 在当下的日本自由职业市场，拿这样一套系统去向中小商户推销或在 CrowdWorks/Lancers 接单，卖点和溢价空间是否合理？
   * 相比市场上现成的 SaaS（如 STORES 予約、tol、Lステップ），本系统的差异化切入点应该是什么？

2. **技术架构与高并发防超卖验证**：
   * 在“选时间槽（Time-slot）”环节，如何优雅实现服务时长 + 缓冲时间（buffer time）的算法切片？
   * 面对两人几乎同一秒抢订同一位教练的并发场景，除了 Redis 锁与 DB 行级锁，还有哪些必须考虑的边缘情况（Edge Cases）？

3. **数据库与领域模型审查**：
   * 目前的数据表设计草案中，是否遗漏了关键字段或关系（例如：多店铺支持、法定节假日/临时休假处理、取消政策/违约金、支付订金状态等）？

4. **开发优先级与 MVP 范围建议**：
   * 如果要在 2~3 周内做出极其惊艳的第一版，你建议砍掉哪些非核心功能？最先保住哪些杀手锏功能？
