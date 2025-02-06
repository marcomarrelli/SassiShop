<!-- qua viene visualizzata la cronologia ordini dell'utente -->

<div class="top-banner">
    <div class="banner-success text-center">
    </div>
</div>

<?php
    //lista ordini vuota, allora l'utente non ha ancora effettuato ordini
    if(empty($templateParams["wishlist"])){
        echo "<div class=\"home-profile\">
            <h2>Nessun prodotto è stato ancora aggiunto!</h2>
            <h3><a class=\"link-product\" href=\"?page=search\">Sfoglia Prodotti</a></h3>
            </div>  ";
    }else{ //se l'utente ha effettuato almeno un ordine, stampo gli ordini
     ?>    



    
    <?php foreach ($templateParams["wishlist"] as $product): ?>
        <div class="card mb-3 product-card" data-product-id="<?php echo $product['id']; ?>">
            <div class="row no-gutters h-100">
                <div class="col-md-4">
                    <img src=<?php echo getProductImage($product); ?> class="card-img" alt="Prodotto: <?php echo $product['name']; ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title"><?php echo $product['name']; ?></h4>
                                <?php if(isAdmin()) { ?>
                                    <a href="?page=manageProduct&action=edit&id=<?php echo $product['id']; ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                <?php } else { ?>
                                    <button class="btn btn-outline-danger btn-sm" name="heart">
                                        <p class="d-none"><?php echo $product["id"] ?></p>
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                <?php }     ?>
                            
                        </div>
                        <p class="card-text"><?php echo stringCutter($product['description'], 50); ?></p>
                        <p class="card-subtext"><small class="text-muted"><?php echo getQuantityAlert($product['quantity']); ?></small></p>
                        <?php if(isset($_SESSION["idUser"]) && $_SESSION["privilege"] == 2): ?>
                            <button class="btn card-purchase-button">
                                <?php echo $product['quantity'] > 0 ? "Compra a " . $product['price'] . "€" : "Prodotto esaurito"; ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
        
<?php 
    }
?>

<script src="js/productHelper.js"></script>