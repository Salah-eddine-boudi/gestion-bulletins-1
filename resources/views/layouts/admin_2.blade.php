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
            overflow-y: auto;
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

        /* Enhanced Search Styles */
        .header-search {
            flex: 1;
            max-width: 600px;
            margin: 0 1rem;
            position: relative;
        }

        .search-container {
            position: relative;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.8rem;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            background-color: #EDF2F7;
            transition: var(--transition-default);
            font-size: 0.95rem;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            background-color: white;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            font-size: 0.9rem;
        }

        .search-clear {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            font-size: 0.9rem;
            cursor: pointer;
            display: none;
        }

        .search-input:not(:placeholder-shown) + .search-clear {
            display: block;
        }

        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            margin-top: 0.5rem;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            border: 1px solid #E2E8F0;
            display: none;
        }

        .search-dropdown.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .search-categories {
            display: flex;
            border-bottom: 1px solid #E2E8F0;
            padding: 0.5rem;
            gap: 0.5rem;
            overflow-x: auto;
        }

        .search-category {
            padding: 0.35rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            background-color: #EDF2F7;
            color: #4A5568;
            cursor: pointer;
            white-space: nowrap;
            transition: var(--transition-default);
        }

        .search-category:hover, .search-category.active {
            background-color: var(--secondary-color);
            color: white;
        }

        .search-results {
            padding: 0.5rem 0;
        }

        .search-result {
            padding: 0.7rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: var(--transition-default);
        }

        .search-result:hover {
            background-color: #EDF2F7;
        }

        .search-result-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background-color: rgba(66, 153, 225, 0.1);
            color: var(--accent-color);
        }

        .search-result-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .search-result-title {
            font-weight: 500;
            color: var(--text-dark);
        }

        .search-result-path {
            font-size: 0.8rem;
            color: #718096;
        }

        .search-result-badge {
            font-size: 0.7rem;
            background-color: var(--secondary-color);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
        }

        .search-result-shortcut {
            font-size: 0.8rem;
            color: #718096;
            background-color: #EDF2F7;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
        }

        .search-no-results {
            padding: 1.5rem;
            text-align: center;
            color: #718096;
        }

        .search-footer {
            padding: 0.7rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #E2E8F0;
            font-size: 0.8rem;
            color: #718096;
        }

        .search-footer-tips {
            display: flex;
            gap: 1rem;
        }

        .search-footer-tip {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .search-footer-key {
            background-color: #E2E8F0;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-size: 0.7rem;
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

        /* Quick Action Button */
        .quick-action-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            cursor: pointer;
            z-index: 100;
            transition: var(--transition-default);
        }

        .quick-action-btn:hover {
            background-color: var(--primary-color);
            transform: scale(1.05);
        }

        .quick-action-menu {
            position: absolute;
            bottom: 65px;
            right: 0;
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            width: 220px;
            overflow: hidden;
            transform: scale(0.95);
            transform-origin: bottom right;
            opacity: 0;
            pointer-events: none;
            transition: var(--transition-default);
        }

        .quick-action-btn.active .quick-action-menu {
            transform: scale(1);
            opacity: 1;
            pointer-events: auto;
        }

        .quick-action-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            transition: var(--transition-default);
            cursor: pointer;
        }

        .quick-action-item:hover {
            background-color: rgba(22, 160, 133, 0.1);
            color: var(--secondary-color);
        }

        .quick-action-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
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

            .header-search {
                max-width: none;
            }
        }

        @media (max-width: 768px) {
            .search-dropdown {
                position: fixed;
                top: var(--header-height);
                left: 0;
                right: 0;
                margin-top: 0;
                border-radius: 0 0 10px 10px;
                max-height: 80vh;
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
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Rechercher une page ou une fonctionnalité..." autocomplete="off">
                <i class="fas fa-times search-clear" id="searchClear"></i>
            </div>
            <div class="search-dropdown" id="searchDropdown">
                <div class="search-categories">
                    <div class="search-category active" data-category="all">Tout</div>
                    <div class="search-category" data-category="pages">Pages</div>
                    <div class="search-category" data-category="users">Utilisateurs</div>
                    <div class="search-category" data-category="academic">Pédagogie</div>
                    <div class="search-category" data-category="settings">Paramètres</div>
                </div>
                <div class="search-results" id="searchResults">
                    <!-- Les résultats seront chargés dynamiquement ici -->
                </div>
                <div class="search-footer">
                    <span>Résultats filtrés par rôle</span>
                    <div class="search-footer-tips">
                        <div class="search-footer-tip">
                            <span class="search-footer-key">↑</span>
                            <span class="search-footer-key">↓</span>
                            <span>Naviguer</span>
                        </div>
                        <div class="search-footer-tip">
                            <span class="search-footer-key">Enter</span>
                            <span>Sélectionner</span>
                        </div>
                        <div class="search-footer-tip">
                            <span class="search-footer-key">ESC</span>
                            <span>Fermer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="header-nav">
            <button class="header-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="notification-badge"></span>
            </button>
            
            <button class="header-btn" title="Paramètres">
                <i class="fas fa-cog"></i>
            </button>
            
            @auth
            <div class="user-dropdown dropdown">
                <div class="user-dropdown-toggle dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" alt="Avatar" class="user-avatar">
                    <div class="user-info d-none d-md-block">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>
                <ul class="dropdown-menu user-dropdown-menu dropdown-menu-end">
                    <li>
                        <div class="dropdown-header">
                            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </div>
                    form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary me-2">Connexion</a>
            <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Inscription</a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        @yield('content')
    </main>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Quick Action Button -->
    <div class="quick-action-btn" id="quickActionBtn">
        <i class="fas fa-plus"></i>
        <div class="quick-action-menu">
            <div class="quick-action-item" onclick="window.location.href='{{ route('evaluations.create') }}'">
                <div class="quick-action-icon"><i class="fas fa-clipboard-check"></i></div>
                <div>Nouvelle évaluation</div>
            </div>
           
            
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle sidebar
            const toggleSidebar = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');
            const mainHeader = document.getElementById('main-header');
            const mainContent = document.getElementById('mainContent');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            toggleSidebar.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                mainHeader.classList.toggle('full-width');
                mainContent.classList.toggle('full-width');
                sidebarOverlay.classList.toggle('show');
            });
            
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
            
            // Dropdown menus in sidebar
            const dropdownToggles = document.querySelectorAll('.sidebar-link.dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-bs-target');
                    const targetDropdown = document.querySelector(targetId);
                    targetDropdown.classList.toggle('show');
                });
            });
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const searchClear = document.getElementById('searchClear');
            const searchDropdown = document.getElementById('searchDropdown');
            const searchResults = document.getElementById('searchResults');
            const searchCategories = document.querySelectorAll('.search-category');
            
            searchInput.addEventListener('focus', function() {
                searchDropdown.classList.add('show');
                if (this.value.length > 0) {
                    performSearch(this.value);
                } else {
                    showRecentSearches();
                }
            });
            
            searchInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    performSearch(this.value);
                } else {
                    showRecentSearches();
                }
            });
            
            searchClear.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.focus();
                showRecentSearches();
            });
            
            document.addEventListener('click', function(e) {
                if (!searchDropdown.contains(e.target) && e.target !== searchInput) {
                    searchDropdown.classList.remove('show');
                }
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchDropdown.classList.remove('show');
                }
            });
            
            searchCategories.forEach(category => {
                category.addEventListener('click', function() {
                    searchCategories.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    if (searchInput.value.length > 0) {
                        performSearch(searchInput.value);
                    }
                });
            });
            
            function performSearch(query) {
                // Simulate search results - in a real app, this would call an API
                const activeCategory = document.querySelector('.search-category.active').getAttribute('data-category');
                
                // Clear previous results
                searchResults.innerHTML = '';
                
                // Sample data - would be replaced with actual data from server
                if (query.length > 0) {
                    const results = getSearchResults(query, activeCategory);
                    
                    if (results.length === 0) {
                        searchResults.innerHTML = `
                            <div class="search-no-results">
                                <i class="fas fa-search mb-3" style="font-size: 2rem; color: #CBD5E0;"></i>
                                <p>Aucun résultat trouvé pour "${query}"</p>
                                <small>Essayez avec d'autres termes ou catégories</small>
                            </div>
                        `;
                    } else {
                        results.forEach(result => {
                            const resultItem = document.createElement('div');
                            resultItem.className = 'search-result';
                            resultItem.innerHTML = `
                                <div class="search-result-icon">
                                    <i class="${result.icon}"></i>
                                </div>
                                <div class="search-result-content">
                                    <div class="search-result-title">${result.title}</div>
                                    <div class="search-result-path">${result.path}</div>
                                </div>
                                ${result.badge ? `<span class="search-result-badge">${result.badge}</span>` : ''}
                            `;
                            resultItem.addEventListener('click', function() {
                                window.location.href = result.url;
                            });
                            searchResults.appendChild(resultItem);
                        });
                    }
                }
            }
            
            function showRecentSearches() {
                // Display recent searches - would be stored in localStorage or cookies in a real app
                searchResults.innerHTML = `
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fs-6">Recherches récentes</span>
                            <button class="btn btn-sm text-muted" style="font-size: 0.8rem;">Effacer</button>
                        </div>
                        <div class="search-result">
                            <div class="search-result-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="search-result-content">
                                <div class="search-result-title">Bulletins du semestre 2</div>
                            </div>
                        </div>
                        <div class="search-result">
                            <div class="search-result-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="search-result-content">
                                <div class="search-result-title">Paramètres d'évaluation</div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            function getSearchResults(query, category) {
                // Sample data - would be replaced with actual search logic
                const allResults = [
                    { title: 'Tableau de bord', path: 'Navigation > Tableau de bord', icon: 'fas fa-tachometer-alt', url: '{{ route("dashboard") }}', category: 'pages' },
                    { title: 'Liste des élèves', path: 'Utilisateurs > Élèves', icon: 'fas fa-user-graduate', url: '{{ route("eleves.index") }}', category: 'users' },
                    { title: 'Bulletins de notes', path: 'Pédagogie > Bulletins', icon: 'fas fa-file-alt', url: '{{ route("bulletins.index") }}', category: 'academic' },
                    { title: 'Paramètres du compte', path: 'Paramètres > Compte', icon: 'fas fa-cog', url: '{{ route("settings") }}', category: 'settings' },
                    { title: 'Évaluations', path: 'Pédagogie > Évaluations', icon: 'fas fa-clipboard-check', url: '{{ route("evaluations.index") }}', category: 'academic' },
                    { title: 'Profil utilisateur', path: 'Utilisateur > Profil', icon: 'fas fa-user', url: '{{ route("profile.show") }}', category: 'users' }
                ];
                
                // Filter by query and category
                return allResults.filter(result => {
                    const matchesQuery = result.title.toLowerCase().includes(query.toLowerCase()) || 
                                        result.path.toLowerCase().includes(query.toLowerCase());
                    const matchesCategory = category === 'all' || result.category === category;
                    return matchesQuery && matchesCategory;
                });
            }
            
            // Quick Action Button
            const quickActionBtn = document.getElementById('quickActionBtn');
            quickActionBtn.addEventListener('click', function() {
                this.classList.toggle('active');
            });
            
            document.addEventListener('click', function(e) {
                if (!quickActionBtn.contains(e.target)) {
                    quickActionBtn.classList.remove('active');
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>