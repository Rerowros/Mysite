<?php
session_start();
require_once('admin/db.php');

if (!isset($_SESSION['authorized']))
{
    $email = $password = "";
    $email_err = $password_err = "";

    //  является ли метод запроса POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_SPECIAL_CHARS);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Пожалуйста, введите почту.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Пусто ли поле пароля.
    if (empty(trim($_POST["password"]))) {
        $password_err = "Пожалуйста, введите пароль.";
    } else {
        $password = trim($_POST["password"]);
    }
}
    // Проверка ошибки ввода перед запросом к базе данных
        if (empty($email_err) && empty($password_err)) {

            $sql = "SELECT id, email, password FROM users WHERE email = ? LIMIT 1";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                // привязываем переменные к подготовленному оператору в качестве параметров
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                $param_email = $email;


                // Попытка выполнить подготовленный запрос
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    //  существует ли электронная почта, если да, то проверьте пароль
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);

                        if (mysqli_stmt_fetch($stmt)) {

                            if (password_verify($password, $hashed_password)) {

                                // помещаем данные в сессию (cookie)
                                $_SESSION['authorized'] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;

                                header("location: index.php");
                            } else {
                                $password_err = "Неверный пароль.";
                            }
                        }
                    } else {
                        $email_err = "Аккаунт с такой почтой не найден.";
                    }
                } else {
                    echo "Что-то пошло не так";
                }

                mysqli_stmt_close($stmt);
            }
        }

        mysqli_close($conn);
    }



else
{


    $message = 'Вы уже авторизованы!';
    header("Location: index.php?message=". urlencode($message));
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-5">
            <h2 class="text-center mb-4">Вход</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="email">Почта:</label>
                    <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" name="password">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-success" type="submit">Войти</button>
                        <a class="btn btn-primary btn-block" href="Register.php" role="button">Ещё не зарегестрированы?</a>
                    </div>
            </form>
        </div>
    </div>
</div>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
