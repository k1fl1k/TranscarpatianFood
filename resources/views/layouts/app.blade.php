<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <title>@yield('title', config('app.name', 'Смак Закарпаття'))</title>
    </head>
    <body class="font-sans antialiased d-flex flex-column min-vh-100">
        <div class="flex-grow-1 d-flex flex-column">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

        </div>
        <!-- Footer -->
        <footer class="bg-dark text-white py-4 mt-auto">
                <div class="container d-flex flex-column flex-md-row justify-content-between">
                    <div>
                        <h5>Смак Закарпаття</h5>
                        <p>© {{ date('Y') }} Всі права захищені</p>
                    </div>
                    <div>
                        <h6>Контакти</h6>
                        <p>Email: info@zakarpattiataste.com<br>Тел: +38 (099) 123-45-67</p>
                    </div>
                    <div>
                        <h6>Соцмережі</h6>
                        <a href="#" class="text-white me-2">Instagram</a>
                        <a href="#" class="text-white me-2">Facebook</a>
                        <a href="#" class="text-white">TikTok</a>
                    </div>
                </div>
            </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
