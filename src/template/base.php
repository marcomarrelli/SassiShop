<!-- file base -->

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $templateParams["title"]; ?></title>
    <link rel="stylesheet" type="text/css" href="../assets/styles/style.css"/>
    <link rel="icon" href="../assets/images/logo.ico" type="image/x-icon">
</head>

<body>
    <nav class="header-nav-container">
        <ul class="nav justify-content-center justify-content-between header-nav-body">
            <li class="header-nav-item">
                <a href="?page=home"> <i class="bi bi-house"> </i> </a>
            </li>
            <li class="header-nav-item">
                <a href="?page=search"> <i class="bi bi-search"> </i> </a>
            </li>
            <li class="header-nav-item">
                <?php
                    if(isset($_SESSION["privilege"]) && $_SESSION["privilege"]== 1){ ?>
                        <a href="?page=manageProduct">
                            <i class="bi bi-plus-square"> </i>
                        </a>
                    <?php }else{ ?>
                        <a href="?page=cart" class="position-relative">
                            <i class="bi bi-cart"></i>
                            <span id="cartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-badge">
                                <?php echo isset($_SESSION['cartCount']) ? $_SESSION['cartCount'] : '0'; ?>
                            </span>
                        </a>
                    <?php } ?>
            </li>
            <li class="header-nav-item">
                <a href="?page=notification"> 
                    <i class="bi bi-bell"> </i>
                        <?php if(isUserLoggedIn()){ ?>
                            <span id="notificationBadge" class="position-absolute top-0 start-55 translate-middle badge rounded-pill cart-badge">
                                <?php echo $dbh->getUserNotificationUnread($_SESSION["idUser"])["numNotification"];?>
                            </span>
                        <?php } ?>
                </a>
            </li>
            <li class="header-nav-item">
                <a href="?page=profile"> <i class="bi bi-person-circle"> </i> </a>
            </li>
        </ul>
    </nav>

    <header>
        <div class="header-logo">
            <img class="header-text-logo" src="../assets/images/text_logo.svg" alt="Logo"/>
            <label class="header-welcome-phrase">
                Che sasso stai cercando oggi?
            </label>
        </div>
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
</body>
</html>

<script src="js/functions.js"></script>