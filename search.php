<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>

</body>
</html>
<?php
require_once 'admin/bd.php';


if (isset($_GET['query'])) {
    $search = $conn->real_escape_string($_GET['query']);

    $stmt = $conn->prepare("SELECT * FROM product WHERE product_name LIKE ?");
    $searchParam = "%$search%";
    $stmt->bind_param('s', $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productId = $row['id'];
            $productImage = $row['product_image_link'];
            $productName = $row['product_name'];
            $productPrice = $row['product_price'];

            echo '<div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="' . htmlspecialchars($productImage) . '" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($productName) . '</h5>
                                <p class="card-text">' . htmlspecialchars($productPrice) . '</p>
                                <form action="generate_page.php" method="GET">
                                    <input type="hidden" name="id" value="' . htmlspecialchars($productId) . '">
                                    <button type="submit">' . htmlspecialchars($productName) . '</button>
                                </form>
                            </div>
                        </div>
                    </div>';
        }
    } else {
        echo 'Нет результатов';
    }
}

$conn->close();
?>
<script>
    function viewProduct(productId) {
        window.location.href = 'generate_page.php?id=' + productId;
        return false;
    }
</script>
