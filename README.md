# UniShop

A Laravel-based e-commerce platform with product management, cart, orders, discounts, and role-based access control.

## Requirements

- PHP 8.3+
- Composer
- MySQL (or compatible database)

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd unishop
composer install
cp .env.example .env
php artisan key:generate
```

### 5. Configure the database

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unishop
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run migrations

```bash
php artisan migrate
```
Or start only the Laravel server:

```bash
php artisan serve
```
