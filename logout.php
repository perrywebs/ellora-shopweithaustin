<?php
require_once __DIR__ . '/init.php';

if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id'], $_SESSION['user_name']);
}
session_destroy();
header('Location: login.php');
exit;
