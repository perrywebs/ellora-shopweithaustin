<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Security';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($current)) $errors[] = 'Current password is required.';
    if (empty($new)) $errors[] = 'New password is required.';
    if ($new !== $confirm) $errors[] = 'New passwords do not match.';
    if (strlen($new) < 6) $errors[] = 'New password must be at least 6 characters.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
        $stmt->execute([$_SESSION['admin_id']]);
        $admin = $stmt->fetch();

        if (!password_verify($current, $admin['password'])) {
            $errors[] = 'Current password is incorrect.';
        } else {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $_SESSION['admin_id']]);
            $success = 'Password changed successfully.';
        }
    }
}

include 'includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success alert-custom"><?= $success ?></div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-danger alert-custom">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= sanitize($e) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="POST">
    <?= csrfField() ?>
    <div class="form-section">
        <h5>Change Password</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Current Password *</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">New Password *</label>
                <input type="password" name="new_password" class="form-control" required minlength="6">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirm New Password *</label>
                <input type="password" name="confirm_password" class="form-control" required minlength="6">
            </div>
        </div>
    </div>

    <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-check me-1"></i> Change Password</button>
</form>

<?php include 'includes/footer.php'; ?>
