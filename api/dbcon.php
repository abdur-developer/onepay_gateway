<?php
    $servername = "localhost";
    // $db_username = "superear_gateway";
    // $db_password = '123Asd!@#';
    // $dbname = "superear_gateway";
    $db_username = "root";
    $db_password = '';
    $dbname = "gateway";
    $domain = "http://localhost:8080/getway";

    $conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>