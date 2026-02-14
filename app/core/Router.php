<?php

/* ============================================
   app/core/Router.php
   Enrutador personalizado - Front Controller
   Mapea URLs a controladores y métodos
   ============================================ */

class Router {
    // Tabla de rutas registradas
    private $routes = [];

    /**
     * Add a route to the routing table
     * @param string $route The route URL
     * @param string $controller The controller class name
     * @param string $method The method name in the controller
     * @param string|null $middleware Optional middleware
     */
    public function add($route, $controller, $method, $middleware = null) {
        $this->routes[$route] = [
            'controller' => $controller,
            'method' => $method,
            'middleware' => $middleware
        ];
    }

    /**
     * Dispatch the request to the corresponding controller
     * @param string $uri The requested URI
     */
    public function dispatch($uri) {
        // Simple URI cleaning
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        
        // Remove project folder from URI if present
        $urlParts = parse_url(URLROOT);
        $projectFolder = isset($urlParts['path']) ? trim($urlParts['path'], '/') : '';
        if ($projectFolder && strpos($uri, $projectFolder) === 0) {
            $uri = trim(substr($uri, strlen($projectFolder)), '/');
        }

        // Remove 'public' from URI if present
        if (strpos($uri, 'public') === 0) {
            $uri = trim(substr($uri, strlen('public')), '/');
        }

        // Check if route exists (Exact match)
        if (array_key_exists($uri, $this->routes)) {
            $route = $this->routes[$uri];
            $this->executeRoute($route);
        } else {
                // Check for dynamic routes (e.g., productos/editar/5 or login/resetear/abc123)
                $found = false;
                foreach ($this->routes as $routeKey => $routeVal) {
                    $parts = explode('/', $uri);
                    if (count($parts) > 1) {
                        $lastPart = array_pop($parts);
                        $baseUri = implode('/', $parts);
                        
                        if (array_key_exists($baseUri, $this->routes)) {
                            $route = $this->routes[$baseUri];
                            $route['params'] = [$lastPart];
                            $this->executeRoute($route);
                            $found = true;
                            break;
                        }
                    }
                }

            if (!$found) {
                // 404 Not Found
                http_response_code(404);
                echo "<h1>404 - Not Found</h1>";
                echo "<p>Requested URI: <strong>$uri</strong></p>";
                echo "<p>Available routes:</p><ul>";
                foreach (array_keys($this->routes) as $route) {
                    echo "<li>$route</li>";
                }
                echo "</ul>";
            }
        }
    }

     // ------------------------------------------
    // Ejecutar controlador y método de la ruta
    // @param array $route - Datos de la ruta
    // ------------------------------------------
    
    private function executeRoute($route) {
        $controllerName = $route['controller'];
        $methodName = $route['method'];
        $params = $route['params'] ?? [];

        // Require the controller file
        $controllerFile = APPROOT . '/app/controllers/' . $controllerName . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            // Check if middleware is required
            if ($route['middleware']) {
                AuthMiddleware::handle($route['middleware']);
            }

            // Instantiate and call the method
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $methodName)) {
                    call_user_func_array([$controller, $methodName], $params);
                } else {
                    die("Method $methodName not found in controller $controllerName");
                }
            } else {
                die("Controller class $controllerName not found");
            }
        } else {
            die("Controller file not found: $controllerFile");
        }
    }
}
