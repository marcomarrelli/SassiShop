<div class="top-banner">
    <div class="banner-success text-center">
    </div>
</div>

<?php
    $name_filter = "";
    $category_filter = -1;

    if(isset($_GET["filtering"]) || isset($_GET["category"])) {
        $name_filter = isset($_GET["filtering"]) ? $_GET["filtering"] : "";
        $category_filter = isset($_GET["category"]) ? $_GET["category"] : -1;
        $templateParams["productList"] = $dbh->getProducts($name_filter, $category_filter);
    }
    else $templateParams["productList"] = $dbh->getProducts();
?>

<form action="?page=search" method="GET">
    <input type="hidden" name="page" value="search">
    <div class="home-search-container">
        <div class="input-group home-search-input">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control" id="inlineFormInputGroupUsername2" name="filtering" placeholder="Cerca il tuo sasso..." value="<?php echo htmlspecialchars($name_filter); ?>">
        </div>

        <div class="input-group home-select-category">
            <i class="bi bi-columns-gap"></i>
            <select class="form-select" aria-label="Cerca tra tutte le categorie" name="category">
                <option class="home-select-category-placeholder" value="-1">Tutte le categorie</option>
                <?php foreach ($dbh->getCategories() as $category): ?>
                    <option value="<?php echo $category["id"]; ?>" <?php echo ($category_filter == $category["id"]) ? 'selected' : ''; ?>>
                        <?php echo $category["name"]; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="home-search-and-filter d-flex gap-2">
            <button type="submit" class="btn home-search-button">Cerca</button>
        </div>
    </div>
</form>

<?php if (!empty($templateParams["productList"])): ?>
    <?php foreach ($templateParams["productList"] as $product): ?>
        <div class="card mb-3 product-card" data-product-id="<?php echo $product['id']; ?>">
            <div class="row no-gutters h-100">
                <div class="col-md-4">
                    <img src=<?php echo getProductImage($product); ?> class="card-img" alt="Prodotto: <?php echo $product['name']; ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title"><?php echo $product['name']; ?></h4>
                            <?php if($templateParams["userLogged"]){ ?>
                                <button class="btn btn-outline-danger btn-sm" name="heart">
                                    <p class="d-none"><?php echo $product["id"] ?></p>
                                    <?php if($dbh->checkProductWishlist($product["id"], $_SESSION["idUser"])){?>
                                        <i class="bi bi-heart-fill"></i>
                                    <?php }else{?>
                                        <i class="bi bi-heart"></i>
                                    <?php }?>
                                </button>
                            <?php } ?>
                        </div>
                        <p class="card-text"><?php echo string_cutter($product['description'], 50); ?></p>
                        <p class="card-subtext"><small class="text-muted"><?php echo getQuantityAlert($product['quantity']); ?></small></p>
                        <button class="btn card-purchase-button">
                            Compra a <?php echo $product['price']; ?>€
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="container py-5 d-flex justify-content-center align-items-center">
        <div class="alert alert-warning text-center w-75" role="alert">
            <h4 class="alert-heading">Nessun prodotto trovato!</h4>
            <p>La ricerca "<?php echo htmlspecialchars($name_filter); ?>" non ha prodotto risultati. Prova a cercare utilizzando altri termini o categorie.</p>
            <hr>
            <a href="?page=search" class="btn btn-warning">Torna alla Ricerca</a>
        </div>
    </div>
<?php endif; ?>

<script src="js/productHelper.js"></script>