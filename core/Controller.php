<?php
abstract class Controller {
    
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = VIEW_PATH . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View file not found: $view");
        }
    }
    
    protected function redirect($url) {
        if (strpos($url, 'http') !== 0) {
            $url = BASE_URL . $url;
        }
        header("Location: $url");
        exit();
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
    
    protected function getGet($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
    }
    
    protected function requireRole($roles) {
        $this->requireAuth();
        
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        if (!in_array($_SESSION['user_role'], $roles)) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard');
        }
    }
}