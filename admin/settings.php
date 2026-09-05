<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Settings';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $fields = ['site_name', 'site_url', 'contact_email', 'phone', 'address', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'from_email', 'from_name'];
    
    foreach ($fields as $field) {
        $value = trim($_POST[$field] ?? '');
        if ($field === 'smtp_password' && empty($value)) {
            continue;
        }
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$field, $value, $value]);
    }
    header('Location: settings.php?saved=1');
    exit;
}

if (isset($_GET['saved'])) {
    $success = 'Settings saved successfully.';
}

include 'includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success alert-custom"><?= $success ?></div>
<?php endif; ?>

<form method="POST">
    <?= csrfField() ?>
    <div class="form-section">
        <h5>General Settings</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Site Name</label>
                <input type="text" name="site_name" class="form-control" value="<?= sanitize(getSetting($pdo, 'site_name')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Site URL</label>
                <input type="url" name="site_url" class="form-control" value="<?= sanitize(getSetting($pdo, 'site_url')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Contact Email</label>
                <input type="email" name="contact_email" class="form-control" value="<?= sanitize(getSetting($pdo, 'contact_email')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= sanitize(getSetting($pdo, 'phone')) ?>">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2"><?= sanitize(getSetting($pdo, 'address')) ?></textarea>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5>SMTP Settings</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">SMTP Host</label>
                <input type="text" name="smtp_host" class="form-control" value="<?= sanitize(getSetting($pdo, 'smtp_host')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">SMTP Port</label>
                <input type="number" name="smtp_port" class="form-control" value="<?= sanitize(getSetting($pdo, 'smtp_port', '587')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">SMTP Username</label>
                <input type="text" name="smtp_username" class="form-control" value="<?= sanitize(getSetting($pdo, 'smtp_username')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">SMTP Password</label>
                <input type="password" name="smtp_password" class="form-control" placeholder="Leave empty to keep current password">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Encryption</label>
                <select name="smtp_encryption" class="form-select">
                    <option value="tls" <?= getSetting($pdo, 'smtp_encryption') === 'tls' ? 'selected' : '' ?>>TLS</option>
                    <option value="ssl" <?= getSetting($pdo, 'smtp_encryption') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                    <option value="none" <?= getSetting($pdo, 'smtp_encryption') === 'none' ? 'selected' : '' ?>>None</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">From Email</label>
                <input type="email" name="from_email" class="form-control" value="<?= sanitize(getSetting($pdo, 'from_email')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">From Name</label>
                <input type="text" name="from_name" class="form-control" value="<?= sanitize(getSetting($pdo, 'from_name')) ?>">
            </div>
        </div>
    </div>

    <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-check me-1"></i> Save Settings</button>
</form>

<?php include 'includes/footer.php'; ?>
