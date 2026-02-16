<?php
// Configuration file for Restaurant Management System
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'rms_socket');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL
define('BASE_URL', 'http://localhost/php-socket-activity/');

// Create PDO connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Helper functions
function isLoggedIn() {
    return true; // Always return true - no authentication required
}

function isMasterUser() {
    return true; // Always return true - full access for all users
}

function isWaiterUser() {
    return true; // Always return true - full access for all users
}

function isCashierUser() {
    return true; // Always return true - full access for all users
}

function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit;
}

function cleanInput($string) {
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}
?>
