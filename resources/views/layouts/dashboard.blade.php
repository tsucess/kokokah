<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') - Kokokah</title>

  <link rel="icon" type="image/x-icon" href="{{ asset('images/Kokokah_Logo.png') }}" />

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome - Local build to avoid CORS issues -->
  @vite(['resources/css/app.css'])
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
 <link rel="stylesheet" href="{{ asset('css/style_theme.css') }}">
 <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

  {{-- @vite(['resources/css/dashboard.css']) --}}
  @yield('styles')
</head>
<body>
  <!-- Overlay for mobile sidebar -->
  <div class="overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <img src="{{ asset('images/KOKOKAH Logo.svg') }}" alt="Kokokah Logo" class="img-fluid dashboard-logo">
    </div>

    <nav class="nav-group" id="sidebarNav">
      @yield('sidebar-content')
    </nav>

    <div class="sidebar-footer">
      @yield('sidebar-footer')
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Topbar -->
    <div class="topbar">
      <button class="hamburger" id="hamburger">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="topbar-right">
        @yield('topbar-content')
      </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
      @yield('content')
    </div>
  </main>

  @vite(['resources/js/app.js'])
  @yield('scripts')
</body>
</html>

