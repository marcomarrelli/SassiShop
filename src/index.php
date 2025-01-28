
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
        case "profile":
            $templateParams["title"] = "Sassi Shop - Profile";
            $templateParams["content"] = "profile.php";
            break;

    }

    

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