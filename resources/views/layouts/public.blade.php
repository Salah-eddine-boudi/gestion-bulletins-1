<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Junia Maroc') }}</title>

    {{-- Dépendances --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root{
            --clr-bg:#F7F9FA;
            --clr-primary:#1F2937;          /* gris anthracite */
            --clr-sec:#0D9488;              /* vert pétrole   */
            --clr-light:#ffffff;
            --sidebar-w:250px;
            --header-h:58px;
            --tr:all .3s ease;
            --shadow:0 4px 12px rgba(0,0,0,.08);
        }
        *{transition:var(--tr)}
        body{margin:0;font-family:'Open Sans',sans-serif;background:var(--clr-bg);display:flex;overflow-x:hidden}
        /* SIDEBAR */
        .sidebar{
            position:fixed;inset-block:0;left:0;width:var(--sidebar-w);
            background:var(--clr-primary);color:#fff;box-shadow:var(--shadow);
            padding-top:var(--header-h);z-index:1000
        }
        .sidebar.collapsed{left:-200px;width:60px}
        .sidebar a{display:flex;align-items:center;gap:.75rem;padding:.7rem 1rem;color:#fff;text-decoration:none;font-size:.925rem}
        .sidebar a:hover,.sidebar a.active{background:rgba(255,255,255,.08)}
        .sidebar .icon{width:22px;text-align:center}
        .brand img{max-height:34px;object-fit:contain}
        .dropdown-links{overflow:hidden;max-height:0}
        .dropdown-links.show{max-height:500px}
        /* HEADER */
        .header{
            position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--header-h);
            background:var(--clr-light);box-shadow:var(--shadow);display:flex;align-items:center;padding:0 1rem;z-index:999
        }
        .header.collapsed{left:60px}
        .toggle-btn{background:none;border:none;font-size:1.2rem;color:var(--clr-primary);margin-right:1rem}
        .searchbox{flex:1;max-width:500px;position:relative}
        .searchbox input{
            width:100%;padding:.45rem 1rem;border:1px solid #d1d5db;border-radius:20px;background:#EDF2F7
        }
        .header-actions button{background:none;border:none;font-size:1.1rem;color:var(--clr-primary);margin-left:.6rem;position:relative}
        .header-actions .badge{position:absolute;top:2px;right:2px;width:8px;height:8px;background:var(--clr-sec);border-radius:50%}
        /* CONTENT */
        .content{margin-top:var(--header-h);margin-left:var(--sidebar-w);padding:1.8rem;width:100%}
        .content.collapsed{margin-left:60px}
        /* RESPONSIVE */
        @media(max-width:992px){
            .sidebar{left:-var(--sidebar-w)}
            .sidebar.show{left:0}
            .header,.content{left:0;margin-left:0}
            .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:900}
            .overlay.show{display:block}
        }
    </style>
</head>
<body>
    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('storage/image/JUNIA.png') }}" alt="Logo">
            <span class="d-none d-md-inline fw-semibold">JUNIA Maroc</span>
        </a>

        <ul class="mt-3 px-0">
            <li><a href="{{ route('home') }}"   class="{{ request()->routeIs('home')?'active':'' }}"><span class="icon"><i class="fas fa-home"></i></span>Accueil</a></li>
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard')?'active':'' }}"><span class="icon"><i class="fas fa-chart-line"></i></span>Tableau de bord</a></li>

            @auth
            @php $r=Auth::user()->role; @endphp

            @if(in_array($r,['admin','directeur']))
            <li>
                <a href="#" class="dropdown-toggle"><span class="icon"><i class="fas fa-users"></i></span>Utilisateurs<i class="ms-auto fas fa-chevron-down"></i></a>
                <div class="dropdown-links ps-4">
                    <a href="{{ route('eleves.index') }}">Élèves</a>
                    <a href="{{ route('professeurs.index') }}">Professeurs</a>
                    <a href="{{ route('directeurs.index') }}">Directeurs</a>
                    @if($r==='admin')<a href="{{ route('admins.index') }}">Admins</a>@endif
                </div>
            </li>
            @endif

            <li>
                <a href="#" class="dropdown-toggle"><span class="icon"><i class="fas fa-graduation-cap"></i></span>Pédagogie<i class="ms-auto fas fa-chevron-down"></i></a>
                <div class="dropdown-links ps-4">
                    @if($r==='admin')
                        <a href="{{ route('matieres.index') }}">Matières</a>
                        <a href="{{ route('unites-enseignement.index') }}">Unités d'enseignement</a>
                    @endif
                    @if(in_array($r,['admin','directeur','professeur','eleve']))
                        <a href="{{ route('evaluations.index') }}">Évaluations</a>
                        <a href="{{ route('evaluations.rattrapage') }}">Rattrapage</a>
                        <a href="{{ route('bulletins.index') }}">Bulletins</a>
                    @endif
                </div>
            </li>
            @endauth

            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact')?'active':'' }}"><span class="icon"><i class="fas fa-envelope"></i></span>Contact</a></li>
        </ul>
    </aside>

    {{-- HEADER --}}
    <header class="header" id="header">
        <button class="toggle-btn" id="btn-toggle"><i class="fas fa-bars"></i></button>

        {{-- Barre de recherche "go-to" --}}
        <div class="searchbox">
            <input list="pages" id="searchGo" placeholder="Aller à la page…" autocomplete="off">
            <datalist id="pages">
                @foreach([
                    'Accueil' => route('home'),
                    'Tableau de bord' => route('dashboard'),
                    'Élèves' => route('eleves.index'),
                    'Professeurs' => route('professeurs.index'),
                    'Directeurs' => route('directeurs.index'),
                    'Admins' => route('admins.index'),
                    'Matières' => route('matieres.index'),
                    'Unités' => route('unites-enseignement.index'),
                    'Évaluations' => route('evaluations.index'),
                    'Rattrapage' => route('evaluations.rattrapage'),
                    'Bulletins' => route('bulletins.index'),
                    'Contact' => route('contact')
                ] as $lbl=>$url)
                    <option value="{{ $lbl }}" data-url="{{ $url }}"></option>
                @endforeach
            </datalist>
        </div>

        <div class="header-actions ms-auto">
            <button><i class="fas fa-bell"></i><span class="badge"></span></button>
            <button><i class="fas fa-cog"></i></button>
            {{-- profil --}}
            @auth
            <div class="dropdown d-inline">
                <button class="border-0 bg-transparent" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->photo?asset('storage/'.Auth::user()->photo):asset('storage/profile_pictures/default.png') }}" width="32" height="32" class="rounded-circle">
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="px-3 py-2"><strong>{{ Auth::user()->nom }}</strong><br><small>{{ Auth::user()->email }}</small></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="dropdown-item"><i class="fas fa-sign-out-alt me-2 text-danger"></i>Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">Se connecter</a>
            @endauth
        </div>
    </header>

    {{-- Overlay pour mobile --}}
    <div class="overlay" id="overlay"></div>

    {{-- CONTENT --}}
    <main class="content" id="content">
        @yield('content')
    </main>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function(){
        const sidebar  = document.getElementById('sidebar'),
              header   = document.getElementById('header'),
              content  = document.getElementById('content'),
              overlay  = document.getElementById('overlay'),
              toggle   = document.getElementById('btn-toggle'),
              searchGo = document.getElementById('searchGo'),
              pages    = [...document.querySelectorAll('#pages option')];

        /* --- Sidebar --- */
        toggle.onclick = ()=> {
            if (window.innerWidth > 992){
                sidebar.classList.toggle('collapsed');
                header.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
            }else{
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        };
        overlay.onclick = ()=>{ sidebar.classList.remove('show'); overlay.classList.remove('show'); };

        /* Dropdown logic */
        document.querySelectorAll('.dropdown-toggle').forEach(t=>{
            t.onclick=e=>{
                e.preventDefault();
                t.nextElementSibling.classList.toggle('show');
            };
        });

        /* --- Search "go‑to" --- */
        searchGo.addEventListener('change',e=>{
            const opt = pages.find(o=>o.value.toLowerCase()===e.target.value.toLowerCase());
            if(opt){ window.location.href = opt.dataset.url; }
        });
        searchGo.addEventListener('keydown',e=>{
            if(e.key==='Enter'){
                const term = e.target.value.toLowerCase();
                const opt  = pages.find(o=>o.value.toLowerCase().includes(term));
                if(opt){ window.location.href = opt.dataset.url; }
            }
        });
    })();
    </script>
</body>
</html>
