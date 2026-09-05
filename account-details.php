<?php
require_once __DIR__ . '/init.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$_SESSION['user_id']]);
$user = $user->fetch();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($name) || empty($email)) {
        $error = 'Name and email are required.';
    } else {
        $pdo->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?")->execute([$name, $email, $phone, $_SESSION['user_id']]);
        $_SESSION['user_name'] = $name;
        $success = 'Account details updated successfully.';
        $user['name'] = $name;
        $user['email'] = $email;
        $user['phone'] = $phone;
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">
<?php include 'partials/head.php'; ?>
<body>
    <?php include 'partials/topbar.php'; ?>
    <?php include 'partials/header.php'; ?>

    <div class="page-account-details light-section">
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
                    <div class="account-details-content-box">
                        <?php if ($success): ?><div class="alert alert-success" style="border-radius:8px;"><?= $success ?></div><?php endif; ?>
                        <?php if ($error): ?><div class="alert alert-danger" style="border-radius:8px;"><?= $error ?></div><?php endif; ?>
                        <form class="checkout-bill-address-form" method="POST">
                            <div class="account-details-content-item wow fadeInUp">
                                <div class="checkout-bill-address-title"><h2>Account details</h2></div>
                                <div class="checkout-bill-address-form">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Name *</label>
                                            <input type="text" name="name" class="form-control" required value="<?= sanitize($user['name']) ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email address *</label>
                                            <input type="email" name="email" class="form-control" required value="<?= sanitize($user['email']) ?>">
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control" value="<?= sanitize($user['phone'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout-login-btn wow fadeInUp" data-wow-delay="0.4s">
                                <button type="submit" class="btn-default">Save Changes</button>
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
