@extends('layouts.app')

@section('content')
@if (Auth::check()) 
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">Liste des Matières</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('matieres.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Ajouter une Matière
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Filtres de recherche</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('matieres.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="filiere" class="form-label">Filière</label>
                    <select name="filiere" id="filiere" class="form-select">
                        <option value="">Toutes les filières</option>
                        <option value="Tronc Commun" {{ $filiere == 'Tronc Commun' ? 'selected' : '' }}>Tronc Commun</option>
                        <option value="HEI" {{ $filiere == 'HEI' ? 'selected' : '' }}>HEI</option>
                        <option value="ISEN" {{ $filiere == 'ISEN' ? 'selected' : '' }}>ISEN</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="annee_universitaire" class="form-label">Année universitaire</label>
                    <select name="annee_universitaire" id="annee_universitaire" class="form-select">
                        <option value="">Toutes les années</option>
                        <option value="2022-2023" {{ $annee_universitaire == '2022-2023' ? 'selected' : '' }}>2022-2023</option>
                        <option value="2021-2022" {{ $annee_universitaire == '2021-2022' ? 'selected' : '' }}>2021-2022</option>
                        <option value="2020-2021" {{ $annee_universitaire == '2020-2021' ? 'selected' : '' }}>2020-2021</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="semestre" class="form-label">Semestre</label>
                    <select name="semestre" id="semestre" class="form-select">
                        <option value="">Tous les semestres</option>
                        <option value="S1" {{ $semestre == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ $semestre == 'S2' ? 'selected' : '' }}>S2</option>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('matieres.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-redo me-1"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Intitulé</th>
                            <th>Code</th>
                            <th>Année Universitaire</th>
                            <th>Semestre</th>
                            <th>Filière</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matieres as $matiere)
                        <tr>
                            <td>{{ $matiere->id_matiere }}</td>
                            <td>{{ $matiere->intitule }}</td>
                            <td><span class="badge bg-secondary">{{ $matiere->code }}</span></td>
                            <td>{{ $matiere->annee_universitaire }}</td>
                            <td>{{ $matiere->semestre }}</td>
                            <td>
                                @if($matiere->filiere == 'Tronc Commun')
                                    <span class="badge bg-primary">{{ $matiere->filiere }}</span>
                                @elseif($matiere->filiere == 'HEI')
                                    <span class="badge bg-success">{{ $matiere->filiere }}</span>
                                @elseif($matiere->filiere == 'ISEN')
                                    <span class="badge bg-info">{{ $matiere->filiere }}</span>
                                @else
                                    {{ $matiere->filiere }}
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('matieres.show', $matiere->id_matiere) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye me-1"></i> Voir
                                    </a>
                                    <a href="{{ route('matieres.edit', $matiere->id_matiere) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i> Modifier
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $matiere->id_matiere }}">
                                        <i class="fas fa-trash me-1"></i> Supprimer
                                    </button>
                                </div>
                                
                                <!-- Modal de confirmation de suppression -->
                                <div class="modal fade" id="deleteModal{{ $matiere->id_matiere }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $matiere->id_matiere }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $matiere->id_matiere }}">Confirmation de suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir supprimer la matière <strong>{{ $matiere->intitule }}</strong> ?</p>
                                                <p class="text-danger"><small>Cette action est irréversible.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('matieres.destroy', $matiere->id_matiere) }}" method="POST">
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
                            <td colspan="7" class="text-center py-4">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i> Aucune matière trouvée
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination avec vérification du type -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    @if(method_exists($matieres, 'total'))
                        Affichage de {{ $matieres->firstItem() }} à {{ $matieres->lastItem() }} sur {{ $matieres->total() }} matières
                    @else
                        {{ count($matieres) }} matière(s) au total
                    @endif
                </div>
                <div>
                    @if(method_exists($matieres, 'links'))
                        {{ $matieres->appends(request()->query())->links('pagination::bootstrap-4') }}
                    @endif
                </div>
            </div>
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