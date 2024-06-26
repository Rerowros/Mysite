<?php
session_start();
require_once('admin/bd.php');

$email = $username = $password = "";
$email_err = $username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($email)) {
        $email_err = "Введите email";
    } else if (isUserExists($conn, 'email', $email)) {
        $email_err = "Email уже зарегистрирован.";
    }

    if (empty($username)) {
        $username_err = "Введите имя пользователя.";
    } else if (isUserExists($conn, 'username', $username)) {
        $username_err = "Имя пользователя уже зарегистрировано.";
    }

    if (empty($password)) {
        $password_err = "Введите пароль";
    }

    if (empty($email_err) && empty($username_err) && empty($password_err)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        registerUser($conn, $email, $username, $hashed_password);
    }
}

function isUserExists($conn, $field, $value)
{
    $sql = "SELECT id FROM users WHERE $field = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $value);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $result = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $result;
}

function registerUser($conn, $email, $username, $hashed_password)
{
    $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hashed_password);
    if (mysqli_stmt_execute($stmt)) {
        header("location: login.php");
    } else {
        echo "Что-то не так.";
    }
    mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User 333 Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-5">
            <h2 class="text-center mb-4">Регистрация</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="email">Почта:</label>
                    <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                           id="email" name="email" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="username">Имя:</label>
                    <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                           id="username" name="username" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password"
                           class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password"
                           name="password">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-success" type="submit">Зарегистрироваться</button>
                    <a class="btn btn-primary btn-block" href="login.php" role="button">Уже зарегистрированы?</a>
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
</body>
</html>
