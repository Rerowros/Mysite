<?php
require_once('../admin/bd.php');


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Проверка метода запроса (должен быть POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Получение данных из формы  ////  pid это типо product_id, но просто id. в panel использую
        if (isset($_POST['pid'])) {
            // Присваиваем значения из массива $_POST переменным
            $id = $_POST['pid'];
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $product_image_link = $_POST['product_image_link'];
            $product_code = $_POST['product_code'];

            // SQL-запрос для обновления записи в таблице "product"
            $sql = "UPDATE product SET 
                    product_name = :product_name,
                    product_price = :product_price,
                    product_image_link = :product_image_link,
                    product_code = :product_code
                    where id = :id";

            // Подготовка запроса к выполнению
            $stmt = $conn->prepare($sql);

            // бинд параметров к переменным
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':product_price', $product_price);
            $stmt->bindParam(':product_image_link', $product_image_link);
            $stmt->bindParam(':product_code', $product_code);

            // Выполнение запроса
            $stmt->execute();


            header("Location: panel.php");
            exit;
        } else {
            echo "id не найден.";
        }
    } else {
        echo "Нет post запроса";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;

