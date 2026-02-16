# Merge Plan: Cafeteria Management into PHP Socket Activity

## Overview
Merging cafeteria-management system into php-socket-activity as the main project.

## Structure
```
php-socket-activity/ (MAIN)
├── client.php (Enhanced with cafeteria features)
├── server.php (Enhanced with cafeteria operations)
├── database.php (Updated with full schema)
├── index.php (Redirect to client.php)
├── menu.sql (Complete database schema)
├── bootstrap/ (UI framework)
├── cafeteria/ (New folder for cafeteria-specific modules)
│   ├── billing.php
│   ├── category.php
│   ├── product.php
│   ├── order.php
│   ├── table.php
│   ├── tax.php
│   ├── user.php
│   ├── dashboard.php
│   ├── setting.php
│   └── actions/ (API endpoints)
├── class/ (PDF generation and utilities)
├── css/ (Styles from cafeteria)
├── js/ (Scripts from cafeteria)
├── images/ (Assets)
└── vendor/ (Dependencies)
```

## Features Integrated
1. Menu Management (existing socket-based)
2. Billing System
3. Category Management
4. Product Management
5. Order Management
6. Table Management
7. Tax Management
8. User Management
9. Dashboard with Analytics
10. PDF Report Generation

## Database Schema
- Unified database: `food_menu` (renamed from `rms`)
- Tables from both systems merged
- Socket operations extended for all modules

## Key Changes
1. Keep socket architecture for real-time updates
2. Add cafeteria management modules
3. Unified authentication system
4. Enhanced UI with Bootstrap
5. PDF generation for bills/reports
