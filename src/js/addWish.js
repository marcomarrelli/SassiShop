document.querySelectorAll("button[name='heart']").forEach(b => {
    b.addEventListener("click",function(){

        const banner = document.querySelector("div[class='top-banner']");
        const bannerContent = banner.querySelector('div');
    
        bannerContent.className = 'banner-success text-center';
        
        let icon = this.querySelector("i");

        if(icon.classList.contains("bi-heart")){
            icon.classList.remove("bi-heart");
            icon.classList.add("bi-heart-fill");
            bannerContent.innerHTML = `<strong> Elemento aggiunto ai preferiti! </strong> `;
        }else{
            icon.classList.remove("bi-heart-fill");
            icon.classList.add("bi-heart");
            bannerContent.innerHTML = `<strong> Elemento rimosso dai preferiti! </strong> `;
        }
        
        // Mostra il banner
        banner.classList.add('show');
        
        // Nascondi il banner dopo 3 secondi
        setTimeout(() => {
            banner.classList.remove('show');
        }, 3000);

    
    });
});
