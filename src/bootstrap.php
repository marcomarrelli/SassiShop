<?php
    session_start();
    require_once("../db/database.php");
    require_once("utils/functions.php");

    // Connessione al Database
    $dbh = new DatabaseHelper();
?>

<!-- Icone Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Font -->
<link href='https://fonts.googleapis.com/css?family=Istok Web' rel='stylesheet'>