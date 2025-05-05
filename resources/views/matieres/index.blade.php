{{-- resources/views/matieres/index.blade.php --}}
@extends('layouts.app')

@section('content')
@if (Auth::check())
<div class="container py-4">
    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Matières</h2>
        <a href="{{ route('matieres.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i> Ajouter
        </a>
    </div>

    {{-- Message de succès --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">Filtres</div>
        <div class="card-body">
            <form method="GET" action="{{ route('matieres.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="filiere" class="form-label">Filière</label>
                    <select name="filiere" id="filiere" class="form-select">
                        <option value="">Toutes</option>
                        <option value="Tronc Commun" {{ request('filiere') == 'Tronc Commun' ? 'selected' : '' }}>Tronc Commun</option>
                        <option value="HEI"           {{ request('filiere') == 'HEI'           ? 'selected' : '' }}>HEI</option>
                        <option value="ISEN"         {{ request('filiere') == 'ISEN'         ? 'selected' : '' }}>ISEN</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="annee_universitaire" class="form-label">Année universitaire</label>
                    <select name="annee_universitaire" id="annee_universitaire" class="form-select">
                        <option value="">Toutes</option>
                        <option value="2022-2023" {{ request('annee_universitaire') == '2022-2023' ? 'selected' : '' }}>2022-2023</option>
                        <option value="2021-2022" {{ request('annee_universitaire') == '2021-2022' ? 'selected' : '' }}>2021-2022</option>
                        <option value="2020-2021" {{ request('annee_universitaire') == '2020-2021' ? 'selected' : '' }}>2020-2021</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="semestre" class="form-label">Semestre</label>
                    <select name="semestre" id="semestre" class="form-select">
                        <option value="">Tous</option>
                        <option value="S1" {{ request('semestre') == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ request('semestre') == 'S2' ? 'selected' : '' }}>S2</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('matieres.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-sync-alt me-1"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Version Desktop --}}
    <div class="table-responsive d-none d-md-block">
        @if($matieres->count())
        <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Intitulé</th>
                    <th>Code</th>
                    <th>Année</th>
                    <th>Semestre</th>
                    <th>Filière</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matieres as $matiere)
                <tr>
                    <td>{{ $matiere->id_matiere }}</td>
                    <td>{{ $matiere->intitule }}</td>
                    <td><span class="badge bg-light text-dark">{{ $matiere->code }}</span></td>
                    <td>{{ $matiere->annee_universitaire }}</td>
                    <td>{{ $matiere->semestre }}</td>
                    <td><span class="badge bg-secondary">{{ $matiere->filiere }}</span></td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('matieres.show', $matiere->id_matiere) }}" class="btn btn-outline-primary" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('matieres.edit', $matiere->id_matiere) }}" class="btn btn-outline-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $matiere->id_matiere }}" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <!-- Modal confirmation -->
                        <div class="modal fade" id="deleteModal{{ $matiere->id_matiere }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Supprimer matière</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        Confirmer la suppression de <strong>{{ $matiere->intitule }}</strong> ?
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <form action="{{ route('matieres.destroy', $matiere->id_matiere) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Oui</button>
                                        </form>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Non</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-center text-muted py-4">Aucune matière trouvée.</p>
        @endif
    </div>

    {{-- Version Mobile --}}
    <div class="d-block d-md-none">
        @forelse($matieres as $matiere)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h5 class="card-title mb-0">{{ $matiere->intitule }}</h5>
                    <span class="badge bg-secondary">{{ $matiere->code }}</span>
                </div>
                <p class="mb-1"><strong>ID :</strong> {{ $matiere->id_matiere }}</p>
                <p class="mb-1"><strong>Année :</strong> {{ $matiere->annee_universitaire }}</p>
                <p class="mb-1"><strong>Semestre :</strong> {{ $matiere->semestre }}</p>
                <p class="mb-3"><strong>Filière :</strong> <span class="badge bg-secondary">{{ $matiere->filiere }}</span></p>
                <div class="d-flex gap-2">
                    <a href="{{ route('matieres.show', $matiere->id_matiere) }}" class="btn btn-sm btn-outline-primary flex-fill">Voir</a>
                    <a href="{{ route('matieres.edit', $matiere->id_matiere) }}" class="btn btn-sm btn-outline-warning flex-fill">Modifier</a>
                    <button type="button" class="btn btn-sm btn-outline-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteModalMobile{{ $matiere->id_matiere }}">
                        Supprimer
                    </button>
                </div>

                <!-- Modal mobile -->
                <div class="modal fade" id="deleteModalMobile{{ $matiere->id_matiere }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Supprimer matière</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                Confirmer la suppression de <strong>{{ $matiere->intitule }}</strong> ?
                            </div>
                            <div class="modal-footer justify-content-center">
                                <form action="{{ route('matieres.destroy', $matiere->id_matiere) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Oui</button>
                                </form>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Non</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <p class="text-center text-muted py-4">Aucune matière trouvée.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if(method_exists($matieres, 'links'))
    <div class="mt-4 d-flex justify-content-center">
        {{ $matieres->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@else
    @include('partials.auth_required')
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (el) {
            return new bootstrap.Tooltip(el);
        });
    });
</script>
@endpush
