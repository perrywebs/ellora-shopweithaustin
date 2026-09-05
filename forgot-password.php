<?php
require_once __DIR__ . '/init.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (empty($email)) {
        $error = 'Please enter your email address.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $success = 'If an account exists with that email, a password reset link has been sent.';
        } else {
            $success = 'If an account exists with that email, a password reset link has been sent.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">
<?php include 'partials/head.php'; ?>
<body>
    <?php include 'partials/topbar.php'; ?>
    <?php include 'partials/header.php'; ?>

    <div class="page-header dark-section parallaxie">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Forgot password</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Forgot Password</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-forgot-password light-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="forgot-password-content-box">
                        <div class="login-content-form-item">
                            <form method="POST">
                                <div class="login-form-content">
                                    <div class="login-content-title-box">
                                        <h2 class="text-anime-style-3" data-cursor="-opaque">Forgot your password</h2>
                                        <p class="wow fadeInUp">Forgot your password? Please enter your email address. You will receive a link to create a new password via email.</p>
                                    </div>

                                    <?php if ($success): ?><div class="alert alert-success" style="border-radius:8px;"><?= $success ?></div><?php endif; ?>
                                    <?php if ($error): ?><div class="alert alert-danger" style="border-radius:8px;"><?= $error ?></div><?php endif; ?>

                                    <div class="checkout-login-form wow fadeInUp" data-wow-delay="0.2s">
                                        <div class="form-group">
                                            <label>Email address *</label>
                                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?= sanitize($_POST['email'] ?? '') ?>">
                                        </div>
                                        <div class="checkout-login-btn reset-password-btn">
                                            <button type="submit" class="btn-default">Reset Password</button>
                                        </div>
                                        <div class="login-content-form-btn login-now-btn">
                                            <a href="login.php">Have Account? Login now</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>
    <?php include 'partials/scripts.php'; ?>
</body>
</html>
