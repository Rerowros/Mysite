<?php
require_once 'bd.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $new_password = $_POST['password']; // насколько

        if ($username !== null && $email !== false && $new_password !== null) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $role = $_POST['role'];

            $sql = "INSERT INTO users (username, email, password, role)
                    VALUES (:username, :email, :password, :role)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                header("Location: panel.php");
                exit();
            } else {
                echo "Ошибка какая-то";
            }
        } else {
            echo "Ошибка в данных!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally // выполняется независимо от ошибок.
    {
        $conn = null;
    }
}
