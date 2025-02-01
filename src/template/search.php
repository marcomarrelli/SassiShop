<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $name_filter = isset($_POST["name_filter"]) ? $_POST["name_filter"] : "";
        $category_filter = isset($_POST["category_filter"]) ? $_POST["category_filter"] : -1;
    }

    $templateParams["productList"] = $dbh->getProducts($name_filter, $category_filter);
?>

<h1>Lista Prodotti</h1>

<?php if (!empty($templateParams["productList"])): ?>
    <?php foreach ($templateParams["productList"] as $product): ?>
        <div>
            <h2><?php echo $product['name']; ?></h2>
            <p>Prezzo: <?php echo $product['price']; ?> €</p>
            <p>Quantità: <?php echo $product['quantity']; ?></p>
            <p>Descrizione: <?php echo $product['description']; ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nessun prodotto trovato.</p>
<?php endif; ?>