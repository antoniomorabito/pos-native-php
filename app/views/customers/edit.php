<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row fade-in">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-edit text-warning me-2"></i>
                            Edit Customer
                        </h4>
                        <small class="text-muted">Update customer information</small>
                    </div>
                    <a href="<?= BASE_URL ?>customers" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Customers
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Customer Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Customer Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?= htmlspecialchars($customer['name']) ?>" required>
                                            <div class="invalid-feedback">Please provide a customer name.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?= htmlspecialchars($customer['email'] ?? '') ?>">
                                            <div class="invalid-feedback">Please provide a valid email.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number *</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   value="<?= htmlspecialchars($customer['phone']) ?>" required>
                                            <div class="invalid-feedback">Please provide a phone number.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select class="form-select" id="gender" name="gender">
                                                <option value="">Choose...</option>
                                                <option value="male" <?= $customer['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                                                <option value="female" <?= $customer['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="birth_date" class="form-label">Birth Date</label>
                                            <input type="date" class="form-control" id="birth_date" name="birth_date"
                                                   value="<?= $customer['birth_date'] ?>">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="join_date" class="form-label">Join Date *</label>
                                            <input type="date" class="form-control" id="join_date" name="join_date" 
                                                   value="<?= $customer['join_date'] ?>" required>
                                            <div class="invalid-feedback">Please provide a join date.</div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" 
                                                      rows="3"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Customer Settings -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Customer Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="points" class="form-label">Loyalty Points</label>
                                        <input type="number" class="form-control" id="points" name="points" 
                                               value="<?= $customer['points'] ?>" min="0">
                                        <small class="text-muted">Current loyalty points</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="discount_percent" class="form-label">Special Discount (%)</label>
                                        <input type="number" class="form-control" id="discount_percent" name="discount_percent" 
                                               value="<?= $customer['discount_percent'] ?? 0 ?>" min="0" max="100" step="0.1">
                                        <small class="text-muted">Special discount for this customer</small>
                                    </div>
                                    
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" <?= $customer['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active Customer
                                        </label>
                                    </div>
                                    <small class="text-muted">Inactive customers won't appear in customer list</small>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Customer Stats</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Purchases:</span>
                                        <span class="badge bg-primary"><?= $customerStats['total_purchases'] ?? 0 ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Spent:</span>
                                        <span class="badge bg-success"><?= formatCurrency($customerStats['total_spent'] ?? 0) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Current Points:</span>
                                        <span class="badge bg-warning" id="current-points"><?= $customer['points'] ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Last Purchase:</span>
                                        <span class="badge bg-info">
                                            <?= $customerStats['last_purchase'] ? formatDate($customerStats['last_purchase']) : 'Never' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="<?= BASE_URL ?>sales?customer_id=<?= $customer['id'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-shopping-cart"></i> New Sale
                                        </a>
                                        <button type="button" class="btn btn-info btn-sm" onclick="viewPurchaseHistory()">
                                            <i class="fas fa-history"></i> Purchase History
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="adjustPoints()">
                                            <i class="fas fa-coins"></i> Adjust Points
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="<?= BASE_URL ?>customers" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Customer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Points Adjustment Modal -->
<div class="modal fade" id="pointsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adjust Customer Points</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="pointsForm">
                    <div class="mb-3">
                        <label class="form-label">Current Points</label>
                        <input type="text" class="form-control" value="<?= $customer['points'] ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="adjustment_type" class="form-label">Adjustment Type</label>
                        <select class="form-select" id="adjustment_type" required>
                            <option value="">Choose...</option>
                            <option value="add">Add Points</option>
                            <option value="subtract">Subtract Points</option>
                            <option value="set">Set Points</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="adjustment_points" class="form-label">Points</label>
                        <input type="number" class="form-control" id="adjustment_points" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="adjustment_reason" class="form-label">Reason</label>
                        <textarea class="form-control" id="adjustment_reason" rows="2" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePointsAdjustment()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Update current points display
document.getElementById('points').addEventListener('input', function() {
    document.getElementById('current-points').textContent = this.value;
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, ''); // Remove non-digits
    
    // Format Indonesian phone number
    if (value.startsWith('62')) {
        // International format
        value = '+' + value;
    } else if (value.startsWith('0')) {
        // National format, keep as is
    } else if (value.length > 0) {
        // Add 0 prefix for mobile numbers
        value = '0' + value;
    }
    
    this.value = value;
});

function viewPurchaseHistory() {
    window.open('<?= BASE_URL ?>customers/history/<?= $customer['id'] ?>', '_blank');
}

function adjustPoints() {
    new bootstrap.Modal(document.getElementById('pointsModal')).show();
}

function savePointsAdjustment() {
    const form = document.getElementById('pointsForm');
    const formData = new FormData();
    
    formData.append('customer_id', '<?= $customer['id'] ?>');
    formData.append('type', document.getElementById('adjustment_type').value);
    formData.append('points', document.getElementById('adjustment_points').value);
    formData.append('reason', document.getElementById('adjustment_reason').value);
    
    fetch('<?= BASE_URL ?>customers/adjustPoints', {
        method: 'POST',
        body: formData
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
        alert('An error occurred while adjusting points.');
    });
}
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>