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


  <!-- Inter font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  @vite(['resources/css/dashboard.css'])
</head>
<body>

      <!-- Overlay for mobile sidebar -->
  <div class="overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <img src="images/Kokokah_Logo.png" alt="" class = "img-fluid" style = "width:236px; height:61px;">
    </div>

    <nav class="nav-group" id="sidebarNav">
      <a class="nav-item-link" href="/dashboard"><i class="fa-solid fa-gauge pe-2"></i> Dashboard</a>

      <!-- Users Management (collapsible) -->
<a class="nav-item-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"  role="button"
   aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-users pe-3"></i> Users Management</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

  <!-- Dropdown items -->
  <div class="collapse ps-4" id="coursesMenu">
    <a class="nav-item-link d-block" href="#">All Users</a>
    <a class="nav-item-link d-block" href="#">Add Users</a>
    <a class="nav-item-link d-block" href="#">Users Activity Log</a>
  </div>


      <!-- Courses Management (collapsible) -->
  <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" href="#coursesMenu" role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-book-open me-2 pe-2"></i> Courses Management</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

  <!-- Dropdown items -->
  <div class="collapse ps-4" id="coursesMenu">
    <a class="nav-item-link d-block" href="#">All Courses</a>
    <a class="nav-item-link d-block" href="#">Create New Course</a>
    <a class="nav-item-link d-block" href="#">Course Categories</a>
    <a class="nav-item-link d-block" href="#">Course Reviews & Rating</a>
    <a class="nav-item-link d-block" href="#">Course Approval</a>
  </div>

    <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-chalkboard-user pe-3"></i> Instructors</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

  <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse"  role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-user-graduate pe-4"></i> Students</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

    <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-book pe-4"></i> Content Management</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

  <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-credit-card pe-4"></i> Financial / Payments</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

  <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-chart-line pe-4"></i> Reports & Analytics</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>


    <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-comments pe-4"></i> Communication</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

      <a class="nav-item-link" href="#"><i class="fa-solid fa-child-reaching pe-4"></i> Koodies</a>
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
      <div class="d-lg-none small text-muted">Welcome back,</div>
      <div class="d-lg-none fw-bold">Samuel (Admin)</div>
    </div>

    <div class="search-wrap mx-3">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input class="search-input" type="text" placeholder="Search">
    </div>

    <div class="top-icons">
      <button class="icon-btn round-2" title="bell" style = "background: #ECEBF1;"><i class="fa-regular fa-bell"></i></button>
      <button class="icon-btn round-2" title="message" style = "background: #ECEBF1;"><i class="fa-regular fa-envelope"></i></button>
      <button class="icon-btn round-2" title="question" style = "background: #ECEBF1;"><i class="fa-solid fa-question"></i></button>
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
