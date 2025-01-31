<!-- i dettagli dell'account sono all'interno di un form per il momento disabilitato. Si potranno poi modificare i dati
 premendo il pulsante modifica -->
<?php
    if(isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["username"]) && isset($_POST["email"])){
        $dbh->updateUser($_SESSION["idUser"], $_POST["firstName"], $_POST["lastName"], $_POST["username"], $_POST["email"]);
        registerLoggedUser($dbh->getUserInfo($_SESSION["idUser"]));
        header("Refresh:5");
    }
?>

<form action="#" method="POST">
    <ul>
        <li>
            <label for="firstName">Nome</label>
            <input type="text" id="firstName" name="firstName" value="<?php echo $_SESSION["firstName"] ?>" disabled required/>
        </li>
        <li>
            <label for="lastName">Cognome</label>
            <input type="text" id="lastName" name="lastName" value="<?php echo $_SESSION["lastName"] ?>" disabled required/>
        </li>
        <li>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $_SESSION["username"] ?>" disabled required/>
        </li>
        <li>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php echo $_SESSION["email"] ?>" disabled required/>
        </li>
        <li>
            <input class="btn" type="button" name="edit" value="Modifica"/>
        </li>
        <li>
            <input class="d-none" type="submit" name="save" value="Salva"/>
        </li>
        <li>
            <input class="d-none" type="button" name="cancel" value="Cancella"/>
        </li>
    </ul>
</form>

<script src="js/accountDetails.js"></script>
