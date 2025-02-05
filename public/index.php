<?php

/**
 * Front Controller
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Error and Exception handling
$errorHandler = new App\Core\ErrorHandler();
set_error_handler([$errorHandler, 'errorHandler']);
set_exception_handler([$errorHandler, 'exceptionHandler']);

// Routing
$router = new App\Core\Router();

// Add routes
$router->add('GET', '', ['controller' => 'Shopping', 'action' => 'index']);
$router->add('POST', 'create', ['controller' => 'Shopping', 'action' => 'create']);
$router->add('POST', 'items/{id:\d+}/update', ['controller' => 'Shopping', 'action' => 'update']);
$router->add('POST', 'items/{id:\d+}/toggle', ['controller' => 'Shopping', 'action' => 'toggle']);
$router->add('POST', 'items/{id:\d+}/delete', ['controller' => 'Shopping', 'action' => 'delete']);

// Get the URL from REQUEST_URI for built-in PHP server
$url = $_SERVER['REQUEST_URI'] ?? '';

// Remove query string and trailing slash
$url = parse_url($url, PHP_URL_PATH);
$url = rtrim($url, '/');

try {
    $router->dispatch($url, $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    $errorHandler->exceptionHandler($e);
}
