<?php
/**
 * Logger Utility Class
 * 
 * Handles all logging functionality
 */

class Logger {
    
    // Log levels
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_CRITICAL = 'CRITICAL';
    
    // Log file path
    private static $logPath = null;
    
    /**
     * Set log file path
     * 
     * @param string $path Log file path
     */
    public static function setLogPath(string $path): void {
        self::$logPath = $path;
    }
    
    /**
     * Log debug message
     * 
     * @param string $message Message to log
     * @param array $context Additional context
     */
    public static function debug(string $message, array $context = []): void {
        self::log(self::LEVEL_DEBUG, $message, $context);
    }
    
    /**
     * Log info message
     * 
     * @param string $message Message to log
     * @param array $context Additional context
     */
    public static function info(string $message, array $context = []): void {
        self::log(self::LEVEL_INFO, $message, $context);
    }
    
    /**
     * Log warning message
     * 
     * @param string $message Message to log
     * @param array $context Additional context
     */
    public static function warning(string $message, array $context = []): void {
        self::log(self::LEVEL_WARNING, $message, $context);
    }
    
    /**
     * Log error message
     * 
     * @param string $message Message to log
     * @param array $context Additional context
     */
    public static function error(string $message, array $context = []): void {
        self::log(self::LEVEL_ERROR, $message, $context);
    }
    
    /**
     * Log critical message
     * 
     * @param string $message Message to log
     * @param array $context Additional context
     */
    public static function critical(string $message, array $context = []): void {
        self::log(self::LEVEL_CRITICAL, $message, $context);
    }
    
    /**
     * Main logging function
     * 
     * @param string $level Log level
     * @param string $message Message to log
     * @param array $context Additional context
     */
    private static function log(string $level, string $message, array $context = []): void {
        // Format log message
        $logMessage = self::formatMessage($level, $message, $context);
        
        // Write to log file
        self::writeToFile($logMessage);
        
        // Write to error log
        error_log($logMessage);
    }
    
    /**
     * Format log message
     * 
     * @param string $level Log level
     * @param string $message Message
     * @param array $context Context data
     * @return string Formatted message
     */
    private static function formatMessage(string $level, string $message, array $context = []): string {
        // Build context string
        $contextStr = '';
        if (!empty($context)) {
            $contextStr = ' | ' . json_encode($context);
        }
        
        // Build log message
        return sprintf(
            '[%s] [%s] %s%s',
            date('Y-m-d H:i:s'),
            $level,
            $message,
            $contextStr
        );
    }
    
    /**
     * Write message to log file
     * 
     * @param string $message Message to write
     */
    private static function writeToFile(string $message): void {
        $logFile = self::$logPath ?? getLogPath('app.log');
        
        // Ensure directory exists
        $dir = dirname($logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Write to file
        file_put_contents($logFile, $message . PHP_EOL, FILE_APPEND);
    }
    
    /**
     * Clear log file
     */
    public static function clear(): void {
        $logFile = self::$logPath ?? getLogPath('app.log');
        if (file_exists($logFile)) {
            unlink($logFile);
        }
    }
    
    /**
     * Get log file size
     * 
     * @return int File size in bytes
     */
    public static function getSize(): int {
        $logFile = self::$logPath ?? getLogPath('app.log');
        return file_exists($logFile) ? filesize($logFile) : 0;
    }
    
    /**
     * Read log file contents
     * 
     * @param int $lines Number of lines to read (0 = all)
     * @return array Log lines
     */
    public static function read(int $lines = 0): array {
        $logFile = self::$logPath ?? getLogPath('app.log');
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $allLines = file($logFile, FILE_IGNORE_NEW_LINES);
        
        if ($lines <= 0) {
            return $allLines;
        }
        
        return array_slice($allLines, -$lines);
    }
}

?>
