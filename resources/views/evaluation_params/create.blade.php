{{-- resources/views/evaluation_params/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1>Créer un Paramétrage d'Évaluation</h1>

  {{-- Professeur --}}
  <div class="mb-3">
    <strong>Professeur :</strong>
    {{ $prof->user->prenom }} {{ $prof->user->nom }}
  </div>

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
    <div class="mb-3 row">
      <label for="id_matiere" class="col-sm-4 col-form-label">Matière</label>
      <div class="col-sm-8">
        <select name="id_matiere" id="id_matiere" class="form-control" required>
          <option value="">Choisir une matière</option>
          @foreach($matieres as $m)
            <option value="{{ $m->id_matiere }}"
              {{ old('id_matiere')==$m->id_matiere?'selected':'' }}>
              {{ $m->intitule }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    {{-- Nombre de DS --}}
    <div class="mb-3 row">
      <label for="totalDS" class="col-sm-4 col-form-label">Nombre de DS</label>
      <div class="col-sm-8">
        <input
          type="number"
          name="total"
          id="totalDS"
          class="form-control"
          value="{{ old('total', 0) }}"
          min="0"
          max="7"
          required>
      </div>
    </div>

    <hr>

    {{-- Somme des % --}}
    <div class="alert alert-info mb-3">
      <strong>Total des pourcentages : <span id="totalPercentage">0.00</span>%</strong>
      <div id="percentageWarning" class="text-danger mt-1" style="display:none;">
        Le total doit être égal à 100 %.
      </div>
    </div>

    {{-- Tableau DS + Examen --}}
    <table class="table table-bordered" id="evalTable">
      <thead>
        <tr>
          <th>Type</th>
          <th style="width:25%">Pourcentage (%)</th>
          <th style="width:25%">Nb d’épreuves</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <button type="submit" id="submitBtn" class="btn btn-primary">
      Enregistrer le paramétrage
    </button>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const totalInput   = document.getElementById('totalDS');
  const tbody        = document.querySelector('#evalTable tbody');
  const pctSpan      = document.getElementById('totalPercentage');
  const pctWarn      = document.getElementById('percentageWarning');
  const submitBtn    = document.getElementById('submitBtn');
  const form         = document.getElementById('totalEvalForm');

  // Anciennes valeurs
  const oldEvals = @json(old('evaluations', []));
  let oldDSPct   = 25, oldExamPct = 75;
  oldEvals.forEach(ev => {
    if(ev.type === 'DS')   oldDSPct   = ev.pourcentage;
    if(ev.type === 'EXAM') oldExamPct = ev.pourcentage;
  });

  function renderRows(){
    const dsCount = Math.min(Math.max(parseInt(totalInput.value)||0, 0), 7);
    totalInput.value = dsCount;
    tbody.innerHTML = '';

    // DS
    if(dsCount > 0){
      addRow({
        type:        'DS',
        label:       'DS',
        pourcentage: oldDSPct,
        nombre:      dsCount,
        idx:         0,
        editableNb:  true
      });
    }

    // Examen final
    const examIdx = dsCount > 0 ? 1 : 0;
    const examPct = dsCount > 0 ? oldExamPct : 100;
    addRow({
      type:        'EXAM',
      label:       'Examen final',
      pourcentage: examPct,
      nombre:      1,
      idx:         examIdx,
      editableNb:  false
    });

    calculateTotal();
  }

  function addRow({type,label,pourcentage,nombre,idx,editableNb}){
    const tr = document.createElement('tr');
    tr.dataset.type = type;
    tr.innerHTML = `
      <td>
        <input type="hidden" name="evaluations[${idx}][type]" value="${type}">
        ${label}
      </td>
      <td>
        <input
          type="number"
          name="evaluations[${idx}][pourcentage]"
          class="form-control pct"
          value="${pourcentage}"
          step="0.01"
          min="0"
          max="100"
          required>
      </td>
      <td>
        <input
          type="number"
          name="evaluations[${idx}][nombre_evaluations]"
          class="form-control nb"
          value="${nombre}"
          min="1"
          ${editableNb?'':'readonly'}>
      </td>`;
    tbody.appendChild(tr);

    tr.querySelector('.pct').addEventListener('input', onPctChange);
    if(editableNb){
      tr.querySelector('.nb').addEventListener('input', calculateTotal);
    }
  }

  function onPctChange(e){
    const tr = e.target.closest('tr');
    if(tr.dataset.type === 'EXAM'){
      const examVal = parseFloat(e.target.value)||0;
      const dsTr = tbody.querySelector('tr[data-type="DS"]');
      if(dsTr){
        dsTr.querySelector('.pct').value = Math.max(0,100 - examVal).toFixed(2);
      }
    }
    calculateTotal();
  }

  function calculateTotal(){
    let sum=0;
    tbody.querySelectorAll('.pct').forEach(inp => sum += parseFloat(inp.value)||0);
    pctSpan.textContent = sum.toFixed(2);
    const bad = Math.abs(sum - 100) > 0.01;
    pctWarn.style.display = bad?'block':'none';
    submitBtn.disabled = bad;
  }

  totalInput.addEventListener('input', renderRows);
  form.addEventListener('submit', e => {
    if(Math.abs(parseFloat(pctSpan.textContent) - 100) > 0.01){
      e.preventDefault();
      alert('La somme des pourcentages doit être de 100 %.');
    }
  });

  renderRows();
});
</script>
@endpush
