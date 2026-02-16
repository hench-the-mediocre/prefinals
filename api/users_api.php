<?php
require_once '../config.php';

if (!isMasterUser()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'fetch':
        fetchUsers();
        break;
    case 'fetch_single':
        fetchSingleUser();
        break;
    case 'add':
        addUser();
        break;
    case 'edit':
        editUser();
        break;
    case 'change_status':
        changeStatus();
        break;
    case 'delete':
        deleteUser();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function fetchUsers() {
    global $pdo;
    
    $draw = $_POST['draw'] ?? 1;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM user_table");
    $totalRecords = $stmt->fetch()['total'];
    
    $query = "SELECT COUNT(*) as total FROM user_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND (user_name LIKE :search OR user_email LIKE :search)";
    }
    $stmt = $pdo->prepare($query);
    if ($searchValue) {
        $stmt->execute(['search' => "%$searchValue%"]);
    } else {
        $stmt->execute();
    }
    $filteredRecords = $stmt->fetch()['total'];
    
    $query = "SELECT * FROM user_table WHERE 1=1";
    if ($searchValue) {
        $query .= " AND (user_name LIKE :search OR user_email LIKE :search)";
    }
    $query .= " ORDER BY user_id DESC LIMIT :start, :length";
    
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

function fetchSingleUser() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM user_table WHERE user_id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
}

function addUser() {
    global $pdo;
    $name = cleanInput($_POST['name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? '';
    
    if (empty($name) || empty($email) || empty($password) || empty($type)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_table WHERE user_email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        return;
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO user_table (user_name, user_email, user_password, user_type, user_status) VALUES (?, ?, ?, ?, 'Enable')");
    if ($stmt->execute([$name, $email, $hashedPassword, $type])) {
        echo json_encode(['success' => true, 'message' => 'User added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add user']);
    }
}

function editUser() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $name = cleanInput($_POST['name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? '';
    
    if (empty($name) || empty($email) || empty($type)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_table WHERE user_email = ? AND user_id != ?");
    $stmt->execute([$email, $id]);
    if ($stmt->fetch()['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        return;
    }
    
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user_table SET user_name = ?, user_email = ?, user_password = ?, user_type = ? WHERE user_id = ?");
        $result = $stmt->execute([$name, $email, $hashedPassword, $type, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE user_table SET user_name = ?, user_email = ?, user_type = ? WHERE user_id = ?");
        $result = $stmt->execute([$name, $email, $type, $id]);
    }
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user']);
    }
}

function changeStatus() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $nextStatus = $_POST['next_status'] ?? 'Enable';
    
    $stmt = $pdo->prepare("UPDATE user_table SET user_status = ? WHERE user_id = ?");
    if ($stmt->execute([$nextStatus, $id])) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
}

function deleteUser() {
    global $pdo;
    $id = $_POST['id'] ?? 0;
    
    // Don't allow deleting yourself
    if ($id == $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
        return;
    }
    
    $stmt = $pdo->prepare("DELETE FROM user_table WHERE user_id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
    }
}
?>
