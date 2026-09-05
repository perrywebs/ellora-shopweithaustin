<?php
require_once __DIR__ . '/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['qty'] as $productId => $qty) {
            $productId = (int)$productId;
            $qty = max(1, (int)$qty);
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['qty'] = $qty;
            }
        }
        header('Location: cart.php?updated=1');
        exit;
    }
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
        header('Location: cart.php');
        exit;
    }
}

$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="zxx">

<?php include 'partials/head.php'; ?>

<body>

    <?php include 'partials/topbar.php'; ?>

    <?php include 'partials/header.php'; ?>

    <!-- Page Cart Section Start -->
    <div class="page-cart">
        <div class="container">
            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success" style="border-radius:8px;margin-bottom:20px;">Cart updated successfully.</div>
            <?php endif; ?>
            <div class="row">
                <div class="col-xl-8">
                    <!-- Cart Content Box Start -->
                    <div class="cart-content-box">
                        <div class="cart-item-table-box">
                            <?php if (empty($cart)): ?>
                                <div class="text-center py-5">
                                    <h3>Your cart is empty</h3>
                                    <p>Looks like you haven't added anything to your cart yet.</p>
                                    <a href="products.php" class="btn-default mt-3">Start Shopping</a>
                                </div>
                            <?php else: ?>
                                <form method="POST">
                                    <div class="cart-item-table wow fadeInUp">
                                        <div class="cart-item-header">
                                            <span class="product-header-tag">Product</span>
                                            <span class="price-header-tag">Price</span>
                                            <span class="quantity-header-tag">Quantity</span>
                                            <span class="subtotal-header-tag">Subtotal</span>
                                        </div>

                                        <?php foreach ($cart as $productId => $item): ?>
                                            <div class="cart-item">
                                                <div class="cart-item-image-content">
                                                    <div class="cart-item-image">
                                                        <figure>
                                                            <img src="uploads/products/<?= sanitize($item['image']) ?>" alt="<?= sanitize($item['name']) ?>">
                                                        </figure>
                                                    </div>
                                                    <div class="cart-item-info-content">
                                                        <div class="cart-item-title">
                                                            <p><a href="product-single.php?id=<?= $item['id'] ?>"><?= sanitize($item['name']) ?></a></p>
                                                        </div>
                                                        <div class="cart-item-price">
                                                            <p>₦<?= number_format($item['price']) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="cart-item-quantity-total">
                                                    <div class="cart-item-quantity">
                                                        <div class="qty-box">
                                                            <button type="button" class="qty-btn minus" onclick="updateCartItem(<?= $productId ?>, -1)"><span>-</span></button>
                                                            <input type="text" class="qty-input" value="<?= str_pad($item['qty'], 2, '0', STR_PAD_LEFT) ?>" readonly id="cart-qty-<?= $productId ?>">
                                                            <button type="button" class="qty-btn plus" onclick="updateCartItem(<?= $productId ?>, 1)"><span>+</span></button>
                                                        </div>
                                                    </div>
                                                    <div class="cart-item-subtotal">
                                                        <p id="cart-subtotal-<?= $productId ?>">₦<?= number_format($item['price'] * $item['qty']) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="cart-item-buttons wow fadeInUp" data-wow-delay="0.2s">
                                        <button type="submit" name="update_cart" class="btn-default btn-update">Update Cart</button>
                                        <button type="submit" name="clear_cart" class="btn-default btn-clear" onclick="return confirm('Clear all items from cart?')">Clear Cart</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Cart Content Box End -->
                </div>
                
                <div class="col-xl-4">
                    <div class="page-single-sidebar right-side-sidebar">
                        <div class="order-summary-box wow fadeInUp" data-wow-delay="0.2s">
                            <div class="order-summary-content-box">
                                <div class="order-summary-box-title">
                                    <h2>Order Summary</h2>
                                </div>
                                <div class="order-summary-promocode-box">
                                    <div class="order-summary-total">
                                        <h3>Subtotal</h3>
                                        <h3 id="cart-total">₦<?= number_format($subtotal) ?></h3>
                                    </div>
                                </div>
                                <div class="order-summary-total">
                                    <h3>Total</h3>
                                    <h3 id="cart-grand-total">₦<?= number_format($subtotal) ?></h3>
                                </div>
                            </div>

                            <?php if (!empty($cart)): ?>
                            <div class="order-checkout-button">
                                <a href="checkout.php" class="btn-default">Proceed to Checkout</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Cart Section End -->

    <?php include 'partials/footer.php'; ?>

    <?php include 'partials/scripts.php'; ?>

    <script>
    function updateCartItem(productId, delta) {
        var qtyEl = document.getElementById('cart-qty-' + productId);
        var val = parseInt(qtyEl.value) + delta;
        if (val < 1) val = 1;
        qtyEl.value = val.toString().padStart(2, '0');
        
        fetch('cart_handler.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=update&product_id=' + productId + '&qty=' + val
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cart-total').textContent = '₦' + Number(data.cart_total).toLocaleString();
                document.getElementById('cart-grand-total').textContent = '₦' + Number(data.cart_total).toLocaleString();
                var subEl = document.getElementById('cart-subtotal-' + productId);
                if (subEl) {
                    subEl.textContent = '₦' + (val * parseInt(subEl.textContent.replace(/[₦,]/g, '')) / (val - delta)).toLocaleString();
                }
            }
        });
    }
    </script>
</body>
</html>
