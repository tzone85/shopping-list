<?php

namespace App\Core;

/**
 * Router Class
 * 
 * Handles all routing functionality for the framework
 * 
 * @package App\Core
 */
class Router
{
    /**
     * @var array Stores all registered routes
     */
    private array $routes = [];
    
    /**
     * @var array Stores all middleware
     */
    private array $middleware = [];

    /**
     * Add a route to the routing table
     * 
     * @param string $method  The HTTP method
     * @param string $route   The route URL
     * @param array  $params  Controller and action
     * @return void
     */
    public function add(string $method, string $route, array $params = []): void
    {
        // Remove leading slash if present
        $route = ltrim($route, '/');
        
        // Convert route parameters
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        
        // Add start and end markers
        $route = '/^' . str_replace('/', '\/', $route) . '$/i';
        
        $this->routes[$method][$route] = $params;
    }

    /**
     * Match the route to the routes in the routing table
     * 
     * @param string $url     The route URL
     * @param string $method  The HTTP method
     * @return array|false
     */
    public function match(string $url, string $method)
    {
        error_log("Trying to match URL: '$url' with method: $method");
        error_log("Available routes for $method: " . print_r($this->routes[$method] ?? [], true));
        
        foreach ($this->routes[$method] ?? [] as $route => $params) {
            error_log("Checking route pattern: $route");
            if (preg_match($route, $url, $matches)) {
                error_log("Route matched! Params: " . print_r($params, true));
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                return $params;
            }
        }
        error_log("No route matched for: $url");
        return false;
    }

    /**
     * Dispatch the route and create the controller object and execute the action
     * 
     * @param string $url     The route URL
     * @param string $method  The HTTP method
     * @return void
     * @throws \Exception
     */
    public function dispatch(string $url, string $method): void
    {
        error_log("Dispatching URL: $url");
        // Remove leading slash and query string
        $url = ltrim($this->removeQueryStringVariables($url), '/');
        error_log("Cleaned URL for matching: $url");
        
        if ($params = $this->match($url, $method)) {
            $controller = $params['controller'];
            error_log("Original controller name: " . $controller);
            
            $controller = $this->convertToStudlyCaps($controller);
            error_log("After StudlyCaps: " . $controller);
            
            $controller = $this->getNamespace() . $controller . 'Controller';
            error_log("Final controller class: " . $controller);

            if (class_exists($controller)) {
                $controller_object = new $controller($params);
                
                $action = $params['action'];
                $action = $this->convertToCamelCase($action) . 'Action';
                
                if (method_exists($controller_object, $action)) {
                    // Don't pass params as arguments, they're already available in $route_params
                    call_user_func([$controller_object, $action]);
                } else {
                    throw new \Exception("Method $action in controller $controller not found");
                }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            throw new \Exception('No route matched.', 404);
        }
    }

    /**
     * Convert string with hyphens to StudlyCaps
     * e.g. post-authors => PostAuthors
     * 
     * @param string $string The string to convert
     * @return string
     */
    private function convertToStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert string with hyphens to camelCase
     * e.g. add-new => addNew
     * 
     * @param string $string The string to convert
     * @return string
     */
    private function convertToCamelCase(string $string): string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL
     * 
     * @param string $url The full URL
     * @return string The URL with the query string variables removed
     */
    private function removeQueryStringVariables(string $url): string
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }

    /**
     * Get the namespace for the controller class
     * 
     * @return string The request URL
     */
    private function getNamespace(): string
    {
        $namespace = 'App\Controllers\\';
        return $namespace;
    }
}
