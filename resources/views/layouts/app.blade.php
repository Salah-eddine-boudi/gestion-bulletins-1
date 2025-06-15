<!DOCTYPE html>
<html lang="fr">
<head>
  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Junia Maroc - Plateforme éducative">
    <title>{{ config('app.name', 'Junia Maroc') }}</title>

    {{-- Preload critical assets --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    {{-- Animate.css for animations --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" integrity="sha384-UM7OftnvC/X6X4D3fQrTOtSrWOZGySvZnBnP9PBnRtX+NLkk2ZHVD6EILUTFy+R" crossorigin="anonymous">
    
    {{-- Font Awesome for icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha384-iw3OoTErCYJJB9mCa8LNS2hbsQ7M3C0SkXI2StPCPmyQ9t7W9qKX/2Zqe1uZR5.Ut" crossorigin="anonymous">
    
    {{-- Google Fonts - Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4B2E83;
            --secondary-color: #FF5F1F;
            --secondary-hover: #FF8C00;
            --white: #fff;
            --transition-default: all 0.3s ease-in-out;
            --shadow-sm: 0 2px 5px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Base styles */
        body {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        /* Card styles */
        .card {
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            border: none;
        }

        /* Navigation styles */
        .navbar {
            background: var(--white) !important;
            box-shadow: var(--shadow-sm);
            padding: 0.75rem 0;
        }

        .navbar-brand {
            transition: var(--transition-default);
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        /* Button styles */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            transition: var(--transition-default);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--secondary-hover);
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(255, 95, 31, 0.3);
        }

        .btn-outline-primary {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
            transition: var(--transition-default);
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: var(--secondary-color);
            color: var(--white);
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(255, 95, 31, 0.3);
        }

        /* Navigation link styles */
        .navbar-nav .nav-item .nav-link {
            font-size: 1rem;
            color: var(--primary-color);
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem;
            position: relative;
        }

        .navbar-nav .nav-item .nav-link:hover,
        .navbar-nav .nav-item .nav-link:focus {
            color: var(--secondary-color);
        }

        .navbar-nav .nav-item .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background-color: var(--secondary-color);
            border-radius: 2px;
        }

        /* User profile styles */
        .user-name {
            font-weight: 600;
            color: var(--primary-color);
            transition: color 0.3s ease;
        }

        .user-name:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .profile-picture-container {
            position: relative;
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

        /* Dropdown styling */
        .dropdown-menu {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            animation-duration: 0.3s;
        }

        .dropdown-item {
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: var(--transition-default);
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: rgba(255, 95, 31, 0.1);
            color: var(--secondary-color);
        }

        /* Focus styles for accessibility */
        a:focus, button:focus {
            outline: 3px solid rgba(255, 95, 31, 0.4);
            outline-offset: 3px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-brand img {
                width: 120px;
            }
            
            .animate__animated {
                animation-name: none !important;
            }
        }

        /* Reduced motion preferences */
        @media (prefers-reduced-motion: reduce) {
            .animate__animated {
                animation-name: none !important;
                transition-duration: 0s !important;
            }
            
            * {
                transition-duration: 0.1s !important;
            }
        }
    </style>
</head>
<body>
  @stack('scripts')
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm animate__animated animate__fadeInDown sticky-top" aria-label="Navigation principale">
      <div class="container">
        <!-- Logo Junia Maroc -->
        <a class="navbar-brand fw-bold text-primary position-relative overflow-hidden" 
           href="{{ route('home') }}"
           aria-label="Accueil Junia Maroc">
          <span class="d-flex align-items-center">
            <img src="{{ asset('storage/image/JUNIA.png') }}" alt="Logo JUNIA" class="me-2" width="150" height="auto" style="object-fit: contain;">
          </span>
        </a>

        <!-- Bouton hamburger pour mobile -->
        <button class="navbar-toggler border-0" type="button" 
                data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Afficher/masquer le menu">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Barre de navigation -->
<div class="collapse navbar-collapse" id="navbarNav">
  <ul class="navbar-nav ms-auto align-items-center">

    {{-- Liens communs --}}
    <li class="nav-item mx-2">
      <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
        Accueil
      </a>
    </li>
    <li class="nav-item mx-2">
      <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
        Contact
      </a>
    </li>

    {{-- Liens dynamiques selon le rôle --}}
    @auth
      @php $role = Auth::user()->role; @endphp

      {{-- Section Utilisateurs (admin & directeur) --}}
      @if(in_array($role, ['admin', 'directeur']))
        <li class="nav-item dropdown mx-2">
          <a class="nav-link dropdown-toggle" href="#" id="usersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Utilisateurs
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li><a class="dropdown-item" href="{{ route('eleves.index') }}">Élèves</a></li>
            <li><a class="dropdown-item" href="{{ route('professeurs.index') }}">Professeurs</a></li>
            <li><a class="dropdown-item" href="{{ route('directeurs.index') }}">Directeurs Pédagogiques</a></li>
            
            @if($role === 'admin')
              <li><a class="dropdown-item" href="{{ route('admins.index') }}">Admins</a></li>
            @endif
          </ul>
          
        </li>
      @endif

      {{-- Section Pédagogie --}}
      <li class="nav-item dropdown mx-2">
        <a class="nav-link dropdown-toggle" href="#" id="academicDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Pédagogie
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow">
          @if(in_array($role, ['admin',]))
            <li><a class="dropdown-item" href="{{ route('matieres.index') }}">Matières</a></li>
            <li><a class="dropdown-item" href="{{ route('unites-enseignement.index') }}">Unités d'Enseignement</a></li>
            <li><a class="dropdown-item" href="{{ route('evaluation-params.index') }}">evaluation-params</a></li>
          @endif

          @if(in_array($role, [ 'directeur', 'professeur']))
            <li><a class="dropdown-item" href="{{ route('evaluations.index') }}">Évaluations</a></li>
            <li><a class="dropdown-item" href="{{ route('evaluations.rattrapage') }}">rattrapage</a></li>
            <li><a class="dropdown-item" href="{{ route('evaluation-params.index') }}">evaluation-params</a></li>
          @endif

          @if(in_array($role, ['admin', 'directeur', 'eleve']))
            <li><a class="dropdown-item" href="{{ route('bulletins.index') }}">Bulletins</a></li>
            <li><a class="dropdown-item" href="{{ route('evaluations.index') }}">Évaluations</a></li>
            <li><a class="dropdown-item" href="{{ route('evaluations.rattrapage') }}">rattrapage</a></li>
          @endif
          @if(in_array($role, []))
            <li><a class="dropdown-item" href="{{ route('matieres.index') }}">Matières</a></li>
            <li><a class="dropdown-item" href="{{ route('unites-enseignement.index') }}">Unités d'Enseignement</a></li>
          @endif
 {{--
          @if($role === 'eleve')
            <li><a class="dropdown-item" href="{{ route('mes.matieres') }}">Mes Matières</a></li>
          @endif
          --}}
        </ul>
      </li>

      {{-- Lien vers Dashboard --}}
      <li class="nav-item mx-2">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
          <i class="fas fa-tachometer-alt me-1"></i> Dashboard
        </a>
      </li>

      {{-- Profil utilisateur --}}
      <li class="nav-item dropdown mx-2">
        @php
          $photo = Auth::user()->photo
              ? asset('storage/' . Auth::user()->photo)
              : asset('storage/profile_pictures/default.png');
        @endphp
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="position-relative me-2">
            <img src="{{ $photo }}" alt="Photo de profil" class="rounded-circle border border-primary" width="40" height="40" style="object-fit: cover;">
            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle" style="width: 10px; height: 10px;"></span>
          </div>
          <span class="fw-medium">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
          <li class="px-3 py-2 small text-muted">
            Connecté en tant que <strong>{{ Auth::user()->email }}</strong><br>
            Rôle : <strong>{{ $role }}</strong>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
              <i class="fas fa-user-circle me-2 text-primary"></i> Mon Profil
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            <button type="button" class="dropdown-item text-danger d-flex align-items-center" onclick="document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
            </button>
          </li>
        </ul>
      </li>
    @endauth

    {{-- Pour les invités (non connectés) --}}
    @guest
      <li class="nav-item mx-2">
        <a class="btn btn-outline-primary" href="{{ route('login') }}">
          <i class="fas fa-sign-in-alt me-2"></i>Se Connecter
        </a>
      </li>
      <li class="nav-item mx-2">
        <a class="btn btn-primary" href="{{ route('register') }}">
          <i class="fas fa-user-plus me-2"></i>S'inscrire
        </a>
      </li>
    @endguest

  </ul>
</div>

    </nav>

    <!-- Contenu principal -->
    <main class="container py-4 animate__animated animate__fadeIn">
        @yield('content')
    </main>

    <!-- Pied de page -->
    <footer class="mt-auto py-3 bg-white shadow-sm">
      <div class="container text-center">
        <p class="mb-0 text-muted">&copy; {{ date('Y') }} Junia Maroc. Tous droits réservés.</p>
      </div>
    </footer>

    <!-- Scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous" defer></script>
    <script>
      // Stop infinite animations after 3 seconds for better performance
      document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
          document.querySelectorAll('.animate__infinite').forEach(function(element) {
            element.classList.remove('animate__infinite');
          });
        }, 3000);
        
        // Handle active state for navbar items
        const currentPath = window.location.pathname;
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
          if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
          }
        });
      });
      @stack('scripts')
</body>
</html>
