<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row fade-in">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-chart-line text-success me-2"></i>
                            Profit Report
                        </h4>
                        <small class="text-muted">Analyze profitability by product</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="exportProfit('excel')">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button class="btn btn-danger" onclick="exportProfit('pdf')">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Date Range Filter -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date" 
                                       value="<?= $startDate ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" 
                                       value="<?= $endDate ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="text-end">
                            <small class="text-muted">Period: <?= formatDate($startDate) ?> - <?= formatDate($endDate) ?></small>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <?php 
                $totalRevenue = array_sum(array_column($profitData, 'total_revenue'));
                $totalCost = array_sum(array_column($profitData, 'total_cost'));
                $totalProfit = array_sum(array_column($profitData, 'profit'));
                $overallMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
                ?>
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-1"><?= formatCurrency($totalRevenue) ?></h3>
                                        <p class="mb-0">Total Revenue</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
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
                                        <h3 class="mb-1"><?= formatCurrency($totalCost) ?></h3>
                                        <p class="mb-0">Total Cost</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-coins fa-2x opacity-75"></i>
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
                                        <h3 class="mb-1"><?= formatCurrency($totalProfit) ?></h3>
                                        <p class="mb-0">Total Profit</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
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
                                        <h3 class="mb-1"><?= number_format($overallMargin, 1) ?>%</h3>
                                        <p class="mb-0">Overall Margin</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-percentage fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profit by Product Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Profitability Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="profitTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Purchase Price</th>
                                        <th>Selling Price</th>
                                        <th>Units Sold</th>
                                        <th>Revenue</th>
                                        <th>Cost</th>
                                        <th>Profit</th>
                                        <th>Margin %</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($profitData as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                        </td>
                                        <td><?= formatCurrency($item['purchase_price']) ?></td>
                                        <td><?= formatCurrency($item['selling_price']) ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $item['total_sold'] ?></span>
                                        </td>
                                        <td><?= formatCurrency($item['total_revenue']) ?></td>
                                        <td><?= formatCurrency($item['total_cost']) ?></td>
                                        <td>
                                            <span class="<?= $item['profit'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= formatCurrency($item['profit']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $item['profit_margin'] >= 20 ? 'bg-success' : ($item['profit_margin'] >= 10 ? 'bg-warning' : 'bg-danger') ?>">
                                                <?= number_format($item['profit_margin'], 1) ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($item['profit_margin'] >= 20): ?>
                                                <span class="badge bg-success">Excellent</span>
                                            <?php elseif ($item['profit_margin'] >= 10): ?>
                                                <span class="badge bg-warning">Good</span>
                                            <?php elseif ($item['profit_margin'] >= 0): ?>
                                                <span class="badge bg-secondary">Poor</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Loss</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Profit Chart -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Top 10 Most Profitable Products</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="profitChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#profitTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[6, 'desc']], // Sort by profit descending
        columnDefs: [
            { type: 'currency', targets: [1, 2, 4, 5, 6] }
        ]
    });

    // Profit Chart
    const ctx = document.getElementById('profitChart').getContext('2d');
    const profitData = <?= json_encode(array_slice($profitData, 0, 10)) ?>;
    
    const labels = profitData.map(item => item.product_name.length > 20 ? 
        item.product_name.substring(0, 20) + '...' : item.product_name);
    const profits = profitData.map(item => parseFloat(item.profit));
    const revenues = profitData.map(item => parseFloat(item.total_revenue));
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Profit',
                data: profits,
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1
            }, {
                label: 'Revenue',
                data: revenues,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});

function exportProfit(format) {
    const startDate = '<?= $startDate ?>';
    const endDate = '<?= $endDate ?>';
    const url = `<?= BASE_URL ?>reports/export?type=profit&format=${format}&start_date=${startDate}&end_date=${endDate}`;
    window.open(url, '_blank');
}
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>