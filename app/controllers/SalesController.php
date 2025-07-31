<?php
class SalesController extends Controller {
    private $saleModel;
    private $productModel;
    private $customerModel;
    private $categoryModel;
    
    public function __construct() {
        $this->saleModel = new SaleModel();
        $this->productModel = new ProductModel();
        $this->customerModel = new CustomerModel();
        $this->categoryModel = new CategoryModel();
    }
    
    public function index() {
        $this->requireAuth();
        
        $data = [
            'title' => 'Point of Sale',
            'products' => $this->productModel->getProductsForPOS(),
            'categories' => $this->categoryModel->findAll(),
            'customers' => $this->customerModel->findAll([], 'name ASC')
        ];
        
        $this->view('sales/pos', $data);
    }
    
    public function history() {
        $this->requireAuth();
        
        $data = [
            'title' => 'Sales History',
            'sales' => $this->saleModel->getRecentSales(100)
        ];
        
        $this->view('sales/history', $data);
    }
    
    public function checkout() {
        $this->requireAuth();
        
        if ($this->isPost() && $this->isAjax()) {
            $items = json_decode($this->getPost('items'), true);
            $customerId = $this->getPost('customer_id');
            $paymentMethod = $this->getPost('payment_method');
            $paidAmount = $this->getPost('paid_amount');
            $discountPercent = $this->getPost('discount_percent', 0);
            $notes = $this->getPost('notes');
            
            // Calculate totals
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $discountAmount = $subtotal * ($discountPercent / 100);
            $taxPercent = 11; // Get from settings
            $taxAmount = ($subtotal - $discountAmount) * ($taxPercent / 100);
            $total = $subtotal - $discountAmount + $taxAmount;
            $changeAmount = $paidAmount - $total;
            
            // Prepare sale data
            $saleData = [
                'invoice_number' => $this->saleModel->generateInvoiceNumber(),
                'customer_id' => $customerId ?: null,
                'user_id' => $_SESSION['user_id'],
                'subtotal' => $subtotal,
                'discount_percent' => $discountPercent,
                'discount_amount' => $discountAmount,
                'tax_percent' => $taxPercent,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'notes' => $notes
            ];
            
            try {
                $saleId = $this->saleModel->createSale($saleData, $items);
                $this->json([
                    'success' => true,
                    'sale_id' => $saleId,
                    'invoice_number' => $saleData['invoice_number']
                ]);
            } catch (Exception $e) {
                $this->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }
    
    public function detail($id) {
        $this->requireAuth();
        
        $sale = $this->saleModel->getSaleWithDetails($id);
        if (!$sale) {
            $this->setFlash('error', 'Sale not found');
            $this->redirect('sales/history');
        }
        
        $data = [
            'title' => 'Sale Details',
            'sale' => $sale
        ];
        
        $this->view('sales/detail', $data);
    }
    
    public function receipt($id) {
        $this->requireAuth();
        
        $sale = $this->saleModel->getSaleWithDetails($id);
        if (!$sale) {
            $this->setFlash('error', 'Sale not found');
            $this->redirect('sales/history');
        }
        
        $data = [
            'title' => 'Receipt',
            'sale' => $sale
        ];
        
        $this->view('sales/receipt', $data);
    }
}