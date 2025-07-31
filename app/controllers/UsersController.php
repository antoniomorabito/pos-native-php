<?php
class UsersController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    public function index() {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        $data = [
            'title' => 'Users Management',
            'users' => $this->userModel->findAll([], 'full_name ASC')
        ];
        
        $this->view('users/index', $data);
    }
    
    public function add() {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        if ($this->isPost()) {
            $data = [
                'username' => $this->getPost('username'),
                'password' => $this->getPost('password'),
                'full_name' => $this->getPost('full_name'),
                'email' => $this->getPost('email'),
                'role' => $this->getPost('role'),
                'is_active' => $this->getPost('is_active') ? 1 : 0
            ];
            
            try {
                $this->userModel->createUser($data);
                $this->setFlash('success', 'User created successfully');
                $this->redirect('users');
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to create user: ' . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Add User'
        ];
        
        $this->view('users/add', $data);
    }
    
    public function edit($id) {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        $user = $this->userModel->findById($id);
        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect('users');
        }
        
        if ($this->isPost()) {
            $data = [
                'username' => $this->getPost('username'),
                'full_name' => $this->getPost('full_name'),
                'email' => $this->getPost('email'),
                'role' => $this->getPost('role'),
                'is_active' => $this->getPost('is_active') ? 1 : 0
            ];
            
            // Only update password if provided
            if ($this->getPost('password')) {
                $data['password'] = $this->getPost('password');
            }
            
            try {
                $this->userModel->updateUser($id, $data);
                $this->setFlash('success', 'User updated successfully');
                $this->redirect('users');
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to update user: ' . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Edit User',
            'user' => $user
        ];
        
        $this->view('users/edit', $data);
    }
    
    public function delete($id) {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        // Prevent deleting own account
        if ($id == $_SESSION['user_id']) {
            $this->setFlash('error', 'Cannot delete your own account');
            $this->redirect('users');
        }
        
        try {
            $this->userModel->delete($id);
            $this->setFlash('success', 'User deleted successfully');
        } catch (Exception $e) {
            $this->setFlash('error', 'Failed to delete user: ' . $e->getMessage());
        }
        
        $this->redirect('users');
    }
    
    public function toggle($id) {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        $user = $this->userModel->findById($id);
        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect('users');
        }
        
        // Prevent deactivating own account
        if ($id == $_SESSION['user_id']) {
            $this->setFlash('error', 'Cannot deactivate your own account');
            $this->redirect('users');
        }
        
        $newStatus = $user['is_active'] ? 0 : 1;
        
        try {
            $this->userModel->update($id, ['is_active' => $newStatus]);
            $statusText = $newStatus ? 'activated' : 'deactivated';
            $this->setFlash('success', "User $statusText successfully");
        } catch (Exception $e) {
            $this->setFlash('error', 'Failed to update user status');
        }
        
        $this->redirect('users');
    }
}