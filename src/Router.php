<?php

declare(strict_types=1);

namespace App;

use App\Container;

/**
 * Simple URL router that maps HTTP requests to controller actions.
 */
class Router {

    /**
     * @var array $routes Registered routes grouped by HTTP method
     */
    private array $routes = [];
    
    private Container $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * Register a GET route.
     *
     * @param string $uri URL pattern, supports {param} placeholders
     * @param array $action Array containing controller class name and method
     * @return void
     */
    public function get(string $uri, array $action): void {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Register a POST route.
     *
     * @param string $uri URL pattern, supports {param} placeholders
     * @param array $action Array containing controller class name and method
     * @return void
     */
    public function post(string $uri, array $action): void {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Match current request to a registered route and call the controller.
     * Returns 404 if no route matches.
     *
     * @return void
     */
    public function dispatch(): void {       
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach($this->routes[$method] as $route => $action) {
            $pattern = preg_replace('/\{[a-z]+\}/', '(\d+)', $route);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                [$controllerClass, $method] = $action;

                $controller = $this->container->make($controllerClass);
                $controller->$method(...array_map('intval', $matches));
                return;
            }
        
        }

        http_response_code(404);
        require_once __DIR__ . '/Views/404.php';
    } 
}