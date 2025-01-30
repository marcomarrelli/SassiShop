<form action="#" method="POST">
    <h2>Registrazione</h2>
    <?php if(isset($templateParams["erroreRegister"])): ?>
    <p><?php echo $templateParams["erroreRegister"]; ?></p>  
    <?php endif ?>
    <ul>
        <li>
            <label for="firstNameRegister">Nome:</label><input type="text" id="firstNameRegister" name="firstNameRegister" required/>
        </li>
        <li>
            <label for="lastNameRegister">Cognome:</label><input type="text" id="lastNameRegister" name="lastNameRegister" required/>
        </li>
        <li>
            <label for="usernameRegister">Username:</label><input type="text" id="usernameRegister" name="usernameRegister" required/>
        </li>
        <li>
            <label for="emailRegister">Email:</label><input type="text" id="emailRegister" name="emailRegister" required/>
        </li>
        <li>
            <label for="passwordRegister">Password:</label><input type="password" id="passwordRegister" name="passwordRegister" required />
        </li>
        <li>
            <input type="submit" name="submit" value="Registrati" />
        </li>
    </ul>
</form>