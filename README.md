# LifeLink – Emergency Blood Donor Network

LifeLink is a **production-grade Laravel 12 web application** designed to connect verified blood donors with patients during medical emergencies through a secure, structured, and admin-controlled system.

This project focuses on **data integrity, role-based access, and real-world emergency workflows**, making it suitable for academic submission, portfolio demonstration, and real deployment.

---

## 🚀 Key Features

### 🔐 Authentication & Security

* Laravel Breeze authentication
* Email verification
* Admin verification (`is_verified`)
* Role-based access control (Admin / Donor / Recipient)
* Policy-driven authorization

### 🩸 Blood Request Management

* Recipients can create emergency blood requests
* Admin approval required before donors can respond
* Strict request lifecycle enforcement
* Automatic audit logging for every status change

### 🧑‍⚕️ Donor Management

* Donor profile creation & approval
* Availability control
* Donation eligibility check (90-day rule)
* Location & blood-group–based matching

### 📊 Admin Panel

* User verification & role management
* Donor approval / rejection
* Request approval, fulfilment, cancellation
* System statistics & reports

---

## 🧠 System Roles

### Admin

* Verify users
* Approve donor profiles
* Approve / cancel / fulfill blood requests
* View reports and statistics

### Recipient

* Create blood requests
* Edit or cancel pending requests
* View donor responses
* Track request lifecycle

### Donor

* Create and update donor profile
* Toggle availability (after approval)
* View approved blood requests
* Respond to requests
* Update donation status

---

## 🏗️ Architecture Overview

LifeLink follows a **clean layered architecture**:

```
Routes → Controllers → Policies → Services → Models → Database
                     ↘ Observers (Audit & State Enforcement)
```

### Design Principles

* **Thin controllers** – no business logic
* **Services** handle all state changes
* **Policies** handle authorization
* **Observers** enforce valid state transitions
* **Models** represent data only

---

## 🔄 Blood Request Lifecycle

```
PENDING
  │ (Admin Approval)
  ▼
APPROVED
  │ (Donation)
  ▼
FULFILLED

OR

PENDING / APPROVED
  │ (Admin or Recipient)
  ▼
CANCELLED
```

Invalid transitions are blocked automatically.

---

## 🗃️ Database Highlights

Main tables:

* `users`
* `donor_profiles`
* `recipient_profiles`
* `blood_requests`
* `donor_responses`
* `blood_request_logs`
* `user_settings`

Every critical action is auditable.

---

## 🧪 Testing Strategy

* Seeder-based realistic data
* Role-based testing scenarios
* Policy and service-level validation
* Observer-enforced state safety

---

## ⚙️ Tech Stack

* **Backend:** Laravel 12
* **Frontend:** Blade + Tailwind CSS
* **Database:** MySQL
* **Auth:** Laravel Breeze
* **Architecture:** MVC + Services + Policies + Observers

---

## 📦 Installation

```bash
git clone https://github.com/rhjoyofficial/lifelink.git
cd lifelink
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## ✅ Production Readiness

✔ Role-based access control
✔ Verified user enforcement
✔ Strict state transitions
✔ Audit logs
✔ Scalable architecture

---

## 🧩 One-Line Summary

LifeLink is a Laravel-based emergency blood donation platform that securely connects verified donors with patients through admin-controlled workflows and auditable state management.
