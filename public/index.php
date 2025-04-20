<?php
declare(strict_types=1);

/**
 * Application entry point
 * 
 * Handles all incoming HTTP requests, sets CORS headers,
 * loads dependencies and routes the request to the appropriate controller.
 */

// Set CORS headers for cross-origin requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Log incoming requests for debugging
error_log("Request received: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);

// Import required dependencies
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/Models/Service.php');
require_once(__DIR__ . '/../app/Models/AboutUs.php');
require_once(__DIR__ . '/../app/Controllers/ServiceController.php');
require_once(__DIR__ . '/../app/Controllers/AboutUsController.php');
require_once(__DIR__ . '/../app/Services/ServiceService.php');
require_once(__DIR__ . '/../app/Services/AboutUsService.php');
require_once(__DIR__ . '/../app/Repositories/ServiceRepository.php');
require_once(__DIR__ . '/../app/Repositories/AboutUsRepository.php');
require_once(__DIR__ . '/../app/Utils/Response.php');
require_once(__DIR__ . '/../app/Core/Router.php');
require_once(__DIR__ . '/../app/Utils/Logger.php');

use App\Utils\Response;
use App\Utils\Logger;

// Extract request method and URI from request
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Validate the extracted values
if (!is_string($method)) {
    Logger::error('Invalid request method');
    Response::json(['error' => 'Invalid request method'], Response::HTTP_BAD_REQUEST);
    exit;
}

if (!is_string($uri)) {
    Logger::error('Invalid request URI');
    Response::json(['error' => 'Invalid request URI'], Response::HTTP_BAD_REQUEST);
    exit;
}

try {
    // Load API routes
    require_once(__DIR__ . '/../routes/api.php');
} catch (\Throwable $e) {
    // Log the error
    Logger::error('Unhandled exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    
    // Return a generic error response
    Response::json(
        ['error' => 'Internal server error', 'message' => 'An unexpected error occurred'],
        Response::HTTP_INTERNAL_SERVER_ERROR
    );
}
