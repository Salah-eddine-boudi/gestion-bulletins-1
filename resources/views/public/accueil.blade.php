<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Junia Maroc') }}</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    :root {
      --sidebar-bg: #2C3E50;
      --sidebar-color: #ECF0F1;
      --sidebar-hover: #34495E;
      --sidebar-active: #16A085;
      --header-bg: #FFF;
      --header-text: #2C3E50;
      --content-bg: #F4F6F9;
      --btn-primary: #16A085;
      --btn-primary-hover: #1ABC9C;
      --transition: all .3s ease;
    }
    * { transition: var(--transition) }
    body {
      margin: 0; padding: 0;
      font-family: 'Poppins', sans-serif;
      display: flex; height: 100vh; overflow: hidden;
    }

    /* SIDEBAR */
    .sidebar {
      width: 220px;
      background: var(--sidebar-bg);
      color: var(--sidebar-color);
      display: flex; flex-direction: column;
      position: fixed; inset: 0;
    }
    .sidebar .logo {
      height: 60px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem; font-weight: 600;
      border-bottom: 1px solid rgba(255,255,255,.1);
    }
    .sidebar nav {
      flex: 1; padding-top: .5rem;
    }
    .sidebar nav a {
      display: flex; align-items: center; gap: .75rem;
      padding: .75rem 1rem;
      color: var(--sidebar-color);
      text-decoration: none;
    }
    .sidebar nav a:hover {
      background: var(--sidebar-hover);
    }
    .sidebar nav a.active {
      background: var(--sidebar-active);
      color: #fff;
    }
    .sidebar nav a .bi {
      font-size: 1.2rem;
    }

    /* MAIN */
    .main {
      margin-left: 220px;
      width: calc(100% - 220px);
      display: flex; flex-direction: column;
      background: var(--content-bg);
      overflow: auto;
    }

    /* HEADER */
    .header {
      height: 60px;
      background: var(--header-bg);
      display: flex; align-items: center;
      padding: 0 .75rem;
      box-shadow: 0 1px 4px rgba(0,0,0,.1);
      position: sticky; top: 0; z-index: 100;
    }
    .toggle-btn {
      font-size: 1.4rem; color: var(--header-text);
      background: none; border: none; cursor: pointer;
    }
    .search-box {
      flex: 1; position: relative; margin: 0 1rem;
    }
    .search-box input {
      width: 100%; padding: .5rem 1rem;
      border: 1px solid #ccc; border-radius: 4px;
    }
    .header-nav {
      display: flex; align-items: center; gap: .75rem;
    }
    .header-nav a {
      color: var(--header-text); text-decoration: none;
      padding: .5rem; border-radius: 4px;
    }
    .header-nav a:hover {
      background: rgba(0,0,0,.05);
    }

    /* CONTENT */
    .content {
      padding: 1.5rem;
      animation: fadeInRight 0.8s ease both;
    }

    /* FOOTER */
    .footer {
      padding: 1rem 1.5rem;
      text-align: center; font-size: .9rem;
      color: #666; background: #fff;
    }

    /* ANIMATIONS */
    @keyframes fadeInRight {
      from { opacity: 0; transform: translateX(30px); }
      to   { opacity: 1; transform: translateX(0); }
    }

    @media (max-width: 992px) {
      .sidebar { left: -220px; position: fixed; transition: var(--transition); }
      .sidebar.show { left: 0; }
      .main { margin-left: 0; width: 100%; }
    }
  </style>
</head>
<body>
  <!-- SIDEBAR -->
  <aside class="sidebar animate__animated animate__fadeInLeft" id="sidebar">
    <div class="logo"><i class="bi bi-building-fill"></i> JUNIA</div>
    <nav>
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home')?'active':'' }} animate__animated animate__fadeInLeft animate__delay-1s">
        <i class="bi bi-house"></i> Accueil
      </a>
      <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard')?'active':'' }} animate__animated animate__fadeInLeft animate__delay-2s">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
      @auth
      <a href="{{ route('bulletins.index') }}" class="animate__animated animate__fadeInLeft animate__delay-3s">
        <i class="bi bi-file-earmark-text"></i> Bulletins
      </a>
      <a href="{{ route('evaluations.index') }}" class="animate__animated animate__fadeInLeft animate__delay-4s">
        <i class="bi bi-journal-check"></i> Évaluations
      </a>
      @endauth
      <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact')?'active':'' }} animate__animated animate__fadeInLeft animate__delay-5s">
        <i class="bi bi-envelope"></i> Contact
      </a>
    </nav>
  </aside>

  <!-- MAIN -->
  <div class="main">
    <!-- HEADER -->
    <header class="header animate__animated animate__fadeInDown">
      <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
      <div class="search-box">
        <input list="pages" id="searchInput" placeholder="Rechercher et aller…">
        <datalist id="pages">
          <option value="Accueil" data-url="{{ route('home') }}">
          <option value="Dashboard" data-url="{{ route('dashboard') }}">
          @auth
            <option value="Bulletins" data-url="{{ route('bulletins.index') }}">
            <option value="Évaluations" data-url="{{ route('evaluations.index') }}">
          @endauth
          <option value="Contact" data-url="{{ route('contact') }}">
        </datalist>
      </div>
      <div class="header-nav">
        @guest
          <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">Se connecter</a>
          <a href="{{ route('register') }}" class="btn btn-sm" style="background:var(--btn-primary);color:#fff;">S’inscrire</a>
        @else
          <a href="{{ route('profile.edit') }}"><i class="bi bi-person-circle"></i> {{ Auth::user()->nom }}</a>
          <a href="{{ route('logout') }}"
             onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right text-danger"></i>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        @endguest
      </div>
    </header>

    <!-- CONTENT -->
    <div class="content">
      @yield('content')
    </div>

    <!-- FOOTER -->
    <footer class="footer">
      &copy; {{ date('Y') }} JUNIA Maroc — Tous droits réservés.
    </footer>
  </div>

  <!-- Bootstrap JS & Toggle/Search -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Sidebar toggle
    document.getElementById('toggleSidebar').onclick = () => {
      document.getElementById('sidebar').classList.toggle('show');
    };
    // Search + go
    const pages = [...document.querySelectorAll('#pages option')];
    document.getElementById('searchInput').addEventListener('input', e => {
      const val = e.target.value.toLowerCase();
      const opt = pages.find(o => o.value.toLowerCase() === val);
      if (opt) window.location.href = opt.dataset.url;
    });
    document.getElementById('searchInput').addEventListener('keydown', e => {
      if (e.key === 'Enter') {
        const val = e.target.value.toLowerCase();
        const opt = pages.find(o => o.value.toLowerCase().includes(val));
        if (opt) window.location.href = opt.dataset.url;
      }
    });
  </script>
</body>
</html>
