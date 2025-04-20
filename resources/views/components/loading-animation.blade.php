@props(['show' => false])

<div id="loading-animation" class="loading-container" style="display: {{ $show ? 'flex' : 'none' }}">
    <div class="background-gradient">
        <div class="light-effect"></div>
    </div>
    <div class="content-wrapper">
        <div class="junia-icon-container">
            <div class="orbit-container">
                <div class="orbit orbit1"></div>
                <div class="orbit orbit2"></div>
                <div class="orbit orbit3"></div>
            </div>
            
            <div class="sparkles">
                <div class="sparkle s1"></div>
                <div class="sparkle s2"></div>
                <div class="sparkle s3"></div>
                <div class="sparkle s4"></div>
            </div>

            <div class="junia-icon animate-float">
                <div class="icon-left animate-glow">
                    <div class="score-card">
                        <div class="header-lines">
                            <div class="header-line animate-width"></div>
                            <div class="header-line animate-width" style="animation-delay: 0.2s"></div>
                        </div>
                        <div class="score-lines">
                            <div class="line line1 animate-width" style="animation-delay: 0.4s"></div>
                            <div class="line line2 animate-width" style="animation-delay: 0.6s"></div>
                            <div class="line line3 animate-width" style="animation-delay: 0.8s"></div>
                            <div class="line line4 animate-width" style="animation-delay: 1s"></div>
                            <div class="line line5 animate-width" style="animation-delay: 1.2s"></div>
                        </div>
                        <div class="margin-line"></div>
                    </div>
                </div>
                <div class="icon-right">
                    <div class="stats-container">
                        <div class="stat-bar bar1 animate-height"></div>
                        <div class="stat-bar bar2 animate-height" style="animation-delay: 0.3s"></div>
                        <div class="stat-bar bar3 animate-height" style="animation-delay: 0.6s"></div>
                    </div>
                </div>
            </div>
            <div class="icon-title animate-fade-in">
                <span class="title-main">JUNIA MAROC</span>
                <span class="title-sub">Gestion Académique</span>
            </div>
        </div>
    </div>
</div>

<style>
.loading-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

.content-wrapper {
    position: absolute;
    top: 50vh;
    left: 50vw;
    transform: translate(-50%, -50%);
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
}

.background-gradient {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: linear-gradient(135deg, #4B2E83 0%, #6b42b3 50%, #FF5733 100%);
    z-index: -1;
}

.light-effect {
    position: fixed;
    top: 50vh;
    left: 50vw;
    transform: translate(-50%, -50%);
    width: 40vmin;
    height: 40vmin;
    background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.1) 30%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    animation: pulse-light 4s ease-in-out infinite;
}

.junia-icon-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-family: 'Poppins', 'Arial', sans-serif;
    padding: 0;
    position: relative;
    z-index: 1;
    transform-origin: center center;
}

.junia-icon {
    width: clamp(100px, 15vmin, 180px);
    height: clamp(100px, 15vmin, 180px);
    background: #2D1A3F;
    border-radius: 16px;
    padding: clamp(15px, 2vmin, 25px);
    display: flex;
    gap: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    position: relative;
}

.icon-left {
    flex: 1.2;
    display: flex;
    flex-direction: column;
}

.score-card {
    background: white;
    border-radius: 8px;
    padding: 12px 12px 12px 24px;
    position: relative;
    height: 100%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.header-lines {
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 2px solid rgba(45,26,63,0.1);
}

.header-line {
    height: 2px;
    background: rgba(45,26,63,0.1);
    margin: 4px 0;
    width: 60%;
}

.score-lines .line {
    height: 2px;
    background: rgba(45,26,63,0.08);
    margin: 8px 0;
    border-radius: 1px;
}

.line1 { width: 90%; }
.line2 { width: 75%; }
.line3 { width: 85%; }
.line4 { width: 70%; }
.line5 { width: 80%; }

.margin-line {
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 1px;
    background: rgba(45,26,63,0.1);
}

.icon-right {
    flex: 0.8;
    display: flex;
    align-items: flex-end;
}

.stats-container {
    display: flex;
    align-items: flex-end;
    gap: 6px;
    height: 100%;
    padding-bottom: 8px;
}

.stat-bar {
    width: 8px;
    background: #FF5733;
    border-radius: 4px 4px 1px 1px;
    position: relative;
}

.bar1 { 
    height: 50%;
    background: linear-gradient(180deg, #FF7F50, #FF5733);
}

.bar2 { 
    height: 80%;
    background: linear-gradient(180deg, #FF7F50, #FF5733);
}

.bar3 { 
    height: 65%;
    background: linear-gradient(180deg, #FF7F50, #FF5733);
}

.icon-title {
    margin-top: 16px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.title-main {
    color: white;
    font-size: 24px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.title-sub {
    color: rgba(255,255,255,0.9);
    font-size: 16px;
    font-weight: 500;
    letter-spacing: 1px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes vibrate {
    0%, 100% {
        transform: translate(0);
    }
    25% {
        transform: translate(-2px, 2px);
    }
    50% {
        transform: translate(2px, -2px);
    }
    75% {
        transform: translate(-2px, -2px);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-spin-slow {
    animation: spin-slow 3s linear infinite;
}

.animate-vibrate {
    animation: vibrate 0.3s ease-in-out infinite;
}

.animate-float {
    animation: improved-float 4s ease-in-out infinite;
}

.animate-fade-in {
    animation: fade-in 1s ease-out forwards;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

@media (max-width: 768px) {
    .junia-icon {
        width: clamp(80px, 12vmin, 120px);
        height: clamp(80px, 12vmin, 120px);
        padding: clamp(10px, 1.5vmin, 15px);
    }
    
    .title-main {
        font-size: clamp(16px, 3vmin, 20px);
    }
    
    .title-sub {
        font-size: clamp(12px, 2vmin, 16px);
    }

    .score-card {
        padding: clamp(8px, 1vmin, 12px);
    }

    .stat-bar {
        width: clamp(4px, 0.8vmin, 6px);
    }
}

@media (min-width: 769px) and (max-width: 1200px) {
    .junia-icon {
        width: clamp(120px, 18vmin, 160px);
        height: clamp(120px, 18vmin, 160px);
    }
}

@media (orientation: landscape) and (max-height: 500px) {
    .junia-icon {
        width: clamp(80px, 15vh, 120px);
        height: clamp(80px, 15vh, 120px);
    }
    
    .icon-title {
        margin-top: 8px;
    }
}

.orbit-container {
    position: absolute;
    width: 200%;
    height: 200%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
}

.orbit {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: orbit 4s linear infinite;
}

.orbit1 {
    width: 120%;
    height: 120%;
    animation-duration: 3s;
    border-top-color: rgba(255, 87, 51, 0.5);
}

.orbit2 {
    width: 140%;
    height: 140%;
    animation-duration: 5s;
    animation-direction: reverse;
    border-right-color: rgba(75, 46, 131, 0.5);
}

.orbit3 {
    width: 160%;
    height: 160%;
    animation-duration: 7s;
    border-bottom-color: rgba(255, 255, 255, 0.3);
}

.sparkles {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.sparkle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: white;
    border-radius: 50%;
    animation: sparkle 2s ease-in-out infinite;
}

.s1 { top: 20%; left: 20%; animation-delay: 0s; }
.s2 { top: 20%; right: 20%; animation-delay: 0.5s; }
.s3 { bottom: 20%; left: 20%; animation-delay: 1s; }
.s4 { bottom: 20%; right: 20%; animation-delay: 1.5s; }

@keyframes orbit {
    from {
        transform: translate(-50%, -50%) rotate(0deg);
    }
    to {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

@keyframes sparkle {
    0%, 100% {
        transform: scale(1);
        opacity: 0;
    }
    50% {
        transform: scale(2);
        opacity: 1;
    }
}

.animate-width {
    animation: width-animate 2s ease-in-out infinite;
    transform-origin: left;
}

@keyframes width-animate {
    0%, 100% { width: 60%; opacity: 0.5; }
    50% { width: 90%; opacity: 1; }
}

.animate-height {
    animation: height-animate 1.5s ease-in-out infinite;
    transform-origin: bottom;
}

@keyframes height-animate {
    0%, 100% { height: 50%; opacity: 0.7; }
    50% { height: 100%; opacity: 1; }
}

.animate-glow {
    animation: glow 2s ease-in-out infinite;
}

@keyframes glow {
    0%, 100% {
        filter: brightness(1);
    }
    50% {
        filter: brightness(1.2);
    }
}

@keyframes improved-float {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    25% {
        transform: translateY(-10px) rotate(2deg);
    }
    75% {
        transform: translateY(8px) rotate(-2deg);
    }
}

@keyframes pulse-light {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.2);
        opacity: 0.5;
    }
}
</style>