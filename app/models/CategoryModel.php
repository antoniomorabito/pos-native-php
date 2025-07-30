<?php
class CategoryModel extends Model {
    protected $table = 'categories';
    
    public function getProductCount($categoryId) {
        $sql = "SELECT COUNT(*) as count 
                FROM products 
                WHERE category_id = :category_id 
                AND is_active = 1";
        $stmt = $this->db->query($sql, ['category_id' => $categoryId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public function getCategoriesWithProductCount() {
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM {$this->table} c
                LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1
                GROUP BY c.id
                ORDER BY c.name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}