<?php
require_once 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        if (!empty($_POST['password'])) {
            $newPassword = $_POST['password'];
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        } elseif (!empty($_POST['existing_password'])) {
            $hashedPassword = $_POST['existing_password'];
        }

        $sql = "UPDATE users SET 
        username = :username,
        email = :email,
        password = :password,
        role = :role
        WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            header("Location: panel.php");
            exit;        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
} else {
    echo "Ошибка";
}
?>
