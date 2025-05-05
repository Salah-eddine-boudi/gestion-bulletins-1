{{-- resources/views/professeurs/index.blade.php --}}
@extends('layouts.app')

@section('content')
@if (Auth::check())
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">Professeurs</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('professeurs.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus me-1"></i> Ajouter un Professeur
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Desktop table --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Photo</th>
                    <th>Nom Complet</th>
                    <th>Email</th>
                    <th>Spécialité</th>
                    <th>Grade</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($professeurs as $prof)
                    @php($user = $prof->user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-3" style="width:40px; height:40px;">
                                    @if($user && $user->photo)
                                        <img src="{{ asset('storage/'.$user->photo) }}"
                                             class="rounded-circle w-100 h-100" style="object-fit:cover;" alt="">
                                    @else
                                        <div class="bg-light rounded-circle w-100 h-100 d-flex align-items-center justify-content-center">
                                            <span class="fw-bold text-primary">
                                                {{ strtoupper(substr($user->prenom ?? '',0,1).substr($user->nom ?? '',0,1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $user->prenom ?? 'Inconnu' }} {{ $user->nom ?? '' }}</div>
                                    <small class="text-muted">ID: {{ $prof->id_prof }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user && $user->email)
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1 text-muted"></i>{{ $user->email }}
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $prof->specialite }}</td>
                        <td>{{ $prof->grade }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('professeurs.show',$prof->id_prof) }}" class="btn btn-outline-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('professeurs.edit',$prof->id_prof) }}" class="btn btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $prof->id_prof }}" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            {{-- Modal --}}
                            <div class="modal fade" id="deleteModal{{ $prof->id_prof }}" tabindex="-1">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">Supprimer professeur</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                  </div>
                                  <div class="modal-body text-center">
                                    Supprimer <strong>{{ $user->prenom ?? '' }} {{ $user->nom ?? 'Inconnu' }}</strong> ?
                                  </div>
                                  <div class="modal-footer justify-content-center">
                                    <form action="{{ route('professeurs.destroy',$prof->id_prof) }}" method="POST">
                                      @csrf @method('DELETE')
                                      <button class="btn btn-danger">Oui</button>
                                    </form>
                                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Non</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucun professeur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="d-block d-md-none">
        @forelse ($professeurs as $prof)
            @php($user = $prof->user)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="width:50px;height:50px;">
                            @if($user && $user->photo)
                                <img src="{{ asset('storage/'.$user->photo) }}"
                                     class="rounded-circle w-100 h-100" style="object-fit:cover;" alt="">
                            @else
                                <div class="bg-light rounded-circle w-100 h-100 d-flex align-items-center justify-content-center">
                                    <span class="fw-bold text-primary">
                                        {{ strtoupper(substr($user->prenom ?? '',0,1).substr($user->nom ?? '',0,1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <h5 class="mb-0">{{ $user->prenom ?? 'Inconnu' }} {{ $user->nom ?? '' }}</h5>
                    </div>
                    <p class="mb-1"><strong>ID :</strong> {{ $prof->id_prof }}</p>
                    <p class="mb-1">
                        <strong>Email :</strong> {{ $user->email ?? 'N/A' }}
                    </p>
                    <p class="mb-1"><strong>Spécialité :</strong> {{ $prof->specialite }}</p>
                    <p class="mb-3"><strong>Grade :</strong> {{ $prof->grade }}</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('professeurs.show',$prof->id_prof) }}"
                           class="btn btn-info flex-fill btn-sm">
                            <i class="fas fa-eye me-1"></i> Voir
                        </a>
                        <a href="{{ route('professeurs.edit',$prof->id_prof) }}"
                           class="btn btn-primary flex-fill btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <button class="btn btn-danger flex-fill btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModalMobile{{ $prof->id_prof }}">
                            <i class="fas fa-trash me-1"></i> Supprimer
                        </button>

                        {{-- Mobile delete modal --}}
                        <div class="modal fade" id="deleteModalMobile{{ $prof->id_prof }}" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Supprimer professeur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body text-center">
                                Supprimer <strong>{{ $user->prenom ?? '' }} {{ $user->nom ?? 'Inconnu' }}</strong> ?
                              </div>
                              <div class="modal-footer justify-content-center">
                                <form action="{{ route('professeurs.destroy',$prof->id_prof) }}" method="POST">
                                  @csrf @method('DELETE')
                                  <button class="btn btn-danger">Oui</button>
                                </form>
                                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Non</button>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Aucun professeur trouvé.</p>
        @endforelse

        @if(method_exists($professeurs,'links'))
            <div class="d-flex justify-content-center mt-3">
                {{ $professeurs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@else
<div class="container py-5 text-center">
    <div class="alert alert-danger">
        Accès interdit. <a href="{{ route('login') }}">Se connecter</a>.
    </div>
</div>
@endif
@endsection
