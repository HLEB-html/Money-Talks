<?php  
require_once __DIR__ . '/config/db.php'; 

try {
    // Количество клиентов
    $stmt = $conn->query("SELECT COUNT(*) as total_clients FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalClients = $result ? (int)$result['total_clients'] : 0;

    // Общее количество транзакций
    $stmt = $conn->query("SELECT COUNT(*) as total_transactions FROM transactions");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalTransactions = $result ? (int)$result['total_transactions'] : 0;

    // Общий баланс всех пользователей (положительные и отрицательные транзакции)
    $stmt = $conn->query("SELECT SUM(amount) as total_balance FROM transactions");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalBalance = $result ? (float)$result['total_balance'] : 0;
} catch (PDOException $e) {
    $totalClients = 0;
    $totalTransactions = 0;
    $totalBalance = 0;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
  <title>Добро Пожаловать</title>
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="container">

  <section class="info-section" role="main" aria-labelledby="site-info-title">
    <h1 id="site-info-title">Money-Talks</h1>
    <h2>Money-Talks — говори с деньгами на одном языке.</h2>
    <p>Профессиональное веб-приложение для удобного и эффективного управления личными финансами. Наш сервис предоставляет полный контроль над доходами и расходами, позволяет планировать бюджет и анализировать финансовое состояние с помощью наглядных отчетов и графиков.</p>
    <p>Безопасность и конфиденциальность данных обеспечивается надежными технологиями и проверенными методами хранения. Наш продукт — это мощный инструмент для тех, кто ценит свое время и деньги.</p>
    <a href="/login" class="btn-primary" role="button">Перейти в профиль</a>
  </section>

  <aside class="card" role="complementary" aria-label="Количество зарегистрированных клиентов">
    <h2>Зарегистрировано клиентов</h2>
    <div class="number" aria-live="polite"><?= number_format($totalClients) ?></div>
  </aside>

  <aside class="card" role="complementary" aria-label="Статистика всех пользователей">
    <h2>Общая статистика</h2>
    <p><strong>Суммарный оборот:</strong> <?= number_format($totalBalance, 2, ',', ' ') ?> ₽</p>
    <p><strong>Всего транзакций:</strong> <?= number_format($totalTransactions) ?></p>
  </aside>

</div>

</body>
</html>
