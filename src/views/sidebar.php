<?php
?>
<link rel="stylesheet" href="css/sidebar.css">

<!-- Затемнение -->
<div id="overlay"></div>

<!-- Сайдбар -->
<aside id="sidebar" class="closed" role="navigation" aria-label="Меню">

  <!-- Кнопка открытия -->
  <button id="sidebarToggle" aria-label="Меню" class="toggle-btn">☰</button>

  <a href="/" title="Главная">
    <span class="icon">
      <!-- Домик -->
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" width="24" height="24">
        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/>
        <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
      </svg>
    </span>
    <span class="text">Главная</span>
  </a>

  <a href="/profile" title="Профиль">
    <span class="icon">
      <!-- Пользователь -->
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" width="24" height="24">
        <path d="M20 21v-2a4 4 0 0 0-3-3.87M4 21v-2a4 4 0 0 1 3-3.87M16 3.13a4 4 0 1 1-8 0 4 4 0 0 1 8 0z"/>
      </svg>
    </span>
    <span class="text">Профиль</span>
  </a>

  <a href="/add" title="Добавить">
    <span class="icon">
      <!-- Плюс -->
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" width="24" height="24">
        <line x1="12" y1="5" x2="12" y2="19"/>
        <line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
    </span>
    <span class="text">Добавить Транзакцию</span>
  </a>

  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="/logout" title="Выход">
      <span class="icon">
        <!-- Дверь -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" width="24" height="24">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
          <polyline points="10 17 15 12 10 7"/>
          <line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
      </span>
      <span class="text">Выход</span>
    </a>
  <?php endif; ?>
</aside>

<script>
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const toggleBtn = document.getElementById('sidebarToggle');

  function openSidebar() {
    sidebar.classList.remove('closed');
    sidebar.classList.add('open');
    overlay.classList.add('active');
  }

  function closeSidebar() {
    sidebar.classList.add('closed');
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
  }

  function toggleSidebar() {
    if (sidebar.classList.contains('closed')) {
      openSidebar();
    } else {
      closeSidebar();
    }
  }

  toggleBtn.addEventListener('click', toggleSidebar);
  overlay.addEventListener('click', closeSidebar);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeSidebar();
    }
  });

  closeSidebar(); // Запуск с закрытым сайдбаром
</script>
