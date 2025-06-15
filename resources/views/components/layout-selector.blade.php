<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaires Stylisés</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6A4C93 0%, #8B5A87 25%, #B85450 50%, #E67E22 75%, #F39C12 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            margin: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
        }

        .form-container:hover::before {
            left: 100%;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .form-title {
            color: white;
            font-size: 1.2em;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .custom-select {
            position: relative;
            display: inline-block;
            width: 100%;
            margin-bottom: 15px;
        }

        .select-wrapper {
            position: relative;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .select-wrapper:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.02);
        }

        .select-wrapper::after {
            content: '▼';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6A4C93;
            font-size: 12px;
            pointer-events: none;
            transition: transform 0.3s ease;
        }

        .select-wrapper.active::after {
            transform: translateY(-50%) rotate(180deg);
        }

        select {
            width: 100%;
            padding: 15px 45px 15px 15px;
            border: none;
            background: transparent;
            font-size: 16px;
            color: #333;
            cursor: pointer;
            appearance: none;
            outline: none;
            transition: all 0.3s ease;
        }

        select:focus {
            background: rgba(106, 76, 147, 0.1);
        }

        .form-label {
            display: block;
            color: white;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .btn {
            background: linear-gradient(45deg, #6A4C93, #B85450, #E67E22);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(106, 76, 147, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(106, 76, 147, 0.6);
        }

        .btn:active {
            transform: translateY(0);
        }

        .mb-3 {
            margin-bottom: 20px;
        }

        .layout-form {
            min-width: 250px;
        }

        .accueil-form {
            max-width: 400px;
            width: 100%;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container {
            animation: fadeInUp 0.6s ease-out;
        }

        .form-container:nth-child(2) {
            animation-delay: 0.2s;
        }

        .ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 1;
            }
            20% {
                transform: scale(25, 25);
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }

        .ripple:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                margin: 10px;
            }
            
            .accueil-form {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="form-container layout-form">
        <div class="form-title">🎨 Sélection du Layout</div>
        <form method="POST" action="{{ route('user.setLayout') }}" style="display:inline;">
            @csrf
            <div class="custom-select">
                <div class="select-wrapper">
                    <select name="layout" onchange="this.form.submit()" class="ripple">
                        <option value="app" {{ session('layout', 'app') == 'app' ? 'selected' : '' }}>App</option>
                        <option value="admin" {{ session('layout') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="admin2" {{ session('layout') == 'admin2' ? 'selected' : '' }}>Admin 2</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="form-container accueil-form">
        <div class="form-title">🏠 Configuration Page d'Accueil</div>
        <form method="POST" action="{{ route('user.setAccueilPage') }}">
            @csrf
            <div class="mb-3">
                <label for="accueil_page" class="form-label">Page d'accueil à afficher :</label>
                <div class="custom-select">
                    <div class="select-wrapper">
                        <select name="accueil_page" id="accueil_page" class="ripple">
                            <option value="accueil" {{ session('accueil_page', 'accueil') == 'accueil' ? 'selected' : '' }}>Accueil Classique</option>
                            <option value="acceuil_2" {{ session('accueil_page') == 'acceuil_2' ? 'selected' : '' }}>Accueil 2</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn">Changer la page d'accueil</button>
        </form>
    </div>

    <script>
        // Animation pour les selects
        document.querySelectorAll('select').forEach(select => {
            const wrapper = select.closest('.select-wrapper');
            
            select.addEventListener('focus', () => {
                wrapper.classList.add('active');
            });
            
            select.addEventListener('blur', () => {
                wrapper.classList.remove('active');
            });
            
            select.addEventListener('change', () => {
                // Animation de confirmation
                wrapper.style.transform = 'scale(1.05)';
                wrapper.style.background = 'rgba(106, 76, 147, 0.2)';
                
                setTimeout(() => {
                    wrapper.style.transform = '';
                    wrapper.style.background = '';
                }, 200);
            });
        });

        // Effet de particules au survol des boutons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('mouseenter', function(e) {
                createParticles(e.target);
            });
        });

        function createParticles(element) {
            for (let i = 0; i < 6; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'absolute';
                particle.style.width = '4px';
                particle.style.height = '4px';
                particle.style.background = 'rgba(255, 255, 255, 0.8)';
                particle.style.borderRadius = '50%';
                particle.style.pointerEvents = 'none';
                particle.style.zIndex = '1000';
                
                const rect = element.getBoundingClientRect();
                particle.style.left = rect.left + Math.random() * rect.width + 'px';
                particle.style.top = rect.top + Math.random() * rect.height + 'px';
                
                document.body.appendChild(particle);
                
                // Animation des particules
                particle.animate([
                    { transform: 'translateY(0px) scale(1)', opacity: 1 },
                    { transform: 'translateY(-30px) scale(0)', opacity: 0 }
                ], {
                    duration: 1000,
                    easing: 'ease-out'
                }).onfinish = () => {
                    particle.remove();
                };
            }
        }

        // Animation d'apparition au chargement
        window.addEventListener('load', () => {
            document.querySelectorAll('.form-container').forEach((container, index) => {
                container.style.opacity = '0';
                container.style.transform = 'translateY(50px)';
                
                setTimeout(() => {
                    container.style.transition = 'all 0.6s ease-out';
                    container.style.opacity = '1';
                    container.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });

        // Effet de typing pour les titres
        function typeWriter(element, text, speed = 100) {
            let i = 0;
            element.innerHTML = '';
            
            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                }
            }
            type();
        }

        // Gestion du changement automatique pour le premier form
        document.querySelector('select[name="layout"]').addEventListener('change', function() {
            const wrapper = this.closest('.select-wrapper');
            wrapper.style.background = 'rgba(184, 84, 80, 0.2)';
            
            // Indicateur visuel de soumission
            const indicator = document.createElement('div');
            indicator.innerHTML = '✓ Changement appliqué';
            indicator.style.cssText = `
                position: absolute;
                top: -30px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(184, 84, 80, 0.9);
                color: white;
                padding: 5px 15px;
                border-radius: 15px;
                font-size: 12px;
                z-index: 100;
                animation: fadeInOut 2s ease-out forwards;
            `;
            
            wrapper.style.position = 'relative';
            wrapper.appendChild(indicator);
            
            setTimeout(() => {
                if (indicator.parentNode) {
                    indicator.remove();
                }
                wrapper.style.background = '';
            }, 2000);
        });

        // Animation CSS pour l'indicateur
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translateX(-50%) translateY(10px); }
                50% { opacity: 1; transform: translateX(-50%) translateY(0); }
                100% { opacity: 0; transform: translateX(-50%) translateY(-10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>