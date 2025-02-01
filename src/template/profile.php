<!-- pagina in cui c'è il profilo utente  -->

<?php
    if(isset($_GET["profilePage"])){
        if($_GET["profilePage"] == "logout" && isUserLoggedIn()){
            logout();
            header("Location: ?page=profile");
        }
    }
?>

<div class="container-fluid p-0 mb-4">
    <header class="header-profile p-4">
        <h1>Il mio account</h1>
    </header>
</div>

<!-- <div class="container"> -->

<h3 class="ps-4">Il mio account</h3>
<nav class="navbar">
    <ul class="nav">
        <div class="row">
        <li class="col-12 col-md-6 col-lg-4 nav-item"> 
            <a class="link-profile nav-link" href="?page=profile&profilePage=dettagliAccount"><i class="bi bi-person pe-2 icons"></i>Dettagli account</a> <hr class="hr"/>
        </li>
        <li class="col-12 col-md-6 col-lg-4 nav-item">
            <a class="link-profile nav-link" href="?page=profile&profilePage=cronologiaOrdini"><i class="bi bi-card-list pe-2 icons"></i>Cronologia ordini</a><hr class="hr"/>
        </li>
        <li class="col-12 col-md-6 col-lg-4 nav-item">
            <a class="link-profile nav-link" href="?page=profile&profilePage=wishlist"><i class="bi bi-heart-fill pe-2 icons"></i>wishlist</a><hr class="hr"/>
        </li>
        <li class="col-12 col-md-6 col-lg-4 nav-item">
            <a class="link-profile nav-link" href="?page=profile&profilePage=assistenza"><i class="bi bi-telephone-fill pe-2 icons"></i>Assistenza</a><hr class="hr"/>
        </li>
        <li class="col-12 col-md-6 col-lg-4 nav-item">
            <a class="link-profile nav-link" href="?page=profile&profilePage=privacy"><i class="bi bi-fingerprint pe-2 icons"></i>Privicy e sicurezza</a><hr class="hr"/>
        </li>
        <li class="col-12 col-md-6 col-lg-4 nav-item">
            <a class="link-profile nav-link" href="?page=profile&profilePage=logout"><i class="bi bi-door-open pe-2 icons"></i>Logout</a><hr class="hr"/>
        </li>
        </div>
    </ul>
</nav>

<?php
    if(isset($_GET["profilePage"])){
        $profilePage = $_GET["profilePage"];
    }else{
        $profilePage = "home";
    }
    switch($profilePage){
        //l'utente non ha selezionato ancora nulla nel profilo
        case "home":
            $homeString = " <h3>Ciao " . $_SESSION["username"] . " (non sei " . $_SESSION["username"] . "? Logout).</h3>
            <h2>Effettua subito il tuo ordine!</h2>
            <h3><a href=\"?page=search\">Sfoglia Prodotti</a></h3>  ";
            echo $homeString;
            break;
        case "dettagliAccount":
            require("accountDetails.php");
            break;
        case "cronologiaOrdini":
            require("orders.php");
            break;
        case "wishlist":
            require("wishlist.php");
            break;
        case "assistenza":
            echo " <h3>Se hai domande o hai bisogno di assistenza per il tuo ordine scrivi alla mail sassishop@gmail.com.
                    Oppure contatta il numero verde 338 1234 5678. </h3> ";
            break;
        case "privacy":
            require("privacy.php");
            break;
        case "logout":
            
            break;
    }
?>
<!-- </div> -->