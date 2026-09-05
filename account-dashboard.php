<?php
require_once __DIR__ . '/init.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$_SESSION['user_id']]);
$user = $user->fetch();
?>
<!DOCTYPE html>
<html lang="zxx">

<?php include 'partials/head.php'; ?>

<body>

    <?php include 'partials/topbar.php'; ?>

    <?php include 'partials/header.php'; ?>

    <!-- Page My Account Start -->
    <div class="page-my-account light-section">
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
                    <div class="account-dashboard-detail-box wow fadeInUp" data-wow-delay="0.2s">
                        <p class="account-dashboard-detail-title">Hello <b><?= sanitize($user['name']) ?></b> ( not <?= sanitize($user['name']) ?>? <a href="logout.php">Log out</a> )</p>
                        <p>From your account dashboard you can view your <a href="account-order.php">recent orders</a>, manage your <a href="account-address.php">shipping and billing addresses</a>, and <a href="account-details.php">edit your password and account details.</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page My Account End -->

    <?php include 'partials/footer.php'; ?>

    <?php include 'partials/scripts.php'; ?>
</body>
</html>
