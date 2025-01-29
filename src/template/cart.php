<!-- qua viene visualizzata il carrello dell'utente -->
<?php
    //lista ordini vuota, allora l'utente non ha ancora effettuato ordini
    if(empty($templateParams["cartProducts"])){
        echo "<p>Nessun prodotto è stato ancora aggiunto al carrello</p>
        <h2><a href=\"?page=search\">Sfoglia Prodotti</a></h2> ";
    }else{ //se l'utente ha effettuato almeno un ordine, stampo gli ordini
     ?>    

    <table>
        <thead>
            <tr>
                <th>Prodotto</th>
                <th>Quantità</th>
                <th>Prezzo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($templateParams["cartProducts"] as $product): ?>
            <tr>
                <td><?php echo $product["name"]?></td>
                <td><?php echo $product["quantity"]?></td>
                <td><?php echo $product["price"]?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    
<?php 
    }
?>