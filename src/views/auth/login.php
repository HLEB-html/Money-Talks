<?php 
session_start();
require_once __DIR__ . '/../config/db.php'; // поправь путь к файлу подключения

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Введите email и пароль.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    header("Location:/"); // <- изменено здесь
                    exit;
                } else {
                    $error = "Неверный пароль.";
                }
            } else {
                $error = "Пользователь с таким email не найден.";
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
  <title>Вход</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
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
      box-sizing: border-box;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    input[type="email"],
    input[type="password"] {
      background-color: #0f172a;
      border: 1px solid #334155;
      border-radius: 8px;
      padding: 0.75rem;
      width: 100%;
      color: #fff;
      margin-bottom: 1rem;
      font-size: 1rem;
      box-sizing: border-box;
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

    .error {
      text-align: center;
      margin-bottom: 1rem;
      color: #f87171;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Вход</h2>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <button type="submit">Войти</button>
    </form>
  </div>
</body>
</html>
