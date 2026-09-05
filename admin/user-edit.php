<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Edit User';
$errors = [];

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $status = $_POST['status'] ?? 'active';

    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email)) $errors[] = 'Email is required.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=?, status=? WHERE id=?");
        $stmt->execute([$name, $email, $phone, $status, $id]);
        header("Location: users.php?updated=1");
        exit;
    }
}

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="page-header-title">Edit User</div>
        <div class="page-header-subtitle">Update user: <?= sanitize($user['name']) ?></div>
    </div>
    <a href="users.php" class="btn-admin" style="background:#f0f0f0;color:#333;">← Back to Users</a>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger alert-custom">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= sanitize($e) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="POST">
    <?= csrfField() ?>
    <div class="form-section">
        <h5>User Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-control" required value="<?= sanitize($user['name']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" required value="<?= sanitize($user['email']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= sanitize($user['phone'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="disabled" <?= $user['status'] === 'disabled' ? 'selected' : '' ?>>Disabled</option>
                </select>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-check me-1"></i> Update User</button>
        <a href="users.php" class="btn-admin" style="background:#f0f0f0;color:#333;">Cancel</a>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
