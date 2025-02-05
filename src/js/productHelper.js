document.addEventListener('DOMContentLoaded', function() {
    // Common elements
    const banner = document.querySelector("div[class='top-banner']");
    const bannerContent = banner?.querySelector('div');
    const cartBadge = document.getElementById('cartBadge');

    // Check for stored banner message
    const storedMessage = sessionStorage.getItem('bannerMessage');
    if (storedMessage) {
        showBanner(storedMessage);
        sessionStorage.removeItem('bannerMessage');
    }

    function showBanner(message, reload = false) {
        if (!banner || !bannerContent) return;
        
        bannerContent.innerHTML = `<strong>${message}</strong>`;
        banner.classList.add('show');

        if (reload) {
            sessionStorage.setItem('bannerMessage', message);
            setTimeout(() => {
                banner.classList.remove('show');
                location.reload();
            }, 25);
            return;
        }

        setTimeout(() => {
            banner.classList.remove('show');
        }, 2500);
    }

    // Cart quantity update functionality
    function updateCartQuantity(productId, newQuantity, action = 'update') {
        return fetch('addProductCart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: productId, quantity: newQuantity, action })
        }).then(response => response.json());
    }

    // Wishlist functionality
    document.querySelectorAll('button[name="heart"]')?.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const productId = this.querySelector('p')?.innerHTML;
            const icon = this.querySelector('i');
            
            if (!productId || !icon) return;

            fetch('addProductWishlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const isAdding = icon.classList.contains("bi-heart");
                    icon.classList.replace(
                        isAdding ? "bi-heart" : "bi-heart-fill",
                        isAdding ? "bi-heart-fill" : "bi-heart"
                    );
                    showBanner(`Elemento ${isAdding ? 'aggiunto ai' : 'rimosso dai'} preferiti!`);
                } else {
                    showBanner("Errore!");
                }
            });
        });
    });

    // Cart quantity controls
    document.querySelectorAll('.quantity-adder, .quantity-remover')?.forEach(button => {
        button.addEventListener('click', function() {
            const container = this.closest('.input-group');
            const input = container?.querySelector('input[type="number"]');
            const productCard = this.closest('.product-card');
            
            if (!input || !productCard) return;
    
            const productId = productCard.dataset.productId;
            const isAdding = this.classList.contains('quantity-adder');
            const currentValue = parseInt(input.value) || 1;
            const newQuantity = isAdding ? currentValue + 1 : currentValue - 1;
            const maxQuantity = parseInt(input.max) || 999;
    
            if (newQuantity < 1 || newQuantity > maxQuantity) return;
    
            updateCartQuantity(productId, newQuantity, isAdding ? 'increment' : 'decrement')
                .then(data => {
                    if (data.success) {
                        input.value = newQuantity;
                        
                        // Update price display
                        const priceElement = productCard.querySelector('.h5.mb-0');
                        if (priceElement) {
                            priceElement.textContent = `€ ${(data.unitPrice * newQuantity).toFixed(2)}`;
                        }
    
                        // Update total
                        const totalElement = document.querySelector('.card-body .mb-0');
                        if (totalElement && data.cartTotal) {
                            totalElement.textContent = `Totale: € ${data.cartTotal.toFixed(2)}`;
                        }
    
                        showBanner("Quantità aggiornata!");
                    } else {
                        showBanner("Errore nell'aggiornamento della quantità!");
                    }
                });
        });
    });

    // Product card purchase functionality
    document.querySelectorAll('.card-purchase-button')?.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const productId = this.closest('.product-card')?.dataset.productId;
            if (!productId) return;

            updateCartQuantity(productId, 1, 'add')
                .then(data => {
                    if(data.success) {
                        cartBadge && (cartBadge.textContent = data.cartCount);
                        showBanner("Prodotto aggiunto al carrello!");
                    } else {
                        showBanner("Errore nell'aggiunta al carrello!");
                    }
                });
        });
    });

    // Product page add to cart
    document.querySelector('.add-to-cart')?.addEventListener('click', function() {
        const quantityInput = document.querySelector('input[name="product-quantity"]');
        const productId = document.querySelector('button[name="heart"] p')?.innerHTML;
        const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

        if (!productId) return;

        updateCartQuantity(productId, quantity, 'add')
            .then(data => {
                if(data.success) {
                    cartBadge && (cartBadge.textContent = data.cartCount);
                    showBanner("Prodotto aggiunto al carrello!");
                } else {
                    showBanner("Errore nell'aggiunta al carrello!");
                }
            });
    });

    // Product card navigation
    document.querySelectorAll('.product-card')?.forEach(card => {
        card.addEventListener('click', function(e) {
            if(!e.target.closest('button')) {
                const productId = this.dataset.productId;
                if (productId) window.location.href = `?page=productPage&id=${productId}`;
            }
        });
    });

    // Remove from cart functionality
    document.querySelectorAll('button[name="remove-cart"]')?.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const productCard = this.closest('.product-card');
            const productId = productCard?.dataset.productId;
            
            if (!productId) return;

            updateCartQuantity(productId, 0, 'remove')
                .then(data => {
                    if(data.success) {
                        cartBadge && (cartBadge.textContent = data.cartCount);
                        const remainingCards = document.querySelectorAll('.product-card');
                        
                        if(remainingCards.length <= 1) {
                            showBanner("Prodotto rimosso dal carrello!", true);
                        } else {
                            productCard.remove();
                            const totalElement = document.querySelector('.card-body .mb-0');
                            if(totalElement && data.cartTotal) {
                                totalElement.textContent = `Totale: € ${data.cartTotal.toFixed(2)}`;
                            }
                            showBanner("Prodotto rimosso dal carrello!", true);
                        }
                    } else {
                        showBanner("Errore nella rimozione dal carrello!");
                    }
                });
        });
    });
});