{{-- resources/views/unites_enseignement/_rows.blade.php --}}
@foreach($unites as $ue)
<tr>
  <td>{{ $ue->id_ue }}</td>
  <td>{{ $ue->intitule }}</td>
  <td>{{ $ue->type }}</td>
  <td>{{ $ue->niveau_scolaire }}</td>
  <td>{{ $ue->code }}</td>
  <td>{{ $ue->annee_universitaire }}</td>
  <td class="text-center">
    <a href="{{ route('unites-enseignement.show',$ue->id_ue) }}" class="btn btn-info btn-sm">👁</a>
    <a href="{{ route('unites-enseignement.edit',$ue->id_ue) }}" class="btn btn-warning btn-sm">✏</a>
    <form action="{{ route('unites-enseignement.destroy',$ue->id_ue) }}" method="POST" class="d-inline">
      @csrf @method('DELETE')
      <button class="btn btn-danger btn-sm" onclick="return confirm('Confirmer ?')">🗑</button>
    </form>
  </td>
</tr>
@endforeach
