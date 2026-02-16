# Complete Installation Guide - Restaurant Management System

## Quick Start

### Step 1: Import Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "New" to create a database
3. Name it: `rms_socket`
4. Click "Import" tab
5. Choose file: `setup_database.sql`
6. Click "Go"

### Step 2: Update Configuration (if needed)

Open `config.php` and verify these settings:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'rms_socket');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Step 3: Access the System

Open your browser and go to:
```
http://localhost/php-socket-activity/login.php
```

**Login with:**
- Email: `admin@restaurant.com`
- Password: `admin123`

## Complete Feature List

### ✅ All Pages Working

1. **Login** (`login.php`) - User authentication
2. **Dashboard** (`dashboard.php`) - Sales statistics & table status
3. **Categories** (`categories.php`) - Manage product categories
4. **Products** (`products.php`) - Manage menu items
5. **Tables** (`tables.php`) - Manage restaurant tables
6. **Taxes** (`taxes.php`) - Configure tax rates
7. **Users** (`users.php`) - Manage staff accounts
8. **Orders** (`orders.php`) - Take and manage orders
9. **Billing** (`billing.php`) - Process payments and print bills

### User Roles

#### Master (Admin)
- Access: ALL features
- Can manage: Categories, Products, Tables, Taxes, Users
- Can view: Sales statistics, All orders, All bills

#### Waiter
- Access: Orders, Dashboard
- Can: Take orders, View table status
- Cannot: Manage products, users, or process billing

#### Cashier
- Access: Billing, Dashboard
- Can: Process payments, Print bills
- Cannot: Take orders or manage products

## Sample Data Included

### Categories (5)
- Beverages
- Main Course
- Desserts
- Appetizers
- Salads

### Products (16)
- Coffee ($3.50)
- Tea ($2.50)
- Fresh Juice ($4.00)
- Soft Drink ($2.00)
- Grilled Chicken ($12.99)
- Beef Steak ($18.99)
- Pasta Carbonara ($10.99)
- Fish and Chips ($14.99)
- Chocolate Cake ($5.99)
- Ice Cream ($4.50)
- Tiramisu ($6.99)
- French Fries ($3.99)
- Chicken Wings ($7.99)
- Garlic Bread ($4.50)
- Caesar Salad ($6.99)
- Greek Salad ($7.50)

### Tables (6)
- Table 1 (4 persons)
- Table 2 (2 persons)
- Table 3 (6 persons)
- Table 4 (4 persons)
- Table 5 (8 persons)
- Table 6 (2 persons)

### Taxes (2)
- VAT: 10%
- Service Tax: 5%

## How to Use

### Taking an Order (Waiter)

1. Login as waiter
2. Go to "Orders" page
3. Click on an available table
4. Select category
5. Select product
6. Choose quantity
7. Click "Add Item"
8. Repeat for more items
9. Order is automatically saved

### Processing Payment (Cashier)

1. Login as cashier
2. Go to "Billing" page
3. Click "View" on pending order
4. Review bill details
5. Click "Complete & Print"
6. Bill is marked as paid and printed

### Managing Products (Master)

1. Login as admin
2. Go to "Products" page
3. Click "Add Product"
4. Fill in details
5. Click "Add Product"
6. Product appears in list

## Troubleshooting

### "Database Connection Failed"
- Make sure MySQL is running
- Check database name is `rms_socket`
- Verify credentials in `config.php`

### "404 Not Found"
- Check you're accessing: `http://localhost/php-socket-activity/`
- Verify files are in correct directory
- Check Apache is running

### "Unauthorized" Error
- Make sure you're logged in
- Check your user role has permission
- Try logging out and back in

### Tables Not Showing
- Refresh the page
- Check database has sample data
- Verify table_table has records

## Creating Additional Users

1. Login as Master/Admin
2. Go to "Users" page
3. Click "Add User"
4. Fill in:
   - Name
   - Email
   - Password
   - User Type (Waiter/Cashier/Master)
5. Click "Add User"

## Changing Admin Password

1. Login as admin
2. Go to "Users" page
3. Click edit (pencil icon) on admin user
4. Enter new password
5. Click "Update User"

## Database Backup

To backup your data:
```bash
mysqldump -u root -p rms_socket > backup.sql
```

To restore:
```bash
mysql -u root -p rms_socket < backup.sql
```

## System Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite
- 50MB disk space
- Modern web browser

## Browser Compatibility

- ✅ Chrome (Recommended)
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ✅ Opera

## Security Recommendations

1. Change default admin password immediately
2. Use strong passwords for all users
3. Don't use 'root' with no password in production
4. Enable HTTPS in production
5. Regular database backups

## Support

If you encounter issues:
1. Check this guide first
2. Verify all requirements are met
3. Check browser console for errors
4. Review PHP error logs

## Next Steps

After installation:
1. Change admin password
2. Add your own products
3. Configure taxes for your region
4. Create user accounts for staff
5. Start taking orders!

---

**Congratulations!** Your restaurant management system is ready to use. 🎉
