<?php
$servername = "mysql";
$port = "4444";
$username = "root";
$password = "prestashop";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>