@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-4 animate_animated animate_fadeIn">
            <h1 class="fw-bold text-primary"><i class="fas fa-chart-line"></i> Tableau de Bord</h1>
            <p class="text-muted">Bienvenue, <strong>{{ Auth::user()->name }}</strong>. Voici un aperçu de vos informations.</p>
        </div>
    </div>

    <!-- Cartes de résumé -->
    <div class="row g-4 mb-5">
        <!-- Carte 1 : Bulletins -->
        <div class="col-md-4 animate_animated animate_fadeInUp" style="animation-delay: 0.1s">
            <div class="card dashboard-card h-100 border-0 rounded-4">
                <div class="card-body d-flex flex-column">
                    <div class="icon-container mb-3">
                        <div class="icon-circle bg-primary-subtle">
                            <i class="fas fa-file-alt icon text-primary"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold">Bulletins</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="display-6 fw-bold text-primary me-2">10</div>
                        <div class="badge bg-primary-subtle text-primary rounded-pill">
                            <i class="fas fa-arrow-up me-1"></i>2 nouveaux
                        </div>
                    </div>
                    <p class="card-text text-muted mb-4">Accédez à tous vos bulletins scolaires et suivez vos progrès.</p>
                    <a href="#" class="btn btn-primary mt-auto stretched-link">
                        <i class="fas fa-eye me-2"></i>Voir les bulletins
                    </a>
                </div>
            </div>
        </div>

        <!-- Carte 2 : Statistiques -->
        <div class="col-md-4 animate_animated animate_fadeInUp" style="animation-delay: 0.2s">
            <div class="card dashboard-card h-100 border-0 rounded-4">
                <div class="card-body d-flex flex-column">
                    <div class="icon-container mb-3">
                        <div class="icon-circle bg-success-subtle">
                            <i class="fas fa-chart-bar icon text-success"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold">Statistiques</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="display-6 fw-bold text-success me-2">15.6</div>
                        <div class="badge bg-success-subtle text-success rounded-pill">
                            <i class="fas fa-arrow-up me-1"></i>+1.2 pts
                        </div>
                    </div>
                    <div class="progress mb-4" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="card-text text-muted mb-4">Visualisez vos performances et évolution au fil du temps.</p>
                    <a href="#" class="btn btn-success mt-auto stretched-link">
                        <i class="fas fa-chart-line me-2"></i>Voir les statistiques
                    </a>
                </div>
            </div>
        </div>

        <!-- Carte 3 : Messages -->
        <div class="col-md-4 animate_animated animate_fadeInUp" style="animation-delay: 0.3s">
            <div class="card dashboard-card h-100 border-0 rounded-4">
                <div class="card-body d-flex flex-column">
                    <div class="icon-container mb-3">
                        <div class="icon-circle bg-danger-subtle">
                            <i class="fas fa-envelope icon text-danger"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold">Messages</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="display-6 fw-bold text-danger me-2">5</div>
                        <div class="badge bg-danger-subtle text-danger rounded-pill">
                            <i class="fas fa-bell me-1"></i>Non lus
                        </div>
                    </div>
                    <p class="card-text text-muted mb-4">Consultez  messages et notifications importantes.</p>
                    <a href="#" class="btn btn-danger mt-auto stretched-link">
                        <i class="fas fa-inbox me-2"></i>Voir les messages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Activités récentes et calendrier -->
    <div class="row g-4">
        <!-- Activités récentes -->
        <div class="col-lg-7 animate_animated animate_fadeInUp" style="animation-delay: 0.4s">
            <div class="card border-0 rounded-4 shadow">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-history me-2 text-primary"></i>Activités récentes</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item pb-3">
                            <div class="d-flex">
                                <div class="timeline-icon bg-primary text-white">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-bold mb-1">Nouveau bulletin disponible</h6>
                                        <small class="text-muted">Il y a 2 jours</small>
                                    </div>
                                    <p class="text-muted mb-0">Votre bulletin du trimestre 2 est maintenant disponible.</p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item pb-3">
                            <div class="d-flex">
                                <div class="timeline-icon bg-success text-white">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-bold mb-1">Devoir soumis</h6>
                                        <small class="text-muted">Il y a 4 jours</small>
                                    </div>
                                    <p class="text-muted mb-0">Votre devoir de mathématiques a été soumis avec succès.</p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="d-flex">
                                <div class="timeline-icon bg-warning text-white">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-bold mb-1">Rappel d'examen</h6>
                                        <small class="text-muted">Il y a 1 semaine</small>
                                    </div>
                                    <p class="text-muted mb-0">N'oubliez pas votre examen de sciences prévu pour la semaine prochaine.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-center pb-4">
                    <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-4">Voir toutes les activités</a>
                </div>
            </div>
        </div>
        
        <!-- Calendrier -->
        <div class="col-lg-5 animate_animated animate_fadeInUp" style="animation-delay: 0.5s">
            <div class="card border-0 rounded-4 shadow">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>Événements à venir</h5>
                </div>
                <div class="card-body">
                    <div class="event-item mb-3 p-3 border-start border-primary border-3 rounded bg-light">
                        <h6 class="fw-bold mb-1">Examen de mathématiques</h6>
                        <div class="d-flex align-items-center text-muted mb-2">
                            <i class="fas fa-calendar-day me-2"></i>
                            <span>28 juillet 2023</span>
                            <i class="fas fa-clock ms-3 me-2"></i>
                            <span>09:00 - 11:00</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            <span>Salle 203</span>
                        </div>
                    </div>
                    
                    <div class="event-item mb-3 p-3 border-start border-success border-3 rounded bg-light">
                        <h6 class="fw-bold mb-1">Réunion des parents</h6>
                        <div class="d-flex align-items-center text-muted mb-2">
                            <i class="fas fa-calendar-day me-2"></i>
                            <span>5 août 2023</span>
                            <i class="fas fa-clock ms-3 me-2"></i>
                            <span>17:00 - 19:00</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            <span>Amphithéâtre</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-center pb-4">
                    <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-4">Voir le calendrier complet</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style des cartes */
    .dashboard-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Style des icônes */
    .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .icon {
        font-size: 1.75rem;
    }
    
    /* Style de la timeline */
    .timeline {
        position: relative;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 17px;
        top: 30px;
        height: calc(100% - 15px);
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 0.875rem;
        z-index: 1;
        position: relative;
    }
    
    /* Style des événements */
    .event-item {
        transition: transform 0.2s ease;
    }
    
    .event-item:hover {
        transform: translateX(5px);
    }
    
    /* Animation des badges */
    .badge {
        transition: all 0.3s ease;
    }
    
    .badge:hover {
        transform: scale(1.05);
    }
    
    /* Optimisations pour les appareils mobiles */
    @media (max-width: 768px) {
        .timeline-item:not(:last-child)::after {
            left: 16px;
        }
        
        .timeline-icon {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;q
        }
    }
</style>

@endsection