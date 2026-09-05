<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Dashboard';

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$inStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_status = 'in_stock'")->fetchColumn();
$outOfStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_status = 'out_of_stock'")->fetchColumn();
$recommended = $pdo->query("SELECT COUNT(*) FROM products WHERE recommended = 1")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3><?= $totalProducts ?></h3>
                    <p>Total Products</p>
                </div>
                <span class="icon"><i class="fa-solid fa-box"></i></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3><?= $inStock ?></h3>
                    <p>Products In Stock</p>
                </div>
                <span class="icon"><i class="fa-solid fa-check-circle"></i></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3><?= $outOfStock ?></h3>
                    <p>Products Out of Stock</p>
                </div>
                <span class="icon"><i class="fa-solid fa-xmark-circle"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3><?= $recommended ?></h3>
                    <p>Recommended Products</p>
                </div>
                <span class="icon"><i class="fa-solid fa-star"></i></span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3><?= $totalUsers ?></h3>
                    <p>Total Users</p>
                </div>
                <span class="icon"><i class="fa-solid fa-users"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="table-container">
            <h5 style="font-size:16px;font-weight:600;margin-bottom:15px;">Recent Products</h5>
            <table class="table">
                <thead><tr><th>Product</th><th>Price</th><th>Status</th></tr></thead>
                <tbody>
                <?php
                $recent = $pdo->query("SELECT name, price, sale_price, stock_status, main_image FROM products ORDER BY created_at DESC LIMIT 5");
                while ($p = $recent->fetch()):
                ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="../uploads/products/<?= sanitize($p['main_image']) ?>" class="product-thumb" alt="">
                                <span><?= sanitize($p['name']) ?></span>
                            </div>
                        </td>
                        <td><?= $p['sale_price'] ? '₦' . number_format($p['sale_price']) : '₦' . number_format($p['price']) ?></td>
                        <td>
                            <?php if ($p['stock_status'] === 'in_stock'): ?>
                                <span class="badge-stock badge-in">In Stock</span>
                            <?php else: ?>
                                <span class="badge-stock badge-out">Out of Stock</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="table-container">
            <h5 style="font-size:16px;font-weight:600;margin-bottom:15px;">Recent Users</h5>
            <table class="table">
                <thead><tr><th>Name</th><th>Email</th><th>Status</th></tr></thead>
                <tbody>
                <?php
                $recentUsers = $pdo->query("SELECT name, email, status FROM users ORDER BY created_at DESC LIMIT 5");
                while ($u = $recentUsers->fetch()):
                ?>
                    <tr>
                        <td><?= sanitize($u['name']) ?></td>
                        <td><?= sanitize($u['email']) ?></td>
                        <td>
                            <?php if ($u['status'] === 'active'): ?>
                                <span class="badge-stock badge-in">Active</span>
                            <?php else: ?>
                                <span class="badge-stock badge-out">Disabled</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
