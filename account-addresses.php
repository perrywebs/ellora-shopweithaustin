<?php
require_once __DIR__ . '/init.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="zxx">
<?php include 'partials/head.php'; ?>
<body>
    <?php include 'partials/topbar.php'; ?>
    <?php include 'partials/header.php'; ?>
    <div class="page-account-addresses light-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="page-single-sidebar">
                        <div class="my-account-sidebar-item wow fadeInUp">
                            <ul>
                                <li><a href="account-dashboard.php"><img src="images/icon-dashboard-primary.svg" alt="">Dashboard</a></li>
                                <li><a href="account-order.php"><img src="images/icon-cart-primary.svg" alt="">Orders</a></li>
                                <li><a href="account-address.php"><img src="images/icon-location-primary.svg" alt="">Addresses</a></li>
                                <li><a href="account-details.php"><img src="images/icon-user.svg" alt="">Account details</a></li>
                                <li><a href="account-wishlist.php"><img src="images/icon-wishlist-primary.svg" alt="">Wishlist</a></li>
                                <li><a href="logout.php"><img src="images/icon-logout-primary.svg" alt="">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="text-center py-5">
                        <h3>No addresses saved</h3>
                        <p>Address management will be available when the order system is implemented.</p>
                        <a href="products.php" class="btn-default mt-3">Back to Shop</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
    <?php include 'partials/scripts.php'; ?>
</body>
</html>
