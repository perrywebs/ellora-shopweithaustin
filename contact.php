<?php
require_once __DIR__ . '/init.php';

$contactSuccess = '';
$contactError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($fname) || empty($email)) {
        $contactError = 'First name and email are required.';
    } else {
        $contactSuccess = 'Thank you for your message. We will get back to you soon.';
    }
}

$siteEmail = getSetting($pdo, 'contact_email', 'info@example.com');
$sitePhone = getSetting($pdo, 'phone', '+00 123 - 456 - 789');
?>
<!DOCTYPE html>
<html lang="zxx">
<?php include 'partials/head.php'; ?>
<body>
    <?php include 'partials/topbar.php'; ?>
    <?php include 'partials/header.php'; ?>

    <div class="page-contact-us light-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="contact-us-content-box">
                        <div class="section-title">
                            <span class="section-sub-title wow fadeInUp">Get in Touch</span>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Let's Start Your Fashion Journey Today</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Have questions about our products, orders, or collections? Our team is always ready to assist you with quick and friendly support.</p>
                        </div>

                        <div class="contact-info-body-box">
                            <div class="contact-info-list wow fadeInUp" data-wow-delay="0.4s">
                                <div class="contact-info-item">
                                    <div class="icon-box"><img src="images/icon-mail-primary.svg" alt=""></div>
                                    <div class="contact-info-item-content">
                                        <p>E-mail us</p>
                                        <h3><a href="mailto:<?= sanitize($siteEmail) ?>"><?= sanitize($siteEmail) ?></a></h3>
                                    </div>
                                </div>
                                <div class="contact-info-item">
                                    <div class="icon-box"><img src="images/icon-phone-primary.svg" alt=""></div>
                                    <div class="contact-info-item-content">
                                        <p>Talk to us</p>
                                        <h3><a href="tel:<?= sanitize($sitePhone) ?>"><?= sanitize($sitePhone) ?></a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="contact-us-social-links wow fadeInUp" data-wow-delay="0.6s">
                                <h3>Follow Now :</h3>
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
                <div class="col-xl-6">
                    <div class="contact-us-form wow fadeInUp" data-wow-delay="0.2s">
                        <?php if ($contactSuccess): ?><div class="alert alert-success" style="border-radius:8px;"><?= $contactSuccess ?></div><?php endif; ?>
                        <?php if ($contactError): ?><div class="alert alert-danger" style="border-radius:8px;"><?= $contactError ?></div><?php endif; ?>
                        <form method="POST">
                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <label>First Name:</label>
                                    <input type="text" name="fname" class="form-control" placeholder="Enter First Name *" required>
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label>Last Name:</label>
                                    <input type="text" name="lname" class="form-control" placeholder="Enter Last Name">
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label>Phone Number:</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number">
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label>Email Address:</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter Email Address *" required>
                                </div>
                                <div class="form-group col-md-12 mb-5">
                                    <label>Message:</label>
                                    <textarea name="message" class="form-control" rows="6" placeholder="Any Additional Message..."></textarea>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn-default">submit Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>
    <?php include 'partials/scripts.php'; ?>
</body>
</html>
