<?php
class SaleModel extends Model {
    protected $table = 'sales';
    
    public function getTodaySales() {
        $sql = "SELECT COUNT(*) as count, COALESCE(SUM(total), 0) as total 
                FROM {$this->table} 
                WHERE DATE(created_at) = CURDATE() 
                AND status = 'completed'";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
    
    public function getRecentSales($limit = 10) {
        $sql = "SELECT s.*, u.full_name as cashier_name, c.name as customer_name 
                FROM {$this->table} s
                LEFT JOIN users u ON s.user_id = u.id
                LEFT JOIN customers c ON s.customer_id = c.id
                WHERE s.status = 'completed'
                ORDER BY s.created_at DESC
                LIMIT :limit";
        $stmt = $this->db->query($sql, ['limit' => $limit]);
        return $stmt->fetchAll();
    }
    
    public function getSalesChartData($days = 7) {
        $sql = "SELECT DATE(created_at) as date, 
                COUNT(*) as count, 
                SUM(total) as total
                FROM {$this->table}
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                AND status = 'completed'
                GROUP BY DATE(created_at)
                ORDER BY date ASC";
        $stmt = $this->db->query($sql, ['days' => $days]);
        $results = $stmt->fetchAll();
        
        // Format for Chart.js
        $labels = [];
        $data = [];
        foreach ($results as $row) {
            $labels[] = date('d M', strtotime($row['date']));
            $data[] = $row['total'];
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    public function getSaleWithDetails($id) {
        // Get sale info
        $sql = "SELECT s.*, u.full_name as cashier_name, c.name as customer_name, c.phone as customer_phone
                FROM {$this->table} s
                LEFT JOIN users u ON s.user_id = u.id
                LEFT JOIN customers c ON s.customer_id = c.id
                WHERE s.id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        $sale = $stmt->fetch();
        
        if ($sale) {
            // Get sale details
            $sql = "SELECT sd.*, p.name as product_name, p.barcode
                    FROM sale_details sd
                    JOIN products p ON sd.product_id = p.id
                    WHERE sd.sale_id = :sale_id";
            $stmt = $this->db->query($sql, ['sale_id' => $id]);
            $sale['items'] = $stmt->fetchAll();
        }
        
        return $sale;
    }
    
    public function generateInvoiceNumber() {
        $date = date('Ymd');
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $count = $result['count'] + 1;
        
        return "INV-{$date}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
    
    public function createSale($saleData, $items) {
        try {
            $conn = $this->db->getConnection();
            $conn->beginTransaction();
            
            // Insert sale
            $saleId = $this->insert($saleData);
            
            // Insert sale details and update stock
            foreach ($items as $item) {
                // Insert sale detail
                $detailData = [
                    'sale_id' => $saleId,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'subtotal' => $item['subtotal']
                ];
                
                $sql = "INSERT INTO sale_details (sale_id, product_id, price, quantity, discount_percent, discount_amount, subtotal)
                        VALUES (:sale_id, :product_id, :price, :quantity, :discount_percent, :discount_amount, :subtotal)";
                $this->db->query($sql, $detailData);
                
                // Update product stock
                $sql = "UPDATE products SET stock = stock - :quantity WHERE id = :product_id";
                $this->db->query($sql, [
                    'quantity' => $item['quantity'],
                    'product_id' => $item['product_id']
                ]);
                
                // Record stock movement
                $sql = "INSERT INTO stock_movements (product_id, type, quantity, reference_type, reference_id, notes, user_id)
                        VALUES (:product_id, 'out', :quantity, 'sale', :sale_id, 'Sale transaction', :user_id)";
                $this->db->query($sql, [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'sale_id' => $saleId,
                    'user_id' => $_SESSION['user_id']
                ]);
            }
            
            // Update customer points if applicable
            if (!empty($saleData['customer_id'])) {
                $points = floor($saleData['total'] / 10000); // 1 point per 10,000
                $sql = "UPDATE customers SET points = points + :points WHERE id = :customer_id";
                $this->db->query($sql, [
                    'points' => $points,
                    'customer_id' => $saleData['customer_id']
                ]);
            }
            
            $conn->commit();
            return $saleId;
            
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}