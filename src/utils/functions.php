<!-- funzioni comuni a tutti i file -->

<?php
    //registra sulla session l'utente in questo momento attivo
    function registerLoggedUser($user){
        $_SESSION["idUser"] = $user["id"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["firstName"] = $user["firstName"];
        $_SESSION["lastName"] = $user["lastName"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["privilege"] = $user["privilege"];
    }
    

    function isUserLoggedIn() :bool{
        return !empty($_SESSION['idUser']);
    }

    function logout(){
        unset($_SESSION["idUser"]);
        unset($_SESSION["email"]);
        unset($_SESSION["firstName"]);
        unset($_SESSION["lastName"]);
        unset($_SESSION["privilege"]);
    }
?>