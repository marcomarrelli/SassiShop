<!-- funzioni comuni a tutti i file -->

<?php
    //registra sulla session l'utente in questo momento attivo
    function registerLoggedUser($user){
        $_SESSION["idutente"] = $user["id"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["firstName"] = $user["firstName"];
        $_SESSION["lastName"] = $user["lastName"];
    }

    function isUserLoggedIn() :bool{
        return !empty($_SESSION['idutente']);
    }

    function logout(){
        unset($_SESSION["idutente"]);
        unset($_SESSION["email"]);
        unset($_SESSION["firstName"]);
        unset($_SESSION["lastName"]);
        //header("Location: ?page=home");
    }
?>