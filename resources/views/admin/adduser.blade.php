@extends('admin.dashboardtemp')

@section('content')
<main>
  <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">

           <div>
          <h4 class ="fw-bold">User Activity Logs</h4>
          <p class = "text-muted">Here overview of your </p>
           </div>

           <div>
          <button class="btn btn-nav-primary"><i class="fa-solid fa-plus me-2"></i> Create New User</button>
           </div>


        </div>




  <!-- Table Section -->
  <div class="mt-4 card border-0 shadow-sm rounded-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class = "">All Users List</h6>
        <div class="d-flex gap-2">

          {{-- <div class="search-wrapper">
            <span class="input-group-text" id="basic-addon1">
            <i class="fa fa-search"></i>
            </span>
            <input type="text" class="form-control form-control-sm search-bar" placeholder="Search">
          </div> --}}


  <div class="position-relative">
    {{-- <i class="fas fa-lock-open position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i> --}}
    <input
      type="text"
      class="form-control ps-5"
      placeholder="Search by Name"
      aria-label="Search">
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
            <option>All Classes</option>
            <option>All Course</option>
            <option>All Category</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>No</th>
              <th>Users</th>
              <th>ID</th>
              <th>Email</th>
              <th>Gender</th>
              <th>Contact</th>
              <th>Role</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Repeatable Row -->
            <tr>
              <td>01</td>
              <td>
                <div class="d-flex align-items-center">
                  <img src="images/winner-round.png" class="rounded-circle me-2" width="35" height="35">
                  Winner
                </div>
              </td>
              <td>KOKOKAH-0001</td>
              <td>majorsignature@gmail.com</td>
              <td>Male</td>
              <td>08102929049</td>
              <td>Instructor</td>
              <td claass="d-flex">
                <button class="btn btn-sm btn-light"><i class="fa fa-edit text-primary"></i></button>
                <button class="btn btn-sm btn-light"><i class="fa fa-trash text-danger"></i></button>
              </td>
            </tr>



            <tr>
              <td>02</td>
              <td>
                <div class="d-flex align-items-center">
                  <img src="images/winner-round.png" class="rounded-circle me-2" width="35" height="35">
                  Winner
                </div>
              </td>
              <td>KOKOKAH-0001</td>
              <td>majorsignature@gmail.com</td>
              <td>Male</td>
              <td>08102929049</td>
              <td>Student</td>
              <td claass="d-flex">
                <button class="btn btn-sm btn-light"><i class="fa fa-edit text-primary"></i></button>
                <button class="btn btn-sm btn-light"><i class="fa fa-trash text-danger"></i></button>
              </td>
            </tr>


            <!-- Copy more rows here -->
          </tbody>
        </table>
      </div>
    </div>


     <div class="d-flex justify-content-between align-items-center">
    <!-- Previous Button -->
    <button class="btn btn-outline-secondary rounded-3 ms-2 mb-2 px-4">Previous</button>

    <!-- Center Text -->
    <span class="text-muted fw-semibold small">Page 1 of 12</span>

    <!-- Next Button -->
    <button class="btn btn-outline-secondary rounded-3 me-2 mb-2 px-4">Next</button>
  </div>
  </div>




    </div>
  </main>
  @endsection
