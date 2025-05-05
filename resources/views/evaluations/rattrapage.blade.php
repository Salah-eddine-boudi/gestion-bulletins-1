{{-- resources/views/evaluations/rattrapage.blade.php --}}
@extends('layouts.app')

@section('head')
  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-4">

  {{-- Entête + bouton --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold text-primary mb-0">Évaluations de rattrapage</h4>
    @if(auth()->user()->role !== 'eleve')
      <a href="{{ route('evaluations.createRatt') }}" class="btn btn-sm btn-success">
        <i class="bi bi-plus-circle me-1"></i> Ajouter un rattrapage
      </a>
    @endif
  </div>

  {{-- Filtre --}}
  <form action="{{ route('evaluations.rattrapage') }}" method="GET" class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <label for="niveau" class="form-label">Niveau scolaire</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
        <select name="niveau" id="niveau" class="form-select">
          <option value="">Tous</option>
          @foreach($niveaux as $niveau)
            <option value="{{ $niveau }}" {{ request('niveau') == $niveau ? 'selected' : '' }}>
              {{ $niveau }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <label for="id_matiere" class="form-label">Matière</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
        <select name="id_matiere" id="id_matiere" class="form-select">
          <option value="">Toutes</option>
          @foreach($matieres as $matiere)
            <option value="{{ $matiere->id_matiere }}" {{ request('id_matiere') == $matiere->id_matiere ? 'selected' : '' }}>
              {{ $matiere->intitule }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-12 col-md-4 d-grid">
      <button type="submit" class="btn btn-primary mt-md-4">
        <i class="bi bi-funnel-fill me-1"></i> Filtrer
      </button>
    </div>
  </form>

  {{-- TABLEAU pour md+ --}}
  <div class="table-responsive d-none d-md-block">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Élève</th>
          <th>Matière</th>
          <th class="text-center">Note</th>
          <th class="text-center">Présence</th>
          <th>Date</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($evaluations as $evaluation)
          @php
            $noteClass = is_null($evaluation->note)
              ? ''
              : ($evaluation->note < 10 ? 'text-danger' : 'text-success');
            $presenceClass = $evaluation->presence === 'Présent'
              ? 'badge bg-success'
              : 'badge bg-danger';
          @endphp
          <tr>
            <td>{{ $evaluation->eleve->user->prenom }} {{ $evaluation->eleve->user->nom }}</td>
            <td>{{ $evaluation->matiere->intitule }}</td>
            <td class="text-center fw-bold {{ $noteClass }}">
              {{ is_null($evaluation->note) ? '—' : number_format($evaluation->note, 2) }}
            </td>
            <td class="text-center">
              <span class="{{ $presenceClass }}">{{ $evaluation->presence }}</span>
            </td>
            <td>{{ \Carbon\Carbon::parse($evaluation->date_evaluation)->format('d/m/Y') }}</td>
            <td class="text-center">
              <div class="btn-group btn-group-sm">
                @if(auth()->user()->role !== 'eleve')
                  <a href="{{ route('evaluations.edit', $evaluation->id_evaluation) }}"
                     class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Modifier">
                    <i class="bi bi-pencil-fill"></i>
                  </a>
                  <button type="button"
                          class="btn btn-outline-danger"
                          data-bs-toggle="modal"
                          data-bs-target="#deleteModal{{ $evaluation->id_evaluation }}"
                          data-bs-toggle="tooltip" title="Supprimer">
                    <i class="bi bi-trash-fill"></i>
                  </button>
                @endif
              </div>
            </td>
          </tr>

          {{-- Modal suppression --}}
          @if(auth()->user()->role !== 'eleve')
            <div class="modal fade" id="deleteModal{{ $evaluation->id_evaluation }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    Supprimer cette évaluation de rattrapage ?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('evaluations.destroy', $evaluation->id_evaluation) }}" method="POST">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          @endif
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">
              <i class="bi bi-exclamation-circle me-1"></i>Aucune évaluation trouvée.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- LISTE “CARDS” pour xs-sm --}}
  <div class="d-block d-md-none">
    @forelse($evaluations as $evaluation)
      @php
        $noteClass = is_null($evaluation->note)
          ? ''
          : ($evaluation->note < 10 ? 'text-danger' : 'text-success');
        $presenceClass = $evaluation->presence === 'Présent'
          ? 'badge bg-success'
          : 'badge bg-danger';
      @endphp
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">
            {{ $evaluation->eleve->user->prenom }} {{ $evaluation->eleve->user->nom }}
          </h5>
          <p class="mb-1"><strong>Matière :</strong> {{ $evaluation->matiere->intitule }}</p>
          <p class="mb-1">
            <strong>Note :</strong>
            <span class="fw-bold {{ $noteClass }}">
              {{ is_null($evaluation->note) ? '—' : number_format($evaluation->note, 2) }}
            </span>
          </p>
          <p class="mb-1">
            <strong>Présence :</strong>
            <span class="{{ $presenceClass }}">{{ $evaluation->presence }}</span>
          </p>
          <p class="mb-3"><strong>Date :</strong> {{ \Carbon\Carbon::parse($evaluation->date_evaluation)->format('d/m/Y') }}</p>
          <div class="d-flex gap-2">
            @if(auth()->user()->role !== 'eleve')
              <a href="{{ route('evaluations.edit', $evaluation->id_evaluation) }}"
                 class="btn btn-sm btn-outline-primary flex-fill">Modifier</a>
              <button type="button"
                      class="btn btn-sm btn-outline-danger flex-fill"
                      data-bs-toggle="modal"
                      data-bs-target="#deleteModal{{ $evaluation->id_evaluation }}">
                Supprimer
              </button>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="text-center text-muted py-4">
        <i class="bi bi-exclamation-circle me-1"></i>Aucune évaluation trouvée.
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="small text-muted">
      @if($evaluations->total())
        Affichage {{ $evaluations->firstItem() }}–{{ $evaluations->lastItem() }} sur {{ $evaluations->total() }}
      @else
        Aucun résultat
      @endif
    </div>
    <div>
      {{ $evaluations->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Tooltips Bootstrap
    var triggers = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    triggers.forEach(el => new bootstrap.Tooltip(el));
  });
</script>
@endpush
