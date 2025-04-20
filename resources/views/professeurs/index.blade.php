@extends('layouts.app')

@section('content')
@if (Auth::check())
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">Liste des Professeurs</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('professeurs.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> Ajouter un professeur
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nom Complet</th>
                            <th scope="col">Email</th>
                            <th scope="col">Spécialité</th>
                            <th scope="col">Grade</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($professeurs as $professeur)
                            <tr>
                                <!-- Affichage de la photo -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            @if ($professeur->user->photo)
                                                <img src="{{ asset('storage/' . $professeur->user->photo) }}" alt="{{ $professeur->user->prenom }}" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fas fa-user"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $professeur->user->prenom }} {{ $professeur->user->nom }}</div>
                                            <small class="text-muted">ID: {{ $professeur->id_prof }}</small>
                                        </div>
                                    </div>
                                </td>
                                <!-- Affichage de l'email -->
                                <td>
                                    <a href="mailto:{{ $professeur->user->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1 text-muted"></i>
                                        {{ $professeur->user->email }}
                                    </a>
                                </td>
                                <!-- Affichage de la spécialité -->
                                <td>{{ $professeur->specialite }}</td>
                                <!-- Affichage du grade -->
                                <td>{{ $professeur->grade }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('professeurs.show', $professeur->id_prof) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i> Voir
                                        </a>
                                        <a href="{{ route('professeurs.edit', $professeur->id_prof) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit me-1"></i> Modifier
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $professeur->id_prof }}">
                                            <i class="fas fa-trash me-1"></i> Supprimer
                                        </button>
                                    </div>
                                    
                                    <!-- Modal de confirmation de suppression -->
                                    <div class="modal fade" id="deleteModal{{ $professeur->id_prof }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $professeur->id_prof }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $professeur->id_prof }}">Confirmation de suppression</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer le professeur <strong>{{ $professeur->user->prenom }} {{ $professeur->user->nom }}</strong> ?</p>
                                                    <p class="text-danger"><small>Cette action est irréversible et supprimera toutes les données associées à ce professeur.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('professeurs.destroy', $professeur->id_prof) }}" method="POST">
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
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i> Aucun professeur trouvé
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(method_exists($professeurs, 'links'))
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        @if(method_exists($professeurs, 'total'))
                            Affichage de {{ $professeurs->firstItem() }} à {{ $professeurs->lastItem() }} sur {{ $professeurs->total() }} professeurs
                        @else
                            {{ count($professeurs) }} professeur(s) au total
                        @endif
                    </div>
                    <div>
                        {{ $professeurs->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@else
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activer les tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
