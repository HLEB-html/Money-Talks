<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/router.php';
get('/', 'views/index.php');
get('/login', 'views/auth/login.php');
post('/login', 'views/auth/login.php');
get('/register', 'views/auth/register.php');
post('/register', 'views/auth/register.php');
post('/add-post', 'views/index.php');
get('/profile', 'views/profile/profile.php');
post('/profile', 'views/profile/profile.php');
get('/logout', 'views/auth/logout.php');
post('/logout', 'views/auth/logout.php');
get('/add', 'views/profile/add_transaction.php');
post('/add', 'views/profile/add_transaction.php');
get('/dash', 'views/dashboard.php');
post('/dash', 'views/dashboard.php');
get('/edit', 'views/profile/edit_transaction.php');
post('/edit', 'views/profile/edit_transaction.php');
get('/ldash', 'views/profile/dashboard_login.php');
post('/ldash', 'views/profile/dashboard_login.php');
// Остальные страницы далжны быть выше 404!!!
any('/404', 'views/404.php');




?>