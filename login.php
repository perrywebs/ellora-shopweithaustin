<?php
require_once __DIR__ . '/init.php';

$loginError = '';
$registerError = '';
$registerSuccess = '';

if (isset($_SESSION['user_id'])) {
    header('Location: account-dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $loginError = 'Please enter email and password.';
        } else {
            $stmt = $pdo->prepare("SELECT id, name, email, password, status FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] !== 'active') {
                    $loginError = 'Your account has been disabled.';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    header('Location: account-dashboard.php');
                    exit;
                }
            } else {
                $loginError = 'Incorrect email or password.';
            }
        }
    }
    
    if (isset($_POST['register'])) {
        $name = trim($_POST['reg_name'] ?? '');
        $email = trim($_POST['reg_email'] ?? '');
        $password = $_POST['reg_password'] ?? '';
        
        if (empty($name)) $registerError = 'Name is required.';
        elseif (empty($email)) $registerError = 'Email is required.';
        elseif (empty($password)) $registerError = 'Password is required.';
        elseif (strlen($password) < 6) $registerError = 'Password must be at least 6 characters.';
        else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $registerError = 'An account with this email already exists.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, status, created_at) VALUES (?, ?, ?, 'active', NOW())");
                $stmt->execute([$name, $email, $hashed]);
                $registerSuccess = 'Account created successfully! You can now login.';
            }
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

    <!-- Page Login Section Start -->
    <div class="page-login light-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="login-content-box">
                        <!-- Login Form -->
                        <div class="login-content-form-item">
                            <form id="LoginForm" action="#" method="POST">
                                <div class="login-form-content">
                                    <div class="login-content-title-box">
                                        <h2 class="text-anime-style-3" data-cursor="-opaque">Login your account</h2>
                                        <p class="wow fadeInUp">Access your account to explore our latest collections, track your orders and manage your shopping experience.</p>
                                    </div>

                                    <?php if ($loginError): ?>
                                        <div class="alert alert-danger" style="border-radius:8px;"><?= sanitize($loginError) ?></div>
                                    <?php endif; ?>

                                    <div class="checkout-login-form wow fadeInUp" data-wow-delay="0.2s">
                                        <div class="form-group">
                                            <label>Email address *</label>
                                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?= sanitize($_POST['email'] ?? '') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Password *</label>
                                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                        </div>
                                        <div class="checkout-login-form-footer">
                                            <div class="checkout-login-btn">
                                                <button type="submit" name="login" class="btn-default">Login</button>
                                            </div>
                                        </div>
                                        <div class="login-content-form-btn">
                                            <a href="forgot-password.php">Forgot your password?</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Registration Form -->
                        <div class="login-content-form-item">
                            <form id="SignupForm" action="#" method="POST">
                                <div class="login-form-content">
                                    <div class="login-content-title-box">
                                        <h2 class="text-anime-style-3" data-cursor="-opaque">Sign up your account</h2>
                                        <p class="wow fadeInUp">Create your account to enjoy exclusive offers, track orders, and stay updated with the latest arrivals.</p>
                                    </div>

                                    <?php if ($registerError): ?>
                                        <div class="alert alert-danger" style="border-radius:8px;"><?= sanitize($registerError) ?></div>
                                    <?php endif; ?>
                                    <?php if ($registerSuccess): ?>
                                        <div class="alert alert-success" style="border-radius:8px;"><?= sanitize($registerSuccess) ?></div>
                                    <?php endif; ?>

                                    <div class="checkout-login-form wow fadeInUp" data-wow-delay="0.2s">
                                        <div class="form-group">
                                            <label>Full Name *</label>
                                            <input type="text" name="reg_name" class="form-control" placeholder="Enter your name" required value="<?= sanitize($_POST['reg_name'] ?? '') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Email address *</label>
                                            <input type="email" name="reg_email" class="form-control" placeholder="Enter your e-mail" required value="<?= sanitize($_POST['reg_email'] ?? '') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Password *</label>
                                            <input type="password" name="reg_password" class="form-control" placeholder="Enter password (min 6 characters)" required minlength="6">
                                        </div>
                                        <div class="login-form-info">
                                            <p>Your personal data will be used to support your experience throughout this site and for other purposes described in our <a href="privacy-policy.php">privacy policy.</a></p>
                                        </div>
                                        <div class="checkout-login-btn signup-form-btn">
                                            <button type="submit" name="register" class="btn-default">Register</button>
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
    <!-- Page Login Section End -->

    <?php include 'partials/footer.php'; ?>

    <?php include 'partials/scripts.php'; ?>
</body>
</html>
