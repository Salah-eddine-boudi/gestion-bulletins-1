@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold text-primary">Liste des évaluations</h4>
            @if(auth()->user()->role !== 'eleve')
            <a href="{{ route('evaluations.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Nouvelle évaluation
            </a>
            @endif
        </div>
        
        <div class="card-body">
            <!-- Formulaire de filtrage -->
            <form action="{{ route('evaluations.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="niveau" class="form-label text-secondary fw-medium">Niveau Scolaire</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-graduation-cap text-muted"></i>
                            </span>
                            <select name="niveau" id="niveau" class="form-select">
                                <option value="">Tous les niveaux</option>
                                @foreach($niveaux as $niveau)
                                    <option value="{{ $niveau }}" {{ request('niveau') == $niveau ? 'selected' : '' }}>
                                        {{ $niveau }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="id_matiere" class="form-label text-secondary fw-medium">Matière</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-book text-muted"></i>
                            </span>
                            <select name="id_matiere" id="id_matiere" class="form-select">
                                <option value="">Toutes les matières</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id_matiere }}" 
                                        {{ request('id_matiere') == $matiere->id_matiere ? 'selected' : '' }}>
                                        {{ $matiere->intitule }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i> Filtrer
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Table responsive -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold">Nom de l'élève</th>
                            <th class="fw-semibold">Matière</th>
                            <th class="fw-semibold text-center">Note</th>
                            <th class="fw-semibold text-center">Présence</th>
                            <th class="fw-semibold">Type d'évaluation</th>
                            <th class="fw-semibold">Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                            @php
                                $noteClass = '';
                                if ($evaluation->note < 10) {
                                    $noteClass = 'text-danger';
                                } elseif ($evaluation->note >= 15) {
                                    $noteClass = 'text-success';
                                }

                                $presenceClass = $evaluation->presence == 'Présent' ? 'badge bg-success' : 'badge bg-danger';
                            @endphp
                            <tr>
                                <td class="fw-medium">{{ $evaluation->eleve->user->prenom }} {{ $evaluation->eleve->user->nom }}</td>
                                <td>{{ $evaluation->matiere->intitule }}</td>
                                <td class="text-center {{ $noteClass }} fw-bold">{{ $evaluation->note }}</td>
                                <td class="text-center">
                                    <span class="{{ $presenceClass }}">{{ $evaluation->presence }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $evaluation->type }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($evaluation->date_evaluation)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        @if(auth()->user()->role !== 'eleve')
                                        <a href="{{ route('evaluations.edit', $evaluation->id_evaluation) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('evaluations.show', $evaluation->id_evaluation) }}" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->role !== 'eleve')
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $evaluation->id_evaluation }}" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                    
                                    @if(auth()->user()->role !== 'eleve')
                                    <!-- Modal de confirmation de suppression -->
                                    <div class="modal fade" id="deleteModal{{ $evaluation->id_evaluation }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmation de suppression</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer cette évaluation?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('evaluations.destroy', $evaluation->id_evaluation) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-search me-2"></i> Aucune évaluation trouvée pour ce filtre.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    @if($evaluations->total() > 0)
                        Affichage de {{ $evaluations->firstItem() }} à {{ $evaluations->lastItem() }} sur {{ $evaluations->total() }} résultats
                    @else
                        Aucun résultat
                    @endif
                </div>
                <div>
                    {{ $evaluations->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Activer les tooltips Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
