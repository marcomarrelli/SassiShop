<div class="top-banner">
    <div class="banner-success text-center">
    </div>
</div>

<?php if(!$templateParams["userLogged"]): ?>
    <div class="container py-5 d-flex justify-content-center align-items-center">
        <div class="alert alert-warning text-center no-login-cart-alert w-75" role="alert">
            <h4 class="alert-heading">Effettua l'accesso per vedere il carrello!</h4>
            <p>Per visualizzare i prodotti nel tuo carrello devi prima effettuare l'accesso.</p>
            <hr>
            <a href="?page=profile" class="btn btn-warning">Vai al Login!</a>
        </div>
    </div>
<?php elseif(empty($templateParams["cartProducts"])): ?>
    <div class="home-profile">
        <h2>Nessun prodotto è stato ancora aggiunto al carrello!</h2>
        <h3><a class="link-product" href="?page=search">Sfoglia Prodotti</a></h3>
    </div>
<?php else: ?>
    <div class="container mt-4">
    <?php foreach ($templateParams["cartProducts"] as $product): ?>
        <div class="card mb-3 product-card" data-product-id="<?php echo $product['id']; ?>">
            <div class="row no-gutters h-100">
                <div class="col-md-4">
                    <img src=<?php echo getProductImage($product); ?> class="card-img" alt="Prodotto: <?php echo $product['name']; ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title"><?php echo $product['name']; ?></h4>
                            <button class="btn btn-outline-danger btn-sm" name="remove-cart">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <p class="card-text"><?php echo stringCutter($product['description'], 50); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="input-group" style="width: 130px;">
                                <!-- <button class="btn btn-outline-secondary quantity-remover" type="button">-</button>
                                <input type="number" class="form-control text-center" value=" php echo $product['quantity']; " min="1">
                                <button class="btn btn-outline-secondary quantity-adder" type="button">+</button> -->
                                <input type="number" class="form-control text-center" value="<?php echo $product['quantity']; ?>" readonly>
                            </div>
                            <p class="h5 mb-0">€ <?php echo number_format($product['price'] * $product['quantity'], 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

        <div class="card mt-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Totale: € <?php echo number_format($dbh->getCartTotal($_SESSION["idUser"]), 2); ?></h4>
                <button class="btn card-purchase-button">Procedi all'Acquisto</button>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="js/productHelper.js"></script>