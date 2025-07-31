<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row fade-in">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        Customers Management
                    </h4>
                    <small class="text-muted">Manage your customer database</small>
                </div>
                <a href="<?= BASE_URL ?>customers/add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Customer
                </a>
            </div>
            <div class="card-body">
                <!-- Search and Stats -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchCustomer" 
                                   placeholder="Search customers...">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="bg-light p-3 rounded">
                                    <h5 class="text-primary mb-0"><?= count($customers) ?></h5>
                                    <small class="text-muted">Total Customers</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-3 rounded">
                                    <h5 class="text-success mb-0">
                                        <?= count(array_filter($customers, function($c) { return $c['points'] > 0; })) ?>
                                    </h5>
                                    <small class="text-muted">With Points</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-3 rounded">
                                    <h5 class="text-info mb-0">
                                        <?= array_sum(array_column($customers, 'points')) ?>
                                    </h5>
                                    <small class="text-muted">Total Points</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="customersTable">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Points</th>
                                <th>Member Since</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($customer['code']) ?></span>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= htmlspecialchars($customer['name']) ?></strong>
                                        <?php if ($customer['email']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($customer['email']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($customer['phone']): ?>
                                        <a href="tel:<?= $customer['phone'] ?>" class="text-decoration-none">
                                            <i class="fas fa-phone text-success"></i>
                                            <?= htmlspecialchars($customer['phone']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($customer['address']): ?>
                                        <small><?= htmlspecialchars(substr($customer['address'], 0, 50)) ?><?= strlen($customer['address']) > 50 ? '...' : '' ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?= $customer['points'] > 0 ? 'primary' : 'secondary' ?> me-2">
                                            <?= number_format($customer['points']) ?>
                                        </span>
                                        <?php if ($_SESSION['user_role'] !== 'cashier'): ?>
                                            <a href="<?= BASE_URL ?>customers/points/<?= $customer['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip" title="Manage Points">
                                                <i class="fas fa-coins"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted"><?= formatDate($customer['created_at']) ?></small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= BASE_URL ?>customers/history/<?= $customer['id'] ?>" 
                                           class="btn btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Purchase History">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>customers/edit/<?= $customer['id'] ?>" 
                                           class="btn btn-warning" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['user_role'] !== 'cashier'): ?>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="confirmDelete('<?= BASE_URL ?>customers/delete/<?= $customer['id'] ?>')"
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

<script>
$(document).ready(function() {
    // Search functionality
    $('#searchCustomer').on('keyup', function() {
        var table = $('#customersTable').DataTable();
        table.search(this.value).draw();
    });
});
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>