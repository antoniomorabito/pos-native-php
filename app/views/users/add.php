<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row fade-in">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-plus text-primary me-2"></i>
                            Add New User
                        </h4>
                        <small class="text-muted">Create a new system user</small>
                    </div>
                    <a href="<?= BASE_URL ?>users" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <!-- User Information -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">User Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="full_name" class="form-label">Full Name *</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                                   placeholder="Enter full name" required>
                                            <div class="invalid-feedback">Please provide a full name.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">Username *</label>
                                            <input type="text" class="form-control" id="username" name="username" 
                                                   placeholder="Enter username" required>
                                            <div class="invalid-feedback">Please provide a username.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   placeholder="user@example.com" required>
                                            <div class="invalid-feedback">Please provide a valid email.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   placeholder="08xxxxxxxxxx">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Password *</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password" name="password" 
                                                       placeholder="Enter password" required minlength="6">
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                    <i class="fas fa-eye" id="password-eye"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback">Password must be at least 6 characters.</div>
                                            <small class="text-muted">Minimum 6 characters required</small>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="confirm_password" class="form-label">Confirm Password *</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                                       placeholder="Confirm password" required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                                    <i class="fas fa-eye" id="confirm_password-eye"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback">Passwords do not match.</div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" 
                                                      rows="2" placeholder="User address..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Settings -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">User Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">User Role *</label>
                                        <select class="form-select" id="role" name="role" required>
                                            <option value="">Choose role...</option>
                                            <option value="admin">Administrator</option>
                                            <option value="cashier">Cashier</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a user role.</div>
                                        <small class="text-muted">
                                            <strong>Admin:</strong> Full access<br>
                                            <strong>Cashier:</strong> POS & sales<br>
                                            <strong>Staff:</strong> Limited access
                                        </small>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" checked>
                                        <label class="form-check-label" for="is_active">
                                            Active User
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="force_password_change" name="force_password_change" 
                                               value="1">
                                        <label class="form-check-label" for="force_password_change">
                                            Force Password Change
                                        </label>
                                    </div>
                                    <small class="text-muted">User must change password on first login</small>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Permissions Preview</h5>
                                </div>
                                <div class="card-body">
                                    <div id="permissions-list">
                                        <small class="text-muted">Select a role to see permissions</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="<?= BASE_URL ?>users" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create User
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
            // Check password confirmation
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                document.getElementById('confirm_password').setCustomValidity('Passwords do not match');
            } else {
                document.getElementById('confirm_password').setCustomValidity('');
            }
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Password visibility toggle
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        eye.className = 'fas fa-eye';
    }
}

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

// Username validation (no spaces, special chars)
document.getElementById('username').addEventListener('input', function() {
    let value = this.value.toLowerCase().replace(/[^a-z0-9._]/g, '');
    this.value = value;
});

// Role change handler
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const permissionsList = document.getElementById('permissions-list');
    
    const permissions = {
        'admin': [
            '✓ Full system access',
            '✓ User management',
            '✓ Settings configuration',
            '✓ All reports',
            '✓ POS operations',
            '✓ Product management',
            '✓ Customer management'
        ],
        'cashier': [
            '✓ POS operations',
            '✓ Sales transactions',
            '✓ Customer lookup',
            '✓ Basic reports',
            '✗ User management',
            '✗ Settings access',
            '✗ Product management'
        ],
        'staff': [
            '✓ Product viewing',
            '✓ Customer viewing',
            '✓ Basic reports',
            '✗ POS operations',
            '✗ User management',
            '✗ Settings access',
            '✗ Product management'
        ]
    };
    
    if (permissions[role]) {
        permissionsList.innerHTML = permissions[role].map(perm => {
            const isAllowed = perm.startsWith('✓');
            return `<div class="mb-1 ${isAllowed ? 'text-success' : 'text-danger'}">${perm}</div>`;
        }).join('');
    } else {
        permissionsList.innerHTML = '<small class="text-muted">Select a role to see permissions</small>';
    }
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

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;
    let feedback = [];
    
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    if (password.length < 6) feedback.push('At least 6 characters');
    if (!password.match(/[a-z]/)) feedback.push('Lowercase letter');
    if (!password.match(/[A-Z]/)) feedback.push('Uppercase letter');
    if (!password.match(/[0-9]/)) feedback.push('Number');
    
    const colors = ['danger', 'danger', 'warning', 'info', 'success'];
    const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    
    // Update visual feedback if needed
    console.log(`Password strength: ${labels[strength - 1] || 'Very Weak'}`);
});
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>