<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Products';

if (isset($_GET['toggle_stock'])) {
    $id = (int)$_GET['toggle_stock'];
    $stmt = $pdo->prepare("UPDATE products SET stock_status = IF(stock_status='in_stock','out_of_stock','in_stock') WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: products.php');
    exit;
}

if (isset($_GET['toggle_recommended'])) {
    $id = (int)$_GET['toggle_recommended'];
    $stmt = $pdo->prepare("UPDATE products SET recommended = IF(recommended=1,0,1) WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: products.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT main_image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if ($product) {
        deleteFile('uploads/products/' . $product['main_image']);
        $stmt2 = $pdo->prepare("SELECT image FROM product_images WHERE product_id = ?");
        $stmt2->execute([$id]);
        while ($img = $stmt2->fetch()) {
            deleteFile('uploads/products/' . $img['image']);
        }
        $pdo->prepare("DELETE FROM product_images WHERE product_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    }
    header('Location: products.php');
    exit;
}

$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

if ($search) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name LIKE ?");
    $stmt->execute(["%$search%"]);
    $total = $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
    $stmt->execute(["%$search%"]);
} else {
    $total = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
}
$products = $stmt->fetchAll();
$totalPages = ceil($total / $perPage);

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="text-muted">Showing <?= count($products) ?> of <?= $total ?> products</span>
    </div>
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?= sanitize($search) ?>" style="width:250px;">
            <button class="btn-admin btn-admin-primary"><i class="fa-solid fa-search me-1"></i> Search</button>
        </form>
        <a href="product-create.php" class="btn-admin btn-admin-primary"><i class="fa-solid fa-plus me-1"></i> Add Product</a>
    </div>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Sale Price</th>
                <th>Stock</th>
                <th>Recommended</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($products)): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">No products found.</td></tr>
        <?php else: ?>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><img src="../uploads/products/<?= sanitize($p['main_image']) ?>" class="product-thumb" alt=""></td>
                <td><strong><?= sanitize($p['name']) ?></strong></td>
                <td>₦<?= number_format($p['price']) ?></td>
                <td><?= $p['sale_price'] ? '₦' . number_format($p['sale_price']) : '-' ?></td>
                <td>
                    <a href="?toggle_stock=<?= $p['id'] ?>" class="btn-admin btn-admin-sm <?= $p['stock_status'] === 'in_stock' ? 'badge-stock badge-in' : 'badge-stock badge-out' ?>">
                        <?= $p['stock_status'] === 'in_stock' ? 'In Stock' : 'Out of Stock' ?>
                    </a>
                </td>
                <td>
                    <a href="?toggle_recommended=<?= $p['id'] ?>" class="btn-admin btn-admin-sm <?= $p['recommended'] ? 'badge-stock badge-in' : 'badge-stock badge-out' ?>">
                        <?= $p['recommended'] ? 'Yes' : 'No' ?>
                    </a>
                </td>
                <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
                <td class="action-links">
                    <a href="product-edit.php?id=<?= $p['id'] ?>"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                    <a href="?delete=<?= $p['id'] ?>" class="btn-delete" style="color:#dc3545;"><i class="fa-solid fa-trash"></i> Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<div class="d-flex justify-content-center mt-4">
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= sanitize($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
