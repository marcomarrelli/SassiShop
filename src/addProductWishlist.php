<?php
    
    //legge il contenuto del body
    $jsonData = file_get_contents('php://input');

    //decodifica il contenuto json
    $data = json_decode($jsonData, true);

    //prendo l'id mandato con il file json
    $productId = $data['id'];

    require_once("bootstrap.php");
    
    //controlla se il prodotto è già all'interno della wishlist allora lo rimuove, se no lo aggiunge
    if($dbh->checkProductWishlist($productId, $_SESSION["idUser"])){
        $dbh->removeProductWishlist($productId, $_SESSION["idUser"]);
    }else{
        $dbh->addProductWishlist($productId, $_SESSION["idUser"]);
    }

    //restituisce una risposta json con la chiave success impostata a true.
    echo json_encode(['success' => true]);

?>