<?php
require_once __DIR__ . '/config/db.php';

$email = 'admin@ellora.com';
$password = 'admin123';

$stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
$stmt->execute([$email]);
if (!$stmt->fetch()) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO admins (name, email, password, created_at) VALUES (?, ?, ?, NOW())")->execute(['Admin', $email, $hashed]);
    echo "Admin account created successfully!\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
} else {
    echo "Admin account already exists.\n";
}
