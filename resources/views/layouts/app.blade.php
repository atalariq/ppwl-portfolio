<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-gray-50 text-gray-900 antialiased">
    @include('partials.navbar')

    <main class="mx-auto max-w-4xl px-4 py-8 grow">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
