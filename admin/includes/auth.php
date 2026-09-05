<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

function adminLogin() {
    return isset($_SESSION['admin_id']);
}

function requireAdmin() {
    if (!adminLogin()) {
        header('Location: login.php');
        exit;
    }
}

function getAdmin() {
    global $pdo;
    if (!adminLogin()) return null;
    $stmt = $pdo->prepare("SELECT id, name, email FROM admins WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    return $stmt->fetch();
}
