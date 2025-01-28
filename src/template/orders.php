<!-- qua viene visualizzata la cronologia ordini dell'utente -->
<?php
    //lista ordini vuota, allora l'utente non ha ancora effettuato ordini
    if(empty($templateParams["orders"])){
        echo "<p>Nessun ordine è stato ancora effettuato </p>
        <h2><a href=\"?page=search\">Sfoglia Prodotti</a></h2> ";
    }else{ //se l'utente ha effettuato almeno un ordine, stampo gli ordini
     ?>    

    <table>
        <thead>
            <tr>
                <th>Prodotto</th>
                <th>Quantità</th>
                <th>Stato Ordine</th>
                <th>Data</th>
                <th>Prezzo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($templateParams["orders"] as $order): ?>
            <tr>
                <td><?php echo $order["name"]?></td>
                <td><?php echo $order["quantity"]?></td>
                <td><?php echo $order["status"]?></td>
                <td><?php echo $order["date"]?></td>
                <td><?php echo $order["price"]?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    
<?php 
    }
?>