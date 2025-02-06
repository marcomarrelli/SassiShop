<?php
    session_start();
    define("UPLOAD_DIR", "./upload/");
    require_once("../db/database.php");
    require_once("utils/functions.php");

    // Connessione al Database
    $dbh = new DatabaseHelper();

    if(isset($_SESSION["idUser"]) && !isset($_SESSION['cartCount'])) {
        $_SESSION['cartCount'] = $dbh->getCartCount($_SESSION["idUser"]);
    }
?>