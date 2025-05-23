<?php 
require_once __DIR__ . '/../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

$categories = ['Еда', 'Транспорт', 'Развлечения', 'Зарплата', 'Прочее'];

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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID транзакции не указан или некорректен.");
}

$transaction_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM transactions WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $transaction_id, 'user_id' => $_SESSION['user_id']]);
$transaction = $stmt->fetch();

if (!$transaction) {
    die("Транзакция не найдена.");
}

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
        $stmt = $conn->prepare("UPDATE transactions SET type = :type, amount = :amount, category = :category, comment = :comment WHERE id = :id AND user_id = :user_id");
        $result = $stmt->execute([
            'type' => $type,
            'amount' => $amount,
            'category' => $category,
            'comment' => $comment ?: null,
            'id' => $transaction_id,
            'user_id' => $_SESSION['user_id'],
        ]);

        if ($result) {
            $success = true;
            $transaction = array_merge($transaction, $formData);
        } else {
            $errors[] = "Ошибка при обновлении транзакции.";
        }
    }
}

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../sidebar.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать транзакцию</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/edit.css">
</head>
<body>

<main>
    <div class="form-container">
        <h1>Редактировать транзакцию</h1>

        <?php if ($success): ?>
            <div class="success">Транзакция успешно обновлена!</div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" novalidate>
            <label for="type">Тип транзакции</label>
            <select id="type" name="type" required>
                <option value="" disabled>Выберите тип</option>
                <option value="income" <?= $transaction['type'] === 'income' ? 'selected' : '' ?>>Доход</option>
                <option value="expense" <?= $transaction['type'] === 'expense' ? 'selected' : '' ?>>Расход</option>
            </select>

            <label for="amount">Сумма</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0.01" value="<?= htmlspecialchars($transaction['amount']) ?>" required>

            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <option value="" disabled>Выберите категорию</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $transaction['category'] === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="comment">Комментарий</label>
            <textarea id="comment" name="comment" maxlength="255"><?= htmlspecialchars($transaction['comment'] ?? '') ?></textarea>

            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
</main>

</body>
</html>
