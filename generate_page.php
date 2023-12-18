<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <style>
        .product-img {
            width: 650px;
            height: 500px;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

</body>
</html>
<?php
require_once 'admin/db.php';
if(isset($_GET['id'])) {
    $productId = $_GET['id'];

    $sql = "SELECT * FROM product WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $productName = $row['product_name'];
        $productImage = $row['product_image_link'];
        $productPrice = $row['product_price'];

        echo "<h1>$productName</h1>";
        echo '<img src="'.$productImage.'" class="card-img-top, product-img" alt="Product Image">';
        echo "<p>Price: $productPrice</p>";
        echo '<form action="" class="form-submit">
                <div class="row p-2">
                    <div class="col-md-6 py-1 pl-4">
                        <b>Количество : </b>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control pqty" value="'. $row['product_qty'].'">
                    </div>
                </div>
                <input type="hidden" class="pid" value="'.$row['id'].'">
                <input type="hidden" class="pname" value="'.$row['product_name'].'">
                <input type="hidden" class="pprice" value="'.$row['product_price'].'">
                <input type="hidden" class="pimage" value="'.$row['product_image_link'].'">
                <input type="hidden" class="pcode" value="'.$row['product_code'].'">
                <button class="btn btn-info btn-block addItemBtn"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Добавить в корзину</button>
            </form>';
    } else {
        echo "Product not found";
    }
} else {
    echo "Product ID not provided";
}

$conn->close();
?>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $(".addItemBtn").click(function(e) {
            e.preventDefault();
            var $form = $(this).closest(".form-submit");
            var pid = $form.find(".pid").val();
            var pname = $form.find(".pname").val();
            var pprice = $form.find(".pprice").val();
            var pimage = $form.find(".pimage").val();
            var pcode = $form.find(".pcode").val();

            var pqty = $form.find(".pqty").val();

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
                success: function(response) {
                    $("#message").html(response);
                    window.scrollTo(0, 0);
                    load_cart_item_number();
                }
            });
        });

    });
</script>
