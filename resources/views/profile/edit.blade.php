<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Junia Maroc</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons (facultatif, pour les icônes) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #4B2E83;
            --secondary-color: #FF5F1F;
            --gradient: linear-gradient(135deg, #4B2E83, #FF5F1F);
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            margin-top: 2rem;
            padding: 2rem 0;
        }
        .profile-card {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        .profile-header {
            background: var(--gradient);
            padding: 2rem;
            color: white;
            text-align: center;
            position: relative;
        }
        .profile-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            background: white;
            border-radius: 50% 50% 0 0;
        }
        #photoPreview {
            width: 150px;
            height: 150px;
            border: 5px solid white;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }
        #photoPreview:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        .photo-upload-btn {
            position: relative;
            overflow: hidden;
            display: inline-block;
            margin-top: 1rem;
        }
        .photo-upload-btn input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            opacity: 0;
            cursor: pointer;
            display: block;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(75, 46, 131, 0.25);
        }
        .form-label {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .btn-update {
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(75, 46, 131, 0.4);
        }
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .form-section {
            padding: 2rem;
        }
        .field-group {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
        }
        .field-icon {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    @extends('layouts.app')

@section('content')
<div class="container profile-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-card animate__animated animate__fadeIn">
                <div class="profile-header">
                    <h2 class="mb-4">Mon Profil</h2>
                    <div class="text-center">
                        <img id="photoPreview"
                             src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('storage/profile_pictures/default.png') }}"
                             alt="Photo de profil">
                    </div>
                </div>

                <div class="form-section">
                    {{-- Affichage des erreurs --}}
                    @if ($errors->any())
                        <div class="alert alert-danger animate__animated animate__shakeX">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Message de succès --}}
                    @if (session('success'))
                        <div class="alert alert-success animate__animated animate__bounceIn">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Changer la photo de profil -->
                        <div class="text-center mb-4">
                            <div class="photo-upload-btn">
                                <button type="button" class="btn btn-outline-primary">
                                    <i class="fas fa-camera"></i> Changer la photo
                                </button>
                                <input type="file" name="photo" accept="image/*" onchange="previewImage(event)">
                            </div>
                        </div>

                        <!-- Groupes de champs -->
                        <div class="field-group">
                            <!-- Nom -->
                            <div class="mb-3">
                                <label for="nom" class="form-label">
                                    <i class="fas fa-user field-icon"></i> Nom
                                </label>
                                <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', Auth::user()->nom) }}" required>
                                @error('nom')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Prénom -->
                            <div class="mb-3">
                                <label for="prenom" class="form-label">
                                    <i class="fas fa-user field-icon"></i> Prénom
                                </label>
                                <input type="text" id="prenom" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                       value="{{ old('prenom', Auth::user()->prenom) }}" required>
                                @error('prenom')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope field-icon"></i> Adresse Email
                                </label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Téléphone Professionnel -->
                            <div class="mb-3">
                                <label for="tel_pro" class="form-label">
                                    <i class="fas fa-phone field-icon"></i> Téléphone Professionnel
                                </label>
                                <input type="text" id="tel_pro" name="tel_pro" class="form-control"
                                       value="{{ old('tel_pro', Auth::user()->tel_pro) }}" placeholder="Ex: +212XXXXXXXXX">
                            </div>

                            <!-- Statut -->
                            <div class="mb-3">
                                <label for="statut" class="form-label">
                                    <i class="fas fa-info-circle field-icon"></i> Statut
                                </label>
                                <select id="statut" name="statut" class="form-select">
                                    <option value="actif" {{ (old('statut', Auth::user()->statut) == 'actif') ? 'selected' : '' }}>Actif</option>
                                    <option value="inactif" {{ (old('statut', Auth::user()->statut) == 'inactif') ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>

                            <!-- Champ pour le rôle -->
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-users field-icon"></i> Rôle
                                </label>
                                <select id="role" name="role" class="form-select">
                                    <option value="" disabled>Choisissez votre rôle</option>
                                    <option value="admin" {{ (old('role', Auth::user()->role) == 'admin') ? 'selected' : '' }}>ADMIN</option>
                                    <option value="professeur" {{ (old('role', Auth::user()->role) == 'professeur') ? 'selected' : '' }}>PROFESSEUR</option>
                                    <option value="eleve" {{ (old('role', Auth::user()->role) == 'eleve') ? 'selected' : '' }}>ELEVE</option>
                                    <option value="directeur" {{ (old('role', Auth::user()->role) == 'directeur') ? 'selected' : '' }}>DIRECTEUR Pédagogique</option>
                                </select>
                                @error('role')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-update animate__animated animate__pulse">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour l'aperçu de l'image -->
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('photoPreview');
            output.src = reader.result;
            output.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                output.classList.remove('animate__animated', 'animate__pulse');
            }, 1000);
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
