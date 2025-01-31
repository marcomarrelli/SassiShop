<!-- pagina in cui c'è il profilo utente  -->

<?php
    if(isset($_GET["profilePage"])){
        if($_GET["profilePage"] == "logout" && isUserLoggedIn()){
            logout();
            header("Location: ?page=profile");
        }
    }
?>

<header>
    <h1>Il mio account</h1>
</header>

<h2>Il mio account</h2>
<nav>
    <ul>
        <li> 
            <a href="?page=profile&profilePage=dettagliAccount"><i class="bi bi-person"></i>Dettagli account</a> <hr/>
        </li>
        <li>
            <a href="?page=profile&profilePage=cronologiaOrdini"><i class="bi bi-card-list"></i>Cronologia ordini</a><hr/>
        </li>
        <li>
            <a href="?page=profile&profilePage=wishlist"><i class="bi bi-heart-fill"></i>wishlist</a><hr/>
        </li>
        <li>
            <a href="?page=profile&profilePage=assistenza"><i class="bi bi-telephone-fill"></i>Assistenza</a><hr/>
        </li>
        <li>
            <a href="?page=profile&profilePage=privacy"><i class="bi bi-fingerprint"></i>Privicy e sicurezza</a><hr/>
        </li>
        <li>
            <a href="?page=profile&profilePage=logout"><i class="bi bi-door-open"></i>Logout</a><hr/>
        </li>
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
