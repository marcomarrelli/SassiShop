<?php
    $stockEmpty1 = "Oh nooo! È esaurito un prodotto presente nel tuo carrello: ";
    $stockRefill2 = "Yuppi! È stato rifornito un prodotto presente nel tuo carrello: ";
    $purchase3 = "È stato effettuato un ordine dall'utente: ";
?>


<!-- qua vengono visualizzate le notifiche dell'utente -->
<?php
    //lista ordini vuota, allora l'utente non ha ancora effettuato ordini
    if(!$templateParams["userLogged"]){ ?>
        <div class="container py-5 d-flex justify-content-center align-items-center">
            <div class="alert alert-warning text-center no-login-cart-alert w-75" role="alert">
                <h4 class="alert-heading">Effettua l'accesso per vedere le notifiche!</h4>
                <p>Per visualizzare le tue nuove notifiche devi prima effettuare l'accesso.</p>
                <hr>
                <a href="?page=profile" class="btn btn-warning">Vai al Login!</a>
            </div>
        </div>
    <?php
    }else if(empty($templateParams["notification"])){
        echo "<p>Non hai nessuna notifica</p>
        <h2><a href=\"?page=search\">Sfoglia Prodotti</a></h2> ";
    }else{ //se l'utente ha effettuato almeno un ordine, stampo gli ordini
     ?>    

    <div class="container-fluid align-items-center justify-content-center mt-4">
        <div class="card shadow-sm">
            <div class="card-header form-profile">
                <h5 class="card-title mb-0">Notifiche</h5>
            </div>
            <div class="card-body p-2 p-md-4">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        </thead>
                        <tbody>
                            <?php foreach($templateParams["notification"] as $notification): ?>
                            <tr>
                                <td class="ps-5"><?php 
                                if($notification["type"] == 1){
                                    echo $stockEmpty1 ."<a class=\"link-product ps-0\" href=\"?page=productPage&id="  . $notification["product"] ."\">". $dbh->getProductInfo($notification["product"])["name"] . "</a>";
                                }else if($notification["type"] == 2){
                                    echo $stockRefill2 ."<a class=\"link-product ps-0\" href=\"?page=productPage&id="  . $notification["product"] ."\">". $dbh->getProductInfo($notification["product"])["name"] . "</a>";
                                }else if($notification["type"] == 3){
                                    echo $purchase3 . $dbh->getUserInfo($notification["user"])["username"];
                                }?></td>
                                <td class="ps-5 fs-6"><?php echo $notification["date"]?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <a href="?page=productPage&id=. $notification["product"] ."></a>
    
<?php 
    }
?>