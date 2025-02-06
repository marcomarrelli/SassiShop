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
        if($data['quantity']) $result = $dbh->removeCartProductQuantity($productId, $userId, $data['quantity']);
        else $result = $dbh->removeProductCart($productId, $userId);
        break;
        
    case 'increment':
        $result = $dbh->addCartProductQuantity($productId, $userId);
        break;
        
    case 'decrement':
        $result = $dbh->removeCartProductQuantity($productId, $userId);
        break;
        
    case 'update':
        $quantity = $data['quantity'] ?? 1;
        $result = $dbh->addCartProductQuantity($productId, $userId, $quantity);
        break;
        
    case 'add':
    default:
        $quantity = $data['quantity'] ?? 1;
        if($dbh->checkProductCart($productId, $userId)) {
            $result = $dbh->addCartProductQuantity($productId, $userId, $quantity);
        } else {
            $result = $dbh->addProductCart($productId, $userId, $quantity);
        }
        break;

    case 'buy':
        $result = $dbh->createPurchase($userId);
        break;
}

// Return response
if($result) {
    $cartCount = $dbh->getCartCount($userId);
    $cartTotal = $dbh->getCartTotal($userId);

    switch($action) {
        case 'increment':
        case 'decrement':
        case 'remove':
        case 'update':
        case 'add':
            echo json_encode([
                'success' => true,
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal,
                'unitPrice' => $dbh->getProductInfo($productId)['price']
            ]);

            break;

        case 'buy':
            echo json_encode([
                'success' => true
            ]);
            break;
    }

    $_SESSION['cartCount'] = $cartCount;
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Database error'
    ]);
}
?>