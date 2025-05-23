<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Футер и сайдбар</title>
<style>
  html, body {
    height: 100%;
    margin: 0;
  }
  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #0f172a;
    color: #f8fafc;
    font-family: Arial, sans-serif;
  }

  /* Сайдбар фиксированный слева */
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 200px;
    height: 100vh;
    background-color: #1e293b;
    padding: 20px;
    box-sizing: border-box;
    color: #cbd5e1;
  }

  /* Основной контент с отступом слева под сайдбар */
  main {
    flex: 1 0 auto;
    margin-left: 200px; /* отступ для сайдбара */
    padding: 20px;
  }

  /* Футер с таким же отступом слева */
  footer {
    flex-shrink: 0;
    margin-left: 200px; /* отступ для сайдбара */
    background-color: #1e293b;
    color: #cbd5e1;
    padding: 20px;
    text-align: center;
    font-size: 14px;
  }

  .footer-links {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
    margin-bottom: 10px;
  }
  .footer-links a {
    color: #94a3b8;
    text-decoration: none;
    font-size: 13px;
  }
  .footer-links a:hover {
    color: #3b82f6;
  }
</style>
</head>
<body>

<div class="sidebar">
  <h2>Сайдбар</h2>
  <p>Навигация или что-то еще</p>
</div>

<main>
  <h1>Основной контент страницы</h1>
  <p>Здесь основной контент с отступом слева, чтобы не пересекаться с сайдбаром.</p>
</main>

<footer>
  <div class="footer-links">
    <a href="#">Главная</a>
    <a href="#">О нас</a>
    <a href="#">Услуги</a>
    <a href="#">Контакты</a>
    <a href="#">Поддержка</a>
    <a href="#">Блог</a>
    <a href="#">Новости</a>
    <a href="#">FAQ</a>
    <a href="#">Партнёры</a>
    <a href="#">Карьера</a>
    <a href="#">Политика конфиденциальности</a>
    <a href="#">Условия использования</a>
    <a href="#">Отзывы</a>
    <a href="#">События</a>
    <a href="#">Помощь</a>
  </div>
  © 2025 Money Talks. Все права защищены.
</footer>

</body>
</html>
