<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row fade-in">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-boxes text-info me-2"></i>
                            Inventory Report
                        </h4>
                        <small class="text-muted">Monitor stock levels and inventory value</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="exportInventory('excel')">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button class="btn btn-danger" onclick="exportInventory('pdf')">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Summary Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-1"><?= $totalProducts ?></h3>
                                        <p class="mb-0">Total Products</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-box fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-1"><?= formatCurrency($totalStockValue) ?></h3>
                                        <p class="mb-0">Total Stock Value</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-1"><?= count($lowStockProducts) ?></h3>
                                        <p class="mb-0">Low Stock Items</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-1"><?= array_sum(array_column($products, 'stock')) ?></h3>
                                        <p class="mb-0">Total Stock Quantity</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-cubes fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <?php if (!empty($lowStockProducts)): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Low Stock Alert!</strong> You have <?= count($lowStockProducts) ?> products with low stock levels.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Inventory Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Current Inventory</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="inventoryTable">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>SKU</th>
                                        <th>Current Stock</th>
                                        <th>Min Stock</th>
                                        <th>Purchase Price</th>
                                        <th>Selling Price</th>
                                        <th>Stock Value</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr class="<?= $product['stock'] <= $product['min_stock'] ? 'table-warning' : '' ?>">
                                        <td>
                                            <img src="<?= $product['image_path'] ? UPLOAD_URL . $product['image_path'] : ASSET_URL . 'images/no-product.svg' ?>" 
                                                 alt="<?= htmlspecialchars($product['name']) ?>" 
                                                 class="product-image">
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($product['name']) ?></strong>
                                            <?php if ($product['description']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($product['category_name'] ?? 'No Category') ?></td>
                                        <td><?= htmlspecialchars($product['sku'] ?? '-') ?></td>
                                        <td>
                                            <span class="badge <?= $product['stock'] <= $product['min_stock'] ? 'bg-warning' : 'bg-success' ?>">
                                                <?= $product['stock'] ?>
                                            </span>
                                        </td>
                                        <td><?= $product['min_stock'] ?></td>
                                        <td><?= formatCurrency($product['purchase_price']) ?></td>
                                        <td><?= formatCurrency($product['selling_price']) ?></td>
                                        <td><?= formatCurrency($product['stock'] * $product['purchase_price']) ?></td>
                                        <td>
                                            <?php if ($product['stock'] <= 0): ?>
                                                <span class="badge bg-danger">Out of Stock</span>
                                            <?php elseif ($product['stock'] <= $product['min_stock']): ?>
                                                <span class="badge bg-warning">Low Stock</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">In Stock</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#inventoryTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[4, 'asc']], // Sort by stock level
        columnDefs: [
            { orderable: false, targets: [0] }
        ]
    });
});

function exportInventory(format) {
    const url = `<?= BASE_URL ?>reports/export?type=inventory&format=${format}`;
    window.open(url, '_blank');
}
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>