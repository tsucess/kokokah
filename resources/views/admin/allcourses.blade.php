
@extends('admin.dashboardtemp')
@section('content')

<main>
    <div class="container">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold">All Courses</h4>
      <p class="text-muted mb-0">Here overview of your</p>
    </div>
    <button class="btn btn-teal text-white rounded-pill px-4" style="background:#0d9488;">
      <i class="fa fa-plus me-2"></i>Create New Course
    </button>
  </div>

<!-- Stats -->
      <div class="stats-row mb-4">
        <div class="stat-card">
          <div class="stat-orb orb-users"><i class="fa-solid fa-users"></i></div>
          <div class="stat-meta ">
            <div class="label mt-2">Total Users</div>
            <div class = "mt-2">
            <p style = "font-size: 7px;">
             <i class="fa-solid fa-square text-success"></i> science (50%) &nbsp; &nbsp;
             <i class="fa-solid fa-square text-warning"></i> Arts (50%)
            </p>
            </div>
            <div class="value">50</div>
          </div>
        </div>

        <div class="stat-card">
        <div class="stat-orb orb-students"><i class="fa-solid fa-user-graduate"></i></div>
        <div class="stat-meta ps-2">
            <div class="label mt-2">Students</div>
            <div class = "mt-2">
            <p style = "font-size: 7px;">
             <i class="fa-solid fa-square text-success"></i> MALE (61%) &nbsp; &nbsp;
             <i class="fa-solid fa-square text-warning"></i> FEMALE (39%)
            </p>
            </div>
            <div class="value">308</div>
          </div>
        </div>

        <div class="stat-card">
            <div class="stat-orb orb-instructors"><i class="fa-solid fa-chalkboard-user"></i></div>
          <div class="stat-meta">
            <div class="label">Instructors</div>
            <div class = "mt-2">
            <p style = "font-size: 7px;">
             <i class="fa-solid fa-square text-success"></i> MALE (55%) &nbsp; &nbsp;
             <i class="fa-solid fa-square text-warning"></i> FEMALE (45%)
            </p>
            </div>
            <div class="value">100</div>
          </div>
        </div>

        <div class="stat-card">
        <div class="stat-orb orb-courses"><i class="fa-solid fa-book-open"></i></div>
          <div class="stat-meta">
            <div class="label">Active Courses</div>
            <div class = "mt-2">
            <p style = "font-size: 7px;">
             <i class="fa-solid fa-square text-success"></i> science (50%) &nbsp; &nbsp;
             <i class="fa-solid fa-square text-warning"></i> Arts (50%)
            </p>
            </div>
            <div class="value">50</div>
          </div>
        </div>
      </div>

  <!-- Table Section -->
  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold">Courses</h6>
        <div class="d-flex gap-2">
          <div class="search-wrapper">
            <span class="input-group-text" id="basic-addon1">
            <i class="fa fa-search"></i>
            </span>
            <input type="text" class="form-control form-control-sm search-bar" placeholder="Search">
          </div>

            {{-- <div class="container mt-5">
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search..." aria-label="Search" aria-describedby="basic-addon1">
        </div>
    </div> --}}


          <select class="form-select form-select-sm rounded-pill">
            <option>Status</option>
            <option>Published</option>
            <option>Draft</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>No</th>
              <th>Course Name</th>
              <th>Date Created</th>
              <th>Progress</th>
              <th>Ratings</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Repeatable Row -->
            <tr>
              <td>01</td>
              <td>
                <div class="d-flex align-items-center">
                  <img src="https://randomuser.me/api/portraits/men/1.jpg" class="rounded-circle me-2" width="35" height="35">
                  English Language
                </div>
              </td>
              <td>02 August 2025</td>
              <td>
                <span><i class="fa-solid fa-message text-success ms-4"></i></span>
                <div class="progress w-75">
                  <div class="progress-bar bg-success" style="width: 70%;"></div>
                </div>
              </td>
              <td class="rating"><i class="fa fa-star text-warning"></i>4.5</td>
              <td><span class="status-badge status-published">Published</span></td>
              <td>
                <button class="btn btn-sm btn-light"><i class="fa fa-edit text-primary"></i></button>
                <button class="btn btn-sm btn-light"><i class="fa fa-trash text-danger"></i></button>
              </td>
            </tr>



            <tr>
              <td>02</td>
              <td>
                <div class="d-flex align-items-center">
                  <img src="https://randomuser.me/api/portraits/men/1.jpg" class="rounded-circle me-2" width="35" height="35">
                  Mathematics
                </div>
              </td>
              <td>02 August 2025</td>
              <td>
                <span><i class="fa-solid fa-message text-success ms-4"></i></span>
                <div class="progress w-75">
                  <div class="progress-bar bg-success" style="width: 70%;"></div>
                </div>
              </td>
              <td class="rating"><i class="fa fa-star text-warning"></i>4.5</td>
              <td><span class="status-badge status-published">Published</span></td>
              <td>
                <button class="btn btn-sm btn-light"><i class="fa fa-edit text-primary"></i></button>
                <button class="btn btn-sm btn-light"><i class="fa fa-trash text-danger"></i></button>
              </td>
            </tr>
            <!-- Copy more rows here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

    </div>
</main>

@endsection
