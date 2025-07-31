<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Users Management</h4>
                <a href="<?= BASE_URL ?>users/add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add User
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['full_name']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '-') ?></td>
                                <td>
                                    <?php
                                    $roleColors = [
                                        'admin' => 'danger',
                                        'manager' => 'warning',
                                        'cashier' => 'info'
                                    ];
                                    $roleColor = $roleColors[$user['role']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $roleColor ?>"><?= ucfirst($user['role']) ?></span>
                                </td>
                                <td>
                                    <?= $user['is_active'] ? 
                                        '<span class="badge bg-success">Active</span>' : 
                                        '<span class="badge bg-secondary">Inactive</span>' ?>
                                </td>
                                <td><?= formatDate($user['created_at']) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= BASE_URL ?>users/edit/<?= $user['id'] ?>" 
                                           class="btn btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="<?= BASE_URL ?>users/toggle/<?= $user['id'] ?>" 
                                           class="btn btn-<?= $user['is_active'] ? 'warning' : 'success' ?>" 
                                           data-bs-toggle="tooltip" 
                                           title="<?= $user['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                            <i class="fas fa-<?= $user['is_active'] ? 'pause' : 'play' ?>"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="confirmDelete('<?= BASE_URL ?>users/delete/<?= $user['id'] ?>')"
                                                data-bs-toggle="tooltip" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php else: ?>
                                        <span class="btn btn-sm btn-outline-secondary disabled">
                                            <i class="fas fa-user-shield"></i> You
                                        </span>
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