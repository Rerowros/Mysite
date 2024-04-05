<?php
session_start();
require_once('../admin/bd.php');

function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['pid'])) {
    $pid = sanitizeInput($_POST['pid']);
    $pname = sanitizeInput($_POST['pname']);
    $pprice = sanitizeInput($_POST['pprice']);
    $pimage = sanitizeInput($_POST['pimage']);
    $pcode = sanitizeInput($_POST['pcode']);
    $pqty = sanitizeInput($_POST['pqty']);
    $total_price = $pprice * $pqty;

    $stmt = $conn->prepare('SELECT product_code FROM cart WHERE product_code=?');
    $stmt->bind_param('s', $pcode);
    $stmt->execute();
    $res = $stmt->get_result();
    $r = $res->fetch_assoc();
    $code = $r['product_code'] ?? '';

    if (!$code) {
        $query = $conn->prepare('INSERT INTO cart (product_name,product_price,product_image_link,quantity,total_price,product_code) VALUES (?,?,?,?,?,?)');
        $query->bind_param('ssssss', $pname, $pprice, $pimage, $pqty, $total_price, $pcode);
        $query->execute();

        echo '<div class="alert alert-success alert-dismissible mt-2">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Товар добавлен в корзину</strong>
						</div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible mt-2">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Товар уже добавлен в корзину</strong>
						</div>';
    }
}
// количество товаров, доступных в таблице корзины
if (isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item') {
    $stmt = $conn->prepare('SELECT * FROM cart');
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;

    echo $rows;
}

// Удаление отдельных товаров из корзины
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];

    $stmt = $conn->prepare('DELETE FROM cart WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'Товары удалены из вашей корзины!';
    header('location:cart.php');
}

// уделание из корзины всех товаров сразу
if (isset($_GET['clear'])) {
    $stmt = $conn->prepare('DELETE FROM cart');
    $stmt->execute();
    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'Товары удалены из вашей корзины!    ';
    header('location:cart.php');
}

//общую цена товаров в таблице корзины
if (isset($_POST['quantity'])) {
    $quantity = $_POST['quantity'];
    $pid = $_POST['pid'];
    $pprice = $_POST['pprice'];

    $tprice = $quantity * $pprice;

    $stmt = $conn->prepare('UPDATE cart SET quantity=?, total_price=? WHERE id=?');
    $stmt->bind_param('isi', $quantity, $tprice, $pid);
    $stmt->execute();
}

//сохранение информации о покупателе в таблице заказов
if (isset($_POST['action']) && isset($_POST['action']) == 'order') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $products = $_POST['products'];
    $total_price = $_POST['total_price'];
    $address = $_POST['address'];
    $paym = $_POST['paym'];

    $data = '';

    $stmt = $conn->prepare('INSERT INTO orders (name,email,phone,address,paym,products )VALUES(?,?,?,?,?,?)');
    $stmt->bind_param('sssssss', $name, $email, $phone, $address, $paym, $products, $total_price);
    $stmt->execute();
    $stmt2 = $conn->prepare('DELETE FROM cart');
    $stmt2->execute();
    $data .= '<div class="text-center">
								<h2 class="text-success">Заказ оформлен!</h2>
								<h4 class="text-light rounded p-2">Заказано: ' . $products . '</h4>
								<h4>' . $name . '</h4>
								<h4>' . $email . '</h4>
								<h4>' . $phone . '</h4>
								<h4>' . number_format($total_price, 2) . '</h4>
								<h4>' . $paym . '</h4>
						  </div>';
    echo $data;
}
