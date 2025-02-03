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
                    <table class="table table-hover">
                        <thead class="table-danger">
                            <tr>
                                <th scope="col" class="d-none d-md-table-cell">#</th>
                                <th scope="col">Prodotto</th>
                                <th scope="col" class="d-none d-md-table-cell">Quantità</th>
                                <th scope="col">Stato Ordine</th>
                                <th scope="col" class="d-none d-md-table-cell">Data</th>
                                <th scope="col">Prezzo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cont=0;
                                foreach($templateParams["orders"] as $order):
                                $cont++; ?>
                            <tr>
                                <th scope="row" class="d-none d-md-table-cell"><?php echo $cont?></th>
                                <td><?php echo $order["name"]?></td>
                                <td class="d-none d-md-table-cell"><?php echo $order["quantity"]?></td>
                                <td><?php echo $order["status"]?></td>
                                <td data-label="Data" class="d-none d-md-table-cell"><?php echo $order["date"]?></td>
                                <td><?php echo $order["price"]*$order["quantity"]?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
<?php 
    }
?>