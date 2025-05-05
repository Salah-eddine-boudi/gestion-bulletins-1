{{-- resources/views/eleves/index.blade.php --}}
@extends('layouts.app')

@section('content')
@if (Auth::check())
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">Élèves</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('eleves.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus me-1"></i> Ajouter un Élève
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Desktop table view --}}
    <div class="table-responsive d-none d-md-block">
        @if($eleves->count())
        <table class="table table-hover table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Photo</th>
                    <th>Nom & Prénom</th>
                    <th>Matricule</th>
                    <th>Email</th>
                    <th>Niveau</th>
                    <th>Spécialité</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleves as $eleve)
                <tr>
                    <td class="text-center">
                        @if($eleve->user && $eleve->user->photo)
                            <img src="{{ asset('storage/'.$eleve->user->photo) }}"
                                 class="rounded-circle"
                                 width="40" height="40"
                                 style="object-fit:cover;" alt="">
                        @else
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width:40px;height:40px;">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $eleve->user->nom ?? 'Inconnu' }}
                        {{ $eleve->user->prenom ?? '' }}</strong><br>
                        <small class="text-muted">Matricule : {{ $eleve->matricule }}</small>
                    </td>
                    <td>{{ $eleve->matricule }}</td>
                    <td>
                        @if($eleve->user && $eleve->user->email)
                            <a href="mailto:{{ $eleve->user->email }}" class="text-decoration-none">
                                <i class="fas fa-envelope me-1 text-muted"></i>
                                {{ $eleve->user->email }}
                            </a>
                        @else
                            <span class="text-muted">Non défini</span>
                        @endif
                    </td>
                    <td>{{ $eleve->niveau_scolaire }}</td>
                    <td>{{ $eleve->specialite ?? 'Non spécifié' }}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('eleves.show',$eleve->id_eleve) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('eleves.edit',$eleve->id_eleve) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $eleve->id_eleve }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        {{-- Modal --}}
                        <div class="modal fade" id="deleteModal{{ $eleve->id_eleve }}" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Supprimer élève</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body text-center">
                                Supprimer <strong>{{ $eleve->user->prenom ?? '' }}
                                {{ $eleve->user->nom ?? 'Inconnu' }}</strong> ?
                              </div>
                              <div class="modal-footer justify-content-center">
                                <form action="{{ route('eleves.destroy',$eleve->id_eleve) }}"
                                      method="POST">
                                  @csrf @method('DELETE')
                                  <button class="btn btn-danger">Oui</button>
                                </form>
                                <button class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">Non</button>
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
            <p class="text-center text-muted">Aucun élève trouvé.</p>
        @endif
    </div>

    {{-- Mobile card view --}}
    <div class="d-block d-md-none">
        @foreach($eleves as $eleve)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3" style="width:50px;height:50px;">
                        @if($eleve->user && $eleve->user->photo)
                            <img src="{{ asset('storage/'.$eleve->user->photo) }}"
                                 class="rounded-circle w-100 h-100" style="object-fit:cover;" alt="">
                        @else
                            <div class="bg-light rounded-circle w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $eleve->user->prenom ?? '' }}
                            {{ $eleve->user->nom ?? 'Inconnu' }}</h5>
                        <small class="text-muted">Matricule : {{ $eleve->matricule }}</small>
                    </div>
                </div>
                <p class="mb-1"><strong>Email :</strong>
                    {{ $eleve->user->email ?? 'Non défini' }}</p>
                <p class="mb-1"><strong>Niveau :</strong> {{ $eleve->niveau_scolaire }}</p>
                <p class="mb-1"><strong>Spécialité :</strong> {{ $eleve->specialite ?? 'Non spécifié' }}</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('eleves.show',$eleve->id_eleve) }}"
                       class="btn btn-info flex-fill btn-sm">
                        <i class="fas fa-eye me-1"></i> Voir
                    </a>
                    <a href="{{ route('eleves.edit',$eleve->id_eleve) }}"
                       class="btn btn-primary flex-fill btn-sm">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                    <button class="btn btn-danger flex-fill btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModalMobile{{ $eleve->id_eleve }}">
                        <i class="fas fa-trash me-1"></i> Supprimer
                    </button>

                    {{-- Mobile delete modal --}}
                    <div class="modal fade" id="deleteModalMobile{{ $eleve->id_eleve }}" tabindex="-1">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Supprimer élève</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body text-center">
                            Supprimer <strong>{{ $eleve->user->prenom ?? '' }}
                            {{ $eleve->user->nom ?? 'Inconnu' }}</strong> ?
                          </div>
                          <div class="modal-footer justify-content-center">
                            <form action="{{ route('eleves.destroy',$eleve->id_eleve) }}"
                                  method="POST">
                              @csrf @method('DELETE')
                              <button class="btn btn-danger">Oui</button>
                            </form>
                            <button class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Non</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @if(method_exists($eleves, 'links'))
        <div class="d-flex justify-content-center mt-3">
            {{ $eleves->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@else
<div class="container py-5">
    <div class="alert alert-danger text-center">
        Accès interdit. <a href="{{ route('login') }}">Se connecter</a>.
    </div>
</div>
@endif
@endsection
