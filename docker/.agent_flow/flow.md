# Project Documentation: Automation Engine (Zapier-like)

## 1. Tổng quan dự án (Project Overview)
Dự án là một hệ thống Automation Engine cho phép người dùng tự định nghĩa và thực thi các luồng công việc tự động (workflows) dựa trên kiến trúc Event-Driven. Dự án được thiết kế hướng tới khả năng mở rộng cao, xử lý background jobs bất đồng bộ và đáp ứng UI linh hoạt cho việc kéo thả/config steps.

**Tech Stack:**
* **Backend:** PHP 8.2, Laravel 12
* **Frontend:** ReactJS (TypeScript) via Laravel Breeze
* **Database:** PostgreSQL (tận dụng mạnh JSONB)
* **Infrastructure:** Docker
* **Architecture:** Domain-Driven Design (DDD)

---

## 2. Cấu trúc Database (Database Schema)
Hệ thống xoay quanh 3 bảng core. Dưới đây là mô tả chi tiết và các lưu ý về tối ưu (Indexing/Constraints):

| Table | Cột (Columns) | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- | :--- |
| `workflows` | `uuid` (PK)<br>`user_id` (Index)<br>`name`<br>`status` | UUID<br>UUID/Int<br>String<br>Enum | `status`: `active`, `inactive`. Chỉ trigger những workflow `active`. |
| `workflow_steps` | `uuid` (PK)<br>`workflow_id` (FK, Index)<br>`type`<br>`settings`<br>`order` | UUID<br>UUID<br>Enum<br>JSONB<br>Integer | `type`: `trigger`, `action`, `condition`.<br>`order`: Xác định thứ tự thực thi.<br>*Cần tạo GIN index cho `settings` nếu sau này có nhu cầu query deep json.* |
| `workflow_logs` | `uuid` (PK)<br>`workflow_id` (FK, Index)<br>`status`<br>`payload` | UUID<br>UUID<br>Enum<br>JSONB | `status`: `success`, `failed`, `running`.<br>`payload`: Lưu trữ input/output của từng step để trace lỗi. |

---

## 3. Kiến trúc Domain-Driven Design (DDD)
Áp dụng strict DDD để isolate business logic của hệ thống Automation. Cấu trúc thư mục trong `app/Domain/Workflows` sẽ như sau:

```text
app/
└── Domain/
    └── Workflows/
        ├── Models/
        │   ├── Workflow.php
        │   ├── WorkflowStep.php
        │   └── WorkflowLog.php
        ├── Actions/
        │   ├── CreateWorkflowAction.php
        │   ├── UpdateWorkflowStepAction.php
        │   └── ExecuteWorkflowAction.php       # Nơi trigger job
        ├── DTOs/
        │   ├── WorkflowData.php                # Readonly class (PHP 8.2)
        │   └── StepSettingsData.php
        ├── Enums/
        │   ├── WorkflowStatus.php
        │   ├── StepType.php
        │   └── LogStatus.php
        ├── Contracts/                          # Interfaces
        │   ├── StepExecutorInterface.php       # Interface cho các Action/Condition
        │   └── TriggerParserInterface.php
        └── Services/                           # The Engine
            └── WorkflowExecutionEngine.php     # Xử lý logic đọc steps và chạy tuần tự