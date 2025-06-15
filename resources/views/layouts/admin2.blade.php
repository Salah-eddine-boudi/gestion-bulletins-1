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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    {{-- Google Fonts - Open Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Thème par défaut (light) */
            --primary-color: #2C3E50;
            --secondary-color: #16A085;
            --accent-color: #3498DB;
            --hover-color: #1ABC9C;
            --text-light: #ECF0F1;
            --text-dark: #2C3E50;
            --bg-light: #F9FAFB;
            --bg-dark: #34495E;
            --card-bg: #FFFFFF;
            --border-color: #E2E8F0;
            --input-bg: #EDF2F7;
            --shadow-sm: 0 2px 5px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.15);
            --transition-default: all 0.3s ease;
            --sidebar-width: 250px;
            --header-height: 60px;
        }
        
        /* Thème sombre */
        [data-theme="dark"] {
            --primary-color: #1E293B;
            --secondary-color: #06B6D4;
            --accent-color: #38BDF8;
            --hover-color: #22D3EE;
            --text-light: #F8FAFC;
            --text-dark: #F1F5F9;
            --bg-light: #0F172A;
            --bg-dark: #0F172A;
            --card-bg: #1E293B;
            --border-color: #334155;
            --input-bg: #1E293B;
            --shadow-sm: 0 2px 5px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.4);
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
            transition: var(--transition-default);
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
            background-color: var(--card-bg);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 999;
            transition: var(--transition-default);
            color: var(--text-dark);
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
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            background-color: var(--input-bg);
            color: var(--text-dark);
            transition: var(--transition-default);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        }
        
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dark);
            opacity: 0.6;
        }
        
        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--card-bg);
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            margin-top: 5px;
            display: none;
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
        }
        
        .search-dropdown.show {
            display: block;
        }
        
        .search-category {
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 1px solid var(--border-color);
        }
        
        .search-options {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .search-option {
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition-default);
            cursor: pointer;
            color: var(--text-dark);
        }
        
        .search-option:hover {
            background-color: var(--bg-light);
        }
        
        .search-option i {
            color: var(--secondary-color);
            width: 20px;
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
            cursor: pointer;
        }

        .header-btn:hover {
            background-color: rgba(0,0,0,0.05);
            color: var(--secondary-color);
        }
        
        .settings-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 200px;
            background: var(--card-bg);
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            margin-top: 10px;
            display: none;
            z-index: 1000;
            border: 1px solid var(--border-color);
        }
        
        .settings-dropdown.show {
            display: block;
        }
        
        .settings-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition-default);
            cursor: pointer;
            color: var(--text-dark);
            text-decoration: none;
        }
        
        .settings-item:hover {
            background-color: var(--bg-light);
            color: var(--secondary-color);
        }
        
        .settings-item i {
            width: 18px;
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
            color: var(--text-dark);
            background-color: var(--bg-light);
            min-height: calc(100vh - var(--header-height));
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
            background-color: var(--card-bg);
        }

        .dashboard-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .card-body {
            padding: 1.5rem;
            color: var(--text-dark);
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

        /* Style pour le lien de l'avatar */
        .user-dropdown-toggle a {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: var(--transition-default);
        }
        
        .user-dropdown-toggle a:hover .user-avatar {
            transform: scale(1.05);
            box-shadow: 0 0 0 2px rgba(22, 160, 133, 0.3);
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
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            background-color: var(--card-bg);
        }

        .dropdown-header {
            padding: 1rem;
            background-color: var(--bg-light);
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            color: var(--text-dark);
        }
        
        .dropdown-divider {
            border-top: 1px solid var(--border-color);
            margin: 0.25rem 0;
        }

        .dropdown-item {
            color: var(--text-dark);
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition-default);
        }

        .dropdown-item:hover {
            background-color: var(--bg-light);
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
                box-shadow: none;
                z-index: 1010;
            }
            
            .sidebar.show {
                left: 0;
                box-shadow: var(--shadow-md);
            }
            
            .main-header, .main-content {
                left: 0;
                margin-left: 0;
                width: 100%;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 1005;
                display: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }
            
            .header-btn, .toggle-sidebar {
                width: 44px;
                height: 44px;
            }
            
            .search-dropdown {
                position: fixed;
                top: var(--header-height);
                left: 1rem;
                right: 1rem;
                width: auto;
                max-height: 80vh;
            }
        }

        @media (max-width: 768px) {
            .header-search {
                position: static;
                margin: 0 0.5rem;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .search-input {
                width: 40px;
                background-position: center;
                padding-left: 40px;
                transition: all 0.3s ease;
                cursor: pointer;
                background-color: transparent;
            }
            
            .search-input:focus {
                width: 100%;
                background-color: var(--input-bg);
                cursor: text;
            }
            
            .user-dropdown-toggle {
                padding: 0.25rem;
            }
            
            .user-name, .user-role {
                display: none;
            }
            
            .sidebar-link {
                padding: 1rem 1.5rem;
            }
            
            .header-btn, .toggle-sidebar {
                margin: 0 0.2rem;
            }
            
            .header-nav {
                margin-left: auto;
                gap: 0.3rem;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 0.75rem;
            }
            
            .page-title {
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }
            
            .header-btn, .toggle-sidebar {
                width: 50px;
                height: 50px;
            }
            
            .dashboard-card {
                margin-bottom: 1rem;
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

        /* Accessibility improvements */
        :focus-visible {
            outline: 3px solid var(--accent-color) !important;
            outline-offset: 2px !important;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3) !important;
        }
        
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--accent-color);
            color: white;
            padding: 8px;
            z-index: 9999;
            transition: top 0.3s;
        }
        
        .skip-link:focus {
            top: 0;
        }

        /* Language selector styles */
        .lang-selector {
            position: relative;
        }
        
        .current-lang {
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .lang-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 160px;
            background: var(--card-bg);
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            margin-top: 10px;
            display: none;
            z-index: 1000;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        
        .lang-dropdown.show {
            display: block;
        }
        
        .lang-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition-default);
        }
        
        .lang-item:hover {
            background-color: var(--bg-light);
        }
        
        .lang-item:not(:last-child) {
            border-bottom: 1px solid var(--border-color);
        }
        
        .lang-flag {
            font-size: 1.2rem;
        }
        
        .lang-name {
            font-size: 0.9rem;
        }
        
        /* RTL Support pour l'arabe */
        [dir="rtl"] {
            font-family: 'Tajawal', 'Open Sans', sans-serif;
        }
        
        [dir="rtl"] .sidebar {
            right: 0;
            left: auto;
        }
        
        [dir="rtl"] .main-header {
            right: var(--sidebar-width);
            left: 0;
        }
        
        [dir="rtl"] .main-content {
            margin-right: var(--sidebar-width);
            margin-left: 0;
        }
        
        [dir="rtl"].sidebar-collapsed {
            right: calc(-1 * var(--sidebar-width) + 60px);
        }
        
        [dir="rtl"] .main-header.full-width {
            right: 60px;
        }
        
        [dir="rtl"] .main-content.full-width {
            margin-right: 60px;
        }
    </style>
</head>
<body>
    <!-- Skip link for keyboard users -->
    <a href="#mainContent" class="skip-link">Aller au contenu principal</a>
    
    <!-- Sidebar overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('home') }}" class="sidebar-brand">
            <span>JUNIA Maroc</span>
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

    <!-- Header -->
    <header class="main-header" id="header">
        <!-- Hamburger menu -->
        <div class="toggle-sidebar" id="toggleSidebar" tabindex="0" role="button" aria-label="Afficher/masquer la barre latérale" aria-expanded="true">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </div>
        
        <!-- Search bar -->
        <div class="header-search">
            <i class="fas fa-search search-icon" aria-hidden="true"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Rechercher..." autocomplete="off" aria-label="Barre de recherche">
            
            <!-- Search dropdown with categories -->
            <div class="search-dropdown" id="searchDropdown" role="menu" aria-labelledby="searchInput">
                @auth
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'directeur')
                        <div class="search-category">Utilisateurs</div>
                        <ul class="search-options">
                            <li class="search-option" data-url="{{ route('eleves.index') }}">
                                <i class="fas fa-user-graduate"></i>Élèves
                            </li>
                            <li class="search-option" data-url="{{ route('professeurs.index') }}">
                                <i class="fas fa-chalkboard-teacher"></i>Professeurs
                            </li>
                            @if(auth()->user()->role === 'admin')
                                <li class="search-option" data-url="{{ route('directeurs.index') }}">
                                    <i class="fas fa-user-tie"></i>Directeurs pédagogiques
                                </li>
                                <li class="search-option" data-url="{{ route('admins.index') }}">
                                    <i class="fas fa-user-shield"></i>Administrateurs
                                </li>
                            @endif
                        </ul>
                        
                        <div class="search-category">Enseignement</div>
                        <ul class="search-options">
                            <li class="search-option" data-url="{{ route('matieres.index') }}">
                                <i class="fas fa-book"></i>Matières
                            </li>
                            <li class="search-option" data-url="{{ route('unites-enseignement.index') }}">
                                <i class="fas fa-cube"></i>Unités d'enseignement
                            </li>
                            <li class="search-option" data-url="{{ route('classes.index') }}">
                                <i class="fas fa-users"></i>Classes
                            </li>
                        </ul>
                        
                        <div class="search-category">Évaluations</div>
                        <ul class="search-options">
                            <li class="search-option" data-url="{{ route('evaluations.index') }}">
                                <i class="fas fa-pen"></i>Évaluations
                            </li>
                            <li class="search-option" data-url="{{ route('evaluation-params.index') }}">
                                <i class="fas fa-cog"></i>Paramètres d'évaluation
                            </li>
                            <li class="search-option" data-url="{{ route('evaluations.rattrapage') }}">
                                <i class="fas fa-redo"></i>Rattrapages
                            </li>
                            <li class="search-option" data-url="{{ route('bulletins.index') }}">
                                <i class="fas fa-file-alt"></i>Bulletins
                            </li>
                        </ul>
                    @elseif(auth()->user()->role === 'professeur')
                        <div class="search-category">Enseignement</div>
                        <ul class="search-options">
                            <li class="search-option" data-url="{{ route('matieres.index') }}">
                                <i class="fas fa-book"></i>Matières enseignées
                            </li>
                            <li class="search-option" data-url="{{ route('classes.index') }}">
                                <i class="fas fa-users"></i>Classes
                            </li>
                        </ul>
                        
                        <div class="search-category">Évaluations</div>
                        <ul class="search-options">
                            <li class="search-option" data-url="{{ route('evaluations.index') }}">
                                <i class="fas fa-pen"></i>Évaluations
                            </li>
                            <li class="search-option" data-url="{{ route('evaluations.rattrapage') }}">
                                <i class="fas fa-redo"></i>Rattrapages
                            </li>
                        </ul>
                    @elseif(auth()->user()->role === 'eleve')
                        <div class="search-category">Enseignement</div>
                        <ul class="search-options">
                            <li class="search-option" data-url="{{ route('matieres.index') }}">
                                <i class="fas fa-book"></i>Mes matières
                            </li>
                        </ul>
                        
                        <div class="search-category">Résultats</div>
                        <ul class="search-options">
                            <li class="search-option" data-url="{{ route('evaluations.index') }}">
                                <i class="fas fa-pen"></i>Mes évaluations
                            </li>
                            <li class="search-option" data-url="{{ route('bulletins.index') }}">
                                <i class="fas fa-file-alt"></i>Mes bulletins
                            </li>
                        </ul>
                    @endif
                @endauth
            </div>
        </div>
        
        <!-- Right navigation -->
        <div class="header-nav">
            <!-- Language selector -->
            <div class="header-btn lang-selector" id="langSelectorBtn" tabindex="0" role="button" aria-label="Changer de langue" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-globe" aria-hidden="true"></i>
                <span class="current-lang d-none d-md-inline-block ms-1">FR</span>
                
                <div class="lang-dropdown" id="langDropdown">
                    <a href="#" class="lang-item" data-lang="fr">
                        <span class="lang-flag">🇫🇷</span>
                        <span class="lang-name">Français</span>
                    </a>
                    <a href="#" class="lang-item" data-lang="en">
                        <span class="lang-flag">🇬🇧</span>
                        <span class="lang-name">English</span>
                    </a>
                    <a href="#" class="lang-item" data-lang="ar">
                        <span class="lang-flag">🇲🇦</span>
                        <span class="lang-name">العربية</span>
                    </a>
                </div>
            </div>
            
            <!-- Theme toggle button -->
            <div class="header-btn" id="themeToggleBtn" tabindex="0" role="button" aria-label="Changer de thème" aria-pressed="false">
                <i class="fas fa-moon" id="darkIcon" aria-hidden="true"></i>
                <i class="fas fa-sun" id="lightIcon" style="display: none;" aria-hidden="true"></i>
            </div>
            
            <!-- Notification button -->
            <div class="header-btn" tabindex="0" role="button" aria-label="Notifications" aria-haspopup="true">
                <i class="fas fa-bell" aria-hidden="true"></i>
                <span class="notification-badge" aria-hidden="true"></span>
            </div>
            
            <!-- Settings button with dropdown -->
            <div class="header-btn" id="settingsBtn" tabindex="0" role="button" aria-label="Paramètres" aria-expanded="false" aria-haspopup="true">
                <i class="fas fa-cog" aria-hidden="true"></i>
                
                <div class="settings-dropdown" id="settingsDropdown">
                    <a href="{{ route('profile.edit') }}" class="settings-item">
                        <i class="fas fa-user-cog"></i>
                        <span>Paramètres du profil</span>
                    </a>
                    <a href="#" class="settings-item">
                        <i class="fas fa-palette"></i>
                        <span>Apparence</span>
                    </a>
                    <a href="#" class="settings-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                    <a href="#" class="settings-item">
                        <i class="fas fa-lock"></i>
                        <span>Confidentialité</span>
                    </a>
                    <a href="#" class="settings-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Aide & Support</span>
                    </a>
                </div>
            </div>

            <!-- User profile -->
            <div class="user-dropdown">
                <div class="user-dropdown-toggle" id="userDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <a href="{{ route('profile.edit') }}" style="display: block; margin-right: 8px;" onclick="event.stopPropagation();">
                        <img src="{{ asset('storage/images/default-avatar.png') }}" alt="Avatar" class="user-avatar">
                    </a>
                    <div class="user-info d-none d-md-flex">
                        <span class="user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                        <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>
                <!-- User dropdown menu -->
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
        </div>
    </header>

    <!-- Main content -->
    <main class="main-content" id="mainContent">
            @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Theme Toggler
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const darkIcon = document.getElementById('darkIcon');
            const lightIcon = document.getElementById('lightIcon');
            
            // Vérifie le thème sauvegardé ou la préférence système
            function getThemePreference() {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme) {
                    return savedTheme;
                }
                // Vérifie la préférence système pour le mode sombre
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            
            // Applique le thème
            function applyTheme(theme) {
                if (theme === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    darkIcon.style.display = 'none';
                    lightIcon.style.display = 'block';
                } else {
                    document.documentElement.removeAttribute('data-theme');
                    darkIcon.style.display = 'block';
                    lightIcon.style.display = 'none';
                }
            }
            
            // Initialise le thème
            const currentTheme = getThemePreference();
            applyTheme(currentTheme);
            
            // Événement pour basculer le thème
            themeToggleBtn.addEventListener('click', function() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                // Sauvegarde le thème
                localStorage.setItem('theme', newTheme);
                
                // Applique le nouveau thème
                applyTheme(newTheme);
            });
            
            // Toggle sidebar avec gestion mobile
            const toggleSidebar = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const header = document.getElementById('header');
            const mainContent = document.getElementById('mainContent');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            toggleSidebar.addEventListener('click', function() {
                const isMobile = window.innerWidth < 992;
                
                if (isMobile) {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('sidebar-collapsed');
                    header.classList.toggle('full-width');
                    mainContent.classList.toggle('full-width');
                }
                
                const isExpanded = isMobile 
                    ? sidebar.classList.contains('show') 
                    : !sidebar.classList.contains('sidebar-collapsed');
                    
                this.setAttribute('aria-expanded', isExpanded);
            });
            
            // Fermer sidebar en cliquant sur l'overlay
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                toggleSidebar.setAttribute('aria-expanded', 'false');
            });
            
            // Fermer sidebar en cliquant sur un lien (mobile)
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        toggleSidebar.setAttribute('aria-expanded', 'false');
                    }
                });
            });
            
            // Ajuster le comportement en fonction du redimensionnement
            window.addEventListener('resize', function() {
                const isMobile = window.innerWidth < 992;
                
                if (!isMobile) {
                    sidebarOverlay.classList.remove('show');
                    if (sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    sidebar.classList.remove('sidebar-collapsed');
                    }
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    header.classList.remove('full-width');
                    mainContent.classList.remove('full-width');
                }
            });
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const searchDropdown = document.getElementById('searchDropdown');
            
            // Show/hide search dropdown
            searchInput.addEventListener('focus', function() {
                searchDropdown.classList.add('show');
            });
            
            // Hide dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!searchInput.contains(event.target) && !searchDropdown.contains(event.target)) {
                    searchDropdown.classList.remove('show');
                }
            });
            
            // Search filter functionality
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const options = document.querySelectorAll('.search-option');
                
                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(query)) {
                        option.style.display = 'flex';
                    } else {
                        option.style.display = 'none';
                    }
                });
                
                // Show/hide category headers if all options are hidden
                document.querySelectorAll('.search-category').forEach(category => {
                    const nextOptions = category.nextElementSibling;
                    const hasVisibleOptions = Array.from(nextOptions.children).some(option => option.style.display !== 'none');
                    category.style.display = hasVisibleOptions ? 'block' : 'none';
                });
            });
            
            // Navigate to selected option
            document.querySelectorAll('.search-option').forEach(option => {
                option.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    if (url) {
                        window.location.href = url;
                    }
                });
            });
            
            // Settings dropdown
            const settingsBtn = document.getElementById('settingsBtn');
            const settingsDropdown = document.getElementById('settingsDropdown');
            
            settingsBtn.addEventListener('click', function(event) {
                event.stopPropagation();
                settingsDropdown.classList.toggle('show');
            });
            
            document.addEventListener('click', function(event) {
                if (!settingsBtn.contains(event.target) && !settingsDropdown.contains(event.target)) {
                    settingsDropdown.classList.remove('show');
                }
            });
            
            // Dropdown submenu functionality (for sidebar)
            const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');
            
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const dropdown = parent.querySelector('.sidebar-dropdown');
                    
                    // Close all other dropdowns
                    document.querySelectorAll('.sidebar-dropdown.show').forEach(dropdown => {
                        if (!parent.contains(dropdown)) {
                            dropdown.classList.remove('show');
                        }
                    });
                    
                    dropdown.classList.toggle('show');
                });
            });

            // Keyboard navigation for theme toggler
            themeToggleBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
            
            // Mise à jour du bouton de thème avec ARIA
            function updateThemeButtonState(theme) {
                const isPressed = theme === 'dark';
                themeToggleBtn.setAttribute('aria-pressed', isPressed);
            }
            
            // Mise à jour de l'état ARIA avec le thème
            function applyTheme(theme) {
                if (theme === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    darkIcon.style.display = 'none';
                    lightIcon.style.display = 'block';
                } else {
                    document.documentElement.removeAttribute('data-theme');
                    darkIcon.style.display = 'block';
                    lightIcon.style.display = 'none';
                }
                updateThemeButtonState(theme);
            }
            
            // Toggle sidebar with keyboard
            toggleSidebar.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
            
            // Update ARIA state when toggling sidebar
            toggleSidebar.addEventListener('click', function() {
                const isExpanded = !sidebar.classList.contains('sidebar-collapsed');
                this.setAttribute('aria-expanded', isExpanded);
            });
            
            // Settings dropdown keyboard navigation
            settingsBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                } else if (e.key === 'Escape' && settingsDropdown.classList.contains('show')) {
                    settingsDropdown.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                    this.focus();
                }
            });
            
            // Update ARIA state for settings dropdown
            settingsBtn.addEventListener('click', function() {
                const isExpanded = settingsDropdown.classList.contains('show');
                this.setAttribute('aria-expanded', !isExpanded);
            });
            
            // Close dropdown with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (searchDropdown.classList.contains('show')) {
                        searchDropdown.classList.remove('show');
                        searchInput.focus();
                    }
                    if (settingsDropdown.classList.contains('show')) {
                        settingsDropdown.classList.remove('show');
                        settingsBtn.setAttribute('aria-expanded', 'false');
                        settingsBtn.focus();
                    }
                }
            });

            // Language selector
            const langSelectorBtn = document.getElementById('langSelectorBtn');
            const langDropdown = document.getElementById('langDropdown');
            const currentLang = document.querySelector('.current-lang');
            const langItems = document.querySelectorAll('.lang-item');
            
            // Afficher/masquer le menu langue
            langSelectorBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                langDropdown.classList.toggle('show');
                this.setAttribute('aria-expanded', langDropdown.classList.contains('show'));
            });
            
            // Fermer le menu langue en cliquant ailleurs
            document.addEventListener('click', function(e) {
                if (!langSelectorBtn.contains(e.target)) {
                    langDropdown.classList.remove('show');
                    langSelectorBtn.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Gérer la navigation clavier pour le sélecteur de langue
            langSelectorBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    langDropdown.classList.toggle('show');
                    this.setAttribute('aria-expanded', langDropdown.classList.contains('show'));
                } else if (e.key === 'Escape' && langDropdown.classList.contains('show')) {
                    langDropdown.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Changer de langue
            langItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const lang = this.getAttribute('data-lang');
                    
                    // Mettre à jour l'affichage
                    currentLang.textContent = lang.toUpperCase();
                    
                    // Stocker la préférence de langue
                    localStorage.setItem('language', lang);
                    
                    // Appliquer la direction RTL pour l'arabe
                    if (lang === 'ar') {
                        document.documentElement.setAttribute('dir', 'rtl');
                    } else {
                        document.documentElement.removeAttribute('dir');
                    }
                    
                    // Fermer le dropdown
                    langDropdown.classList.remove('show');
                    langSelectorBtn.setAttribute('aria-expanded', 'false');
                    
                    // Ici, vous pourriez rediriger vers la version localisée
                    // window.location.href = /${lang}${window.location.pathname};
                });
            });
            
            // Charger la préférence de langue au chargement
            document.addEventListener('DOMContentLoaded', function() {
                const savedLang = localStorage.getItem('language');
                if (savedLang) {
                    currentLang.textContent = savedLang.toUpperCase();
                    
                    // Appliquer RTL pour l'arabe
                    if (savedLang === 'ar') {
                        document.documentElement.setAttribute('dir', 'rtl');
                    }
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>