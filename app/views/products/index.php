<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Products Management</h4>
                <a href="<?= BASE_URL ?>products/add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Product
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Barcode</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <?php if ($product['image']): ?>
                                        <img src="<?= UPLOAD_URL . $product['image'] ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>" 
                                             class="product-image">
                                    <?php else: ?>
                                        <img src="<?= ASSET_URL . 'images/no-product.png' ?>" 
                                             alt="No image" 
                                             class="product-image">
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product['barcode']) ?></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category_name'] ?? '-') ?></td>
                                <td>
                                    <?php if ($product['stock'] <= $product['min_stock']): ?>
                                        <span class="badge bg-danger"><?= $product['stock'] ?> <?= $product['unit'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><?= $product['stock'] ?> <?= $product['unit'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= formatCurrency($product['purchase_price']) ?></td>
                                <td><?= formatCurrency($product['selling_price']) ?></td>
                                <td>
                                    <?= $product['is_active'] ? 
                                        '<span class="badge bg-success">Active</span>' : 
                                        '<span class="badge bg-secondary">Inactive</span>' ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= BASE_URL ?>products/edit/<?= $product['id'] ?>" 
                                           class="btn btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['user_role'] == 'admin'): ?>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="confirmDelete('<?= BASE_URL ?>products/delete/<?= $product['id'] ?>')"
                                                data-bs-toggle="tooltip" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
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

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>