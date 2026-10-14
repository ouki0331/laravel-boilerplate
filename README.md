# Laravel Enterprise Boilerplate & LINE Reservation CRM Starter

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Redis](https://img.shields.io/badge/Redis-7.x-DC382D?logo=redis&logoColor=white)](https://redis.io/)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)
[![Larastan](https://img.shields.io/badge/PHPStan%20%2F%20Larastan-Level%205-blue)](https://phpstan.org/)
[![Pint](https://img.shields.io/badge/Code%20Style-Laravel%20Pint-brightgreen)](https://laravel.com/docs/pint)

A production-ready, standardized Laravel 11 application template crafted for freelance and enterprise client delivery. Built with strict coding standards, full containerization, unified IoC service architecture, and CI/CD quality gates.

---

## 🌟 核心特性 (Core Features)

### 1. 业务与工具组件 (Pre-integrated Business Components)
* **RBAC 角色权限控制 (Role-Based Access Control)**: 集成 `spatie/laravel-permission`，内置角色与权限中间件（`role`, `permission`）。
* **Excel 导入导出**: 集成 `maatwebsite/excel`，封装 `ExcelServiceInterface`，支持数组流直接下载与复杂格式导出。
* **网页/模板转 PDF**: 集成 `barryvdh/laravel-dompdf`，封装 `PdfServiceInterface`，支持 Blade 模板直接渲染为合同、收据、账票 PDF。
* **矢量二维码生成**: 集成 `simplesoftwareio/simple-qrcode`，封装 `QrCodeServiceInterface`，支持纯 SVG 与 Base64 格式，零落盘前端渲染。
* **统一邮件发送体系**: 封装 `MailServiceInterface`，支持带模板渲染、附件携带与全流程异常错误捕获。
* **标准 RESTful 响应规范**: 封装 `ApiResponse` 助手，统一全系统 JSON 结构（`success`, `code`, `message`, `data`, `pagination`）。
* **安全 Cookie/Session 管理**: 封装 `CookieHelper`（SameSite, HttpOnly, Secure），基于 Redis 7 支撑高并发会话。
* **全局异常与友好错误页**: 自动区分 Web 与 API 请求，内置精美的日系现代风格 404 与 500 页面。

### 2. 架构设计与工程化规范
* **IoC 容器契约绑定**: 所有基础服务均定义 Contract Interface 并注册单例绑定至 Laravel 服务容器，严格遵守依赖反转原则（DIP）。
* **纯容器化隔离环境**: 预置生产级 Docker 编排架构（Nginx Alpine + PHP 8.3 FPM Bookworm + PostgreSQL 16 + Redis 7）。
* **大厂级 CI/CD 自动化流水线**:
  * 安全漏洞扫描 (`composer audit`)
  * 代码风格检查 (`Laravel Pint`)
  * 静态代码与类型推断 (`Larastan / PHPStan Level 5`)
  * 数据库双向迁移测试 (`migrate` & `migrate:rollback`)
  * 自动化测试套件 (`PHPUnit`)
  * SonarQube / SonarCloud 质量门禁配置

---

## 🛠️ 快速上手 (Quick Start)

### 1. 环境准备
确保本机已安装 [Docker](https://www.docker.com/) 与 [Docker Compose](https://docs.docker.com/compose/)。

### 2. 一键启动
```bash
# 1. 复制环境变量文件
cp .env.example .env

# 2. 启动 Docker 容器群
docker compose up -d

# 3. 执行数据库迁移
docker compose exec app php artisan migrate
```

### 3. 本地访问地址
* **Web 应用首页**: [http://localhost:8033](http://localhost:8033)
* **API 健康检查**: [http://localhost:8033/api/v1/ping](http://localhost:8033/api/v1/ping)
* **二维码生成演示**: [http://localhost:8033/demo/qrcode](http://localhost:8033/demo/qrcode)
* **账票 PDF 下载演示**: [http://localhost:8033/demo/pdf](http://localhost:8033/demo/pdf)
* **Excel 导出演示**: [http://localhost:8033/demo/excel](http://localhost:8033/demo/excel)

---

## 🧪 代码质量与测试 (Testing & Quality Assurance)

```bash
# 运行单元与功能测试套件 (9/9 Passed)
docker compose exec app vendor/bin/phpunit

# 运行代码风格格式化与检查
docker compose exec app vendor/bin/pint --test

# 运行 Larastan 静态代码扫描 (Level 5)
docker compose exec app vendor/bin/phpstan analyse
```

---

## 📱 扩展业务规划：LINE 智能预约与客户 CRM 系统

本项目不仅是基础模板，更可无缝衍生为面向日本本地线下商户（个人私教馆、健身房、美容美甲沙龙、诊所）的 **LINE 官方账号预约管理系统**。

### 1. 商业背景与价值
* **摆脱高额平台佣金**：免去 HotPepper 等大型预约平台的抽成负担，将客户直接沉淀在商家私域 LINE 账号中。
* **零安装门槛**：顾客无需下载 App，通过 LINE 扫码一键授权，几秒内完成选教练、选项目、选时间。
* **防爽约（No-show）自动机制**：预约完成后及前 24 小时，通过 LINE Messaging API 自动推送确认提醒卡片。

### 2. 系统核心架构与流程

```
【顾客端 (LINE LIFF)】
关注官方号 ──> 点击底部 Rich Menu ──> 打开 LIFF 页面 (免密登录)
   ──> 选择服务与人员 ──> 日历时隙排他选择 ──[Redis锁]──> 预约成功
   ──> LINE 自动推送 Flex Message 确认卡片

【商家端 (Laravel Admin)】
PC 看板 ──> 日历视图实时排班 (FullCalendar)
   ──> 教练出勤时段配置
   ──> 顾客档案 (LINE昵称/头像/历史预约/健康备注)
   ──> 月度营业额与出勤报表一键导出 (PDF / Excel)
```

### 3. 核心数据模型 (Data Schema)
* `line_customers`: 记录 LINE UID (`sub`)、昵称、头像、电话及特记备忘。
* `staffs`: 教练/技师基础档案、擅长项目及在职状态。
* `schedules`: 人员常规排班时间规则（星期、上下班时段、休息时段）。
* `services`: 服务类目、时长（分）、缓冲准备时间（分）、单价。
* `appointments`: 预约主表，高精度起止时间戳、状态机（`pending`, `confirmed`, `cancelled`, `completed`）。

---

## 📄 开源许可 (License)
The MIT License (MIT).
