<!-- qua vengono visualizzate le notifiche dell'utente -->
<?php
    //lista ordini vuota, allora l'utente non ha ancora effettuato ordini
    if(!$templateParams["userLogged"]){
        echo " <h2> Effettua l'accesso per vedere le tue notifiche </h2> ";
    }else if(empty($templateParams["notification"])){
        echo "<p>Non hai nessuna notifica</p>
        <h2><a href=\"?page=search\">Sfoglia Prodotti</a></h2> ";
    }else{ //se l'utente ha effettuato almeno un ordine, stampo gli ordini
     ?>    

    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Tipo</th>
                <th>Testo</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($templateParams["notifications"] as $notification): ?>
            <tr>
                <td><?php echo $notification["id"]?></td>
                <td><?php echo $notification["type"]?></td>
                <td><?php echo $notification["text"]?></td>
                <td><?php echo $notification["date"]?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    
<?php 
    }
?>