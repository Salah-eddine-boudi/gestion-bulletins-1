<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Junia Maroc</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Animate.css -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  <style>
    :root {
      --primary-color: #4B2E83;
      --secondary-color: #FF5F1F;
      --text-light: #ffffff;
      --text-dark: #2C1810;
      --transition: all 0.3s ease;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }
    
    /* Navbar styles améliorés */
    .navbar {
      background-color: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      transition: all 0.3s ease;
    }
    
    .navbar.scrolled {
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-brand {
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--primary-color);
      letter-spacing: -0.5px;
    }
    
    .navbar-brand img {
      transition: transform 0.3s ease;
    }
    
    .navbar-brand:hover img {
      transform: scale(1.05);
    }
    
    /* Navigation links améliorés */
    .nav-link {
      font-weight: 500;
      color: var(--text-dark);
      position: relative;
      padding: 0.5rem 1rem;
      margin: 0 0.2rem;
    }
    
    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 50%;
      background-color: var(--primary-color);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }
    
    .nav-link:hover::after {
      width: 100%;
    }
    
    /* Boutons améliorés */
    .btn {
      padding: 0.5rem 1.25rem;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .btn-outline-primary {
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      color: white;
      transform: translateY(-2px);
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border: none;
    }
    
    .btn-primary:hover {
      /* Note : La fonction darken() n'est pas supportée en CSS natif, vous pouvez définir une couleur manuellement */
      background-color: #3a1f6e;
      transform: translateY(-2px);
    }
    
    /* Dropdown menu amélioré */
    .dropdown-menu {
      border-radius: 12px;
      border: none;
      box-shadow: 0 5px 30px rgba(0, 0, 0, 0.15);
      padding: 1rem 0;
    }
    
    .dropdown-item {
      padding: 0.7rem 1.5rem;
      font-weight: 500;
      display: flex;
      align-items: center;
    }
    
    .dropdown-item i {
      margin-right: 10px;
      font-size: 1.1rem;
    }
    
    .dropdown-item:hover {
      background-color: rgba(75, 46, 131, 0.1);
    }
    
    /* Profile picture container */
    .profile-picture-container {
      position: relative;
      margin-right: 10px;
    }
    
    .status-indicator {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 10px;
      height: 10px;
      background-color: #22c55e;
      border: 2px solid white;
      border-radius: 50%;
    }
    
    /* Mobile responsive improvements */
    @media (max-width: 991.98px) {
      .navbar-collapse {
        background-color: white;
        padding: 1rem;
        border-radius: 12px;
        margin-top: 1rem;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.15);
      }
      
      .nav-item {
        margin: 0.5rem 0;
      }
      
      .btn {
        width: 100%;
        margin: 0.5rem 0;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg fixed-top animate__animated animate__fadeIn">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset('storage/image/JUNIA.png') }}" alt="Junia Maroc Logo" class="me-2" width="70">
        <span class="brand-text">JUNIA Maroc</span>
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item mx-2">
            <a class="nav-link" href="{{ route('home') }}">Accueil</a>
          </li>
          <li class="nav-item mx-2">
            <a class="nav-link" href="{{ route('contact') }}">Contact</a>
          </li>
          @guest
            <li class="nav-item ms-lg-3">
              <a class="btn btn-outline-primary animate__animated animate__fadeIn" href="{{ route('login') }}">
                <i class="fas fa-sign-in-alt me-2"></i>Se Connecter
              </a>
            </li>
            <li class="nav-item ms-lg-3">
              <a class="btn btn-primary animate__animated animate__fadeIn" href="{{ route('register') }}">
                <i class="fas fa-user-plus me-2"></i>S'inscrire
              </a>
            </li>
          @else
            <li class="nav-item mx-2">
              <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item dropdown ms-lg-3">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                <div class="profile-picture-container">
                  <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('storage/profile_pictures/default.png') }}"
                       alt="Profile"
                       class="rounded-circle"
                       width="40"
                       height="40"
                       style="object-fit: cover;">
                  <span class="status-indicator"></span>
                </div>
                <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeInUp" aria-labelledby="navbarDropdown">
                <li class="px-3 py-2 text-muted small">
                  <span class="d-block">Connecté en tant que</span>
                  <strong>{{ Auth::user()->email }}</strong>
                </li>
                <li class="px-3 py-2 text-muted small">
                  <span class="d-block">Rôle :</span>
                  <strong>{{ Auth::user()->role }}</strong>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-circle text-primary"></i> Mon Profil
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
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

  <!-- Script pour l'effet de scroll -->
  <script>
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  </script>

  <!-- Bannière d'accueil -->
  <section class="hero">
    <div>
      <h1 class="animate__animated animate__fadeInDown">Bienvenue à Junia Maroc</h1>
      <p class="animate__animated animate__fadeInUp">Une plateforme moderne pour la gestion des bulletins et des étudiants</p>
      @guest
        <div>
          <a href="{{ route('login') }}" class="btn btn-primary btn-custom animate__animated animate__pulse">Se Connecter</a>
          <a href="{{ route('register') }}" class="btn btn-outline-light btn-custom animate__animated animate__pulse">S'inscrire</a>
        </div>
      @else
        <div>
          <a href="{{ route('dashboard') }}" class="btn btn-success btn-custom animate__animated animate__pulse">Accéder au Dashboard</a>
          <a href="{{ route('profile.edit') }}" class="btn btn-outline-light btn-custom animate__animated animate__pulse">Mon Profil</a>
        </div>
      @endguest
    </div>
  </section>

  <!-- Pied de page -->
  <footer class="footer">
    <p>&copy; 2024 Junia Maroc | Tous droits réservés</p>
  </footer>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
