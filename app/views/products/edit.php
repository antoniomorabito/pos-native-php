<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row fade-in">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit text-warning me-2"></i>
                            Edit Product
                        </h4>
                        <small class="text-muted">Update product information</small>
                    </div>
                    <a href="<?= BASE_URL ?>products" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="row">
                        <!-- Product Information -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Product Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?= htmlspecialchars($product['name']) ?>" required>
                                            <div class="invalid-feedback">Please provide a product name.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="sku" class="form-label">SKU</label>
                                            <input type="text" class="form-control" id="sku" name="sku" 
                                                   value="<?= htmlspecialchars($product['sku'] ?? '') ?>" 
                                                   placeholder="Auto-generated if empty">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-select" id="category_id" name="category_id">
                                                <option value="">Select Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>" 
                                                        <?= $product['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($category['name']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="barcode" class="form-label">Barcode</label>
                                            <input type="text" class="form-control" id="barcode" name="barcode" 
                                                   value="<?= htmlspecialchars($product['barcode'] ?? '') ?>">
                                        </div>
                                        
                                        <div class="col-12">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" 
                                                      rows="3" placeholder="Product description..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pricing & Stock -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Pricing & Stock</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="purchase_price" class="form-label">Purchase Price *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="purchase_price" name="purchase_price" 
                                                       value="<?= $product['purchase_price'] ?>" min="0" step="0.01" required>
                                            </div>
                                            <div class="invalid-feedback">Please provide a purchase price.</div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="selling_price" class="form-label">Selling Price *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="selling_price" name="selling_price" 
                                                       value="<?= $product['selling_price'] ?>" min="0" step="0.01" required>
                                            </div>
                                            <div class="invalid-feedback">Please provide a selling price.</div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label class="form-label">Profit Margin</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="profit_margin" readonly>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="stock" class="form-label">Current Stock *</label>
                                            <input type="number" class="form-control" id="stock" name="stock" 
                                                   value="<?= $product['stock'] ?>" min="0" required>
                                            <div class="invalid-feedback">Please provide stock quantity.</div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="min_stock" class="form-label">Minimum Stock *</label>
                                            <input type="number" class="form-control" id="min_stock" name="min_stock" 
                                                   value="<?= $product['min_stock'] ?>" min="0" required>
                                            <div class="invalid-feedback">Please provide minimum stock level.</div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="unit" class="form-label">Unit</label>
                                            <select class="form-select" id="unit" name="unit">
                                                <option value="pcs" <?= $product['unit'] == 'pcs' ? 'selected' : '' ?>>Pieces</option>
                                                <option value="kg" <?= $product['unit'] == 'kg' ? 'selected' : '' ?>>Kilogram</option>
                                                <option value="liter" <?= $product['unit'] == 'liter' ? 'selected' : '' ?>>Liter</option>
                                                <option value="box" <?= $product['unit'] == 'box' ? 'selected' : '' ?>>Box</option>
                                                <option value="bottle" <?= $product['unit'] == 'bottle' ? 'selected' : '' ?>>Bottle</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Image -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Image</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="image-upload-container">
                                        <div class="current-image mb-3">
                                            <img id="imagePreview" 
                                                 src="<?= $product['image_path'] ? UPLOAD_URL . $product['image_path'] : ASSET_URL . 'images/no-product.svg' ?>" 
                                                 alt="Product Image" 
                                                 class="img-fluid" 
                                                 style="max-height: 200px; border-radius: 10px;">
                                        </div>
                                        <input type="file" class="form-control" id="image" name="image" 
                                               accept="image/*" onchange="previewImage(this)">
                                        <small class="text-muted mt-2 d-block">
                                            Supported: JPG, PNG, GIF (Max: 2MB)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" <?= $product['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active Product
                                        </label>
                                    </div>
                                    <small class="text-muted">Inactive products won't appear in POS</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="<?= BASE_URL ?>products" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Product
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Calculate profit margin
function calculateProfitMargin() {
    const purchasePrice = parseFloat(document.getElementById('purchase_price').value) || 0;
    const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
    
    if (purchasePrice > 0) {
        const margin = ((sellingPrice - purchasePrice) / purchasePrice) * 100;
        document.getElementById('profit_margin').value = margin.toFixed(2);
        
        // Update margin color
        const marginInput = document.getElementById('profit_margin');
        if (margin >= 20) {
            marginInput.className = 'form-control text-success';
        } else if (margin >= 10) {
            marginInput.className = 'form-control text-warning';
        } else {
            marginInput.className = 'form-control text-danger';
        }
    }
}

// Image preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Form validation
(function() {
    'use strict';
    
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Calculate margin on price change
document.getElementById('purchase_price').addEventListener('input', calculateProfitMargin);
document.getElementById('selling_price').addEventListener('input', calculateProfitMargin);

// Calculate initial margin
calculateProfitMargin();
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>