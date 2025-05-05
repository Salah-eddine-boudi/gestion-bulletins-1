{{-- resources/views/professeurs/show.blade.php --}}
@extends('layouts.app')

@section('content')
@if(Auth::check())
<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3><i class="fas fa-user-tie"></i> Détails du Professeur</h3>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    @php($user = $professeur->user)
                    @if($user && $user->photo)
                        <img src="{{ asset('storage/'.$user->photo) }}" class="rounded-circle img-fluid" alt="Photo de {{ $user->prenom }}" style="max-width:150px;">
                    @else
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:150px;height:150px;">
                            <span class="fw-bold text-primary" style="font-size:2rem;">
                                {{ strtoupper(substr($user->prenom ?? '','0',1).substr($user->nom ?? '','0',1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <dl class="row">
                        <dt class="col-sm-4">Nom complet :</dt>
                        <dd class="col-sm-8">
                            {{ $user->prenom ?? 'Inconnu' }} {{ $user->nom ?? '' }}
                        </dd>

                        <dt class="col-sm-4">Email :</dt>
                        <dd class="col-sm-8">
                            @if($user && $user->email)
                                <a href="mailto:{{ $user->email }}">
                                    <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                                </a>
                            @else
                                <span class="text-muted">Non défini</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Spécialité :</dt>
                        <dd class="col-sm-8">{{ $professeur->specialite ?? 'Non spécifiée' }}</dd>

                        <dt class="col-sm-4">Grade :</dt>
                        <dd class="col-sm-8">{{ $professeur->grade ?? 'Non défini' }}</dd>

                        @if($professeur->matieres->count())
                            <dt class="col-sm-4">Matières enseignées :</dt>
                            <dd class="col-sm-8">
                                <ul class="mb-0">
                                    @foreach($professeur->matieres as $matiere)
                                        <li>{{ $matiere->intitule }}</li>
                                    @endforeach
                                </ul>
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('professeurs.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
                <div>
                    <a href="{{ route('professeurs.edit', $professeur) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <form action="{{ route('professeurs.destroy', $professeur) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Supprimer définitivement ce professeur ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
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
