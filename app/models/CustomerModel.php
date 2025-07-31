<?php
class CustomerModel extends Model {
    protected $table = 'customers';
    
    public function generateCustomerCode() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $count = $result['count'] + 1;
        
        return "CUST" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
    
    public function searchCustomers($keyword) {
        $sql = "SELECT * FROM {$this->table}
                WHERE name LIKE :keyword 
                OR phone LIKE :keyword
                OR code LIKE :keyword
                ORDER BY name ASC";
        $stmt = $this->db->query($sql, ['keyword' => "%$keyword%"]);
        return $stmt->fetchAll();
    }
    
    public function getTopCustomers($limit = 10) {
        $sql = "SELECT c.*, 
                COUNT(s.id) as total_transactions,
                SUM(s.total) as total_spent
                FROM {$this->table} c
                JOIN sales s ON c.id = s.customer_id
                WHERE s.status = 'completed'
                GROUP BY c.id
                ORDER BY total_spent DESC
                LIMIT " . (int)$limit;
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getCustomerHistory($customerId) {
        $sql = "SELECT s.*, u.full_name as cashier_name
                FROM sales s
                JOIN users u ON s.user_id = u.id
                WHERE s.customer_id = :customer_id
                AND s.status = 'completed'
                ORDER BY s.created_at DESC";
        $stmt = $this->db->query($sql, ['customer_id' => $customerId]);
        return $stmt->fetchAll();
    }
    
    public function updatePoints($customerId, $points, $type = 'add') {
        if ($type === 'add') {
            $sql = "UPDATE {$this->table} SET points = points + :points WHERE id = :id";
        } else {
            $sql = "UPDATE {$this->table} SET points = points - :points WHERE id = :id";
        }
        
        $stmt = $this->db->query($sql, [
            'points' => $points,
            'id' => $customerId
        ]);
        return $stmt->rowCount();
    }
}