<?php
require_once 'bd.php';
// Log error with a timestamp to a file
require_once 'error_handling.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pid'])) {
    $user_id = $_POST['pid'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // Prepare a DELETE statement
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);

        // Execute the delete query
        $stmt->execute();
        header("Location: panel.php");
        exit;
    } catch (Exception $e) {
        logError($e->getMessage());
    }

    // Close connection
    $conn = null;
} else {
    echo "Ну чёт серьёзней чем в catch";
}