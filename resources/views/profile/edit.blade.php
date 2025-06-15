<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mon Profil – Junia Maroc</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    :root {
      --primary-color: #4B2E83;
      --secondary-color: #FF5F1F;
      --gradient: linear-gradient(135deg, #4B2E83, #FF5F1F);
    }
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      margin: 0; padding: 0;
    }
    .profile-container { margin-top: 2rem; padding: 2rem 0; }
    .profile-card {
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      position: relative;
    }
    .profile-header {
      background: var(--gradient);
      color: #fff;
      padding: 2rem;
      text-align: center;
      position: relative;
    }
    .profile-header::after {
      content: '';
      position: absolute;
      bottom: -20px;
      left: 0; right: 0;
      height: 40px;
      background: #fff;
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
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
    .photo-upload-btn { position: relative; display: inline-block; margin-top: 1rem; }
    .photo-upload-btn input[type=file] {
      position: absolute; top: 0; right: 0;
      width: 100%; height: 100%;
      font-size: 100px; opacity: 0; cursor: pointer;
    }
    .form-control {
      border-radius: 10px;
      padding: .8rem 1.2rem;
      border: 2px solid #e9ecef;
      transition: 0.3s;
    }
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 .2rem rgba(75,46,131,0.25);
    }
    .form-label { color: var(--primary-color); font-weight: 600; }
    .btn-update {
      background: var(--gradient);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 1rem 2rem;
      font-weight: 600;
      letter-spacing: .5px;
      transition: 0.3s;
    }
    .btn-update:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(75,46,131,0.4);
    }
    .alert {
      border: none;
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: 1.5rem;
    }
    .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
    .alert-danger  { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
    .form-section { padding: 2rem; }
    .field-group {
      background: #f8f9fa;
      padding: 1.5rem;
      border-radius: 15px;
      margin-bottom: 1.5rem;
    }
    .field-icon { color: var(--primary-color); margin-right: .5rem; }
    @media (max-width:576px) {
      .profile-header { padding: 1rem; }
      .form-section   { padding: 1rem; }
      .field-group    { padding: 1rem; margin-bottom: 1rem; }
      .btn-update     { width: 100%; padding: .8rem; }
    }
  </style>
</head>
<body>
{{-- resources/views/profile/edit.blade.php --}}
@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('head')
  {{-- Bootstrap Icons --}}
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container profile-container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8">
      <div class="profile-card shadow-lg animate__animated animate__fadeIn">
        <div class="profile-header">
          <h2>Mon Profil</h2>
          <img
            id="photoPreview"
            class="img-fluid rounded-circle"
            src="{{ Auth::user()->photo
              ? asset('storage/'.Auth::user()->photo)
              : asset('storage/profile_pictures/default.png') }}"
            alt="Photo de profil"
          >
        </div>
        <div class="form-section">
          @if($errors->any())
            <div class="alert alert-danger animate__shakeX">
              <ul class="mb-0">
                @foreach($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          @if(session('success'))
            <div class="alert alert-success animate__bounceIn">
              {{ session('success') }}
            </div>
          @endif

          <form method="POST" action="{{ route('profile.update') }}"
                enctype="multipart/form-data" id="profileForm">
            @csrf @method('PATCH')

            {{-- Photo --}}
            <div class="text-center mb-4">
              <div class="photo-upload-btn">
                <button type="button" class="btn btn-outline-primary">
                  <i class="bi bi-camera"></i> Changer la photo
                </button>
                <input type="file" name="photo" accept="image/*"
                       onchange="previewImage(event)">
              </div>
            </div>

            {{-- Infos générales --}}
            <div class="field-group mb-4">
              @foreach([
                ['id'=>'nom','icon'=>'person-fill','label'=>'Nom','value'=>Auth::user()->nom],
                ['id'=>'prenom','icon'=>'person-fill','label'=>'Prénom','value'=>Auth::user()->prenom],
                ['id'=>'email','icon'=>'envelope-fill','label'=>'Email','value'=>Auth::user()->email,'type'=>'email'],
                ['id'=>'tel_pro','icon'=>'telephone-fill','label'=>'Téléphone','value'=>Auth::user()->tel_pro,'required'=>false],
              ] as $f)
                <div class="mb-3">
                  <label for="{{ $f['id'] }}" class="form-label">
                    <i class="bi bi-{{ $f['icon'] }} field-icon"></i> {{ $f['label'] }}
                  </label>
                  <input
                    type="{{ $f['type'] ?? 'text' }}"
                    id="{{ $f['id'] }}"
                    name="{{ $f['id'] }}"
                    class="form-control @error($f['id']) is-invalid @enderror"
                    value="{{ old($f['id'], $f['value']) }}"
                    {{ ($f['required'] ?? true) ? 'required' : '' }}
                  >
                  @error($f['id'])<small class="text-danger">{{ $message }}</small>@enderror
                </div>
              @endforeach

              <div class="mb-3">
                <label for="statut" class="form-label">
                  <i class="bi bi-info-circle-fill field-icon"></i> Statut
                </label>
                <select id="statut" name="statut" class="form-select">
                  <option value="actif"   {{ old('statut',Auth::user()->statut)=='actif'   ? 'selected':'' }}>Actif</option>
                  <option value="inactif" {{ old('statut',Auth::user()->statut)=='inactif' ? 'selected':'' }}>Inactif</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="role" class="form-label">
                  <i class="bi bi-people-fill field-icon"></i> Rôle
                </label>
                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror">
                  <option value="" disabled>Choisissez un rôle</option>
                  <option value="admin"      {{ old('role',Auth::user()->role)=='admin'      ? 'selected':'' }}>ADMIN</option>
                  <option value="professeur" {{ old('role',Auth::user()->role)=='professeur' ? 'selected':'' }}>PROFESSEUR</option>
                  <option value="eleve"      {{ old('role',Auth::user()->role)=='eleve'      ? 'selected':'' }}>ELEVE</option>
                  <option value="directeur"  {{ old('role',Auth::user()->role)=='directeur'  ? 'selected':'' }}>DIRECTEUR</option>
                </select>
                @error('role')<small class="text-danger">{{ $message }}</small>@enderror
              </div>
            </div>

      {{-- Checkbox activer mot de passe --}}
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox"
               id="editPasswordToggle">
        <label class="form-check-label" for="editPasswordToggle">
          Modifier mon mot de passe
        </label>
      </div>

      {{-- Section Mot de passe --}}
      <div id="passwordSection"
           class="field-group mb-4"
           style="display: none;">
        @foreach([
          ['id'=>'current_password','label'=>'Mot de passe actuel'],
          ['id'=>'password','label'=>'Nouveau mot de passe'],
          ['id'=>'password_confirmation','label'=>'Confirmer le nouveau mot de passe']
        ] as $f)
          <div class="mb-3">
            <label for="{{ $f['id'] }}" class="form-label">
              <i class="bi bi-lock-fill field-icon"></i>
              {{ $f['label'] }}
            </label>
            <div class="input-group">
              <input
                type="password"
                id="{{ $f['id'] }}"
                name="{{ $f['id'] }}"
                class="form-control @error($f['id']) is-invalid @enderror"
                disabled
              >
              <button type="button"
                      class="btn btn-outline-secondary"
                      onclick="togglePassword('{{ $f['id'] }}','icon-{{ $f['id'] }}')"
                      disabled>
                <i id="icon-{{ $f['id'] }}"
                   class="bi bi-eye-slash"></i>
              </button>
            </div>
            @error($f['id'])<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        @endforeach
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-update">
          <i class="bi bi-save"></i> Enregistrer
        </button>
      </div>
    </form>
  </div>
</div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
// Toggle section mot de passe
const toggleCheckbox = document.getElementById('editPasswordToggle');
const passwordSection = document.getElementById('passwordSection');

toggleCheckbox.addEventListener('change', function(){
const show = this.checked;
passwordSection.style.display = show ? 'block' : 'none';

// activer / désactiver inputs et boutons
passwordSection.querySelectorAll('input, button').forEach(el => {
el.disabled = !show;
if (el.tagName === 'INPUT' && el.type === 'password') {
  el.required = show && el.id !== 'current_password';
}
});
});
});

// Aperçu image
function previewImage(e) {
const reader = new FileReader();
reader.onload = () => {
document.getElementById('photoPreview').src = reader.result;
};
reader.readAsDataURL(e.target.files[0]);
}

// Toggle eye / eye-slash
function togglePassword(fieldId, iconId) {
const input = document.getElementById(fieldId);
const icon  = document.getElementById(iconId);
if (input.disabled) return;
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