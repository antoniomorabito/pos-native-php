<?php
// Helper functions

function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}

function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    return date($format, strtotime($datetime));
}

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function isActive($url) {
    $currentUrl = $_GET['url'] ?? '';
    return strpos($currentUrl, $url) === 0 ? 'active' : '';
}

function calculateDiscount($price, $discountPercent) {
    return $price * ($discountPercent / 100);
}

function calculateTax($amount, $taxPercent) {
    return $amount * ($taxPercent / 100);
}

function getStatusBadge($status) {
    $badges = [
        'completed' => 'success',
        'pending' => 'warning',
        'cancelled' => 'danger',
        'active' => 'success',
        'inactive' => 'secondary'
    ];
    
    $badgeClass = $badges[$status] ?? 'secondary';
    return '<span class="badge bg-' . $badgeClass . '">' . ucfirst($status) . '</span>';
}

function getPaymentMethodLabel($method) {
    $labels = [
        'cash' => 'Cash',
        'credit_card' => 'Credit Card',
        'debit_card' => 'Debit Card',
        'e_wallet' => 'E-Wallet'
    ];
    
    return $labels[$method] ?? $method;
}

function uploadImage($file, $directory = 'products/', $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return false;
    }
    
    $uploadDir = UPLOAD_PATH . $directory;
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileExtension, $allowedTypes)) {
        return false;
    }
    
    $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $directory . $fileName;
    }
    
    return false;
}

function deleteImage($imagePath) {
    $fullPath = UPLOAD_PATH . $imagePath;
    if (file_exists($fullPath) && is_file($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}

function getDefaultProductImage() {
    return 'assets/images/no-product.png';
}

function validateBarcode($barcode) {
    // Simple validation - can be enhanced for specific barcode formats
    return preg_match('/^[a-zA-Z0-9\-\_]+$/', $barcode);
}

function calculatePoints($amount, $pointRate = 10000) {
    return floor($amount / $pointRate);
}

function getTimeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    
    if ($difference < 60) {
        return $difference . ' seconds ago';
    } elseif ($difference < 3600) {
        return round($difference / 60) . ' minutes ago';
    } elseif ($difference < 86400) {
        return round($difference / 3600) . ' hours ago';
    } elseif ($difference < 2592000) {
        return round($difference / 86400) . ' days ago';
    } else {
        return formatDate($datetime);
    }
}