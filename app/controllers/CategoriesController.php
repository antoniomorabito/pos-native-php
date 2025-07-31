<?php
class CategoriesController extends Controller {
    private $categoryModel;
    
    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }
    
    public function index() {
        $this->requireAuth();
        $this->requireRole(['admin', 'manager']);
        
        if ($this->isPost()) {
            $action = $this->getPost('action');
            
            if ($action == 'add') {
                $data = [
                    'name' => $this->getPost('name'),
                    'description' => $this->getPost('description')
                ];
                
                try {
                    $this->categoryModel->insert($data);
                    $this->setFlash('success', 'Category added successfully');
                } catch (Exception $e) {
                    $this->setFlash('error', 'Failed to add category');
                }
            } elseif ($action == 'edit') {
                $id = $this->getPost('id');
                $data = [
                    'name' => $this->getPost('name'),
                    'description' => $this->getPost('description')
                ];
                
                try {
                    $this->categoryModel->update($id, $data);
                    $this->setFlash('success', 'Category updated successfully');
                } catch (Exception $e) {
                    $this->setFlash('error', 'Failed to update category');
                }
            }
            
            $this->redirect('categories');
        }
        
        $data = [
            'title' => 'Categories Management',
            'categories' => $this->categoryModel->getCategoriesWithProductCount()
        ];
        
        $this->view('categories/index', $data);
    }
    
    public function delete($id) {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        // Check if category has products
        $productCount = $this->categoryModel->getProductCount($id);
        if ($productCount > 0) {
            $this->setFlash('error', 'Cannot delete category with products');
        } else {
            try {
                $this->categoryModel->delete($id);
                $this->setFlash('success', 'Category deleted successfully');
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to delete category');
            }
        }
        
        $this->redirect('categories');
    }
}