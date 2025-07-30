<?php
class DashboardController extends Controller {
    private $saleModel;
    private $productModel;
    private $customerModel;
    
    public function __construct() {
        $this->saleModel = new SaleModel();
        $this->productModel = new ProductModel();
        $this->customerModel = new CustomerModel();
    }
    
    public function index() {
        $this->requireAuth();
        
        // Get dashboard statistics
        $data = [
            'title' => 'Dashboard',
            'todaySales' => $this->saleModel->getTodaySales(),
            'totalProducts' => $this->productModel->count(['is_active' => 1]),
            'totalCustomers' => $this->customerModel->count(),
            'lowStockProducts' => $this->productModel->getLowStockProducts(),
            'recentSales' => $this->saleModel->getRecentSales(10),
            'topProducts' => $this->productModel->getTopSellingProducts(5),
            'salesChart' => $this->saleModel->getSalesChartData()
        ];
        
        $this->view('dashboard/index', $data);
    }
}