{{-- resources/views/auth/reset-password.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">{{ __('Réinitialisation du mot de passe') }}</h5>
        </div>
        <div class="card-body">
          
          <p class="text-muted mb-4">
            {{ __("Veuillez saisir votre adresse e-mail et choisir un nouveau mot de passe.") }}
          </p>
          
          @if (session('status'))
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
          @endif

          <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email --}}
            <div class="mb-3">
              <label for="email" class="form-label">{{ __('Adresse e-mail') }}</label>
              <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
                class="form-control @error('email') is-invalid @enderror"
              >
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Nouveau mot de passe --}}
            <div class="mb-3">
              <label for="password" class="form-label">{{ __('Nouveau mot de passe') }}</label>
              <div class="input-group">
                <input
                  id="password"
                  type="password"
                  name="password"
                  required
                  autocomplete="new-password"
                  class="form-control @error('password') is-invalid @enderror"
                >
                <span class="input-group-text toggle-password" data-target="#password" style="cursor: pointer;">
                  <!-- Par défaut, œil barré -->
                  <i class="fas fa-eye-slash"></i>
                </span>
              </div>
              @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- Confirmation --}}
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
              <div class="input-group">
                <input
                  id="password_confirmation"
                  type="password"
                  name="password_confirmation"
                  required
                  autocomplete="new-password"
                  class="form-control @error('password_confirmation') is-invalid @enderror"
                >
                <span class="input-group-text toggle-password" data-target="#password_confirmation" style="cursor: pointer;">
                  <!-- Par défaut, œil barré -->
                  <i class="fas fa-eye-slash"></i>
                </span>
              </div>
              @error('password_confirmation')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- Bouton --}}
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                {{ __('Réinitialiser le mot de passe') }}
              </button>
            </div>
          </form>
          
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var selector = this.getAttribute('data-target');
        var input = document.querySelector(selector);
        var icon  = this.querySelector('i');
        if (!input) return;
        if (input.type === 'password') {
          // Affiche le texte et change l'icône en œil ouvert
          input.type = 'text';
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        } else {
          // Recache le texte et remet l'icône œil barré
          input.type = 'password';
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        }
      });
    });
  });
</script>
@endsection
