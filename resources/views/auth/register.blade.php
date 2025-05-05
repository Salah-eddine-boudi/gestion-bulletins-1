{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@section('head')
  {{-- Bootstrap Icons (si pas déjà inclus dans layouts.app) --}}
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
      <div class="card shadow-lg animate__animated animate__fadeIn" style="border-radius:15px">
        <div class="card-body p-4">
          <h2 class="text-center text-primary mb-4">Créer un Compte</h2>

          {{-- Erreurs --}}
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Nom --}}
            <div class="mb-3">
              <label for="nom" class="form-label">Nom :</label>
              <input type="text"
                     id="nom"
                     name="nom"
                     class="form-control @error('nom') is-invalid @enderror"
                     value="{{ old('nom') }}"
                     required>
              @error('nom')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Prénom --}}
            <div class="mb-3">
              <label for="prenom" class="form-label">Prénom :</label>
              <input type="text"
                     id="prenom"
                     name="prenom"
                     class="form-control @error('prenom') is-invalid @enderror"
                     value="{{ old('prenom') }}"
                     required>
              @error('prenom')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
              <label for="email" class="form-label">Email :</label>
              <input type="email"
                     id="email"
                     name="email"
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}"
                     required>
              @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Téléphone professionnel --}}
            <div class="mb-3">
              <label for="tel_pro" class="form-label">Téléphone professionnel :</label>
              <input type="text"
                     id="tel_pro"
                     name="tel_pro"
                     class="form-control @error('tel_pro') is-invalid @enderror"
                     value="{{ old('tel_pro') }}">
              @error('tel_pro')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Statut --}}
            <div class="mb-3">
              <label for="statut" class="form-label">Statut :</label>
              <select id="statut"
                      name="statut"
                      class="form-select @error('statut') is-invalid @enderror"
                      required>
                <option value="actif"   {{ old('statut')=='actif'   ? 'selected':'' }}>Actif</option>
                <option value="inactif" {{ old('statut')=='inactif' ? 'selected':'' }}>Inactif</option>
              </select>
              @error('statut')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Rôle --}}
            <div class="mb-3">
              <label for="role" class="form-label">Rôle :</label>
              <select id="role"
                      name="role"
                      class="form-select @error('role') is-invalid @enderror"
                      required>
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choisissez un rôle</option>
                <option value="admin"      {{ old('role')=='admin'      ? 'selected':'' }}>ADMIN</option>
                <option value="professeur" {{ old('role')=='professeur' ? 'selected':'' }}>PROFESSEUR</option>
                <option value="eleve"      {{ old('role')=='eleve'      ? 'selected':'' }}>ELEVE</option>
                <option value="directeur"  {{ old('role')=='directeur'  ? 'selected':'' }}>DIRECTEUR Pédagogique</option>
              </select>
              @error('role')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Mot de passe --}}
            <div class="mb-3">
              <label for="password" class="form-label">Mot de passe :</label>
              <div class="input-group">
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required>
                <button type="button"
                        class="btn btn-outline-secondary"
                        onclick="togglePassword('password','toggle-password-icon')">
                  <i id="toggle-password-icon" class="bi bi-eye-slash"></i>
                </button>
              </div>
              @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Confirmation du mot de passe --}}
            <div class="mb-4">
              <label for="password_confirmation" class="form-label">Confirmer le mot de passe :</label>
              <div class="input-group">
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       class="form-control"
                       required>
                <button type="button"
                        class="btn btn-outline-secondary"
                        onclick="togglePassword('password_confirmation','toggle-password-confirm-icon')">
                  <i id="toggle-password-confirm-icon" class="bi bi-eye-slash"></i>
                </button>
              </div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">S'inscrire</button>
            </div>
          </form>

          <div class="text-center mt-3">
            <span>Déjà inscrit ?</span>
            <a href="{{ route('login') }}" class="text-primary">Se connecter</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function togglePassword(fieldId, iconId) {
    const input = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.replace('bi-eye-slash','bi-eye');
    } else {
      input.type = 'password';
      icon.classList.replace('bi-eye','bi-eye-slash');
    }
  }
</script>
@endpush
@endsection
