<!-- qua viene visualizzata la cronologia ordini dell'utente -->

<div class="top-banner">
    <div class="banner-success text-center">
    </div>
</div>

<?php
    //lista ordini vuota, allora l'utente non ha ancora effettuato ordini
    if(empty($templateParams["wishlist"])){
        echo "<div class=\"home-profile\">
            <h2>Nessun prodotto è stato ancora aggiunto ai preferiti!</h2>
            <h3><a class=\"link-product\" href=\"?page=search\">Sfoglia Prodotti</a></h3>
            </div>  ";
    }else{ //se l'utente ha effettuato almeno un ordine, stampo gli ordini
     ?>    


    
    <?php foreach($templateParams["wishlist"] as $wish): ?>
        <div class="card mb-3">
            <div class="row no-gutters">
                <div class="col-md-4 text-center">
                    <img class="img" src="../assets/images/placeholders/not_available.png" class="card-img" alt="Prodotto: <?php echo $wish['name']; ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body m-5">
                        <div class="d-flex justify-content-between">
                        <h5 class="card-title"><?php echo $wish['name']; ?></h5>
        
                        <button class="btn btn-outline-danger btn-sm" name="heart">
                                <!-- paragrafo nascosto per comunicare l'id del prodotto allo script js -->
                                <p class="d-none"><?php echo $wish['product'] ?></p>
                                <i class="bi bi-heart-fill"></i>
                        </button>
                        
        
                        </div>
                        <p class="card-text"><?php 
                            echo string_cutter($wish['description'], 50);
                        ?></p>
                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">Compra a <?php echo $wish['price']; ?>€</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
        
<?php 
    }
?>

<script src="js/productHelper.js"></script>