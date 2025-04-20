@extends('layouts.app')

@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h1 class="fw-bold text-primary"><i class="fas fa-chart-line"></i> Tableau de Bord</h1>
            <p class="text-muted">Bienvenue, <strong>{{ Auth::user()->name }}</strong>. Voici un aperçu de vos informations.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Carte 1 : Bulletins -->
        <div class="col-md-4">
            <div class="card dashboard-card text-center shadow-lg border-0">
                <div class="card-body">
                    <div class="icon-container">
                        <i class="fas fa-file-alt icon"></i>
                    </div>
                    <h5 class="card-title fw-bold">Bulletins</h5>
                    <p class="card-text">Nombre de bulletins : <span class="fw-bold text-primary">10</span></p>
                    <a href="#" class="btn btn-outline-primary">Voir les bulletins</a>
                </div>
            </div>
        </div>

        <!-- Carte 2 : Statistiques -->
        <div class="col-md-4">
            <div class="card dashboard-card text-center shadow-lg border-0">
                <div class="card-body">
                    <div class="icon-container">
                        <i class="fas fa-chart-bar icon"></i>
                    </div>
                    <h5 class="card-title fw-bold">Statistiques</h5>
                    <p class="card-text">Moyenne générale : <span class="fw-bold text-success">15.6</span></p>
                    <a href="#" class="btn btn-outline-success">Voir les statistiques</a>
                </div>
            </div>
        </div>

        <!-- Carte 3 : Messages -->
        <div class="col-md-4">
            <div class="card dashboard-card text-center shadow-lg border-0">
                <div class="card-body">
                    <div class="icon-container">
                        <i class="fas fa-envelope icon"></i>
                    </div>
                    <h5 class="card-title fw-bold">Messages</h5>
                    <p class="card-text">Nouveaux messages : <span class="fw-bold text-danger">5</span></p>
                    <a href="#" class="btn btn-outline-danger">Voir les messages</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animation et style des cartes */
    .dashboard-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border-radius: 15px;
        padding: 20px;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    /* Style des icônes */
    .icon-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 15px;
    }

    .icon {
        font-size: 3rem;
        color: #007bff;
    }

    /* Couleurs dynamiques pour les différentes cartes */
    .dashboard-card:nth-child(2) .icon {
        color: #28a745;
    }

    .dashboard-card:nth-child(3) .icon {
        color: #dc3545;
    }
</style>

@endsection
