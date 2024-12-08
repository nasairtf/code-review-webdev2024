<?php

declare(strict_types=1);

// Determine environment based on the path of the entry script
$entryScript = $_SERVER['SCRIPT_FILENAME'];

// Basic environment detection based on directory path
if (strpos($entryScript, '/home/webdev2024') === 0) {
    // Development environment
    define('APP_ENV', 'development');
    define('BASE_PATH', '/home/webdev2024/');
    define('BASE_URL', '/~webdev2024');
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

/*

// SAVE FOR FUTURE DISCUSSION:

<?php
// Basic environment detection using directory path or hostname
if (strpos(__DIR__, '/home/webdev2024') === 0) {
    // Primary development environment
    define('APP_ENV', 'development');
    define('BASE_PATH', '/home/webdev2024/');
} elseif (strpos(__DIR__, '/home/anotherdev') === 0) {
    // Secondary development environment
    define('APP_ENV', 'development');
    define('BASE_PATH', '/home/anotherdev/');
} elseif (php_uname('n') === 'staging-server') {
    // Staging environment on a separate server
    define('APP_ENV', 'staging');
    define('BASE_PATH', '/staging/irtf-web/');
} else {
    // Default to production environment
    define('APP_ENV', 'production');
    define('BASE_PATH', '/aux1/irtf-web/');
}

// Define other paths based on BASE_PATH
define('VENDOR_PATH', BASE_PATH . 'vendor/');
define('CONFIG_PATH', BASE_PATH . 'configs/');
define('CLASS_PATH', BASE_PATH . 'classes/');
define('LOGS_PATH', BASE_PATH . 'logs/');
define('DATA_PATH', BASE_PATH . 'data/');

// Load Composer autoloader
require_once VENDOR_PATH . 'autoload.php';
*/
