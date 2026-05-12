<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>@yield('title')</title>

        <meta name="description" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework">
        <meta property="og:site_name" content="OneUI">
        <meta property="og:description" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href={{ asset("assets/media/favicons/favicon.png") }}>
        <link rel="icon" type="image/png" sizes="192x192" href={{ asset("assets/media/favicons/favicon-192x192.png") }}>
        <link rel="apple-touch-icon" sizes="180x180" href={{ asset("assets/media/favicons/apple-touch-icon-180x180.png") }}>
        <!-- END Icons -->

        <!-- Stylesheets -->
        @stack('css')
        <!-- Fonts and OneUI framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
        <link rel="stylesheet" id="css-main" href={{ asset("assets/css/oneui.min.css") }}>
        <!-- END Stylesheets -->
    </head>
    <body>
        <!-- Page Container -->
        <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
            <!-- Sidebar -->
            @include('layouts.nav')
            <!-- END Sidebar -->

            <!-- Header -->
            @include('layouts.header')
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">
                @yield('hero')
                @yield('content')
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            @include('layouts.footer')
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <script src={{ asset("assets/js/oneui.core.min.js") }}></script>
        <script src={{ asset("assets/js/oneui.app.min.js") }}></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @stack('js')
    </body>
</html>