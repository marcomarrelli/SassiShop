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

    // Show banner helper function
    function showBanner(message, reload = false) {
        if (!banner || !bannerContent) return;
    
        if (reload) {
            sessionStorage.setItem('bannerMessage', message);
            setTimeout(() => {
                location.reload();
            }, 100);

            return;
        }

        bannerContent.innerHTML = `<strong>${message}</strong>`;
        banner.classList.add('show');

        setTimeout(() => {
            banner.classList.remove('show');
        }, 3000);
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
                    if(icon.classList.contains("bi-heart")) {
                        icon.classList.replace("bi-heart", "bi-heart-fill");
                        showBanner("Elemento aggiunto ai preferiti!");
                    } else {
                        icon.classList.replace("bi-heart-fill", "bi-heart");
                        showBanner("Elemento rimosso dai preferiti!");
                    }
                } else {
                    showBanner("Errore!");
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
    
            fetch('addProductCart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: productId, quantity: 1 })
            })
            .then(response => response.json())
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

    // Product page quantity controls
    const quantityInput = document.querySelector('input[name="product-quantity"]');
    const quantityControls = {
        add: document.querySelector('.quantity-adder'),
        remove: document.querySelector('.quantity-remover')
    };

    if(quantityInput && quantityControls.add && quantityControls.remove) {
        quantityControls.add.addEventListener('click', () => {
            const max = parseInt(quantityInput.max) || 999;
            const current = parseInt(quantityInput.value) || 1;
            if(current < max) quantityInput.value = current + 1;
        });

        quantityControls.remove.addEventListener('click', () => {
            const current = parseInt(quantityInput.value) || 1;
            if(current > 1) quantityInput.value = current - 1;
        });
    }

    // Product page add to cart
    document.querySelector('.add-to-cart')?.addEventListener('click', function() {
        const quantity = parseInt(document.querySelector('input[name="product-quantity"]')?.value) || 1;
        const productId = document.querySelector('button[name="heart"] p')?.innerHTML;

        if (!productId) return;

        fetch('addProductCart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: productId, quantity })
        })
        .then(response => response.json())
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

    const productOrder = document.querySelectorAll('.product-order');
    if (productOrder.length > 0) {
        productOrder.forEach(card => {
            card.addEventListener('click', function(e) {
                if(!e.target.closest('button')) {
                    const productId = this.dataset.productId;
                    if (!productId) {
                        console.error('Product ID not found');
                        return;
                    }
                    window.location.href = `?page=productPage&id=${productId}`;
                }
            });
        });
    }

    // Remove from cart functionality
    document.querySelectorAll('button[name="remove-cart"]')?.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const productCard = this.closest('.product-card');
            const productId = productCard?.dataset.productId;
            
            if (!productId) return;

            fetch('addProductCart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: productId, action: 'remove' })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    cartBadge && (cartBadge.textContent = data.cartCount);
                    const remainingCards = document.querySelectorAll('.product-card');
                    
                    if(remainingCards.length > 1) {
                        productCard.remove();
                        document.querySelector('.mb-0').textContent = `Totale: € ${data.cartTotal.toFixed(2)}`;
                    }

                    showBanner("Prodotto rimosso dal carrello!", true);
                } else {
                    showBanner("Errore nella rimozione dal carrello!");
                }
            });
        });
    });
});