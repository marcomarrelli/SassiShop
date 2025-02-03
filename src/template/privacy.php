<?php
    if(isset($_GET["password"]) && $_GET["password"] == "change" && !isset($passwordOk)){ 
        if(isset($templateParams["errorPassword"])){
            echo $templateParams["errorPassword"];
        }?>

        <form action="#" method="POST">

            <div class="container align-items-center justify-content-center mt-4">
                <div class="card shadow-sm">
                    <div class="card-header form-profile">
                        <h5 class="card-title mb-0">Modifica Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Nuova Password</label>
                            <input class="form-control" type="text" id="newPassword" name="newPassword" required/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label pt-3" for="confirmPassword">Conferma Password</label>
                            <input class="form-control" type="text" id="confirmPassword" name="confirmPassword" required/>
                        </div>
                        <div>
                            <input class="btn btn-profile btn-outline-dark px-4 mx-3" type="submit" name="save" value="Salva"/>
                            <input class="btn btn-profile btn-outline-dark px-4 mx-3" type="button" name="cancel" value="Cancella"/>
                        </div>
                    </div>
                </div>
            </div>
        </form>

<?php
    }else{
?>

<div class="text-center mt-3 px-2">
    <h2>
        Proteggi il tuo account!
    </h2>
    <p>Controlla le tue informazioni per tenere al sicuro il tuo account.</p>
    <hr/>
    <h3><a class="link-product" href="?page=profile&profilePage=privacy&password=change">Cambia Password</a></h3>
    <p>Proteggi il tuo account con una password più sicura</p>
</div>

<?php
    }
?>