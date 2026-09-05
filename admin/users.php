<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

$pageTitle = 'Users';

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE users SET status = IF(status='active','disabled','active') WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: users.php');
    exit;
}

$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

if ($search) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE name LIKE ? OR email LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
    $total = $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $total = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
}
$users = $stmt->fetchAll();
$totalPages = ceil($total / $perPage);

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="text-muted">Showing <?= count($users) ?> of <?= $total ?> users</span>
    </div>
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?= sanitize($search) ?>" style="width:250px;">
        <button class="btn-admin btn-admin-primary"><i class="fa-solid fa-search me-1"></i> Search</button>
    </form>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($users)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">No users found.</td></tr>
        <?php else: ?>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><strong><?= sanitize($u['name']) ?></strong></td>
                <td><?= sanitize($u['email']) ?></td>
                <td><?= sanitize($u['phone'] ?? '-') ?></td>
                <td>
                    <a href="?toggle=<?= $u['id'] ?>" class="btn-admin btn-admin-sm <?= $u['status'] === 'active' ? 'badge-stock badge-in' : 'badge-stock badge-out' ?>">
                        <?= $u['status'] === 'active' ? 'Active' : 'Disabled' ?>
                    </a>
                </td>
                <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                <td class="action-links">
                    <a href="user-edit.php?id=<?= $u['id'] ?>"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<div class="d-flex justify-content-center mt-4">
    <nav><ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= sanitize($search) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul></nav>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
