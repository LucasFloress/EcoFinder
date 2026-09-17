@endpush

@section('body')
<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        @yield('sidebar')
    </aside>
    
    <!-- Content -->
    <main class="content">
        @yield('content')
    </main>
</div>
@endsection