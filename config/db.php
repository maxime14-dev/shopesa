<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "shopesa";

// Create connection using mysqli
$conn = mysqli_connect("localhost", $username, $password, $dbname, 3307);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
