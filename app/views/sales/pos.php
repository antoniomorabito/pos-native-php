<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="pos-container">
    <div class="row g-0 h-100">
        <!-- Products Section -->
        <div class="col-lg-8">
            <div class="p-3">
                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control" id="search-product" 
                                   placeholder="Search product or scan barcode...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filter-category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-secondary w-100" id="btn-clear-cart">
                            <i class="fas fa-trash"></i> Clear Cart
                        </button>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div class="product-grid">
                    <div class="row g-2" id="products-container">
                        <?php foreach ($products as $product): ?>
                        <div class="col-md-3 col-sm-4 col-6 product-item" 
                             data-category="<?= $product['category_id'] ?>"
                             data-name="<?= strtolower($product['name']) ?>"
                             data-barcode="<?= $product['barcode'] ?>">
                            <div class="card product-card h-100 <?= $product['stock'] <= 0 ? 'out-of-stock' : '' ?>" 
                                 data-product='<?= json_encode([
                                     'id' => $product['id'],
                                     'name' => $product['name'],
                                     'price' => $product['selling_price'],
                                     'stock' => $product['stock']
                                 ]) ?>'>
                                <div class="card-body text-center p-2">
                                    <?php if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])): ?>
                                        <img src="<?= UPLOAD_URL . $product['image'] ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>" 
                                             class="product-image-large mb-2"
                                             onerror="this.src='<?= ASSET_URL ?>images/no-product.svg'">
                                    <?php else: ?>
                                        <img src="<?= ASSET_URL ?>images/no-product.svg" 
                                             alt="<?= htmlspecialchars($product['name']) ?>" 
                                             class="product-image-large mb-2">
                                    <?php endif; ?>
                                    <h6 class="mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                                    <p class="text-primary mb-0 fw-bold"><?= formatCurrency($product['selling_price']) ?></p>
                                    <small class="text-muted">Stock: <?= $product['stock'] ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cart Section -->
        <div class="col-lg-4">
            <div class="cart-container">
                <div class="cart-header">
                    <h5 class="mb-0">Shopping Cart</h5>
                </div>
                
                <div class="cart-items" id="cart-items">
                    <p class="text-center text-muted py-5">Cart is empty</p>
                </div>
                
                <div class="cart-footer">
                    <!-- Customer Selection -->
                    <div class="mb-3">
                        <select class="form-select select2" id="customer-select">
                            <option value="">Walk-in Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id'] ?>">
                                    <?= htmlspecialchars($customer['name']) ?> - <?= $customer['phone'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Summary -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="cart-subtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <div class="input-group input-group-sm" style="width: 100px;">
                                <input type="number" class="form-control" id="discount-percent" 
                                       value="0" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (11%):</span>
                            <span id="cart-tax">Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span id="cart-total">Rp 0</span>
                        </div>
                    </div>
                    
                    <!-- Payment -->
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="payment-method">
                            <option value="cash">Cash</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="e_wallet">E-Wallet</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="cash-payment">
                        <label class="form-label">Paid Amount</label>
                        <input type="number" class="form-control" id="paid-amount" min="0" step="1000">
                        <div class="mt-2">
                            <span>Change: </span>
                            <span id="change-amount" class="fw-bold text-success">Rp 0</span>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="setExactAmount()">Exact</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="setRoundAmount()">Round Up</button>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary btn-lg w-100" id="btn-checkout" disabled>
                        <i class="fas fa-shopping-cart"></i> Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- POS JavaScript -->
<script>
// Helper functions for payment
function setExactAmount() {
    const total = window.pos.getTotal();
    $('#paid-amount').val(total);
    window.pos.calculateChange();
}

function setRoundAmount() {
    const total = window.pos.getTotal();
    const rounded = Math.ceil(total / 1000) * 1000;
    $('#paid-amount').val(rounded);
    window.pos.calculateChange();
}

// Auto-set paid amount when total changes
$(document).on('cartUpdated', function() {
    if ($('#payment-method').val() === 'cash') {
        setExactAmount();
    }
});
</script>
<script src="<?= ASSET_URL ?>js/pos.js"></script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>