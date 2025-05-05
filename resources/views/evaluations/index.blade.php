{{-- resources/views/evaluations/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold text-primary">Évaluations</h4>
            @if(auth()->user()->role !== 'eleve')
                <a href="{{ route('evaluations.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i> Nouvelle évaluation
                </a>
            @endif
        </div>

        <div class="card-body">
            {{-- Filtre --}}
            <form action="{{ route('evaluations.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <label for="niveau" class="form-label text-muted fw-medium">Niveau</label>
                    <select name="niveau" id="niveau" class="form-select">
                        <option value="">Tous niveaux</option>
                        @foreach($niveaux as $niveau)
                            <option value="{{ $niveau }}"{{ request('niveau') == $niveau ? ' selected' : '' }}>
                                {{ $niveau }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label for="id_matiere" class="form-label text-muted fw-medium">Matière</label>
                    <select name="id_matiere" id="id_matiere" class="form-select">
                        <option value="">Toutes matières</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id_matiere }}"{{ request('id_matiere') == $matiere->id_matiere ? ' selected' : '' }}>
                                {{ $matiere->intitule }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4 d-grid">
                    <button type="submit" class="btn btn-outline-primary mt-4">
                        <i class="fas fa-filter me-2"></i> Filtrer
                    </button>
                </div>
            </form>

            {{-- Table desktop --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Élève</th>
                            <th>Matière</th>
                            <th class="text-center">Note</th>
                            <th class="text-center">Présence</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $eval)
                            <tr>
                                <td>{{ $eval->eleve->user->prenom }} {{ $eval->eleve->user->nom }}</td>
                                <td>{{ $eval->matiere->intitule }}</td>
                                <td class="text-center fw-semibold">{{ number_format($eval->note,2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $eval->presence }}</span>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $eval->type }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($eval->date_evaluation)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('evaluations.show', $eval->id_evaluation) }}" class="btn btn-outline-info" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->role !== 'eleve')
                                            <a href="{{ route('evaluations.edit', $eval->id_evaluation) }}" class="btn btn-outline-primary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $eval->id_evaluation }}" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                    @if(auth()->user()->role !== 'eleve')
                                    <div class="modal fade" id="deleteModal{{ $eval->id_evaluation }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Supprimer évaluation</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    Confirmer la suppression de cette évaluation ?
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <form action="{{ route('evaluations.destroy', $eval->id_evaluation) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Oui</button>
                                                    </form>
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Non</button>
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
                                    <i class="fas fa-info-circle me-2"></i> Aucun résultat trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Cartes mobile --}}
            <div class="d-block d-md-none">
                @foreach($evaluations as $eval)
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $eval->eleve->user->prenom }} {{ $eval->eleve->user->nom }}</strong>
                                    <div class="text-muted">{{ $eval->matiere->intitule }}</div>
                                </div>
                                <div class="text-end">
                                    <span class="fw-semibold">{{ number_format($eval->note,2) }}</span><br>
                                    <small class="badge bg-light text-dark">{{ $eval->presence }}</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small><i class="fas fa-tag me-1"></i>{{ $eval->type }}</small>
                                <small><i class="fas fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($eval->date_evaluation)->format('d/m/Y') }}</small>
                            </div>
                            <div class="mt-3 text-end">
                                <a href="{{ route('evaluations.show', $eval->id_evaluation) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye me-1"></i>Détails
                                </a>
                                @if(auth()->user()->role !== 'eleve')
                                    <a href="{{ route('evaluations.edit', $eval->id_evaluation) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($evaluations->isEmpty())
                    <p class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                        Aucun résultat trouvé
                    </p>
                @endif
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    @if($evaluations->total())
                        Affichage de {{ $evaluations->firstItem() }} à {{ $evaluations->lastItem() }} sur {{ $evaluations->total() }} résultats
                    @else
                        Aucun résultat
                    @endif
                </div>
                <div>
                    {{ $evaluations->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // initialiser les tooltips si besoin
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el=>{
        new bootstrap.Tooltip(el);
    });
});
</script>
@endpush

@push('styles')
<style>
.text-primary { color: #4B0082!important; }
.card-header .btn-success { font-size: .875rem; }
@media print {
    .btn, .card-header .btn { display: none!important; }
}
</style>
@endpush
