<?php
class ReportsController extends Controller {
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
        $this->redirect('reports/sales');
    }
    
    public function sales() {
        $this->requireAuth();
        
        // Get date range from request or default to this month
        $startDate = $this->getGet('start_date', date('Y-m-01'));
        $endDate = $this->getGet('end_date', date('Y-m-t'));
        
        $data = [
            'title' => 'Sales Report',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'salesData' => $this->getSalesReportData($startDate, $endDate),
            'chartData' => $this->getSalesChartData($startDate, $endDate)
        ];
        
        $this->view('reports/sales', $data);
    }
    
    public function inventory() {
        $this->requireAuth();
        
        $data = [
            'title' => 'Inventory Report',
            'products' => $this->productModel->getAllWithCategory(),
            'lowStockProducts' => $this->productModel->getLowStockProducts(),
            'totalProducts' => $this->productModel->count(['is_active' => 1]),
            'totalStockValue' => $this->getTotalStockValue()
        ];
        
        $this->view('reports/inventory', $data);
    }
    
    public function profit() {
        $this->requireAuth();
        
        // Get date range
        $startDate = $this->getGet('start_date', date('Y-m-01'));
        $endDate = $this->getGet('end_date', date('Y-m-t'));
        
        $data = [
            'title' => 'Profit Report',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'profitData' => $this->getProfitReportData($startDate, $endDate)
        ];
        
        $this->view('reports/profit', $data);
    }
    
    public function customers() {
        $this->requireAuth();
        
        $data = [
            'title' => 'Customer Report',
            'topCustomers' => $this->customerModel->getTopCustomers(20),
            'customerStats' => $this->getCustomerStats()
        ];
        
        $this->view('reports/customers', $data);
    }
    
    private function getSalesReportData($startDate, $endDate) {
        $sql = "SELECT 
                    COUNT(*) as total_transactions,
                    SUM(total) as total_sales,
                    SUM(tax_amount) as total_tax,
                    SUM(discount_amount) as total_discount,
                    AVG(total) as average_transaction,
                    payment_method,
                    COUNT(*) as method_count
                FROM sales 
                WHERE DATE(created_at) BETWEEN :start_date AND :end_date
                AND status = 'completed'
                GROUP BY payment_method";
        
        $stmt = $this->saleModel->db->query($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        $paymentMethods = $stmt->fetchAll();
        
        // Get overall totals
        $sql = "SELECT 
                    COUNT(*) as total_transactions,
                    SUM(total) as total_sales,
                    SUM(tax_amount) as total_tax,
                    SUM(discount_amount) as total_discount,
                    AVG(total) as average_transaction
                FROM sales 
                WHERE DATE(created_at) BETWEEN :start_date AND :end_date
                AND status = 'completed'";
        
        $stmt = $this->saleModel->db->query($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        $totals = $stmt->fetch();
        
        return [
            'totals' => $totals,
            'payment_methods' => $paymentMethods
        ];
    }
    
    private function getSalesChartData($startDate, $endDate) {
        $sql = "SELECT 
                    DATE(created_at) as sale_date,
                    COUNT(*) as transaction_count,
                    SUM(total) as daily_total
                FROM sales 
                WHERE DATE(created_at) BETWEEN :start_date AND :end_date
                AND status = 'completed'
                GROUP BY DATE(created_at)
                ORDER BY sale_date ASC";
        
        $stmt = $this->saleModel->db->query($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        $results = $stmt->fetchAll();
        
        $labels = [];
        $salesData = [];
        $transactionData = [];
        
        foreach ($results as $row) {
            $labels[] = date('d M', strtotime($row['sale_date']));
            $salesData[] = $row['daily_total'];
            $transactionData[] = $row['transaction_count'];
        }
        
        return [
            'labels' => $labels,
            'sales' => $salesData,
            'transactions' => $transactionData
        ];
    }
    
    private function getProfitReportData($startDate, $endDate) {
        $sql = "SELECT 
                    p.name as product_name,
                    p.purchase_price,
                    p.selling_price,
                    SUM(sd.quantity) as total_sold,
                    SUM(sd.subtotal) as total_revenue,
                    SUM(sd.quantity * p.purchase_price) as total_cost,
                    (SUM(sd.subtotal) - SUM(sd.quantity * p.purchase_price)) as profit,
                    ((SUM(sd.subtotal) - SUM(sd.quantity * p.purchase_price)) / SUM(sd.subtotal) * 100) as profit_margin
                FROM sale_details sd
                JOIN products p ON sd.product_id = p.id
                JOIN sales s ON sd.sale_id = s.id
                WHERE DATE(s.created_at) BETWEEN :start_date AND :end_date
                AND s.status = 'completed'
                GROUP BY p.id
                ORDER BY profit DESC";
        
        $stmt = $this->saleModel->db->query($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        return $stmt->fetchAll();
    }
    
    private function getTotalStockValue() {
        $sql = "SELECT SUM(stock * purchase_price) as total_value FROM products WHERE is_active = 1";
        $stmt = $this->productModel->db->query($sql);
        $result = $stmt->fetch();
        return $result['total_value'] ?? 0;
    }
    
    private function getCustomerStats() {
        $sql = "SELECT 
                    COUNT(*) as total_customers,
                    SUM(points) as total_points,
                    AVG(points) as average_points
                FROM customers";
        
        $stmt = $this->customerModel->db->query($sql);
        return $stmt->fetch();
    }
}