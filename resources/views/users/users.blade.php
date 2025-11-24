@extends('layouts.dashboardtemp')

@section('content')
<main>
    <div class="container">

     <!-- Welcome row: left = welcome text, right = action buttons -->
      <div class="welcome-row mb-3">
        <div class="welcome-text d-none d-lg-block">
          {{-- <div class="small text-muted">Welcome back,</div> --}}
          <h4>Welcome back, Samuel (Admin)</h4>
          <p>Here overview of your dashboard</p>
        </div>

        <div class="action-buttons ms-auto">
          <button class="btn btn-outline-primary"><i class="fa-solid fa-plus me-2"></i> Add New Course</button>
          <button class="btn btn-success"><i class="fa-solid fa-user-plus me-2"></i> Create New User</button>
        </div>
      </div>
    </div>

    <div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold">All Users List</h5>
      <div class="d-flex gap-2">
        <!-- Search -->
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="fa fa-search"></i></span>
          <input type="text" class="form-control" placeholder="Search by Name or roll">
        </div>
        <!-- Dropdown -->
        <div class = "w-50">
          <select class="form-select">
            <option selected>All Classes</option>
            <option value="1">Class 1</option>
            <option value="2">Class 2</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th scope="col">No</th>
            <th scope="col">Users</th>
            <th scope="col">ID</th>
            <th scope="col">Email Address</th>
            <th scope="col">Gender</th>
            <th scope="col">Contact</th>
            <th scope="col">Role</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Row 1 -->
          <tr>
            <td>01</td>
            <td>
              <div class="d-flex align-items-center">
                <img src="images/jimmy.png" class="rounded-circle me-2" alt="User" width="40" height="40">
                <span>Winner Effiong</span>
              </div>
            </td>
            <td>KOKOKM-0001</td>
            <td>major.signature@gmail.com</td>
            <td>Male</td>
            <td>08112345678</td>
            <td>Instructor</td>
            <td>
              <a href="#" class="text-secondary me-2"><i class="fa fa-edit"></i></a>
              <a href="#" class="text-danger"><i class="fa fa-trash"></i></a>
            </td>
          </tr>

          <!-- Row 2 -->
          <tr>
            <td>02</td>
            <td>
              <div class="d-flex align-items-center">
                <img src="images/jimmy.png" class="rounded-circle me-2" alt="User" width="40" height="40">
                <span>Winner Effiong</span>
              </div>
            </td>
            <td>KOKOKM-0002</td>
            <td>major.signature@gmail.com</td>
            <td>Female</td>
            <td>08112345679</td>
            <td>Student</td>
            <td>
              <a href="#" class="text-secondary me-2"><i class="fa fa-edit"></i></a>
              <a href="#" class="text-danger"><i class="fa fa-trash"></i></a>
            </td>
          </tr>

          <!-- Add more rows as needed -->
        </tbody>
      </table>
    </div>



    <div class = "row">
        <div class = "d-flex justify-content-between align-items-center mb-2">

            <div>
                <nav aria-label="Page navigation example">
                <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                </ul>
                </nav>
            </div>

            <div>
                <nav aria-label="Page navigation example">
                <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">1 of 12</a></li>
                </ul>
                </nav>
            </div>
            <div>
                <nav aria-label="Page navigation example">
                <ul class="pagination">
                 <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
                </nav>
            </div>

    </div>
  </div>
</main>

@endsection
