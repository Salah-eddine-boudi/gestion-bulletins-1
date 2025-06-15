{{-- resources/views/auth/login.blade.php --}}
@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

{{-- Charger Bootstrap Icons (idéalement dans layouts.app <head>) --}}
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@section('content')
<div class="container py-5">
  <div class="row justify-content-center align-items-center" style="height: 90vh;">
    <div class="col-12 col-sm-8 col-md-6 col-lg-4">
      <div class="card shadow-lg animate__animated animate__fadeIn" style="border-radius: 15px;">
        <div class="card-body p-4">
          <h2 class="text-center text-primary mb-4">Se Connecter</h2>

          {{-- Message de succès --}}
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>✔ Succès !</strong> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Message d'erreurs --}}
          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
              <label for="email" class="form-label">Email :</label>
              <input
                type="email"
                id="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                required
                autofocus
              >
              @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Mot de passe --}}
            <div class="mb-4">
              <label for="password" class="form-label">Mot de passe :</label>
              <div class="input-group">
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-control @error('password') is-invalid @enderror"
                  required
                >
                <button
                  class="btn btn-outline-secondary"
                  type="button"
                  onclick="togglePassword('password','toggle-password-icon')"
                >
                  <i id="toggle-password-icon" class="bi bi-eye-slash"></i>
                </button>
              </div>
              @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Bouton de connexion --}}
            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary w-100">Se Connecter</button>
            </div>
          </form>

          <div class="text-center">
            <a href="{{ route('password.request') }}" class="text-primary">Mot de passe oublié ?</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Toggle œil et loader --}}
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

  document.getElementById('loginForm').addEventListener('submit', function(e){
    e.preventDefault();
    // Afficher loader si besoin...
    setTimeout(()=> this.submit(), 800);
  });
</script>
@endsection
