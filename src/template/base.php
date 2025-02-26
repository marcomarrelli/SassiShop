<!DOCTYPE html>
<html lang="it">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Page Title -->
    <title><?php echo $templateParams["title"] ?? "SassiShop"; ?></title>
    
    <!-- Page Icon -->
    <link rel="icon" href="../assets/images/logo.ico" type="image/x-icon">

    <!-- Icone Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font -->
    <link href='https://fonts.googleapis.com/css?family=Istok%20Web' rel='stylesheet'>
    
    <!-- Proprietary CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/styles/style.css">
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

    <footer class="container-fluid py-4 mt-5">
        <div class="container">
        <div class="row text-center">
          <div class="col-12">
            <nav class="nav flex-column">
              <a href="?page=findOutMore" class="nav-link text">Scopri di più</a>
              <hr class="border-dark opacity-25 w-75 mx-auto">
              <a href="?page=aboutUs" class="nav-link text">Chi Siamo</a>
              <hr class="border-dark opacity-25 w-75 mx-auto">
              <a href="?page=sostenibility" class="nav-link text">Sostenibilità</a>
              <hr class="border-dark opacity-25 w-75 mx-auto">
            </nav>
          </div>
        </div>
      </div>
    </footer>


    <!-- Generic Functions -->
    <script src="js/functions.js"></script>
</body>
</html>