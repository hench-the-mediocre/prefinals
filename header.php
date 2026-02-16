<?php
// No authentication required - set default user info
$currentUser = [
    'user_name' => 'Admin',
    'user_type' => 'Master',
    'user_email' => 'admin@restaurant.com'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --sidebar-bg: rgba(102, 126, 234, 0.95);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"><animate attributeName="cy" values="20;80;20" dur="3s" repeatCount="indefinite"/></circle></svg>') repeat;
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px);
            padding: 1.5rem;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            text-align: center;
            padding: 1rem 0 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 1.5rem;
        }

        .sidebar-brand h3 {
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }

        .sidebar-brand i {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(5px);
        }

        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .main-content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            color: white;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-weight: 600;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-header-custom h5 {
            color: white;
            margin: 0;
            font-weight: 600;
        }

        .btn-gradient {
            background: var(--secondary-gradient);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            color: white;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 1.5rem;
            overflow-x: auto;
        }

        .table {
            margin: 0;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
        }

        .page-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 1.5rem;
            color: white;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.2);
        }

        .stat-card h6 {
            font-size: 0.875rem;
            text-transform: uppercase;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-cup-hot-fill"></i>
            <h3>Restaurant</h3>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <?php if (isMasterUser()): ?>
            <li class="nav-item">
                <a class="nav-link" href="categories.php">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="products.php">
                    <i class="bi bi-box-seam"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tables.php">
                    <i class="bi bi-table"></i>
                    <span>Tables</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="taxes.php">
                    <i class="bi bi-percent"></i>
                    <span>Taxes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="users.php">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (isWaiterUser() || isMasterUser()): ?>
            <li class="nav-item">
                <a class="nav-link" href="orders.php">
                    <i class="bi bi-cart-check"></i>
                    <span>Orders</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (isCashierUser() || isMasterUser()): ?>
            <li class="nav-item">
                <a class="nav-link" href="billing.php">
                    <i class="bi bi-receipt"></i>
                    <span>Billing</span>
                </a>
            </li>
            <?php endif; ?>
            

        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h5 class="text-white mb-0">
                <i class="bi bi-house-door me-2"></i>
                Restaurant Management System
            </h5>
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($currentUser['user_name'], 0, 1)) ?>
                </div>
                <div>
                    <div style="font-weight: 600;"><?= htmlspecialchars($currentUser['user_name']) ?></div>
                    <small style="opacity: 0.8;"><?= htmlspecialchars($currentUser['user_type']) ?></small>
                </div>
            </div>
        </div>

        <!-- Page Content -->
