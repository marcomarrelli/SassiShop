<!-- file base a tutti -->

<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $templateParams["title"]; ?></title>
    <link rel="stylesheet" type="text/css" href="../assets/styles/style.css"/>
    <link rel="icon" href="../assets/images/logo.ico" type="image/x-icon">
</head>

<body>
    <nav>
        <ul>
            <li>
                <a class="header-button" href="?page=home">
                    <i class="bi bi-house"> </i>
                </a>
            </li>
            <li>
                <a class="header-button" href="?page=search">
                    <i class="bi bi-search"> </i>
                </a>
            </li>
            <li>
                <a class="header-button" href="?page=cart">
                    <i class="bi bi-cart"> </i>
                </a>
            </li>
            <li>
                <a class="header-button" href="?page=notification">
                    <i class="bi bi-bell"> </i>
                </a>
            </li>
            <li>
                <a class="header-button" href="?page=profile">
                    <i class="bi bi-person-circle"> </i>
                </a>
            </li>
        </ul>
    </nav>

    <header>
        <h1>SASSI SHOP</h1>
        <button id="theme-switcher" onclick="switchTheme()">Change Theme</button>
        <script>
            const root = document.documentElement;

            const savedTheme = localStorage.getItem("theme") || "light-mode";
            root.classList.add(savedTheme);

            function switchTheme() {
                const newTheme = root.classList.contains("light-mode") ? "dark-mode" : "light-mode";

                root.classList.replace(root.classList.contains("light-mode") ? "light-mode" : "dark-mode", newTheme);
                localStorage.setItem("theme", newTheme);
            }
        </script>
    </header>

    <main>
        <?php
            require($templateParams["content"]);
        ?>
    </main>

    <footer>
        <hr/>
        <p>Scopri di più</p>
        <hr/>
        <p>Chi Siamo</p>
        <hr/>
        <p>Sostenibilità</p>
        <hr/>
        <p>Gestisci Cookies</p>
        <hr/>
    </footer>
</body>
</html>