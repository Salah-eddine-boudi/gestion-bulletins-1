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
    
    {{-- Google Fonts - Open Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2C3E50;
            --secondary-color: #16A085;
            --accent-color: #3498DB;
            --hover-color: #1ABC9C;
            --text-light: #ECF0F1;
            --text-dark: #2C3E50;
            --sidebar-width: 250px;
            --header-height: 60px;
            --bg-light: #F9FAFB;
            --bg-dark: #34495E;
            --transition-default: all 0.3s ease;
            --shadow-sm: 0 2px 5px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Base styles */
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: var(--bg-light);
            color: var(--text-dark);
            display: flex;
            overflow-x: hidden;
        }

        /* Sidebar styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            color: var(--text-light);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: var(--transition-default);
            box-shadow: var(--shadow-md);
            padding-top: var(--header-height);
        }

        .sidebar-collapsed {
            left: calc(-1 * var(--sidebar-width) + 60px);
        }

        .sidebar-header {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand {
            color: var(--text-light);
            font-weight: 700;
            font-size: 1.2rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-item {
            display: block;
            padding: 0;
        }

        .sidebar-link {
            color: var(--text-light);
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: var(--transition-default);
            position: relative;
            white-space: nowrap;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background-color: rgba(255,255,255,0.1);
            color: var(--hover-color);
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--secondary-color);
        }

        .sidebar-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .sidebar-dropdown {
            background-color: rgba(0,0,0,0.15);
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease;
        }

        .sidebar-dropdown.show {
            max-height: 500px;
        }

        .sidebar-dropdown .sidebar-link {
            padding-left: 3.5rem;
            font-size: 0.9rem;
        }

        /* Header styles */
        .main-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background-color: var(--text-light);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 999;
            transition: var(--transition-default);
        }

        .main-header.full-width {
            left: 60px;
        }

        .toggle-sidebar {
            cursor: pointer;
            font-size: 1.3rem;
            padding: 0.5rem;
            border-radius: 0.3rem;
            background-color: transparent;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-default);
            margin-right: 1rem;
        }

        .toggle-sidebar:hover {
            background-color: rgba(0,0,0,0.05);
            color: var(--secondary-color);
        }

        .header-search {
            flex: 1;
            max-width: 600px;
            margin: 0 1rem;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            background-color: #EDF2F7;
            transition: var(--transition-default);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        }

        .header-nav {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        .header-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: transparent;
            color: var(--text-dark);
            margin-left: 0.5rem;
            transition: var(--transition-default);
            position: relative;
        }

        .header-btn:hover {
            background-color: rgba(0,0,0,0.05);
            color: var(--secondary-color);
        }

        .notification-badge {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            border: 2px solid white;
        }

        /* Main content styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            transition: var(--transition-default);
        }

        .main-content.full-width {
            margin-left: 60px;
        }

        .page-title {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        /* Card styles */
        .dashboard-card {
            border-radius: 10px;
            box-shadow: var(--shadow-sm);
            border: none;
            transition: var(--transition-default);
            margin-bottom: 2rem;
            background-color: white;
        }

        .dashboard-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* User dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: var(--transition-default);
        }

        .user-dropdown-toggle:hover {
            background-color: rgba(0,0,0,0.05);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--secondary-color);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: #718096;
        }

        .user-dropdown-menu {
            min-width: 240px;
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .dropdown-header {
            padding: 1rem;
            background-color: var(--bg-light);
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            border-bottom: 1px solid #E2E8F0;
        }

        .dropdown-item {
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition-default);
        }

        .dropdown-item:hover {
            background-color: rgba(22, 160, 133, 0.1);
            color: var(--secondary-color);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-header, .main-content {
                left: 0;
                margin-left: 0;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .header-search {
                display: none;
            }
            
            .main-content {
                padding: 1rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.3s ease forwards;
        }

        /* Utility classes */
        .text-primary { color: var(--primary-color) !important; }
        .text-secondary { color: var(--secondary-color) !important; }
        .bg-primary { background-color: var(--primary-color) !important; }
        .bg-secondary { background-color: var(--secondary-color) !important; }
        .btn-primary {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
        }
        .btn-primary:hover {
            background-color: var(--hover-color) !important;
            border-color: var(--hover-color) !important;
        }
        .btn-outline-primary {
            color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
        }
        .btn-outline-primary:hover {
            background-color: var(--secondary-color) !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('home') }}" class="sidebar-brand">
                <img src="{{ asset('storage/image/JUNIA.png') }}" alt="Logo JUNIA" height="30">
                <span class="ms-2">JUNIA Maroc</span>
            </a>
        </div>
        
        <ul class="sidebar-menu mt-4">
            <!-- Accueil -->
            <li class="sidebar-item">
                <a href="{{ route('home') }}" class="sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="fas fa-home"></i></span>
                    <span class="sidebar-text">Accueil</span>
                </a>
            </li>
            
            <!-- Dashboard -->
            <li class="sidebar-item">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="sidebar-text">Tableau de bord</span>
                </a>
            </li>

            @auth
                @php $role = Auth::user()->role; @endphp
                
                <!-- Section Utilisateurs (admin & directeur) -->
                @if(in_array($role, ['admin', 'directeur']))
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#usersDropdown">
                        <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                        <span class="sidebar-text">Utilisateurs</span>
                    </a>
                    <div class="sidebar-dropdown collapse" id="usersDropdown">
                        <a href="{{ route('eleves.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Élèves</span>
                        </a>
                        <a href="{{ route('professeurs.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Professeurs</span>
                        </a>
                        <a href="{{ route('directeurs.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Directeurs Pédagogiques</span>
                        </a>
                        @if($role === 'admin')
                        <a href="{{ route('admins.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Admins</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif
                
                <!-- Section Pédagogie -->
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#academicDropdown">
                        <span class="sidebar-icon"><i class="fas fa-graduation-cap"></i></span>
                        <span class="sidebar-text">Pédagogie</span>
                    </a>
                    <div class="sidebar-dropdown collapse" id="academicDropdown">
                        @if(in_array($role, ['admin']))
                        <a href="{{ route('matieres.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Matières</span>
                        </a>
                        <a href="{{ route('unites-enseignement.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Unités d'Enseignement</span>
                        </a>
                        <a href="{{ route('evaluation-params.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Paramètres d'évaluation</span>
                        </a>
                        @endif
                        
                        @if(in_array($role, ['directeur', 'professeur']))
                        <a href="{{ route('evaluations.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Évaluations</span>
                        </a>
                        <a href="{{ route('evaluations.rattrapage') }}" class="sidebar-link">
                            <span class="sidebar-text">Rattrapage</span>
                        </a>
                        <a href="{{ route('evaluation-params.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Paramètres d'évaluation</span>
                        </a>
                        @endif
                        
                        @if(in_array($role, ['admin', 'directeur', 'eleve']))
                        <a href="{{ route('bulletins.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Bulletins</span>
                        </a>
                        <a href="{{ route('evaluations.index') }}" class="sidebar-link">
                            <span class="sidebar-text">Évaluations</span>
                        </a>
                        <a href="{{ route('evaluations.rattrapage') }}" class="sidebar-link">
                            <span class="sidebar-text">Rattrapage</span>
                        </a>
                        @endif
                    </div>
                </li>
            @endauth
            
            <!-- Contact -->
            <li class="sidebar-item">
                <a href="{{ route('contact') }}" class="sidebar-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="fas fa-envelope"></i></span>
                    <span class="sidebar-text">Contact</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Header -->
    <header class="main-header" id="main-header">
        <button class="toggle-sidebar" id="toggle-sidebar">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="header-search">
            <input type="text" class="search-input" placeholder="Rechercher...">
        </div>
        
        <div class="header-nav">
            <button class="header-btn">
                <i class="fas fa-bell"></i>
                <span class="notification-badge"></span>
            </button>
            
            <button class="header-btn">
                <i class="fas fa-cog"></i>
            </button>
            
            @auth
            <div class="dropdown user-dropdown ms-3">
                @php
                $photo = Auth::user()->photo
                    ? asset('storage/' . Auth::user()->photo)
                    : asset('storage/profile_pictures/default.png');
                @endphp
                <div class="user-dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ $photo }}" alt="Photo de profil" class="user-avatar">
                    <div class="user-info d-none d-md-flex">
                        <span class="user-name">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</span>
                        <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>
                
                <ul class="dropdown-menu user-dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">
                        <h6 class="mb-0">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</h6>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-circle text-secondary"></i>
                            Mon Profil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-bell text-secondary"></i>
                            Notifications
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog text-secondary"></i>
                            Paramètres
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        <button type="button" class="dropdown-item" onclick="document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt text-danger"></i>
                            Déconnexion
                        </button>
                    </li>
                </ul>
            </div>
            @else
            <div class="d-flex align-items-center ms-3">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Se Connecter</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">S'inscrire</a>
            </div>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainHeader = document.getElementById('main-header');
            const mainContent = document.getElementById('main-content');
            const toggleSidebar = document.getElementById('toggle-sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            // Toggle sidebar function
            function toggleSidebarState() {
                sidebar.classList.toggle('sidebar-collapsed');
                mainHeader.classList.toggle('full-width');
                mainContent.classList.toggle('full-width');
            }
            
            // Handle sidebar toggle on click
            toggleSidebar.addEventListener('click', function() {
                if (window.innerWidth > 992) {
                    toggleSidebarState();
                } else {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                }
            });
            
            // Close sidebar when clicking overlay
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 992) {
                    sidebar.classList.remove('sidebar-collapsed');
                    mainHeader.classList.remove('full-width');
                    mainContent.classList.remove('full-width');
                }
            });
            
            // Handle dropdown menus in sidebar
            const dropdownToggles = document.querySelectorAll('.sidebar-link.dropdown-toggle');
            
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Get the target from the data-bs-target attribute
                    const targetId = this.getAttribute('data-bs-target');
                    const target = document.querySelector(targetId);
                    
                    // Toggle the 'show' class
                    if (target) {
                        target.classList.toggle('show');
                    }
                });
            });
            
            // Active state for sidebar links
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-link:not(.dropdown-toggle)');
            
            sidebarLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href)) {
                    link.classList.add('active');
                    
                    // If it's in a dropdown, open the parent dropdown
                    const parentDropdown = link.closest('.sidebar-dropdown');
                    if (parentDropdown) {
                        parentDropdown.classList.add('show');
                    }
                }
            });
        });
    </script>
</body>
</html>