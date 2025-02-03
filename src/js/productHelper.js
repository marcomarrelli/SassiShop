document.querySelectorAll("button[name='heart']").forEach(b => {
    b.addEventListener("click",function(){

        let productId = this.querySelector("p").innerHTML; //prendo il paragrafo nascosto collegato al bottone che mi comunica l'id del prodotto

        const banner = document.querySelector("div[class='top-banner']"); //prendo la parte di html che mi crea il banner
        const bannerContent = banner.querySelector('div');
        
        let icon = this.querySelector("i"); //prendo l'icona collegata al bottone


        //costruisco il json
        const productData = {
            id: productId,
        };

        //mando tutto alla pagina php che si occupa della comunicazione con il database
        fetch('addProductWishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(productData)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                //se ha avuto successo controllo se l'elemento è da rimuovere dai preferifi o da aggiungere
                if(icon.classList.contains("bi-heart")){
                    icon.classList.remove("bi-heart");
                    icon.classList.add("bi-heart-fill");
                    bannerContent.innerHTML = `<strong> Elemento aggiunto ai preferiti! </strong> `;
                }else{
                    icon.classList.remove("bi-heart-fill");
                    icon.classList.add("bi-heart");
                    bannerContent.innerHTML = `<strong> Elemento rimosso dai preferiti! </strong> `;
                }
            } else{
                bannerContent.innerHTML = `<strong> Errore! </strong> `;
            }
        })

         // Mostra il banner
         banner.classList.add('show');
        
         // Nascondi il banner dopo 3 secondi
         setTimeout(() => {
             banner.classList.remove('show');
         }, 3000);
    });
});

/** Funzione che permette di aggiungere un prodotto al carrello */
document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', function(e) {
        const productId = this.dataset.productId;
        window.location.href = `?page=productPage&id=${productId}`;
    });
});

/** Funzione che permette di visualizzare la pagina del prodotto */
document.querySelectorAll('button.card-purchase-button').forEach(button => {
    button.addEventListener('click', function(e) {
        e.stopPropagation();
        const productId = this.closest('.product-card').dataset.productId;
    });
});

/** Funzione helper per lo spinner */
function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    const currentValue = parseInt(input.value);
    if (currentValue < max) {
        input.value = currentValue + 1;
    }
}

/** Funzione helper per lo spinner */
function decrementQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

/** Funzione che permette di aggiungere un prodotto al carrello */
document.querySelector('.product-quantity').addEventListener('change', function() {
    const max = parseInt(this.max);
    const value = parseInt(this.value);
    if (value > max) this.value = max;
    if (value < 1) this.value = 1;
});

document.querySelector('.quantity-adder').addEventListener('click', incrementQuantity);
document.querySelector('.quantity-remover').addEventListener('click', decrementQuantity);