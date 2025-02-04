
    <h2 class="text-center">Registrazione</h2>
    <?php if(isset($templateParams["erroreRegister"])): ?>
    <p class="text-center"><?php echo $templateParams["erroreRegister"]; ?></p>  
    <?php endif ?>



    <form action="#" method="POST">
    
        <div class="container align-items-center justify-content-center mt-4">
            <div class="card shadow-sm">
                <div class="card-header form-profile">
                    <h5 class="card-title mb-0">Registrazione</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="firstNameRegister" class="form-label">Nome:</label>
                        <input class="form-control" type="text" id="firstNameRegister" name="firstNameRegister" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="lastNameRegister">Cognome:</label>
                        <input class="form-control" type="text" id="lastNameRegister" name="lastNameRegister" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="usernameRegister">Username:</label>
                        <input class="form-control" type="text" id="usernameRegister" name="usernameRegister" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="emailRegister">Email:</label>
                        <input class="form-control" type="text" id="emailRegister" name="emailRegister" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="passwordRegister">Password:</label>
                        <input class="form-control" type="password" id="passwordRegister" name="passwordRegister" required/>
                    </div>
                    <div>
                        <input class="btn btn-profile btn-outline-dark px-4 mx-3" type="submit" name="save" value="Salva"/>
                    </div>
                    <!-- <label for="admin">I am Admin</label><input type="checkbox" name="admin" value="admin"/> -->
                </div>
            </div>
        </div>
    </form>

    
