<?php
class SettingsController extends Controller {
    private $settingModel;
    
    public function __construct() {
        $this->settingModel = new SettingModel();
    }
    
    public function index() {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        if ($this->isPost()) {
            $settings = $this->getPost('settings');
            
            try {
                foreach ($settings as $key => $value) {
                    $this->settingModel->updateSetting($key, $value);
                }
                $this->setFlash('success', 'Settings updated successfully');
                $this->redirect('settings');
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to update settings: ' . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'System Settings',
            'settings' => $this->settingModel->getAllSettings()
        ];
        
        $this->view('settings/index', $data);
    }
    
    public function backup() {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        if ($this->isPost()) {
            try {
                $backupFile = $this->createDatabaseBackup();
                $this->setFlash('success', 'Database backup created successfully');
                
                // Download backup file
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
                header('Content-Length: ' . filesize($backupFile));
                readfile($backupFile);
                
                // Delete temporary file
                unlink($backupFile);
                exit;
                
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to create backup: ' . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Database Backup'
        ];
        
        $this->view('settings/backup', $data);
    }
    
    public function logs() {
        $this->requireAuth();
        $this->requireRole(['admin']);
        
        $data = [
            'title' => 'System Logs',
            'logs' => $this->getSystemLogs()
        ];
        
        $this->view('settings/logs', $data);
    }
    
    private function createDatabaseBackup() {
        $filename = 'backup_' . DB_NAME . '_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = sys_get_temp_dir() . '/' . $filename;
        
        $command = "mysqldump --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME . " > " . $filepath;
        
        exec($command, $output, $return_var);
        
        if ($return_var !== 0) {
            throw new Exception('Backup command failed');
        }
        
        return $filepath;
    }
    
    private function getSystemLogs() {
        // This is a simple example - in production you'd want proper logging
        $logs = [];
        
        // Get recent sales as activity logs
        try {
            $db = Database::getInstance();
            $sql = "SELECT 
                        s.created_at,
                        'Sale' as type,
                        CONCAT('Invoice ', s.invoice_number, ' - ', FORMAT(s.total, 0)) as message,
                        u.full_name as user
                    FROM sales s
                    JOIN users u ON s.user_id = u.id
                    WHERE s.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    ORDER BY s.created_at DESC
                    LIMIT 50";
            
            $stmt = $db->query($sql);
            $logs = $stmt->fetchAll();
            
        } catch (Exception $e) {
            // Handle error silently
        }
        
        return $logs;
    }
}