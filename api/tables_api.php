<?php
require_once '../config.php';

if (!isMasterUser()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'fetch':
        fetchTables();
        break;
    case 'fetch_single':
        fetchSingleTable();
        break;
    case 'add':
        addTable();
        break;
    case 'edit':
        editTable();
        break;
    case 'change_status':
        changeStatus();
        break;
    case 'delete':
        deleteTable();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function fetchTables() {
    global $pdo;
    
    $draw = $_POST['draw'] ?? 1;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM table_table");
    $totalRecords = $stmt->fetch()['total'];
    
    $query = "SELECT COUNT(*) as total FROM table_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND table_name LIKE :search";
    }
    $stmt = $pdo->prepare($query);
    if ($searchValue) {
        $stmt->execute(['search' => "%$searchValue%"]);
    } else {
        $stmt->execute();
    }
    $filteredRecords = $stmt->fetch()['total'];
    
    $query = "SELECT * FROM table_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND table_name LIKE :search";
    }
    $query .= " ORDER BY table_id DESC LIMIT :start, :length";
    
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

function fetchSingleTable() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM table_table WHERE table_id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
}

function addTable() {
    global $pdo;
    $name = cleanInput($_POST['name'] ?? '');
    $capacity = $_POST['capacity'] ?? 0;
    
    if (empty($name) || $capacity <= 0) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM table_table WHERE table_name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetch()['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Table already exists']);
        return;
    }
    
    $stmt = $pdo->prepare("INSERT INTO table_table (table_name, table_capacity, table_status) VALUES (?, ?, 'Enable')");
    if ($stmt->execute([$name, $capacity])) {
        echo json_encode(['success' => true, 'message' => 'Table added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add table']);
    }
}

function editTable() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $name = cleanInput($_POST['name'] ?? '');
    $capacity = $_POST['capacity'] ?? 0;
    
    if (empty($name) || $capacity <= 0) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM table_table WHERE table_name = ? AND table_id != ?");
    $stmt->execute([$name, $id]);
    if ($stmt->fetch()['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Table already exists']);
        return;
    }
    
    $stmt = $pdo->prepare("UPDATE table_table SET table_name = ?, table_capacity = ? WHERE table_id = ?");
    if ($stmt->execute([$name, $capacity, $id])) {
        echo json_encode(['success' => true, 'message' => 'Table updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update table']);
    }
}

function changeStatus() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $nextStatus = $_POST['next_status'] ?? 'Enable';
    
    $stmt = $pdo->prepare("UPDATE table_table SET table_status = ? WHERE table_id = ?");
    if ($stmt->execute([$nextStatus, $id])) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
}

function deleteTable() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM table_table WHERE table_id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true, 'message' => 'Table deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete table']);
    }
}
?>
