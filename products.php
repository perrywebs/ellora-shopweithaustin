<?php
require_once __DIR__ . '/init.php';

$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

if ($search) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name LIKE ? OR description LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
    $total = $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $total = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
}
$products = $stmt->fetchAll();
$totalPages = ceil($total / $perPage);
?>
<!DOCTYPE html>
<html lang="zxx">

<?php include 'partials/head.php'; ?>

<body>

    <?php include 'partials/topbar.php'; ?>

    <?php include 'partials/header.php'; ?>

    <!-- Page Products Section Start -->
    <div class="page-products">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Product item List Box Start -->
                    <div class="product-item-list-box">
                        <!-- Product Category Filter Header Start -->
                        <div class="product-category-filter-header wow fadeInUp">
                            <div class="product-category-filter-title">
                                <h2>Showing <?= count($products) ?> of <?= $total ?> results</h2>
                            </div>
                            <div class="product-category-result-info">
                                <?php if ($search): ?>
                                    <span class="me-3">Search: "<?= sanitize($search) ?>" <a href="products.php" style="color:#999;">&times;</a></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Product Category Filter Header End -->

                        <!-- Product item Boxes Start -->
                        <div class="product-item-boxes">
                            <?php if (empty($products)): ?>
                                <div class="text-center py-5">
                                    <h3>No products found</h3>
                                    <p>Check back soon for new arrivals!</p>
                                    <a href="products.php" class="btn-default mt-3">View All Products</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($products as $i => $p): ?>
                                    <div class="product-item wow fadeInUp" data-wow-delay="<?= ($i % 4) * 0.2 ?>s">
                                        <div class="product-item-header">
                                            <div class="product-item-image">
                                                <a href="product-single.php?slug=<?= sanitize($p['slug']) ?>">
                                                    <figure>
                                                        <img src="uploads/products/<?= sanitize($p['main_image']) ?>" alt="<?= sanitize($p['name']) ?>">
                                                    </figure>
                                                </a>
                                            </div>
                                            <div class="product-item-action">
                                                <ul>
                                                    <?php if ($p['stock_status'] === 'in_stock'): ?>
                                                        <li><a href="#" onclick="event.preventDefault(); addToCart(<?= $p['id'] ?>);"><img src="images/icon-cart-primary.svg" alt=""></a></li>
                                                    <?php else: ?>
                                                        <li><span style="opacity:0.4;"><img src="images/icon-cart-primary.svg" alt=""></span></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                            <?php if ($p['stock_status'] === 'out_of_stock'): ?>
                                                <span class="out-of-stock-badge" style="position:absolute;top:15px;left:15px;">Out of Stock</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-item-body">
                                            <div class="product-item-content">
                                                <h2 class="product-item-title"><a href="product-single.php?slug=<?= sanitize($p['slug']) ?>"><?= sanitize($p['name']) ?></a></h2>
                                            </div>
                                            <div class="product-item-price">
                                                <h3>
                                                    ₦<?= number_format($p['sale_price'] ?: $p['price']) ?>
                                                    <?php if ($p['sale_price']): ?>
                                                        <span>₦<?= number_format($p['price']) ?></span>
                                                    <?php endif; ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <!-- Product item Boxes End -->

                        <?php if ($totalPages > 1): ?>
                        <div class="product-learn-more-btn wow fadeInUp" data-wow-delay="0.2s">
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>&search=<?= sanitize($search) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!-- Product item List Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Products Section End -->

    <?php include 'partials/footer.php'; ?>

    <?php include 'partials/scripts.php'; ?>

    <script>
    function addToCart(productId, qty) {
        qty = qty || 1;
        fetch('cart_handler.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=add&product_id=' + productId + '&qty=' + qty
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('Added to cart!');
                location.reload();
            } else {
                alert(data.message || 'Could not add to cart.');
            }
        });
    }
    </script>
</body>
</html>
