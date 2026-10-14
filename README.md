# Laravel Starter Template & LINE Reservation System Design

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Redis](https://img.shields.io/badge/Redis-7.x-DC382D?logo=redis&logoColor=white)](https://redis.io/)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)
[![Larastan](https://img.shields.io/badge/PHPStan%20%2F%20Larastan-Level%205-blue)](https://phpstan.org/)
[![Pint](https://img.shields.io/badge/Code%20Style-Laravel%20Pint-brightgreen)](https://laravel.com/docs/pint)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A solid, containerized Laravel 11 starter boilerplate with standard business utilities, IoC service contracts, CI/CD pipeline, and a specialized domain architecture for a Japanese personal gym LINE reservation MVP.

---

## 🌟 包含组件与实装状态 (Components & Implementation Status)

### 1. 核心业务服务与工具类 (IoC Service Layer)
* **RBAC 角色权限体系**:
  * 集成 `spatie/laravel-permission`。
  * 数据库表迁移已纳入版本控制 (`create_permission_tables`)。
  * 内置默认角色与权限填充器 (`RoleAndPermissionSeeder`: `admin`, `staff`, `customer`)。
  * 配备完整功能测试验证 (`tests/Feature/RbacTest.php`)。
* **API 认证与令牌**:
  * 集成 `laravel/sanctum`，`User` 模型接入 `HasApiTokens`，预置个人令牌数据迁移。
* **Excel 导出**: 封装 `ExcelServiceInterface`，支持轻量数组流直接导出与自定义导出器。
* **PDF 生成**: 封装 `PdfServiceInterface`，支持 Blade 视图直接渲染并输出 PDF 字节流或下载。
* **矢量二维码**: 封装 `QrCodeServiceInterface`，支持 SVG 格式与 Base64 Data URI，前端免落盘直接加载。
* **统一邮件体系**: 封装 `MailServiceInterface`，支持带模板渲染、附件传递与全流程异常错误捕获。
* **标准 API 响应**: 封装 `ApiResponse`，统一全系统 JSON 结构（`success`, `code`, `message`, `data`, `pagination`）。
* **Cookie 助手**: 封装 `CookieHelper`（HttpOnly, SameSite, Secure），支持与 Redis 7 会话结合。
* **错误处理与页面**: 全局拦截 API 请求中的 404/422/401/500 并转换为标准 JSON，Web 请求显示定制日系风格 404/500 页面。

### 2. CI/CD 自动化流水线
* **安全漏洞审计**: `composer audit` 扫描依赖库已知 CVE。
* **代码风格校验**: `Laravel Pint` (PSR-12 / Laravel 标准)。
* **静态类型检查**: `Larastan / PHPStan` (Level 5 级别，0 errors)。
* **数据库双向测试**: 自动拉起 PostgreSQL 16 与 Redis 7 容器，运行 `migrate` ➔ `db:seed` ➔ `migrate:rollback` ➔ `migrate`。
* **自动化测试套件**: `PHPUnit` 功能与单元测试 (13 tests, 31 assertions, 100% Passed)。

---

## 🛠️ 快速上手 (Quick Start)

### 1. 环境准备
确保本机已安装 [Docker](https://www.docker.com/) 与 [Docker Compose](https://docs.docker.com/compose/)。

### 2. 启动与初始化步骤
```bash
# 1. 复制针对 Docker 环境优化的环境变量文件
cp .env.example .env

# 2. 启动 Docker 容器群 (Nginx, PHP-FPM, PostgreSQL, Redis)
docker compose up -d

# 3. 在容器内安装 Composer 依赖 (若是 fresh clone)
docker compose exec app composer install

# 4. 生成应用密钥 (APP_KEY 已在 .env.example 和 phpunit.xml 中预设测试值)
docker compose exec app php artisan key:generate

# 5. 执行数据库迁移与初始角色数据填充
docker compose exec app php artisan migrate:fresh --seed
```

### 3. 本地验证端点
* **Web 应用首页**: [http://localhost:8033](http://localhost:8033)
* **API 状态检查**: [http://localhost:8033/api/v1/ping](http://localhost:8033/api/v1/ping)
* **矢量二维码演示**: [http://localhost:8033/demo/qrcode](http://localhost:8033/demo/qrcode)
* **PDF 账票下载演示**: [http://localhost:8033/demo/pdf](http://localhost:8033/demo/pdf)
* **Excel 导出演示**: [http://localhost:8033/demo/excel](http://localhost:8033/demo/excel)

---

## 🧪 代码质量与测试 (Testing & Linting)

```bash
# 运行全部功能与单元测试 (包含 RBAC 鉴权、API 响应与各服务测试)
docker compose exec app vendor/bin/phpunit

# 运行代码风格格式化与检查
docker compose exec app vendor/bin/pint --test

# 运行 Larastan 静态类型扫描 (Level 5)
docker compose exec app vendor/bin/phpstan analyse
```

---

## 📱 业务演进方案：单店パーソナルジム (私教健身房) 专用 LINE 预约与来店管理系统

基于资深评审意见，业务系统**收敛聚焦于单店垂直行业**，不追求横向无差异的通用 SaaS，而是作为面向日本线下单店的一站式高可靠定制交付方案。

### 1. 差异化定位与商业切入点
* **核心价值**: 帮助 1~10 名教练的单店私教馆摆脱 HotPepper 平台抽成，打通自有 LINE 官方号。
* **定制壁垒**:
  * 深入私教馆独特规则：课程消耗、担当教练指定/轮空、器械场地排他、体验课规则、更衣与准备缓冲时长。
  * 提供包含 LINE 审查、Rich Menu、日文操作说明书与老会员历史数据导入在内的全套交付服务。

### 2. 时间槽切片与高并发防超卖架构

#### ① 时间槽模型 (Time-Slot Math)
* **服务区间**: `[start, start + duration)`
* **实际占用区间 (半开区间)**: `[start - buffer_before, start + duration + buffer_after)`
* **候选开始时间步长**: `slot_interval`（如 15 分钟）
* **计算逻辑**:
  1. 获取门店当天营业时间。
  2. 求交集于所选教练的工作排班区间。
  3. 扣除临时休假 (`schedule_exceptions`) 与管理员手工封锁时段。
  4. 扣除已有预约的“实际占用区间”。
  5. 按步长切片生成前端可选时隙；提交时在后端事务中再次全量重新校验。

#### ② 防超卖的底层物理防线 (PostgreSQL Range Exclusion Constraint)
Redis 锁作为削峰和防重试体验优化，**数据的最终强一致性防线设在 PostgreSQL 数据库层**：
```sql
-- 使用 PostgreSQL 原生 GIST 排他约束，保证同一教练被占用的时间区间绝不重叠
ALTER TABLE appointment_allocations 
ADD CONSTRAINT exclude_staff_overlapping_time 
EXCLUDE USING gist (
    staff_id WITH =, 
    occupied_range WITH &&
) WHERE (is_active = true);
```
当两名顾客同时抢订同一时隙时，数据库直接抛出 `ExclusionViolation`，后端优雅转换为 HTTP 409 Conflict 与清晰的日文提示。

#### ③ LINE 鉴权安全规范
* 前端 LIFF 不直接透传未经签名的用户 ID。
* 前端通过 LIFF SDK 取得 `id_token`，提交至后端由官方 API (`https://api.line.me/oauth2/v2.1/verify`) 验证签名与合法性，彻底杜绝身份伪造。

### 3. 2~3 周 MVP 范围界定
* **必须保住的核心 (In Scope)**:
  1. 单店、多教练排班（上下班、休息日、临时请假）。
  2. LIFF 移动端预约界面（日文原生体验、时隙选择）。
  3. 基于 PostgreSQL GIST 排他约束的高并发防超卖验证。
  4. 管理员 PC 日历看盘 (FullCalendar) 与手工改期/锁台。
  5. LINE 预约成功 Flex Message 推送与前日自动提醒。
* **第一版果断砍掉 (Out of Scope)**:
  * 在线信用卡支付与退款（先支持现场结算或既有回数券）。
  * 多店铺切换与复杂多租户权限。
  * Google Calendar 双向同步。
  * 高级 CRM 分群营销与 AI 推荐。

---

## 📄 开源许可 (License)
本项目基于 [MIT License](LICENSE) 开源。
