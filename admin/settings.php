<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Settings';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    if (isset($_POST['action'])) {

        if ($_POST['action'] === 'upload_hero') {
            if (!isset($_FILES['hero_image']) || $_FILES['hero_image']['error'] === UPLOAD_ERR_NO_FILE) {
                $error = 'No file selected.';
            } else {
                $result = uploadImage($_FILES['hero_image'], 'uploads/hero');
                if ($result['success']) {
                    $oldHero = getSetting($pdo, 'hero_image');
                    if ($oldHero) {
                        deleteFile($oldHero);
                    }
                    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                    $stmt->execute(['hero_image', $result['path'], $result['path']]);
                    header('Location: settings.php?saved=1');
                    exit;
                } else {
                    $error = $result['error'];
                }
            }
        }

        if ($_POST['action'] === 'remove_hero') {
            $heroPath = getSetting($pdo, 'hero_image');
            if ($heroPath) {
                deleteFile($heroPath);
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute(['hero_image', '', '']);
            }
            header('Location: settings.php?removed=hero');
            exit;
        }

        if ($_POST['action'] === 'upload_logo') {
            if (!isset($_FILES['site_logo']) || $_FILES['site_logo']['error'] === UPLOAD_ERR_NO_FILE) {
                $error = 'No file selected.';
            } else {
                $logoAllowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon'];
                $result = uploadImage($_FILES['site_logo'], 'uploads/logo', $logoAllowed);
                if ($result['success']) {
                    $oldLogo = getSetting($pdo, 'site_logo');
                    if ($oldLogo) {
                        deleteFile($oldLogo);
                    }
                    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                    $stmt->execute(['site_logo', $result['path'], $result['path']]);
                    header('Location: settings.php?saved=logo');
                    exit;
                } else {
                    $error = $result['error'];
                }
            }
        }

        if ($_POST['action'] === 'remove_logo') {
            $logoPath = getSetting($pdo, 'site_logo');
            if ($logoPath) {
                deleteFile($logoPath);
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute(['site_logo', '', '']);
            }
            header('Location: settings.php?removed=logo');
            exit;
        }

        if ($_POST['action'] === 'save_settings') {
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
    }
}

if (isset($_GET['saved'])) {
    if ($_GET['saved'] === 'logo') {
        $success = 'Logo updated successfully.';
    } else {
        $success = 'Settings saved successfully.';
    }
}
if (isset($_GET['removed'])) {
    if ($_GET['removed'] === 'logo') {
        $success = 'Logo removed.';
    } else {
        $success = 'Hero image removed.';
    }
}

include 'includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success alert-custom"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-custom"><?= $error ?></div>
<?php endif; ?>

<div class="form-section">
    <h5>Hero Section</h5>
    <?php $heroImage = getSetting($pdo, 'hero_image'); ?>
    <?php if ($heroImage): ?>
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="../<?= sanitize($heroImage) ?>" alt="Hero Image" style="max-width: 400px; max-height: 250px; border-radius: 8px; border: 1px solid #e0e0e0; object-fit: cover;">
        </div>
    <?php else: ?>
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <p class="text-muted" style="font-size: 14px; margin: 0;">No hero image uploaded. The default background will be used.</p>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="mb-3">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="upload_hero">
        <div class="row align-items-end">
            <div class="col-md-6 mb-2">
                <label class="form-label">Choose New Image</label>
                <input type="file" name="hero_image" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                <small class="text-muted">Allowed: JPG, JPEG, PNG, WEBP. Max 5MB.</small>
            </div>
            <div class="col-md-6 mb-2">
                <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-upload me-1"></i> Upload &amp; Save</button>
            </div>
        </div>
    </form>

    <?php if ($heroImage): ?>
        <form method="POST">
            <?= csrfField() ?>
            <input type="hidden" name="action" value="remove_hero">
            <button type="submit" class="btn-admin btn-admin-sm" style="background: #dc3545; color: #fff;" onclick="return confirm('Remove the current hero image?')"><i class="fa-solid fa-trash me-1"></i> Remove Image</button>
        </form>
    <?php endif; ?>
</div>

<div class="form-section">
    <h5>Logo</h5>
    <?php $siteLogo = getSetting($pdo, 'site_logo'); ?>
    <?php if ($siteLogo): ?>
        <div class="mb-3">
            <label class="form-label">Current Logo</label><br>
            <img src="../<?= sanitize($siteLogo) ?>" alt="Site Logo" style="max-width: 200px; max-height: 100px; border-radius: 8px; border: 1px solid #e0e0e0; object-fit: contain; background: #f8f9fa; padding: 8px;">
        </div>
    <?php else: ?>
        <div class="mb-3">
            <label class="form-label">Current Logo</label><br>
            <p class="text-muted" style="font-size: 14px; margin: 0;">No custom logo uploaded. The default logo will be used.</p>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="mb-3">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="upload_logo">
        <div class="row align-items-end">
            <div class="col-md-6 mb-2">
                <label class="form-label">Choose New Logo</label>
                <input type="file" name="site_logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.ico" required>
                <small class="text-muted">Allowed: JPG, JPEG, PNG, WEBP, ICO. Max 5MB.</small>
            </div>
            <div class="col-md-6 mb-2">
                <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-upload me-1"></i> Upload &amp; Save</button>
            </div>
        </div>
    </form>

    <?php if ($siteLogo): ?>
        <form method="POST">
            <?= csrfField() ?>
            <input type="hidden" name="action" value="remove_logo">
            <button type="submit" class="btn-admin btn-admin-sm" style="background: #dc3545; color: #fff;" onclick="return confirm('Remove the custom logo? The default logo will be restored.')"><i class="fa-solid fa-trash me-1"></i> Remove Logo</button>
        </form>
    <?php endif; ?>
</div>

<form method="POST">
    <?= csrfField() ?>
    <input type="hidden" name="action" value="save_settings">
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
