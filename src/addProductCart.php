<?php
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

require_once("bootstrap.php");

// Check user login
if(!isset($_SESSION["idUser"])) {
    echo json_encode([
        'success' => false,
        'error' => 'User not logged in'
    ]);
    exit();
}

$action = $data['action'] ?? 'add';
$productId = $data['id'];
$userId = $_SESSION["idUser"];

// Handle different cart actions
switch($action) {
    case 'remove':
        $result = $dbh->removeProductCart($productId, $userId);
        break;
        
    case 'increment':
        $result = $dbh->addCartProductQuantity($userId, $productId);
        break;
        
    case 'decrement':
        $result = $dbh->removeCartProductQuantity($userId, $productId);
        break;
        
    case 'update':
        $quantity = $data['quantity'] ?? 1;
        $result = $dbh->updateProductCartQuantity($productId, $userId, $quantity);
        break;
        
    case 'add':
    default:
        $quantity = $data['quantity'] ?? 1;
        if($dbh->checkProductCart($productId, $userId)) {
            $result = $dbh->updateProductCartQuantity($productId, $userId, $quantity);
        } else {
            $result = $dbh->addProductCart($productId, $userId, $quantity);
        }
        break;
}

// Return response
if($result) {
    $cartCount = $dbh->getCartCount($userId);
    $cartTotal = $dbh->getCartTotal($userId);
    $_SESSION['cartCount'] = $cartCount;
    
    echo json_encode([
        'success' => true,
        'cartCount' => $cartCount,
        'cartTotal' => $cartTotal,
        'unitPrice' => $dbh->getProductInfo($productId)['price']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Database error'
    ]);
}
?>