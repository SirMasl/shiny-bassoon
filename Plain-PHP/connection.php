<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "mydb";

$conn = null;
try {
    $conn = new PDO("mysql:host=$servername;port=3307;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>