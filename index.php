<?php
require_once __DIR__ . '/init.php';

$newArrivals = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8")->fetchAll();
$recommended = $pdo->query("SELECT * FROM products WHERE recommended = 1 ORDER BY created_at DESC LIMIT 8")->fetchAll();
$heroImage = getSetting($pdo, 'hero_image');
$heroStyle = 'padding: 0px;';
if ($heroImage) {
    $heroStyle .= ' background: url(\'' . sanitize($heroImage) . '\') no-repeat; background-position: center center; background-size: contain;';
}
?>
<!DOCTYPE html>
<html lang="zxx">

<?php include 'partials/head.php'; ?>

<body>

    <?php include 'partials/topbar.php'; ?>

    <?php include 'partials/header.php'; ?>

    <!-- Hero Section Start -->
    <div class="hero dark-section parallaxie" style="<?= $heroStyle ?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <!-- Hero Content Box Start -->
                    <div class="hero-content-box">
                        <!-- Hero Sub Heading Start -->
                        <div class="hero-sub-heading">
                        </div>
                        <!-- Hero Sub Heading End -->

                        <!-- Section Title Start -->
                        <div class="section-title">
                        </div>
                        <!-- Section Title End -->
                    </div>
                    <!-- Hero Content Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Section End -->

    <!-- New Arrivals - Horizontal Product Rail Start -->
    <section class="product-rail-section">
        <div class="product-rail-container">
            <div class="product-rail-header">
                <h2 data-cursor="-opaque">New Arrivals</h2>
            </div>
            <div class="product-rail-track" id="newArrivalsTrack">
                <?php if (empty($newArrivals)): ?>
                    <p class="text-center w-100">No products available yet. Check back soon!</p>
                <?php else: ?>
                    <?php foreach ($newArrivals as $p): ?>
                        <a href="product-single.php?slug=<?= sanitize($p['slug']) ?>" class="product-rail-card <?= $p['stock_status'] === 'out_of_stock' ? 'is-out-of-stock' : '' ?>">
                            <div class="product-rail-card-image">
                                <img src="uploads/products/<?= sanitize($p['main_image']) ?>" alt="<?= sanitize($p['name']) ?>">
                                <div class="product-rail-card-actions">
                                    <?php if ($p['stock_status'] === 'in_stock'): ?>
                                        <span class="action-btn" title="Add to Cart" onclick="event.preventDefault(); addToCart(<?= $p['id'] ?>);"><img src="images/icon-cart-primary.svg" alt=""></span>
                                    <?php else: ?>
                                        <span class="action-btn action-btn-disabled" title="Out of Stock"><img src="images/icon-cart-primary.svg" alt=""></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="product-rail-card-info">
                                <h3 class="product-rail-title"><?= sanitize($p['name']) ?></h3>
                                <div class="product-rail-price">
                                    <span class="current-price">₦<?= number_format($p['sale_price'] ?: $p['price']) ?></span>
                                    <?php if ($p['sale_price']): ?>
                                        <span class="original-price">₦<?= number_format($p['price']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($p['stock_status'] === 'out_of_stock'): ?>
                                <span class="out-of-stock-badge">Out of Stock</span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- New Arrivals - Horizontal Product Rail End -->

    <!-- Recommended For You - Horizontal Product Rail Start -->
    <section class="product-rail-section">
        <div class="product-rail-container">
            <div class="product-rail-header">
                <h2 data-cursor="-opaque">Recommended For You</h2>
            </div>
            <div class="product-rail-track" id="recommendedTrack">
                <?php if (empty($recommended)): ?>
                    <p class="text-center w-100">No recommended products yet.</p>
                <?php else: ?>
                    <?php foreach ($recommended as $p): ?>
                        <a href="product-single.php?slug=<?= sanitize($p['slug']) ?>" class="product-rail-card <?= $p['stock_status'] === 'out_of_stock' ? 'is-out-of-stock' : '' ?>">
                            <div class="product-rail-card-image">
                                <img src="uploads/products/<?= sanitize($p['main_image']) ?>" alt="<?= sanitize($p['name']) ?>">
                                <div class="product-rail-card-actions">
                                    <?php if ($p['stock_status'] === 'in_stock'): ?>
                                        <span class="action-btn" title="Add to Cart" onclick="event.preventDefault(); addToCart(<?= $p['id'] ?>);"><img src="images/icon-cart-primary.svg" alt=""></span>
                                    <?php else: ?>
                                        <span class="action-btn action-btn-disabled" title="Out of Stock"><img src="images/icon-cart-primary.svg" alt=""></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="product-rail-card-info">
                                <h3 class="product-rail-title"><?= sanitize($p['name']) ?></h3>
                                <div class="product-rail-price">
                                    <span class="current-price">₦<?= number_format($p['sale_price'] ?: $p['price']) ?></span>
                                    <?php if ($p['sale_price']): ?>
                                        <span class="original-price">₦<?= number_format($p['price']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($p['stock_status'] === 'out_of_stock'): ?>
                                <span class="out-of-stock-badge">Out of Stock</span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- Recommended For You - Horizontal Product Rail End -->

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
