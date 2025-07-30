<?php
// Load configuration
require_once 'config/config.php';

// Autoloader
spl_autoload_register(function($class) {
    // Check core directory
    if (file_exists(CORE_PATH . $class . '.php')) {
        require_once CORE_PATH . $class . '.php';
    }
    // Check models directory
    elseif (file_exists(MODEL_PATH . $class . '.php')) {
        require_once MODEL_PATH . $class . '.php';
    }
    // Check controllers directory
    elseif (file_exists(CONTROLLER_PATH . $class . '.php')) {
        require_once CONTROLLER_PATH . $class . '.php';
    }
});

// Start the application
$app = new Router();