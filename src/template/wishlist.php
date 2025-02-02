<!-- qua viene visualizzata la cronologia ordini dell'utente -->
<?php
    //lista ordini vuota, allora l'utente non ha ancora effettuato ordini
    if(empty($templateParams["wishlist"])){
        echo "<div class=\"home-profile\">
            <h2>Nessun prodotto è stato ancora aggiunto ai preferiti!</h2>
            <h3><a class=\"link-product\" href=\"?page=search\">Sfoglia Prodotti</a></h3>
            </div>  ";
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