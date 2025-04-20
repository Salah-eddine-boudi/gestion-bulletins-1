<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - session exprirée | JUNIA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #6b46c1 0%, #f43f5e 100%);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .navbar {
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar img {
            height: 40px;
        }

        .error-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .error-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            color: white;
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.5s ease-out;
            transform-style: preserve-3d;
            perspective: 1000px;
            transition: transform 0.1s ease-out;
            position: relative;
            overflow: visible;
        }

        .error-card-content {
            position: relative;
            z-index: 2;
            transform-style: preserve-3d;
        }

        .error-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(125deg,
                rgba(255, 255, 255, 0.3) 0%,
                rgba(255, 255, 255, 0.2) 40%,
                rgba(255, 255, 255, 0.1) 60%,
                rgba(255, 255, 255, 0.05) 100%
            );
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            z-index: 1;
            border-radius: 20px;
        }

        .error-card:hover::before {
            opacity: 1;
        }

        .lock-icon {
            font-size: 2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            transform: translateZ(20px);
        }

        .error-code {
            font-size: 6rem;
            font-weight: 700;
            margin: 0;
            line-height: 1;
            color: #ff4f5e;
            margin-bottom: 1rem;
            transform: translateZ(30px);
        }

        .error-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            transform: translateZ(25px);
        }

        .error-message {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
            font-size: 0.95rem;
            line-height: 1.5;
            transform: translateZ(15px);
        }

        .home-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            margin-top: 1rem;
            transition: all 0.3s ease;
            transform: translateZ(35px);
            cursor: pointer;
        }

        .home-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateZ(35px) translateY(-2px);
        }

        .help-section {
            margin-top: 2rem;
            transform-style: preserve-3d;
            position: relative;
            z-index: 5;
        }

        .help-text {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 1rem;
            transform: translateZ(20px);
        }

        .help-links {
            display: flex;
            gap: 2rem;
            justify-content: center;
            transform: translateZ(30px);
            position: relative;
            z-index: 10;
        }

        .help-links a {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            opacity: 0.8;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            cursor: pointer;
            transform-style: preserve-3d;
            transform: translateZ(0);
            will-change: transform, opacity, background;
        }

        .help-links a:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px) translateZ(0);
        }

        .help-links a:active {
            transform: translateY(1px) translateZ(0);
        }

        .help-links i {
            font-size: 1.1rem;
            margin-right: 0.3rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .error-card {
                padding: 2rem;
                margin: 1rem;
            }

            .error-code {
                font-size: 4rem;
            }

            .error-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
   

    <div class="error-container">
        <div class="error-card">
            <div class="error-card-content">
                <div class="lock-icon">
                    <i class="fas fa-lock"></i>
                </div>

                <h1 class="error-code">419</h1>
                <h2 class="error-title">Session expirée</h2>

                <div class="error-message">
                    Votre session a expiré pour des raisons de sécurité. Veuillez actualiser la page et réessayer.<br>
                    Cela peut se produire si vous êtes resté inactif trop longtemps ou si votre requête est invalide.
                </div>

                <a href="{{ url('/') }}" class="home-button">
                    <i class="fas fa-home me-2"></i> Retour à l'accueil
                </a>

                <div class="help-section">
                    <div class="help-text">
                        Besoin d'aide ?
                    </div>

                    <div class="help-links">
                        <a href="{{ route('contact') }}">
                            <i class="fas fa-headset"></i>
                            <span>Support</span>
                        </a>
                        <a href="#">
                            <i class="fas fa-question-circle"></i>
                            <span>FAQ</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const card = document.querySelector('.error-card');
        const container = document.querySelector('.error-container');

        // Variables pour l'effet de perspective
        const maxRotation = 8;
        const maxMove = 10;

        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            const mouseX = e.clientX - centerX;
            const mouseY = e.clientY - centerY;

            const rotateY = (mouseX / rect.width) * maxRotation * 2;
            const rotateX = -(mouseY / rect.height) * maxRotation * 2;

            const translateX = (mouseX / rect.width) * maxMove;
            const translateY = (mouseY / rect.height) * maxMove;

            requestAnimationFrame(() => {
                card.style.transform = `
                    perspective(1000px)
                    rotateX(${rotateX}deg)
                    rotateY(${rotateY}deg)
                    translate3d(${translateX}px, ${translateY}px, 0)
                `;
            });
        });

        container.addEventListener('mouseleave', () => {
            requestAnimationFrame(() => {
                card.style.transform = `
                    perspective(1000px)
                    rotateX(0deg)
                    rotateY(0deg)
                    translate3d(0, 0, 0)
                `;
            });
        });
    });
    </script>
</body>
</html>