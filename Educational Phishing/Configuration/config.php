<?php
/**
 * Application Configuration File
 * 
 * This file contains all application settings and constants
 */

// ============================================
// ENVIRONMENT DETECTION
// ============================================

// Define environment
define('ENVIRONMENT', getenv('APP_ENV') ?: 'development');
define('IS_PRODUCTION', ENVIRONMENT === 'production');
define('IS_DEVELOPMENT', ENVIRONMENT === 'development');

// ============================================
// DATABASE CONFIGURATION
// ============================================

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'phishing');
define('DB_CHARSET', 'utf8mb4');

// ============================================
// APPLICATION SETTINGS
// ============================================

// Security
define('BCRYPT_COST', 12);
define('SESSION_TIMEOUT', 1800); // 30 minutes

// Input Validation
define('MIN_USERNAME_LENGTH', 3);
define('MAX_USERNAME_LENGTH', 20);
define('MIN_PASSWORD_LENGTH', 6);
define('MAX_PASSWORD_LENGTH', 255);
define('USERNAME_PATTERN', '/^[a-zA-Z0-9_]{3,20}$/');

// File Paths
define('ROOT_PATH', dirname(__FILE__));
define('LOG_PATH', ROOT_PATH . '/logs/');
define('TEMP_PATH', ROOT_PATH . '/temp/');

// ============================================
// ERROR HANDLING
// ============================================

// Set error reporting based on environment
if (IS_PRODUCTION) {
    error_reporting(0);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Set error log file
ini_set('log_errors', '1');
ini_set('error_log', LOG_PATH . 'php-errors.log');

// ============================================
// SECURITY HEADERS
// ============================================

// Prevent header injection
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Content-Security-Policy: default-src \'self\'');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// ============================================
// SESSION CONFIGURATION
// ============================================

// Secure session settings
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', IS_PRODUCTION ? '1' : '0');
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', SESSION_TIMEOUT);

// ============================================
// TIMEZONE
// ============================================

date_default_timezone_set('UTC');

// ============================================
// LOCALE
// ============================================

setlocale(LC_ALL, 'ar_EG.UTF-8', 'ar_EG');

// ============================================
// CONSTANTS FOR MESSAGES
// ============================================

define('ERROR_MESSAGE_GENERIC', 'An error occurred. Please try again later.');
define('ERROR_MESSAGE_VALIDATION', 'The provided data is invalid.');
define('ERROR_MESSAGE_DATABASE', 'A database error occurred.');
define('ERROR_MESSAGE_SECURITY', 'Security validation failed.');

define('SUCCESS_MESSAGE_CREATED', 'Record created successfully.');
define('SUCCESS_MESSAGE_UPDATED', 'Record updated successfully.');
define('SUCCESS_MESSAGE_DELETED', 'Record deleted successfully.');

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Get config value with fallback
 * 
 * @param string $key Configuration key
 * @param mixed $default Default value
 * @return mixed Configuration value
 */
function config(string $key, $default = null) {
    $configs = [
        'database.host' => DB_HOST,
        'database.user' => DB_USER,
        'database.password' => DB_PASS,
        'database.name' => DB_NAME,
        'database.charset' => DB_CHARSET,
    ];
    
    return $configs[$key] ?? $default;
}

/**
 * Check if application is in production mode
 * 
 * @return bool
 */
function isProduction(): bool {
    return IS_PRODUCTION;
}

/**
 * Get log path for a specific log file
 * 
 * @param string $filename Log filename
 * @return string Full path to log file
 */
function getLogPath(string $filename = 'app.log'): string {
    return LOG_PATH . $filename;
}

/**
 * Get temporary file path
 * 
 * @param string $filename Temp filename
 * @return string Full path to temp file
 */
function getTempPath(string $filename): string {
    return TEMP_PATH . $filename;
}

// ============================================
// ENSURE REQUIRED DIRECTORIES EXIST
// ============================================

$requiredDirs = [LOG_PATH, TEMP_PATH];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// ============================================
// AUTO-LOAD UTILITIES
// ============================================

require_once ROOT_PATH . '/includes/Logger.php';
require_once ROOT_PATH . '/includes/Database.php';
require_once ROOT_PATH . '/includes/Validator.php';
require_once ROOT_PATH . '/includes/Security.php';

?>
