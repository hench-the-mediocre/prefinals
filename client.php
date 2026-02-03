<?php

// Set time limit to infinity
set_time_limit(seconds: 0);

// Define socket server details - these need to be accessible globally
define(constant_name: 'SOCKET_ADDRESS', value: '127.0.0.1');
define(constant_name: 'SOCKET_PORT', value: 8888);

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    handleFormSubmission();
    exit;
}

// Get initial menu data
$menuData = [];
try {
    // Create a socket
    $socket = socket_create(domain: AF_INET, type: SOCK_STREAM, protocol: SOL_TCP);
    if ($socket !== false) {
        // Connect to the server
        $result = socket_connect(socket: $socket, address: SOCKET_ADDRESS, port: SOCKET_PORT);
        if ($result !== false) {
            // Receive welcome message
            $welcome = socket_read(socket: $socket, length: 1024);
            
            // Request menu items
            $request = json_encode(value: ['action' => 'read']);
            socket_write(socket: $socket, data: $request, length: strlen(string: $request));
            
            // Get response
            $response = socket_read(socket: $socket, length: 4096);
            $menuData = json_decode(json: $response, associative: true);
            
            // Close the socket
            socket_close(socket: $socket);
        }
    }
} catch (Exception $e) {
    $menuData = ['status' => 'error', 'data' => []];
}

/*
 * Handle form submissions via AJAX
 */
function handleFormSubmission() {
    // Use the constants instead of global variables
    $address = SOCKET_ADDRESS;
    $port = SOCKET_PORT;
    
    // Create a socket
    $socket = socket_create(domain: AF_INET, type: SOCK_STREAM, protocol: SOL_TCP);
    if ($socket === false) {
        echo json_encode(value: ['status' => 'error', 'message' => 'Socket creation failed']);
        return;
    }
    
    // Connect to the server
    $result = socket_connect(socket: $socket, address: $address, port: $port);
    if ($result === false) {
        echo json_encode(value: ['status' => 'error', 'message' => 'Socket connection failed: ' . socket_strerror(error_code: socket_last_error(socket: $socket))]);
        return;
    }
    
    // Skip welcome message
    socket_read(socket: $socket, length: 1024);
    
    // Prepare data for server
    $data = [
        'action' => $_POST['action']
    ];
    
    // Add additional data based on action
    switch ($_POST['action']) {
        case 'create':
            $data['name'] = $_POST['name'];
            $data['description'] = $_POST['description'] ?? '';
            $data['price'] = $_POST['price'];
            break;
            
        case 'update':
            $data['id'] = $_POST['id'];
            $data['name'] = $_POST['name'];
            $data['description'] = $_POST['description'] ?? '';
            $data['price'] = $_POST['price'];
            break;
            
        case 'delete':
            $data['id'] = $_POST['id'];
            break;
    }
    
    // Send request to server
    $request = json_encode(value: $data);
    socket_write(socket: $socket, data: $request, length: strlen(string: $request));
    
    // Get response
    $response = socket_read(socket: $socket, length: 4096);
    
    // Close the socket
    socket_close(socket: $socket);
    
    // Get updated menu items
    $socket = socket_create(domain: AF_INET, type: SOCK_STREAM, protocol: SOL_TCP);
    $connectResult = socket_connect(socket: $socket, address: $address, port: $port);
    
    if ($connectResult === false) {
        echo json_encode(value: ['status' => 'error', 'message' => 'Failed to connect for menu refresh']);
        return;
    }
    
    socket_read(socket: $socket, length: 1024); // Skip welcome message
    
    $request = json_encode(value: ['action' => 'read']);
    socket_write(socket: $socket, data: $request, length: strlen(string: $request));
    $menuResponse = socket_read(socket: $socket, length: 4096);
    socket_close(socket: $socket);
    
    // Return response and updated menu
    echo json_encode(value: [
        'status' => 'success',
        'message' => $response,
        'menu' => json_decode(json: $menuResponse, associative: true)
    ]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-light: rgba(255, 255, 255, 0.9);
            --text-dark: rgba(0, 0, 0, 0.8);
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

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle cx="20" cy="20" r="2" fill="url(%23a)"><animate attributeName="cy" values="20;80;20" dur="3s" repeatCount="indefinite"/></circle><circle cx="80" cy="80" r="2" fill="url(%23a)"><animate attributeName="cy" values="80;20;80" dur="4s" repeatCount="indefinite"/></circle><circle cx="40" cy="60" r="1" fill="url(%23a)"><animate attributeName="cx" values="40;60;40" dur="5s" repeatCount="indefinite"/></circle></svg>') repeat;
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Glass Morphism Effects */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.2);
        }

        /* Header Styling */
        .main-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }

        .main-title {
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.5rem;
        }

        .main-subtitle {
            color: var(--text-light);
            font-size: 1.2rem;
            font-weight: 300;
        }

        /* Form Styling */
        .form-container {
            padding: 2rem;
        }

        .form-floating label {
            color: var(--text-dark);
            font-weight: 500;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
        }

        /* Button Styling */
        .btn-gradient {
            background: var(--secondary-gradient);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
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

        .btn-success-gradient {
            background: var(--success-gradient);
        }

        /* Carousel Container */
        .carousel-container {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 2rem;
            margin: 2rem 0;
            border: 1px solid var(--glass-border);
        }

        /* Menu Item Cards */
        .menu-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .menu-card:hover::before {
            left: 100%;
        }

        .menu-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .menu-image {
            width: 80px;
            height: 80px;
            border-radius: 15px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-right: 1rem;
        }

        .price-tag {
            background: var(--success-gradient);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Status Container */
        .status-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            max-width: 400px;
        }

        /* Alert Styling */
        .alert {
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .alert-info {
            background: rgba(13, 202, 240, 0.9);
            color: white;
        }

        .alert-success {
            background: rgba(25, 135, 84, 0.9);
            color: white;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.9);
            color: white;
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-title {
                font-size: 2.5rem;
            }
            
            .carousel-container {
                padding: 1rem;
            }
            
            .menu-card {
                padding: 1rem;
            }
            
            .menu-image {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <!-- Status Container for Notifications -->
    <div class="status-container" id="status-container"></div>

    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="main-header">
            <h1 class="main-title">
                <i class="bi bi-cup-hot-fill me-3"></i>
                Food Menu Manager
            </h1>
            <p class="main-subtitle">Manage your restaurant menu with style and elegance</p>
        </div>

        <div class="row g-4">
            <!-- Form Section -->
            <div class="col-lg-5">
                <div class="glass-card form-container">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-plus-circle-fill fs-3 text-white me-3"></i>
                        <h2 class="text-white mb-0" id="item-title">Add New Menu Item</h2>
                    </div>
                    
                    <form id="menu-form">
                        <input type="hidden" id="item-id" name="id" value="">
                        <input type="hidden" id="action" name="action" value="create">
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Item Name" required>
                            <label for="name"><i class="bi bi-card-text me-2"></i>Item Name</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="description" name="description" placeholder="Description" style="height: 100px"></textarea>
                            <label for="description"><i class="bi bi-file-text me-2"></i>Description</label>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input type="number" class="form-control" id="price" name="price" step="0.01" placeholder="Price" required>
                            <label for="price"><i class="bi bi-currency-dollar me-2"></i>Price ($)</label>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gradient flex-fill" id="submit-button">
                                <i class="bi bi-plus-lg me-2"></i>Add Item
                            </button>
                            <button type="button" class="btn btn-outline-light d-none" id="cancel-btn">
                                <i class="bi bi-x-lg me-2"></i>Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Menu Items Section -->
            <div class="col-lg-7">
                <div class="carousel-container">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-list-ul fs-3 text-white me-3"></i>
                            <h2 class="text-white mb-0">Menu Items</h2>
                        </div>
                        <button class="btn btn-outline-light btn-sm" onclick="fetchUpdates()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                        </button>
                    </div>
                    
                    <div id="menu-container" class="menu-items-container">
                        <?php if (isset($menuData['status']) && $menuData['status'] === 'success' && !empty($menuData['data'])): ?>
                            <?php 
                            $foodIcons = ['🍕', '🍔', '🍝', '🥗', '🍗', '🍤', '🥘', '🍜', '🌮', '🥙', '🍱', '🍛', '🥪', '🌯', '🥞'];
                            foreach ($menuData['data'] as $index => $item): 
                                $icon = $foodIcons[$index % count($foodIcons)];
                            ?>
                                <div class="menu-card d-flex align-items-center" data-id="<?= $item['id'] ?>">
                                    <div class="menu-image">
                                        <?= $icon ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2 fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></h5>
                                        <p class="mb-2 text-muted"><?= htmlspecialchars($item['description']) ?></p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            Added: <?= date('M j, Y', strtotime($item['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="price-tag mb-3">
                                            $<?= number_format($item['price'], 2) ?>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-outline-primary btn-sm edit-btn" data-id="<?= $item['id'] ?>">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="<?= $item['id'] ?>">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-cup-hot display-1 text-white-50 mb-3"></i>
                                <h4 class="text-white mb-2">No menu items found</h4>
                                <p class="text-white-50">Add your first delicious item to get started!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const menuForm = document.getElementById('menu-form');
const statusContainer = document.getElementById('status-container');
const cancelBtn = document.getElementById('cancel-btn');

// Event listeners
menuForm.addEventListener('submit', handleSubmit);
document.addEventListener('click', handleButtonClick);
cancelBtn.addEventListener('click', resetForm);

// Auto-hide alerts after 5 seconds
function autoHideAlert() {
    setTimeout(() => {
        const alerts = statusContainer.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
}

function showAlert(type, message, showSpinner = false) {
    const alertClass = `alert-${type}`;
    const icon = type === 'success' ? 'bi-check-circle-fill' : 
                 type === 'danger' ? 'bi-exclamation-triangle-fill' : 
                 'bi-info-circle-fill';
    
    const spinner = showSpinner ? '<span class="loading-spinner me-2"></span>' : '';
    
    statusContainer.innerHTML = `
        <div class="alert ${alertClass} d-flex align-items-center" role="alert">
            ${spinner}
            <i class="${icon} me-2"></i>
            ${message}
        </div>
    `;
    
    if (!showSpinner) {
        autoHideAlert();
    }
}

function handleSubmit(e) {
    e.preventDefault();
    const action = document.getElementById('action').value;
    const actionText = action === 'create' ? 'Adding' : 'Updating';
    
    showAlert('info', `${actionText} menu item...`, true);
    sendRequest(new FormData(this));
}

function handleButtonClick(e) {
    if (e.target.closest('.edit-btn')) {
        handleEdit(e.target.closest('.edit-btn'));
    } else if (e.target.closest('.delete-btn')) {
        const btn = e.target.closest('.delete-btn');
        const menuCard = btn.closest('.menu-card');
        const itemName = menuCard.querySelector('h5').textContent;
        
        if (confirm(`Are you sure you want to delete "${itemName}"?`)) {
            handleDelete(btn);
        }
    }
}

function handleEdit(btn) {
    const item = btn.closest('.menu-card');
    const id = item.getAttribute('data-id');
    const name = item.querySelector('h5').textContent;
    const desc = item.querySelector('p').textContent;
    const priceText = item.querySelector('.price-tag').textContent;
    const price = priceText.replace('$', '');

    document.getElementById('item-id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('description').value = desc;
    document.getElementById('price').value = price;
    document.getElementById('action').value = 'update';
    document.getElementById('item-title').innerHTML = '<i class="bi bi-pencil-fill me-2"></i>Edit Menu Item';
    document.getElementById('submit-button').innerHTML = '<i class="bi bi-check-lg me-2"></i>Update Item';
    cancelBtn.classList.remove('d-none');
    
    // Smooth scroll to form
    document.querySelector('.form-container').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'center' 
    });
}

function handleDelete(btn) {
    const id = btn.getAttribute('data-id');
    showAlert('info', 'Deleting menu item...', true);

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);
    sendRequest(formData);
}

function sendRequest(formData) {
    fetch('client.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showAlert('success', data.message);
            resetForm();
            if (data.menu) {
                updateMenuItems(data.menu);
            }
        } else {
            showAlert('danger', data.message || 'An error occurred');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('danger', 'Connection failed. Please check if the server is running.');
    });
}

function resetForm() {
    menuForm.reset();
    document.getElementById('item-id').value = '';
    document.getElementById('action').value = 'create';
    document.getElementById('item-title').innerHTML = '<i class="bi bi-plus-circle-fill me-2"></i>Add New Menu Item';
    document.getElementById('submit-button').innerHTML = '<i class="bi bi-plus-lg me-2"></i>Add Item';
    cancelBtn.classList.add('d-none');
}

function updateMenuItems(menuData) {
    const container = document.getElementById('menu-container');
    const foodIcons = ['🍕', '🍔', '🍝', '🥗', '🍗', '🍤', '🥘', '🍜', '🌮', '🥙', '🍱', '🍛', '🥪', '🌯', '🥞'];
    
    if (menuData.status === 'success' && menuData.data.length > 0) {
        let html = '';
        menuData.data.forEach((item, index) => {
            const icon = foodIcons[index % foodIcons.length];
            const date = new Date(item.created_at).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
            
            html += `
                <div class="menu-card d-flex align-items-center" data-id="${item.id}">
                    <div class="menu-image">
                        ${icon}
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-2 fw-bold text-dark">${item.name}</h5>
                        <p class="mb-2 text-muted">${item.description}</p>
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            Added: ${date}
                        </small>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <div class="price-tag mb-3">
                            $${parseFloat(item.price).toFixed(2)}
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm edit-btn" data-id="${item.id}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="${item.id}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    } else {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-cup-hot display-1 text-white-50 mb-3"></i>
                <h4 class="text-white mb-2">No menu items found</h4>
                <p class="text-white-50">Add your first delicious item to get started!</p>
            </div>
        `;
    }
}

function fetchUpdates() {
    showAlert('info', 'Refreshing menu items...', true);
    
    const formData = new FormData();
    formData.append('action', 'read');

    fetch('client.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            updateMenuItems(data.menu || data);
            showAlert('success', 'Menu refreshed successfully!');
        } else {
            showAlert('danger', 'Failed to refresh menu');
        }
    })
    .catch(err => {
        console.error('Update error:', err);
        showAlert('danger', 'Failed to refresh menu');
    });
}

// Add CSS animation for slide out
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
    </body>
</html>

