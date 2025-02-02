<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $name_filter = isset($_POST["name_filter"]) ? $_POST["name_filter"] : "";
        $category_filter = isset($_POST["category_filter"]) ? $_POST["category_filter"] : -1;
        $templateParams ["productList"] = $dbh->getProducts ($name_filter, $category_filter);
    }else{
        $templateParams ["productList"] = $dbh->getProducts();
    }
?>

<h1>Lista Prodotti</h1>

<?php if (!empty($templateParams["productList"])): ?>
    <?php foreach ($templateParams["productList"] as $product): ?>
        <div class="card mb-3">
            <div class="row no-gutters">
                <div class="col-md-4">
                <img src="../assets/images/placeholders/not_available.png" class="card-img" alt="Prodotto: <?php echo $product['name']; ?>">
                </div>
                <div class="col-md-8">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                    <h5 class="card-title"><?php echo $product['name']; ?></h5>

                    <button class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-heart"></i>
                    </button>

                    </div>
                    <p class="card-text"><?php 
                        echo string_cutter($product['description'], 50);
                    ?></p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary">Compra a <?php echo $product['price']; ?>€</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nessun prodotto trovato.</p>
<?php endif; ?>

        <!-- <div>
            <h2></h2>
            <p>Prezzo: <?php echo $product['price']; ?> €</p>
            <p>Quantità: <?php echo $product['quantity']; ?></p>
            <p>Descrizione: <?php echo $product['description']; ?></p>
        </div> -->