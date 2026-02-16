# 🚀 QUICK START GUIDE

## Get Your Restaurant Management System Running in 5 Minutes!

---

## ⚡ Step 1: Start Your Servers (30 seconds)

### If using XAMPP:
1. Open XAMPP Control Panel
2. Click "Start" next to Apache
3. Click "Start" next to MySQL
4. Wait for both to turn GREEN

### If using WAMP:
1. Start WAMP
2. Wait for icon to turn GREEN
3. Left-click icon → MySQL → Service → Start Service
4. Left-click icon → Apache → Service → Start Service

---

## 📦 Step 2: Import Database (1 minute)

1. Open your browser
2. Go to: `http://localhost/phpmyadmin`
3. Click "Import" tab at the top
4. Click "Choose File"
5. Navigate to: `php-socket-activity/setup_database.sql`
6. Click "Go" button at the bottom
7. Wait for "Import has been successfully finished"

**✅ Done!** Database `rms_socket` is now created with sample data.

---

## 🧪 Step 3: Test Everything Works (30 seconds)

1. Open browser
2. Go to: `http://localhost/php-socket-activity/test_crud.php`
3. You should see:
   - ✅ Database Connection: SUCCESS
   - ✅ All tables showing green checkmarks
   - ✅ All pages showing green checkmarks
   - ✅ All APIs showing green checkmarks

**If you see RED ❌:**
- Check Apache is running
- Check MySQL is running
- Check database was imported correctly

---

## 🎯 Step 4: Access the System (10 seconds)

Go to: `http://localhost/php-socket-activity/dashboard.php`

You should see:
- Beautiful gradient dashboard
- Sales statistics (may be $0.00 if no orders yet)
- Table status cards
- System resource counts
- Quick action buttons

---

## 🎨 Step 5: Test CRUD Operations (3 minutes)

### Test Categories:

1. Click "Categories" in left sidebar
2. You should see:
   ```
   ┌─────────────────────────────────────────┐
   │ Category List      [➕ Add Category]   │
   │ ┌─────────────────────────────────────┐ │
   │ │ Beverages  │ Active │ ✏️ 🔄 🗑️    │ │
   │ │ Main Course│ Active │ ✏️ 🔄 🗑️    │ │
   │ └─────────────────────────────────────┘ │
   └─────────────────────────────────────────┘
   ```

3. **Test CREATE:**
   - Click "Add Category" button (top right)
   - Modal opens
   - Type "Test Category"
   - Click "Add Category"
   - ✅ Success message appears
   - ✅ Table refreshes
   - ✅ New category appears

4. **Test UPDATE:**
   - Click Edit button (✏️ pencil icon)
   - Modal opens with "Test Category"
   - Change to "Updated Category"
   - Click "Update Category"
   - ✅ Name changes in table

5. **Test DELETE:**
   - Click Delete button (🗑️ trash icon)
   - Confirm deletion
   - ✅ Category removed

### Test Products:

1. Click "Products" in sidebar
2. Click "Add Product"
3. Select category: "Beverages"
4. Name: "Test Drink"
5. Price: "5.99"
6. Click "Add Product"
7. ✅ Product appears in table
8. Test Edit and Delete same way

### Test Tables:

1. Click "Tables" in sidebar
2. Click "Add Table"
3. Name: "Test Table"
4. Capacity: "4 Persons"
5. Click "Add Table"
6. ✅ Table appears
7. Go back to Dashboard
8. ✅ "Test Table" appears in live status

### Test Orders:

1. Click "Orders" in sidebar
2. Click any BLUE table card (available)
3. Modal opens
4. Select Category: "Beverages"
5. Select Product: "Coffee"
6. Quantity: "2"
7. Click "Add Item"
8. ✅ Order appears on right
9. ✅ Table turns PINK (occupied)
10. ✅ Total calculated with tax

### Test Billing:

1. Click "Billing" in sidebar
2. Find your order
3. Click "View" button
4. ✅ Bill details show
5. Click "Complete & Print"
6. ✅ Print window opens
7. ✅ Order marked as completed

---

## 🎉 SUCCESS!

If all the above worked, your system is 100% functional!

---

## 🐛 Something Not Working?

### Problem: Can't see buttons

**Solution:**
1. Press `F12` to open browser console
2. Look for RED errors
3. Press `Ctrl + F5` to hard refresh
4. Check internet connection (for CDN resources)

### Problem: Database connection failed

**Solution:**
1. Check MySQL is running (green in XAMPP/WAMP)
2. Open `config.php`
3. Verify:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'rms_socket');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```
4. Re-import database

### Problem: Buttons visible but not working

**Solution:**
1. Open browser console (F12)
2. Click a button
3. Look for error messages
4. Common fixes:
   - Clear cache (Ctrl + F5)
   - Check API files exist in `api/` folder
   - Verify database tables exist

### Problem: Modal not opening

**Solution:**
1. Check browser console for errors
2. Verify jQuery loaded: Type `jQuery` in console
3. Verify Bootstrap loaded: Type `bootstrap` in console
4. Clear cache and refresh

---

## 📚 Next Steps

### Explore All Features:

1. **Dashboard** - View real-time statistics
2. **Categories** - Organize your menu
3. **Products** - Add menu items
4. **Tables** - Set up restaurant tables
5. **Taxes** - Configure tax rates
6. **Users** - Manage staff accounts
7. **Orders** - Take customer orders
8. **Billing** - Process payments

### Customize:

1. **Change Colors:**
   - Edit `header.php`
   - Find CSS variables
   - Change gradient colors

2. **Add More Data:**
   - Add more categories
   - Add more products
   - Add more tables
   - Create test orders

3. **Test Workflows:**
   - Create order → Add items → Complete → Print
   - Add product → Assign category → Use in order
   - Create user → Test different roles

---

## 🎯 Key URLs to Bookmark

```
Dashboard:   http://localhost/php-socket-activity/dashboard.php
Test Page:   http://localhost/php-socket-activity/test_crud.php
Categories:  http://localhost/php-socket-activity/categories.php
Products:    http://localhost/php-socket-activity/products.php
Tables:      http://localhost/php-socket-activity/tables.php
Taxes:       http://localhost/php-socket-activity/taxes.php
Users:       http://localhost/php-socket-activity/users.php
Orders:      http://localhost/php-socket-activity/orders.php
Billing:     http://localhost/php-socket-activity/billing.php
```

---

## ✅ Verification Checklist

- [ ] Apache running
- [ ] MySQL running
- [ ] Database imported
- [ ] Test page shows all green
- [ ] Dashboard loads
- [ ] Can add category
- [ ] Can edit category
- [ ] Can delete category
- [ ] Can add product
- [ ] Can add table
- [ ] Can create order
- [ ] Can complete bill
- [ ] All buttons visible
- [ ] All modals working

---

## 🎓 Understanding the System

### File Structure:
```
php-socket-activity/
├── dashboard.php          ← Start here
├── categories.php         ← CRUD for categories
├── products.php           ← CRUD for products
├── tables.php             ← CRUD for tables
├── taxes.php              ← CRUD for taxes
├── users.php              ← CRUD for users
├── orders.php             ← CRUD for orders
├── billing.php            ← CRUD for billing
├── api/                   ← All CRUD operations
│   ├── categories_api.php
│   ├── products_api.php
│   ├── tables_api.php
│   ├── taxes_api.php
│   ├── users_api.php
│   ├── orders_api.php
│   └── billing_api.php
├── config.php             ← Database connection
├── header.php             ← Navigation & styles
├── footer.php             ← Scripts
└── setup_database.sql     ← Database schema
```

### How It Works:
1. User clicks button
2. JavaScript sends AJAX request
3. API file processes request
4. Database updated
5. Response sent back
6. Table refreshes
7. Success message shown

---

## 🚀 You're Ready!

Your Restaurant Management System is now fully operational with complete CRUD functionality!

**Need Help?**
- Check `WHERE_ARE_CRUD_BUTTONS.md` for button locations
- Check `TESTING_GUIDE.md` for detailed testing
- Check `CRUD_VERIFICATION.md` for feature documentation
- Run `test_crud.php` for diagnostics

**Happy Coding! 🎉**
