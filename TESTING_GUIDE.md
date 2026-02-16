# Testing Guide - Restaurant Management System

## 🧪 Complete Testing Checklist

This guide will help you verify that all CRUD operations are working correctly.

## 🚀 Quick Start Testing

### 1. Initial Setup Verification

**Step 1: Access Dashboard**
```
URL: http://localhost/php-socket-activity/dashboard.php
Expected: Dashboard loads with statistics
```

**Verify:**
- [ ] Page loads without errors
- [ ] Statistics cards show numbers (may be 0 if no data)
- [ ] Table status section displays
- [ ] Navigation sidebar is visible
- [ ] All links are clickable

---

## 📋 Feature-by-Feature Testing

### ✅ 1. Categories Management

**Access:** Click "Categories" in sidebar or navigate to `categories.php`

#### Test CREATE
1. Click "Add Category" button
2. Modal opens
3. Enter category name: "Test Category"
4. Click "Add Category"
5. **Expected:** Success message, table refreshes, new category appears

#### Test READ
1. Observe DataTable with categories
2. Use search box: type "Test"
3. **Expected:** Table filters to show matching categories
4. Try pagination if more than 10 records
5. **Expected:** Navigation works

#### Test UPDATE
1. Click edit (pencil) icon on "Test Category"
2. Modal opens with pre-filled data
3. Change name to "Updated Category"
4. Click "Update Category"
5. **Expected:** Success message, table refreshes, name updated

#### Test STATUS TOGGLE
1. Click status toggle (toggle icon) on "Updated Category"
2. Confirm the action
3. **Expected:** Status changes from Active to Inactive (or vice versa)

#### Test DELETE
1. Click delete (trash) icon on "Updated Category"
2. Confirm deletion
3. **Expected:** Success message, category removed from table

**Validation Tests:**
- Try adding duplicate category name → Should show error
- Try deleting category with products → Should show error
- Try adding empty name → Should show validation error

---

### ✅ 2. Products Management

**Access:** Click "Products" in sidebar or navigate to `products.php`

#### Test CREATE
1. Click "Add Product" button
2. Select category: "Beverages"
3. Enter product name: "Test Drink"
4. Enter price: "5.99"
5. Click "Add Product"
6. **Expected:** Success message, product appears in table

#### Test READ
1. Observe DataTable with products
2. Search for "Test Drink"
3. **Expected:** Table filters correctly
4. Check price displays as "$5.99"

#### Test UPDATE
1. Click edit icon on "Test Drink"
2. Change price to "6.99"
3. Change category to "Main Course"
4. Click "Update Product"
5. **Expected:** Changes saved, table refreshes

#### Test STATUS TOGGLE
1. Click status toggle on "Test Drink"
2. **Expected:** Status changes

#### Test DELETE
1. Click delete icon on "Test Drink"
2. Confirm deletion
3. **Expected:** Product removed

**Validation Tests:**
- Try adding product with price 0 → Should show error
- Try adding without category → Should show error
- Try adding without name → Should show error

---

### ✅ 3. Tables Management

**Access:** Click "Tables" in sidebar or navigate to `tables.php`

#### Test CREATE
1. Click "Add Table" button
2. Enter table name: "Test Table"
3. Select capacity: "4 Persons"
4. Click "Add Table"
5. **Expected:** Success message, table appears

#### Test READ
1. Verify table appears in DataTable
2. Check capacity shows "4 Persons"
3. Search for "Test Table"
4. **Expected:** Filters correctly

#### Test UPDATE
1. Click edit icon on "Test Table"
2. Change capacity to "6 Persons"
3. Click "Update Table"
4. **Expected:** Capacity updated

#### Test STATUS TOGGLE
1. Click status toggle
2. **Expected:** Status changes

#### Test DELETE
1. Click delete icon
2. Confirm deletion
3. **Expected:** Table removed

**Dashboard Integration:**
1. Go back to dashboard
2. **Expected:** Table count updated
3. **Expected:** "Test Table" appears in live status (if enabled)

**Validation Tests:**
- Try adding duplicate table name → Should show error
- Try adding without name → Should show error

---

### ✅ 4. Taxes Management

**Access:** Click "Taxes" in sidebar or navigate to `taxes.php`

#### Test CREATE
1. Click "Add Tax" button
2. Enter tax name: "Test Tax"
3. Enter percentage: "7.5"
4. Click "Add Tax"
5. **Expected:** Success message, tax appears

#### Test READ
1. Verify tax shows as "7.50%"
2. Search for "Test Tax"
3. **Expected:** Filters correctly

#### Test UPDATE
1. Click edit icon
2. Change percentage to "8.0"
3. Click "Update Tax"
4. **Expected:** Shows "8.00%"

#### Test STATUS TOGGLE
1. Click status toggle
2. **Expected:** Status changes

#### Test DELETE
1. Click delete icon
2. Confirm deletion
3. **Expected:** Tax removed

**Validation Tests:**
- Try adding duplicate tax name → Should show error
- Try adding percentage > 100 → Should show error
- Try adding negative percentage → Should show error

---

### ✅ 5. Users Management

**Access:** Click "Users" in sidebar or navigate to `users.php`

#### Test CREATE
1. Click "Add User" button
2. Enter name: "Test User"
3. Enter email: "test@example.com"
4. Enter password: "password123"
5. Select type: "Waiter"
6. Click "Add User"
7. **Expected:** Success message, user appears

#### Test READ
1. Verify user appears with "Waiter" badge
2. Search for "Test User"
3. **Expected:** Filters correctly

#### Test UPDATE
1. Click edit icon
2. Change name to "Updated User"
3. Change type to "Cashier"
4. Leave password blank
5. Click "Update User"
6. **Expected:** Name and type updated, password unchanged

#### Test UPDATE PASSWORD
1. Click edit icon again
2. Enter new password: "newpassword123"
3. Click "Update User"
4. **Expected:** Password updated

#### Test STATUS TOGGLE
1. Click status toggle
2. **Expected:** Status changes

#### Test DELETE
1. Click delete icon
2. Confirm deletion
3. **Expected:** User removed

**Validation Tests:**
- Try adding duplicate email → Should show error
- Try adding without password (new user) → Should show error
- Try adding invalid email → Should show error

---

### ✅ 6. Orders Management

**Access:** Click "Orders" in sidebar or navigate to `orders.php`

#### Test CREATE ORDER
1. Click on any available table (blue card)
2. Modal opens
3. Select category: "Beverages"
4. Select product: "Coffee"
5. Select quantity: "2"
6. Click "Add Item"
7. **Expected:** 
   - Modal closes
   - Order details appear on right
   - Table turns pink (occupied)
   - Order total calculated

#### Test ADD MORE ITEMS
1. Click same table again (now pink)
2. Select category: "Main Course"
3. Select product: "Grilled Chicken"
4. Quantity: "1"
5. Click "Add Item"
6. **Expected:** 
   - Item added to order
   - Total recalculated
   - Tax applied

#### Test UPDATE QUANTITY
1. In order details, change quantity dropdown
2. Select different quantity
3. **Expected:** 
   - Total updates immediately
   - Tax recalculated

#### Test DELETE ITEM
1. Click trash icon on an item
2. Confirm removal
3. **Expected:** 
   - Item removed
   - Total recalculated

#### Test DELETE ALL ITEMS
1. Remove all items from order
2. **Expected:** 
   - Order deleted automatically
   - Table becomes available (blue)

**Dashboard Integration:**
1. Go to dashboard
2. **Expected:** 
   - Pending orders count updated
   - Table shows as occupied with order number
   - Click table → redirects to orders page

**Validation Tests:**
- Try adding item without selecting product → Should show error
- Verify tax calculation is correct
- Verify subtotal + tax = total

---

### ✅ 7. Billing Management

**Access:** Click "Billing" in sidebar or navigate to `billing.php`

#### Test READ BILLS
1. Observe DataTable with all orders
2. Verify pending orders show
3. Search for order number
4. **Expected:** Filters correctly

#### Test VIEW BILL
1. Click "View" button on any order
2. Modal opens with bill details
3. **Expected:** 
   - Shows all items
   - Shows subtotal, tax, total
   - Shows waiter name
   - Shows table and date

#### Test COMPLETE BILL
1. In bill modal, click "Complete & Print"
2. Confirm action
3. **Expected:** 
   - Success message
   - Print window opens
   - Order status changes to "Completed"
   - Table becomes available

#### Test PRINT
1. Print window should show:
   - Restaurant header
   - Order details
   - All items with prices
   - Totals
   - "Print Bill" button
2. Click "Print Bill"
3. **Expected:** Print dialog opens

#### Test DELETE ORDER
1. Click delete (trash) icon on an order
2. Confirm deletion
3. **Expected:** Order removed from system

**Dashboard Integration:**
1. Go to dashboard
2. **Expected:** 
   - Completed orders count updated
   - Pending orders count decreased
   - Table now available

---

## 🔄 Integration Testing

### Test Complete Order Flow

**Scenario:** Customer orders, eats, and pays

1. **Dashboard** → See available tables
2. **Orders** → Click Table 1
3. **Add Items:**
   - Coffee x2
   - Grilled Chicken x1
   - Caesar Salad x1
4. **Verify:** 
   - Order total calculated
   - Tax applied
   - Table shows occupied
5. **Dashboard** → Verify pending order count
6. **Billing** → View the order
7. **Complete** → Mark as paid
8. **Print** → Receipt prints
9. **Dashboard** → Verify:
   - Completed orders increased
   - Pending orders decreased
   - Table available again
   - Sales statistics updated

---

## 📊 Dashboard Testing

### Test All Statistics

1. **Sales Metrics:**
   - Create and complete orders
   - Check if today's sales updates
   - Wait until tomorrow, check yesterday's sales
   - Verify weekly and all-time totals

2. **Order Counts:**
   - Create orders → Pending count increases
   - Complete orders → Completed count increases
   - Delete orders → Counts decrease

3. **Table Status:**
   - Create order → Table becomes occupied
   - Complete order → Table becomes available
   - Verify color changes (blue/pink)
   - Check order numbers display

4. **Resource Counts:**
   - Add category → Count increases
   - Delete category → Count decreases
   - Repeat for all resources
   - Verify all counts are clickable

5. **Quick Actions:**
   - Click each quick action button
   - Verify redirects to correct page

6. **Auto-Refresh:**
   - Open dashboard in two browser windows
   - Create order in one window
   - Wait 10 seconds
   - **Expected:** Other window updates automatically

---

## 🔍 Search and Filter Testing

### Test DataTables Search

For each module:
1. Enter search term
2. Verify results filter correctly
3. Clear search
4. Verify all records return
5. Test partial matches
6. Test case-insensitive search

### Test Pagination

1. If more than 10 records:
   - Click "Next" button
   - Verify page changes
   - Click "Previous"
   - Verify returns to first page
2. Change "Show X entries" dropdown
3. Verify records per page changes

### Test Sorting

1. Click column headers
2. Verify sorting works
3. Click again for reverse sort

---

## 🎨 UI/UX Testing

### Test Responsive Design

1. **Desktop (1920x1080):**
   - Verify full layout
   - Check sidebar visible
   - Verify all columns show

2. **Laptop (1366x768):**
   - Verify layout adapts
   - Check readability

3. **Tablet (768x1024):**
   - Verify sidebar collapses
   - Check touch targets
   - Verify modals fit

4. **Mobile (375x667):**
   - Verify single column layout
   - Check navigation works
   - Verify forms usable

### Test Animations

1. Hover over cards → Should lift
2. Hover over buttons → Should change
3. Click buttons → Should show loading state
4. Success messages → Should auto-dismiss
5. Modals → Should fade in/out

### Test Accessibility

1. Tab through forms → Should focus correctly
2. Press Enter on buttons → Should submit
3. Press Escape in modals → Should close
4. Check color contrast → Should be readable

---

## 🐛 Error Testing

### Test Error Handling

1. **Database Errors:**
   - Stop MySQL service
   - Try to load page
   - **Expected:** Connection error message

2. **Validation Errors:**
   - Submit empty forms
   - **Expected:** Validation messages

3. **Duplicate Errors:**
   - Add duplicate names
   - **Expected:** Duplicate error message

4. **Dependency Errors:**
   - Delete category with products
   - **Expected:** Dependency error message

5. **Network Errors:**
   - Disable internet (for CDN resources)
   - **Expected:** Graceful degradation

---

## ✅ Final Verification Checklist

### All Features
- [ ] Categories: Full CRUD working
- [ ] Products: Full CRUD working
- [ ] Tables: Full CRUD working
- [ ] Taxes: Full CRUD working
- [ ] Users: Full CRUD working
- [ ] Orders: Full CRUD working
- [ ] Billing: Full CRUD working

### Dashboard
- [ ] Sales statistics accurate
- [ ] Order counts correct
- [ ] Table status live
- [ ] Resource counts accurate
- [ ] Quick actions work
- [ ] Auto-refresh working

### UI/UX
- [ ] Responsive on all devices
- [ ] Animations smooth
- [ ] Forms validate correctly
- [ ] Messages display properly
- [ ] Navigation works
- [ ] Icons display

### Security
- [ ] Passwords hashed
- [ ] SQL injection prevented
- [ ] XSS protection working
- [ ] Validation on server-side

### Performance
- [ ] Pages load quickly
- [ ] DataTables responsive
- [ ] No console errors
- [ ] Database queries optimized

---

## 📝 Test Results Template

```
Date: _______________
Tester: _______________

Categories Management:
- Create: [ ] Pass [ ] Fail
- Read:   [ ] Pass [ ] Fail
- Update: [ ] Pass [ ] Fail
- Delete: [ ] Pass [ ] Fail

Products Management:
- Create: [ ] Pass [ ] Fail
- Read:   [ ] Pass [ ] Fail
- Update: [ ] Pass [ ] Fail
- Delete: [ ] Pass [ ] Fail

Tables Management:
- Create: [ ] Pass [ ] Fail
- Read:   [ ] Pass [ ] Fail
- Update: [ ] Pass [ ] Fail
- Delete: [ ] Pass [ ] Fail

Taxes Management:
- Create: [ ] Pass [ ] Fail
- Read:   [ ] Pass [ ] Fail
- Update: [ ] Pass [ ] Fail
- Delete: [ ] Pass [ ] Fail

Users Management:
- Create: [ ] Pass [ ] Fail
- Read:   [ ] Pass [ ] Fail
- Update: [ ] Pass [ ] Fail
- Delete: [ ] Pass [ ] Fail

Orders Management:
- Create: [ ] Pass [ ] Fail
- Read:   [ ] Pass [ ] Fail
- Update: [ ] Pass [ ] Fail
- Delete: [ ] Pass [ ] Fail

Billing Management:
- Read:     [ ] Pass [ ] Fail
- Complete: [ ] Pass [ ] Fail
- Print:    [ ] Pass [ ] Fail
- Delete:   [ ] Pass [ ] Fail

Dashboard:
- Statistics: [ ] Pass [ ] Fail
- Live Status: [ ] Pass [ ] Fail
- Quick Actions: [ ] Pass [ ] Fail

Overall Status: [ ] All Tests Passed [ ] Some Failed

Notes:
_________________________________
_________________________________
_________________________________
```

---

## 🎯 Expected Results Summary

After completing all tests, you should have:

✅ All CRUD operations working
✅ Dashboard showing real data
✅ Live table status updating
✅ Orders calculating correctly
✅ Bills printing properly
✅ All validations working
✅ No console errors
✅ Responsive on all devices

---

**Status:** Ready for Testing
**Version:** 1.0
**Last Updated:** 2024
