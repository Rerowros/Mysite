<?php
require_once('../admin/bd.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['pid']) && is_numeric($_POST['pid'])) {
        $itemId = $_POST['pid'];

        $sql = "DELETE FROM product WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: panel.php");
            exit;
        } else {
            $errorMessage = "Ошибка удаления ID: $itemId.";
            include 'error_page.php';
            exit;
        }
    } else {
        $errorMessage = "Возможно не указан ID.";
        include 'error_page.php';
        exit;
    }
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    $errorMessage = "An error occurred. Please try again later.";
    include 'error_page.php';
    exit;
} finally {
    $conn = null;
}