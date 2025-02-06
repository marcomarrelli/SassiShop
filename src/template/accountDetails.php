<!-- i dettagli dell'account sono all'interno di un form per il momento disabilitato. Si potranno poi modificare i dati
 premendo il pulsante modifica -->
<?php
    if(isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["creditCard"])){
        $dbh->updateUser($_SESSION["idUser"], $_POST["firstName"], $_POST["lastName"], $_POST["username"], $_POST["email"], $_POST["creditCard"]);
        registerLoggedUser($dbh->getUserInfo($_SESSION["idUser"]));
    }
?>

<form action="#" method="POST">    
    <div class="container align-items-center justify-content-center mt-4">
        <div class="card shadow-sm">
            <div class="card-header form-profile">
                <h5 class="card-title mb-0">Profilo Utente</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="firstName" class="form-label">Nome</label>
                    <input class="form-control" type="text" id="firstName" name="firstName" value="<?php echo $_SESSION["firstName"] ?>" disabled required/>
                </div>
                <div class="mb-3">
                    <label class="form-label pt-3" for="lastName">Cognome</label>
                    <input class="form-control" type="text" id="lastName" name="lastName" value="<?php echo $_SESSION["lastName"] ?>" disabled required/>
                </div>
                <div class="mb-3">
                    <label class="form-label pt-3" for="username">Username</label>
                    <input class="form-control" type="text" id="username" name="username" value="<?php echo $_SESSION["username"] ?>" disabled required/>
                </div>
                <div class="mb-3">
                    <label class="form-label pt-3" for="email">Email</label>
                    <input class="form-control" type="text" id="email" name="email" value="<?php echo $_SESSION["email"] ?>" disabled required/>
                </div>
                <div class="mb-3">
                    <label class="form-label pt-3" for="creditCard">Carta di Credito</label>
                    <input class="form-control" type="text" id="creditCard" name="creditCard" value="<?php echo isset($_SESSION["creditCard"]) ? $_SESSION["creditCard"] : '' ?>" disabled required/>
                    <small class="form-text text-muted">Formato: 1234 5678 9012 3456</small>
                </div>
                <div>
                    <input class="btn btn-profile btn-outline-dark px-4 m-2" type="button" name="edit" value="Modifica"/>
                    <input class="btn btn-profile btn-outline-dark px-4 m-2 d-none" type="submit" name="save" value="Salva"/>
                    <input class="btn btn-profile btn-outline-secondary px-4 m-2 d-none" type="button" name="cancel" value="Cancella"/>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="js/accountDetails.js"></script>