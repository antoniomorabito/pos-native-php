<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Add New Product</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>products/add" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" class="form-control" id="barcode" name="barcode" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select select2" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">Unit</label>
                            <select class="form-select" id="unit" name="unit" required>
                                <option value="pcs">Pieces (pcs)</option>
                                <option value="box">Box</option>
                                <option value="pack">Pack</option>
                                <option value="btl">Bottle (btl)</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="ltr">Liter (ltr)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="purchase_price" class="form-label">Purchase Price</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="purchase_price" name="purchase_price" 
                                       min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="selling_price" name="selling_price" 
                                       min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="stock" class="form-label">Initial Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" 
                                   min="0" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="min_stock" class="form-label">Minimum Stock</label>
                            <input type="number" class="form-control" id="min_stock" name="min_stock" 
                                   min="0" value="10" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" 
                               accept="image/jpeg,image/jpg,image/png,image/gif">
                        <small class="text-muted">Allowed formats: JPG, JPEG, PNG, GIF. Max size: 2MB</small>
                    </div>
                    
                    <div class="text-end">
                        <a href="<?= BASE_URL ?>products" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Calculate profit margin
    $('#purchase_price, #selling_price').on('input', function() {
        var purchase = parseFloat($('#purchase_price').val()) || 0;
        var selling = parseFloat($('#selling_price').val()) || 0;
        var profit = selling - purchase;
        var margin = purchase > 0 ? (profit / purchase * 100).toFixed(2) : 0;
        
        if (margin < 0) {
            $('#selling_price').addClass('is-invalid');
        } else {
            $('#selling_price').removeClass('is-invalid');
        }
    });
});
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>