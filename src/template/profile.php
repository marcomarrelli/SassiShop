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

<h3 class="ps-4 text">Il mio account</h3>
<nav class="navbar">
    <div class="container">
        <ul class="nav row justidy-content-center">
            <li class="col-12 col-md-6 col-lg-4 nav-item"> 
                <a class="link-profile nav-link" href="?page=profile&profilePage=dettagliAccount"><i class="bi bi-person pe-2 icons"></i>Dettagli account</a> <hr class="hr text"/>
            </li>
            <li class="col-12 col-md-6 col-lg-4 nav-item">
                <a class="link-profile nav-link" href="?page=profile&profilePage=orders"><i class="bi bi-card-list pe-2 icons"></i>Cronologia ordini</a><hr class="hr text"/>
            </li>
            <li class="col-12 col-md-6 col-lg-4 nav-item">
                <a class="link-profile nav-link" href="?page=profile&profilePage=wishlist"><i class="bi bi-heart-fill pe-2 icons"></i>Wishlist</a><hr class="hr text"/>
            </li>
            <li class="col-12 col-md-6 col-lg-4 nav-item">
                <a class="link-profile nav-link" href="?page=profile&profilePage=assistenza"><i class="bi bi-telephone-fill pe-2 icons"></i>Assistenza</a><hr class="hr text"/>
            </li>
            <li class="col-12 col-md-6 col-lg-4 nav-item">
                <a class="link-profile nav-link" href="?page=profile&profilePage=privacy"><i class="bi bi-fingerprint pe-2 icons"></i>Privicy e sicurezza</a><hr class="hr text"/>
            </li>
            <li class="col-12 col-md-6 col-lg-4 nav-item">
                <a class="link-profile nav-link" href="?page=profile&profilePage=logout"><i class="bi bi-door-open pe-2 icons"></i>Logout</a><hr class="hr text"/>
            </li>
        </ul>
    </div>
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
            $homeString = " <h5 class=\"ps-4 mb-3 text\">Ciao <b>" . $_SESSION["username"] . "</b> (non sei <b>" . $_SESSION["username"] . "</b>? Logout).</h5>
            <div class=\"home-profile mt-3\">
            <h2>Effettua subito il tuo ordine!</h2>
            <h3><a class=\"link-product\" href=\"?page=search\">Sfoglia Prodotti</a></h3>
            </div>  ";
            echo $homeString;
            break;
        case "dettagliAccount":
            require("accountDetails.php");
            break;
        case "orders":
            require("orders.php");
            break;
        case "wishlist":
            require("wishlist.php");
            break;
        case "assistenza":
            $homeString = " <div class=\"home-profile mt-3\">
                        <h3 class=\"p-2\">Se hai domande o hai bisogno di assistenza per il tuo ordine scrivi alla mail <b>sassishop@gmail.com</b>. <br/> Oppure contatta il numero verde <b>338 1234 5678</b>.</h3>
                        </div>  ";
            echo $homeString;
            break;
        case "privacy":
            if(isset($_POST["newPassword"]) && isset($_POST["confirmPassword"])){
                if($_POST["newPassword"] == $_POST["confirmPassword"]){
                    $result = $dbh->updatePassword($_POST["newPassword"], $_SESSION["idUser"]);
                    if($result){
                        $passwordOk = " <div class=\"home-profile mt-3\">
                                <h3 class=\"p-2\">Password cambiata con successo!!</h3>
                                </div>  ";
                        echo $passwordOk;
                    }else{
                        $templateParams["errorPassword"] = " <div class=\"home-profile mt-3\">
                                <h3 class=\"p-2\">Non siamo riusciti a cambiare la password, ci scusiamo per il disagio :( </h3>
                                </div>  ";
                    }
                }else{
                    $templateParams["errorPassword"] ="<div class=\"home-profile mt-3\">
                                    <h3 class=\"p-2\">Le due password non coincidono!!</h3>
                                    </div>  ";
                }
            }
            require("privacy.php");
            break;

        case "logout":
            
            break;
    }
?>
<!-- </div> -->