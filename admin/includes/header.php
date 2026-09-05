<?php
requireAdmin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$admin = getAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= $pageTitle ?? 'Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f4f6f9; }
        .sidebar {
            position: fixed; top: 0; left: 0; width: 260px; height: 100vh;
            background: #1a1d24; color: #fff; padding: 0; z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 25px 20px; border-bottom: 1px solid rgba(255,255,255,0.1);
            font-size: 20px; font-weight: 700;
        }
        .sidebar-brand a { color: #fff; text-decoration: none; }
        .sidebar-menu { padding: 15px 0; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 25px; color: #a0a3ab; text-decoration: none;
            font-size: 14px; font-weight: 500; transition: all 0.2s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            color: #fff; background: rgba(255,255,255,0.08);
        }
        .sidebar-menu a i { width: 20px; text-align: center; font-size: 16px; }
        .sidebar-section {
            padding: 15px 25px 8px; font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px; color: #666;
        }
        .main-content {
            margin-left: 260px; padding: 25px 30px; min-height: 100vh;
        }
        .topbar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px; background: #fff; padding: 15px 25px;
            border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .topbar h4 { font-size: 18px; font-weight: 600; color: #1a1d24; margin: 0; }
        .topbar-user { display: flex; align-items: center; gap: 10px; }
        .topbar-user a { color: #666; text-decoration: none; font-size: 14px; }
        .card-stat {
            background: #fff; border-radius: 10px; padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px;
        }
        .card-stat h3 { font-size: 32px; font-weight: 700; color: #1a1d24; margin: 0; }
        .card-stat p { font-size: 14px; color: #666; margin: 5px 0 0; }
        .card-stat .icon { font-size: 24px; color: #1a1d24; opacity: 0.3; }
        .table-container {
            background: #fff; border-radius: 10px; padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .table-container table { margin: 0; }
        .table-container th { font-size: 12px; font-weight: 600; text-transform: uppercase; color: #999; border-bottom: 2px solid #f0f0f0; }
        .table-container td { font-size: 14px; color: #333; vertical-align: middle; }
        .badge-stock { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-in { background: #d4edda; color: #155724; }
        .badge-out { background: #f8d7da; color: #721c24; }
        .btn-admin {
            padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 500;
            border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-block;
        }
        .btn-admin-primary { background: #1a1d24; color: #fff; }
        .btn-admin-primary:hover { background: #2d3139; color: #fff; }
        .btn-admin-sm { padding: 5px 12px; font-size: 12px; }
        .form-section {
            background: #fff; border-radius: 10px; padding: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 25px;
        }
        .form-section h5 { font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; }
        .form-label { font-size: 13px; font-weight: 600; color: #333; }
        .form-control, .form-select {
            border: 1px solid #e0e0e0; border-radius: 6px; padding: 10px 14px;
            font-size: 14px; transition: border-color 0.2s;
        }
        .form-control:focus, .form-select:focus { border-color: #1a1d24; box-shadow: 0 0 0 2px rgba(26,29,36,0.1); }
        .page-header-title { font-size: 24px; font-weight: 700; color: #1a1d24; margin-bottom: 5px; }
        .page-header-subtitle { font-size: 14px; color: #666; }
        .alert-custom { border-radius: 8px; font-size: 14px; padding: 12px 16px; }
        .product-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .action-links a { color: #1a1d24; text-decoration: none; margin-right: 10px; font-size: 14px; }
        .action-links a:hover { color: #000; text-decoration: underline; }
        .switch-label { font-size: 13px; color: #666; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <a href="dashboard.php"><i class="fa-solid fa-store me-2"></i>Ellora Admin</a>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
            <div class="sidebar-section">Catalog</div>
            <a href="products.php" class="<?= in_array($currentPage, ['products', 'product-create', 'product-edit']) ? 'active' : '' ?>">
                <i class="fa-solid fa-box"></i> Products
            </a>
            <a href="product-create.php" class="<?= $currentPage === 'product-create' ? 'active' : '' ?>">
                <i class="fa-solid fa-plus"></i> Add Product
            </a>
            <div class="sidebar-section">Users</div>
            <a href="users.php" class="<?= in_array($currentPage, ['users', 'user-edit']) ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i> Users
            </a>
            <div class="sidebar-section">Configuration</div>
            <a href="settings.php" class="<?= $currentPage === 'settings' ? 'active' : '' ?>">
                <i class="fa-solid fa-gear"></i> Settings
            </a>
            <div class="sidebar-section">Account</div>
            <a href="profile.php" class="<?= $currentPage === 'profile' ? 'active' : '' ?>">
                <i class="fa-solid fa-user"></i> Profile
            </a>
            <a href="security.php" class="<?= $currentPage === 'security' ? 'active' : '' ?>">
                <i class="fa-solid fa-shield-halved"></i> Security
            </a>
            <a href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>
    <div class="main-content">
        <div class="topbar">
            <h4><?= $pageTitle ?? 'Dashboard' ?></h4>
            <div class="topbar-user">
                <i class="fa-solid fa-user-circle"></i>
                <span><?= sanitize($admin['name'] ?? 'Admin') ?></span>
                <a href="logout.php" class="ms-2"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>
