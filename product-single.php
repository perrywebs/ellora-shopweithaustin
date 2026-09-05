<?php
require_once __DIR__ . '/init.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ?");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: 404.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY created_at");
$stmt->execute([$product['id']]);
$images = $stmt->fetchAll();

$related = $pdo->query("SELECT * FROM products WHERE id != {$product['id']} ORDER BY RAND() LIMIT 4")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zxx">

<?php include 'partials/head.php'; ?>

<body>

    <?php include 'partials/topbar.php'; ?>

    <?php include 'partials/header.php'; ?>

    <!-- Page Product Single Start -->
    <div class="page-product-single">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Product Single Content Start -->
                    <div class="page-product-single-content">
                        <!-- Product Single Breadcrumb List Start -->
                        <div class="product-single-breadcrumb-list">
                            <nav class="wow fadeInUp">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= sanitize($product['name']) ?></li>
                                </ol>
                            </nav>
                        </div>
                        <!-- Product Single Breadcrumb List End -->

                        <!-- Product Single Info Box Start -->
                        <div class="product-single-info-box">
                            <!-- Product Single Image Box Start -->
                            <div class="product-single-image-box wow fadeInUp">
                                <?php $allImages = array_merge([$product['main_image']], array_column($images, 'image')); ?>
                                <!-- Product Single Image Slider Start -->
                                <div class="swiper product-single-image-slider">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($allImages as $img): ?>
                                            <div class="swiper-slide">
                                                <figure>
                                                    <img src="uploads/products/<?= sanitize($img) ?>" alt="<?= sanitize($product['name']) ?>">
                                                </figure>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <!-- Product Single Image Slider End -->
                                
                                <!-- Product Single Image Item Start -->
                                <div class="swiper product-single-image-item">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($allImages as $img): ?>
                                            <div class="swiper-slide">
                                                <figure class="imgae-anime">
                                                    <img src="uploads/products/<?= sanitize($img) ?>" alt="<?= sanitize($product['name']) ?>">
                                                </figure>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <!-- Product Single Image Item End -->
                            </div>
                            <!-- Product Single Image Box End -->

                            <!-- Product Single Info Content Start -->
                            <div class="product-single-info-content">
                                <!-- Product Single title Start -->
                                <div class="product-single-title wow fadeInUp">
                                    <h1><?= sanitize($product['name']) ?></h1>
                                </div>
                                <!-- Product Single title End -->

                                <!-- Product Single Description Start -->
                                <div class="product-single-description wow fadeInUp">
                                    <?php if ($product['description']): ?>
                                        <p><?= nl2br(sanitize($product['description'])) ?></p>
                                    <?php endif; ?>
                                </div>
                                <!-- Product Single Description End -->

                                <!-- Product Single Price Start -->
                                <div class="product-single-price wow fadeInUp" data-wow-delay="0.2s">
                                    <h2>₦<?= number_format($product['sale_price'] ?: $product['price']) ?>
                                        <?php if ($product['sale_price']): ?>
                                            <sub>₦<?= number_format($product['price']) ?></sub>
                                        <?php endif; ?>
                                    </h2>
                                    <span>
                                        <?php if ($product['stock_status'] === 'in_stock'): ?>
                                            <span style="color:green;">✓ In Stock</span>
                                        <?php else: ?>
                                            <span style="color:red;">✗ Out of Stock</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <!-- Product Single Price End -->

                                <!-- Product Single Content Body Start -->
                                <div class="product-single-content-body wow fadeInUp" data-wow-delay="0.4s">
                                    <?php if ($product['stock_status'] === 'in_stock'): ?>
                                        <div class="qty-box">
                                            <button class="qty-btn minus" onclick="changeQty(-1)"><span>-</span></button>
                                            <input type="text" class="qty-input" value="1" readonly id="qtyInput">
                                            <button class="qty-btn plus" onclick="changeQty(1)"><span>+</span></button>
                                        </div>
                                        <div class="product-single-content-btn">
                                            <a href="#" class="btn-default" onclick="event.preventDefault(); addToCart(<?= $product['id'] ?>);">Add To cart</a>
                                        </div>
                                    <?php else: ?>
                                        <div class="product-single-content-btn">
                                            <span class="btn-default" style="opacity:0.5;cursor:not-allowed;">Out of Stock</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Product Single Content Body End -->

                                <!-- Product Single Content Footer Start -->
                                <div class="product-single-content-footer wow fadeInUp" data-wow-delay="0.6s">
                                    <div class="product-single-details-list">
                                        <ul>
                                            <li><span>SKU:</span> <?= $product['slug'] ?></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Product Single Content Footer End -->
                            </div>
                            <!-- Product Single Info Content End -->
                        </div>
                        <!-- Product Single Info Box End -->

                        <!-- Product Single Description Tab Start -->
                        <div class="product-single-review-box wow fadeInUp" data-wow-delay="0.2s">
                            <div class="product-single-review-tab tab-content" id="missionvision">
                                <div class="product-step-nav">
                                    <ul class="nav nav-tabs" id="mvTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="first-tab" data-bs-toggle="tab" data-bs-target="#first" type="button" role="tab">Product Description</button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-tab-item-box tab-pane fade show active" id="first" role="tabpanel">
                                    <div class="product-tab-item-content">
                                        <?php if ($product['description']): ?>
                                            <?= nl2br(sanitize($product['description'])) ?>
                                        <?php else: ?>
                                            <p>No description available for this product.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product Single Description Tab End -->
                    </div>
                    <!-- Page Product Single Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Products Single End -->

    <!-- Related Product Section Start -->
    <div class="related-products light-section">
        <div class="container">
            <div class="row section-row">
                <h2 data-cursor="-opaque">Related Products</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="related-product-items-list">
                        <?php foreach ($related as $r): ?>
                            <div class="product-item wow fadeInUp">
                                <div class="product-item-header">
                                    <div class="product-item-image">
                                        <a href="product-single.php?slug=<?= sanitize($r['slug']) ?>">
                                            <figure>
                                                <img src="uploads/products/<?= sanitize($r['main_image']) ?>" alt="<?= sanitize($r['name']) ?>">
                                            </figure>
                                        </a>
                                    </div>
                                    <div class="product-item-action">
                                        <ul>
                                            <?php if ($r['stock_status'] === 'in_stock'): ?>
                                                <li><a href="#" onclick="event.preventDefault(); addToCart(<?= $r['id'] ?>);"><img src="images/icon-cart-primary.svg" alt=""></a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-item-body">
                                    <div class="product-item-content">
                                        <h2 class="product-item-title"><a href="product-single.php?slug=<?= sanitize($r['slug']) ?>"><?= sanitize($r['name']) ?></a></h2>
                                    </div>
                                    <div class="product-item-price">
                                        <h3>
                                            ₦<?= number_format($r['sale_price'] ?: $r['price']) ?>
                                            <?php if ($r['sale_price']): ?>
                                                <span>₦<?= number_format($r['price']) ?></span>
                                            <?php endif; ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Related Product Section End -->

    <?php include 'partials/footer.php'; ?>

    <?php include 'partials/scripts.php'; ?>

    <script>
    function changeQty(delta) {
        var input = document.getElementById('qtyInput');
        var val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        input.value = val;
    }

    function addToCart(productId) {
        var qty = document.getElementById('qtyInput') ? document.getElementById('qtyInput').value : 1;
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
