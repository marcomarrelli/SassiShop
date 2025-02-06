<?php
if (!isset($_GET["id"])) {
    header("Location: ?page=search");
    exit();
}

$productId = $_GET["id"];
$product = $dbh->getProductInfo($productId);

if (empty($product)) {
    header("Location: ?page=search");
}
?>

<div class="top-banner">
    <div class="banner-success text-center">
    </div>
</div>

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col">
            <?php $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?page=search'; ?>
            <a href="<?php echo $previousPage; ?>" class="btn btn-outline-secondary go-back-search">
                <i class="bi bi-arrow-left"></i> Torna alla Ricerca
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <img src=<?php echo getProductImage($product); ?> class="img-fluid" alt="<?php echo $product['name']; ?>">
        </div>

        <div class="col-md-6">
            <h2 class="mb-3"><?php echo $product['name']; ?></h2>
            <p class="text-muted mb-4"><?php echo $product['description']; ?></p>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">€ <?php echo $product['price']; ?></h3>
                <?php if($templateParams["userLogged"]){ 
                    if(isAdmin()) { ?>
                        <a href="?page=manageProduct&action=edit&id=<?php echo $product['id']; ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                    <?php } else { ?>
                        <button class="btn btn-outline-danger btn-sm" name="heart">
                            <p class="d-none"><?php echo $product["id"] ?></p>
                            <?php if($dbh->checkProductWishlist($product["id"], $_SESSION["idUser"])){?>
                                <i class="bi bi-heart-fill"></i>
                            <?php }else{?>
                                <i class="bi bi-heart"></i>
                            <?php }?>
                        </button>
                    <?php } 
                } ?>
            </div>

            <p class="text-muted mb-3">
                <?php echo getQuantityAlert($product['quantity']); ?>
            </p>

            <?php if($product['quantity'] > 0 && !isAdmin()): ?>
                <div class="row align-items-center mb-4">
                    <div class="col-4">
                        <div class="input-group">
                            <button class="btn btn-outline-secondary quantity-remover" type="button">-</button>
                            <input type="number" name="product-quantity" class="form-control text-center" value="1" min="1" max="<?php echo $product['quantity']; ?>">
                            <button class="btn btn-outline-secondary quantity-adder" type="button">+</button>
                        </div>
                    </div>
                    <div class="col-8 text-end">
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <h4>Dettagli Prodotto</h4>
                <table class="product-details-table">
                    <tbody>
                        <tr>
                            <th scope="row">Categoria</th>
                            <td><?php echo $dbh->getCategoryName($product['category']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Dimensione</th>
                            <td><?php echo $dbh->getSizeName($product['size']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Disponibilità</th>
                            <td><?php echo ($product['quantity'] == 1) ? $product['quantity'] . " pezzo" : $product['quantity'] . " pezzi"?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="js/productHelper.js"></script>