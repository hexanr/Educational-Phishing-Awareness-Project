<?php
/**
 * Security Utility Class
 * 
 * Handles all security operations
 */

class Security {
    
    /**
     * Hash password using BCRYPT
     * 
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
    }
    
    /**
     * Verify password hash
     * 
     * @param string $password Plain text password
     * @param string $hash Password hash
     * @return bool True if password matches
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
    
    /**
     * Sanitize string input
     * 
     * @param string $input Input to sanitize
     * @return string Sanitized input
     */
    public static function sanitizeString(string $input): string {
        return filter_var(trim($input), FILTER_SANITIZE_STRING);
    }
    
    /**
     * Sanitize email
     * 
     * @param string $email Email to sanitize
     * @return string Sanitized email
     */
    public static function sanitizeEmail(string $email): string {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
    
    /**
     * Escape HTML special characters
     * 
     * @param string $text Text to escape
     * @return string Escaped text
     */
    public static function escapeHtml(string $text): string {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Generate CSRF token
     * 
     * @return string CSRF token
     */
    public static function generateCSRFToken(): string {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Verify CSRF token
     * 
     * @param string $token Token to verify
     * @return bool True if valid
     */
    public static function verifyCSRFToken(string $token): bool {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Generate secure random string
     * 
     * @param int $length Length of string
     * @return string Random string
     */
    public static function generateRandomString(int $length = 32): string {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Rate limit check
     * 
     * @param string $key Rate limit key
     * @param int $attempts Maximum attempts
     * @param int $interval Time interval in seconds
     * @return bool True if within limit
     */
    public static function checkRateLimit(string $key, int $attempts = 5, int $interval = 300): bool {
        $cacheKey = "ratelimit_$key";
        
        // Get current attempt count
        $count = apcu_fetch($cacheKey);
        
        if ($count === false) {
            // First attempt
            apcu_store($cacheKey, 1, $interval);
            return true;
        }
        
        // Check if exceeded
        if ($count >= $attempts) {
            return false;
        }
        
        // Increment counter
        apcu_inc($cacheKey);
        
        return true;
    }
    
    /**
     * Reset rate limit
     * 
     * @param string $key Rate limit key
     */
    public static function resetRateLimit(string $key): void {
        $cacheKey = "ratelimit_$key";
        apcu_delete($cacheKey);
    }
    
    /**
     * Check if HTTPS is enabled
     * 
     * @return bool True if HTTPS
     */
    public static function isHttps(): bool {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
               (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
               (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on');
    }
    
    /**
     * Get client IP address
     * 
     * @return string Client IP
     */
    public static function getClientIp(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        
        return 'UNKNOWN';
    }
    
    /**
     * Log security event
     * 
     * @param string $eventType Type of security event
     * @param array $data Event data
     */
    public static function logSecurityEvent(string $eventType, array $data = []): void {
        $logData = array_merge(
            [
                'event' => $eventType,
                'ip' => self::getClientIp(),
                'timestamp' => date('Y-m-d H:i:s'),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            ],
            $data
        );
        
        Logger::info('Security Event', $logData);
    }
    
    /**
     * Validate referrer
     * 
     * @param string $expectedDomain Expected domain
     * @return bool True if valid referrer
     */
    public static function validateReferrer(string $expectedDomain): bool {
        if (empty($_SERVER['HTTP_REFERER'])) {
            return false;
        }
        
        $referrerHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        
        return $referrerHost === $expectedDomain;
    }
    
    /**
     * Generate API token
     * 
     * @return string API token
     */
    public static function generateApiToken(): string {
        return 'token_' . bin2hex(random_bytes(32));
    }
    
    /**
     * Hash API token for storage
     * 
     * @param string $token API token
     * @return string Hashed token
     */
    public static function hashApiToken(string $token): string {
        return hash('sha256', $token);
    }
}

?>
