<?php
require_once '../config.php';

if (!isMasterUser()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'fetch':
        fetchProducts();
        break;
    case 'fetch_single':
        fetchSingleProduct();
        break;
    case 'add':
        addProduct();
        break;
    case 'edit':
        editProduct();
        break;
    case 'change_status':
        changeStatus();
        break;
    case 'delete':
        deleteProduct();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function fetchProducts() {
    global $pdo;
    
    $draw = $_POST['draw'] ?? 1;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM product_table");
    $totalRecords = $stmt->fetch()['total'];
    
    $query = "SELECT COUNT(*) as total FROM product_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND (product_name LIKE :search OR category_name LIKE :search)";
    }
    $stmt = $pdo->prepare($query);
    if ($searchValue) {
        $stmt->execute(['search' => "%$searchValue%"]);
    } else {
        $stmt->execute();
    }
    $filteredRecords = $stmt->fetch()['total'];
    
    $query = "SELECT * FROM product_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND (product_name LIKE :search OR category_name LIKE :search)";
    }
    $query .= " ORDER BY product_id DESC LIMIT :start, :length";
    
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

function fetchSingleProduct() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM product_table WHERE product_id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
}

function addProduct() {
    global $pdo;
    $category = cleanInput($_POST['category'] ?? '');
    $name = cleanInput($_POST['name'] ?? '');
    $price = $_POST['price'] ?? 0;
    
    if (empty($category) || empty($name) || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    $stmt = $pdo->prepare("INSERT INTO product_table (category_name, product_name, product_price, product_status) VALUES (?, ?, ?, 'Enable')");
    if ($stmt->execute([$category, $name, $price])) {
        echo json_encode(['success' => true, 'message' => 'Product added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add product']);
    }
}

function editProduct() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $category = cleanInput($_POST['category'] ?? '');
    $name = cleanInput($_POST['name'] ?? '');
    $price = $_POST['price'] ?? 0;
    
    if (empty($category) || empty($name) || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    $stmt = $pdo->prepare("UPDATE product_table SET category_name = ?, product_name = ?, product_price = ? WHERE product_id = ?");
    if ($stmt->execute([$category, $name, $price, $id])) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update product']);
    }
}

function changeStatus() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $nextStatus = $_POST['next_status'] ?? 'Enable';
    
    $stmt = $pdo->prepare("UPDATE product_table SET product_status = ? WHERE product_id = ?");
    if ($stmt->execute([$nextStatus, $id])) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
}

function deleteProduct() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM product_table WHERE product_id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
    }
}
?>
