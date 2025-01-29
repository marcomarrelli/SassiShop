
<?php
    require_once("bootstrap.php");

    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }else{
        $page = "home"; //la pagina da cui si inizia è la home
    }

    //parametri di base
    $templateParams["title"] = "Sassi Shop - Home";
    $templateParams["content"] = "home.php";
    

    //in base alla pagina che voglio accedere cambiano i parametri e quindi il contenuto della pagina
    switch($page){
        case "home":
            $templateParams["title"] = "Sassi Shop - Home";
            $templateParams["content"] = "home.php";
            break;

        case "search":
            $templateParams["title"] = "Sassi Shop - Search";
            $templateParams["content"] = "search.php";
            $templateParams["product"] = $dbh->getProducts();
            break;

        case "cart":
            $userId = 2;
            $templateParams["title"] = "Sassi Shop - Cart";
            $templateParams["content"] = "cart.php";
            $templateParams["cartProduct"] = $dbh->getUserCart($userId);
            break;

        case "notification":
            $templateParams["title"] = "Sassi Shop - Notification";
            $templateParams["content"] = "notification.php";
            //$templateParams["notification"] = $dbh->getUserNotification($userId);
            //non c'è ancora la tabella delle notifiche
            break;

        case "profile":
            $userId = 2;
            $templateParams["title"] = "Sassi Shop - Profile";
            $templateParams["content"] = "profile.php";
            $templateParams["orders"] = $dbh->getUserOrders($userId);
            $templateParams["wishlist"] = $dbh->getUserWishlist($userId);
            break;

    }

    //in ogni caso si parte sempre dal template base
    require("template/base.php");

?>


<!--
Pagine:
- index (home)
- login / register
- il mio account (e sottopagine)
- utente (pagina )
- chi siamo
- sostenibilità
- wishlist / carrello
- notifiche
- pagina di ricerca
-->