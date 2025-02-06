
<!-- qua vengono visualizzate le notifiche dell'utente -->
<?php
    //lista notifiche vuota, allora l'utente non ha ancora nessuna notifica
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
    }else if(empty($templateParams["notification"])){ ?>
        <div class="home-profile">
            <h2>Non hai ancora ricevuto nessuna notifica!</h2>
            <h3><a class="link-product" href="?page=search">Sfoglia Prodotti</a></h3>
        </div>
    <?php }else{ //se l'utente ha effettuato almeno un ordine, stampo gli ordini
     ?>    

    <div class="container-fluid align-items-center justify-content-center mt-4">
        <div class="card shadow-sm">
            <div class="card-header form-profile">
                <h5 class="card-title fw-semibold mb-0">Notifiche</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                        </thead>
                        <tbody>
                            <?php foreach($templateParams["notification"] as $notification): ?>
                                <tr class="notification-row" data-notification-id="<?php echo $notification["id"]?>">
                                    <td scope="row" class=" col-auto ps-3 pe-0 text-center">    
                                    <?php if(!$notification["isRead"]){ ?>
                                        <i class="bi bi-circle-fill text-primary"></i>
                                    <?php } ?>
                                    </td>
                                    <td class="py-3"> 
                                        <div class="d-flex flex-column fw-semibold text-dark">
                                            <?php echo " <b>";
                                            switch($notification["type"]) {
                                                case 1:
                                                    echo '<i class="bi bi-exclamation-circle text-danger me-2"></i>Prodotto Esaurito';
                                                    break;
                                                case 2:
                                                    echo '<i class="bi bi-check-circle text-success me-2"></i>Prodotto Rifornito';
                                                    break;
                                                case 3:
                                                    echo '<i class="bi bi-bag-check text-primary me-2"></i>Acquisto Completato';
                                                    break;
                                                case 4:
                                                    echo '<i class="bi bi-cart-dash text-danger me-2"></i>Prodotto in Esaurimento';
                                                    break;
                                            }?>
                                            </b>
                                        </div>
                                    <td class="ps-5 fs-6"> <small class="text-muted"><?php echo $notification["date"]?> </small></td>
                                </tr> 
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
<?php 
    }
?>

<script src="js/notificationHelper.js"></script>