<?php
require_once __DIR__ . '/init.php';
?>
<!DOCTYPE html>
<html lang="zxx">

<?php include 'partials/head.php'; ?>

<body>

    <?php include 'partials/topbar.php'; ?>

    <?php include 'partials/header.php'; ?>

    <!-- Page Order Receive Start -->
    <div class="page-order-receive">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="text-center py-5">
                        <h2>Order Received</h2>
                        <p class="mt-3" style="font-size:18px;">Thank you for your interest. Our checkout system is being finalized.</p>
                        <p style="font-size:16px;color:#666;">Please contact us to complete your purchase manually.</p>
                        <a href="products.php" class="btn-default mt-3">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Order Receive End -->

    <?php include 'partials/footer.php'; ?>

    <?php include 'partials/scripts.php'; ?>
</body>
</html>
