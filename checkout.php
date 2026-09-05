<?php
require_once __DIR__ . '/init.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['qty'];
}

$siteEmail = getSetting($pdo, 'contact_email', 'support@fashion.com');
$sitePhone = getSetting($pdo, 'phone', '+00 123 - 456 - 789');
$siteName = getSetting($pdo, 'site_name', 'ShopWithAustin');
?>
<!DOCTYPE html>
<html lang="zxx">

<?php include 'partials/head.php'; ?>

<body>

    <?php include 'partials/topbar.php'; ?>

    <?php include 'partials/header.php'; ?>

    <!-- Page Checkout Start -->
    <div class="page-checkout">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="checkout-form-box">
                        <div class="checkout-bill-address-box wow fadeInUp">
                            <div class="checkout-bill-address-title">
                                <h2>Checkout</h2>
                            </div>

                            <div class="checkout-accordion-body-content mb-4">
                                <div class="alert alert-info" style="border-radius:10px;padding:25px;font-size:16px;background:#f0f7ff;border:1px solid #b6d4fe;">
                                    <h4 style="margin-bottom:10px;"><i class="fa-solid fa-info-circle me-2"></i>Manual Checkout</h4>
                                    <p style="margin-bottom:10px;">Checkout is currently completed manually. Please contact us to complete your purchase.</p>
                                    <p style="margin-bottom:0;"><strong>Contact us:</strong><br>
                                    Email: <a href="mailto:<?= sanitize($siteEmail) ?>"><?= sanitize($siteEmail) ?></a><br>
                                    Phone: <a href="tel:<?= sanitize($sitePhone) ?>"><?= sanitize($sitePhone) ?></a>
                                    </p>
                                </div>
                            </div>

                            <div class="checkout-bill-address-form">
                                <div class="row">                   
                                    <div class="form-group col-md-6">
                                        <label>First Name *</label>
                                        <input type="text" name="fname" class="form-control" id="fname" placeholder="Enter first name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Last Name *</label>
                                        <input type="text" name="lname" class="form-control" id="lname" placeholder="Enter last name">
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label>Email address *</label>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter e-mail">
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label>Phone Number*</label>
                                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label>Order notes (optional)</label>
                                        <textarea name="notes" class="form-control" id="notes" rows="4" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 mt-4">
                    <div class="page-single-sidebar right-side-sidebar">
                        <div class="checkout-sidebar-box wow fadeInUp" data-wow-delay="0.2s">
                            <div class="product-total-order-box">
                                <div class="product-total-order-title">
                                    <h3>Your Order</h3>
                                </div>
                                <div class="product-total-order-list">
                                    <div class="product-total-item-tag-list">
                                        <span class="product-total-item-tag">Product</span>
                                        <span class="product-total-item-tag">Subtotal</span>
                                    </div>

                                    <?php foreach ($cart as $item): ?>
                                        <div class="product-total-item">
                                            <div class="product-total-item-header">
                                                <div class="product-total-item-image">
                                                    <figure>
                                                        <img src="uploads/products/<?= sanitize($item['image']) ?>" alt="">
                                                    </figure>
                                                </div>
                                                <div class="product-total-item-title">
                                                    <p><?= sanitize($item['name']) ?> × <?= $item['qty'] ?></p>
                                                </div>
                                            </div>
                                            <div class="product-total-item-subtotal">
                                                <p>₦<?= number_format($item['price'] * $item['qty']) ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="all-product-total-list">
                                        <div class="all-product-total">
                                            <p>Subtotal <span>₦<?= number_format($subtotal) ?></span></p>
                                        </div>
                                        <div class="all-product-total">
                                            <p>Total <span>₦<?= number_format($subtotal) ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="place-order-button">
                                <a href="mailto:<?= sanitize($siteEmail) ?>?subject=Order%20from%20<?= urlencode($siteName) ?>" class="btn-default">Contact Us to Place Order</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Checkout End -->

    <?php include 'partials/footer.php'; ?>

    <?php include 'partials/scripts.php'; ?>
</body>
</html>
