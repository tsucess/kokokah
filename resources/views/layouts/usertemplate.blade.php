<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard</title>

  <link rel="icon" type="image/x-icon" href="images/Kokokah_Logo.png" />

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
 
  <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- chartjs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

 <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

  {{-- @vite(['resources/css/dashboard.css']) --}}
</head>
<body>

      <!-- Overlay for mobile sidebar -->
  <div class="overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <img src="images/Kokokah_Logo.png" alt="Kokokah Logo" class="img-fluid dashboard-logo">
    </div>

    <nav class="nav-group" id="sidebarNav">
      <a class="nav-item-link" href="/dashboard"><i class="fa-solid fa-gauge pe-3"></i> Dashboard</a>

    <a class="nav-item-link" href="/subjects"><i class="fa-solid fa-book-open me-2  pe-1"></i> Subjects</a>

    <a class="nav-item-link" href="/results"><i class="fa-solid fa-user me-2  pe-2"></i> Results & Scoring</a>

    <a class="nav-item-link" href="/results"><i class="fa-solid fa-user me-2  pe-2"></i> Results & Scoring</a>

    <a class="nav-item-link" href="/results"><i class="fa-solid fa-user me-2  pe-2"></i> Wallet</a>

    <a class="nav-item-link" href="/results"><i class="fa-solid fa-user me-2  pe-2"></i> Notification</a>

          <!-- Communication -->
  <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" href="#communication" role="button"
     aria-expanded="false" aria-controls="communication">
    <span><i class="fa-solid fa-comments me-2 pe-2"></i> Communication</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

  <!-- communication dropdowns -->
  <div class="collapse ps-4" id="communication">
    <a class="nav-item-link d-block" href="#">Announcement</a>
    <a class="nav-item-link d-block" href="#">Email / Messaging Center</a>
    <a class="nav-item-link d-block" href="#">Feedback / Surveys</a>
  </div>


    </nav>



    <div class="sidebar-footer">
      <a class="nav-item-link" href="#"><i class="fa-solid fa-gear pe-3"></i> Settings</a>
      <div class="profile mt-3">
        <img class="avatar" src="https://dummyimage.com/72x72/0ea5e9/ffffff.png&text=U" alt="user">
        <div>
          <div class="fw-bold">Culacino_</div>
          <div class="text-muted small">UI Designer</div>
        </div>
      </div>
    </div>
  </aside>

    <!-- Topbar -->
  <header class="topbar">
    <div class="d-flex align-items-center gap-2">
      <button class="btn btn-light d-lg-none" id="hamburger"><i class="fa-solid fa-bars"></i></button>
      {{-- <div class="d-lg-none small text-muted">Welcome back,</div>
      <div class="d-lg-none fw-bold">Samuel (Admin)</div> --}}
    </div>

    <div class="search-wrap mx-3">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input class="search-input" type="text" placeholder="Search">
    </div>

    <div class="top-icons">
      <button class="icon-btn round-2 icon-btn-light" title="bell"><i class="fa-regular fa-bell"></i></button>
      <button class="icon-btn round-2 icon-btn-light" title="message"><i class="fa-regular fa-envelope"></i></button>
      <button class="icon-btn round-2 icon-btn-light" title="question"><i class="fa-solid fa-question"></i></button>
    </div>
  </header>

    @yield('content')

  <!-- Footer -->
      <div class="d-flex page-footer justify-content-between">
        <div class="small text-muted">© Copyright Kokokah 2025. All rights reserved.</div>

        <div class = "small text-muted">
          <a href="#" class = "text-decoration-none text-muted">License</a>&nbsp;
          <a href="#" class = "text-decoration-none text-muted">More Themes</a>&nbsp;
          <a href="#" class = "text-decoration-none text-muted">Documentation</a>&nbsp;
          <a href="#" class = "text-decoration-none text-muted">Support</a>
        </div>


    </div>
    </div>
  </main>

  <!-- Chart.js (keep after body) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

  <script>
    // Mobile sidebar toggle behavior
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('hamburger');

    function openSidebar() {
      sidebar.classList.add('show');
      overlay.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
      sidebar.classList.remove('show');
      overlay.classList.remove('show');
      document.body.style.overflow = '';
    }

    hamburger.addEventListener('click', (e) => {
      openSidebar();
    });
    overlay.addEventListener('click', () => closeSidebar());

    // Close sidebar when clicking any sidebar nav link on mobile
    document.querySelectorAll('.nav-item-link').forEach(link => {
      link.addEventListener('click', () => {
        if (window.innerWidth < 992) closeSidebar();
      });
    });

    // Ensure overlay/ sidebar state resets on window resize
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 992) {
        // desktop: ensure overlay hidden and sidebar visible
        overlay.classList.remove('show');
        sidebar.classList.remove('show');
        document.body.style.overflow = '';
      }
    });

  </script>
</body>
</html>
