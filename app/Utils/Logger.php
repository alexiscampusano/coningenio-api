<?php
declare(strict_types=1);
namespace App\Utils;

/**
 * Simple logging utility for application messages
 * 
 * Provides methods for logging different types of messages
 * to application log files.
 */
class Logger
{
    /**
     * Log levels
     */
    public const LEVEL_ERROR = 'ERROR';
    public const LEVEL_WARNING = 'WARNING';
    public const LEVEL_INFO = 'INFO';
    public const LEVEL_DEBUG = 'DEBUG';
    
    /**
     * Base path for log files
     * @var string
     */
    private static string $basePath = __DIR__ . '/../../storage/logs';
    
    /**
     * Log an error message
     *
     * @param string $message Error message to log
     * @return void
     */
    public static function error(string $message): void
    {
        self::log(self::LEVEL_ERROR, $message);
    }
    
    /**
     * Log a warning message
     *
     * @param string $message Warning message to log
     * @return void
     */
    public static function warning(string $message): void
    {
        self::log(self::LEVEL_WARNING, $message);
    }
    
    /**
     * Log an informational message
     *
     * @param string $message Info message to log
     * @return void
     */
    public static function info(string $message): void
    {
        self::log(self::LEVEL_INFO, $message);
    }
    
    /**
     * Log a debug message
     *
     * @param string $message Debug message to log
     * @return void
     */
    public static function debug(string $message): void
    {
        self::log(self::LEVEL_DEBUG, $message);
    }
    
    /**
     * Internal method to handle logging
     *
     * @param string $level Log level
     * @param string $message Message to log
     * @return void
     */
    private static function log(string $level, string $message): void
    {
        $logDir = self::$basePath;
        
        // Ensure log directory exists
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        $file = $logDir . '/app.log';
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = sprintf("[%s] %s: %s%s", $timestamp, $level, $message, PHP_EOL);
        
        @file_put_contents($file, $formattedMessage, FILE_APPEND);
    }
}
