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
        else return imagecreatefromstring(base64_decode($product["image"])) || "../../assets/images/placeholders/not_available.png";
    }
?>