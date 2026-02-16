# URL Reference Guide

## All Working URLs

### Public Pages
- **Login**: `http://localhost/php-socket-activity/login.php`
- **Logout**: `http://localhost/php-socket-activity/logout.php`

### Main Pages (Requires Login)
- **Dashboard**: `http://localhost/php-socket-activity/dashboard.php`
- **Categories**: `http://localhost/php-socket-activity/categories.php`
- **Products**: `http://localhost/php-socket-activity/products.php`
- **Tables**: `http://localhost/php-socket-activity/tables.php`
- **Taxes**: `http://localhost/php-socket-activity/taxes.php`
- **Users**: `http://localhost/php-socket-activity/users.php`
- **Orders**: `http://localhost/php-socket-activity/orders.php`
- **Billing**: `http://localhost/php-socket-activity/billing.php`

### Utility Pages
- **Print Bill**: `http://localhost/php-socket-activity/print_bill.php?order_id=1`

### API Endpoints (AJAX Only)
- `api/categories_api.php`
- `api/products_api.php`
- `api/tables_api.php`
- `api/taxes_api.php`
- `api/users_api.php`
- `api/orders_api.php`
- `api/billing_api.php`

## Access Control

### Master (Admin) Only
- Categories
- Products
- Tables
- Taxes
- Users

### Waiter Access
- Dashboard
- Orders

### Cashier Access
- Dashboard
- Billing

### All Roles
- Dashboard (limited view for non-admin)
- Logout

## Default Credentials

**Master Account:**
- Email: `admin@restaurant.com`
- Password: `admin123`

## Quick Navigation

After login, use the sidebar menu:
- 🏠 Dashboard
- 📦 Categories (Master only)
- 📦 Products (Master only)
- 🪑 Tables (Master only)
- 💰 Taxes (Master only)
- 👥 Users (Master only)
- 🛒 Orders (Waiter/Master)
- 🧾 Billing (Cashier/Master)
- 🚪 Logout

## Testing URLs

To test the system, visit these URLs in order:

1. **Login**: `http://localhost/php-socket-activity/login.php`
2. **Dashboard**: `http://localhost/php-socket-activity/dashboard.php`
3. **View Products**: `http://localhost/php-socket-activity/products.php`
4. **Take Order**: `http://localhost/php-socket-activity/orders.php`
5. **Process Bill**: `http://localhost/php-socket-activity/billing.php`

## Common Issues

### 404 Not Found
- ✅ Correct: `http://localhost/php-socket-activity/billing.php`
- ❌ Wrong: `http://localhost/billing.php`
- ❌ Wrong: `http://localhost/php-socket-activity/bill.php`

### Unauthorized Access
- Make sure you're logged in
- Check your user role has permission
- Some pages are role-restricted

### Blank Page
- Check PHP errors in Apache error log
- Verify database connection in config.php
- Ensure database is imported

## File Structure Reference

```
php-socket-activity/
├── login.php              ← Login page
├── logout.php             ← Logout handler
├── dashboard.php          ← Main dashboard
├── categories.php         ← Category management
├── products.php           ← Product management
├── tables.php             ← Table management
├── taxes.php              ← Tax management
├── users.php              ← User management
├── orders.php             ← Order taking
├── billing.php            ← Billing & payment
├── print_bill.php         ← Print bill
├── config.php             ← Configuration
├── header.php             ← Header template
├── footer.php             ← Footer template
├── setup_database.sql     ← Database schema
└── api/                   ← API endpoints
    ├── categories_api.php
    ├── products_api.php
    ├── tables_api.php
    ├── taxes_api.php
    ├── users_api.php
    ├── orders_api.php
    └── billing_api.php
```

## Direct Database Access

**phpMyAdmin**: `http://localhost/phpmyadmin`
- Database: `rms_socket`
- Username: `root`
- Password: (blank)

## Port Information

- **Apache**: Port 80
- **MySQL**: Port 3306
- **Application**: `http://localhost/php-socket-activity/`

---

**Note**: All URLs assume XAMPP is installed at default location and Apache is running on port 80.
