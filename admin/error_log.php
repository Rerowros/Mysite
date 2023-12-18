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

</body>
</html>
<form method="post">
    <button type="submit" name="clear_log">Очистить лог</button>
</form>

<?php

if (file_exists('error.log')) {
    echo nl2br(file_get_contents('error.log'));
} else {
    echo 'No errors logged.';
}

if (isset($_POST['clear_log'])) {
    // Clear the log file
    file_put_contents('error.log', '');

    // Redirect back to the page where the request originated
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} ?>
