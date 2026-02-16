<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Test Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { padding: 20px; background: #f8f9fa; }
        .test-section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-result { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .test-pass { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .test-fail { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .feature-link { display: block; padding: 15px; margin: 10px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 10px; text-align: center; font-weight: 600; transition: transform 0.3s; }
        .feature-link:hover { transform: translateY(-3px); color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4"><i class="bi bi-check2-circle me-2"></i>CRUD Functionality Test</h1>
        
        <div class="test-section">
            <h3><i class="bi bi-info-circle me-2"></i>System Status</h3>
            <?php
            require_once 'config.php';
            
            // Test database connection
            try {
                $stmt = $pdo->query("SELECT 1");
                echo '<div class="test-result test-pass"><i class="bi bi-check-circle me-2"></i>Database Connection: SUCCESS</div>';
            } catch (Exception $e) {
                echo '<div class="test-result test-fail"><i class="bi bi-x-circle me-2"></i>Database Connection: FAILED - ' . $e->getMessage() . '</div>';
            }
            
            // Test each table exists
            $tables = [
                'user_table' => 'Users',
                'product_category_table' => 'Categories',
                'product_table' => 'Products',
                'table_table' => 'Tables',
                'tax_table' => 'Taxes',
                'order_table' => 'Orders',
                'order_item_table' => 'Order Items'
            ];
            
            foreach ($tables as $table => $name) {
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                    $count = $stmt->fetch()['count'];
                    echo '<div class="test-result test-pass"><i class="bi bi-check-circle me-2"></i>' . $name . ' Table: EXISTS (' . $count . ' records)</div>';
                } catch (Exception $e) {
                    echo '<div class="test-result test-fail"><i class="bi bi-x-circle me-2"></i>' . $name . ' Table: NOT FOUND</div>';
                }
            }
            ?>
        </div>
        
        <div class="test-section">
            <h3><i class="bi bi-file-code me-2"></i>CRUD Pages Status</h3>
            <?php
            $pages = [
                'dashboard.php' => 'Dashboard',
                'categories.php' => 'Categories Management',
                'products.php' => 'Products Management',
                'tables.php' => 'Tables Management',
                'taxes.php' => 'Taxes Management',
                'users.php' => 'Users Management',
                'orders.php' => 'Orders Management',
                'billing.php' => 'Billing Management'
            ];
            
            foreach ($pages as $file => $name) {
                if (file_exists($file)) {
                    $content = file_get_contents($file);
                    
                    // Check for CRUD elements
                    $hasAdd = (strpos($content, 'id="add-') !== false || strpos($content, 'Add') !== false);
                    $hasEdit = (strpos($content, 'edit-btn') !== false);
                    $hasDelete = (strpos($content, 'delete-btn') !== false);
                    $hasModal = (strpos($content, 'modal fade') !== false);
                    $hasDataTable = (strpos($content, 'DataTable') !== false || strpos($content, 'dataTable') !== false);
                    
                    $status = ($hasAdd && ($hasEdit || $hasDelete)) ? 'test-pass' : 'test-fail';
                    $icon = ($hasAdd && ($hasEdit || $hasDelete)) ? 'check-circle' : 'exclamation-triangle';
                    
                    echo '<div class="test-result ' . $status . '">';
                    echo '<i class="bi bi-' . $icon . ' me-2"></i><strong>' . $name . '</strong><br>';
                    echo '<small>';
                    echo ($hasAdd ? '✓ Add Button ' : '✗ Add Button ');
                    echo ($hasEdit ? '✓ Edit Button ' : '✗ Edit Button ');
                    echo ($hasDelete ? '✓ Delete Button ' : '✗ Delete Button ');
                    echo ($hasModal ? '✓ Modal ' : '✗ Modal ');
                    echo ($hasDataTable ? '✓ DataTable' : '✗ DataTable');
                    echo '</small>';
                    echo '</div>';
                } else {
                    echo '<div class="test-result test-fail"><i class="bi bi-x-circle me-2"></i>' . $name . ': FILE NOT FOUND</div>';
                }
            }
            ?>
        </div>
        
        <div class="test-section">
            <h3><i class="bi bi-cloud-arrow-up me-2"></i>API Endpoints Status</h3>
            <?php
            $apis = [
                'api/categories_api.php' => 'Categories API',
                'api/products_api.php' => 'Products API',
                'api/tables_api.php' => 'Tables API',
                'api/taxes_api.php' => 'Taxes API',
                'api/users_api.php' => 'Users API',
                'api/orders_api.php' => 'Orders API',
                'api/billing_api.php' => 'Billing API'
            ];
            
            foreach ($apis as $file => $name) {
                if (file_exists($file)) {
                    $content = file_get_contents($file);
                    
                    // Check for CRUD actions
                    $hasFetch = (strpos($content, "case 'fetch':") !== false);
                    $hasAdd = (strpos($content, "case 'add':") !== false);
                    $hasEdit = (strpos($content, "case 'edit':") !== false);
                    $hasDelete = (strpos($content, "case 'delete':") !== false);
                    
                    $status = ($hasFetch && $hasAdd && $hasEdit && $hasDelete) ? 'test-pass' : 'test-fail';
                    $icon = ($hasFetch && $hasAdd && $hasEdit && $hasDelete) ? 'check-circle' : 'exclamation-triangle';
                    
                    echo '<div class="test-result ' . $status . '">';
                    echo '<i class="bi bi-' . $icon . ' me-2"></i><strong>' . $name . '</strong><br>';
                    echo '<small>';
                    echo ($hasFetch ? '✓ Fetch ' : '✗ Fetch ');
                    echo ($hasAdd ? '✓ Add ' : '✗ Add ');
                    echo ($hasEdit ? '✓ Edit ' : '✗ Edit ');
                    echo ($hasDelete ? '✓ Delete' : '✗ Delete');
                    echo '</small>';
                    echo '</div>';
                } else {
                    echo '<div class="test-result test-fail"><i class="bi bi-x-circle me-2"></i>' . $name . ': FILE NOT FOUND</div>';
                }
            }
            ?>
        </div>
        
        <div class="test-section">
            <h3><i class="bi bi-link-45deg me-2"></i>Quick Access Links</h3>
            <div class="row">
                <div class="col-md-6">
                    <a href="dashboard.php" class="feature-link">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="categories.php" class="feature-link">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Categories
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="products.php" class="feature-link">
                        <i class="bi bi-box-seam me-2"></i>Products
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="tables.php" class="feature-link">
                        <i class="bi bi-table me-2"></i>Tables
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="taxes.php" class="feature-link">
                        <i class="bi bi-percent me-2"></i>Taxes
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="users.php" class="feature-link">
                        <i class="bi bi-people me-2"></i>Users
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="orders.php" class="feature-link">
                        <i class="bi bi-cart-check me-2"></i>Orders
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="billing.php" class="feature-link">
                        <i class="bi bi-receipt me-2"></i>Billing
                    </a>
                </div>
            </div>
        </div>
        
        <div class="test-section">
            <h3><i class="bi bi-clipboard-check me-2"></i>Testing Instructions</h3>
            <ol>
                <li>Click on each feature link above</li>
                <li>Look for the <strong>"Add"</strong> button at the top right</li>
                <li>Click it to open the modal form</li>
                <li>Fill in the form and submit</li>
                <li>Look for <strong>Edit (pencil icon)</strong> and <strong>Delete (trash icon)</strong> buttons in the table</li>
                <li>Test each CRUD operation</li>
            </ol>
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Note:</strong> All CRUD buttons and modals are present in the code. If you don't see them:
                <ul class="mb-0 mt-2">
                    <li>Check browser console for JavaScript errors (F12)</li>
                    <li>Ensure you have internet connection (for CDN resources)</li>
                    <li>Clear browser cache (Ctrl+F5)</li>
                    <li>Make sure database is properly set up</li>
                </ul>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
