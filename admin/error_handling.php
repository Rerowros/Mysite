<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
function logError($message) {
    $currentPage = $_SERVER['REQUEST_URI'] ?? 'Unknown Page';

    $userID = $_SESSION['id'] ?? 'Unknown User';

    $errorData = [
        'time' => date('Y-m-d H:i:s'),
        'page' => $currentPage,
        'user_id' => $userID,
        'message' => $message,
    ];

    file_put_contents('error.log', print_r($errorData, true) . PHP_EOL, FILE_APPEND);
}

function globalExceptionHandler($exception) {
    logError("Uncaught Exception: " . $exception->getMessage());
    // You can also add redirection or other error handling logic here
}

set_exception_handler('globalExceptionHandler');
