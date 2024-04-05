<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Sahil Kumar">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Товары</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css'/>
</head>

<body>
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
                    <a class="nav-link active" aria-current="page" href="/index.php">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../cart/products.php">Все товары</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../cart/cart.php">Корзина</a>
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
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Поиск" aria-label="Поиск">
                <button class="btn btn-outline-success" type="submit">Поиск</button>
            </form>
        </div>
    </div>
</nav>

<!-- Displaying Products Start -->
<div class="container">
    <div id="message"></div>
    <div class="row mt-2 pb-3">
        <?php
        require_once('../admin/bd.php');
        $stmt = $conn->prepare('SELECT * FROM product');
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()):
            $productImage = $row['product_image_link'];
            $productName = $row['product_name'];
            $productPrice = $row['product_price'];

            echo '<div class="col-md-4 mb-4">
    <div class="card">
        <img src="' . $productImage . '" class="card-img-top" alt="Product Image">
        <div class="card-body">
            <h4 class="card-title">' . $productName . '</h4>
            <p class="card-text">' . $productPrice . '<i class="fas fa-ruble-sign"></i></p>
        </div>
        <div class="card-footer p-1">
            <form action="" class="form-submit">
                <div class="row p-2">
                    <div class="col-md-6 py-1 pl-4">
                        <b>Количество : </b>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control pqty" value="' . $row['product_qty'] . '">
                    </div>
                </div>
                <input type="hidden" class="pid" value="' . $row['id'] . '">
                <input type="hidden" class="pname" value="' . $row['product_name'] . '">
                <input type="hidden" class="pprice" value="' . $row['product_price'] . '">
                <input type="hidden" class="pimage" value="' . $row['product_image_link'] . '">
                <input type="hidden" class="pcode" value="' . $row['product_code'] . '">
                <button class="btn btn-info btn-block addItemBtn"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Добавить в корзину</button>
            </form>
        </div>
    </div>
</div>'; ?>
        <?php endwhile; ?>
    </div>
</div>
<!-- Displaying Products End -->

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function () {

        // Send product details in the server
        $(".addItemBtn").click(function (e) {
            e.preventDefault();
            const $form = $(this).closest(".form-submit");
            const pid = $form.find(".pid").val();
            const pname = $form.find(".pname").val();
            const pprice = $form.find(".pprice").val();
            const pimage = $form.find(".pimage").val();
            const pcode = $form.find(".pcode").val();

            const pqty = $form.find(".pqty").val();

            $.ajax({
                url: 'action.php',
                method: 'post',
                data: {
                    pid: pid,
                    pname: pname,
                    pprice: pprice,
                    pqty: pqty,
                    pimage: pimage,
                    pcode: pcode
                },
                success: function (response) {
                    $("#message").html(response);
                    window.scrollTo(0, 0);
                    load_cart_item_number();
                }
            });
        });

        // Load total no.of items added in the cart and display in the navbar
        load_cart_item_number();

        function load_cart_item_number() {
            $.ajax({
                url: 'action.php',
                method: 'get',
                data: {
                    cartItem: "cart_item"
                },
                success: function (response) {
                    $("#cart-item").html(response);
                }
            });
        }
    });
</script>
</body>

</html>