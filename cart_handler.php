<?php
session_start();
require_once __DIR__ . '/init.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = &$_SESSION['cart'];

switch ($action) {
    case 'add':
        $productId = (int)($_POST['product_id'] ?? 0);
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        
        $stmt = $pdo->prepare("SELECT id, name, price, sale_price, main_image, stock_status FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        
        if (!$product || $product['stock_status'] !== 'in_stock') {
            echo json_encode(['success' => false, 'message' => 'Product not available.']);
            exit;
        }
        
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $qty;
        } else {
            $cart[$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['sale_price'] ? $product['sale_price'] : $product['price'],
                'image' => $product['main_image'],
                'qty' => $qty,
            ];
        }
        
        $total = 0;
        $count = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
            $count += $item['qty'];
        }
        
        echo json_encode(['success' => true, 'message' => 'Added to cart.', 'cart_count' => $count, 'cart_total' => $total]);
        break;
    
    case 'update':
        $productId = (int)($_POST['product_id'] ?? 0);
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] = $qty;
        }
        
        $total = 0;
        $count = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
            $count += $item['qty'];
        }
        
        echo json_encode(['success' => true, 'cart_count' => $count, 'cart_total' => $total]);
        break;
    
    case 'remove':
        $productId = (int)($_POST['product_id'] ?? 0);
        unset($cart[$productId]);
        
        $total = 0;
        $count = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
            $count += $item['qty'];
        }
        
        echo json_encode(['success' => true, 'cart_count' => $count, 'cart_total' => $total]);
        break;
    
    case 'clear':
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'cart_count' => 0, 'cart_total' => 0]);
        break;
    
    case 'count':
        $total = 0;
        $count = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
            $count += $item['qty'];
        }
        echo json_encode(['cart_count' => $count, 'cart_total' => $total]);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}
