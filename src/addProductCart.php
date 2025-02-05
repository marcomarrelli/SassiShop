<?php
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    require_once("bootstrap.php");

    if(!isset($_SESSION["idUser"])) {
        echo json_encode([
            'success' => false,
            'error' => 'User not logged in'
        ]);
        exit();
    }

    $action = $data['action'] ?? 'add';
    $productId = $data['id'];
    
    if($action === 'remove') { // Remove product from cart
        $result = $dbh->removeProductCart($productId, $_SESSION["idUser"]);
    } else { // Add/Update product in cart
        $quantity = $data['quantity'] ?? 1;

        if($dbh->checkProductCart($productId, $_SESSION["idUser"])) {
            $result = $dbh->updateProductCartQuantity($productId, $_SESSION["idUser"], $quantity);
        } else {
            $result = $dbh->addProductCart($productId, $_SESSION["idUser"], $quantity);
        }
    }

    if($result) {
        $cartCount = $dbh->getCartCount($_SESSION["idUser"]);
        $cartTotal = $dbh->getCartTotal($_SESSION["idUser"]);
        $_SESSION['cartCount'] = $cartCount;
        
        echo json_encode([
            'success' => true,
            'cartCount' => $cartCount,
            'cartTotal' => $cartTotal
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Database error'
        ]);
    }
?>