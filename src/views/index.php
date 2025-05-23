<?php
session_start(); // Всегда в самом начале, до любого вывода

require_once __DIR__ . '/config/db.php';

if (isset($_SESSION['user_id'])) {
    // Авторизованный пользователь — подключаем авторизованный дашборд
    require_once 'views/header.php';
    require_once 'views/sidebar.php';
    require_once 'views/profile/dashboard_login.php';
    // require_once 'views/footer.php';
} else {
    // Неавторизованный пользователь — подключаем публичный дашборд
    require_once 'views/header.php';
    require_once 'views/sidebar.php';
    require_once 'views/dashboard.php';
    // require_once 'views/footer.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" type="image/png" href="../MT.png">


</head>
<body>
    
</body>
</html>