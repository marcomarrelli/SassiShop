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
    
    function isUserLoggedIn() : bool{
        return !empty($_SESSION['idUser']);
    }

    function isAdmin() : bool {
        return (isset($_SESSION["privilege"]) && $_SESSION["privilege"] == 1);
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
    function stringCutter($string, $length) : string {
        if(is_null($string)) return "";
        if(empty($string)) return "";

        if($length <= 0) return $string;

        return (strlen($string) > $length) ? substr($string, 0, $length) . "..." : $string;
    }

    function getProductImage($product) {
        $defaultImage = "../assets/images/placeholders/not_available.png";
        if(is_null($product) || empty($product) || !isset($product["image"])) return $defaultImage;
        return file_exists($product["image"]) ? $product["image"] : $defaultImage; 
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

    function uploadProductImage($file) {
        $uploadDir = '../assets/images/products/';
        $validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 4 * 1024 * 1024; // 4MB
    
        if($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Errore nel caricamento del file!'];
        }
    
        if(!in_array($file['type'], $validTypes)) {
            return ['success' => false, 'error' => 'Tipo di file non supportato!'];
        }
    
        if($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'File troppo grande (4MB Max.)!'];
        }
    
        $fileInfo = pathinfo($file['name']);
        $fileName = time() . '_' . $fileInfo['filename'] . '.' . $fileInfo['extension'];

        $targetPath = $uploadDir . $fileName;

        if(move_uploaded_file($file['tmp_name'], $targetPath)) {
            return [
                'success' => true, 
                'path' => $targetPath
            ];
        }
    
        return ['success' => false, 'error' => 'Errore nel salvataggio del file!'];
    }
?>