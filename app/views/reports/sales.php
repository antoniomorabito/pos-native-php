<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row fade-in">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Sales Report
                        </h4>
                        <small class="text-muted">Analyze your sales performance</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="exportReport('excel')">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button class="btn btn-danger" onclick="exportReport('pdf')">
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
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-1"><?= $salesData['totals']['total_transactions'] ?? 0 ?></h3>
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
                                        <h3 class="mb-1"><?= formatCurrency($salesData['totals']['total_sales'] ?? 0) ?></h3>
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
                                        <h3 class="mb-1"><?= formatCurrency($salesData['totals']['average_transaction'] ?? 0) ?></h3>
                                        <p class="mb-0">Average Transaction</p>
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
                                        <h3 class="mb-1"><?= formatCurrency($salesData['totals']['total_tax'] ?? 0) ?></h3>
                                        <p class="mb-0">Total Tax</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-percentage fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Daily Sales Trend</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="salesChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Payment Methods</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="paymentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Table -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Payment Methods Breakdown</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Payment Method</th>
                                                <th>Transactions</th>
                                                <th>Total Amount</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $totalTransactions = $salesData['totals']['total_transactions'] ?? 1;
                                            foreach ($salesData['payment_methods'] as $method): 
                                                $percentage = ($method['method_count'] / $totalTransactions) * 100;
                                            ?>
                                            <tr>
                                                <td><?= getPaymentMethodLabel($method['payment_method']) ?></td>
                                                <td><?= $method['method_count'] ?></td>
                                                <td><?= formatCurrency($method['total_sales']) ?></td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar" style="width: <?= $percentage ?>%">
                                                            <?= number_format($percentage, 1) ?>%
                                                        </div>
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
            </div>
        </div>
    </div>
</div>

<script>
// Sales Chart
document.addEventListener('DOMContentLoaded', function() {
    // Daily Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartData['labels']) ?>,
            datasets: [{
                label: 'Sales Amount',
                data: <?= json_encode($chartData['sales']) ?>,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Transactions',
                data: <?= json_encode($chartData['transactions']) ?>,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Sales Amount'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Transactions'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Payment Methods Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    const paymentData = <?= json_encode(array_column($salesData['payment_methods'], 'method_count')) ?>;
    const paymentLabels = <?= json_encode(array_map('getPaymentMethodLabel', array_column($salesData['payment_methods'], 'payment_method'))) ?>;
    
    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: paymentLabels,
            datasets: [{
                data: paymentData,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});

function exportReport(format) {
    const startDate = '<?= $startDate ?>';
    const endDate = '<?= $endDate ?>';
    const url = `<?= BASE_URL ?>reports/export?format=${format}&start_date=${startDate}&end_date=${endDate}`;
    window.open(url, '_blank');
}
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>