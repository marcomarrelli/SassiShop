<?php
    session_start();
    require_once("../db/database.php");
    require_once("utils/functions.php");

    // Connessione al Database
    $dbh = new DatabaseHelper();
?>