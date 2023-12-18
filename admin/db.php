<?php
global $conn;

$servername = "localhost";
$username = "root";
$password = "1337rerowros";
$dbname = "db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>