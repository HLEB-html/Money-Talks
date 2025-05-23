<header class="header-main">  
  <button class="header-toggle-sidebar" aria-label="Toggle sidebar" onclick="toggleSidebar()">☰</button>
  <div class="header-logo"><svg
  width="140"
  height="60"
  viewBox="0 0 140 60"
  fill="none"
  xmlns="http://www.w3.org/2000/svg"
  aria-label="Money-Talks Logo"
  role="img"
>
  <rect x="4" y="4" width="132" height="52" rx="10" ry="10" fill="#4f46e5" />
  <text
    x="50%"
    y="42"
    font-family="Segoe UI, Tahoma, Geneva, Verdana, sans-serif"
    font-size="36"
    fill="white"
    font-weight="700"
    text-anchor="middle"
  >
    M - T
  </text>
</svg>

</div>
  <div class="header-menu-wrapper">
    <button class="header-menu-toggle" aria-label="Open menu" onclick="toggleMenu()">⋮</button>
    <nav class="header-menu-dropdown" id="dropdownMenu" aria-hidden="true" role="menu">
      <a href="login" role="menuitem">Вход</a>
      <a href="register" role="menuitem">Регистрация</a>
      <hr />
    </nav>
  </div>
</header>

<style>
:root {
  --bg-dark:  #0f172a;
  --text-dark: #fff;
  --primary: #4f46e5;
  --transition-speed: 0.4s;
  --shadow-dark: rgba(0,0,0,0.8);
}

html, body {
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: var(--bg-dark);
  color: var(--text-dark);
  transition: background-color var(--transition-speed), color var(--transition-speed);
}

/* Общие стили хедера */
.header-main {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 1.5rem;
  box-shadow: 0 2px 15px var(--shadow-dark);
  background: inherit;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 60px;
  box-sizing: border-box;
  z-index: 1000;
  transition: box-shadow var(--transition-speed);
  user-select: none;
}

.header-logo {
  font-weight: 700;
  font-size: 1.5rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.header-toggle-sidebar,
.header-menu-toggle {
  background: none;
  border: none;
  font-size: 1.8rem;
  cursor: pointer;
  color: var(--text-dark);
  transition: transform var(--transition-speed), color var(--transition-speed);
  padding: 0.3rem;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  user-select: none;
}

.header-toggle-sidebar:hover,
.header-menu-toggle:hover {
  transform: scale(1.2);
  color: var(--primary);
}

.header-menu-wrapper {
  position: relative;
}

.header-menu-dropdown {
  position: absolute;
  top: 60px;
  right: 1rem;
  background: var(--bg-dark);
  border: 1px solid #555;
  padding: 0.5rem 1rem;
  display: none;
  box-shadow: 0 5px 25px rgba(0,0,0,0.7);
  border-radius: 0.5rem;
  transition: opacity var(--transition-speed) ease;
  min-width: 130px;
  z-index: 1100;
  user-select: none;
}

.header-menu-dropdown.active {
  display: block;
}

.header-menu-dropdown a {
  display: block;
  text-decoration: none;
  color: var(--text-dark);
  padding: 0.5rem 0;
  font-size: 0.9rem;
  cursor: pointer;
  width: 100%;
  text-align: left;
  font-family: inherit;
  background: none;
  border: none;
}

.header-menu-dropdown a:hover {
  color: var(--primary);
}

.header-menu-dropdown hr {
  border: none;
  border-top: 1px solid #444;
  margin: 0.4rem 0;
}

/* --- Адаптивность --- */

/* Очень маленькие экраны (до 320px) */
@media (max-width: 320px) {
  .header-main {
    padding: 0 0.8rem;
    height: 48px;
  }

  .header-logo {
    font-size: 1rem;
  }

  .header-toggle-sidebar,
  .header-menu-toggle {
    font-size: 1.2rem;
    padding: 0.15rem;
  }

  .header-menu-dropdown {
    top: 48px;
    right: 0.5rem;
    min-width: 100px;
    padding: 0.3rem 0.6rem;
  }

  .header-menu-dropdown a {
    font-size: 0.8rem;
    padding: 0.3rem 0;
  }
}

/* Мелкие телефоны (321-480px) */
@media (min-width: 321px) and (max-width: 480px) {
  .header-main {
    padding: 0 1rem;
    height: 50px;
  }

  .header-logo {
    font-size: 1.2rem;
  }

  .header-toggle-sidebar,
  .header-menu-toggle {
    font-size: 1.4rem;
    padding: 0.2rem;
  }

  .header-menu-dropdown {
    top: 50px;
    right: 0.5rem;
    min-width: 110px;
    padding: 0.4rem 0.8rem;
  }

  .header-menu-dropdown a {
    font-size: 0.85rem;
    padding: 0.4rem 0;
  }
}

/* Планшеты (481-768px) */
@media (min-width: 481px) and (max-width: 768px) {
  .header-main {
    padding: 0 1.2rem;
    height: 55px;
  }

  .header-logo {
    font-size: 1.3rem;
  }

  .header-toggle-sidebar,
  .header-menu-toggle {
    font-size: 1.6rem;
    padding: 0.25rem;
  }

  .header-menu-dropdown {
    top: 55px;
    right: 1rem;
    min-width: 120px;
  }
}

/* Маленькие ноутбуки и десктопы (769-1024px) */
@media (min-width: 769px) and (max-width: 1024px) {
  .header-main {
    padding: 0 1.4rem;
    height: 58px;
  }

  .header-logo {
    font-size: 1.4rem;
  }

  .header-toggle-sidebar,
  .header-menu-toggle {
    font-size: 1.7rem;
    padding: 0.28rem;
  }

  .header-menu-dropdown {
    top: 58px;
    right: 1rem;
    min-width: 125px;
  }
}

/* Десктопы и большие экраны (1025px и выше) */
@media (min-width: 1025px) {
  .header-main {
    padding: 0 1.5rem;
    height: 60px;
  }

  .header-logo {
    font-size: 1.5rem;
  }

  .header-toggle-sidebar,
  .header-menu-toggle {
    font-size: 1.8rem;
    padding: 0.3rem;
  }

  .header-menu-dropdown {
    top: 60px;
    right: 1rem;
    min-width: 130px;
  }
}
</style>

<script>
function toggleMenu() {
  const menu = document.getElementById("dropdownMenu");
  const isActive = menu.classList.toggle("active");
  menu.setAttribute('aria-hidden', !isActive);
}

function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  if (!sidebar) return;
  sidebar.classList.toggle("active");
}
</script>
