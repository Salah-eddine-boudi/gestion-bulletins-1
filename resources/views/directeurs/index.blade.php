@extends('layouts.app')

@section('content')

<div class="container">
    <h3 class="mb-4">Liste des Directeurs Pédagogiques</h3>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <a href="{{ route('directeurs.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-user-plus"></i> Ajouter un Directeur
    </a>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
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
                            <tr>
                                <td>{{ $directeur->id }}</td>
                                <td class="text-center">
                                    @if ($directeur->user && $directeur->user->photo)
                                        <img src="{{ asset('storage/' . $directeur->user->photo) }}" 
                                             alt="Photo de {{ $directeur->user->nom }}" 
                                             class="rounded-circle" 
                                             width="50" height="50" 
                                             style="object-fit: cover; border: 2px solid #ccc;">
                                    @else
                                        <img src="{{ asset('storage/profile_pictures/default.png') }}" 
                                             alt="Photo par défaut" 
                                             class="rounded-circle" 
                                             width="50" height="50" 
                                             style="object-fit: cover; border: 2px solid #ccc;">
                                    @endif
                                </td>
                                <td>
                                    @if ($directeur->user)
                                        <strong>{{ $directeur->user->prenom }} {{ $directeur->user->nom }}</strong>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($directeur->user)
                                        <a href="mailto:{{ $directeur->user->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope text-muted"></i> {{ $directeur->user->email }}
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
                                        <a href="{{ route('directeurs.edit', $directeur) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form action="{{ route('directeurs.destroy', $directeur) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Voulez-vous vraiment supprimer ce directeur ?');">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    @if($directeurs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        Affichage de {{ $directeurs->firstItem() }} à {{ $directeurs->lastItem() }} sur {{ $directeurs->total() }} directeurs
                    @else
                        {{ count($directeurs) }} directeur(s) au total
                    @endif
                </div>
                <div>
                    {{ $directeurs->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
