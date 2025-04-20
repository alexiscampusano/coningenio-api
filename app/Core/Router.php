<?php
declare(strict_types=1);
namespace App\Core;

/**
 * Simple Router for API endpoints
 * 
 * Provides a clean, declarative way to define routes
 */
class Router
{
    /**
     * Collection of routes
     * @var array<int, array{
     *     method: string,
     *     path: string,
     *     pattern: string,
     *     handler: callable,
     *     params: array<int, string>
     * }>
     */
    private array $routes = [];
    
    /**
     * Current route group prefix
     * @var string
     */
    private string $prefix = '';
    
    /**
     * Register a GET route
     *
     * @param string $path Route path with optional {parameters}
     * @param callable $handler Handler function or [controller, method]
     * @return self For method chaining
     */
    public function get(string $path, callable $handler): self
    {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }
    
    /**
     * Register a POST route
     *
     * @param string $path Route path with optional {parameters}
     * @param callable $handler Handler function or [controller, method]
     * @return self For method chaining
     */
    public function post(string $path, callable $handler): self
    {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }
    
    /**
     * Register a PUT route
     *
     * @param string $path Route path with optional {parameters}
     * @param callable $handler Handler function or [controller, method]
     * @return self For method chaining
     */
    public function put(string $path, callable $handler): self
    {
        $this->addRoute('PUT', $path, $handler);
        return $this;
    }
    
    /**
     * Register a DELETE route
     *
     * @param string $path Route path with optional {parameters}
     * @param callable $handler Handler function or [controller, method]
     * @return self For method chaining
     */
    public function delete(string $path, callable $handler): self
    {
        $this->addRoute('DELETE', $path, $handler);
        return $this;
    }
    
    /**
     * Group routes with a common prefix
     *
     * @param string $prefix The URL prefix for all routes in the group
     * @param callable $callback Function that defines grouped routes
     * @return self For method chaining
     */
    public function group(string $prefix, callable $callback): self
    {
        // Save current prefix
        $previousPrefix = $this->prefix;
        
        // Apply new prefix
        $this->prefix .= $prefix;
        
        // Define routes in the group
        $callback($this);
        
        // Restore previous prefix
        $this->prefix = $previousPrefix;
        
        return $this;
    }
    
    /**
     * Add route to collection
     * 
     * @param string $method HTTP method
     * @param string $path Route path
     * @param callable $handler Route handler
     * @return void
     */
    private function addRoute(string $method, string $path, callable $handler): void
    {
        // Build full path with current prefix
        $fullPath = $this->prefix . $path;
        
        // Extract parameter names like {id} from the path
        preg_match_all('/{([a-zA-Z0-9_]+)}/', $fullPath, $matches);
        $paramNames = $matches[1] ?? [];
        
        // Convert path to regex pattern
        $pattern = preg_replace('/{([a-zA-Z0-9_]+)}/', '([^/]+)', $fullPath);
        
        // Add route to collection
        $this->routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'pattern' => "#^$pattern$#",
            'handler' => $handler,
            'params' => $paramNames
        ];
    }
    
    /**
     * Match and execute route handler for the current request
     *
     * @param string $method HTTP method
     * @param string $uri Request URI
     * @return bool True if route was matched and executed
     */
    public function dispatch(string $method, string $uri): bool
    {
        // Debug log
        error_log("Router: Dispatching $method $uri");
        
        foreach ($this->routes as $route) {
            // Skip if method doesn't match
            if ($route['method'] !== $method) {
                continue;
            }
            
            // Try to match URI against route pattern
            if (preg_match($route['pattern'], $uri, $matches)) {
                // First match is the full string
                array_shift($matches);
                
                // Extract parameters
                /** @var array<int, string|int> $params */
                $params = [];
                foreach ($matches as $match) {
                    // Convert numeric strings to integers
                    if (is_numeric($match) && strpos($match, '.') === false) {
                        $params[] = (int)$match;
                    } else {
                        $params[] = $match;
                    }
                }
                
                // Execute the handler with parameters
                call_user_func_array($route['handler'], $params);
                return true;
            }
        }
        
        return false;
    }
}