<!-- i dettagli dell'account sono all'interno di un form per il momento disabilitato. Si potranno poi modificare i dati
 premendo il pulsante modifica -->
<form action="#" method="POST">
    <ul>
        <li>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?php echo $_SESSION["firstName"] ?>" disabled/>
        </li>
        <li>
            <label for="cognome">Cognome</label>
            <input type="text" id="cognome" name="cognome" value="<?php echo $_SESSION["lastName"] ?>" disabled/>
        </li>
        <li>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $_SESSION["username"] ?>" disabled/>
        </li>
        <li>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php echo $_SESSION["email"] ?>" disabled/>
        </li>
        <li>
            <input type="button" name="edit" value="Modifica"/>
        </li>
    </ul>
</form>
<script src="js/accountDetails.js"></script>
