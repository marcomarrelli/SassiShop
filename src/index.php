<!DOCTYPE html>

<!-- Icone Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Font -->
<link href='https://fonts.googleapis.com/css?family=Istok Web' rel='stylesheet'>

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
            $templateParams["categoriesList"] = $dbh->getCategories();
            $templateParams["mostLovedCategories"] = [
                [
                    "title" => "Amici ed Emozioni",
                    "description" => "Un'emozione,<br>per lasciarti impietrito!",
                    "image" => "../assets/images/placeholders/categoria_amichetti.jpg",
                    "alt" => "Categoria: Sassi Amici"
                ],
                [
                    "title" => "Bandiere",
                    "description" => "Tutto il mondo,<br>a portata di pietra!",
                    "image" => "../assets/images/placeholders/categoria_bandiere.jpg",
                    "alt" => "Categoria: Bandiere"
                ],
                [
                    "title" => "Animaletti",
                    "description" => "Che verso fa la pietra?<br>Scoprilo!",
                    "image" => "../assets/images/placeholders/categoria_animali.jpg",
                    "alt" => "Categoria: Animali"
                ]
            ];
            
            $templateParams["mostSoldCollections"] = [
                [
                    "title" => "San Valentino",
                    "description" => "Non avere il cuore di pietra, regalalo!",
                    "image" => "../assets/images/placeholders/collezione_san_valentino.jpg",
                    "alt" => "Categoria: San Valentino"
                ],
                [
                    "title" => "Cartoni Animati",
                    "description" => "Gotta catch 'em all!<br>E tanto altro...",
                    "image" => "../assets/images/placeholders/collezione_cartoni_animati.jpg",
                    "alt" => "Categoria: Cartoni Animati"
                ],
                [
                    "title" => "Famiglia",
                    "description" => "Fatti abbracciare!<br>Per tutta la famiglia.",
                    "image" => "../assets/images/placeholders/collezione_famiglia.jpg",
                    "alt" => "Categoria: Famiglie"
                ]
            ];

            break;

        case "search":
            if(isUserLoggedIn()){
                $templateParams["userLogged"] = true;
            }else{ 
                $templateParams["userLogged"] = false;
            }
            $templateParams["title"] = "Sassi Shop - Search";
            $templateParams["content"] = "search.php";
            $templateParams["product"] = $dbh->getProducts();
            break;

        case "cart":
            if(isUserLoggedIn()){
                $templateParams["cartProducts"] = $dbh->getUserCart($_SESSION["idUser"]);
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
                $templateParams["orders"] = $dbh->getUserOrders($_SESSION["idUser"]);
                $templateParams["wishlist"] = $dbh->getUserWishlist($_SESSION["idUser"]);
            
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
                    $privilege = 2;
                    //if(isset($_POST["admin"])){
                      //  $privilege = 1;
                    //}
                    $dbh->addUser($_POST["firstNameRegister"], $_POST["lastNameRegister"], $_POST["usernameRegister"], $_POST["emailRegister"], $_POST["passwordRegister"], $privilege);
                    $login_result = $dbh->Login($_POST["emailRegister"], $_POST["passwordRegister"]);
                    registerLoggedUser($login_result);
                    header("Location: ?page=profile");
                }
            }else{
                $templateParams["erroreRegister"] = "Completare tutti i campi";
            }
            $templateParams["title"] = "Sassi Shop - Registrazione";
            $templateParams["content"] = "register.php";
            break;
        
        case "manageProduct":
            $templateParams["title"] = "Sassi Shop - Gestisci Prodotti";
            $templateParams["content"] = "manageProduct.php";
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