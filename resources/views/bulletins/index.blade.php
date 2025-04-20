@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold text-primary mb-0">Liste des Élèves</h1>
            <p class="text-muted">Consultez les bulletins et les informations académiques</p>
        </div>
      
    </div>

    <!-- Carte de filtrage -->
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('bulletins.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label for="niveau" class="form-label fw-medium mb-2">Niveau scolaire</label>
                        <select name="niveau" id="niveau" class="form-select" onchange="this.form.submit()">
                            <option value="">Tous les niveaux</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau }}" {{ request('niveau') == $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="search" class="form-label fw-medium mb-2">Rechercher un élève</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Nom ou prénom" value="{{ request('search') }}">
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

    <!-- Liste des élèves -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Résultats <span class="text-muted">({{ $eleves->count() }} élèves)</span></h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-3 py-3">Élève</th>
                            <th class="px-3 py-3">Niveau</th>
                            <th class="px-3 py-3">évaluations</th>
                            <th class="px-3 py-3">Crédits validés</th>
                            <th class="px-3 py-3">Moyenne</th>
                            <th class="px-3 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eleves as $eleve)
                            <tr>
                                <td class="px-3 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle rounded-circle bg-primary text-white me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px">
                                            {{ substr($eleve->user->prenom, 0, 1) }}{{ substr($eleve->user->nom, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $eleve->user->nom }} {{ $eleve->user->prenom }}</h6>
                                            <small class="text-muted">{{ $eleve->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    <span class="badge bg-primary-subtle text-primary">{{ $eleve->niveau_scolaire }}</span>
                                </td>
                                <td class="px-3 py-3">
                                    @php $count = $eleve->evaluations->count(); @endphp
                                    <span class="fw-medium">{{ $count }}</span> 
                                    <span class="text-muted">{{ Str::plural('évaluations', $count) }}</span>
                                    
                                    <!-- Détails en popover -->
                                    @if($count > 0)
                                        <a href="#" class="ms-2 small text-decoration-none" data-bs-toggle="popover" data-bs-placement="top" data-bs-html="true" 
                                        data-bs-content="
                                        @foreach($eleve->evaluations->take(5) as $evaluation)
                                            <div class='mb-2'>
                                                <strong>{{ $evaluation->matiere->libelle ?? 'Module non renseigné' }}</strong><br>
                                                <small>Note: {{ $evaluation->note ?? 'Non attribuée' }}</small>
                                            </div>
                                        @endforeach
                                        @if($count > 5)
                                            <div class='text-center'><em>et {{ $count - 5 }} autres...</em></div>
                                        @endif
                                        ">
                                            <i class="fas fa-info-circle"></i> Détails
                                        </a>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    @php
                                        $creditsValidés = $eleve->evaluations->sum(function ($evaluation) {
                                            return $evaluation->matiere->credits ?? 0;
                                        });
                                    @endphp
                                    <span class="fw-medium">{{ $creditsValidés }}</span> 
                                    <span class="text-muted">crédits</span>
                                </td>
                                <td class="px-3 py-3">
                                    @php
                                        $moyenne = $eleve->evaluations->whereNotNull('note')->avg('note');
                                        $moyenne = $moyenne ? number_format($moyenne, 2) : 'N/A';
                                    @endphp
                                    
                                    @if($moyenne !== 'N/A')
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-{{ $moyenne >= 14 ? 'success' : ($moyenne >= 10 ? 'primary' : 'warning') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(($moyenne/20)*100, 100) }}%"></div>
                                            </div>
                                            <span class="fw-medium">{{ $moyenne }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">Non calculée</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <a href="{{ route('bulletins.show', $eleve->id_eleve) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-alt me-1"></i> Voir bulletin
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="py-5">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h5>Aucun élève trouvé</h5>
                                        <p class="text-muted">Essayez de modifier vos critères de recherche</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });
    });
</script>

<style>
    :root {
        --primary-color: #4B2E83;
        --secondary-color: #FF5F1F;
    }
    
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }
    
    .text-primary {
        color: var(--primary-color) !important;
    }
    
    .bg-primary {
        background-color: var(--primary-color) !important;
    }
    
    .bg-primary-subtle {
        background-color: rgba(75, 46, 131, 0.1) !important;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(75, 46, 131, 0.25);
    }
    
    .table th {
        font-weight: 600;
        color: #344767;
    }
    
    .table tr {
        border-bottom: 1px solid #f0f2f5;
    }
    
    .table tr:last-child {
        border-bottom: none;
    }
    
    .avatar-circle {
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endsection