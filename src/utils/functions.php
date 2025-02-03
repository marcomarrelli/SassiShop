<?php

    //funzioni comuni a tutti i file
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

    /**
     * Funzione che taglia una stringa in base alla lunghezza passata come parametro.
     * 
     * @param string $string stringa da tagliare
     * @param int $length lunghezza massima della stringa
     * 
     * @return string stringa tagliata
     */
    function string_cutter($string, $length) : string {
        if(is_null($string)) return "";
        if(empty($string)) return "";

        if($length <= 0) return $string;

        return (strlen($string) > $length) ? substr($string, 0, $length) . "..." : $string;
    }

    function getProductImage($product){
        if(is_null($product) || empty($product) || !isset($product["image"])) return "../../assets/images/placeholders/not_available.png";
        return $product["image"];
    }

    /**
     * Funzione che restituisce un messaggio di alert in base alla quantità del prodotto.
     * 
     * @param int $quantity quantità del prodotto
     * 
     * @return string messaggio di alert
     */
    function getQuantityAlert(int $quantity) : string {
        if($quantity <= 0) return "Prodotto esaurito!";
        else if($quantity == 1) return "Ultimo disponibile!";
        else if($quantity <= 5) return "Ultimi " . $quantity . " disponibili!";
        return "";
    }
?>