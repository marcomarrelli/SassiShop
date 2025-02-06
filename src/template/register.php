
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
                        <input class="form-control" type="text" id="firstNameRegister" name="firstNameRegister" 
                            pattern="[A-Za-z]+" 
                            title="Solo lettere consentite" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="lastNameRegister">Cognome:</label>
                        <input class="form-control" type="text" id="lastNameRegister" name="lastNameRegister" 
                            pattern="[A-Za-z]+" 
                            title="Solo lettere consentite" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="usernameRegister">Username:</label>
                        <input class="form-control" type="text" id="usernameRegister" name="usernameRegister" 
                            pattern="[A-Za-z0-9._]+" 
                            title="Solo lettere, numeri, punti e underscore consentiti" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="emailRegister">Email:</label>
                        <input class="form-control" type="email" id="emailRegister" name="emailRegister" 
                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" 
                            title="Inserisci un indirizzo email valido" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="creditCardRegister">Carta di Credito:</label>
                        <input class="form-control" type="text" id="creditCardRegister" name="creditCardRegister" 
                            maxlength="19" 
                            pattern="\d{4}\s\d{4}\s\d{4}\s\d{4}" 
                            placeholder="XXXX XXXX XXXX XXXX"
                            title="Inserisci 16 numeri raggruppati in gruppi da 4"/>
                        <small class="form-text text-muted">Formato: 1234 5678 9012 3456</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label pt-3" for="passwordRegister">Password:</label>
                        <input class="form-control" type="password" id="passwordRegister" name="passwordRegister" 
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                            title="La password deve contenere almeno 8 caratteri, una lettera maiuscola, una minuscola e un numero" required/>
                    </div>
                    <div>
                        <input class="btn btn-profile btn-outline-dark px-4 mx-3" type="submit" name="save" value="Salva"/>
                    </div>
                </div>
            </div>
        </div>
    </form>

    
