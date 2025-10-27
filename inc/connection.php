<?php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$db = "upegaming";

try {
    $conn = new PDO("mysql:host=" . $servername . ";dbname=" . $db . ";charset=utf8", $username, $password);
    // PDO error mode exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>