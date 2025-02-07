<?php

declare(strict_types=1);

// Determine environment based on the path of the entry script
$entryScript = $_SERVER['SCRIPT_FILENAME'];

// Basic environment detection based on directory path
if (strpos($entryScript, '/home/webdev2024') === 0) {
    // Staging environment
    define('APP_ENV', 'staging');
    define('BASE_PATH', '/home/webdev2024/');
    define('BASE_URL', '/~webdev2024');
} elseif (strpos($entryScript, '/home/hawarden/public_html/src/webdev2024') === 0) {
    // Hawarden's development environment
    define('APP_ENV', 'development');
    define('BASE_PATH', '/home/hawarden/public_html/src/webdev2024/');
    define('BASE_URL', '/~hawarden/src/webdev2024');
} elseif (strpos($entryScript, '/home/agarwal/public_html/src/webdev2024') === 0) {
    // Hawarden's development environment
    define('APP_ENV', 'development');
    define('BASE_PATH', '/home/agarwal/public_html/src/webdev2024/');
    define('BASE_URL', '/~agarwal/src/webdev2024');
} else {
    // Production environment
    define('APP_ENV', 'production');
    define('BASE_PATH', '/aux1/irtf-web/');
    define('BASE_URL', '');
}

// Define other paths based on BASE_PATH
define('VENDOR_PATH', BASE_PATH . 'vendor/');
define('CONFIG_PATH', BASE_PATH . 'configs/');
define('CLASS_PATH', BASE_PATH . 'classes/');
define('LOGS_PATH', BASE_PATH . 'logs/');
define('DATA_PATH', BASE_PATH . 'data/');

// Load Composer autoloader
require_once VENDOR_PATH . 'autoload.php';
