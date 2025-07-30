-- Seed data for testing
USE dbkonterku;

-- Update default users with proper passwords
UPDATE users SET password = '$2y$10$8Kn06hGVH.ZQjJxA7W0FPOLsyhBC3r9S3fJGc7Uxe5H8vTZwR9qWi' WHERE username = 'admin'; -- password: admin123
UPDATE users SET password = '$2y$10$ND4W2rCmxgCHMRj2CpqI6.GvgwM/TfH1eFsYs0.MeJqF/ZUpkeSl.' WHERE username = 'cashier1'; -- password: cashier123

-- Add more sample products
INSERT INTO products (barcode, name, category_id, description, purchase_price, selling_price, stock, min_stock, unit) VALUES
('8991001101010', 'Indomie Goreng Original', 1, 'Mie instant goreng original', 2500, 3500, 100, 20, 'pcs'),
('8992388133246', 'Aqua 600ml', 2, 'Air mineral kemasan botol', 2000, 3000, 50, 10, 'btl'),
('8996001600146', 'Teh Pucuk Harum 350ml', 2, 'Minuman teh kemasan', 3000, 4000, 30, 10, 'btl'),
('8992761166267', 'Oreo Original 137g', 1, 'Biskuit sandwich coklat', 8000, 10000, 25, 5, 'pcs'),
('089686010695', 'Coca Cola 390ml', 2, 'Minuman berkarbonasi', 4000, 5500, 40, 10, 'btl'),
('8992775001165', 'Chitato Sapi Panggang 68g', 1, 'Keripik kentang rasa sapi panggang', 9000, 11000, 20, 5, 'pcs'),
('8996001410011', 'Bear Brand 189ml', 2, 'Susu steril siap minum', 8500, 10000, 15, 5, 'klg'),
('8992702000120', 'Silverqueen Cashew 65g', 1, 'Coklat dengan kacang mete', 15000, 18000, 10, 3, 'pcs'),
('8991102220309', 'Sunlight Jeruk Nipis 800ml', 3, 'Sabun cuci piring', 12000, 15000, 12, 3, 'btl'),
('8999999036126', 'Rinso Anti Noda 900g', 3, 'Deterjen bubuk', 18000, 22000, 8, 3, 'pcs');

-- Add sample customers
INSERT INTO customers (code, name, phone, email, address) VALUES
('CUST001', 'John Doe', '081234567890', 'john@email.com', 'Jl. Sudirman No. 123'),
('CUST002', 'Jane Smith', '081234567891', 'jane@email.com', 'Jl. Thamrin No. 456'),
('CUST003', 'Bob Johnson', '081234567892', 'bob@email.com', 'Jl. Gatot Subroto No. 789'),
('MEMBER001', 'Alice Brown', '081234567893', 'alice@email.com', 'Jl. Kuningan No. 321'),
('MEMBER002', 'Charlie Wilson', '081234567894', 'charlie@email.com', 'Jl. Rasuna Said No. 654');

-- Add sample sales
INSERT INTO sales (invoice_number, customer_id, user_id, subtotal, discount_percent, discount_amount, tax_percent, tax_amount, total, payment_method, paid_amount, change_amount) VALUES
('INV-20240101-001', 1, 1, 50000, 0, 0, 11, 5500, 55500, 'cash', 60000, 4500),
('INV-20240101-002', 2, 2, 35000, 10, 3500, 11, 3465, 34965, 'debit_card', 34965, 0),
('INV-20240102-001', NULL, 2, 22000, 0, 0, 11, 2420, 24420, 'cash', 25000, 580);

-- Add sample sale details
INSERT INTO sale_details (sale_id, product_id, price, quantity, discount_percent, discount_amount, subtotal) VALUES
(1, 1, 3500, 5, 0, 0, 17500),
(1, 3, 4000, 3, 0, 0, 12000),
(1, 5, 5500, 4, 0, 0, 20500),
(2, 2, 3000, 5, 0, 0, 15000),
(2, 4, 10000, 2, 0, 0, 20000),
(3, 6, 11000, 2, 0, 0, 22000);

-- Update stock movements
INSERT INTO stock_movements (product_id, type, quantity, reference_type, reference_id, notes, user_id) VALUES
(1, 'out', 5, 'sale', 1, 'Sale transaction', 1),
(3, 'out', 3, 'sale', 1, 'Sale transaction', 1),
(5, 'out', 4, 'sale', 1, 'Sale transaction', 1),
(2, 'out', 5, 'sale', 2, 'Sale transaction', 2),
(4, 'out', 2, 'sale', 2, 'Sale transaction', 2),
(6, 'out', 2, 'sale', 3, 'Sale transaction', 2);