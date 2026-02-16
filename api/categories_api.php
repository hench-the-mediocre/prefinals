<?php
require_once '../config.php';

if (!isMasterUser()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'fetch':
        fetchCategories();
        break;
    case 'fetch_single':
        fetchSingleCategory();
        break;
    case 'add':
        addCategory();
        break;
    case 'edit':
        editCategory();
        break;
    case 'change_status':
        changeStatus();
        break;
    case 'delete':
        deleteCategory();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function fetchCategories() {
    global $pdo;
    
    $draw = $_POST['draw'] ?? 1;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';
    
    // Total records
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM product_category_table");
    $totalRecords = $stmt->fetch()['total'];
    
    // Filtered records
    $query = "SELECT COUNT(*) as total FROM product_category_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND category_name LIKE :search";
    }
    $stmt = $pdo->prepare($query);
    if ($searchValue) {
        $stmt->execute(['search' => "%$searchValue%"]);
    } else {
        $stmt->execute();
    }
    $filteredRecords = $stmt->fetch()['total'];
    
    // Fetch data
    $query = "SELECT * FROM product_category_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND category_name LIKE :search";
    }
    $query .= " ORDER BY category_id DESC LIMIT :start, :length";
    
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

function fetchSingleCategory() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM product_category_table WHERE category_id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch();
    
    echo json_encode($category);
}

function addCategory() {
    global $pdo;
    $name = cleanInput($_POST['name'] ?? '');
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Category name is required']);
        return;
    }
    
    // Check if exists
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM product_category_table WHERE category_name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetch()['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Category already exists']);
        return;
    }
    
    $stmt = $pdo->prepare("INSERT INTO product_category_table (category_name, category_status) VALUES (?, 'Enable')");
    if ($stmt->execute([$name])) {
        echo json_encode(['success' => true, 'message' => 'Category added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add category']);
    }
}

function editCategory() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $name = cleanInput($_POST['name'] ?? '');
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Category name is required']);
        return;
    }
    
    // Check if exists (excluding current)
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM product_category_table WHERE category_name = ? AND category_id != ?");
    $stmt->execute([$name, $id]);
    if ($stmt->fetch()['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Category already exists']);
        return;
    }
    
    $stmt = $pdo->prepare("UPDATE product_category_table SET category_name = ? WHERE category_id = ?");
    if ($stmt->execute([$name, $id])) {
        echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update category']);
    }
}

function changeStatus() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $nextStatus = $_POST['next_status'] ?? 'Enable';
    
    $stmt = $pdo->prepare("UPDATE product_category_table SET category_status = ? WHERE category_id = ?");
    if ($stmt->execute([$nextStatus, $id])) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
}

function deleteCategory() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    // Check if category has products
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM product_table WHERE category_name = (SELECT category_name FROM product_category_table WHERE category_id = ?)");
    $stmt->execute([$id]);
    if ($stmt->fetch()['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete category with existing products']);
        return;
    }
    
    $stmt = $pdo->prepare("DELETE FROM product_category_table WHERE category_id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete category']);
    }
}
?>
