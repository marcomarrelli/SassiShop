
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
            if(isUserLoggedIn()){
                $templateParams["cartProducts"] = $dbh->getUserCart($_SESSION["idutente"]);
                $templateParams["userLogged"] = true;
            }else{ 
                $templateParams["userLogged"] = false;
            }
            $templateParams["title"] = "Sassi Shop - Cart";
            $templateParams["content"] = "cart.php";
            
            break;

        case "notification":
            if(isUserLoggedIn()){
                $templateParams["notifications"] = "";// = $dbh->getUserNotification($userId);
                //non c'è ancora la tabella delle notifiche
                $templateParams["userLogged"] = true;
            }else{
                $templateParams["userLogged"] = false;
            }
            $templateParams["title"] = "Sassi Shop - Notification";
            $templateParams["content"] = "notification.php";
            break;

        case "profile":
            
            if(isset($_POST["emailLogin"]) && isset($_POST["passwordLogin"])){
                $login_result = $dbh->Login($_POST["emailLogin"], $_POST["passwordLogin"]);
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
            
            break;
        
        case "register":
            if(isset($_POST["firstNameRegister"]) && isset($_POST["lastNameRegister"]) && isset($_POST["usernameRegister"]) && isset($_POST["emailRegister"]) && isset($_POST["passwordRegister"])){
                if($dbh->checkUsername($_POST["usernameRegister"])){
                    $templateParams["erroreRegister"] = "Username già esistente";
                }else{
                    $dbh->addUser($_POST["firstNameRegister"], $_POST["lastNameRegister"], $_POST["usernameRegister"], $_POST["emailRegister"], $_POST["passwordRegister"]);
                    $login_result = $dbh->Login($_POST["emailRegister"], $_POST["passwordRegister"]);
                    registerLoggedUser($login_result);
                    header("Location: ?page=profile");
                }
            }else{
                $templateParams["erroreRegister"] = "Completare tutti i campi";
            }
            $templateParams["title"] = "Sassi Shop - Registrazione";
            $templateParams["content"] = "register.php";

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