<?php
class UserModel extends Model {
    protected $table = 'users';
    
    public function authenticate($username, $password) {
        $user = $this->findOne(['username' => $username]);
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_active']) {
                return $user;
            }
        }
        return false;
    }
    
    public function createUser($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }
    
    public function updateUser($id, $data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->update($id, $data);
    }
    
    public function getUserWithRole($id) {
        $sql = "SELECT id, username, full_name, email, role, is_active 
                FROM {$this->table} 
                WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }
}