@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center py-4">
          <h3 class="mb-0 fw-bold">
            <i class="fas fa-signature me-2"></i>
            Signature du Directeur Pédagogique
          </h3>
        </div>
        
        <div class="card-body p-4">
          <!-- Informations élève -->
          <div class="alert alert-info border-0 mb-4">
            <div class="d-flex align-items-center">
              <i class="fas fa-user-graduate fa-2x text-primary me-3"></i>
              <div>
                <h5 class="mb-1">Bulletin de notes</h5>
                <p class="mb-0 fw-semibold">{{ $eleve->user->nom }} {{ $eleve->user->prenom }}</p>
              </div>
            </div>
          </div>

          <!-- Signature existante -->
          @if(!empty($eleve->signature_dp))
            <div class="mb-4">
              <div class="alert alert-success border-0">
                <div class="d-flex align-items-center mb-2">
                  <i class="fas fa-check-circle text-success me-2"></i>
                  <strong>Signature déjà enregistrée</strong>
                </div>
                <div class="text-center">
                  <img src="{{ asset('storage/' . $eleve->signature_dp) }}" 
                       alt="Signature DP" 
                       class="img-fluid rounded shadow-sm"
                       style="max-height: 120px; border: 2px solid #e9ecef;">
                </div>
                <small class="text-muted mt-2 d-block">
                  <i class="fas fa-info-circle me-1"></i>
                  Vous pouvez remplacer cette signature en créant une nouvelle
                </small>
              </div>
            </div>
          @endif

          <!-- Formulaire de signature -->
          <form method="POST" action="{{ route('bulletins.sign', $eleve->id_eleve) }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
              <label class="form-label fw-semibold mb-3">
                <i class="fas fa-pen-fancy me-2"></i>
                Dessinez votre signature ci-dessous :
              </label>
              
              <div class="signature-container position-relative">
                <canvas id="dp-canvas" 
                        width="500" 
                        height="250"
                        class="signature-canvas rounded shadow-sm"></canvas>
                
                <!-- Indicateur d'état -->
                <div id="signature-status" class="signature-status">
                  <i class="fas fa-pencil-alt me-2"></i>
                  Cliquez et glissez pour signer
                </div>
              </div>
            </div>

            <!-- Boutons d'action -->
            <div class="d-flex gap-3 justify-content-center flex-wrap">
              <button type="button" id="clear-sign" class="btn btn-outline-secondary">
                <i class="fas fa-eraser me-2"></i>
                Effacer
              </button>
              
              <button type="button" id="undo-sign" class="btn btn-outline-warning">
                <i class="fas fa-undo me-2"></i>
                Annuler
              </button>
              
              <button type="submit" id="save-signature" class="btn btn-primary px-4" disabled>
                <i class="fas fa-save me-2"></i>
                Enregistrer ma signature
              </button>
            </div>

            <input type="hidden" name="signature_data" id="signature_data">
          </form>
        </div>
        
        <div class="card-footer bg-light text-center py-3">
          <small class="text-muted">
            <i class="fas fa-shield-alt me-1"></i>
            Votre signature sera sécurisée et associée à ce bulletin
          </small>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Styles personnalisés -->
<style>
.signature-canvas {
  border: 2px dashed #dee2e6;
  background: #ffffff;
  display: block;
  margin: 0 auto;
  cursor: crosshair;
  transition: all 0.3s ease;
}

.signature-canvas:hover {
  border-color: #0d6efd;
  box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

.signature-canvas.signing {
  border-color: #198754;
  border-style: solid;
}

.signature-container {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 20px;
  border-radius: 10px;
}

.signature-status {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #6c757d;
  font-size: 0.9rem;
  pointer-events: none;
  transition: opacity 0.3s ease;
}

.signature-status.hidden {
  opacity: 0;
}

.card {
  border-radius: 15px;
  overflow: hidden;
}

.card-header {
  border-radius: 15px 15px 0 0 !important;
}

.btn {
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-1px);
}

.alert {
  border-radius: 10px;
}

@media (max-width: 576px) {
  .signature-canvas {
    width: 100%;
    height: 200px;
  }
  
  .d-flex.gap-3 {
    flex-direction: column;
  }
  
  .btn {
    width: 100%;
  }
}
</style>

<!-- SignaturePad + script amélioré -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const canvas = document.getElementById('dp-canvas');
  const signatureStatus = document.getElementById('signature-status');
  const saveBtn = document.getElementById('save-signature');
  const clearBtn = document.getElementById('clear-sign');
  const undoBtn = document.getElementById('undo-sign');
  const signatureData = document.getElementById('signature_data');
  
  // Configuration du SignaturePad
  const signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgba(255,255,255,0)',
    penColor: 'rgb(0, 0, 0)',
    minWidth: 1,
    maxWidth: 3,
    throttle: 16,
    minDistance: 5,
  });

  // Historique pour la fonction annuler
  let signatureHistory = [];
  
  // Ajuster la taille du canvas pour les écrans haute résolution
  function resizeCanvas() {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    const rect = canvas.getBoundingClientRect();
    
    canvas.width = rect.width * ratio;
    canvas.height = rect.height * ratio;
    canvas.getContext('2d').scale(ratio, ratio);
    canvas.style.width = rect.width + 'px';
    canvas.style.height = rect.height + 'px';
    
    signaturePad.clear();
  }

  // Événements du SignaturePad
  signaturePad.addEventListener('beginStroke', () => {
    canvas.classList.add('signing');
    signatureStatus.classList.add('hidden');
    // Sauvegarder l'état actuel pour l'annulation
    signatureHistory.push(signaturePad.toData());
  });

  signaturePad.addEventListener('endStroke', () => {
    canvas.classList.remove('signing');
    updateButtonStates();
  });

  // Fonction pour mettre à jour l'état des boutons
  function updateButtonStates() {
    const isEmpty = signaturePad.isEmpty();
    saveBtn.disabled = isEmpty;
    undoBtn.disabled = signatureHistory.length === 0;
    
    if (isEmpty) {
      signatureStatus.classList.remove('hidden');
      signatureStatus.innerHTML = '<i class="fas fa-pencil-alt me-2"></i>Cliquez et glissez pour signer';
    } else {
      signatureStatus.innerHTML = '<i class="fas fa-check-circle me-2 text-success"></i>Signature prête';
    }
  }

  // Bouton effacer
  clearBtn.addEventListener('click', () => {
    signaturePad.clear();
    signatureHistory = [];
    signatureData.value = '';
    updateButtonStates();
    
    // Animation de feedback
    clearBtn.classList.add('btn-outline-danger');
    setTimeout(() => clearBtn.classList.remove('btn-outline-danger'), 200);
  });

  // Bouton annuler
  undoBtn.addEventListener('click', () => {
    if (signatureHistory.length > 0) {
      signatureHistory.pop(); // Retirer l'état actuel
      const previousState = signatureHistory[signatureHistory.length - 1];
      
      if (previousState) {
        signaturePad.fromData(previousState);
      } else {
        signaturePad.clear();
        signatureHistory = [];
      }
      updateButtonStates();
    }
  });

  // Soumission du formulaire
  canvas.closest('form').addEventListener('submit', function(e) {
    if (signaturePad.isEmpty()) {
      e.preventDefault();
      
      // Animation d'erreur
      canvas.style.borderColor = '#dc3545';
      setTimeout(() => {
        canvas.style.borderColor = '';
      }, 1000);
      
      // Alert améliorée
      const alert = document.createElement('div');
      alert.className = 'alert alert-danger alert-dismissible fade show mt-3';
      alert.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Attention !</strong> Veuillez dessiner votre signature avant d'enregistrer.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      canvas.closest('.mb-4').appendChild(alert);
      
      return false;
    }
    
    // Sauvegarder les données de signature
    signatureData.value = signaturePad.toDataURL('image/png');
    
    // Animation de soumission
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
    saveBtn.disabled = true;
  });

  // Initialisation
  resizeCanvas();
  updateButtonStates();
  
  // Redimensionner au besoin
  window.addEventListener('resize', resizeCanvas);
});
</script>

@endsection