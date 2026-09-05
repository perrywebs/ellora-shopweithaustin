<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Create Product';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $sale_price = (float)($_POST['sale_price'] ?? 0);
    $stock_status = $_POST['stock_status'] ?? 'in_stock';
    $recommended = isset($_POST['recommended']) ? 1 : 0;

    if (empty($name)) $errors[] = 'Product name is required.';
    if ($price <= 0) $errors[] = 'Price must be greater than 0.';
    if (empty($_FILES['main_image']['name'])) {
        $errors[] = 'Main image is required.';
    }

    if (empty($errors)) {
        $slug = generateSlug($name, $pdo);
        $mainImage = uploadImage($_FILES['main_image']);
        if (!$mainImage['success']) {
            $errors[] = $mainImage['error'];
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, price, sale_price, main_image, stock_status, recommended, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([$name, $slug, $description, $price, $sale_price > 0 ? $sale_price : null, $mainImage['filename'], $stock_status, $recommended]);
            $productId = $pdo->lastInsertId();

            if (!empty($_FILES['additional_images']['name'][0])) {
                foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp) {
                    if ($tmp) {
                        $file = [
                            'name' => $_FILES['additional_images']['name'][$key],
                            'type' => $_FILES['additional_images']['type'][$key],
                            'tmp_name' => $_FILES['additional_images']['tmp_name'][$key],
                            'size' => $_FILES['additional_images']['size'][$key],
                        ];
                        $upload = uploadImage($file);
                        if ($upload['success']) {
                            $pdo->prepare("INSERT INTO product_images (product_id, image, created_at) VALUES (?, ?, NOW())")->execute([$productId, $upload['filename']]);
                        }
                    }
                }
            }

            header('Location: products.php?created=1');
            exit;
        }
    }
}

include 'includes/header.php';
?>

<div class="page-header-title">Create Product</div>
<div class="page-header-subtitle mb-4">Add a new product to the catalog</div>

<?php if ($errors): ?>
    <div class="alert alert-danger alert-custom">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= sanitize($e) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <?= csrfField() ?>
    <div class="form-section">
        <h5>Basic Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" class="form-control" required value="<?= sanitize($_POST['name'] ?? '') ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Price (₦) *</label>
                <input type="number" name="price" class="form-control" step="0.01" min="0" required value="<?= sanitize($_POST['price'] ?? '') ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Sale Price (₦)</label>
                <input type="number" name="sale_price" class="form-control" step="0.01" min="0" value="<?= sanitize($_POST['sale_price'] ?? '') ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5"><?= sanitize($_POST['description'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="form-section">
        <h5>Images</h5>
        <div class="mb-3">
            <label class="form-label">Main Image *</label>
            <input type="file" name="main_image" class="form-control" accept="image/jpeg,image/jpg,image/png,image/webp" required>
            <small class="text-muted">JPG, JPEG, PNG, or WEBP. Max 5MB.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Additional Images</label>
            <input type="file" name="additional_images[]" class="form-control" multiple accept="image/jpeg,image/jpg,image/png,image/webp">
            <small class="text-muted">Optional. Select multiple files.</small>
        </div>
    </div>

    <div class="form-section">
        <h5>Status</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Stock Status</label>
                <select name="stock_status" class="form-select">
                    <option value="in_stock">In Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">&nbsp;</label>
                <div class="form-check mt-2">
                    <input type="checkbox" name="recommended" class="form-check-input" id="recommended">
                    <label class="form-check-label" for="recommended">Show in Recommended For You</label>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-check me-1"></i> Create Product</button>
        <a href="products.php" class="btn-admin" style="background:#f0f0f0;color:#333;">Cancel</a>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
