<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/db.php';
}
$sitePhone = getSetting($pdo, 'phone', '+(0) 123 458 985');
$siteEmail = getSetting($pdo, 'contact_email', 'support@fashion.com');
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['qty'];
    }
}
?>
<!-- Header Start -->
<header class="main-header">
    <div class="header-sticky">
        <nav class="navbar navbar-expand-lg">
            <div class="header-action-box">
                <!-- Logo Start -->
                <a class="navbar-brand" href="index.php">
                    <img src="images/logo.jpg" alt="ShopWithAustin">
                </a>
                <!-- Logo End -->

                <!-- Header Search Form Box Start -->
                <div class="header-search-form-box">
                    <form class="header-search-form" id="headerForm" action="products.php" method="GET">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="Search products...">
                            <button type="submit" class="header-search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <!-- Header Search Form Box End -->

                <!-- Header Action Details Start -->
                <div class="header-action-details mobile-hide">
                    <ul>
                        <li><a href="products.php"><i class="fa-solid fa-bag-shopping"></i> Shop</a></li>
                        <li><a href="account-dashboard.php"><i class="fa-regular fa-user"></i> Account</a></li>
                        <li><a href="account-wishlist.php"><i class="fa-regular fa-heart"></i> Wishlist</a></li>
                        <li><a href="cart.php"><i class="fa-solid fa-bag-shopping"></i> Cart <?= $cartCount > 0 ? "<span style='background:#16181D;color:#fff;border-radius:50%;padding:2px 7px;font-size:11px;margin-left:4px;'>$cartCount</span>" : '' ?></a></li>
                    </ul>
                </div>
                <!-- Header Action Details End -->
            </div>

            <!-- Main Menu Start -->
            <div class="main-menu">
                <div class="collapse navbar-collapse">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="products.php">Shop</a></li>
                            <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                            <li class="nav-item submenu"><a class="nav-link" href="#">My Account</a>
                                <ul>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <li class="nav-item"><a class="nav-link" href="account-dashboard.php">My Account</a></li>
                                        <li class="nav-item"><a class="nav-link" href="account-order.php">My Orders</a></li>
                                        <li class="nav-item"><a class="nav-link" href="account-wishlist.php">Wishlist</a></li>
                                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                                    <?php else: ?>
                                        <li class="nav-item"><a class="nav-link" href="login.php">Login / Register</a></li>
                                        <li class="nav-item"><a class="nav-link" href="forgot-password.php">Forgot Password</a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="header-contact-info">
                        <ul>
                            <li><i class="fa-solid fa-phone"></i> <a href="tel:<?= sanitize($sitePhone) ?>"><?= sanitize($sitePhone) ?></a></li>
                            <li><i class="fa-regular fa-envelope"></i> <a href="mailto:<?= sanitize($siteEmail) ?>"><?= sanitize($siteEmail) ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="navbar-toggle"></div>
            </div>
            <!-- Main Menu End -->
        </nav>
        <div class="responsive-menu"></div>
    </div>
</header>
<!-- Header End -->
