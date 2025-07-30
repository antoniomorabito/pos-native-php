<?php
class ProductModel extends Model {
    protected $table = 'products';
    
    public function getAllWithCategory() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getActiveProducts() {
        return $this->findAll(['is_active' => 1], 'name ASC');
    }
    
    public function getLowStockProducts() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.stock <= p.min_stock
                AND p.is_active = 1
                ORDER BY p.stock ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getTopSellingProducts($limit = 5) {
        $sql = "SELECT p.*, c.name as category_name,
                SUM(sd.quantity) as total_sold,
                SUM(sd.subtotal) as total_revenue
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                JOIN sale_details sd ON p.id = sd.product_id
                JOIN sales s ON sd.sale_id = s.id
                WHERE s.status = 'completed'
                AND s.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY p.id
                ORDER BY total_sold DESC
                LIMIT :limit";
        $stmt = $this->db->query($sql, ['limit' => $limit]);
        return $stmt->fetchAll();
    }
    
    public function searchProducts($keyword) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p.name LIKE :keyword 
                OR p.barcode LIKE :keyword
                OR c.name LIKE :keyword)
                AND p.is_active = 1
                ORDER BY p.name ASC";
        $stmt = $this->db->query($sql, ['keyword' => "%$keyword%"]);
        return $stmt->fetchAll();
    }
    
    public function getByBarcode($barcode) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.barcode = :barcode
                AND p.is_active = 1";
        $stmt = $this->db->query($sql, ['barcode' => $barcode]);
        return $stmt->fetch();
    }
    
    public function updateStock($productId, $quantity, $type = 'add') {
        if ($type === 'add') {
            $sql = "UPDATE {$this->table} SET stock = stock + :quantity WHERE id = :id";
        } else {
            $sql = "UPDATE {$this->table} SET stock = stock - :quantity WHERE id = :id";
        }
        
        $stmt = $this->db->query($sql, [
            'quantity' => $quantity,
            'id' => $productId
        ]);
        return $stmt->rowCount();
    }
    
    public function checkStock($productId, $quantity) {
        $product = $this->findById($productId);
        return $product && $product['stock'] >= $quantity;
    }
    
    public function getProductsForPOS() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1
                AND p.stock > 0
                ORDER BY c.name ASC, p.name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}