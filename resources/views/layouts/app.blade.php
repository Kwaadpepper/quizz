<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="//fonts.bunny.net" rel="dns-prefetch">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @if (config('app.debug'))
        <script nonce="{{ $nonce }}">
            window.vueDebug = true;
        </script>
    @endif
    @include('modules.script-constants')
    @include('modules.script-routes')
    @vite(['resources/sass/app.scss', 'resources/ts/app.ts'])
</head>

<body>
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>
