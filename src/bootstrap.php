<?php
    session_start();
    require_once("../db/database.php");
    $myConfig = [
        'servername' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'sassishop',
        'charset' => 'utf8mb4'
    ];

    $dbh = new DatabaseHelper($myConfig);
?>