<?php
/**
 * Router
 * Simple URL routing for the application
 */

class Router {
    private $routes = [];
    private $auth;
    private $session;

    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->session = Session::getInstance();
    }

    /**
     * Add a GET route
     */
    public function get($path, $handler, $middleware = []) {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    /**
     * Add a POST route
     */
    public function post($path, $handler, $middleware = []) {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    /**
     * Add a route (both GET and POST)
     */
    public function any($path, $handler, $middleware = []) {
        $this->addRoute('ANY', $path, $handler, $middleware);
    }

    /**
     * Add a route
     */
    private function addRoute($method, $path, $handler, $middleware = []) {
        $this->routes[] = [
            'method'      => $method,
            'path'        => $path,
            'handler'     => $handler,
            'middleware'  => $middleware
        ];
    }

    /**
     * Dispatch the current request
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path if running in subdirectory
        $basePath = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');
        if ($basePath && strpos($requestPath, $basePath) === 0) {
            $requestPath = substr($requestPath, strlen($basePath));
        }

        $requestPath = '/' . ltrim($requestPath, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== 'ANY' && $route['method'] !== $method) {
                continue;
            }

            // Convert route pattern to regex
            $pattern = preg_replace('#\{[^}]+\}#', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $requestPath, $matches)) {
                // Extract parameters
                $params = [];
                if (preg_match_all('#\{([^}]+)\}#', $route['path'], $paramNames)) {
                    foreach ($paramNames[1] as $index => $name) {
                        $params[$name] = $matches[$index + 1];
                    }
                }

                // Run middleware
                foreach ($route['middleware'] as $middlewareName) {
                    if (!$this->runMiddleware($middlewareName)) {
                        return; // Middleware handled the response
                    }
                }

                // Execute handler
                $this->executeHandler($route['handler'], $params);
                return;
            }
        }

        // No route found - 404
        $this->handle404();
    }

    /**
     * Run middleware
     */
    private function runMiddleware($name) {
        switch ($name) {
            case 'auth':
                if (!$this->auth->check()) {
                    $this->session->flash('error', 'Please log in to continue.');
                    redirect('/login');
                    return false;
                }
                break;
            case 'guest':
                if ($this->auth->check()) {
                    redirect('/dashboard');
                    return false;
                }
                break;
            case 'admin':
                if (!$this->auth->check()) {
                    $this->session->flash('error', 'Please log in to continue.');
                    redirect('/login');
                    return false;
                }
                if (!$this->auth->hasRole('admin')) {
                    $this->session->flash('error', 'Access denied.');
                    redirect('/dashboard');
                    return false;
                }
                break;
            case 'manager':
                if (!$this->auth->check()) {
                    $this->session->flash('error', 'Please log in to continue.');
                    redirect('/login');
                    return false;
                }
                if (!$this->auth->hasRole('manager')) {
                    $this->session->flash('error', 'Access denied.');
                    redirect('/dashboard');
                    return false;
                }
                break;
        }
        return true;
    }

    /**
     * Execute handler
     */
    private function executeHandler($handler, $params = []) {
        if (is_callable($handler)) {
            call_user_func($handler, $params);
            return;
        }

        if (is_string($handler)) {
            $parts = explode('@', $handler);
            if (count($parts) === 2) {
                $controllerName = $parts[0];
                $methodName = $parts[1];

                // Load controller file
                $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                }

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $methodName)) {
                        call_user_func_array([$controller, $methodName], $params);
                        return;
                    }
                }
            }
        }

        $this->handle404();
    }

    /**
     * Handle 404
     */
    private function handle404() {
        http_response_code(404);
        if (APP_DEBUG) {
            echo '<h1>404 - Page Not Found</h1>';
            echo '<p>The requested URL was not found on this server.</p>';
        } else {
            echo '<h1>404 - Page Not Found</h1>';
            echo '<p><a href="/">Go to homepage</a></p>';
        }
    }
}
