# 🌾 FeedManager Pro
## 💡 What this project demonstrates

This project showcases:
- Secure authentication system with rate limiting
- Role-based access control (RBAC)
- Real-time POS system with transactional safety
- Scalable Laravel backend architecture
- Production-level security practices
  
Designed to simulate a real-world inventory and sales system.

A production-ready **Laravel 11** feed store management system with:

- 🔐 Secure login with rate limiting & session protection
- 👥 Role-based access control (Admin / Staff)
- 🛒 Point of Sale (POS) with live cart
- 📦 Order management with printable invoices
- 🌾 Product inventory with stock alerts
- 📊 Dashboard with Chart.js analytics
- 👤 User management (admin only)
- 🔒 CSRF, SQL injection, and XSS protection built-in

---
## 🧠 Backend Design Highlights

- Used Laravel MVC architecture for clear separation of concerns
- Implemented database transactions for safe stock updates
- Applied middleware for role-based route protection
- Designed relational schema with normalized tables
  
  ## ⚡ Challenges & Solutions

- Prevented race conditions in stock updates using DB transactions
- Secured authentication against brute-force attacks with rate limiting
- Ensured role-based authorization using middleware guards

## 🚀 Quick Setup (5 steps)

### Prerequisites
- PHP 8.2+
- Composer
- SQLite (built into PHP, no extra install needed)

---

### Step 1 — Clone / Extract
```bash
# If from zip, extract and enter the folder:
unzip feedmanagerpro.zip
cd feedmanagerpro
```

---

### Step 2 — Install dependencies
```bash
composer install
```

---

### Step 3 — Environment setup
```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate
```

> The `.env` is pre-configured for SQLite — no database server needed.

---

### Step 4 — Database setup
```bash
# Create SQLite database file
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed with demo data (users, products, sample orders)
php artisan db:seed
```

---

### Step 5 — Start the server
```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔑 Demo Login Credentials

| Email | Password | Role |
|-------|----------|------|
| admin@feed.pro | admin123 | **Administrator** |
| staff@feed.pro | staff123 | Staff |

---

## 🗂️ Project Structure

```
feedmanagerpro/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       ← Login / logout
│   │   │   ├── DashboardController.php  ← Analytics
│   │   │   ├── ProductController.php    ← CRUD products
│   │   │   ├── OrderController.php      ← POS + invoices
│   │   │   ├── UserController.php       ← Admin: manage users
│   │   │   └── ProfileController.php    ← Own account
│   │   └── Middleware/
│   │       └── AdminMiddleware.php      ← Admin-only guard
│   └── Models/
│       ├── User.php                     ← role: admin | staff
│       ├── Product.php
│       ├── Order.php
│       └── OrderItem.php
├── database/
│   ├── migrations/                      ← Table schemas
│   └── seeders/DatabaseSeeder.php       ← Demo data
├── resources/views/
│   ├── layouts/app.blade.php            ← Master layout + sidebar
│   ├── auth/login.blade.php             ← Login page
│   ├── dashboard/index.blade.php        ← Dashboard + charts
│   ├── products/                        ← Product CRUD views
│   ├── orders/                          ← POS, list, invoice
│   ├── users/                           ← User management
│   └── profile/                         ← Profile & password
└── routes/web.php                       ← All routes
```

---

## 🔐 Security Features

| Feature | Implementation |
|---------|---------------|
| Authentication | Laravel `Auth` with bcrypt hashing |
| Rate limiting | 5 attempts/min per IP+email (`RateLimiter`) |
| CSRF protection | Laravel CSRF tokens on all forms |
| Session fixation | `session()->regenerate()` on login |
| SQL injection | Eloquent ORM — parameterized queries |
| XSS prevention | Blade `{{ }}` auto-escaping |
| Role enforcement | `AdminMiddleware` + route groups |
| Stock race conditions | `DB::transaction` + `lockForUpdate()` |
| Password rules | Min 8 chars, letters + numbers required |
| Self-delete guard | Users cannot delete themselves |

---

## 👥 Role Permissions

| Feature | Admin | Staff |
|---------|-------|-------|
| Dashboard | ✅ | ✅ |
| Point of Sale | ✅ | ✅ |
| View Orders | ✅ | ✅ |
| Delete Orders | ✅ | ❌ |
| View Products | ✅ | ✅ |
| Add/Edit/Delete Products | ✅ | ❌ |
| User Management | ✅ | ❌ |
| Own Profile | ✅ | ✅ |

---

## 🗄️ Database Schema

```
users          — id, name, email, password, role (admin|staff)
products       — id, name, type, price, stock_quantity
orders         — id, customer_name, phone, address, total, user_id
order_items    — id, order_id, product_id, quantity, price
```

---

## ⚙️ Switching to MySQL

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=feedmanager
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then run:
```bash
php artisan migrate:fresh --seed
```

---

## 🖨️ Printing Invoices

Navigate to any order → click **Invoice** → use browser **Print** (Ctrl+P).
The layout is print-optimised (sidebar/nav hidden via CSS `@media print`).

---

## 🛠 Useful Artisan Commands

```bash
# Reset and re-seed database
php artisan migrate:fresh --seed

# Create a new admin via tinker
php artisan tinker
>>> \App\Models\User::create(['name'=>'New Admin','email'=>'new@feed.pro','password'=>bcrypt('password123'),'role'=>'admin'])

# Clear all caches
php artisan optimize:clear
```

---

## 📦 Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** SQLite (default) / MySQL compatible
- **Frontend:** Blade templates, vanilla JS, Chart.js 4
- **Auth:** Laravel built-in authentication
- **Fonts:** Syne + DM Sans (Google Fonts)
- **Charts:** Chart.js 4.4

---
 <img width="1359" height="618" alt="Capture1" src="https://github.com/user-attachments/assets/cff49f5f-b7e8-4f1b-8e28-42496bd11624" />
 <img width="1363" height="617" alt="Capture2" src="https://github.com/user-attachments/assets/a808202a-5bc6-42bf-8443-7677e39414b0" />
 <img width="1365" height="619" alt="Capture3" src="https://github.com/user-attachments/assets/2e8d95ef-8f69-40c3-86ff-c02a9cceeb00" />
 <img width="1366" height="616" alt="Capture4" src="https://github.com/user-attachments/assets/ee855425-92f6-476b-815b-e9a2f01276d3" />
<img width="1363" height="620" alt="Capture6" src="https://github.com/user-attachments/assets/e50f7d31-85c5-4f19-8ae8-b297c62d84a2" />
<img width="1134" height="615" alt="Capture7" src="https://github.com/user-attachments/assets/f7a0fad3-7996-4388-bee7-11f879571f40" />
*Built for reallife work — FeedManager Pro*
