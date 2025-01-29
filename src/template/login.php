<form action="#" method="POST">
    <h2>Login</h2>
    <?php //if(isset($templateParams["errorelogin"])): ?>
    <!-- <p><?php //echo $templateParams["errorelogin"]; ?></p>  -->
    <ul>
        <li>
            <label for="email">Email:</label><input type="text" id="email" name="email" />
        </li>
        <li>
            <label for="password">Password:</label><input type="password" id="password" name="password" />
        </li>
        <li>
            <input type="submit" name="submit" value="Invia" />
        </li>
    </ul>
</form>