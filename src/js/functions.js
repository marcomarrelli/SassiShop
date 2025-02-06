const root = document.documentElement;
const savedTheme = localStorage.getItem("theme") || "light-mode";
root.classList.add(savedTheme);

function switchTheme() {
    const newTheme = root.classList.contains("light-mode") ? "dark-mode" : "light-mode";
    root.classList.replace(root.classList.contains("light-mode") ? "light-mode" : "dark-mode", newTheme);
    localStorage.setItem("theme", newTheme);
}

document.addEventListener('keydown', function(event) {
    if (event.shiftKey && event.keyCode === 84) {
        switchTheme();
        event.preventDefault();
    }
});

function redirectToHome() {
    window.location.href = '?page=home';
}

document.querySelector('img.header-text-logo').addEventListener('click', redirectToHome);