<?php
/**
 * Validator Utility Class
 * 
 * Handles all input validation
 */

class Validator {
    
    private static $errors = [];
    
    /**
     * Validate username
     * 
     * @param string $username Username to validate
     * @return bool True if valid
     */
    public static function validateUsername(string $username): bool {
        $username = trim($username);
        
        // Check if empty
        if (empty($username)) {
            self::addError('username', 'Username cannot be empty');
            return false;
        }
        
        // Check length
        if (strlen($username) < MIN_USERNAME_LENGTH) {
            self::addError('username', 'Username must be at least ' . MIN_USERNAME_LENGTH . ' characters');
            return false;
        }
        
        if (strlen($username) > MAX_USERNAME_LENGTH) {
            self::addError('username', 'Username cannot exceed ' . MAX_USERNAME_LENGTH . ' characters');
            return false;
        }
        
        // Check pattern
        if (!preg_match(USERNAME_PATTERN, $username)) {
            self::addError('username', 'Username contains invalid characters. Use letters, numbers, and underscores only');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate password
     * 
     * @param string $password Password to validate
     * @return bool True if valid
     */
    public static function validatePassword(string $password): bool {
        // Check if empty
        if (empty($password)) {
            self::addError('password', 'Password cannot be empty');
            return false;
        }
        
        // Check length
        if (strlen($password) < MIN_PASSWORD_LENGTH) {
            self::addError('password', 'Password must be at least ' . MIN_PASSWORD_LENGTH . ' characters');
            return false;
        }
        
        if (strlen($password) > MAX_PASSWORD_LENGTH) {
            self::addError('password', 'Password is too long');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate email
     * 
     * @param string $email Email to validate
     * @return bool True if valid
     */
    public static function validateEmail(string $email): bool {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::addError('email', 'Invalid email format');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate integer
     * 
     * @param mixed $value Value to validate
     * @param int $min Minimum value
     * @param int $max Maximum value
     * @return bool True if valid
     */
    public static function validateInteger($value, int $min = null, int $max = null): bool {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            self::addError('integer', 'Value must be an integer');
            return false;
        }
        
        $intValue = intval($value);
        
        if ($min !== null && $intValue < $min) {
            self::addError('integer', "Value must be at least $min");
            return false;
        }
        
        if ($max !== null && $intValue > $max) {
            self::addError('integer', "Value cannot exceed $max");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate string length
     * 
     * @param string $value Value to validate
     * @param int $min Minimum length
     * @param int $max Maximum length
     * @return bool True if valid
     */
    public static function validateStringLength(string $value, int $min = null, int $max = null): bool {
        $length = strlen($value);
        
        if ($min !== null && $length < $min) {
            self::addError('string', "Value must be at least $min characters");
            return false;
        }
        
        if ($max !== null && $length > $max) {
            self::addError('string', "Value cannot exceed $max characters");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate regex pattern
     * 
     * @param string $value Value to validate
     * @param string $pattern Regex pattern
     * @return bool True if matches
     */
    public static function validatePattern(string $value, string $pattern): bool {
        if (!preg_match($pattern, $value)) {
            self::addError('pattern', 'Value does not match required pattern');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate URL
     * 
     * @param string $url URL to validate
     * @return bool True if valid
     */
    public static function validateUrl(string $url): bool {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            self::addError('url', 'Invalid URL format');
            return false;
        }
        
        return true;
    }
    
    /**
     * Add error message
     * 
     * @param string $field Field name
     * @param string $message Error message
     */
    private static function addError(string $field, string $message): void {
        self::$errors[$field] = $message;
    }
    
    /**
     * Get all errors
     * 
     * @return array Error messages
     */
    public static function getErrors(): array {
        return self::$errors;
    }
    
    /**
     * Get error for specific field
     * 
     * @param string $field Field name
     * @return string|null Error message
     */
    public static function getError(string $field): ?string {
        return self::$errors[$field] ?? null;
    }
    
    /**
     * Check if there are any errors
     * 
     * @return bool True if errors exist
     */
    public static function hasErrors(): bool {
        return !empty(self::$errors);
    }
    
    /**
     * Clear all errors
     */
    public static function clearErrors(): void {
        self::$errors = [];
    }
}

?>
