<?php
require_once '../config.php';

if (!isMasterUser()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'fetch':
        fetchTaxes();
        break;
    case 'fetch_single':
        fetchSingleTax();
        break;
    case 'add':
        addTax();
        break;
    case 'edit':
        editTax();
        break;
    case 'change_status':
        changeStatus();
        break;
    case 'delete':
        deleteTax();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function fetchTaxes() {
    global $pdo;
    
    $draw = $_POST['draw'] ?? 1;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tax_table");
    $totalRecords = $stmt->fetch()['total'];
    
    $query = "SELECT COUNT(*) as total FROM tax_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND tax_name LIKE :search";
    }
    $stmt = $pdo->prepare($query);
    if ($searchValue) {
        $stmt->execute(['search' => "%$searchValue%"]);
    } else {
        $stmt->execute();
    }
    $filteredRecords = $stmt->fetch()['total'];
    
    $query = "SELECT * FROM tax_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND tax_name LIKE :search";
    }
    $query .= " ORDER BY tax_id DESC LIMIT :start, :length";
    
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

function fetchSingleTax() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM tax_table WHERE tax_id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
}

function addTax() {
    global $pdo;
    $name = cleanInput($_POST['name'] ?? '');
    $percentage = $_POST['percentage'] ?? 0;
    
    if (empty($name) || $percentage < 0) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM tax_table WHERE tax_name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetch()['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Tax already exists']);
        return;
    }
    
    $stmt = $pdo->prepare("INSERT INTO tax_table (tax_name, tax_percentage, tax_status) VALUES (?, ?, 'Enable')");
    if ($stmt->execute([$name, $percentage])) {
        echo json_encode(['success' => true, 'message' => 'Tax added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add tax']);
    }
}

function editTax() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $name = cleanInput($_POST['name'] ?? '');
    $percentage = $_POST['percentage'] ?? 0;
    
    if (empty($name) || $percentage < 0) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM tax_table WHERE tax_name = ? AND tax_id != ?");
    $stmt->execute([$name, $id]);
    if ($stmt->fetch()['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Tax already exists']);
        return;
    }
    
    $stmt = $pdo->prepare("UPDATE tax_table SET tax_name = ?, tax_percentage = ? WHERE tax_id = ?");
    if ($stmt->execute([$name, $percentage, $id])) {
        echo json_encode(['success' => true, 'message' => 'Tax updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update tax']);
    }
}

function changeStatus() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $nextStatus = $_POST['next_status'] ?? 'Enable';
    
    $stmt = $pdo->prepare("UPDATE tax_table SET tax_status = ? WHERE tax_id = ?");
    if ($stmt->execute([$nextStatus, $id])) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
}

function deleteTax() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM tax_table WHERE tax_id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true, 'message' => 'Tax deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete tax']);
    }
}
?>
