<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Edit Product';
$errors = [];
$success = '';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY created_at");
$stmt->execute([$id]);
$images = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    if (isset($_POST['delete_image'])) {
        $imgId = (int)$_POST['delete_image'];
        $stmt = $pdo->prepare("SELECT image FROM product_images WHERE id = ? AND product_id = ?");
        $stmt->execute([$imgId, $id]);
        $img = $stmt->fetch();
        if ($img) {
            deleteFile('uploads/products/' . $img['image']);
            $pdo->prepare("DELETE FROM product_images WHERE id = ?")->execute([$imgId]);
        }
        header("Location: product-edit.php?id=$id&deleted=1");
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $sale_price = (float)($_POST['sale_price'] ?? 0);
    $stock_status = $_POST['stock_status'] ?? 'in_stock';
    $recommended = isset($_POST['recommended']) ? 1 : 0;

    if (empty($name)) $errors[] = 'Product name is required.';
    if ($price <= 0) $errors[] = 'Price must be greater than 0.';

    if (empty($errors)) {
        $slug = $product['slug'];
        if ($name !== $product['name']) {
            $slug = generateSlug($name, $pdo, $id);
        }

        $mainImage = $product['main_image'];
        if (!empty($_FILES['main_image']['name'])) {
            $upload = uploadImage($_FILES['main_image']);
            if ($upload['success']) {
                deleteFile('uploads/products/' . $product['main_image']);
                $mainImage = $upload['filename'];
            } else {
                $errors[] = $upload['error'];
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE products SET name=?, slug=?, description=?, price=?, sale_price=?, main_image=?, stock_status=?, recommended=?, updated_at=NOW() WHERE id=?");
            $stmt->execute([$name, $slug, $description, $price, $sale_price > 0 ? $sale_price : null, $mainImage, $stock_status, $recommended, $id]);

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
                            $pdo->prepare("INSERT INTO product_images (product_id, image, created_at) VALUES (?, ?, NOW())")->execute([$id, $upload['filename']]);
                        }
                    }
                }
            }

            header("Location: product-edit.php?id=$id&updated=1");
            exit;
        }
    }
    
    $product['name'] = $name;
    $product['description'] = $description;
    $product['price'] = $price;
    $product['sale_price'] = $sale_price;
    $product['stock_status'] = $stock_status;
    $product['recommended'] = $recommended;
}

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="page-header-title">Edit Product</div>
        <div class="page-header-subtitle">Update product: <?= sanitize($product['name']) ?></div>
    </div>
    <a href="products.php" class="btn-admin" style="background:#f0f0f0;color:#333;">← Back to Products</a>
</div>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success alert-custom">Product updated successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success alert-custom">Image deleted successfully.</div>
<?php endif; ?>

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
                <input type="text" name="name" class="form-control" required value="<?= sanitize($product['name']) ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Price (₦) *</label>
                <input type="number" name="price" class="form-control" step="0.01" min="0" required value="<?= $product['price'] ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Sale Price (₦)</label>
                <input type="number" name="sale_price" class="form-control" step="0.01" min="0" value="<?= $product['sale_price'] ?? '' ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5"><?= sanitize($product['description']) ?></textarea>
        </div>
    </div>

    <div class="form-section">
        <h5>Images</h5>
        <div class="mb-3">
            <label class="form-label">Main Image</label>
            <div class="d-flex align-items-center gap-3 mb-2">
                <img src="../uploads/products/<?= sanitize($product['main_image']) ?>" style="width:100px;height:100px;object-fit:cover;border-radius:8px;" alt="">
                <div>
                    <input type="file" name="main_image" class="form-control" accept="image/jpeg,image/jpg,image/png,image/webp">
                    <small class="text-muted">Leave empty to keep current image.</small>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Additional Images</label>
            <?php if ($images): ?>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <?php foreach ($images as $img): ?>
                        <div class="position-relative">
                            <img src="../uploads/products/<?= sanitize($img['image']) ?>" style="width:80px;height:80px;object-fit:cover;border-radius:8px;" alt="">
                            <form method="POST" class="position-absolute" style="top:-5px;right:-5px;">
                                <input type="hidden" name="delete_image" value="<?= $img['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm rounded-circle" style="width:22px;height:22px;padding:0;font-size:11px;" onclick="return confirm('Delete this image?')">×</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No additional images.</p>
            <?php endif; ?>
            <label class="form-label">Add More Images</label>
            <input type="file" name="additional_images[]" class="form-control" multiple accept="image/jpeg,image/jpg,image/png,image/webp">
        </div>
    </div>

    <div class="form-section">
        <h5>Status</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Stock Status</label>
                <select name="stock_status" class="form-select">
                    <option value="in_stock" <?= $product['stock_status'] === 'in_stock' ? 'selected' : '' ?>>In Stock</option>
                    <option value="out_of_stock" <?= $product['stock_status'] === 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">&nbsp;</label>
                <div class="form-check mt-2">
                    <input type="checkbox" name="recommended" class="form-check-input" id="recommended" <?= $product['recommended'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="recommended">Show in Recommended For You</label>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <small class="text-muted">Slug: <?= sanitize($product['slug']) ?> | URL: product-single.php?slug=<?= sanitize($product['slug']) ?></small>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-check me-1"></i> Update Product</button>
        <a href="products.php" class="btn-admin" style="background:#f0f0f0;color:#333;">Cancel</a>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
