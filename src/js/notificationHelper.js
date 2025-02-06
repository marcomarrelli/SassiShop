document.querySelectorAll('.notification-row')?.forEach(card => {
    card.addEventListener('click', function(e) {
        if(!e.target.closest('button')) {
            const notificationId = this.dataset.notificationId;
            console.log(notificationId);
            if (notificationId) window.location.href = `?page=notificationPage&notificationId=${notificationId}`;  
        }
    });
});