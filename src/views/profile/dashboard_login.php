<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

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
    public function getBalance(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT type, SUM(amount) as total FROM transactions WHERE user_id = :user_id GROUP BY type"
        );
        $stmt->execute(['user_id' => $userId]);
        $res = ['income' => 0, 'expense' => 0];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[$row['type']] = (float)$row['total'];
        }
        return $res;
    }
}

$userModel = new User($conn);
$user = $userModel->getById($_SESSION['user_id']);

if (!$user) {
    echo "Пользователь не найден.";
    exit;
}

$transactionModel = new Transaction($conn);
$transactions = $transactionModel->getByUserId($_SESSION['user_id']);
$balance = $transactionModel->getBalance($_SESSION['user_id']);

// Подготовка данных для диаграммы
$income = $balance['income'] ?? 0;
$expense = $balance['expense'] ?? 0;
$total = $income + $expense;
if ($total == 0) $total = 1; // чтобы не делить на ноль

$incomePercent = round(($income / $total) * 100, 2);
$expensePercent = round(($expense / $total) * 100, 2);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Главная - Панель пользователя</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../css/ldash.css">

</head>
<body>
  <main class="ldash-container">
    <section class="ldash-info-section">
      <h1 class="ldash-title">Привет, <?= htmlspecialchars($user['username']) ?>!</h1>
      <p class="ldash-text">Общий баланс: 
        <span class="ldash-balance">
          <?= number_format(($income) - ($expense), 2, ',', ' ') ?> ₽
        </span>
      </p>
    </section>

    <!-- Карточка с объединенной диаграммой -->
    <div class="ldash-cards">
      <div class="ldash-card" role="region" aria-label="Диаграмма доходов и расходов">
        <h2>Доходы и Расходы</h2>
        <p class="ldash-number">
          Доходы: <?= number_format($income, 2, ',', ' ') ?> ₽ &nbsp;&nbsp;|&nbsp;&nbsp; Расходы: <?= number_format($expense, 2, ',', ' ') ?> ₽
        </p>
        <div class="bar-container" aria-label="Диаграмма доходов и расходов">
          <div class="bar-income" style="width: <?= $incomePercent ?>%;" title="Доходы: <?= number_format($income, 2, ',', ' ') ?> ₽">
            <?php if ($incomePercent > 10): ?>
              <?= round($incomePercent) ?>%
            <?php endif; ?>
          </div>
          <div class="bar-expense" style="width: <?= $expensePercent ?>%;" title="Расходы: <?= number_format($expense, 2, ',', ' ') ?> ₽">
            <?php if ($expensePercent > 10): ?>
              <?= round($expensePercent) ?>%
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <section class="ldash-info-section ldash-transactions-section">
      <div class="ldash-transactions-header">
        <h2 class="ldash-title">Транзакции</h2>
        <select id="ldashFilter" class="ldash-select">
          <option value="all">Все</option>
          <option value="income">Доходы</option>
          <option value="expense">Расходы</option>
        </select>
      </div>

      <table class="ldash-table" id="ldashTransactionsTable">
        <thead>
          <tr>
            <th>Дата</th>
            <th>Категория</th>
            <th>Тип</th>
            <th>Сумма</th>
            <th>Комментарий</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $trans): ?>
            <tr data-type="<?= htmlspecialchars($trans['type']) ?>">
              <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($trans['created_at']))) ?></td>
              <td><?= htmlspecialchars($trans['category']) ?></td>
              <td><?= $trans['type'] === 'income' ? 'Доход' : 'Расход' ?></td>
              <td style="color: <?= $trans['type'] === 'income' ? '#4ade80' : '#f87171' ?>">
                <?= ($trans['type'] === 'income' ? '+' : '-') . number_format((float)$trans['amount'], 2, ',', ' ') . ' ₽' ?>
              </td>
              <td><?= htmlspecialchars($trans['comment'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <a href="/add" class="ldash-button">Добавить транзакцию</a>
    </section>
  </main>

  <script>
    const filterSelect = document.getElementById('ldashFilter');
    const tableRows = document.querySelectorAll('#ldashTransactionsTable tbody tr');

    filterSelect.addEventListener('change', () => {
      const selected = filterSelect.value;

      tableRows.forEach(row => {
        const type = row.getAttribute('data-type');
        if (selected === 'all' || type === selected) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>
