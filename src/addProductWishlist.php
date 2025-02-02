<?php
    
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    $productId = $data['id'];

    require_once("bootstrap.php");
    if($dbh->checkProductWishlist($productId, $_SESSION["idUser"])){
        $dbh->removeProductWishlist($productId, $_SESSION["idUser"]);
    }else{
        $dbh->addProductWishlist($productId, $_SESSION["idUser"]);
    }

    echo json_encode(['success' => true]);

?>