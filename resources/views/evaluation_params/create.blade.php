@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h1 class="h4 mb-0">Créer un paramétrage d’évaluation</h1>
    </div>
    <div class="card-body">
      {{-- Professeur --}}
      <p class="mb-4">
        <strong>Enseignant :</strong>
        {{ $prof->user->prenom }} {{ $prof->user->nom }}
      </p>

      {{-- Erreurs --}}
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('evaluation-params.store') }}" method="POST" id="totalEvalForm">
        @csrf

        {{-- Matière --}}
        <div class="row mb-3">
          <label for="id_matiere" class="col-sm-4 col-form-label">Matière</label>
          <div class="col-sm-8">
            <select
              id="id_matiere"
              name="id_matiere"
              class="form-select @error('id_matiere') is-invalid @enderror"
              required
            >
              <option value="">Sélectionnez une matière…</option>
              @foreach($matieres as $m)
                <option value="{{ $m->id_matiere }}"
                  {{ old('id_matiere') == $m->id_matiere ? 'selected' : '' }}>
                  {{ $m->intitule }}
                </option>
              @endforeach
            </select>
            @error('id_matiere')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Nombre de DS --}}
        <div class="row mb-3">
          <label for="totalDS" class="col-sm-4 col-form-label">Nombre de DS</label>
          <div class="col-sm-8">
            <input
              type="number"
              id="totalDS"
              name="total"
              class="form-control @error('total') is-invalid @enderror"
              value="{{ old('total', 0) }}"
              min="0" max="7"
              required
            >
            @error('total')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <hr>

        {{-- Override rattrapage --}}
        <div class="row mb-3">
          <div class="col-sm-4"></div>
          <div class="col-sm-8">
            <div class="form-check">
              <input
                class="form-check-input @error('override_rattrapage') is-invalid @enderror"
                type="checkbox"
                name="override_rattrapage"
                id="override_rattrapage"
                value="1"
                {{ old('override_rattrapage') ? 'checked' : '' }}
              >
              <label class="form-check-label" for="override_rattrapage">
                Activer l’override du pourcentage de rattrapage
              </label>
              @error('override_rattrapage')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        {{-- Pourcentages rattrapage / examen final --}}
        @foreach([
          'rattrapage'    => 'Pourcentage de rattrapage (%)',
          'examen_final'  => 'Pourcentage de l’examen final (%)',
        ] as $key => $label)
        <div class="row mb-4">
          <label for="pourcentage_{{ $key }}" class="col-sm-4 col-form-label">{{ $label }}</label>
          <div class="col-sm-8">
            <input
              type="number"
              id="pourcentage_{{ $key }}"
              name="pourcentage_{{ $key }}"
              class="form-control @error('pourcentage_'.$key) is-invalid @enderror"
              value="{{ old('pourcentage_'.$key, $key==='examen_final' ? 100 : 0) }}"
              min="0" max="100" step="0.01"
              required
            >
            @error('pourcentage_'.$key)
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        @endforeach

        {{-- Totaux dynamiques --}}
        <div class="alert alert-info d-flex justify-content-between align-items-center mb-3">
          <span>Total DS + Examen : <strong><span id="totalPercentage">0.00</span>%</strong></span>
          <span id="percentageWarning" class="text-danger" style="display:none;">
            Le total DS + Examen doit être égal à 100 %.
          </span>
        </div>

        <div class="alert alert-info d-flex justify-content-between align-items-center mb-4">
          <span>Total Rattrapage + Examen final : <strong><span id="totalRattExam">0.00</span>%</strong></span>
          <span id="rattExamWarning" class="text-danger" style="display:none;">
            Le total rattrapage + examen final doit être égal à 100 %.
          </span>
        </div>

        {{-- Tableau DS + Examen --}}
        <div class="table-responsive mb-4">
          <table class="table table-striped table-bordered" id="evalTable">
            <thead class="table-light">
              <tr>
                <th>Type</th>
                <th style="width:25%">Pourcentage (%)</th>
                <th style="width:25%">Nb d’épreuves</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        {{-- Bouton de validation --}}
        <div class="d-grid">
          <button type="submit" id="submitBtn" class="btn btn-success">
            Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const totalInput   = document.getElementById('totalDS');
  const tbody        = document.querySelector('#evalTable tbody');
  const pctSpan      = document.getElementById('totalPercentage');
  const pctWarn      = document.getElementById('percentageWarning');

  const rattInput    = document.getElementById('pourcentage_rattrapage');
  const examFinInput = document.getElementById('pourcentage_examen_final');
  const rattExamSpan = document.getElementById('totalRattExam');
  const rattExamWarn = document.getElementById('rattExamWarning');

  const submitBtn    = document.getElementById('submitBtn');
  const form         = document.getElementById('totalEvalForm');

  // ----- RENDER DES LIGNES DS + EXAM -----------------------------------------------------
  function renderRows () {
    let dsCount = parseInt(totalInput.value) || 0;
    if (dsCount < 0) dsCount = 0;
    if (dsCount > 7) dsCount = 7;
    totalInput.value = dsCount;

    tbody.innerHTML = '';

    // DS séparés
    for (let i = 0; i < dsCount; i++) {
      addRow({
        type : 'DS',             // <-- envoyé à Laravel
        label: 'DS ' + (i + 1),  // <-- affiché à l’écran
        pourcentage: 0,
        nombre: 1,
        idx: i
      });
    }

    // Examen final
    addRow({
      type : 'EXAM',
      label: 'Examen final',
      pourcentage: dsCount === 0 ? 100 : 0,
      nombre: 1,
      idx: dsCount
    });

    verifyTotals();
  }

  // ----- AJOUT D’UNE LIGNE --------------------------------------------------------------
  function addRow ({type, label, pourcentage, nombre, idx}) {
    const tr = document.createElement('tr');
    tr.dataset.type = type;
    tr.innerHTML = `
      <td>
        <input type="hidden" name="evaluations[${idx}][type]" value="${type}">
        ${label}
      </td>
      <td>
        <input type="number"
               name="evaluations[${idx}][pourcentage]"
               class="form-control pct"
               value="${pourcentage}"
               step="0.01" min="0" max="100" required>
      </td>
      <td>
        <input type="number"
               name="evaluations[${idx}][nombre_evaluations]"
               class="form-control"
               value="${nombre}"
               min="1" readonly>
      </td>`;
    tbody.appendChild(tr);

    tr.querySelector('.pct').addEventListener('input', verifyTotals);
  }

  // ----- VÉRIFICATIONS DES TOTAUX --------------------------------------------------------
  function verifyTotals () {
    // Total DS + EXAM
    let sumDSExam = 0;
    tbody.querySelectorAll('.pct').forEach(i => {
      sumDSExam += parseFloat(i.value) || 0;
    });
    pctSpan.textContent   = sumDSExam.toFixed(2);
    pctWarn.style.display = Math.abs(sumDSExam - 100) > 0.01 ? 'inline' : 'none';

    // Total Rattrapage + Examen final
    const r = parseFloat(rattInput.value) || 0;
    const e = parseFloat(examFinInput.value) || 0;
    const sumRatt = r + e;
    rattExamSpan.textContent   = sumRatt.toFixed(2);
    rattExamWarn.style.display = Math.abs(sumRatt - 100) > 0.01 ? 'inline' : 'none';

    // Bouton
    submitBtn.disabled = (pctWarn.style.display === 'inline' || rattExamWarn.style.display === 'inline');
  }

  // ----- ÉVÉNEMENTS ---------------------------------------------------------------------
  totalInput.addEventListener('input', renderRows);
  rattInput.addEventListener('input', verifyTotals);
  examFinInput.addEventListener('input', verifyTotals);

  form.addEventListener('submit', e => {
    if (submitBtn.disabled) {
      e.preventDefault();
      alert('Veuillez corriger les pourcentages pour qu’ils fassent tous 100 %.');
    }
  });

  // ----- INITIALISATION -----------------------------------------------------------------
  renderRows();
  verifyTotals();
});
</script>
@endpush
