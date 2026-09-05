<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/auth.php';

if (adminLogin()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password.';
    } else {
        $stmt = $pdo->prepare("SELECT id, name, email, password FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Incorrect email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Ellora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0; padding: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: #f4f6f9; font-family: 'Inter', -apple-system, sans-serif;
        }
        .login-box {
            background: #fff; border-radius: 12px; padding: 40px; width: 100%; max-width: 420px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .login-box h2 { font-size: 24px; font-weight: 700; color: #1a1d24; margin-bottom: 5px; }
        .login-box p { font-size: 14px; color: #666; margin-bottom: 25px; }
        .login-box .form-label { font-size: 13px; font-weight: 600; color: #333; }
        .login-box .form-control {
            border: 1px solid #e0e0e0; border-radius: 8px; padding: 12px 14px; font-size: 14px;
        }
        .login-box .form-control:focus { border-color: #1a1d24; box-shadow: 0 0 0 2px rgba(26,29,36,0.1); }
        .login-btn {
            width: 100%; padding: 12px; background: #1a1d24; color: #fff; border: none; border-radius: 8px;
            font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s;
        }
        .login-btn:hover { background: #2d3139; }
        .alert { font-size: 14px; border-radius: 8px; }
        .brand-icon { font-size: 36px; color: #1a1d24; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="brand-icon">
            <i class="fa-solid fa-lock"></i>
        </div>
        <h2>Admin Login</h2>
        <p>Enter your credentials to access the admin panel.</p>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= sanitize($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="admin@example.com" required value="<?= sanitize($_POST['email'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>
