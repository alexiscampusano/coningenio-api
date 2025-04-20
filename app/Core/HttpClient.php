<?php
declare(strict_types=1);
namespace App\Core;

/**
 * Simple HTTP client for fetching external API data using Bearer token.
 * 
 * Handles API requests with proper authentication and error handling.
 */
class HttpClient
{
    /**
     * Base URL for the external API
     * 
     * @var string
     */
    private string $baseUrl;
    
    /**
     * Authentication token for API access
     * 
     * @var string
     */
    private string $token;

    /**
     * Initialize client with API endpoint and authentication
     *
     * @param string $baseUrl Base URL of the external API
     * @param string $token Authentication token
     */
    public function __construct(string $baseUrl, string $token)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->token = $token;
    }

    /**
     * Perform GET request to specified API endpoint
     *
     * @param string $endpoint API endpoint path
     * @return array<string, mixed> Decoded JSON response
     * @throws \Exception When request fails
     */
    public function get(string $endpoint): array
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Authorization: Bearer {$this->token}"
            ]
        ];

        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new \Exception("Failed to fetch from external API: $url");
        }

        $data = json_decode($response, true);
        
        // Asegurar que siempre devolvemos un array
        if (!is_array($data)) {
            throw new \Exception("Invalid response format from API: expected JSON array");
        }

        return $data;
    }
}
