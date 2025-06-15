{{-- resources/views/directeurs/index.blade.php --}}
@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Liste des Directeurs Pédagogiques</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <a href="{{ route('directeurs.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-user-plus me-1"></i> Ajouter un Directeur
    </a>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Desktop table --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark text-white">
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Nom Complet</th>
                            <th>Email</th>
                            <th>Date de Prise de Fonction</th>
                            <th>Date de Fin de Mandat</th>
                            <th>Téléphone</th>
                            <th>Bureau</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($directeurs as $directeur)
                            @php($user = $directeur->user)
                            <tr>
                                <td>{{ $directeur->id }}</td>
                                <td class="text-center">
                                    @if($user && $user->photo)
                                        <img src="{{ asset('storage/'.$user->photo) }}"
                                             alt="" class="rounded-circle"
                                             width="50" height="50"
                                             style="object-fit:cover;border:2px solid #ccc;">
                                    @else
                                        <img src="{{ asset('storage/profile_pictures/default.png') }}"
                                             alt="" class="rounded-circle"
                                             width="50" height="50"
                                             style="object-fit:cover;border:2px solid #ccc;">
                                    @endif
                                </td>
                                <td>
                                    @if($user)
                                        <strong>{{ $user->prenom }} {{ $user->nom }}</strong>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user)
                                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope text-muted me-1"></i>
                                            {{ $user->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $directeur->date_prise_fonction }}</td>
                                <td>{{ $directeur->date_fin_mandat ?? 'N/A' }}</td>
                                <td>{{ $directeur->tel ?? 'N/A' }}</td>
                                <td>{{ $directeur->bureau ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('directeurs.edit',$directeur) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit me-1"></i> Modifier
                                        </a>
                                        <form action="{{ route('directeurs.destroy',$directeur) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce directeur ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash me-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="d-block d-md-none">
                @foreach($directeurs as $directeur)
                    @php($user = $directeur->user)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3" style="width:50px;height:50px;">
                                    @if($user && $user->photo)
                                        <img src="{{ asset('storage/'.$user->photo) }}"
                                             class="rounded-circle w-100 h-100" style="object-fit:cover;" alt="">
                                    @else
                                        <img src="{{ asset('storage/profile_pictures/default.png') }}"
                                             class="rounded-circle w-100 h-100" style="object-fit:cover;" alt="">
                                    @endif
                                </div>
                                <h5 class="mb-0">{{ optional($user)->prenom }} {{ optional($user)->nom }}</h5>
                            </div>
                            <p class="mb-1"><strong>ID :</strong> {{ $directeur->id }}</p>
                            <p class="mb-1">
                                <strong>Email :</strong>
                                @if($user)
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                @else
                                    N/A
                                @endif
                            </p>
                            <p class="mb-1"><strong>Prise de fonction :</strong> {{ $directeur->date_prise_fonction }}</p>
                            <p class="mb-1"><strong>Fin de mandat :</strong> {{ $directeur->date_fin_mandat ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Téléphone :</strong> {{ $directeur->tel ?? 'N/A' }}</p>
                            <p class="mb-3"><strong>Bureau :</strong> {{ $directeur->bureau ?? 'N/A' }}</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('directeurs.edit',$directeur) }}"
                                   class="btn btn-warning flex-fill">
                                    <i class="fas fa-edit me-1"></i> Modifier
                                </a>
                                <form action="{{ route('directeurs.destroy',$directeur) }}"
                                      method="POST" class="flex-fill"
                                      onsubmit="return confirm('Supprimer ce directeur ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-1"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($directeurs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Affichage de {{ $directeurs->firstItem() }} à {{ $directeurs->lastItem() }}
                        sur {{ $directeurs->total() }} directeurs
                    </div>
                    <div>
                        {{ $directeurs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
