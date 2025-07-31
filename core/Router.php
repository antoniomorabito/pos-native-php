<?php
class Router {
    private $routes = [];
    private $controller = 'DashboardController';
    private $method = 'index';
    private $params = [];
    
    public function __construct() {
        $url = $this->parseUrl();
        
        // If no URL, load dashboard
        if (empty($url)) {
            $url = ['dashboard'];
        }
        
        // Check if controller exists
        $controllerName = ucfirst($url[0]) . 'Controller';
        $controllerFile = CONTROLLER_PATH . $controllerName . '.php';
        
        if (file_exists($controllerFile)) {
            $this->controller = $controllerName;
            unset($url[0]);
        }
        
        require_once CONTROLLER_PATH . $this->controller . '.php';
        $this->controller = new $this->controller;
        
        // Check if method exists and is public
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $reflectionMethod = new ReflectionMethod($this->controller, $url[1]);
                if ($reflectionMethod->isPublic()) {
                    $this->method = $url[1];
                    unset($url[1]);
                }
            }
        }
        
        // Get params
        $this->params = $url ? array_values($url) : [];
        
        // Call the controller method
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}