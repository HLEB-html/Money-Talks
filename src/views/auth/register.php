<?php
require_once __DIR__ . '/../config/db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (!$email || !$username || !$full_name || !$password || !$password_confirm) {
        $error = "Все поля обязательны для заполнения.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Неверный формат email.";
    } elseif ($password !== $password_confirm) {
        $error = "Пароли не совпадают.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $error = "Пользователь с таким email уже зарегистрирован.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insert = $conn->prepare("INSERT INTO users (email, username, full_name, password) 
                                          VALUES (:email, :username, :full_name, :password)");
                $insert->execute([
                    'email' => $email,
                    'username' => $username,
                    'full_name' => $full_name,
                    'password' => $hashedPassword
                ]);
                $success = "Регистрация прошла успешно!";
            }
        } catch (PDOException $e) {
            $error = "Ошибка базы данных: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Регистрация</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php if ($success): ?>
    <meta http-equiv="refresh" content="3;url=/login">
  <?php endif; ?>
  <style>
    body {
      background-color: #0f172a;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .card {
      background-color: #1e293b;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
      padding: 2rem;
      width: 100%;
      max-width: 420px;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    input[type="email"],
    input[type="text"],
    input[type="password"] {
      background-color: #0f172a;
      border: 1px solid #334155;
      border-radius: 8px;
      padding: 0.75rem;
      width: 100%;
      color: #fff;
      margin-bottom: 1rem;
      font-size: 1rem;
    }

    button {
      width: 100%;
      padding: 0.75rem;
      background-color: #3b82f6;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #2563eb;
    }

    .error, .success {
      text-align: center;
      margin-bottom: 1rem;
    }

    .error {
      color: #f87171;
    }

    .success {
      color: #4ade80;
    }

    .dots {
      display: inline-block;
      width: 1.5em;
      text-align: left;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Регистрация</h2>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="success">
        <?= htmlspecialchars($success) ?><br>
        Переход на страницу входа <span class="dots">.</span>
      </div>
      <script>
        const dots = document.querySelector('.dots');
        let dotCount = 1;
        setInterval(() => {
          dotCount = (dotCount % 3) + 1;
          dots.textContent = '.'.repeat(dotCount);
        }, 500);
      </script>
    <?php endif; ?>

    <form method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="username" placeholder="Имя пользователя" required>
      <input type="text" name="full_name" placeholder="ФИО" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <input type="password" name="password_confirm" placeholder="Подтвердите пароль" required>
      <button type="submit">Зарегистрироваться</button>
    </form>
  </div>
</body>
</html>
