-- Fix password untuk login
USE dbkonterku;

-- Password: admin123
UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin';

-- Cek user admin
SELECT id, username, password, full_name, role, is_active FROM users WHERE username = 'admin';

-- Jika user admin tidak ada, insert
INSERT IGNORE INTO users (username, password, full_name, email, role, is_active) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@pos.com', 'admin', 1);