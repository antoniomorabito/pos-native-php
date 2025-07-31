<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row fade-in">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-history text-info me-2"></i>
                            Sales History
                        </h4>
                        <small class="text-muted">View all sales transactions</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL ?>sales" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Sale
                        </a>
                        <button class="btn btn-success" onclick="exportSales('excel')">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <form method="GET" class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Date From</label>
                                        <input type="date" class="form-control" name="date_from" 
                                               value="<?= $filters['date_from'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date To</label>
                                        <input type="date" class="form-control" name="date_to" 
                                               value="<?= $filters['date_to'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="">All Status</option>
                                            <option value="completed" <?= ($filters['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="pending" <?= ($filters['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="cancelled" <?= ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Payment</label>
                                        <select class="form-select" name="payment_method">
                                            <option value="">All Methods</option>
                                            <option value="cash" <?= ($filters['payment_method'] ?? '') == 'cash' ? 'selected' : '' ?>>Cash</option>
                                            <option value="card" <?= ($filters['payment_method'] ?? '') == 'card' ? 'selected' : '' ?>>Card</option>
                                            <option value="transfer" <?= ($filters['payment_method'] ?? '') == 'transfer' ? 'selected' : '' ?>>Transfer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-1"><?= count($sales) ?></h3>
                                        <p class="mb-0">Total Transactions</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
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
                                        <h3 class="mb-1"><?= formatCurrency(array_sum(array_column($sales, 'total'))) ?></h3>
                                        <p class="mb-0">Total Sales</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
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
                                        <h3 class="mb-1"><?= count($sales) > 0 ? formatCurrency(array_sum(array_column($sales, 'total')) / count($sales)) : formatCurrency(0) ?></h3>
                                        <p class="mb-0">Average Sale</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-bar fa-2x opacity-75"></i>
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
                                        <h3 class="mb-1"><?= count(array_filter($sales, fn($s) => $s['status'] == 'completed')) ?></h3>
                                        <p class="mb-0">Completed</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="salesTable">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Items</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sales as $sale): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($sale['invoice_number']) ?></strong>
                                </td>
                                <td>
                                    <div><?= formatDate($sale['created_at']) ?></div>
                                    <small class="text-muted"><?= formatTime($sale['created_at']) ?></small>
                                </td>
                                <td>
                                    <?php if ($sale['customer_name']): ?>
                                        <div><?= htmlspecialchars($sale['customer_name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($sale['customer_phone'] ?? '') ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Walk-in Customer</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($sale['cashier_name']) ?></td>
                                <td>
                                    <span class="badge bg-info"><?= $sale['total_items'] ?> items</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= ucfirst($sale['payment_method']) ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?= formatCurrency($sale['total']) ?></strong>
                                    <?php if ($sale['discount_amount'] > 0): ?>
                                    <br><small class="text-success">-<?= formatCurrency($sale['discount_amount']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'completed' => 'success',
                                        'pending' => 'warning',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusColor = $statusColors[$sale['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $statusColor ?>">
                                        <?= ucfirst($sale['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="viewSaleDetail(<?= $sale['id'] ?>)" 
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-success" 
                                                onclick="printReceipt(<?= $sale['id'] ?>)" 
                                                title="Print Receipt">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <?php if ($sale['status'] == 'pending' && $_SESSION['user_role'] == 'admin'): ?>
                                        <button type="button" class="btn btn-outline-warning" 
                                                onclick="cancelSale(<?= $sale['id'] ?>)" 
                                                title="Cancel Sale">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (empty($sales)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No sales found</h5>
                    <p class="text-muted">Try adjusting your filters or create a new sale.</p>
                    <a href="<?= BASE_URL ?>sales" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Sale
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Sale Detail Modal -->
<div class="modal fade" id="saleDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sale Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="saleDetailContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="printDetailBtn">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#salesTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[1, 'desc']], // Sort by date descending
        columnDefs: [
            { orderable: false, targets: [8] }, // Actions column
            { type: 'date', targets: [1] }
        ]
    });
});

function viewSaleDetail(saleId) {
    const modal = new bootstrap.Modal(document.getElementById('saleDetailModal'));
    const content = document.getElementById('saleDetailContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    // Set print button action
    document.getElementById('printDetailBtn').onclick = () => printReceipt(saleId);
    
    modal.show();
    
    // Load sale details
    fetch(`<?= BASE_URL ?>sales/detail/${saleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                content.innerHTML = renderSaleDetail(data.sale);
            } else {
                content.innerHTML = `<div class="alert alert-danger">Error loading sale details: ${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `<div class="alert alert-danger">Error loading sale details.</div>`;
        });
}

function renderSaleDetail(sale) {
    let itemsHtml = '';
    sale.items.forEach(item => {
        itemsHtml += `
            <tr>
                <td>${item.product_name}</td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-end">${formatCurrency(item.price)}</td>
                <td class="text-end">${formatCurrency(item.subtotal)}</td>
            </tr>
        `;
    });
    
    return `
        <div class="row">
            <div class="col-md-6">
                <h6>Sale Information</h6>
                <table class="table table-sm">
                    <tr><td>Invoice Number:</td><td><strong>${sale.invoice_number}</strong></td></tr>
                    <tr><td>Date:</td><td>${formatDateTime(sale.created_at)}</td></tr>
                    <tr><td>Cashier:</td><td>${sale.cashier_name}</td></tr>
                    <tr><td>Customer:</td><td>${sale.customer_name || 'Walk-in Customer'}</td></tr>
                    <tr><td>Status:</td><td><span class="badge bg-success">${sale.status}</span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Payment Information</h6>
                <table class="table table-sm">
                    <tr><td>Payment Method:</td><td>${sale.payment_method}</td></tr>
                    <tr><td>Paid Amount:</td><td>${formatCurrency(sale.paid_amount)}</td></tr>
                    <tr><td>Change:</td><td>${formatCurrency(sale.change_amount)}</td></tr>
                </table>
            </div>
        </div>
        
        <h6>Items</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
            </table>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-6 offset-md-6">
                <table class="table table-sm">
                    <tr><td>Subtotal:</td><td class="text-end">${formatCurrency(sale.subtotal)}</td></tr>
                    ${sale.discount_amount > 0 ? `<tr><td>Discount:</td><td class="text-end">-${formatCurrency(sale.discount_amount)}</td></tr>` : ''}
                    <tr><td>Tax:</td><td class="text-end">${formatCurrency(sale.tax_amount)}</td></tr>
                    <tr class="table-primary"><td><strong>Total:</strong></td><td class="text-end"><strong>${formatCurrency(sale.total)}</strong></td></tr>
                </table>
            </div>
        </div>
    `;
}

function printReceipt(saleId) {
    window.open(`<?= BASE_URL ?>sales/receipt/${saleId}`, '_blank');
}

function cancelSale(saleId) {
    if (confirm('Are you sure you want to cancel this sale? This action cannot be undone.')) {
        fetch(`<?= BASE_URL ?>sales/cancel/${saleId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while cancelling the sale.');
        });
    }
}

function exportSales(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    window.open(`<?= BASE_URL ?>sales/export?${params.toString()}`, '_blank');
}

// Helper functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function formatDateTime(dateString) {
    return new Date(dateString).toLocaleString('id-ID');
}
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>