@extends('layouts.app')

@section('content')
  <h1>Informations de l’utilisateur connecté</h1>
  <table class="min-w-full bg-white text-black border border-gray-300 mb-8">
    <thead>
      <tr class="border-b">
        <th class="px-4 py-2 text-left">Clé</th>
        <th class="px-4 py-2 text-left">Valeur</th>
      </tr>
    </thead>
    <tbody>
      <tr class="border-b">
        <td class="px-4 py-2">ID</td>
        <td class="px-4 py-2">{{ $user->id }}</td>
      </tr>
      <tr class="border-b">
        <td class="px-4 py-2">Nom</td>
        <td class="px-4 py-2">{{ $user->nom }}</td>
      </tr>
      <tr class="border-b">
        <td class="px-4 py-2">Prénom</td>
        <td class="px-4 py-2">{{ $user->prenom }}</td>
      </tr>
      <tr class="border-b">
        <td class="px-4 py-2">Rôle</td>
        <td class="px-4 py-2">{{ $user->role }}</td>
      </tr>
      <tr>
        <td class="px-4 py-2">Email</td>
        <td class="px-4 py-2">{{ $user->email }}</td>
      </tr>
    </tbody>
  </table>

  <h2>Cookies</h2>
  <table class="min-w-full bg-white text-black border border-gray-300 mb-8">
    <thead>
      <tr class="border-b">
        <th class="px-4 py-2 text-left">Nom du cookie</th>
        <th class="px-4 py-2 text-left">Valeur</th>
      </tr>
    </thead>
    <tbody>
      @foreach($cookies as $name => $value)
        <tr class="border-b">
          <td class="px-4 py-2">{{ $name }}</td>
          <td class="px-4 py-2 break-all">{{ $value }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <h2>Session</h2>
  <table class="min-w-full bg-white text-black border border-gray-300">
    <thead>
      <tr class="border-b">
        <th class="px-4 py-2 text-left">Clé de session</th>
        <th class="px-4 py-2 text-left">Valeur</th>
      </tr>
    </thead>
    <tbody>
      @foreach($session as $key => $value)
        <tr class="border-b">
          <td class="px-4 py-2">{{ $key }}</td>
          <td class="px-4 py-2">
            @if(is_scalar($value))
              {{ $value }}
            @else
              <pre class="whitespace-pre-wrap">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
