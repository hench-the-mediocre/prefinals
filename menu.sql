-- Food Menu Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS food_menu;
USE food_menu;

-- Create menu_items table
CREATE TABLE IF NOT EXISTS menu_items (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample menu items
INSERT INTO menu_items (name, description, price) VALUES
('Margherita Pizza', 'Classic pizza with tomato sauce, mozzarella cheese, and fresh basil', 12.99),
('Chicken Caesar Salad', 'Crisp romaine lettuce with grilled chicken, parmesan cheese, and caesar dressing', 9.99),
('Beef Burger', 'Juicy beef patty with lettuce, tomato, onion, and special sauce', 11.50),
('Pasta Carbonara', 'Creamy pasta with bacon, eggs, and parmesan cheese', 13.75),
('Fish and Chips', 'Beer-battered fish with crispy fries and tartar sauce', 14.25),
('Vegetable Stir Fry', 'Fresh mixed vegetables stir-fried with soy sauce and garlic', 8.99),
('Chocolate Cake', 'Rich chocolate cake with chocolate frosting', 6.50),
('Grilled Salmon', 'Fresh salmon fillet with lemon and herbs', 16.99),
('Chicken Wings', 'Spicy buffalo wings with blue cheese dip', 10.75),
('Greek Salad', 'Fresh salad with olives, feta cheese, and olive oil dressing', 8.25);