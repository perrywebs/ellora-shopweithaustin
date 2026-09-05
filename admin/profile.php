<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Profile';
$admin = getAdmin();
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email)) $errors[] = 'Email is required.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE admins SET name=?, email=? WHERE id=?");
        $stmt->execute([$name, $email, $_SESSION['admin_id']]);
        $success = 'Profile updated successfully.';
        $admin['name'] = $name;
        $admin['email'] = $email;
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
        <h5>Profile Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-control" required value="<?= sanitize($admin['name']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" required value="<?= sanitize($admin['email']) ?>">
            </div>
        </div>
    </div>

    <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-check me-1"></i> Save Profile</button>
</form>

<?php include 'includes/footer.php'; ?>
