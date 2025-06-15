<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Junia Maroc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4B2E83;
            --secondary-color: #FF5F1F;
            --gradient: linear-gradient(135deg, #4B2E83, #FF5F1F);
        }

        .contact-container {
            margin: 4rem auto;
            padding: 2rem 1rem;
        }

        .contact-card {
            background: white;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .contact-header {
            background: var(--gradient);
            padding: 3rem 2rem;
            color: white;
            text-align: center;
            position: relative;
        }

        .contact-header::after {
            content: '';
            position: absolute;
            bottom: -25px;
            left: 0;
            width: 100%;
            height: 50px;
            background: white;
            border-radius: 50% 50% 0 0;
        }

        .contact-logo {
            width: 150px;
            margin-bottom: 1.5rem;
            filter: brightness(0) invert(1);
        }

        .contact-content {
            padding: 3rem 2rem 2rem;
        }

        .contact-info-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .contact-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .contact-text {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .contact-text strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.3rem;
        }

        .copyright-section {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 0 0 20px 20px;
            border-top: 1px solid #eee;
        }

        .social-links {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(75, 46, 131, 0.3);
            color: white;
        }

        @media (max-width: 768px) {
            .contact-container {
                margin: 2rem auto;
            }
            
            .contact-header {
                padding: 2rem 1rem;
            }
            
            .contact-content {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    @php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

    @section('content')
    <div class="container contact-container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-card animate__animated animate__fadeIn">
                    <div class="contact-header">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/73/JUNIA.png" 
                             alt="Logo Junia" 
                             class="contact-logo">
                        <h2 class="mb-0">Contactez JUNIA Maroc</h2>
                    </div>

                    <div class="contact-content">
                        <div class="contact-info-card animate__animated animate__fadeInUp">
                            <div class="text-center">
                                <i class="bi bi-telephone-fill contact-icon"></i>
                                <p class="contact-text">
                                    <strong>Téléphone</strong>
                                    (+212) 05 37 63 67 97<br>
                                    (+212) 06 64 69 67 94
                                </p>
                            </div>
                        </div>

                        <div class="contact-info-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                            <div class="text-center">
                                <i class="bi bi-envelope-fill contact-icon"></i>
                                <p class="contact-text">
                                    <strong>Responsable Communication et Admission</strong>
                                    omar.piro@junia.com
                                </p>
                            </div>
                        </div>

                        <div class="contact-info-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                            <div class="text-center">
                                <i class="bi bi-geo-alt-fill contact-icon"></i>
                                <p class="contact-text">
                                    <strong>Adresse</strong>
                                    17, avenue des Nation Unies, Agdal,<br>
                                    Rabat 10080
                                </p>
                            </div>
                        </div>

                        <div class="social-links">
                            <a href="#" class="social-link">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="bi bi-instagram"></i>
                            </a>
                        </div>
                    </div>

                    <div class="copyright-section">
                        <p class="mb-0">
                            <strong>JUNIA Maroc</strong><br>
                            Copyright © Junia Maroc 2025<br>
                            Tous droits réservés
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
</body>
</html>