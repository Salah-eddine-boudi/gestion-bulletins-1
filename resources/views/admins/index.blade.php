{{-- resources/views/admins/index.blade.php --}}
@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('title', 'Administrateurs')

@section('content')
<div class="container py-4">
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
      <li class="breadcrumb-item active" aria-current="page">Administrateurs</li>
    </ol>
  </nav>

  <div class="card shadow-sm mb-4 rounded">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
      <h4 class="mb-0">Liste des administrateurs</h4>
      <a href="{{ route('admins.create') }}" class="btn btn-sm btn-success">
        <i class="fas fa-plus-circle me-1"></i> Ajouter un admin
      </a>
    </div>
    <div class="card-body">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      {{-- Desktop table view --}}
      <div class="table-responsive d-none d-md-block">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:5%">ID</th>
              <th style="width:20%">Nom complet</th>
              <th style="width:20%">Email</th>
              <th style="width:10%">Rôle</th>
              <th style="width:10%">Accès</th>
              <th style="width:10%">Téléphone</th>
              <th style="width:15%">Bureau</th>
              <th class="text-center" style="width:10%">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($admins as $admin)
              @php($user = $admin->user)
              <tr>
                <td class="text-muted">{{ $admin->id_admin ?? $admin->id }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="me-2" style="width:40px; height:40px;">
                      @if(optional($user)->photo)
                        <img src="{{ asset('storage/'.$user->photo) }}"
                             class="rounded-circle w-100 h-100 object-fit-cover"
                             alt="">
                      @else
                        <div class="bg-secondary text-white rounded-circle w-100 h-100 d-flex align-items-center justify-content-center">
                          {{ strtoupper(substr($user->prenom,0,1).substr($user->nom,0,1)) }}
                        </div>
                      @endif
                    </div>
                    <span class="fw-semibold">{{ $user->prenom }} {{ $user->nom }}</span>
                  </div>
                </td>
                <td>
                  <a href="mailto:{{ $user->email }}" class="d-block text-truncate" style="max-width:180px;">
                    {{ $user->email }}
                  </a>
                </td>
                <td><span class="badge bg-primary">{{ $admin->role }}</span></td>
                <td><span class="badge bg-info text-dark">{{ $admin->acces }}</span></td>
                <td>
                  <a href="tel:{{ $admin->tel ?? '' }}">
                    {{ $admin->tel ?? '—' }}
                  </a>
                </td>
                <td>{{ $admin->bureau ?? '—' }}</td>
                <td class="text-center">
                  <a href="{{ route('admins.edit', $admin->id_admin ?? $admin->id) }}"
                     class="btn btn-outline-primary btn-sm me-1">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="{{ route('admins.destroy', $admin->id_admin ?? $admin->id) }}"
                        method="POST" class="d-inline"
                        onsubmit="return confirm('Supprimer cet administrateur ?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                  <i class="fas fa-user-slash mb-2" style="font-size:1.5rem;"></i><br>
                  Aucun administrateur trouvé.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Mobile card view: affiche tous les champs --}}
      <div class="d-block d-md-none">
        @forelse($admins as $admin)
          @php($user = $admin->user)
          <div class="card mb-3">
            <div class="card-body">
              <div class="d-flex align-items-center mb-3">
                <div class="me-3" style="width:50px; height:50px;">
                  @if(optional($user)->photo)
                    <img src="{{ asset('storage/'.$user->photo) }}"
                         class="rounded-circle w-100 h-100 object-fit-cover" alt="">
                  @else
                    <div class="bg-secondary text-white rounded-circle w-100 h-100 d-flex align-items-center justify-content-center">
                      {{ strtoupper(substr($user->prenom,0,1).substr($user->nom,0,1)) }}
                    </div>
                  @endif
                </div>
                <h5 class="mb-0">{{ $user->prenom }} {{ $user->nom }}</h5>
              </div>
              <p class="mb-1"><strong>ID :</strong> {{ $admin->id_admin ?? $admin->id }}</p>
              <p class="mb-1"><strong>Email :</strong> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
              <p class="mb-1"><strong>Rôle :</strong> {{ $admin->role }}</p>
              <p class="mb-1"><strong>Accès :</strong> {{ $admin->acces }}</p>
              <p class="mb-1"><strong>Téléphone :</strong> {{ $admin->tel ?? '—' }}</p>
              <p class="mb-3"><strong>Bureau :</strong> {{ $admin->bureau ?? '—' }}</p>
              <div class="d-flex">
                <a href="{{ route('admins.edit', $admin->id_admin ?? $admin->id) }}"
                   class="btn btn-outline-primary btn-sm me-2 flex-fill">
                  <i class="fas fa-edit me-1"></i> Modifier
                </a>
                <form action="{{ route('admins.destroy', $admin->id_admin ?? $admin->id) }}"
                      method="POST" class="flex-fill"
                      onsubmit="return confirm('Supprimer cet administrateur ?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-outline-danger btn-sm w-100">
                    <i class="fas fa-trash-alt me-1"></i> Supprimer
                  </button>
                </form>
              </div>
            </div>
          </div>
        @empty
          <p class="text-center text-muted py-4">Aucun administrateur trouvé.</p>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($admins instanceof \Illuminate\Pagination\LengthAwarePaginator && $admins->hasPages())
        <div class="d-flex justify-content-center mt-4">
          {{ $admins->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      const alert = document.querySelector('.alert-dismissible');
      if (alert) bootstrap.Alert.getOrCreateInstance(alert).close();
    }, 5000);
  });
</script>
@endpush
