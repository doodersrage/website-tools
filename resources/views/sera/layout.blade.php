<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CM Sera Tool')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="sera-body">
    <main class="sera-container">
        @yield('content')
    </main>
</body>
</html>
