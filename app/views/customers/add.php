<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row fade-in">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-plus text-primary me-2"></i>
                            Add New Customer
                        </h4>
                        <small class="text-muted">Create a new customer profile</small>
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
                                                   placeholder="Enter customer name" required>
                                            <div class="invalid-feedback">Please provide a customer name.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   placeholder="customer@example.com">
                                            <div class="invalid-feedback">Please provide a valid email.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number *</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   placeholder="08xxxxxxxxxx" required>
                                            <div class="invalid-feedback">Please provide a phone number.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select class="form-select" id="gender" name="gender">
                                                <option value="">Choose...</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="birth_date" class="form-label">Birth Date</label>
                                            <input type="date" class="form-control" id="birth_date" name="birth_date">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="join_date" class="form-label">Join Date *</label>
                                            <input type="date" class="form-control" id="join_date" name="join_date" 
                                                   value="<?= date('Y-m-d') ?>" required>
                                            <div class="invalid-feedback">Please provide a join date.</div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" 
                                                      rows="3" placeholder="Customer address..."></textarea>
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
                                        <label for="points" class="form-label">Initial Points</label>
                                        <input type="number" class="form-control" id="points" name="points" 
                                               value="0" min="0">
                                        <small class="text-muted">Starting loyalty points</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="discount_percent" class="form-label">Special Discount (%)</label>
                                        <input type="number" class="form-control" id="discount_percent" name="discount_percent" 
                                               value="0" min="0" max="100" step="0.1">
                                        <small class="text-muted">Special discount for this customer</small>
                                    </div>
                                    
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" checked>
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
                                        <span class="badge bg-primary">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Spent:</span>
                                        <span class="badge bg-success">Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Current Points:</span>
                                        <span class="badge bg-warning" id="current-points">0</span>
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
                                    <i class="fas fa-save"></i> Save Customer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>