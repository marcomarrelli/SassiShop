<?php
    if(!$templateParams["userLogged"]){ ?>
        <div class="container py-5 d-flex justify-content-center align-items-center">
            <div class="alert alert-warning text-center no-login-cart-alert w-75" role="alert">
                <h4 class="alert-heading">Effettua l'accesso per vedere il carrello!</h4>
                <p>Per visualizzare i prodotti nel tuo carrello devi prima effettuare l'accesso.</p>
                <hr>
                <a href="?page=profile" class="btn btn-warning">Vai al Login!</a>
            </div>
        </div>
    <?php } else if(empty($templateParams["cartProducts"])){ ?>
        <p>Nessun prodotto è stato ancora aggiunto al carrello</p>
        <h2><a href="?page=search">Sfoglia Prodotti</a></h2>
    <?php } else { ?>
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