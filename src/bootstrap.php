<?php
    session_start();
    require_once("../db/database.php");

    //parametri per connettersi al db
    $myConfig = [
        'servername' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'sassishop',
        'charset' => 'utf8mb4'
    ];

    //connessione al db
    $dbh = new DatabaseHelper($myConfig);
?>