<?php  
require_once __DIR__ . '/../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

// Класс пользователя
class User {
    private PDO $db;

    public function __construct(PDO $conn) {
        $this->db = $conn;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, email, username, full_name FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}

// Класс транзакций
class Transaction {
    private PDO $db;

    public function __construct(PDO $conn) {
        $this->db = $conn;
    }

    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT id, created_at, category, type, amount, comment 
             FROM transactions 
             WHERE user_id = :user_id 
             ORDER BY created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById(int $id, int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }

    /**
     * Получить сумму по категориям за текущий месяц по типу (income/expense)
     * Возвращает массив ['category' => сумма]
     */
    public function getMonthlyStatsByType(int $userId, string $type): array {
        $stmt = $this->db->prepare(
            "SELECT category, SUM(amount) as total
             FROM transactions
             WHERE user_id = :user_id
               AND type = :type
               AND YEAR(created_at) = YEAR(CURRENT_DATE())
               AND MONTH(created_at) = MONTH(CURRENT_DATE())
             GROUP BY category
             ORDER BY total DESC"
        );
        $stmt->execute(['user_id' => $userId, 'type' => $type]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // category => total
    }
}

$userModel = new User($conn);
$user = $userModel->getById($_SESSION['user_id']);

if (!$user) {
    echo "Пользователь не найден.";
    exit;
}

$transactionModel = new Transaction($conn);

// Обработка удаления транзакции (если пришёл POST с delete_id)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    $transactionModel->deleteById($deleteId, $_SESSION['user_id']);
    // После удаления — редирект, чтобы избежать повторной отправки формы
    header("Location: /profile");
    exit;
}

// Получаем все транзакции пользователя
$transactions = $transactionModel->getByUserId($_SESSION['user_id']);

// Получаем статистику по доходам и расходам за текущий месяц
$monthlyIncomeStats = $transactionModel->getMonthlyStatsByType($_SESSION['user_id'], 'income');
$monthlyExpenseStats = $transactionModel->getMonthlyStatsByType($_SESSION['user_id'], 'expense');

// Подключаем header и sidebar до вывода
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../sidebar.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Профиль</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../css/profile.css">
  <style>

  </style>
</head>
<body>

<main>
  <div class="section">
    <h1>Профиль пользователя</h1>
    <div class="info"><span class="label">ID:</span> <?= htmlspecialchars($user['id']) ?></div>
    <div class="info"><span class="label">Email:</span> <?= htmlspecialchars($user['email']) ?></div>
    <div class="info"><span class="label">Имя пользователя:</span> <?= htmlspecialchars($user['username']) ?></div>
    <div class="info"><span class="label">ФИО:</span> <?= htmlspecialchars($user['full_name']) ?></div>
    <a href="/logout" class="logout">Выйти из аккаунта</a>
    
  </div>

  <div class="section">
    <h2>Ваши транзакции</h2>
    <?php if (count($transactions) === 0): ?>
      <p>Транзакций пока нет.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Дата</th>
            <th>Категория</th>
            <th>Тип</th>
            <th>Сумма</th>
            <th>Комментарий</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $trans): ?>
            <tr>
              <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($trans['created_at']))) ?></td>
              <td><?= htmlspecialchars($trans['category']) ?></td>
              <td><?= htmlspecialchars($trans['type'] === 'income' ? 'Доход' : 'Расход') ?></td>
              <td>
                <?php 
                  $sign = ($trans['type'] === 'income') ? '+' : '-';
                  echo $sign . number_format((float)$trans['amount'], 2, ',', ' ') . ' ₽';
                ?>
              </td>
             <td><?= htmlspecialchars($trans['comment'] ?? '') ?></td>

              <td class="actions">
                <a href="/edit?id=<?= (int)$trans['id'] ?>">Редактировать</a>
                <form method="post" onsubmit="return confirm('Удалить эту транзакцию?');" style="display:inline;">
                  <input type="hidden" name="delete_id" value="<?= (int)$trans['id'] ?>">
                  <button type="submit">Удалить</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <div class="section">
    <h2>Статистика за текущий месяц</h2>

    <h3>Доходы по категориям</h3>
    <?php if (empty($monthlyIncomeStats)): ?>
      <p>Доходов за текущий месяц пока нет.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Категория</th>
            <th>Сумма, ₽</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($monthlyIncomeStats as $category => $sum): ?>
            <tr>
              <td><?= htmlspecialchars($category) ?></td>
              <td><?= number_format($sum, 2, ',', ' ') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <h3>Расходы по категориям</h3>
    <?php if (empty($monthlyExpenseStats)): ?>
      <p>Расходов за текущий месяц пока нет.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Категория</th>
            <th>Сумма, ₽</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($monthlyExpenseStats as $category => $sum): ?>
            <tr>
              <td><?= htmlspecialchars($category) ?></td>
              <td><?= number_format($sum, 2, ',', ' ') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</main>

</body>
</html>
