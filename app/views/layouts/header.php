<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= ASSET_URL ?>css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= BASE_URL ?>dashboard">
                <i class="fas fa-store"></i> <?= APP_NAME ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>dashboard">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>sales">
                            <i class="fas fa-cash-register"></i> POS
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-box"></i> Products
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>products">All Products</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>products/add">Add Product</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>categories">Categories</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>customers">
                            <i class="fas fa-users"></i> Customers
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>reports/sales">Sales Report</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>reports/inventory">Inventory Report</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>reports/profit">Profit Report</a></li>
                        </ul>
                    </li>
                    <?php if ($_SESSION['user_role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>users">
                            <i class="fas fa-user-cog"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>settings">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar me-2">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </div>
                            <div class="user-info">
                                <div class="user-name"><?= $_SESSION['full_name'] ?></div>
                                <small class="user-role text-light opacity-75"><?= ucfirst($_SESSION['user_role']) ?></small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                            <li>
                                <div class="dropdown-header">
                                    <strong><?= $_SESSION['full_name'] ?></strong>
                                    <small class="text-muted d-block"><?= ucfirst($_SESSION['user_role']) ?></small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>auth/profile">
                                    <i class="fas fa-user me-2"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>settings">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= BASE_URL ?>auth/logout" onclick="return confirm('Are you sure you want to logout?')">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <!-- Flash Messages -->
    <?php $flash = $this->getFlash(); if ($flash): ?>
    <div class="container mt-3">
        <div class="alert alert-<?= $flash['type'] == 'error' ? 'danger' : $flash['type'] ?> alert-dismissible fade show" role="alert">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="<?= isset($_SESSION['user_id']) ? 'container-fluid py-4' : '' ?>">