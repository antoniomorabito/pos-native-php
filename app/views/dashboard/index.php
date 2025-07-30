<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row g-3 mb-4">
    <!-- Today's Sales Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Today's Sales</h6>
                        <h3 class="mb-2"><?= formatCurrency($todaySales['total'] ?? 0) ?></h3>
                        <p class="text-muted mb-0">
                            <small><?= $todaySales['count'] ?? 0 ?> transactions</small>
                        </p>
                    </div>
                    <div class="dashboard-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Products Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Total Products</h6>
                        <h3 class="mb-2"><?= number_format($totalProducts) ?></h3>
                        <p class="text-muted mb-0">
                            <small>Active products</small>
                        </p>
                    </div>
                    <div class="dashboard-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Customers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Total Customers</h6>
                        <h3 class="mb-2"><?= number_format($totalCustomers) ?></h3>
                        <p class="text-muted mb-0">
                            <small>Registered customers</small>
                        </p>
                    </div>
                    <div class="dashboard-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Alert Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Low Stock Alert</h6>
                        <h3 class="mb-2"><?= count($lowStockProducts) ?></h3>
                        <p class="text-muted mb-0">
                            <small>Products need restock</small>
                        </p>
                    </div>
                    <div class="dashboard-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Sales Chart -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Sales Overview (Last 7 Days)</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Selling Products</h5>
            </div>
            <div class="card-body">
                <?php if (empty($topProducts)): ?>
                    <p class="text-muted text-center">No sales data available</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <?php foreach ($topProducts as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">
                                            <?= $product['total_sold'] ?> sold
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions & Low Stock Products -->
<div class="row g-3 mt-3">
    <!-- Recent Transactions -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Transactions</h5>
                <a href="<?= BASE_URL ?>sales" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Total</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentSales)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">No transactions yet</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentSales as $sale): ?>
                                <tr>
                                    <td>
                                        <a href="<?= BASE_URL ?>sales/view/<?= $sale['id'] ?>">
                                            <?= $sale['invoice_number'] ?>
                                        </a>
                                    </td>
                                    <td><?= $sale['customer_name'] ?? 'Walk-in' ?></td>
                                    <td><?= $sale['cashier_name'] ?></td>
                                    <td><?= formatCurrency($sale['total']) ?></td>
                                    <td><?= date('H:i', strtotime($sale['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Products -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Low Stock Products</h5>
                <a href="<?= BASE_URL ?>products" class="btn btn-sm btn-warning">Manage</a>
            </div>
            <div class="card-body">
                <?php if (empty($lowStockProducts)): ?>
                    <p class="text-muted text-center">All products are well stocked</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <?php foreach (array_slice($lowStockProducts, 0, 5) as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td class="text-end">
                                        <span class="badge bg-danger">
                                            <?= $product['stock'] ?> left
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Format currency function
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Sales Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($salesChart['labels'] ?? []) ?>,
            datasets: [{
                label: 'Sales Amount',
                data: <?= json_encode($salesChart['data'] ?? []) ?>,
                borderColor: 'rgb(13, 110, 253)',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Sales: ' + formatCurrency(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>