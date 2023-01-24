<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'Dice205') }}</title>
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Vite Import -->
    @vite(['resources/theme/sb-admin-free/vendor/fontawesome-free/css/all.min.css'])
    @vite(['resources/theme/sb-admin-free/css/sb-admin-2.min.css'])
    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
