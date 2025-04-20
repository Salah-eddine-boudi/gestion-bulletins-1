@extends('layouts.app')

@section('content')

@if (Auth::check())

<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">Liste des Élèves</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('eleves.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> Ajouter un Élève
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                @if($eleves->count() > 0)
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Photo</th>
                                <th scope="col">Nom & Prénom</th>
                                <th scope="col">Email</th>
                                <th scope="col">Niveau</th>
                                <th scope="col">Spécialité</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eleves as $eleve)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-light text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                @if ($eleve->user && $eleve->user->photo)
                                                    <img src="{{ asset('storage/' . $eleve->user->photo) }}" alt="{{ $eleve->user->nom }}" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $eleve->user->nom ?? 'Inconnu' }} {{ $eleve->user->prenom ?? '' }}</div>
                                                <small class="text-muted">Matricule: {{ $eleve->matricule }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $eleve->user->email ?? '#' }}" class="text-decoration-none">
                                            <i class="fas fa-envelope me-1 text-muted"></i>
                                            {{ $eleve->user->email ?? 'Non défini' }}
                                        </a>
                                    </td>
                                    <td>{{ $eleve->niveau_scolaire }}</td>
                                    <td>{{ $eleve->specialite ?? 'Non spécifié' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('eleves.show', $eleve->id_eleve) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i> Voir
                                            </a>
                                            <a href="{{ route('eleves.edit', $eleve->id_eleve) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit me-1"></i> Modifier
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $eleve->id_eleve }}">
                                                <i class="fas fa-trash me-1"></i> Supprimer
                                            </button>
                                        </div>
                                      
                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="deleteModal{{ $eleve->id_eleve }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $eleve->id_eleve }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $eleve->id_eleve }}">Confirmation de suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir supprimer l'élève <strong>{{ $eleve->user->prenom ?? '' }} {{ $eleve->user->nom ?? 'Inconnu' }}</strong> ?</p>
                                                        <p class="text-danger"><small>Cette action est irréversible et supprimera toutes les données associées à cet élève.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('eleves.destroy', $eleve->id_eleve) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if(method_exists($eleves, 'links'))
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                @if(method_exists($eleves, 'firstItem'))
                                    Affichage de {{ $eleves->firstItem() }} à {{ $eleves->lastItem() }} sur {{ $eleves->total() }} élèves
                                @else
                                    Affichage de {{ $eleves->count() }} élèves
                                @endif
                            </div>
                            <div>
                                {{ $eleves->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                @else
                    <p class="text-center text-muted">Aucun élève trouvé.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@else
<!-- Message d'accès interdit -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Accès interdit</h4>
                </div>
                <div class="card-body text-center py-5">
                    <p class="lead mb-4">Vous devez être connecté pour accéder à cette page.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-lock me-2"></i> Se connecter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection