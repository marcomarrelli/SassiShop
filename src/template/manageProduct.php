<?php
if(!isUserLoggedIn() || !isAdmin()){
    header("Location: ?page=home");
    exit();
}

$editMode = isset($_GET['action']) && $_GET['action'] === 'edit';
$productToEdit = null;

if($editMode && isset($_GET['id'])) {
    $productToEdit = $dbh->getProductInfo($_GET['id']);
    if(!$productToEdit) {
        header("Location: ?page=search");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['productName'] ?? '';
    $description = $_POST['productDescription'] ?? '';
    $price = floatval($_POST['productPrice'] ?? 0);
    $quantity = intval($_POST['productQuantity'] ?? 0);
    $category = intval($_POST['productCategory'] ?? 0);
    $size = intval($_POST['productSize'] ?? 0);
    $productId = intval($_POST['productId'] ?? 0);

    if (empty($name) || empty($description) || $price <= 0 || $quantity <= 0 || $category <= 0 || $size <= 0) {
        echo "<div class='alert alert-danger'>Tutti i campi sono obbligatori!</div>";
    } else {
        $imagePath = null;

        if (isset($_FILES['productImage']) && $_FILES['productImage']['size'] > 0) {

            $uploadResult = uploadProductImage($_FILES['productImage']);
            if ($uploadResult['success']) {
                $imagePath = $uploadResult['path'];
            } else {
                echo "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                exit();
            }
        }

        if($editMode) {
            $oldProductInfo = $dbh->getProductInfo($productId);
            
            if(!$imagePath) {
                $imagePath = $oldProductInfo['image'];
            } else {
                $oldImagePath = '../' . $oldProductInfo['image'];
                if(file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        
            $result = $dbh->updateProduct($productId, $name, $description, $price, $quantity, $category, $size, $imagePath);
        } else {
            if(!$imagePath) {
                echo "<div class='alert alert-danger'>Immagine richiesta!</div>";
                exit();
            }
            $result = $dbh->addProduct($name, $description, $price, $quantity, $category, $size, $imagePath);
        }

        if ($result) {
            echo "<div class='alert alert-success'>Prodotto " . ($editMode ? "modificato" : "aggiunto") . " con successo!</div>";
            header("Refresh: 3; url=?page=home");
        } else {
            echo "<div class='alert alert-danger'>Errore " . ($editMode ? "nella modifica" : "nell'aggiunta") . " del prodotto!</div>";
        }
    }
}
?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header form-profile">
            <h5 class="card-title mb-0"><?php echo $editMode ? "Modifica" : "Aggiungi Nuovo"; ?> Prodotto</h5>
        </div>
        <div class="card-body p-4">
            <form action="#" method="POST" enctype="multipart/form-data">
                <?php if($editMode): ?>
                    <input type="hidden" name="productId" value="<?php echo $productToEdit['id']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="productName" class="form-label">Nome Prodotto</label>
                    <input type="text" class="form-control" id="productName" name="productName" 
                           value="<?php echo $editMode ? htmlspecialchars($productToEdit['name']) : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="productDescription" class="form-label">Descrizione</label>
                    <textarea class="form-control product-description" id="productDescription" name="productDescription" 
                              maxlength="255" rows="3" required><?php echo $editMode ? htmlspecialchars($productToEdit['description']) : ''; ?></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="productPrice" class="form-label">Prezzo (€)</label>
                        <input type="number" class="form-control" id="productPrice" name="productPrice" step="0.01" 
                               value="<?php echo $editMode ? $productToEdit['price'] : '9.99'; ?>" min="0.01" max="9999.99" required>
                    </div>
                    <div class="col-md-6">
                        <label for="productQuantity" class="form-label">Quantità</label>
                        <input type="number" class="form-control" id="productQuantity" name="productQuantity" 
                               value="<?php echo $editMode ? $productToEdit['quantity'] : '1'; ?>" min="1" max="999" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="productCategory" class="form-label">Categoria</label>
                        <select class="form-select" id="productCategory" name="productCategory" required>
                            <option value="">Seleziona una categoria</option>
                            <?php foreach ($dbh->getCategories() as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                    <?php echo ($editMode && $productToEdit['category'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo $category['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="productSize" class="form-label">Dimensione</label>
                        <select class="form-select" id="productSize" name="productSize" required>
                            <option value="">Seleziona una dimensione</option>
                            <?php foreach ($dbh->getSizes() as $size): ?>
                                <option value="<?php echo $size['id']; ?>"
                                    <?php echo ($editMode && $productToEdit['size'] == $size['id']) ? 'selected' : ''; ?>>
                                    <?php echo $size['size']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="productImage" class="form-label">Immagine del Prodotto</label>
                    <input type="file" class="form-control" id="productImage" name="productImage" accept=".jpg,.jpeg,.png">
                    <?php if($editMode): ?>
                        <small class="text-muted">Lascia vuoto per mantenere l'immagine esistente</small>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <?php $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?page=search'; ?>
                    <a href="<?php echo $previousPage; ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Annulla
                    </a>
                    <button type="submit" class="btn create-product-button">
                        <?php echo $editMode ? "Modifica Prodotto" : "Aggiungi Prodotto"; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>