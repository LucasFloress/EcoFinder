<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EcoFinder')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('components.header')
    
   <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        @hasSection('body')
            @yield('body')
        @else
            <main style="flex: 1; padding: 2rem;">
                @yield('content')
            </main>
        @endif
    </div>
    
    <!-- Footer -->
    @include('components.footer')
    
    @stack('scripts')
</body>
</html>