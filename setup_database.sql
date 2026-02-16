-- Restaurant Management System Database Setup
-- Drop database if exists and create fresh
DROP DATABASE IF EXISTS rms_socket;
CREATE DATABASE rms_socket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rms_socket;

-- User table
CREATE TABLE user_table (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    user_type ENUM('Master', 'Waiter', 'Cashier') DEFAULT 'Waiter',
    user_profile VARCHAR(255) DEFAULT NULL,
    user_status ENUM('Enable', 'Disable') DEFAULT 'Enable',
    user_created_on DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Category table
CREATE TABLE product_category_table (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL UNIQUE,
    category_status ENUM('Enable', 'Disable') DEFAULT 'Enable',
    category_created_on DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Product table
CREATE TABLE product_table (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    product_status ENUM('Enable', 'Disable') DEFAULT 'Enable',
    product_created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_name) REFERENCES product_category_table(category_name) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table management
CREATE TABLE table_table (
    table_id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(255) NOT NULL UNIQUE,
    table_capacity INT DEFAULT 4,
    table_status ENUM('Enable', 'Disable') DEFAULT 'Enable',
    table_created_on DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tax table
CREATE TABLE tax_table (
    tax_id INT AUTO_INCREMENT PRIMARY KEY,
    tax_name VARCHAR(255) NOT NULL UNIQUE,
    tax_percentage DECIMAL(5,2) NOT NULL,
    tax_status ENUM('Enable', 'Disable') DEFAULT 'Enable',
    tax_created_on DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order table
CREATE TABLE order_table (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    order_date DATE NOT NULL,
    order_time TIME NOT NULL,
    table_id INT NOT NULL,
    table_name VARCHAR(255) NOT NULL,
    waiter_id INT NOT NULL,
    cashier_id INT DEFAULT NULL,
    order_total_amount DECIMAL(10,2) DEFAULT 0.00,
    order_tax_amount DECIMAL(10,2) DEFAULT 0.00,
    order_net_amount DECIMAL(10,2) DEFAULT 0.00,
    order_status ENUM('Pending', 'Completed', 'Cancelled') DEFAULT 'Pending',
    order_created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (table_id) REFERENCES table_table(table_id),
    FOREIGN KEY (waiter_id) REFERENCES user_table(user_id),
    FOREIGN KEY (cashier_id) REFERENCES user_table(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order items table
CREATE TABLE order_item_table (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    product_quantity INT NOT NULL,
    product_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES order_table(order_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Restaurant settings table
CREATE TABLE restaurant_table (
    restaurant_id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_name VARCHAR(255) NOT NULL,
    restaurant_address TEXT,
    restaurant_phone VARCHAR(50),
    restaurant_email VARCHAR(255),
    restaurant_logo VARCHAR(255) DEFAULT NULL,
    restaurant_currency VARCHAR(10) DEFAULT 'USD',
    restaurant_timezone VARCHAR(100) DEFAULT 'America/New_York',
    restaurant_created_on DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default master user (password: admin123)
INSERT INTO user_table (user_name, user_email, user_password, user_type, user_status) VALUES
('Master Admin', 'admin@restaurant.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Master', 'Enable');

-- Insert sample categories
INSERT INTO product_category_table (category_name, category_status) VALUES
('Beverages', 'Enable'),
('Main Course', 'Enable'),
('Desserts', 'Enable'),
('Appetizers', 'Enable'),
('Salads', 'Enable');

-- Insert sample products
INSERT INTO product_table (category_name, product_name, product_price, product_status) VALUES
('Beverages', 'Coffee', 3.50, 'Enable'),
('Beverages', 'Tea', 2.50, 'Enable'),
('Beverages', 'Fresh Juice', 4.00, 'Enable'),
('Beverages', 'Soft Drink', 2.00, 'Enable'),
('Main Course', 'Grilled Chicken', 12.99, 'Enable'),
('Main Course', 'Beef Steak', 18.99, 'Enable'),
('Main Course', 'Pasta Carbonara', 10.99, 'Enable'),
('Main Course', 'Fish and Chips', 14.99, 'Enable'),
('Desserts', 'Chocolate Cake', 5.99, 'Enable'),
('Desserts', 'Ice Cream', 4.50, 'Enable'),
('Desserts', 'Tiramisu', 6.99, 'Enable'),
('Appetizers', 'French Fries', 3.99, 'Enable'),
('Appetizers', 'Chicken Wings', 7.99, 'Enable'),
('Appetizers', 'Garlic Bread', 4.50, 'Enable'),
('Salads', 'Caesar Salad', 6.99, 'Enable'),
('Salads', 'Greek Salad', 7.50, 'Enable');

-- Insert sample tables
INSERT INTO table_table (table_name, table_capacity, table_status) VALUES
('Table 1', 4, 'Enable'),
('Table 2', 2, 'Enable'),
('Table 3', 6, 'Enable'),
('Table 4', 4, 'Enable'),
('Table 5', 8, 'Enable'),
('Table 6', 2, 'Enable');

-- Insert sample taxes
INSERT INTO tax_table (tax_name, tax_percentage, tax_status) VALUES
('VAT', 10.00, 'Enable'),
('Service Tax', 5.00, 'Enable');

-- Insert restaurant settings
INSERT INTO restaurant_table (restaurant_name, restaurant_address, restaurant_phone, restaurant_email, restaurant_currency, restaurant_timezone) VALUES
('Socket Restaurant', '123 Main Street, City, Country', '+1234567890', 'info@socketrestaurant.com', 'USD', 'America/New_York');
