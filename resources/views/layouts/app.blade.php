<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EsferaSaúde - Gestão de Formação</title>
    <link rel="shortcut icon" href="/images/esferafavicon.png" type="image/x-icon" />

    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main-component
            :auth="{{ json_encode(auth()->user()) }}"
        >
            @yield('content')
        </main-component>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
