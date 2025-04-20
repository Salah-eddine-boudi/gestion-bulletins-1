<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenue à Junia Maroc</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Animate.css CDN -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
  <style>
    :root {
      --primary-color: #4B2E83;
      --secondary-color: #FF5F1F;
      --accent-color: #FF8C00;
      --text-light: #ffffff;
      --text-dark: #2C1810;
      --bg-light: #f8f9fa;
      --transition: all 0.3s ease-in-out;
    }
  
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--bg-light);
      margin: 0;
      padding: 0;
    }
  
    /* Navbar révisée */
    .navbar {
      background-color: var(--primary-color);
      padding: 1rem 2rem;
      transition: var(--transition);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .navbar.scrolled {
      background-color: rgba(75, 46, 131, 0.98);
      backdrop-filter: blur(10px);
    }
    
    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 1.5rem;
      padding: 0;
    }
    
    .logo-container {
      display: flex;
      align-items: center;
    }
    
    .logo {
      height: 50px;
      width: auto;
      filter: brightness(0) invert(1);
      transition: var(--transition);
    }
    
    .brand-text {
      display: flex;
      flex-direction: column;
      line-height: 1.2;
    }
    
    .brand-title {
      font-size: 1.5rem;
      font-weight: 500;
      color: var(--text-light);
      margin: 0;
      letter-spacing: 0.5px;
    }
    
    .brand-subtitle {
      font-size: 1rem;
      color: rgba(255, 255, 255, 0.85);
      font-weight: 300;
    }
    
    .navbar-nav {
      gap: 1rem;
    }
    
    .nav-item {
      position: relative;
    }
    
    .nav-link {
      color: var(--text-light) !important;
      font-size: 1.1rem;
      font-weight: 400;
      padding: 0.5rem 1rem !important;
      transition: var(--transition);
      position: relative;
    }
    
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background-color: var(--text-light);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }
    
    .nav-link:hover::after {
      width: 80%;
    }
    
    .nav-auth {
      display: flex;
      gap: 1rem;
      align-items: center;
      margin-left: 2rem;
    }
    
    .nav-auth .nav-link {
      border-radius: 25px;
      padding: 0.5rem 1.5rem !important;
    }
    
    .nav-auth .nav-link.register {
      background-color: rgba(255, 255, 255, 0.1);
    }
    
    .nav-auth .nav-link.register:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    /* Hero section */
    .hero {
      background: linear-gradient(135deg, var(--primary-color) 0%, #6b42b3 50%, var(--secondary-color) 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
      padding-top: 76px;
    }
    
    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect fill="rgba(255,255,255,0.05)" x="0" y="0" width="100" height="100"/></svg>') repeat;
      opacity: 0.1;
    }
    
    .hero-content {
      text-align: center;
      max-width: 800px;
      padding: 2rem;
      position: relative;
      z-index: 2;
    }
    
    .hero h1 {
      font-size: 4rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: var(--text-light);
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .hero p {
      font-size: 1.4rem;
      color: rgba(255,255,255,0.9);
      margin-bottom: 2.5rem;
    }

    /* Boutons */
    .btn-custom {
      padding: 1rem 2.5rem;
      font-size: 1.1rem;
      border-radius: 50px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 600;
      margin: 0.5rem;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-primary.btn-custom {
      background: var(--secondary-color);
      border: none;
      color: var(--text-light);
      box-shadow: 0 4px 15px rgba(255, 95, 31, 0.3);
    }
    
    .btn-primary.btn-custom:hover {
      background: var(--accent-color);
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(255, 95, 31, 0.4);
    }
    
    .btn-outline-light.btn-custom {
      border: 2px solid rgba(255,255,255,0.8);
      background: transparent;
      color: var(--text-light);
    }
    
    .btn-outline-light.btn-custom:hover {
      background: rgba(255,255,255,0.1);
      border-color: var(--text-light);
      transform: translateY(-3px);
    }

    /* Footer */
    .footer {
      background: var(--primary-color);
      color: var(--text-light);
      padding: 3rem 0;
      text-align: center;
      position: relative;
    }
    
    .footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
    }

    /* Responsive Design */
    @media (max-width: 991px) {
      .navbar-nav {
        padding: 1rem 0;
      }
      
      .nav-auth {
        margin-left: 0;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
      }
      
      .brand-title {
        font-size: 1.2rem;
      }
      
      .brand-subtitle {
        font-size: 0.9rem;
      }
      
      .hero h1 {
        font-size: 2.8rem;
      }
      
      .hero p {
        font-size: 1.2rem;
      }
      
      .btn-custom {
        padding: 0.8rem 2rem;
        font-size: 1rem;
      }
    }

    @media (max-width: 768px) {
      .navbar {
        padding: 0.8rem 1rem;
      }
      
      .logo {
        height: 30px;
      }
      
      .hero h1 {
        font-size: 2.2rem;
      }
      
      .hero p {
        font-size: 1.1rem;
      }
    }
  </style>
</head>
<body>
  <!-- Animation de chargement -->
  <x-loading-animation :show="true" />

  <!-- Contenu principal (initialement caché) -->
  <div id="main-content" style="display: none;">
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
      <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
          <div class="logo-container">
            <img src="{{ asset('storage/image/JUNIA.png') }}" class="logo" alt="JUNIA">
          </div>
          <div class="brand-text">
            <span class="brand-title">JUNIA Maroc</span>
            <span class="brand-subtitle">Grande école d'ingénieurs</span>
          </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('home') }}">Accueil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('contact') }}">Contact</a>
            </li>
            @guest
              <div class="nav-auth">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('login') }}">Se Connecter</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link register" href="{{ route('register') }}">S'inscrire</a>
                </li>
              </div>
            @else
              <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                   data-bs-toggle="dropdown" aria-expanded="false">
                  {{ Auth::user()->nom }} {{ Auth::user()->prenom }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Mon Profil</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }} "
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      Déconnexion
                    </a>
                  </li>
                </ul>
              </li>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1 class="animate_animated animate_fadeInDown">JUNIA Maroc</h1>
        <p class="animate_animated animate_fadeInUp">Une plateforme moderne pour la gestion des bulletins et des étudiants</p>
        @guest
          <div>
            <a href="{{ route('login') }}" class="btn btn-primary btn-custom animate_animated animate_pulse">Se Connecter</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-custom animate_animated animate_pulse">S'inscrire</a>
          </div>
        @else
          <div>
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-custom animate_animated animate_pulse">Accéder au Dashboard</a>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-light btn-custom animate_animated animate_pulse">Mon Profil</a>
          </div>
        @endguest
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <p>&copy; 2024 JUNIA Maroc | Tous droits réservés</p>
      </div>
    </footer>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Masquer l'animation et afficher le contenu après 3 secondes
    setTimeout(() => {
      document.getElementById('loading-animation').style.display = 'none';
      document.getElementById('main-content').style.display = 'block';
    }, 3000);
  </script>
</body>
</html>