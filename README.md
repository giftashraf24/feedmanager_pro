# 🌾 FeedManager Pro

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

*Built with ❤️ — FeedManager Pro*
