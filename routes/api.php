<?php
declare(strict_types=1);

require_once(__DIR__ . '/../app/Core/Router.php');

use App\Core\Router;
use App\Controllers\ServiceController;
use App\Controllers\AboutUsController;
use App\Utils\Response;
use App\Utils\Logger;

/**
 * API Routes Definition
 * 
 * Configures all API routes and their handlers. Routes are grouped by resource
 * and version to maintain a clean and organized API structure.
 */

// Validate input variables
if (!isset($method) || !is_string($method)) {
    Logger::error('Method variable not properly set in router');
    Response::json(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
    exit;
}

if (!isset($uri) || !is_string($uri)) {
    Logger::error('URI variable not properly set in router');
    Response::json(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
    exit;
}

// Initialize controllers
$serviceController = new ServiceController();
$aboutUsController = new AboutUsController();

// Create router
$router = new Router();

// Debug log
Logger::debug("Processing request: $method $uri");

// Define routes
$router->group('/api/v1', function(Router $router) use ($serviceController, $aboutUsController): void {
    // Service routes
    $router->get('/services', [$serviceController, 'index']);
    $router->get('/services/{id}', [$serviceController, 'show']);
    $router->post('/services', [$serviceController, 'store']);
    $router->put('/services/{id}', [$serviceController, 'update']);
    $router->delete('/services/{id}', [$serviceController, 'destroy']);
    
    // About Us routes - note the specific route comes before the general route
    $router->get('/about-us', [$aboutUsController, 'index']);
    $router->get('/about-us/type/{type}', [$aboutUsController, 'getByType']);  // Specific route first!
    $router->get('/about-us/{id}', [$aboutUsController, 'show']);              // General route second
    $router->post('/about-us', [$aboutUsController, 'store']);
    $router->put('/about-us/{id}', [$aboutUsController, 'update']);
    $router->delete('/about-us/{id}', [$aboutUsController, 'destroy']);
});

// Dispatch request to appropriate handler
if (!$router->dispatch($method, $uri)) {
    Logger::warning("No route found for: $method $uri");
    Response::json(['error' => 'Route not found'], Response::HTTP_NOT_FOUND);
}
