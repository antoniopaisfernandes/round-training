<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RoundTraining</title>
    <link rel="shortcut icon" href="/images/favicon.png" type="image/x-icon" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <toast></toast>
        <app-layout
            ref="eApp"
            :auth="{{ json_encode(auth()->user()) }}"
        >
            @yield('content')
        </app-layout>
    </div>
</body>
</html>
