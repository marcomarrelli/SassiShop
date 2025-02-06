
    <h2 class="text-center">Login</h2>
    <p class="text-center">Non hai un account? <a class="link-product p-0" href="?page=register"> Registrati subito!</a></p>
    <?php if(isset($templateParams["errorelogin"])): ?>
    <p><?php echo $templateParams["errorelogin"]; ?></p>  
    <?php endif ?>
    
    <form action="#" method="POST">
        <div class="container align-items-center justify-content-center mt-4">
            <div class="card shadow-sm">
                <div class="card-header form-profile">
                    <h5 class="card-title mb-0">Effettua il Login</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="emailLogin" class="form-label">Email:</label>
                        <input class="form-control" type="text" id="emailLogin" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name="emailLogin" required/>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="passwordLogin">Password:</label>
                        <input class="form-control" type="password" id="passwordLogin" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" name="passwordLogin" required/>
                    </div>
                    <div>
                        <input class="btn btn-profile btn-outline-dark px-4 mx-3" type="submit" name="login" value="Login"/>
                    </div>
                </div>
            </div>
        </div>
    </form>

    
