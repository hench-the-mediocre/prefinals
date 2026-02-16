<?php
require_once '../config.php';

if (!isCashierUser() && !isMasterUser()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'fetch':
        fetchBills();
        break;
    case 'fetch_single':
        fetchSingleBill();
        break;
    case 'complete':
        completeBill();
        break;
    case 'delete':
        deleteBill();
        break;
}

function fetchBills() {
    global $pdo;
    
    $draw = $_POST['draw'] ?? 1;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM order_table");
    $totalRecords = $stmt->fetch()['total'];
    
    $query = "SELECT COUNT(*) as total FROM order_table o WHERE 1=1";
    if ($searchValue) {
        $query .= " AND (o.order_number LIKE :search OR o.table_name LIKE :search)";
    }
    $stmt = $pdo->prepare($query);
    if ($searchValue) {
        $stmt->execute(['search' => "%$searchValue%"]);
    } else {
        $stmt->execute();
    }
    $filteredRecords = $stmt->fetch()['total'];
    
    $query = "
        SELECT o.*, 
               u1.user_name as waiter_name,
               u2.user_name as cashier_name
        FROM order_table o
        LEFT JOIN user_table u1 ON o.waiter_id = u1.user_id
        LEFT JOIN user_table u2 ON o.cashier_id = u2.user_id
        WHERE 1=1
    ";
    if ($searchValue) {
        $query .= " AND (o.order_number LIKE :search OR o.table_name LIKE :search)";
    }
    $query .= " ORDER BY o.order_id DESC LIMIT :start, :length";
    
    $stmt = $pdo->prepare($query);
    if ($searchValue) {
        $stmt->bindValue(':search', "%$searchValue%", PDO::PARAM_STR);
    }
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll();
    
    echo json_encode([
        'draw' => intval($draw),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data
    ]);
}

function fetchSingleBill() {
    global $pdo;
    $orderId = $_POST['order_id'] ?? 0;
    
    // Get order
    $stmt = $pdo->prepare("
        SELECT o.*, 
               u1.user_name as waiter_name,
               u2.user_name as cashier_name
        FROM order_table o
        LEFT JOIN user_table u1 ON o.waiter_id = u1.user_id
        LEFT JOIN user_table u2 ON o.cashier_id = u2.user_id
        WHERE o.order_id = ?
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    // Get items
    $stmt = $pdo->prepare("SELECT * FROM order_item_table WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll();
    
    $html = '<div class="bill-preview" style="background: white; padding: 2rem; border-radius: 10px;">';
    $html .= '<div class="text-center mb-4">';
    $html .= '<h3>Restaurant Bill</h3>';
    $html .= '<p class="mb-0">Order #: '.htmlspecialchars($order['order_number']).'</p>';
    $html .= '<p class="mb-0">Table: '.htmlspecialchars($order['table_name']).'</p>';
    $html .= '<p class="mb-0">Date: '.$order['order_date'].' '.$order['order_time'].'</p>';
    $html .= '<p class="mb-0">Waiter: '.htmlspecialchars($order['waiter_name']).'</p>';
    $html .= '</div>';
    
    $html .= '<table class="table table-sm">';
    $html .= '<thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>';
    $html .= '<tbody>';
    
    foreach ($items as $item) {
        $html .= '<tr>';
        $html .= '<td>'.htmlspecialchars($item['product_name']).'</td>';
        $html .= '<td>$'.number_format($item['product_price'], 2).'</td>';
        $html .= '<td>'.$item['product_quantity'].'</td>';
        $html .= '<td>$'.number_format($item['product_total'], 2).'</td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody>';
    $html .= '<tfoot>';
    $html .= '<tr><th colspan="3">Subtotal</th><th>$'.number_format($order['order_total_amount'], 2).'</th></tr>';
    $html .= '<tr><th colspan="3">Tax</th><th>$'.number_format($order['order_tax_amount'], 2).'</th></tr>';
    $html .= '<tr class="table-primary"><th colspan="3">Total</th><th>$'.number_format($order['order_net_amount'], 2).'</th></tr>';
    $html .= '</tfoot>';
    $html .= '</table>';
    $html .= '</div>';
    
    echo $html;
}

function completeBill() {
    global $pdo;
    $orderId = $_POST['order_id'] ?? 0;
    
    $stmt = $pdo->prepare("UPDATE order_table SET order_status = 'Completed', cashier_id = ? WHERE order_id = ?");
    if ($stmt->execute([$_SESSION['user_id'], $orderId])) {
        echo json_encode(['success' => true, 'message' => 'Bill completed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to complete bill']);
    }
}

function deleteBill() {
    global $pdo;
    $orderId = $_POST['order_id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM order_table WHERE order_id = ?");
    if ($stmt->execute([$orderId])) {
        echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete order']);
    }
}
?>
