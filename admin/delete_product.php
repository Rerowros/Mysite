<?php
require_once('../admin/db.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['pid']) && is_numeric($_POST['pid'])) { // нумерик типо циферка ли это или нет, прост нагуглил это. вроде и без этого ошибок не должно быть, я же в другом файле и так даю циферку
        $itemId = $_POST['pid'];

        $sql = "DELETE FROM product WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: panel.php");
            exit;
        } else {
            echo "Ошибка удаления ID: $itemId.";
        }
    } else {
        echo "Возможно не указан ID.";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
finally // выполняется независимо от ошибок.
{
    $conn = null;
}

?>
