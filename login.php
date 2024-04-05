<?php
session_start();
require_once('admin/bd.php');

$email = $password = $email_err = $password_err = "";
function sanitizeInput($input, $type)
{
    return filter_var(trim($input), $type);
}

function loginUser($conn, $email, $password)
{
    $sql = "SELECT id, email, password FROM users WHERE email = ? LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    if ($hashed_password && password_verify($password, $hashed_password)) {
                        $_SESSION['authorized'] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["email"] = $email;
                        header("location: index.php");
                    } else {
                        return "Неверный пароль.";
                    }
                }
            } else {
                return "Аккаунт с такой почтой не найден.";
            }
        } else {
            return "Что-то пошло не так";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}

if (!isset($_SESSION['authorized']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = sanitizeInput($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);
    if (empty($email)) {
        $email_err = "Пожалуйста, введите почту.";
    } elseif (empty($password)) {
        $password_err = "Пожалуйста, введите пароль.";
    } else {
        $loginError = loginUser($conn, $email, $password);
        if ($loginError === "Неверный пароль.") {
            $password_err = $loginError;
        } elseif ($loginError === "Аккаунт с такой почтой не найден.") {
            $email_err = $loginError;
        } else {
            // Handle other errors
        }
    }
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="loginForm">
                <div class="form-group">
                    <label for="email">Почта:</label>
                    <input type="email"
                           class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?> needs-validation"
                           id="email" name="email" value="<?php echo $email; ?>" required>
                    <div class="invalid-feedback"><?php echo $email_err; ?></div>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password"
                           class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password"
                           name="password">
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


<script>
    document.getElementById('loginForm').addEventListener('submit', function () {
        this.classList.add('was-validated');
    });
</script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
