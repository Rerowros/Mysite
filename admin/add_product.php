<?php
require_once('../admin/bd.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $product_name = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $product_price = filter_input(INPUT_POST, 'product_price', FILTER_VALIDATE_FLOAT);
        $product_image_link = filter_input(INPUT_POST, 'product_image_link', FILTER_VALIDATE_URL);
        $product_code = filter_input(INPUT_POST, 'product_code', FILTER_SANITIZE_SPECIAL_CHARS);


        $sql = "INSERT INTO product (product_name, product_price, product_image_link, product_code)
                VALUES (:product_name, :product_price, :product_image_link, :product_code)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':product_price', $product_price);
        $stmt->bindParam(':product_image_link', $product_image_link, PDO::PARAM_INT);
        $stmt->bindParam(':product_code', $product_code);
        $stmt->execute();


        header("Location: panel.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally // выполняется независимо от ошибок.
    {
        $conn = null;
    }
}

