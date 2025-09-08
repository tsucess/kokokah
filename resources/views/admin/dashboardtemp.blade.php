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
  <style>
    :root{
      --sidebar-w: 280px;
      --brand-green: #16B265;
      --brand-yellow: #FCD321;
      --muted: #6b737a;
      --card-bg: #ffffff;
      --page-bg: #F6F8FA;
    }

    html,body{height:100%;}
    body{
      font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: var(--page-bg);
      margin:0;
      color:#0f1c24;
      overflow-x:hidden;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-w);
      height: 100vh;
      background: var(--card-bg);
      border-right: 1px solid #e6edf3;
      display:flex;
      flex-direction: column;
      padding: 20px;
      gap: 10px;
      z-index:1050;
      transition: transform 0.28s ease-in-out;
    }
    @media(max-width:991px){
      .sidebar{ transform:translateX(-100%); }
      .sidebar.show{ transform:translateX(0); }
      main,.topbar{ margin-left:0 !important; }
    }
    @media(min-width:992px){
      main{ margin-left: var(--sidebar-w); }
      .topbar{ margin-left: var(--sidebar-w); }
    }

    .overlay {
      display:none;
      position:fixed;
      top:0; left:0; right:0; bottom:0;
      background:rgba(0,0,0,.28);
      z-index:1040;
    }
    .overlay.show{display:block;}

    .brand {
      display:flex; align-items:center; gap:10px;
      margin-bottom:6px;
    }
    .brand img{ width:38px; height:38px; border-radius:8px; object-fit:cover; }
    .brand .title{ font-weight:800; font-size:18px; color:#0b2540; }

    .nav-item-link{
      display:flex; align-items:center; gap:12px; padding:10px 12px;
      color:#263238; text-decoration:none; border-radius:10px; font-weight:600;
    }
    .nav-item-link:hover{ background:#f1f6fa; color:#0b2540; }

    .nav-group{ display:flex; flex-direction:column; gap:4px; margin-top:8px; }

    .sidebar-footer{
      margin-top:auto;
      border-top:1px solid #e9f0f4;
      padding-top:12px;
    }
    .sidebar-footer .profile{ display:flex; gap:10px; align-items:center; }
    .avatar{ width:40px; height:40px; border-radius:8px; object-fit:cover; }

    /* Topbar */
    .topbar{
      position: sticky;
      top:0;
      z-index:1020;
      background: #fff;
      border-bottom:1px solid #e9f0f4;
      padding:12px 18px;
      display:flex;
      gap:12px;
      align-items:center;
      justify-content:space-between;
    }

    .search-wrap{ position:relative; max-width:720px; flex:1; margin:0 12px; }
    .search-wrap .fa-magnifying-glass{ position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--muted); }
    .search-input{
      width:100%;
      padding:10px 14px 10px 40px;
      border-radius:999px;
      border:1px solid #e6edf3;
      background:#fbfdfe;
      height:44px;
    }

    .top-icons{ display:flex; gap:10px; align-items:center; }
    .icon-btn{
      width:42px; height:42px; border-radius:10px;
      background:#fff; border:1px solid #e6edf3;
      display:inline-flex; align-items:center; justify-content:center;
    }

    /* Main content */
    main .container{ padding-top:18px; padding-bottom:36px; }

    .welcome-row{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .welcome-text h4{ margin:0; font-weight:800; font-size:18px; }
    .action-buttons{ display:flex; gap:10px; align-items:center; }

    /* Stat cards */
    .stats-row{ margin-top:10px; display:grid; grid-template-columns: repeat(4,1fr); gap:18px; }
    @media(max-width:991px){ .stats-row{ grid-template-columns: repeat(2,1fr); } }

    .stat-card{
      background:var(--card-bg);
      border-radius:18px;
      padding:14px 16px;
      display:flex; justify-content:space-between; align-items:center;
      box-shadow: 0 6px 18px rgba(15,28,36,.04);
      border:1px solid #eef6fa;
    }

@media(max-width:320px){
    .stat-card
    {
        width:100%;
    }
}

    .stat-orb{
      width:60px; height:60px; border-radius:50%;
      display:grid; place-items:center;
      background:linear-gradient(180deg,#fff,#f4f8fb);
      border:6px solid #eef6f9;
    }
    .orb-users i{ color:#3B82F6; font-size:20px; }
    .orb-students i{ color:var(--brand-green); font-size:20px; }
    .orb-instructors i{ color:#F59E0B; font-size:20px; }
    .orb-courses i{ color:#EF4444; font-size:20px; }

    .stat-meta .label{ font-size:13px; color:var(--muted); font-weight:700; text-transform:uppercase; letter-spacing:.3px; }
    .stat-meta .value{ font-size:20px; font-weight:800; margin-top:6px; }

    /* Chart card */
    .chart-card{ margin-top:18px; background:var(--card-bg); border-radius:18px; padding:18px; border:1px solid #eef6fa; box-shadow: 0 6px 18px rgba(15,28,36,.04); }
    .chart-header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; }
    .legend-dot{ display:inline-flex; align-items:center; gap:8px; font-weight:600; color:#465055; }
    .legend-dot .dot{ width:10px; height:10px; border-radius:50%; display:inline-block; }

    /* Table */
    .table-card{
      margin-top:18px;
      background:var(--card-bg);
      border-radius:18px;
      padding:16px;
      border:1px solid #eef6fa;
      box-shadow: 0 6px 18px rgba(15,28,36,.04);
    }

    /* Footer */
    @media screen and (min-width:1024px) {
    .page-footer{ margin-top:22px; text-align:left; margin-left: 290px; color:var(--muted); padding:20px 0; }
    }

    @media screen and (max-width:320px){
    .page-footer{ margin-top:22px; text-align:center; color:var(--muted); padding:20px 0; }
    }


    .card {
      border-radius: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .upload-box {
      border: 2px dashed #ccc;
      border-radius: 10px;
      padding: 30px;
      text-align: center;
      color: #888;
      cursor: pointer;
    }
    .upload-box:hover {
      background: #f8f9fa;
    }

    .coursedraft
{
background-color:#A3D8DF;
color:#fff;
}

    .coursedraft:hover
{
background-color:#68dcec;
color:#fff;
}

.publishcourse
{
background-color: #3BA0AC;
color:#fff;
}

.publishcourse:hover
{
background-color: #2db5c4;
color:#fff;
}
  </style>
</head>
<body>

      <!-- Overlay for mobile sidebar -->
  <div class="overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <img src="images/Kokokah_Logo.png" alt="" class = "img-fluid w-75">
    </div>

    <nav class="nav-group" id="sidebarNav">
      <a class="nav-item-link" href="/dashboard"><i class="fa-solid fa-gauge"></i> Dashboard</a>
      {{-- <a class="nav-item-link" href="#"><i class="fa-solid fa-users"></i> Users Management</a> --}}

<a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse"  role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-users"></i> Users Management</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

      <!-- Courses Management (collapsible) -->
  <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" href="#coursesMenu" role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-book-open me-2"></i> Courses Management</span>
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
    <span><i class="fa-solid fa-chalkboard-user"></i> Instructors</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

  <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse"  role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-user-graduate"></i> Students</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

    <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-book"></i> Content Management</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

  <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-credit-card"></i> Financial / Payments</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

  <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" role="button"
     aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-chart-line"></i> Reports & Analytics</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>


    <a class="nav-item-link d-flex justify-content-between align-items-center"
     data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="coursesMenu">
    <span><i class="fa-solid fa-comments"></i> Communication</span>
    <i class="fa-solid fa-chevron-down small"></i>
  </a>

      <a class="nav-item-link" href="#"><i class="fa-solid fa-child-reaching"></i> Koodies</a>
    </nav>

    <div class="sidebar-footer">
      <a class="nav-item-link" href="#"><i class="fa-solid fa-gear"></i> Settings</a>
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
