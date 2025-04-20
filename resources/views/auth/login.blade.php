@extends('layouts.app')

@section('content')
<x-loading-animation :show="false" />

<div class="container d-flex justify-content-center align-items-center" style="height: 90vh;">
    <div class="card shadow-lg p-4 bg-white animate_animated animate_fadeIn" style="width: 400px; border-radius: 15px;">
        <h2 class="text-center text-primary mb-4">Se Connecter</h2>

        <!-- ✅ Amélioration de l'affichage du message de succès -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>✔ Succès !</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Affichage des erreurs -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary w-100">Se Connecter</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('password.request') }}" class="text-primary">Mot de passe oublié ?</a>
        </div>
    </div>
</div>

<!-- Scripts pour la gestion de l'animation -->
<script>
    // Gestion du message de succès
    setTimeout(() => {
        let alert = document.querySelector('.alert-success');
        if (alert) {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        }
    }, 10000);

    // Gestion de l'animation de chargement
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Afficher l'animation
        document.getElementById('loading-animation').style.display = 'flex';
        
        // Soumettre le formulaire après un court délai
        setTimeout(() => {
            this.submit();
        }, 1500);
    });
</script>

@endsection