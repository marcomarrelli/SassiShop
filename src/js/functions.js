function redirectToHome() {
    window.location.href = '?page=home';
}

document.querySelector('img.header-text-logo').addEventListener('click', redirectToHome);