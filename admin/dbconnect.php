<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "golden_city";
//creating data source name
$dsn = "mysql:host=$hostname;dbname=$dbname";

try {
    $conn = new PDO($dsn,$username,$password); //$dsn, $user, $password
    $conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $conn -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    // echo "connection got";
} catch (PDOException $e) {
    echo $e->getMessage();
}
