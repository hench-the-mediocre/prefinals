<?php
require_once '../config.php';

if (!isWaiterUser() && !isMasterUser()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'load_tables':
        loadTables();
        break;
    case 'load_categories':
        loadCategories();
        break;
    case 'load_products':
        loadProducts();
        break;
    case 'add_item':
        addItem();
        break;
    case 'load_order_details':
        loadOrderDetails();
        break;
    case 'update_quantity':
        updateQuantity();
        break;
    case 'remove_item':
        removeItem();
        break;
}

function loadTables() {
    global $pdo;
    
    $stmt = $pdo->query("
        SELECT t.*, o.order_id, o.order_number
        FROM table_table t
        LEFT JOIN order_table o ON t.table_id = o.table_id AND o.order_status = 'Pending'
        WHERE t.table_status = 'Enable'
        ORDER BY t.table_id ASC
    ");
    $tables = $stmt->fetchAll();
    
    $html = '<div class="row">';
    foreach ($tables as $table) {
        $status = $table['order_id'] ? 'Occupied' : 'Available';
        $color = $table['order_id'] ? 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)' : 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)';
        
        $html .= '
        <div class="col-12 mb-3">
            <div class="table-btn" data-table-id="'.$table['table_id'].'" data-table-name="'.htmlspecialchars($table['table_name']).'" data-order-id="'.($table['order_id'] ?? '').'" style="
                background: '.$color.';
                border-radius: 15px;
                padding: 1.5rem;
                text-align: center;
                color: white;
                cursor: pointer;
                transition: all 0.3s ease;
            ">
                <i class="bi bi-table" style="font-size: 2rem;"></i>
                <h5 style="margin: 0.5rem 0; font-weight: 600;">'.htmlspecialchars($table['table_name']).'</h5>
                <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">
                    <i class="bi bi-people me-1"></i>'.$table['table_capacity'].' Persons
                </p>
                <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid rgba(255,255,255,0.3);">
                    <small style="font-weight: 600;">'.$status.'</small>
                </div>
            </div>
        </div>';
    }
    $html .= '</div>';
    
    echo $html;
}

function loadCategories() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT category_name FROM product_category_table WHERE category_status = 'Enable' ORDER BY category_name ASC");
    $categories = $stmt->fetchAll();
    
    foreach ($categories as $cat) {
        echo '<option value="'.htmlspecialchars($cat['category_name']).'">'.htmlspecialchars($cat['category_name']).'</option>';
    }
}

function loadProducts() {
    global $pdo;
    $category = $_POST['category'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM product_table WHERE category_name = ? AND product_status = 'Enable' ORDER BY product_name ASC");
    $stmt->execute([$category]);
    $products = $stmt->fetchAll();
    
    echo '<option value="">Select Product</option>';
    foreach ($products as $product) {
        echo '<option value="'.$product['product_id'].'" data-price="'.$product['product_price'].'">'.htmlspecialchars($product['product_name']).' - $'.number_format($product['product_price'], 2).'</option>';
    }
}

function addItem() {
    global $pdo;
    
    $tableId = $_POST['table_id'] ?? 0;
    $orderId = $_POST['order_id'] ?? 0;
    $productId = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;
    
    // Get product details
    $stmt = $pdo->prepare("SELECT * FROM product_table WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        return;
    }
    
    // Get table details
    $stmt = $pdo->prepare("SELECT * FROM table_table WHERE table_id = ?");
    $stmt->execute([$tableId]);
    $table = $stmt->fetch();
    
    // Create order if doesn't exist
    if (!$orderId) {
        $orderNumber = 'ORD' . time();
        $stmt = $pdo->prepare("
            INSERT INTO order_table (order_number, order_date, order_time, table_id, table_name, waiter_id, order_status)
            VALUES (?, CURDATE(), CURTIME(), ?, ?, ?, 'Pending')
        ");
        $stmt->execute([$orderNumber, $tableId, $table['table_name'], $_SESSION['user_id']]);
        $orderId = $pdo->lastInsertId();
    }
    
    // Add item to order
    $total = $product['product_price'] * $quantity;
    $stmt = $pdo->prepare("
        INSERT INTO order_item_table (order_id, product_name, product_price, product_quantity, product_total)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$orderId, $product['product_name'], $product['product_price'], $quantity, $total]);
    
    // Update order totals
    updateOrderTotals($orderId);
    
    echo json_encode(['success' => true, 'order_id' => $orderId]);
}

function loadOrderDetails() {
    global $pdo;
    $orderId = $_POST['order_id'] ?? 0;
    
    if (!$orderId) {
        echo '<div class="text-center text-white py-5"><i class="bi bi-cart-x display-1 mb-3"></i><p>No active order</p></div>';
        return;
    }
    
    // Get order
    $stmt = $pdo->prepare("SELECT * FROM order_table WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    // Get items
    $stmt = $pdo->prepare("SELECT * FROM order_item_table WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll();
    
    $html = '<div class="table-container">';
    $html .= '<table class="table table-sm">';
    $html .= '<thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr></thead>';
    $html .= '<tbody>';
    
    foreach ($items as $item) {
        $html .= '<tr>';
        $html .= '<td>'.htmlspecialchars($item['product_name']).'</td>';
        $html .= '<td>$'.number_format($item['product_price'], 2).'</td>';
        $html .= '<td>
            <select class="form-select form-select-sm quantity-select" data-item-id="'.$item['order_item_id'].'" data-order-id="'.$orderId.'">';
        for ($i = 1; $i <= 20; $i++) {
            $selected = $i == $item['product_quantity'] ? 'selected' : '';
            $html .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
        }
        $html .= '</select></td>';
        $html .= '<td>$'.number_format($item['product_total'], 2).'</td>';
        $html .= '<td><button class="btn btn-danger btn-sm remove-item-btn" data-item-id="'.$item['order_item_id'].'" data-order-id="'.$orderId.'"><i class="bi bi-trash"></i></button></td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody>';
    $html .= '<tfoot>';
    $html .= '<tr><th colspan="3">Subtotal</th><th colspan="2">$'.number_format($order['order_total_amount'], 2).'</th></tr>';
    $html .= '<tr><th colspan="3">Tax</th><th colspan="2">$'.number_format($order['order_tax_amount'], 2).'</th></tr>';
    $html .= '<tr><th colspan="3">Total</th><th colspan="2">$'.number_format($order['order_net_amount'], 2).'</th></tr>';
    $html .= '</tfoot>';
    $html .= '</table>';
    $html .= '</div>';
    
    echo $html;
}

function updateQuantity() {
    global $pdo;
    
    $itemId = $_POST['item_id'] ?? 0;
    $orderId = $_POST['order_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;
    
    // Get item
    $stmt = $pdo->prepare("SELECT * FROM order_item_table WHERE order_item_id = ?");
    $stmt->execute([$itemId]);
    $item = $stmt->fetch();
    
    $total = $item['product_price'] * $quantity;
    
    $stmt = $pdo->prepare("UPDATE order_item_table SET product_quantity = ?, product_total = ? WHERE order_item_id = ?");
    $stmt->execute([$quantity, $total, $itemId]);
    
    updateOrderTotals($orderId);
    
    echo json_encode(['success' => true]);
}

function removeItem() {
    global $pdo;
    
    $itemId = $_POST['item_id'] ?? 0;
    $orderId = $_POST['order_id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM order_item_table WHERE order_item_id = ?");
    $stmt->execute([$itemId]);
    
    // Check if order has items
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM order_item_table WHERE order_id = ?");
    $stmt->execute([$orderId]);
    if ($stmt->fetch()['count'] == 0) {
        // Delete order if no items
        $stmt = $pdo->prepare("DELETE FROM order_table WHERE order_id = ?");
        $stmt->execute([$orderId]);
    } else {
        updateOrderTotals($orderId);
    }
    
    echo json_encode(['success' => true]);
}

function updateOrderTotals($orderId) {
    global $pdo;
    
    // Calculate subtotal
    $stmt = $pdo->prepare("SELECT SUM(product_total) as total FROM order_item_table WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $subtotal = $stmt->fetch()['total'] ?? 0;
    
    // Calculate tax
    $stmt = $pdo->query("SELECT SUM(tax_percentage) as total_tax FROM tax_table WHERE tax_status = 'Enable'");
    $taxPercentage = $stmt->fetch()['total_tax'] ?? 0;
    $taxAmount = ($subtotal * $taxPercentage) / 100;
    
    $netAmount = $subtotal + $taxAmount;
    
    // Update order
    $stmt = $pdo->prepare("UPDATE order_table SET order_total_amount = ?, order_tax_amount = ?, order_net_amount = ? WHERE order_id = ?");
    $stmt->execute([$subtotal, $taxAmount, $netAmount, $orderId]);
}
?>
