<?php
declare(strict_types=1);
namespace App\Utils;

/**
 * Utility class for API responses.
 * 
 * Provides methods for sending standardized HTTP responses
 * in different formats with appropriate headers.
 */
class Response
{
    /**
     * Common HTTP status codes
     */
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    
    /**
     * Send a JSON response.
     *
     * @param array<string, mixed> $data Response data.
     * @param int $status HTTP status code.
     * @return string JSON-encoded string (although function exits)
     */
    public static function json(array $data, int $status = self::HTTP_OK): string
    {
        http_response_code($status);
        header('Content-Type: application/json');
        
        // Convert data to JSON
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // Check for JSON encoding errors
        if ($json === false) {
            // Log the error
            error_log('JSON encoding error: ' . json_last_error_msg());
            
            // Send a fallback response
            http_response_code(self::HTTP_INTERNAL_SERVER_ERROR);
            $json = json_encode([
                'error' => 'Error encoding response',
                'details' => json_last_error_msg()
            ]);
        }
        
        echo $json;
        return $json;
    }
    
    /**
     * Send a text response.
     *
     * @param string $content Text content to send
     * @param int $status HTTP status code
     * @return string Content sent (although function exits)
     */
    public static function text(string $content, int $status = self::HTTP_OK): string
    {
        http_response_code($status);
        header('Content-Type: text/plain; charset=UTF-8');
        echo $content;
        return $content;
    }
}
