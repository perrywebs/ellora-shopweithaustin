<!-- Main Footer Start -->
<?php
if (!isset($pdo)) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    require_once __DIR__ . '/../config/db.php';
}
$footerSiteName = getSetting($pdo, 'site_name', 'ShopWithAustin');
$footerLogo = getSetting($pdo, 'site_logo');
$footerLogoPath = $footerLogo ? $footerLogo : 'images/logo.jpg';
?>
<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="footer-links-box">
                    <div class="footer-links">
                        <a href="index.php" class="footer-brand-logo">
                            <img src="<?= sanitize($footerLogoPath) ?>" alt="<?= sanitize($footerSiteName) ?>" style="max-width: 130px; margin-bottom: 15px;">
                        </a>
                    </div>
                    <div class="footer-links">
                        <h2>Quick Links</h2>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="products.php">Shop All</a></li>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-links footer-newsletter-box">
                        <h2>Stay in Touch</h2>
                        <p>Subscribe for new arrivals, exclusive offers, and style inspiration.</p>
                        <form id="newslettersForm" class="footer-newsletter-form" action="#" method="POST">
                            <div class="form-group">
                                <input type="email" name="mail" class="form-control" id="mail" placeholder="Enter your email" required>
                                <button type="submit" class="newsletter-btn"><i class="fa-regular fa-paper-plane" style="color: white;"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="footer-copyright">
                    <div class="footer-copyright-text">
                        <p>&copy; <?= date('Y') ?> <?= sanitize($footerSiteName) ?>. All Rights Reserved.</p>
                    </div>
                    <div class="footer-social-links footer-social-links-desktop">
                        <ul>
                            <li><a href="#"><i class="fa-brands fa-pinterest-p"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Main Footer End -->
