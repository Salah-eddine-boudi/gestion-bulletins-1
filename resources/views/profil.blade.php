<nav class="navbar navbar-expand-lg animate__animated animate__fadeInDown">
    <div class="container">
        <!-- Bouton / Nom de l'application -->
        <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
            JUNIA Maroc
        </a>

        <!-- Bouton hamburger en mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Liens de navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Lien page d'accueil -->
                <li class="nav-item mx-2">
                    <a class="nav-link text-dark" href="{{ route('home') }}">Accueil</a>
                </li>

                <!-- Si l'utilisateur n'est pas connecté -->
                @guest
                    <li class="nav-item mx-2">
                        <a class="btn btn-outline-primary" href="{{ route('login') }}">Se Connecter</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="btn btn-primary" href="{{ route('register') }}">S'inscrire</a>
                    </li>
                @else
                    <!-- Si l'utilisateur est connecté, affichage d'un avatar (sans image) + dropdown -->
                    <li class="nav-item dropdown mx-2">
                        @php
                            // Utiliser l'initiale du nom
                            $initial = strtoupper(substr(Auth::user()->nom, 0, 1));
                        @endphp
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-2"
                                 style="width: 35px; height: 35px; font-weight: bold;">
                                {{ $initial }}
                            </div>
                            <span>{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</span> <!-- ✅ Affichage corrigé -->
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <!-- Utiliser la route 'profile.edit' au lieu de 'profile' -->
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    Mon Profil
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger"
                                   href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Formulaire de déconnexion -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </ul>
        </div>
    </div>
</nav>
