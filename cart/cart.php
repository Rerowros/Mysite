<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>

<body data-bs-theme="dark">
<nav class="navbar navbar-expand-lg bg-body-tertiary" >
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="https://i.etsystatic.com/26757943/r/il/4d18c5/3281705727/il_794xN.3281705727_nxpk.jpg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            Rerowros
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Переключатель навигации">
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
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div style="display:<?php if (isset($_SESSION['showAlert'])) {
                echo $_SESSION['showAlert'];
            } else {
                echo 'none';
            } unset($_SESSION['showAlert']); ?>" class="alert alert-success alert-dismissible mt-3">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><?php if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                    } unset($_SESSION['showAlert']); ?></strong>
            </div>
            <div class="table-responsive mt-2">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <td colspan="7">
                            <h4 class="text-center text-info m-0">Товары в корзине!</h4>
                        </td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Фото</th>
                        <th>Продукт</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Общая цена</th>
                        <th>
                            <a href="action.php?clear=all" class="badge-danger badge p-1" onclick="return confirm('Вы уверены?');"><i class="fas fa-trash"></i>&nbsp;&nbsp;Очистить корзину</a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    require_once('../admin/db.php');
                    $stmt = $conn->prepare('SELECT * FROM cart');
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $total_price = 0;
                    while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                            <td><img src="<?= $row['product_image_link'] ?>" width="50"></td>
                            <td><?= $row['product_name'] ?></td>
                            <td>
                                <i class="fas fa-ruble-sign"></i>&nbsp;&nbsp;<?= number_format($row['product_price'],2); ?>
                            </td>
                            <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                            <td>
                                <input type="number" class="form-control itemQty" value="<?= $row['quantity'] ?>" style="width:75px;">
                            </td>
                            <td><i class="fas fa-ruble-sign"></i>&nbsp;&nbsp;<?= number_format($row['total_price'],2); ?></td>
                            <td>
                                <a href="action.php?remove=<?= $row['id'] ?>" class="text-danger lead" onclick="return confirm('Вы уверены?');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php $total_price += $row['total_price']; ?>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="3">
                            <a href="products.php" class="btn btn-success"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Продолжить покупки</a>
                        </td>
                        <td colspan="2"><b>Общая цена</b></td>
                        <td><b><i class="fas fa-ruble-sign"></i>&nbsp;&nbsp;<?= number_format($total_price,2); ?></b></td>
                        <td>
                            <a href="checkout.php" class="btn btn-info <?= ($total_price > 1) ? '' : 'disabled'; ?>"><i class="far fa-credit-card"></i>&nbsp;&nbsp;Оформить заказ</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>


<script type="text/javascript">
    $(document).ready(function() {

        // Изменить количество товара, не работает пон
        $(".itemQty").on('change', function() {
            var $el = $(this).closest('tr');

            var pid = $el.find(".pid").val();
            var pprice = $el.find(".pprice").val();
            var quantity = $el.find(".itemQty").val();
            location.reload();
            $.ajax({
                url: 'action.php',
                method: 'post',
                cache: false,
                data: {
                    quantity: quantity,
                    pid: pid,
                    pprice: pprice
                },
                success: function(response) {
                    console.log(response);
                }
            });
        });,

    });
</script>
</body>

</html>