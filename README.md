# 🧺 LaundrySpinzone

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

**LaundrySpinzone** is a modern, multi-branch Laundry Management System built on **Laravel 12** and **Tailwind CSS v4**. It streamlines customer online service requests, walk-in orders, order processing pipelines, machine allocation, sales analytics, and audit logging across multiple laundry branches.

---

## 📸 Key Features

### 👤 Customer Portal
- **Branch Selection**: Choose or switch preferred laundry branch (e.g., Baybay Branch, Panay Branch).
- **Service Requests**: Submit online requests with estimated weights, clothing categories, sizing options, and optional add-ons.
- **Order Tracking**: Real-time status visibility (`Pending` → `Received` → `Washing` → `Drying` → `Ready for Pickup` → `Completed`).
- **Profile & Security**: Update default branch and manage password security.

### 🛡️ Admin & Staff Dashboard
- **Operational Overview**: Real-time dashboard showing total requests, active processing queues, pending orders, and total sales revenue.
- **Walk-in Order Entry**: Quickly generate service requests for walk-in clients with automatic customer account creation.
- **Order Verification & Billing**: Verify actual laundry weight/quantity, auto-calculate tiered pricing + add-ons, and finalize charges.
- **Status Lifecycle Pipeline**: Advance orders step-by-step through wash, dry, pickup, and completion states.
- **Machine Fleet Management**: Track and register washing machines and dryers across different branches.
- **Sales Analytics**: Auto-generate sales entries upon order completion and view historic revenue reports.
- **Security Audit Logs**: Track all login attempts (successful and failed), IP addresses, user agents, and logout timestamps.

### 🏷️ Flexible Pricing Engine
- **Clothing Categories**: Regular Clothes, Beddings/Towels & Curtains, and Comforters.
- **Tiered Pricing**: Weight-based pricing (1-6kg, 7-8kg, 9kg+) and item-size pricing for comforters (Single, Double, Queen, King).
- **Add-on Services**: Extra detergent, fabric conditioner (FabCon), and color safe additives.

---

## 🛠️ Tech Stack

- **Backend Framework**: Laravel 12 (PHP 8.2+)
- **Frontend Stack**: Blade Templates, Tailwind CSS v4, Vite 7
- **Database**: SQLite (default / dev) or MySQL / PostgreSQL
- **Testing**: Pest PHP
- **Process Management**: Concurrently

---

## 📂 Project Architecture

```
LaundrySpinzone/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AdminController.php      # Admin dashboard, order pipeline, machines, sales & logs
│   │       └── CustomerController.php   # Customer portal, order creation & branch selection
│   └── Models/
│       ├── Addon.php                   # Laundry add-ons (Detergent, FabCon, etc.)
│       ├── Admin.php                   # Admin guard model
│       ├── Branch.php                  # Laundry branch model
│       ├── ClothType.php               # Clothing category classification
│       ├── Customer.php                # Customer guard model
│       ├── LoginLog.php                # Authentication audit logs
│       ├── Machine.php                 # Washer & Dryer inventory
│       ├── PricingTier.php             # Dynamic weight/size pricing rates
│       ├── Sale.php                    # Completed order revenue records
│       ├── ServiceRequest.php          # Central laundry order model
│       └── ServiceRequestStatusHistory.php # Audit trail for order status transitions
├── database/
│   ├── migrations/                     # Database schema definitions
│   └── seeders/                        # Pre-configured cloth types, pricing tiers, branches & add-ons
├── resources/
│   └── views/
│       ├── admin/                      # Admin dashboard & management interfaces
│       └── customer/                   # Customer dashboard & order placement views
└── routes/
    └── web.php                         # Routing for Customer & Admin authentication guards
```

---

## 🚀 Getting Started

### Prerequisites

Ensure you have the following installed on your system:
- **PHP** >= 8.2 with OpenSSL, PDO, Mbstring, and Tokenizer extensions
- **Composer** >= 2.0
- **Node.js** >= 18.x & **npm**

### Quick Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/JohnLester128/LaundrySpinzone.git
   cd LaundrySpinzone
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies**
   ```bash
   npm install
   ```

4. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run Database Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Start the Development Environment**
   Using the setup script (runs Artisan serve, Queue listener, and Vite concurrently):
   ```bash
   npm run dev
   # or
   composer run dev
   ```

   Or start Artisan serve manually:
   ```bash
   php artisan serve
   ```

   Access the application at `http://127.0.0.1:8000`.

---

## 🧪 Testing

To run the automated test suite with Pest PHP:

```bash
php artisan test
# or
composer test
```

---

## 📑 Default Seeded Data

After running `php artisan db:seed`, the system comes pre-populated with:
- **Branches**: Baybay Branch, Panay Branch
- **Cloth Types & Rates**:
  - Regular Clothes (1-6 kg: ₱95, 7-8 kg: ₱120, 9 kg: ₱145)
  - Beddings, Towels & Curtains (1-5 kg: ₱145)
  - Comforters (Single: ₱100, Double: ₱130, Queen: ₱150, King: ₱170)
- **Add-ons**: Detergent (₱10), Fabric Conditioner (₱10), Color Safe (₱10)

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

