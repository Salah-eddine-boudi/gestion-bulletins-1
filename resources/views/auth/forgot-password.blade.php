{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('Mot de passe oublié') }}</h5>
                </div>
                <div class="card-body">
                    {{-- Message explicatif --}}
                    <p class="text-muted mb-4">
                        {{ __("Pas de panique ! Indiquez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.") }}
                    </p>

                    {{-- Statut de session (lien envoyé) --}}
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- Champ e-mail --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Adresse e-mail') }}</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="form-control @error('email') is-invalid @enderror"
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Bouton --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Envoyer le lien de réinitialisation') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
