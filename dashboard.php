<?php
require_once 'config.php';
require_once 'header.php';

// Get sales statistics
$todaySales = 0;
$yesterdaySales = 0;
$weekSales = 0;
$totalSales = 0;
$pendingOrders = 0;
$completedOrders = 0;

if (isMasterUser()) {
    // Today's sales
    $stmt = $pdo->prepare("SELECT SUM(order_net_amount) as total FROM order_table WHERE order_date = CURDATE()");
    $stmt->execute();
    $result = $stmt->fetch();
    $todaySales = $result['total'] ?? 0;

    // Yesterday's sales
    $stmt = $pdo->prepare("SELECT SUM(order_net_amount) as total FROM order_table WHERE order_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
    $stmt->execute();
    $result = $stmt->fetch();
    $yesterdaySales = $result['total'] ?? 0;

    // Last 7 days sales
    $stmt = $pdo->prepare("SELECT SUM(order_net_amount) as total FROM order_table WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
    $stmt->execute();
    $result = $stmt->fetch();
    $weekSales = $result['total'] ?? 0;

    // Total sales
    $stmt = $pdo->prepare("SELECT SUM(order_net_amount) as total FROM order_table");
    $stmt->execute();
    $result = $stmt->fetch();
    $totalSales = $result['total'] ?? 0;
    
    // Pending orders count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM order_table WHERE order_status = 'Pending'");
    $stmt->execute();
    $pendingOrders = $stmt->fetch()['count'] ?? 0;
    
    // Completed orders count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM order_table WHERE order_status = 'Completed'");
    $stmt->execute();
    $completedOrders = $stmt->fetch()['count'] ?? 0;
}

// Get counts for all features
$stmt = $pdo->query("SELECT COUNT(*) as count FROM product_category_table WHERE category_status = 'Enable'");
$totalCategories = $stmt->fetch()['count'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as count FROM product_table WHERE product_status = 'Enable'");
$totalProducts = $stmt->fetch()['count'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as count FROM table_table WHERE table_status = 'Enable'");
$totalTables = $stmt->fetch()['count'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as count FROM user_table WHERE user_status = 'Enable'");
$totalUsers = $stmt->fetch()['count'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as count FROM tax_table WHERE tax_status = 'Enable'");
$totalTaxes = $stmt->fetch()['count'] ?? 0;

// Get table status
$stmt = $pdo->query("
    SELECT t.*, 
           o.order_id, 
           o.order_number,
           o.order_status
    FROM table_table t
    LEFT JOIN order_table o ON t.table_id = o.table_id AND o.order_status = 'Pending'
    WHERE t.table_status = 'Enable'
    ORDER BY t.table_id ASC
");
$tables = $stmt->fetchAll();

// Calculate occupied vs available tables
$occupiedTables = 0;
$availableTables = 0;
foreach ($tables as $table) {
    if ($table['order_id']) {
        $occupiedTables++;
    } else {
        $availableTables++;
    }
}
?>

<h1 class="page-title">
    <i class="bi bi-speedometer2 me-2"></i>Dashboard
</h1>

<?php if (isMasterUser()): ?>
<!-- Sales Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Today's Sales</h6>
                    <h3>$<?= number_format($todaySales, 2) ?></h3>
                </div>
                <i class="bi bi-cash-stack stat-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Yesterday's Sales</h6>
                    <h3>$<?= number_format($yesterdaySales, 2) ?></h3>
                </div>
                <i class="bi bi-calendar-check stat-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Last 7 Days</h6>
                    <h3>$<?= number_format($weekSales, 2) ?></h3>
                </div>
                <i class="bi bi-graph-up stat-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>All Time Sales</h6>
                    <h3>$<?= number_format($totalSales, 2) ?></h3>
                </div>
                <i class="bi bi-trophy stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Order Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Pending Orders</h6>
                    <h3><?= $pendingOrders ?></h3>
                </div>
                <i class="bi bi-hourglass-split stat-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Completed Orders</h6>
                    <h3><?= $completedOrders ?></h3>
                </div>
                <i class="bi bi-check-circle stat-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Occupied Tables</h6>
                    <h3><?= $occupiedTables ?> / <?= $totalTables ?></h3>
                </div>
                <i class="bi bi-table stat-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Available Tables</h6>
                    <h3><?= $availableTables ?></h3>
                </div>
                <i class="bi bi-check2-square stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- System Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="categories.php" style="text-decoration: none;">
            <div class="stat-card">
                <div class="text-center">
                    <i class="bi bi-grid-3x3-gap" style="font-size: 2rem; opacity: 0.5;"></i>
                    <h3 class="mt-2"><?= $totalCategories ?></h3>
                    <h6>Categories</h6>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="products.php" style="text-decoration: none;">
            <div class="stat-card">
                <div class="text-center">
                    <i class="bi bi-box-seam" style="font-size: 2rem; opacity: 0.5;"></i>
                    <h3 class="mt-2"><?= $totalProducts ?></h3>
                    <h6>Products</h6>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="tables.php" style="text-decoration: none;">
            <div class="stat-card">
                <div class="text-center">
                    <i class="bi bi-table" style="font-size: 2rem; opacity: 0.5;"></i>
                    <h3 class="mt-2"><?= $totalTables ?></h3>
                    <h6>Tables</h6>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="taxes.php" style="text-decoration: none;">
            <div class="stat-card">
                <div class="text-center">
                    <i class="bi bi-percent" style="font-size: 2rem; opacity: 0.5;"></i>
                    <h3 class="mt-2"><?= $totalTaxes ?></h3>
                    <h6>Taxes</h6>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="users.php" style="text-decoration: none;">
            <div class="stat-card">
                <div class="text-center">
                    <i class="bi bi-people" style="font-size: 2rem; opacity: 0.5;"></i>
                    <h3 class="mt-2"><?= $totalUsers ?></h3>
                    <h6>Users</h6>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="orders.php" style="text-decoration: none;">
            <div class="stat-card">
                <div class="text-center">
                    <i class="bi bi-cart-check" style="font-size: 2rem; opacity: 0.5;"></i>
                    <h3 class="mt-2"><?= $pendingOrders ?></h3>
                    <h6>Active Orders</h6>
                </div>
            </div>
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Live Table Status -->
<div class="glass-card">
    <div class="card-header-custom">
        <h5><i class="bi bi-table me-2"></i>Live Table Status</h5>
        <div>
            <button class="btn btn-gradient btn-sm me-2" onclick="window.location.href='orders.php'">
                <i class="bi bi-cart-plus me-1"></i>New Order
            </button>
            <button class="btn btn-gradient btn-sm" onclick="refreshTables()">
                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
            </button>
        </div>
    </div>
    
    <div class="row" id="table-status">
        <?php if (count($tables) > 0): ?>
            <?php foreach ($tables as $table): ?>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class="table-card" style="
                        background: <?= $table['order_id'] ? 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)' : 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)' ?>;
                        border-radius: 15px;
                        padding: 1.5rem;
                        text-align: center;
                        color: white;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                    " onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location.href='orders.php'">
                        <i class="bi bi-table" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                        <h5 style="margin: 0.5rem 0; font-weight: 600;"><?= htmlspecialchars($table['table_name']) ?></h5>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">
                            <i class="bi bi-people me-1"></i><?= $table['table_capacity'] ?> Persons
                        </p>
                        <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid rgba(255,255,255,0.3);">
                            <small style="font-weight: 600;">
                                <?= $table['order_id'] ? 'Occupied' : 'Available' ?>
                            </small>
                            <?php if ($table['order_id']): ?>
                                <br><small style="font-size: 0.75rem; opacity: 0.8;"><?= htmlspecialchars($table['order_number']) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center text-white py-5">
                    <i class="bi bi-table display-1 mb-3" style="opacity: 0.3;"></i>
                    <p>No tables configured yet. <a href="tables.php" class="text-white" style="text-decoration: underline;">Add tables</a> to get started.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (isMasterUser()): ?>
<!-- Quick Actions -->
<div class="glass-card mt-4">
    <div class="card-header-custom">
        <h5><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h5>
    </div>
    
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="categories.php" class="btn btn-gradient w-100 py-3" style="border-radius: 15px;">
                <i class="bi bi-grid-3x3-gap d-block mb-2" style="font-size: 2rem;"></i>
                Manage Categories
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="products.php" class="btn btn-gradient w-100 py-3" style="border-radius: 15px;">
                <i class="bi bi-box-seam d-block mb-2" style="font-size: 2rem;"></i>
                Manage Products
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="orders.php" class="btn btn-gradient w-100 py-3" style="border-radius: 15px;">
                <i class="bi bi-cart-plus d-block mb-2" style="font-size: 2rem;"></i>
                Create Order
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="billing.php" class="btn btn-gradient w-100 py-3" style="border-radius: 15px;">
                <i class="bi bi-receipt d-block mb-2" style="font-size: 2rem;"></i>
                View Bills
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>

<script>
function refreshTables() {
    location.reload();
}

// Auto-refresh every 10 seconds
setInterval(refreshTables, 10000);
</script>

</body>
</html>
