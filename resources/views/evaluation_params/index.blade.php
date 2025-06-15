{{-- resources/views/evaluation-params/index.blade.php --}}
@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('head')
  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  {{-- DataTables pour un meilleur filtrage et tri --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <style>
    .card-header-custom {
      border-left: 4px solid #0d6efd;
      background-color: #f8f9fc;
    }
    .param-card {
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
    }
    .param-card:hover {
      transform: translateY(-2px);
      border-left-color: #0d6efd;
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.1)!important;
    }
    .badge-pill {
      border-radius: 50rem;
      padding: 0.35em 0.65em;
    }
    .dataTables_filter, .dataTables_length {
      margin-bottom: 1rem;
    }
    .actions-col {
      width: 100px;
    }
    .percentage-bar {
      height: 4px;
      width: 100%;
      background-color: #e9ecef;
      margin-top: 3px;
      border-radius: 2px;
      overflow: hidden;
    }
    .percentage-value {
      height: 100%;
      background-color: #0d6efd;
    }
  </style>
@endsection

@section('content')
<div class="container py-4">
  <div class="card shadow-sm mb-4">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center py-3">
      <div>
        <h4 class="mb-0 fw-bold text-primary">
          <i class="bi bi-sliders me-2"></i>Paramétrages d'évaluations
        </h4>
        <p class="text-muted small mb-0">Gérez les configurations d'évaluation des matières</p>
      </div>
      @if(auth()->user()->role === 'professeur')
        <a href="{{ route('evaluation-params.create') }}" class="btn btn-success">
          <i class="bi bi-plus-circle me-1"></i>
          Nouveau paramétrage
        </a>
      @endif
    </div>

    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
      @endif

      {{-- Filtres --}}
      <div class="mb-4 bg-light p-3 rounded">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="searchInput" class="form-label small text-muted mb-1">Recherche globale</label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
              <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
            </div>
          </div>
          <div class="col-md-4">
            <label for="filterType" class="form-label small text-muted mb-1">Filtre par type</label>
            <select id="filterType" class="form-select">
              <option value="">Tous les types</option>
              <option value="contrôle">Contrôle</option>
              <option value="projet">Projet</option>
              <option value="examen">Examen</option>
            </select>
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button id="resetFilters" class="btn btn-outline-secondary w-100">
              <i class="bi bi-x-circle me-1"></i>Réinitialiser les filtres
            </button>
          </div>
        </div>
      </div>

      {{-- TABLEAU VISIBLE SUR MD+ --}}
      <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0" id="evaluationParamsTable">
          <thead class="table-light">
            <tr>
              <th class="text-center">#</th>
              <th>Professeur</th>
              <th>Matière</th>
              <th class="text-center">Type</th>
              <th class="text-center">Épreuves</th>
              <th>Pourcentages</th>
              <th class="text-center">Override</th>
              <th class="text-center actions-col">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($params as $param)
              <tr class="param-row">
                <td class="text-center fw-bold">{{ $param->id_config }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                      {{ strtoupper(substr($param->professeur->user->prenom, 0, 1)) }}
                    </div>
                    <div>
                      <div class="fw-medium">{{ $param->professeur->user->prenom }} {{ $param->professeur->user->nom }}</div>
                      <small class="text-muted">ID: {{ $param->id_professeur }}</small>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="fw-medium">{{ $param->matiere->intitule }}</div>
                  <small class="text-muted">ID: {{ $param->id_matiere }}</small>
                </td>
                <td class="text-center">
                  @php
                    $badgeClass = match($param->type) {
                      'contrôle' => 'bg-info',
                      'projet' => 'bg-warning',
                      'examen' => 'bg-success',
                      default => 'bg-secondary'
                    };
                  @endphp
                  <span class="badge {{ $badgeClass }} badge-pill">{{ $param->type }}</span>
                </td>
                <td class="text-center">
                  <span class="badge bg-light text-dark">{{ $param->nombre_evaluations }}</span>
                </td>
                <td>
                  <div class="small mb-1">
                    <span class="fw-medium">Standard:</span> 
                    <span class="float-end">{{ number_format($param->pourcentage, 2) }}%</span>
                    <div class="percentage-bar">
                      <div class="percentage-value" style="width: {{ $param->pourcentage }}%;"></div>
                    </div>
                  </div>
                  <div class="small mb-1">
                    <span class="fw-medium">Rattrapage:</span>
                    <span class="float-end">{{ number_format($param->pourcentage_rattrapage, 2) }}%</span>
                    <div class="percentage-bar">
                      <div class="percentage-value" style="width: {{ $param->pourcentage_rattrapage }}%; background-color: #ffc107;"></div>
                    </div>
                  </div>
                  <div class="small">
                    <span class="fw-medium">Examen final:</span>
                    <span class="float-end">{{ number_format($param->pourcentage_examen_final, 2) }}%</span>
                    <div class="percentage-bar">
                      <div class="percentage-value" style="width: {{ $param->pourcentage_examen_final }}%; background-color: #20c997;"></div>
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  @if($param->override_rattrapage)
                    <span class="badge bg-warning text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Le coefficient de rattrapage remplace la note standard">
                      <i class="bi bi-check-circle-fill me-1"></i>Oui
                    </span>
                  @else
                    <span class="badge bg-light text-muted">Non</span>
                  @endif
                </td>
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="{{ route('evaluation-params.edit', $param->id_config) }}">
                          <i class="bi bi-pencil-fill text-primary me-2"></i>Modifier
                        </a>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('evaluation-params.destroy', $param->id_config) }}" method="POST" class="delete-form">
                          @csrf @method('DELETE')
                          <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-trash-fill me-2"></i>Supprimer
                          </button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-5">
                  <div class="text-muted">
                    <i class="bi bi-clipboard-x fs-2 d-block mb-3"></i>
                    <p class="mb-0">Aucun paramétrage trouvé</p>
                    @if(auth()->user()->role === 'professeur')
                      <a href="{{ route('evaluation-params.create') }}" class="btn btn-sm btn-primary mt-2">
                        Créer un paramétrage
                      </a>
                    @endif
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- CARTES VISIBLE SUR XS/SM --}}
      <div class="d-block d-md-none">
        <div class="row g-3" id="mobileCards">
          @forelse($params as $param)
            <div class="col-12 param-card-mobile">
              <div class="card shadow-sm param-card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title mb-1">
                      {{ $param->professeur->user->prenom }}
                      {{ $param->professeur->user->nom }}
                    </h5>
                    @php
                      $badgeClass = match($param->type) {
                        'contrôle' => 'bg-info',
                        'projet' => 'bg-warning',
                        'examen' => 'bg-success',
                        default => 'bg-secondary'
                      };
                    @endphp
                    <span class="badge {{ $badgeClass }} badge-pill">{{ $param->type }}</span>
                  </div>
                  
                  <div class="d-flex flex-wrap mb-2 text-muted">
                    <div class="me-3">
                      <small><i class="bi bi-hash me-1"></i>ID: {{ $param->id_config }}</small>
                    </div>
                    <div>
                      <small><i class="bi bi-person-badge me-1"></i>Prof ID: {{ $param->id_professeur }}</small>
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <div class="fw-medium mb-1"><i class="bi bi-book me-1"></i>{{ $param->matiere->intitule }}</div>
                    <small class="text-muted">ID Matière: {{ $param->id_matiere }}</small>
                  </div>
                  
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <div><i class="bi bi-card-list me-1"></i>Nb épreuves:</div>
                    <span class="badge bg-light text-dark">{{ $param->nombre_evaluations }}</span>
                  </div>
                  
                  <div class="card bg-light mb-3">
                    <div class="card-body py-2 px-3">
                      <div class="mb-2">
                        <div class="d-flex justify-content-between">
                          <small class="fw-medium">Standard:</small>
                          <small>{{ number_format($param->pourcentage,2) }}%</small>
                        </div>
                        <div class="progress" style="height: 4px;">
                          <div class="progress-bar" style="width: {{ $param->pourcentage }}%"></div>
                        </div>
                      </div>
                      
                      <div class="mb-2">
                        <div class="d-flex justify-content-between">
                          <small class="fw-medium">Rattrapage:</small>
                          <small>{{ number_format($param->pourcentage_rattrapage,2) }}%</small>
                        </div>
                        <div class="progress" style="height: 4px;">
                          <div class="progress-bar bg-warning" style="width: {{ $param->pourcentage_rattrapage }}%"></div>
                        </div>
                      </div>
                      
                      <div>
                        <div class="d-flex justify-content-between">
                          <small class="fw-medium">Examen final:</small>
                          <small>{{ number_format($param->pourcentage_examen_final,2) }}%</small>
                        </div>
                        <div class="progress" style="height: 4px;">
                          <div class="progress-bar bg-success" style="width: {{ $param->pourcentage_examen_final }}%"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <div><i class="bi bi-toggle-on me-1"></i>Override rattrapage:</div>
                    @if($param->override_rattrapage)
                      <span class="badge bg-warning text-dark">Oui</span>
                    @else
                      <span class="badge bg-light text-muted">Non</span>
                    @endif
                  </div>
                  
                  <div class="d-flex justify-content-between">
                    <a href="{{ route('evaluation-params.edit', $param->id_config) }}"
                       class="btn btn-sm btn-primary">
                      <i class="bi bi-pencil-fill me-1"></i> Modifier
                    </a>
                    <form action="{{ route('evaluation-params.destroy', $param->id_config) }}"
                          method="POST" class="delete-form">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash-fill me-1"></i> Supprimer
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="text-center py-5 bg-light rounded">
                <i class="bi bi-clipboard-x fs-2 d-block mb-3 text-muted"></i>
                <p class="text-muted mb-2">Aucun paramétrage trouvé</p>
                @if(auth()->user()->role === 'professeur')
                  <a href="{{ route('evaluation-params.create') }}" class="btn btn-sm btn-primary mt-2">
                    <i class="bi bi-plus-circle me-1"></i>Créer un paramétrage
                  </a>
                @endif
              </div>
            </div>
          @endforelse
        </div>
      </div>

      {{-- Pagination --}}
      <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
        <div class="small text-muted mb-2 mb-md-0">
          @if($params->total() > 0)
            Affichage {{ $params->firstItem() }} à {{ $params->lastItem() }}
            sur {{ $params->total() }} paramétrages
          @else
            Aucun résultat
          @endif
        </div>
        <div>
          {{ $params->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Initialiser les tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    
    // Confirmation avant suppression
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Êtes-vous sûr de vouloir supprimer ce paramétrage ? Cette action est irréversible.')) {
          this.submit();
        }
      });
    });
    
    // Variables pour le filtrage
    const searchInput = document.getElementById('searchInput');
    const filterType = document.getElementById('filterType');
    const resetFilters = document.getElementById('resetFilters');
    const dataTable = $('#evaluationParamsTable'); // Pour la version desktop
    let table; // Variable pour stocker l'instance DataTable
    
    // Filtrage universel (fonctionne pour mobile ET desktop)
    function applyFilters() {
      const searchTerm = searchInput.value.toLowerCase();
      const typeFilter = filterType.value.toLowerCase();
      
      // Filtrage pour version mobile
      document.querySelectorAll('.col-12.param-card-mobile').forEach(card => {
        const cardText = card.textContent.toLowerCase();
        const typeElement = card.querySelector('.badge');
        const cardType = typeElement ? typeElement.textContent.toLowerCase() : '';
        
        const matchesSearch = searchTerm === '' || cardText.includes(searchTerm);
        const matchesType = typeFilter === '' || cardType === typeFilter;
        
        // Afficher ou masquer la carte selon les filtres
        card.style.display = (matchesSearch && (matchesType || typeFilter === '')) ? '' : 'none';
      });
      
      // Filtrage pour version desktop (si DataTable est initialisé)
      if (table) {
        // Recherche globale via DataTable
        table.search(searchTerm).draw();
        
        // Filtre personnalisé par type
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
          // Si aucun filtre de type n'est appliqué
          if (typeFilter === '') return true;
          
          // Le type est dans la 4ème colonne (index 3)
          const rowType = data[3].toLowerCase();
          return rowType.includes(typeFilter);
        });
        
        table.draw(); // Redessiner la table avec le filtre appliqué
        
        // Nettoyer le filtre personnalisé après utilisation
        $.fn.dataTable.ext.search.pop();
      }
    }
    
    // Événements pour le filtrage
    if (searchInput) {
      searchInput.addEventListener('input', applyFilters);
    }
    
    if (filterType) {
      filterType.addEventListener('change', applyFilters);
    }
    
    if (resetFilters) {
      resetFilters.addEventListener('click', () => {
        searchInput.value = '';
        filterType.value = '';
        applyFilters();
      });
    }
    
    // Initialisation de DataTable pour la version desktop
    if (typeof $.fn.DataTable === 'function' && dataTable.length > 0) {
      table = dataTable.DataTable({
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        responsive: true,
        paging: false,
        info: false,
        dom: 't', // Uniquement afficher le tableau (pas de contrôles DataTable)
        columnDefs: [
          { orderable: false, targets: [7] }
        ],
        initComplete: function() {
          // Désactiver la recherche interne de DataTable 
          // puisque nous gérons notre propre recherche
          $('.dataTables_filter').hide();
        }
      });
    }
  });
</script>
@endpush