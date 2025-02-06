<?php
    if(!isset($_GET["notificationId"])){
        header("Location: ?page=notification");
    }else{
        $stockEmpty1 = "Oh nooo! È esaurito un prodotto presente nel tuo carrello: ";
        $stockRefill2 = "Yuppi! È stato rifornito un prodotto presente nel tuo carrello: ";
        $purchase3 = "È stato effettuato un ordine dall'utente: ";
        $notification = $dbh->getNotificationInfo($_GET["notificationId"]);

        $dbh->readNotification($notification["id"]);
    }
?>



<div class="row mb-4 ps-3">
    <div class="col">
        <a href="?page=notification" class="btn btn-outline-secondary go-back-search">
            <i class="bi bi-arrow-left"></i> Torna alle notifiche
        </a>
    </div>
</div>
<div class="container-fluid align-items-center justify-content-center mt-4">
    <div class="card shadow-sm">
        <div class="card-header form-profile">
            <h5 class="card-title mb-0">Notifiche</h5>
        </div>
        <div class="card-body p-2 p-md-4">
            <?php
            if($notification["type"] == 1){
                echo $stockEmpty1 ."<a class=\"link-product ps-0\" href=\"?page=productPage&id="  . $notification["product"] ."\">". $dbh->getProductInfo($notification["product"])["name"] . "</a>";
            }else if($notification["type"] == 2){
                echo $stockRefill2 ."<a class=\"link-product ps-0\" href=\"?page=productPage&id="  . $notification["product"] ."\">". $dbh->getProductInfo($notification["product"])["name"] . "</a>";
            }else if($notification["type"] == 3){
                echo $purchase3 . $dbh->getUserInfo($notification["purchaseUser"])["username"];
            }?>
            <p class="mt-3"><?php echo $notification["date"] ?></p>
        </div>
    </div>
</div>


<script src="js/notificationHelper.js"></script>