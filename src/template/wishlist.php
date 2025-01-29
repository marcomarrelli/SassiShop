<!-- qua viene visualizzata la cronologia ordini dell'utente -->
<?php
    //lista ordini vuota, allora l'utente non ha ancora effettuato ordini
    if(empty($templateParams["wishlist"])){
        echo "<p>Nessun prodotto è stato ancora aggiunto ai preferiti </p>
        <h2><a href=\"?page=search\">Sfoglia Prodotti</a></h2> ";
    }else{ //se l'utente ha effettuato almeno un ordine, stampo gli ordini
     ?>    


    <table>
        <thead>
            <tr>
                <th>Prodotto</th>
                <th>Prezzo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($templateParams["wishlist"] as $wish): ?>
            <tr>
                <td><?php echo $wish["name"]?></td>
                <td><?php echo $wish["price"]?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    
<?php 
    }
?>