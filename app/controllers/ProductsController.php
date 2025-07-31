<?php
class ProductsController extends Controller {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }
    
    public function index() {
        $this->requireAuth();
        
        $data = [
            'title' => 'Products Management',
            'products' => $this->productModel->getAllWithCategory()
        ];
        
        $this->view('products/index', $data);
    }
    
    public function add() {
        $this->requireAuth();
        $this->requireRole(['admin', 'manager']);
        
        if ($this->isPost()) {
            $data = [
                'barcode' => $this->getPost('barcode'),
                'name' => $this->getPost('name'),
                'category_id' => $this->getPost('category_id'),
                'description' => $this->getPost('description'),
                'purchase_price' => $this->getPost('purchase_price'),
                'selling_price' => $this->getPost('selling_price'),
                'stock' => $this->getPost('stock'),
                'min_stock' => $this->getPost('min_stock'),
                'unit' => $this->getPost('unit')
            ];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = uploadImage($_FILES['image']);
                if ($imagePath) {
                    $data['image'] = $imagePath;
                } else {
                    $this->setFlash('warning', 'Product saved but image upload failed');
                }
            }
            
            try {
                $this->productModel->insert($data);
                $this->setFlash('success', 'Product added successfully');
                $this->redirect('products');
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to add product: ' . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Add Product',
            'categories' => $this->categoryModel->findAll()
        ];
        
        $this->view('products/add', $data);
    }
    
    public function edit($id) {
        $this->requireAuth();
        $this->requireRole(['admin', 'manager']);
        
        $product = $this->productModel->findById($id);
        if (!$product) {
            $this->setFlash('error', 'Product not found');
            $this->redirect('products');
        }
        
        if ($this->isPost()) {
            $data = [
                'barcode' => $this->getPost('barcode'),
                'name' => $this->getPost('name'),
                'category_id' => $this->getPost('category_id'),
                'description' => $this->getPost('description'),
                'purchase_price' => $this->getPost('purchase_price'),
                'selling_price' => $this->getPost('selling_price'),
                'stock' => $this->getPost('stock'),
                'min_stock' => $this->getPost('min_stock'),
                'unit' => $this->getPost('unit'),
                'is_active' => $this->getPost('is_active') ? 1 : 0
            ];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = uploadImage($_FILES['image']);
                if ($imagePath) {
                    // Delete old image
                    if ($product['image']) {
                        deleteImage($product['image']);
                    }
                    $data['image'] = $imagePath;
                } else {
                    $this->setFlash('warning', 'Product updated but image upload failed');
                }
            }
            
            try {
                $this->productModel->update($id, $data);
                $this->setFlash('success', 'Product updated successfully');
                $this->redirect('products');
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to update product: ' . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Edit Product',
            'product' => $product,
            'categories' => $this->categoryModel->findAll()
        ];
        
        $this->view('products/edit', $data);
    }
    
    public function delete($id) {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        $product = $this->productModel->findById($id);
        if (!$product) {
            $this->setFlash('error', 'Product not found');
            $this->redirect('products');
        }
        
        try {
            // Delete product image
            if ($product['image']) {
                deleteImage($product['image']);
            }
            
            $this->productModel->delete($id);
            $this->setFlash('success', 'Product deleted successfully');
        } catch (Exception $e) {
            $this->setFlash('error', 'Failed to delete product: ' . $e->getMessage());
        }
        
        $this->redirect('products');
    }
    
    public function search() {
        $this->requireAuth();
        
        if ($this->isAjax()) {
            $keyword = $this->getGet('q');
            $products = $this->productModel->searchProducts($keyword);
            $this->json(['products' => $products]);
        }
    }
    
    public function barcode($barcode) {
        $this->requireAuth();
        
        if ($this->isAjax()) {
            $product = $this->productModel->getByBarcode($barcode);
            if ($product) {
                $this->json(['success' => true, 'product' => $product]);
            } else {
                $this->json(['success' => false, 'message' => 'Product not found']);
            }
        }
    }
}