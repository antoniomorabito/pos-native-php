<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" 
                                data-bs-target="#general" type="button" role="tab">
                            <i class="fas fa-cog"></i> General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="shop-tab" data-bs-toggle="tab" 
                                data-bs-target="#shop" type="button" role="tab">
                            <i class="fas fa-store"></i> Shop Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="receipt-tab" data-bs-toggle="tab" 
                                data-bs-target="#receipt" type="button" role="tab">
                            <i class="fas fa-receipt"></i> Receipt
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="backup-tab" data-bs-toggle="tab" 
                                data-bs-target="#backup" type="button" role="tab">
                            <i class="fas fa-database"></i> Backup
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>settings">
                    <div class="tab-content" id="settingsTabContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="currency" class="form-label">Currency Symbol</label>
                                    <input type="text" class="form-control" id="currency" 
                                           name="settings[currency]" value="<?= $settings['currency'] ?? 'Rp' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tax_percent" class="form-label">Tax Percentage (%)</label>
                                    <input type="number" class="form-control" id="tax_percent" 
                                           name="settings[tax_percent]" value="<?= $settings['tax_percent'] ?? 11 ?>" 
                                           min="0" max="100" step="0.01">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select" id="timezone" name="settings[timezone]">
                                        <option value="Asia/Jakarta" <?= ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' ?>>Asia/Jakarta (WIB)</option>
                                        <option value="Asia/Makassar" <?= ($settings['timezone'] ?? '') == 'Asia/Makassar' ? 'selected' : '' ?>>Asia/Makassar (WITA)</option>
                                        <option value="Asia/Jayapura" <?= ($settings['timezone'] ?? '') == 'Asia/Jayapura' ? 'selected' : '' ?>>Asia/Jayapura (WIT)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="language" class="form-label">Language</label>
                                    <select class="form-select" id="language" name="settings[language]">
                                        <option value="id" <?= ($settings['language'] ?? 'id') == 'id' ? 'selected' : '' ?>>Bahasa Indonesia</option>
                                        <option value="en" <?= ($settings['language'] ?? '') == 'en' ? 'selected' : '' ?>>English</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shop Info -->
                        <div class="tab-pane fade" id="shop" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shop_name" class="form-label">Shop Name</label>
                                    <input type="text" class="form-control" id="shop_name" 
                                           name="settings[shop_name]" value="<?= $settings['shop_name'] ?? 'Konterku POS' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shop_phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="shop_phone" 
                                           name="settings[shop_phone]" value="<?= $settings['shop_phone'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="shop_address" class="form-label">Address</label>
                                <textarea class="form-control" id="shop_address" name="settings[shop_address]" 
                                          rows="3"><?= $settings['shop_address'] ?? '' ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shop_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="shop_email" 
                                           name="settings[shop_email]" value="<?= $settings['shop_email'] ?? '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shop_website" class="form-label">Website</label>
                                    <input type="url" class="form-control" id="shop_website" 
                                           name="settings[shop_website]" value="<?= $settings['shop_website'] ?? '' ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Receipt Settings -->
                        <div class="tab-pane fade" id="receipt" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="receipt_width" class="form-label">Receipt Paper Width</label>
                                    <select class="form-select" id="receipt_width" name="settings[receipt_width]">
                                        <option value="58mm" <?= ($settings['receipt_width'] ?? '80mm') == '58mm' ? 'selected' : '' ?>>58mm</option>
                                        <option value="80mm" <?= ($settings['receipt_width'] ?? '80mm') == '80mm' ? 'selected' : '' ?>>80mm</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="auto_print" 
                                               name="settings[auto_print]" value="1" 
                                               <?= ($settings['auto_print'] ?? false) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="auto_print">
                                            Auto Print Receipt
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="receipt_footer" class="form-label">Receipt Footer Text</label>
                                <textarea class="form-control" id="receipt_footer" name="settings[receipt_footer]" 
                                          rows="3"><?= $settings['receipt_footer'] ?? 'Terima kasih atas kunjungan Anda' ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Backup -->
                        <div class="tab-pane fade" id="backup" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>Database Backup</h5>
                                    <p class="text-muted">Create a backup of your database for safety purposes.</p>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Note:</strong> Backup includes all your data (products, sales, customers, etc.)
                                    </div>
                                    
                                    <a href="<?= BASE_URL ?>settings/backup" class="btn btn-primary">
                                        <i class="fas fa-download"></i> Create & Download Backup
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>System Info</h6>
                                            <small class="text-muted">
                                                <strong>PHP Version:</strong> <?= PHP_VERSION ?><br>
                                                <strong>Database:</strong> <?= DB_NAME ?><br>
                                                <strong>Server:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>