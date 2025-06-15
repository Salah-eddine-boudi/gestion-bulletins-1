@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container my-4">

    <h1 class="mb-4 text-center">Informations de l’utilisateur connecté</h1>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Clé</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ID</td>
                    <td>{{ $user->id }}</td>
                </tr>
                <tr>
                    <td>Nom</td>
                    <td>{{ $user->nom }}</td>
                </tr>
                <tr>
                    <td>Prénom</td>
                    <td>{{ $user->prenom }}</td>
                </tr>
                <tr>
                    <td>Rôle</td>
                    <td>{{ ucfirst($user->role) }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $user->email }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h2 class="mt-5 mb-4 text-center">Cookies Actifs</h2>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nom du Cookie</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cookies as $name => $value)
                    <tr>
                        <td>{{ $name }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="mt-5 mb-4 text-center">Données de Session</h2>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Clé de Session</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($session as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>
                            @if(is_scalar($value))
                                {{ $value }}
                            @else
                                <pre class="mb-0">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection