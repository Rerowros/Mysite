<?php
require_once('../admin/bd.php');

$total_price = 0;
$allItems = '';
$items = [];

$sql = "SELECT CONCAT(product_name, '(',quantity,')') AS ItemQty, total_price FROM cart";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $total_price += $row['total_price'];
    $items[] = $row['ItemQty'];
}
$allItems = implode(', ', $items);
?>
<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css'/>
</head>

<body>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 px-4 pb-4" id="order">
            <div class="jumbotron p-3 mb-2 text-center">
                <h6 class="lead"><b>Товары: </b><?= $allItems; ?></h6>
                <h5><b>Сумма к оплате: </b><?= number_format($total_price, 2) ?> <i class="fas fa-ruble-sign"></i></h5>
            </div>
            <form action="" method="post" id="placeOrder">
                <input type="hidden" name="products" value="<?= $allItems; ?>">
                <input type="hidden" name="total_price" value="<?= $total_price; ?>">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Имя" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="ПОТОМ ОТПРАВКУ ИЗ БД РЕАЛИЗОВАТЬ"
                           required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" class="form-control" placeholder="Введите ваш телефно" required>
                </div>
                <div class="form-group">
                    <textarea name="address" class="form-control" rows="3" cols="10"
                              placeholder="Адрес доставки, комментарий курьеру"></textarea>
                </div>
                <h6 class="text-center lead">Оплата</h6>
                <div class="form-group">
                    <select name="paym" class="form-control">
                        <option value="" selected disabled>-Выберите метод оплаты-</option>
                        <option value="cod">Наличными после отправки</option>
                        <option value="perevodbank">Переводом</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="ЗАКАЗАТЬ" class="btn btn-danger btn-block">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>


<script type="text/javascript">
    $(document).ready(function () {

        // Sending Form data to the server
        $("#placeOrder").submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: 'action.php',
                method: 'post',
                data: $('form').serialize() + "&action=order",
                success: function (response) {
                    $("#order").html(response);
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