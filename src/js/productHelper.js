document.addEventListener('DOMContentLoaded', function() {
    // Common elements
    const banner = document.querySelector("div[class='top-banner']");
    const bannerContent = banner?.querySelector('div');
    const cartBadge = document.getElementById('cartBadge');

    // Show banner helper function
    function showBanner(message) {
        if (!banner || !bannerContent) return;
        bannerContent.innerHTML = `<strong>${message}</strong>`;
        banner.classList.add('show');
        setTimeout(() => {
            banner.classList.remove('show');
        }, 3000);
    }

    // Wishlist functionality
    const wishlistButtons = document.querySelectorAll('button[name="heart"]');
    if (wishlistButtons.length > 0) {
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const productIdElement = this.querySelector('p');
                const icon = this.querySelector('i');
                
                if (!productIdElement || !icon) return;
                
                const productId = productIdElement.innerHTML;

                fetch('addProductWishlist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
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
    }

    // Product card purchase functionality
    const purchaseButtons = document.querySelectorAll('.card-purchase-button');
    if (purchaseButtons.length > 0) {
        purchaseButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                
                const productCard = this.closest('.product-card');
                if (!productCard) {
                    console.error('Product card not found');
                    return;
                }
                
                const productId = productCard.dataset.productId;
                if (!productId) {
                    console.error('Product ID not found');
                    return;
                }
        
                fetch('addProductCart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: productId,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showBanner("Prodotto aggiunto al carrello!");
                        if(cartBadge) {
                            cartBadge.textContent = data.cartCount;
                        }
                    } else {
                        showBanner("Errore nell'aggiunta al carrello!");
                    }
                });
            });
        });
    }

    // Product page quantity controls
    const quantityInput = document.querySelector('input[name="product-quantity"]');
    const addButton = document.querySelector('.quantity-adder');
    const removeButton = document.querySelector('.quantity-remover');

    if(quantityInput && addButton && removeButton) {
        function incrementQuantity() {
            const max = parseInt(quantityInput.max) || 999;
            const currentValue = parseInt(quantityInput.value) || 1;
            if(currentValue < max) {
                quantityInput.value = currentValue + 1;
            }
        }

        function decrementQuantity() {
            const currentValue = parseInt(quantityInput.value) || 1;
            if(currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }

        addButton.addEventListener('click', incrementQuantity);
        removeButton.addEventListener('click', decrementQuantity);
    }

    // Product page add to cart
    const addToCartBtn = document.querySelector('.add-to-cart');
    if(addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const quantityInput = document.querySelector('input[name="product-quantity"]');
            const productIdElement = document.querySelector('button[name="heart"] p');

            if (!quantityInput || !productIdElement) {
                console.error('Required elements not found');
                return;
            }

            const productId = productIdElement.innerHTML;
            const quantity = parseInt(quantityInput.value) || 1;

            fetch('addProductCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showBanner("Prodotto aggiunto al carrello!");
                    if(cartBadge) {
                        cartBadge.textContent = data.cartCount;
                    }
                } else {
                    showBanner("Errore nell'aggiunta al carrello!");
                }
            });
        });
    }

    // Product card navigation
    const productCards = document.querySelectorAll('.product-card');
    if (productCards.length > 0) {
        productCards.forEach(card => {
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
});