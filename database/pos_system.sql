-- Database: dbkonterku
-- POS System Database Schema

CREATE DATABASE IF NOT EXISTS dbkonterku;
USE dbkonterku;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    role ENUM('admin', 'cashier', 'manager') DEFAULT 'cashier',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barcode VARCHAR(50) UNIQUE,
    name VARCHAR(100) NOT NULL,
    category_id INT,
    description TEXT,
    purchase_price DECIMAL(10,2) NOT NULL,
    selling_price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    min_stock INT DEFAULT 10,
    unit VARCHAR(20) DEFAULT 'pcs',
    image VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Customers table
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sales transactions table
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT,
    user_id INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    discount_percent DECIMAL(5,2) DEFAULT 0,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    tax_percent DECIMAL(5,2) DEFAULT 0,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'credit_card', 'debit_card', 'e_wallet') DEFAULT 'cash',
    paid_amount DECIMAL(10,2) NOT NULL,
    change_amount DECIMAL(10,2) DEFAULT 0,
    status ENUM('completed', 'pending', 'cancelled') DEFAULT 'completed',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Sales details table
CREATE TABLE sale_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    discount_percent DECIMAL(5,2) DEFAULT 0,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Stock movements table
CREATE TABLE stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    type ENUM('in', 'out', 'adjustment') NOT NULL,
    quantity INT NOT NULL,
    reference_type VARCHAR(50), -- 'purchase', 'sale', 'adjustment', etc
    reference_id INT,
    notes TEXT,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Settings table
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT
);

-- Insert default data
INSERT INTO users (username, password, full_name, email, role) VALUES
('admin', '$2y$10$YourHashedPasswordHere', 'Administrator', 'admin@pos.com', 'admin'),
('cashier1', '$2y$10$YourHashedPasswordHere', 'Cashier One', 'cashier1@pos.com', 'cashier');

INSERT INTO categories (name, description) VALUES
('Makanan', 'Produk makanan dan snack'),
('Minuman', 'Produk minuman'),
('Elektronik', 'Produk elektronik dan gadget'),
('Pakaian', 'Produk fashion dan pakaian'),
('Alat Tulis', 'Alat tulis dan perlengkapan kantor');

INSERT INTO settings (setting_key, setting_value, description) VALUES
('shop_name', 'Konterku POS', 'Nama toko'),
('shop_address', 'Jl. Contoh No. 123', 'Alamat toko'),
('shop_phone', '081234567890', 'Nomor telepon toko'),
('tax_percent', '11', 'Persentase pajak (PPN)'),
('currency', 'Rp', 'Mata uang'),
('receipt_footer', 'Terima kasih atas kunjungan Anda', 'Footer struk');

-- Create indexes for better performance
CREATE INDEX idx_sales_date ON sales(created_at);
CREATE INDEX idx_products_barcode ON products(barcode);
CREATE INDEX idx_stock_movements_product ON stock_movements(product_id);