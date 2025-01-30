<form action="#" method="POST">
    <h2>Login</h2>
    <p>Non hai un account? <a href="?page=register"> Registrati subito!</a></p>
    <?php if(isset($templateParams["errorelogin"])): ?>
    <p><?php echo $templateParams["errorelogin"]; ?></p>  
    <?php endif ?>
    <ul>
        <li>
            <label for="emailLogin">Email:</label><input type="text" id="emailLogin" name="emailLogin" />
        </li>
        <li>
            <label for="passwordLogin">Password:</label><input type="password" id="passwordLogin" name="passwordLogin" />
        </li>
        <li>
            <input type="submit" name="submit" value="Login" />
        </li>
    </ul>
</form>