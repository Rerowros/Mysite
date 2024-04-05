<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная страница</title>
    <style>
        .card-img-top {
            height: 200px; /* фиксированную высота */
            object-fit: cover; /* Чтобы было все пространство без растяжения */
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- выпадающее меню короче не работает если include использовать-->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="https://i.etsystatic.com/26757943/r/il/4d18c5/3281705727/il_794xN.3281705727_nxpk.jpg" alt="Logo"
                 width="30" height="24" class="d-inline-block align-text-top">
            Rerowros
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Переключатель навигации">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart/products.php">Все товары</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/cart/cart.php">Корзина</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        Другое
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/err404.html">Действие</a></li>
                        <li><a class="dropdown-item" href="/do_logout.php">Выйти</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex" role="search" method="GET" action="search.php">
                <input class="form-control me-2" type="search" name="query" placeholder="Поиск" aria-label="Поиск">
                <button class="btn btn-outline-success" type="submit">Поиск</button>
            </form>

        </div>
    </div>
</nav>


<!-- Начало самого сайта-->
<div class="container mt-4" data-bs-theme="dark">
    <h1 class="text-center">Sneaker shop</h1>
    <p class="text-center">Описание</p>
    <?php
    if (isset($_GET['message_id'])) {
        echo '<div style="text-align: center;"><h2>' . htmlspecialchars($_GET['message_id']) . '</h2></div>';
    }
    ?>
</div>
<section class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <img src="https://olimpvl.ru/upload/medialibrary/ae3/ae300bfae632915faf2fd98571230adc.jpg" class="img-fluid"
                 alt="Placeholder Image">
        </div>
    </div>
</section>

<?php
require_once 'admin/bd.php';
session_start();

function showMessage($messageKey)
{
    if (isset($_GET[$messageKey])) {
        echo '<div style="text-align: center;"><h2>' . htmlspecialchars($_GET[$messageKey]) . '</h2></div>';
    }
}

function fetchProducts($conn, $limit)
{
    $stmt = $conn->prepare('SELECT id, product_name, product_price, product_code, product_image_link FROM product LIMIT ?');
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    return $stmt->get_result();
}

showMessage('message_id');

if (!isset($_SESSION['authorized'])) {
    echo '
    <div class="text-center">
        <a href="login.php" class="btn btn-primary">Авторизация</a>
   
    </div>';
} else {
    showMessage('message');
}

showMessage('message_adm');

$cardCounter = 0; // Define $cardCounter before the loop

$result = fetchProducts($conn, 6);

if ($result->num_rows > 0) {
    echo '<section class="container mt-4">
    <h2 class="text-center">Товары</h2>
    <div class="row">';

    while ($row = $result->fetch_assoc()) {
        $productImage = $row['product_image_link'];
        $productName = $row['product_name'];
        $productPrice = $row['product_price'];

        echo '<div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="' . $productImage . '" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title">' . $productName . '</h5>
                                <p class="card-text">' . $productPrice . '</p>
                                <a href="#" class="btn btn-primary">Подробнее</a>
                            </div>
                        </div>
                    </div>';

        $cardCounter++;
        if ($cardCounter >= 6) {
            break;
        }
    }
    echo '</div></section>';
}
?>


<!-- Сетка товаров -->


<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
