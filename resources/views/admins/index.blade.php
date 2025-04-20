@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h3 class="mb-0 fw-bold">Liste des Administrateurs</h3>
            <a href="{{ route('admins.create') }}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus-circle me-2" aria-hidden="true"></i>
                Ajouter un Admin
            </a>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="adminsTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nom complet</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rôle</th>
                            <th scope="col">Accès</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Bureau</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $admin->id_admin ?? $admin->id }}</td>
                                <td>
                                    @if($admin->user)
                                        <div class="d-flex align-items-center">
                                            @php
                                                $photo = $admin->user->photo 
                                                    ? asset('storage/' . $admin->user->photo) 
                                                    : asset('storage/profile_pictures/default.png');
                                            @endphp
                                            <img src="{{ $photo }}" alt="Photo de profil" 
                                                class="rounded-circle me-2" width="40" height="40" 
                                                style="object-fit: cover;">
                                            <div>
                                                <span class="fw-medium">{{ $admin->user->prenom }} {{ $admin->user->nom }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i> Utilisateur introuvable</span>
                                    @endif
                                </td>
                                <td>
                                    @if($admin->user && $admin->user->email)
                                        <a href="mailto:{{ $admin->user->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope me-1 text-muted" aria-hidden="true"></i>
                                            {{ $admin->user->email }}
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic">Non renseigné</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $admin->role }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $admin->acces }}</span>
                                </td>
                                <td>
                                    @if($admin->tel)
                                        <a href="tel:{{ $admin->tel }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1 text-muted" aria-hidden="true"></i>
                                            {{ $admin->tel }}
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic">Non renseigné</span>
                                    @endif
                                </td>
                                <td>
                                    @if($admin->bureau)
                                        <i class="fas fa-building me-1 text-muted" aria-hidden="true"></i>
                                        {{ $admin->bureau }}
                                    @else
                                        <span class="text-muted fst-italic">Non renseigné</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('admins.edit', $admin->id_admin ?? $admin->id) }}" 
                                           class="btn btn-sm btn-warning me-2">
                                            <i class="fas fa-edit me-1" aria-hidden="true"></i> Modifier
                                        </a>
                                        
                                        <form action="{{ route('admins.destroy', $admin->id_admin ?? $admin->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cet administrateur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt me-1" aria-hidden="true"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-user-slash text-muted mb-3" style="font-size: 3rem;"></i>
                                        <h5 class="fw-bold">Aucun administrateur trouvé</h5>
                                        <p class="text-muted">Commencez par ajouter un administrateur avec le bouton ci-dessus</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($admins instanceof \Illuminate\Pagination\LengthAwarePaginator && $admins->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $admins->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fermer automatiquement les alertes après 5 secondes
        setTimeout(function() {
            $('.alert-dismissible').alert('close');
        }, 5000);
    });
</script>
@endpush
@endsection