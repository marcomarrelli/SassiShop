<?php
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    $productId = $data['id'];
    $quantity = $data['quantity'] ?? 1;

    require_once("bootstrap.php");

    if(!isset($_SESSION["idUser"])) {
        echo json_encode([
            'success' => false,
            'error' => 'User not logged in'
        ]);
        exit();
    }

    if($dbh->checkProductCart($productId, $_SESSION["idUser"])) {
        $dbh->updateProductCartQuantity($productId, $_SESSION["idUser"], $quantity);
    } else {
        $dbh->addProductCart($productId, $_SESSION["idUser"], $quantity);
    }

    $cartCount = $dbh->getCartCount($_SESSION["idUser"]);
    $_SESSION['cartCount'] = $cartCount;

    echo json_encode([
        'success' => true,
        'cartCount' => $cartCount
    ]);
?>