<?php
class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    public function login() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        
        if ($this->isPost()) {
            $username = $this->getPost('username');
            $password = $this->getPost('password');
            
            $user = $this->userModel->authenticate($username, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];
                
                $this->setFlash('success', 'Welcome back, ' . $user['full_name'] . '!');
                $this->redirect('dashboard');
            } else {
                $this->setFlash('error', 'Invalid username or password');
            }
        }
        
        $this->view('auth/login');
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
    
    public function profile() {
        $this->requireAuth();
        
        $user = $this->userModel->getUserWithRole($_SESSION['user_id']);
        
        if ($this->isPost()) {
            $data = [
                'full_name' => $this->getPost('full_name'),
                'email' => $this->getPost('email')
            ];
            
            if ($this->getPost('password')) {
                $data['password'] = $this->getPost('password');
            }
            
            $this->userModel->updateUser($_SESSION['user_id'], $data);
            $_SESSION['full_name'] = $data['full_name'];
            
            $this->setFlash('success', 'Profile updated successfully');
            $this->redirect('auth/profile');
        }
        
        $this->view('auth/profile', ['user' => $user]);
    }
}