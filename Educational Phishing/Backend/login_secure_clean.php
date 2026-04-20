<?php
/**
 * Phishing Education Project - Secure Login Handler
 * 
 * ⚠️ EDUCATIONAL PURPOSE ONLY
 * 
 * This file demonstrates:
 * 1. How phishing attacks work
 * 2. Common security vulnerabilities
 * 3. Proper security implementations
 */

// ============================================
// INITIALIZATION & SECURITY HEADERS
// ============================================

session_start();

// Security headers to prevent common attacks
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Content-Security-Policy: default-src \'self\'');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/security.log');

// ============================================
// DATABASE CONFIGURATION
// ============================================

class DatabaseConfig {
    const HOST = 'localhost';
    const USER = 'root';
    const PASSWORD = '';
    const DATABASE = 'phishing';
}

// ============================================
// SECURITY CONSTANTS
// ============================================

const MIN_USERNAME_LENGTH = 3;
const MAX_USERNAME_LENGTH = 20;
const MIN_PASSWORD_LENGTH = 6;
const MAX_PASSWORD_LENGTH = 255;
const USERNAME_PATTERN = '/^[a-zA-Z0-9_]{3,20}$/';

// ============================================
// MAIN EXECUTION
// ============================================

try {
    // Validate request method
    validateRequestMethod();
    
    // Validate CSRF token
    validateCSRFToken();
    
    // Get and validate input
    $input = sanitizeInput();
    
    // Process login
    $result = processLogin($input);
    
    // Return result
    handleResult($result);
    
} catch (Exception $e) {
    handleError($e);
}

// ============================================
// FUNCTIONS
// ============================================

/**
 * Validate HTTP request method
 * 
 * @throws Exception if method is not POST
 */
function validateRequestMethod(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Method Not Allowed');
    }
}

/**
 * Validate CSRF token
 * 
 * @throws Exception if token is invalid
 */
function validateCSRFToken(): void {
    // Initialize CSRF token if not exists
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    // Verify CSRF token
    if (!isset($_POST['csrf_token'])) {
        throw new Exception('CSRF token missing');
    }
    
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        error_log('CSRF token mismatch - possible CSRF attack');
        throw new Exception('Security validation failed');
    }
}

/**
 * Sanitize and validate user input
 * 
 * @return array Validated input data
 * @throws Exception if input is invalid
 */
function sanitizeInput(): array {
    // Check if inputs exist
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        throw new Exception('Missing required fields');
    }
    
    // Get and trim inputs
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validate username
    if (empty($username)) {
        throw new Exception('Username cannot be empty');
    }
    
    if (strlen($username) < MIN_USERNAME_LENGTH || strlen($username) > MAX_USERNAME_LENGTH) {
        throw new Exception('Username length must be between ' . MIN_USERNAME_LENGTH . ' and ' . MAX_USERNAME_LENGTH);
    }
    
    if (!preg_match(USERNAME_PATTERN, $username)) {
        throw new Exception('Username contains invalid characters');
    }
    
    // Validate password
    if (empty($password)) {
        throw new Exception('Password cannot be empty');
    }
    
    if (strlen($password) < MIN_PASSWORD_LENGTH || strlen($password) > MAX_PASSWORD_LENGTH) {
        throw new Exception('Password length must be between ' . MIN_PASSWORD_LENGTH . ' and ' . MAX_PASSWORD_LENGTH);
    }
    
    // Sanitize username
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    
    return [
        'username' => $username,
        'password' => $password
    ];
}

/**
 * Process login request
 * 
 * @param array $input Sanitized input data
 * @return array Result of login attempt
 */
function processLogin(array $input): array {
    try {
        // Connect to database
        $conn = connectDatabase();
        
        // Check if user exists
        $userExists = checkUserExists($conn, $input['username']);
        
        if ($userExists) {
            logSecurityEvent('duplicate_login_attempt', $input['username']);
            return [
                'success' => false,
                'message' => 'This account already exists in our educational database',
                'redirect' => null
            ];
        }
        
        // Create new user
        createUser($conn, $input['username'], $input['password']);
        
        logSecurityEvent('new_user_registered', $input['username']);
        
        return [
            'success' => true,
            'message' => 'User registered successfully',
            'redirect' => 'thank_you_educational.html'
        ];
        
    } catch (Exception $e) {
        error_log('Database error: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'A technical error occurred. Please try again later.',
            'redirect' => null
        ];
    }
}

/**
 * Connect to database
 * 
 * @return mysqli Database connection
 * @throws Exception if connection fails
 */
function connectDatabase(): mysqli {
    $conn = new mysqli(
        DatabaseConfig::HOST,
        DatabaseConfig::USER,
        DatabaseConfig::PASSWORD,
        DatabaseConfig::DATABASE
    );
    
    // Check connection
    if ($conn->connect_error) {
        error_log('Database connection failed: ' . $conn->connect_error);
        throw new Exception('Database connection failed');
    }
    
    // Set charset
    $conn->set_charset('utf8mb4');
    
    return $conn;
}

/**
 * Check if user already exists
 * 
 * @param mysqli $conn Database connection
 * @param string $username Username to check
 * @return bool True if user exists
 * @throws Exception if query fails
 */
function checkUserExists(mysqli $conn, string $username): bool {
    $stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param('s', $username);
    
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    
    $stmt->close();
    
    return $exists;
}

/**
 * Create new user with encrypted password
 * 
 * @param mysqli $conn Database connection
 * @param string $username Username
 * @param string $password Plain text password
 * @throws Exception if creation fails
 */
function createUser(mysqli $conn, string $username, string $password): void {
    // Hash password using BCRYPT
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param('ss', $username, $hashedPassword);
    
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
}

/**
 * Log security events
 * 
 * @param string $eventType Type of security event
 * @param string $username Username involved
 */
function logSecurityEvent(string $eventType, string $username): void {
    $logMessage = sprintf(
        '[%s] Event: %s | User: %s | IP: %s',
        date('Y-m-d H:i:s'),
        $eventType,
        $username,
        $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    );
    
    error_log($logMessage);
}

/**
 * Handle login result
 * 
 * @param array $result Login result
 */
function handleResult(array $result): void {
    if ($result['success'] && $result['redirect']) {
        header('Location: ' . $result['redirect'], true, 302);
        exit();
    }
    
    // Display educational page
    displayEducationalPage($result);
}

/**
 * Handle errors gracefully
 * 
 * @param Exception $e Exception object
 */
function handleError(Exception $e): void {
    error_log('Error: ' . $e->getMessage());
    
    $result = [
        'success' => false,
        'message' => 'A technical error occurred. Please try again later.',
        'redirect' => null
    ];
    
    displayEducationalPage($result);
}

/**
 * Display educational information page
 * 
 * @param array $result Result data to display
 */
function displayEducationalPage(array $result): void {
    $title = $result['success'] ? 'Success' : 'Information';
    $messageClass = $result['success'] ? 'success' : 'info';
    
    ?>
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($title); ?> - Educational Demo</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }
            
            .container {
                background: white;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                max-width: 600px;
                width: 100%;
            }
            
            h1 {
                color: #333;
                margin-bottom: 20px;
                font-size: 28px;
            }
            
            .message {
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 20px;
                font-size: 16px;
                line-height: 1.6;
            }
            
            .message.success {
                background: #e8f5e9;
                border-left: 4px solid #4caf50;
                color: #2e7d32;
            }
            
            .message.info {
                background: #e7f3ff;
                border-left: 4px solid #2196F3;
                color: #1565c0;
            }
            
            .info-box {
                background: #f5f5f5;
                padding: 15px;
                border-radius: 6px;
                margin: 15px 0;
            }
            
            .info-box h3 {
                color: #333;
                margin-bottom: 10px;
            }
            
            .info-box ul {
                list-style: none;
                padding-left: 20px;
            }
            
            .info-box li {
                padding: 8px 0;
                color: #666;
            }
            
            .info-box li:before {
                content: "→ ";
                color: #667eea;
                font-weight: bold;
                margin-right: 10px;
            }
            
            .buttons {
                display: flex;
                gap: 10px;
                margin-top: 30px;
                flex-wrap: wrap;
            }
            
            a, button {
                flex: 1;
                min-width: 150px;
                padding: 12px 25px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                text-decoration: none;
                font-size: 14px;
                font-weight: bold;
                transition: all 0.3s;
            }
            
            .btn-primary {
                background: #667eea;
                color: white;
            }
            
            .btn-primary:hover {
                background: #764ba2;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            }
            
            .btn-secondary {
                background: #f5f5f5;
                color: #333;
                border: 2px solid #ddd;
            }
            
            .btn-secondary:hover {
                background: #eee;
                border-color: #667eea;
            }
            
            @media (max-width: 600px) {
                .container {
                    padding: 20px;
                }
                
                h1 {
                    font-size: 22px;
                }
                
                a, button {
                    min-width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>📚 معلومات تعليمية</h1>
            
            <div class="message <?php echo htmlspecialchars($messageClass); ?>">
                <?php echo htmlspecialchars($result['message']); ?>
            </div>
            
            <div class="info-box">
                <h3>🔍 ماذا تعلمت؟</h3>
                <ul>
                    <li>كيف يحاول المهاجم خداعك</li>
                    <li>أهمية التحقق من الـ URL</li>
                    <li>ضرورة البيانات المشفرة</li>
                    <li>كيفية حماية حسابك</li>
                </ul>
            </div>
            
            <div class="info-box">
                <h3>⚠️ نصائح أمنية</h3>
                <ul>
                    <li>تفقد الـ URL قبل إدخال بيانات</li>
                    <li>ابحث عن القفل الأخضر 🔒</li>
                    <li>استخدم كلمات مرور قوية</li>
                    <li>فعّل المصادقة الثنائية (2FA)</li>
                </ul>
            </div>
            
            <div class="buttons">
                <a href="protection_guide.html" class="btn-primary">📚 دليل الحماية</a>
                <a href="warning.html" class="btn-secondary">← الرجوع</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
