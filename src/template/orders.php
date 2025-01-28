<?php
    if(empty($templateParams["orders"])){
        echo "<p>Nessun ordine è stato ancora effettuato </p>
        <h2><a href=\"?page=search\">Sfoglia Prodotti</a></h2> ";
    }else{
     ?>    

    <table>
        <thead>
            <tr>
                <th>Prodotto</th>
                <th>Quantità</th>
                <th>Stato Ordine</th>
                <th>Prezzo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($templateParams["orders"] as $order): ?>
            <tr>
                <td><?php echo $order["name"]?></td>
                <td><?php echo $order["quantity"]?></td>
                <td><?php echo $order["status"]?></td>
                <td><?php echo $order["price"]?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    
<?php 
    }
?>