<?php
abstract class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function findAll($conditions = [], $orderBy = '') {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $key => $value) {
                $whereClause[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }
    
    public function findOne($conditions = []) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $key => $value) {
                $whereClause[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $sql .= " LIMIT 1";
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function insert($data) {
        $columns = array_keys($data);
        $values = array_map(function($col) { return ":$col"; }, $columns);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $values) . ")";
        
        $this->db->query($sql, $data);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $setClause = [];
        foreach ($data as $key => $value) {
            $setClause[] = "$key = :$key";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClause) . " WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->query($sql, $data);
        return $stmt->rowCount();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->rowCount();
    }
    
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $key => $value) {
                $whereClause[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();
        return $result['total'];
    }
}