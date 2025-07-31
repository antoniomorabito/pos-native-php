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
        
        if ($this->isPost()) {
            try {
                // Validate input
                $items = json_decode($this->getPost('items'), true);
                if (empty($items)) {
                    throw new Exception('No items in cart');
                }
                
                $customerId = $this->getPost('customer_id') ?: null;
                $paymentMethod = $this->getPost('payment_method') ?: 'cash';
                $paidAmount = (float)$this->getPost('paid_amount');
                $discountPercent = (float)$this->getPost('discount_percent', 0);
                $notes = $this->getPost('notes', '');
                
                // Validate items and calculate totals
                $subtotal = 0;
                foreach ($items as &$item) {
                    // Validate product exists and has stock
                    $product = $this->productModel->findById($item['product_id']);
                    if (!$product) {
                        throw new Exception('Product not found: ' . $item['product_id']);
                    }
                    
                    if ($product['stock'] < $item['quantity']) {
                        throw new Exception('Insufficient stock for: ' . $product['name']);
                    }
                    
                    // Calculate item subtotal
                    $item['subtotal'] = $item['price'] * $item['quantity'];
                    $subtotal += $item['subtotal'];
                }
                
                // Calculate totals
                $discountAmount = $subtotal * ($discountPercent / 100);
                $taxPercent = 11; // Get from settings later
                $taxAmount = ($subtotal - $discountAmount) * ($taxPercent / 100);
                $total = $subtotal - $discountAmount + $taxAmount;
                $changeAmount = $paidAmount - $total;
                
                // Validate payment amount
                if ($paymentMethod === 'cash' && $paidAmount < $total) {
                    throw new Exception('Insufficient payment amount');
                }
                
                // For non-cash payments, set paid amount to total
                if ($paymentMethod !== 'cash') {
                    $paidAmount = $total;
                    $changeAmount = 0;
                }
                
                // Prepare sale data
                $saleData = [
                    'invoice_number' => $this->saleModel->generateInvoiceNumber(),
                    'customer_id' => $customerId,
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
                    'notes' => $notes,
                    'status' => 'completed'
                ];
                
                // Create sale transaction
                $saleId = $this->saleModel->createSale($saleData, $items);
                
                $this->json([
                    'success' => true,
                    'sale_id' => $saleId,
                    'invoice_number' => $saleData['invoice_number'],
                    'total' => $total,
                    'change' => $changeAmount
                ]);
                
            } catch (Exception $e) {
                error_log("Checkout error: " . $e->getMessage());
                $this->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
        } else {
            $this->json([
                'success' => false,
                'message' => 'Invalid request method'
            ], 405);
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