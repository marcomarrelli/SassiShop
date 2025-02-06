<!-- qua viene visualizzata la cronologia ordini dell'utente -->
<?php
    //lista ordini vuota, allora l'utente non ha ancora effettuato ordini
    if(empty($templateParams["orders"])){
        echo " <div class=\"home-profile\">
                <h2>Non è ancora stato fatto nessun ordine!</h2>
                <h3><a class=\"link-product\" href=\"?page=search\">Sfoglia Prodotti</a></h3>
                </div>  ";
    }else{ //se l'utente ha effettuato almeno un ordine, stampo gli ordini
     ?>    


    <div class="container-fluid align-items-center justify-content-center mt-4">
        <div class="card shadow-sm">
            <div class="card-header form-profile">
                <h5 class="card-title mb-0">Cronologia Ordini</h5>
            </div>
            <div class="card-body p-2 p-md-4">
                <div class="table-responsive">
                    <?php foreach($templateParams["orders"] as $order): ?>
                    <div class="d-flex justify-content-between align-items-center header-profile text-white p-3 rounded">
                        <div>
                            <i class="fas me-2"></i>
                            Ordine del: <?php echo $order["date"]?>
                        </div>
                        <?php if($_SESSION["privilege"] == 1){ ?>
                            <div>
                                Utente: <?php echo $order["username"]?>
                            </div>
                        <?php  } ?>
                        <div class="badge bg-light text-danger">
                            Status: <?php echo $order["status"]?>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr class="fs-5">
                            <th class="d-none d-md-table-cell col-1"></th>
                                <th class="ps-5">Prodotto</th>
                                <th class="d-none d-md-table-cell">Quantità</th>
                                <th>Prezzo</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                            $productList = $dbh->getProductList($order["purchaseId"]); 
                            $total = 0;?>
                            <?php foreach($productList as $product): ?>
                            <tr class="product-order" data-product-id="<?php echo $product['id']; ?>">
                                <td scope="row" class="d-none d-md-table-cell col-2"> <img class="img-responsive img-fluid" src="<?php echo $product['image']?>" alt="Prodotto: <?php echo $product["name"] ?>"> </td>
                                <td class="ps-5 fs-5"><?php echo $product["name"]?></td>
                                <td class="d-none d-md-table-cell fs-5"><?php echo $product["orderQuantity"]?></td>
                                <td class="fs-5"><?php $total += $product["price"]*$product["orderQuantity"];
                                echo $product["price"]?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="table-danger fw-bold fs-5">
                                <td class="d-none d-md-table-cell"></td>
                                <td colspan="2">Totale Ordine</td>
                                <td><?php echo $total?> €</td>
                            </tr>
                        </tbody>
                    </table>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
<?php 
    }
?>
<script src="js/productHelper.js"></script>