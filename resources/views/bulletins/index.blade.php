{{-- resources/views/bulletins/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold text-primary mb-0">Liste des Élèves</h1>
            <p class="text-muted">Consultez les bulletins et les informations académiques</p>
        </div>
    </div>

    {{-- Filtrage --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('bulletins.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label for="niveau" class="form-label fw-medium mb-2">Niveau scolaire</label>
                        <select name="niveau" id="niveau" class="form-select" onchange="this.form.submit()">
                            <option value="">Tous les niveaux</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau }}" {{ request('niveau') == $niveau ? 'selected' : '' }}>
                                    {{ $niveau }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="search" class="form-label fw-medium mb-2">Rechercher un élève</label>
                        <input type="text" class="form-control" id="search" name="search"
                               placeholder="Nom ou prénom" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Desktop table --}}
    <div class="card shadow-sm border-0 mb-4 d-none d-md-block">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Résultats <span class="text-muted">({{ $eleves->count() }} élèves)</span></h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Élève</th>
                            <th>Niveau</th>
                            <th>Évaluations</th>
                            <th>Crédits validés</th>
                            <th>Moyenne</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eleves as $eleve)
                            @php
                                $count = $eleve->evaluations->count();
                                $creditsValides = $eleve->evaluations->sum(fn($e) => $e->matiere->credits ?? 0);
                                $moy = $eleve->evaluations->whereNotNull('note')->avg('note');
                                $moyenne = $moy ? number_format($moy, 2) : null;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle rounded-circle bg-primary text-white me-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px">
                                            {{ strtoupper(substr($eleve->user->prenom,0,1).substr($eleve->user->nom,0,1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $eleve->user->nom }} {{ $eleve->user->prenom }}</h6>
                                            <small class="text-muted">{{ $eleve->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">{{ $eleve->niveau_scolaire }}</span>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $count }}</span>
                                    <span class="text-muted">{{ Str::plural('évaluation', $count) }}</span>
                                    @if($count)
                                        <a href="#" class="ms-2 small text-decoration-none"
                                           data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true"
                                           data-bs-content="
                                             @foreach($eleve->evaluations->take(5) as $e)
                                               <div class='mb-2'>
                                                 <strong>{{ $e->matiere->libelle ?? '—' }}</strong><br>
                                                 <small>Note: {{ $e->note ?? '—' }}</small>
                                               </div>
                                             @endforeach
                                             @if($count>5)
                                               <div class='text-center'><em>et {{ $count-5 }} autres...</em></div>
                                             @endif
                                           ">
                                            <i class="fas fa-info-circle"></i> Détails
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $creditsValides }}</span> crédits
                                </td>
                                <td>
                                    @if($moyenne)
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width:60px; height:8px">
                                                <div class="progress-bar bg-{{ $moyenne>=14 ? 'success' : ($moyenne>=10 ? 'primary' : 'warning') }}"
                                                     role="progressbar"
                                                     style="width:{{ min(($moyenne/20)*100,100) }}%">
                                                </div>
                                            </div>
                                            <span class="fw-medium">{{ $moyenne }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">Non calculée</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('bulletins.show',$eleve->id_eleve) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-alt me-1"></i> Voir bulletin
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Aucun élève trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="d-block d-md-none">
        @forelse($eleves as $eleve)
            @php
                $count = $eleve->evaluations->count();
                $creditsValides = $eleve->evaluations->sum(fn($e) => $e->matiere->credits ?? 0);
                $moy = $eleve->evaluations->whereNotNull('note')->avg('note');
                $moyenne = $moy ? number_format($moy, 2) : null;
            @endphp
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="width:50px;height:50px">
                            <div class="avatar-circle rounded-circle bg-primary text-white w-100 h-100 d-flex align-items-center justify-content-center">
                                {{ strtoupper(substr($eleve->user->prenom,0,1).substr($eleve->user->nom,0,1)) }}
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $eleve->user->nom }} {{ $eleve->user->prenom }}</h5>
                            <small class="text-muted">{{ $eleve->user->email }}</small>
                        </div>
                    </div>
                    <p class="mb-1"><strong>Niveau :</strong> {{ $eleve->niveau_scolaire }}</p>
                    <p class="mb-1">
                        <strong>Évaluations :</strong> {{ $count }}
                        @if($count)
                            <a href="#" class="ms-2 small" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true"
                               data-bs-content="
                                 @foreach($eleve->evaluations->take(5) as $e)
                                   <div class='mb-2'>
                                     <strong>{{ $e->matiere->libelle ?? '—' }}</strong><br>
                                     <small>Note: {{ $e->note ?? '—' }}</small>
                                   </div>
                                 @endforeach
                                 @if($count>5)
                                   <div class='text-center'><em>et {{ $count-5 }} autres...</em></div>
                                 @endif
                               "><i class="fas fa-info-circle"></i></a>
                        @endif
                    </p>
                    <p class="mb-1"><strong>Crédits :</strong> {{ $creditsValides }}</p>
                    <p class="mb-3">
                        <strong>Moyenne :</strong>
                        @if($moyenne)
                            {{ $moyenne }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </p>
                    <a href="{{ route('bulletins.show',$eleve->id_eleve) }}"
                       class="btn btn-primary w-100 btn-sm">
                        <i class="fas fa-file-alt me-1"></i> Voir bulletin
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                Aucun élève trouvé.
            </div>
        @endforelse

        @if(method_exists($eleves,'links'))
            <div class="d-flex justify-content-center mt-3">
                {{ $eleves->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.forEach(function(el) {
            new bootstrap.Popover(el);
        });
    });
</script>
@endpush

<style>
    :root {
        --primary-color: #4B2E83;
    }
    .text-primary { color: var(--primary-color) !important; }
    .bg-primary { background-color: var(--primary-color) !important; }
    .bg-primary-subtle {
        background-color: rgba(75, 46, 131, 0.1) !important;
    }
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-primary:hover {
        background-color: #3a2165;
    }
    .avatar-circle { font-weight: 600; }
</style>
@endsection
