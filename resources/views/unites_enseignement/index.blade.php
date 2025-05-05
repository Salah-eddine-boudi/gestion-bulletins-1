{{-- resources/views/unites-enseignement/index.blade.php --}}
@extends('layouts.app')

@section('content')
@if (! Auth::check())
  <div class="alert alert-danger text-center mt-5">
    <h4>Accès interdit !</h4>
    <p>Vous devez être connecté.</p>
    <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
  </div>
@else
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Unités d'Enseignement</h2>
      <a href="{{ route('unites-enseignement.create') }}" class="btn btn-success">
        <i class="fas fa-plus-circle me-1"></i> Ajouter une UE
      </a>
    </div>

    {{-- Filtres --}}
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-light">
        <h5 class="mb-0">Filtres</h5>
      </div>
      <div class="card-body">
        <form method="GET" action="{{ route('unites-enseignement.index') }}" class="row g-3">
          <div class="col-6 col-md-3">
            <input type="text" name="intitule" class="form-control" placeholder="Intitulé…" value="{{ $filter_intitule }}">
          </div>
          <div class="col-6 col-md-3">
            <select name="type" class="form-select">
              <option value="">Tous types</option>
              @foreach($types as $t)
                <option value="{{ $t }}" {{ $filter_type === $t ? 'selected':'' }}>{{ $t }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-3">
            <select name="niveau_scolaire" class="form-select">
              <option value="">Tous niveaux</option>
              @foreach($niveaux as $n)
                <option value="{{ $n }}" {{ $filter_niveau === $n ? 'selected':'' }}>{{ $n }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-3">
            <select name="annee_universitaire" class="form-select">
              <option value="">Toutes les années</option>
              @foreach($annees as $a)
                <option value="{{ $a }}" {{ $filter_annee === $a ? 'selected':'' }}>{{ $a }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 text-end">
            <button type="submit" class="btn btn-outline-primary me-2">
              <i class="fas fa-filter me-1"></i> Filtrer
            </button>
            <a href="{{ route('unites-enseignement.index') }}" class="btn btn-outline-secondary">
              <i class="fas fa-sync-alt me-1"></i> Réinitialiser
            </a>
          </div>
        </form>
      </div>
    </div>

    {{-- Table desktop --}}
    <div class="card mb-4 shadow-sm d-none d-md-block">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Intitulé</th>
                <th>Type</th>
                <th>Niveau</th>
                <th>Code</th>
                <th>Année</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($unites as $ue)
                <tr>
                  <td>{{ $ue->id_ue }}</td>
                  <td>{{ $ue->intitule }}</td>
                  <td>{{ $ue->type }}</td>
                  <td>{{ $ue->niveau_scolaire }}</td>
                  <td><span class="badge bg-light text-dark">{{ $ue->code }}</span></td>
                  <td>{{ $ue->annee_universitaire }}</td>
                  <td class="text-center">
                    <a href="{{ route('unites-enseignement.show',$ue->id_ue) }}" class="btn btn-sm btn-outline-primary me-1" title="Voir">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('unites-enseignement.edit',$ue->id_ue) }}" class="btn btn-sm btn-outline-warning me-1" title="Modifier">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('unites-enseignement.destroy',$ue->id_ue) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">Aucune UE trouvée</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if(method_exists($unites,'links'))
        <div class="card-footer d-flex justify-content-between align-items-center">
          <small class="text-muted">
            Affichage de {{ $unites->firstItem() ?? 0 }} à {{ $unites->lastItem() ?? 0 }} sur {{ $unites->total() }} unités
          </small>
          {{ $unites->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>

    {{-- Cards mobile --}}
    <div class="d-block d-md-none">
      @forelse($unites as $ue)
        <div class="card mb-3 shadow-sm">
          <div class="card-body">
            <p class="mb-1"><strong>ID :</strong> {{ $ue->id_ue }}</p>
            <p class="mb-1"><strong>Intitulé :</strong> {{ $ue->intitule }}</p>
            <p class="mb-1"><strong>Type :</strong> {{ $ue->type }}</p>
            <p class="mb-1"><strong>Niveau :</strong> {{ $ue->niveau_scolaire }}</p>
            <p class="mb-1"><strong>Code :</strong> {{ $ue->code }}</p>
            <p class="mb-3"><strong>Année :</strong> {{ $ue->annee_universitaire }}</p>
            <div class="d-flex gap-2">
              <a href="{{ route('unites-enseignement.show',$ue->id_ue) }}"
                 class="btn btn-sm btn-primary flex-fill">
                <i class="fas fa-eye me-1"></i> Voir
              </a>
              <a href="{{ route('unites-enseignement.edit',$ue->id_ue) }}"
                 class="btn btn-sm btn-warning flex-fill">
                <i class="fas fa-edit me-1"></i> Modifier
              </a>
              <form action="{{ route('unites-enseignement.destroy',$ue->id_ue) }}"
                    method="POST" class="flex-fill"
                    onsubmit="return confirm('Supprimer cette UE ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger w-100">
                  <i class="fas fa-trash me-1"></i> Supprimer
                </button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <p class="text-center text-muted py-4">Aucune UE trouvée</p>
      @endforelse

      @if(method_exists($unites,'links'))
        <div class="d-flex justify-content-center mt-3">
          {{ $unites->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
@endif
@endsection
