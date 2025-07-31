<?php
class CustomersController extends Controller {
    private $customerModel;
    
    public function __construct() {
        $this->customerModel = new CustomerModel();
    }
    
    public function index() {
        $this->requireAuth();
        
        $data = [
            'title' => 'Customers Management',
            'customers' => $this->customerModel->findAll([], 'name ASC')
        ];
        
        $this->view('customers/index', $data);
    }
    
    public function add() {
        $this->requireAuth();
        
        if ($this->isPost()) {
            $data = [
                'code' => $this->customerModel->generateCustomerCode(),
                'name' => $this->getPost('name'),
                'phone' => $this->getPost('phone'),
                'email' => $this->getPost('email'),
                'address' => $this->getPost('address'),
                'points' => 0
            ];
            
            try {
                $this->customerModel->insert($data);
                $this->setFlash('success', 'Customer added successfully');
                $this->redirect('customers');
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to add customer: ' . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Add Customer'
        ];
        
        $this->view('customers/add', $data);
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $customer = $this->customerModel->findById($id);
        if (!$customer) {
            $this->setFlash('error', 'Customer not found');
            $this->redirect('customers');
        }
        
        if ($this->isPost()) {
            $data = [
                'name' => $this->getPost('name'),
                'phone' => $this->getPost('phone'),
                'email' => $this->getPost('email'),
                'address' => $this->getPost('address')
            ];
            
            try {
                $this->customerModel->update($id, $data);
                $this->setFlash('success', 'Customer updated successfully');
                $this->redirect('customers');
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to update customer: ' . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Edit Customer',
            'customer' => $customer
        ];
        
        $this->view('customers/edit', $data);
    }
    
    public function delete($id) {
        $this->requireAuth();
        $this->requireRole(['admin', 'manager']);
        
        try {
            $this->customerModel->delete($id);
            $this->setFlash('success', 'Customer deleted successfully');
        } catch (Exception $e) {
            $this->setFlash('error', 'Failed to delete customer: ' . $e->getMessage());
        }
        
        $this->redirect('customers');
    }
    
    public function history($id) {
        $this->requireAuth();
        
        $customer = $this->customerModel->findById($id);
        if (!$customer) {
            $this->setFlash('error', 'Customer not found');
            $this->redirect('customers');
        }
        
        $data = [
            'title' => 'Customer History',
            'customer' => $customer,
            'history' => $this->customerModel->getCustomerHistory($id)
        ];
        
        $this->view('customers/history', $data);
    }
    
    public function search() {
        $this->requireAuth();
        
        if ($this->isAjax()) {
            $keyword = $this->getGet('q');
            $customers = $this->customerModel->searchCustomers($keyword);
            $this->json(['customers' => $customers]);
        }
    }
    
    public function points($id) {
        $this->requireAuth();
        $this->requireRole(['admin', 'manager']);
        
        $customer = $this->customerModel->findById($id);
        if (!$customer) {
            $this->setFlash('error', 'Customer not found');
            $this->redirect('customers');
        }
        
        if ($this->isPost()) {
            $points = (int)$this->getPost('points');
            $type = $this->getPost('type'); // 'add' or 'subtract'
            $notes = $this->getPost('notes');
            
            try {
                $this->customerModel->updatePoints($id, $points, $type);
                
                $actionText = $type === 'add' ? 'added' : 'deducted';
                $this->setFlash('success', "Points $actionText successfully");
                $this->redirect('customers');
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to update points: ' . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Manage Points',
            'customer' => $customer
        ];
        
        $this->view('customers/points', $data);
    }
}