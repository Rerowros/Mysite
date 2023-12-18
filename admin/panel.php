<?php
// ПРОВЕРКА НА АДМИНИСТАТОРА
// ПРОВЕРКА НА АДМИНИСТАТОРА
// ПРОВЕРКА НА АДМИНИСТАТОРА
global $conn;
session_start();
require_once('bd.php');
if (isset($_SESSION["id"]))
{
    $specificUserId = $_SESSION["id"];

    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ? AND role = 'admin'");
    $stmt->bind_param('i', $specificUserId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        echo "ADM: Пользователь с ID $specificUserId Администратор.";
    }
    else
    {
        $message_adm = 'ADM: Вы не являетесь администратором.';
        header("Location: ../index.php?message_adm=". urlencode($message_adm));
        exit();
    }
} else
    {
        $message_id = 'ADM: Id в текущей сессии не найден';
        header("Location: ../index.php?message_id=". urlencode($message_id));
        exit();    }
?>

<?php
$sql = "SELECT id, product_name, product_price, product_code, product_image_link FROM product";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    ?>
    <button onclick="toggleTable('productTable')" class="btn btn-primary">Таблица товаров</button>
    <button onclick="toggleTable('UsersTable')" class="btn btn-primary">Таблица пользователей</button>
    <table id="productTable" class="table table-striped table-bordered ">
  <thead>
    <tr>
      <th>ID</th>
      <th>Название товара</th>
      <th>Цена</th>
      <th>Фото</th>
      <th>Код товара</th>
      <th>Действия</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()) { ?>
      <tr>
          <form method="post" action="update_product.php">
              <input type="hidden" name="pid" value="<?php echo $row['id']; ?>"> <!-- костыль чтобы id тоже уходил в post -->
              <td><?php echo $row["id"]; ?></td>
              <td><input type="text" class="form-control" name="product_name" value="<?php echo $row["product_name"]; ?>"></td>
              <td><input type="text" class="form-control" name="product_price" value="<?php echo $row["product_price"]; ?>"></td>
              <td><input type="text" class="form-control" name="product_image_link" value="<?php echo $row["product_image_link"]; ?>"></td>
              <td><input type="text" class="form-control" name="product_code" value="<?php echo $row["product_code"]; ?>"></td>
              <td><button type="submit" name="save" value="<?php echo $row['id']; ?>" class="btn btn-primary">Save</button></td>
              <?php
              $imageUrl = $row["product_image_link"];
              echo '<td><img src="' . $imageUrl . '" alt="Image" width="300" height="200" /></td>';  ?>
          </form>
          <form method="post" action="delete_product.php">
              <td><button type="submit" name="pid" value="<?php echo $row['id']; ?>" class="btn btn-danger">Delete</button></td>
          </form>
      </tr>

    <?php


    }


// ДОБАВЛЕНИЕ НОВОГО ТОВАРА
// ДОБАВЛЕНИЕ НОВОГО ТОВАРА

    ?>
    <form method="post" action="add_product.php">
        <td>new</td>
        <td><input type="text" class="form-control" name="product_name" placeholder="Название товара"></td>
        <td><input type="text" class="form-control" name="product_price" placeholder="Цена"></td>
        <td><input type="text" class="form-control" name="product_image_link" placeholder="Фото URL"></td>
        <td><input type="text" class="form-control" name="product_code" placeholder="Код продукта"></td>
        <td><button type="submit" class="btn btn-success">Добавить</button></td>
    </form>

    <?php
    echo '</td>';
    echo '</tr>';


?>
</tbody>
</table>
<br/>





<?php
$sql = "SELECT id, username, email, role FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {


    ?>
<table id="UsersTable" class="table table-striped table-bordered ">
    <thead>
    <tr>
        <th>ID</th>
        <th>Имя пользователя</th>
        <th>Почта</th>
        <th>Пароль (хэшируется) </th>
        <th>Роль</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php while($row = $result->fetch_assoc()) { ?>

        <tr>
            <form method="post" action="update_users.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <td><?php echo $row["id"]; ?></td>
                <td><input type="text" class="form-control" name="username" value="<?php echo $row["username"]; ?>"></td>
                <td><input type="text" class="form-control" name="email" value="<?php echo $row["email"]; ?>"></td>
                <td><input type="password" class="form-control" name="password" placeholder="Новый пароль"></td>
                <td>
                    <input type="radio" id="adminRole" name="role" value="admin" <?php echo ($row["role"] === "admin") ? 'checked' : ''; ?>>
                    <label for="adminRole">Admin</label>

                    <input type="radio" id="userRole" name="role" value="user" <?php echo ($row["role"] === "user") ? 'checked' : ''; ?>>
                    <label for="userRole">User</label>
                </td>
                <td><button type="submit" name="save" value="<?php echo $row['id']; ?>" class="btn btn-success">Save</button></td>
            </form>
            <td><form method="post" action="delete_users.php"><button type="submit" name="pid" value="<?php echo $row['id']; ?>" class="btn btn-danger">Delete</button></form></td>
        </tr>







        <?php


    }

    ?>

    <form method="post" action="add_user.php">
        <td>new</td>
        <td><input type="text" class="form-control" name="username" placeholder="Имя"></td>
        <td><input type="text" class="form-control" name="email" placeholder="Почта"></td>
        <td><input type="text" class="form-control" name="password" placeholder="Пароль"></td>
        <td>
            <input type="radio" id="adminRole" name="role" value="admin">
            <label for="adminRole">Admin</label>
            <input type="radio" id="userRole" name="role" value="user" checked>
            <label for="userRole">User</label></td>
        <td><button type="submit" class="btn btn-success">Добавить</button></td>
    </form>

    <?php
    echo '</td>';
    echo '</tr>';
    }?>
    </tbody>
</table>
<?php
}  else {
    echo 'Пользователь не найден';
}
?>

<!doctype html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Document</title>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

<script>
    function toggleTable(tableId) {
        const table = document.getElementById(tableId);
        if (table.style.display === 'none' || table.style.display === '') {
            table.style.display = 'table';
        } else {
            table.style.display = 'none';
        }
    }

</script>

<script>
    function toggleTable(tableId) {
        const table = document.getElementById(tableId);
        if (table.style.display === 'none' || table.style.display === '') {
            table.style.display = 'table';
        } else {
            table.style.display = 'none';
        }
    }
</body>
</html>
