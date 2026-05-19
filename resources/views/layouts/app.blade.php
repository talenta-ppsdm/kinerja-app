<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Manajemen Talenta') }}</title>

       <!-- Link Font & Ikon -->
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/struktur.css') }}">
        <link rel="stylesheet" href="{{ asset('css/pintasan.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
        <script src="https://unpkg.com/feather-icons"></script>
    </head>

    <body>
        <nav class="navbar">
            <ul class="navbar__menu">
                <li class="navbar__item">
                <a href="{{ url('/') }}" class="navbar__link"><i data-feather="home"></i><span>Home</span></a>
                </li>
                <li class="navbar__item">
                <a href="{{ route('penyusunan.index') }}" class="navbar__link"><i data-feather="folder-plus"></i><span>Monitoring Penyusunan</span></a>        
                </li>
                <li class="navbar__item">
                <a href="{{ route('evaluasi.index') }}" class="navbar__link"><i data-feather="folder-minus"></i><span>Monitoring Evaluasi</span></a>        
                </li>
                <li class="navbar__item">
                <a href="{{ route('pintasan.index') }}" class="navbar__link"><i data-feather="folder"></i><span>Pintasan</span></a>        
                </li>
                <li class="navbar__item">
                <a href="#" class="navbar__link"><i data-feather="help-circle"></i><span>Help</span></a>        
                </li>
                <li class="navbar__item">
                <a href="#" class="navbar__link"><i data-feather="settings"></i><span>Settings</span></a>        
                </li>
            </ul>
        </nav>

        <!-- Bagian ini akan diisi oleh konten halaman masing-masing -->
        <main>
            @yield('content')
        </main>
        
        <script>
            feather.replace();
        </script>
        <script src="assets/js/oneui.core.min.js"></script>
        <script src="assets/js/oneui.app.min.js"></script>
        @stack('js')
    </body>

</html>