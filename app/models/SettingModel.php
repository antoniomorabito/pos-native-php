<?php
class SettingModel extends Model {
    protected $table = 'settings';
    
    public function getAllSettings() {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $result;
    }
    
    public function getSetting($key, $default = null) {
        $setting = $this->findOne(['setting_key' => $key]);
        return $setting ? $setting['setting_value'] : $default;
    }
    
    public function updateSetting($key, $value) {
        $existing = $this->findOne(['setting_key' => $key]);
        
        if ($existing) {
            return $this->update($existing['id'], ['setting_value' => $value]);
        } else {
            return $this->insert([
                'setting_key' => $key,
                'setting_value' => $value,
                'description' => ''
            ]);
        }
    }
    
    public function getShopInfo() {
        return [
            'name' => $this->getSetting('shop_name', 'Konterku POS'),
            'address' => $this->getSetting('shop_address', ''),
            'phone' => $this->getSetting('shop_phone', ''),
            'email' => $this->getSetting('shop_email', ''),
            'logo' => $this->getSetting('shop_logo', '')
        ];
    }
    
    public function getTaxSettings() {
        return [
            'tax_percent' => (float)$this->getSetting('tax_percent', 11),
            'tax_inclusive' => (bool)$this->getSetting('tax_inclusive', false)
        ];
    }
    
    public function getReceiptSettings() {
        return [
            'footer_text' => $this->getSetting('receipt_footer', 'Terima kasih atas kunjungan Anda'),
            'show_logo' => (bool)$this->getSetting('receipt_show_logo', true),
            'paper_width' => $this->getSetting('receipt_paper_width', '80mm')
        ];
    }
}