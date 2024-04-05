<?php
global $conn;

$servername = "localhost";
$username = "root";
$password = "Rerowros";
$dbname = "db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failedd: " . $conn->connect_error);
}
