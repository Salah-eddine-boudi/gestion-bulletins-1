@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Modifier l'Administrateur</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admins.update', $admin->id_admin) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="id_user" class="form-label">Utilisateur</label>
            <select class="form-control" name="id_user" id="id_user" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $admin->id_user ? 'selected' : '' }}>
                        {{ $user->prenom }} {{ $user->nom }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Rôle</label>
            <input type="text" class="form-control" name="role" id="role" value="{{ $admin->role }}" required>
        </div>

        <div class="mb-3">
            <label for="acces" class="form-label">Accès</label>
            <input type="text" class="form-control" name="acces" id="acces" value="{{ $admin->acces }}" required>
        </div>

        <div class="mb-3">
            <label for="tel" class="form-label">Téléphone</label>
            <input type="text" class="form-control" name="tel" id="tel" value="{{ $admin->tel ?? '' }}">
        </div>

        <div class="mb-3">
            <label for="bureau" class="form-label">Bureau</label>
            <input type="text" class="form-control" name="bureau" id="bureau" value="{{ $admin->bureau }}" required>
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('admins.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
