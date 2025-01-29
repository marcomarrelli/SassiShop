
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
            $templateParams["cartProducts"] = $dbh->getUserCart($userId);
            break;

        case "notification":
            $templateParams["title"] = "Sassi Shop - Notification";
            $templateParams["content"] = "notification.php";
            $templateParams["notifications"] = ""; // = $dbh->getUserNotification($userId);
            //non c'è ancora la tabella delle notifiche
            break;

        case "profile":
            
            if(isset($_POST["email"]) && isset($_POST["password"])){
                $login_result = $dbh->Login($_POST["email"], $_POST["password"]);
                if($login_result == null){
                    //Login fallito
                    $templateParams["errorelogin"] ="Errore!! Controllare username o pass";
                }else{
                    registerLoggedUser($login_result);
                }
            }

            //controllo se l'utente è loggato
            if(isUserLoggedIn()){
                //Utente Loggato
                $templateParams["title"] = "Sassi Shop - Profile";
                $templateParams["content"] = "profile.php";
                $templateParams["orders"] = $dbh->getUserOrders($_SESSION["idutente"]);
                $templateParams["wishlist"] = $dbh->getUserWishlist($_SESSION["idutente"]);
            
            }else{
                //Utente non loggato
                $templateParams["title"] = "Sassi Shop - Login";
                $templateParams["content"] = "login.php";
            }
            

            /*$templateParams["title"] = "Sassi Shop - Profile";
            $templateParams["content"] = "login.php";*/

            /*$userId = 2;
            $templateParams["title"] = "Sassi Shop - Profile";
            $templateParams["content"] = "profile.php";
            $templateParams["orders"] = $dbh->getUserOrders($userId);
            $templateParams["wishlist"] = $dbh->getUserWishlist($userId);*/
            
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