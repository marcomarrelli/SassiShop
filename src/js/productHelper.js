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

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                const productId = this.dataset.productId;
                window.location.href = `?page=productPage&id=${productId}`;
            }
        });
    });

    const quantityInput = document.querySelector("input[name='product-quantity']");
    const addButton = document.querySelector('.quantity-adder');
    const removeButton = document.querySelector('.quantity-remover');

    if (quantityInput && addButton && removeButton) {
        function incrementQuantity() {
            const max = parseInt(quantityInput.max);
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < max) {
                quantityInput.value = currentValue + 1;
            }
        }

        function decrementQuantity() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }

        quantityInput.addEventListener('change', function() {
            const max = parseInt(this.max);
            const value = parseInt(this.value);
            if (value > max) this.value = max;
            if (value < 1) this.value = 1;
        });

        addButton.addEventListener('click', incrementQuantity);
        removeButton.addEventListener('click', decrementQuantity);
    }

    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams();
            
            params.append('page', 'search');
            if(formData.get('filtering').trim() !== '') {
                params.append('filtering', formData.get('filtering'));
            }
            if(formData.get('category') !== '-1') {
                params.append('category', formData.get('category'));
            }
            
            window.location.href = '?' + params.toString();
        });
    }
});