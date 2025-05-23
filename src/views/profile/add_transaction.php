<?php  
require_once __DIR__ . '/../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

// Категории для выпадающего списка
$categories = ['Еда', 'Транспорт', 'Развлечения', 'Зарплата', 'Прочее'];

// Функция для валидации POST данных
function validateTransactionData(array $data, array $categories): array {
    $errors = [];

    if (!isset($data['type']) || !in_array($data['type'], ['income', 'expense'])) {
        $errors[] = "Неверный тип транзакции.";
    }

    if (!isset($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
        $errors[] = "Сумма должна быть положительным числом.";
    }

    if (!isset($data['category']) || !in_array($data['category'], $categories)) {
        $errors[] = "Выберите корректную категорию.";
    }

    if (isset($data['comment']) && strlen($data['comment']) > 255) {
        $errors[] = "Комментарий слишком длинный (максимум 255 символов).";
    }

    return $errors;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $category = $_POST['category'] ?? '';
    $comment = $_POST['comment'] ?? '';

    $formData = [
        'type' => $type,
        'amount' => $amount,
        'category' => $category,
        'comment' => $comment,
    ];

    $errors = validateTransactionData($formData, $categories);

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, category, type, amount, comment, created_at) VALUES (:user_id, :category, :type, :amount, :comment, NOW())");
        $result = $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'category' => $category,
            'type' => $type,
            'amount' => $amount,
            'comment' => $comment ?: null,
        ]);

        if ($result) {
            $success = true;
            $formData = ['type' => '', 'amount' => '', 'category' => '', 'comment' => ''];
        } else {
            $errors[] = "Ошибка при добавлении транзакции в базу данных.";
        }
    }
} else {
    $formData = ['type' => '', 'amount' => '', 'category' => '', 'comment' => ''];
}

// Подключаем header и sidebar (если они выводят HTML шапку и боковую панель)
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../sidebar.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Добавить транзакцию</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
 <link rel="stylesheet" href="../css/add.css">
</head>
<body>
<main>
  <div class="section">
    <h1>Добавить новую транзакцию</h1>

    <?php if ($success): ?>
      <div class="success">Транзакция успешно добавлена!</div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="error">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?=htmlspecialchars($error)?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form id="transactionForm" method="post" novalidate>
  <label for="type">Тип транзакции</label>
  <select id="type" name="type" required>
    <option value="" disabled <?= empty($formData['type'] ?? '') ? 'selected' : '' ?>>Выберите тип</option>
    <option value="income" <?= ($formData['type'] ?? '') === 'income' ? 'selected' : '' ?>>Доход</option>
    <option value="expense" <?= ($formData['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Расход</option>
  </select>

  <label for="amount">Сумма</label>
  <input 
    type="number" 
    id="amount" 
    name="amount" 
    step="0.01" 
    min="0.01" 
    value="<?= htmlspecialchars($formData['amount'] ?? '') ?>" 
    required
    placeholder="Введите сумму"
  />

  <label for="category">Категория</label>
  <select id="category" name="category" required>
    <option value="" disabled <?= empty($formData['category'] ?? '') ? 'selected' : '' ?>>Выберите категорию</option>
    <?php foreach ($categories as $cat): ?>
      <option value="<?= htmlspecialchars($cat) ?>" <?= ($formData['category'] ?? '') === $cat ? 'selected' : '' ?>>
        <?= htmlspecialchars($cat) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label for="comment">Комментарий (необязательно)</label>
  <textarea 
    id="comment" 
    name="comment" 
    rows="3" 
    maxlength="255" 
    placeholder="Введите комментарий (максимум 255 символов)"
  ><?= htmlspecialchars($formData['comment'] ?? '') ?></textarea>

  <button type="submit">Добавить</button>
</form>
