<?php
// Application Configuration

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dbkonterku');

// Application Configuration
define('APP_NAME', 'Konterku POS');
define('BASE_URL', 'http://localhost:82/Pos/');
define('ASSET_URL', BASE_URL . 'public/assets/');
define('UPLOAD_URL', BASE_URL . 'public/uploads/');

// Directory Paths
define('ROOT_PATH', dirname(dirname(__FILE__)) . '/');
define('APP_PATH', ROOT_PATH . 'app/');
define('CONTROLLER_PATH', APP_PATH . 'controllers/');
define('MODEL_PATH', APP_PATH . 'models/');
define('VIEW_PATH', APP_PATH . 'views/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('UPLOAD_PATH', ROOT_PATH . 'public/uploads/');

// Security
define('HASH_KEY', 'your-secret-hash-key-here');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Configuration
session_start();