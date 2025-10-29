@extends('layouts.dashboardtemp')

@section('content')
<main class="users-main">
  <div class="container-fluid px-5 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-start mb-5">
      <div>
        <h1 class="fw-bold mb-2" style="font-size: 2.5rem; color: #004A53; font-family: 'Fredoka One', sans-serif;">Welcome Back Samuel(Admin)</h1>
        <p class="text-muted" style="font-size: 0.95rem;">Here overview of your</p>
      </div>
      <div>
        <a href="/adduser" class="btn px-4 py-2 fw-semibold" style="background-color: #004A53; border: none; color: white;">
          <i class="fa-solid fa-plus me-2"></i> Create New User
        </a>
      </div>
    </div>

    <!-- Table Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
      <div class="card-body p-5">
        <!-- Table Header with Search and Filters -->
        <div class="d-flex justify-content-between align-items-center mb-5">
          <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">All Users List</h5>
          <div class="d-flex gap-3" style="flex: 1; margin-left: 2rem;">
            <!-- Search Input -->
            <div class="position-relative flex-grow-1" style="max-width: 300px;">
              <i class="fa-solid fa-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: #999;"></i>
              <input
                type="text"
                class="form-control search-input-custom"
                id="searchInput"
                placeholder="Search by Name or Email"
                aria-label="Search">
            </div>

            <!-- Filter Dropdown -->
            <select class="form-select filter-select-custom" id="filterSelect" style="max-width: 200px;">
              <option value="">All Classes</option>
              <option value="course">All Courses</option>
              <option value="category">All Categories</option>
              <option value="role-student">Students</option>
              <option value="role-instructor">Instructors</option>
              <option value="role-admin">Admins</option>
            </select>

            <!-- View Options -->
            <button class="btn btn-light" style="border: 1px solid #ddd; padding: 0.625rem 1rem;" title="List View">
              <i class="fa-solid fa-list" style="color: #004A53;"></i>
            </button>
            <button class="btn btn-light" style="border: 1px solid #ddd; padding: 0.625rem 1rem;" title="Grid View">
              <i class="fa-solid fa-grip" style="color: #999;"></i>
            </button>
          </div>
        </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-hover align-middle users-table">
          <thead>
            <tr style="background-color: #f0f0f0; border-bottom: 2px solid #e8e8e8;">
              <th style="color: #333; font-weight: 600; padding: 1rem;">No</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Users</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">ID</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Email Address</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Gender</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Contact</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Role</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Repeatable Row -->
            <tr style="border-bottom: 1px solid #e8e8e8;">
              <td style="padding: 1rem; color: #666;">01</td>
              <td style="padding: 1rem;">
                <div class="d-flex align-items-center">
                  <img src="images/winner-round.png" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                  <span style="color: #333; font-weight: 500;">Winner Ofiong</span>
                </div>
              </td>
              <td style="padding: 1rem; color: #666;">KOKOKAH-0001</td>
              <td style="padding: 1rem; color: #666;">majorsignature@gmail.com</td>
              <td style="padding: 1rem;"><span style="color: #666;">Male</span></td>
              <td style="padding: 1rem; color: #666;">08102929049</td>
              <td style="padding: 1rem;">
                <span class="badge" style="background-color: #004A53; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">Instructor</span>
              </td>
              <td style="padding: 1rem;">
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Edit">
                    <i class="fa fa-edit" style="color: #004A53;"></i>
                  </button>
                  <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Delete">
                    <i class="fa fa-trash" style="color: #dc3545;"></i>
                  </button>
                </div>
              </td>
            </tr>

            <tr style="border-bottom: 1px solid #e8e8e8;">
              <td style="padding: 1rem; color: #666;">02</td>
              <td style="padding: 1rem;">
                <div class="d-flex align-items-center">
                  <img src="images/winner-round.png" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                  <span style="color: #333; font-weight: 500;">Winner Ofiong</span>
                </div>
              </td>
              <td style="padding: 1rem; color: #666;">KOKOKAH-0002</td>
              <td style="padding: 1rem; color: #666;">majorsignature@gmail.com</td>
              <td style="padding: 1rem;"><span style="color: #666;">Female</span></td>
              <td style="padding: 1rem; color: #666;">08102929049</td>
              <td style="padding: 1rem;">
                <span class="badge" style="background-color: #FDAF22; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">Student</span>
              </td>
              <td style="padding: 1rem;">
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Edit">
                    <i class="fa fa-edit" style="color: #004A53;"></i>
                  </button>
                  <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Delete">
                    <i class="fa fa-trash" style="color: #dc3545;"></i>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Additional rows would go here -->
            <tr style="border-bottom: 1px solid #e8e8e8;">
              <td style="padding: 1rem; color: #666;">03</td>
              <td style="padding: 1rem;">
                <div class="d-flex align-items-center">
                  <img src="images/winner-round.png" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                  <span style="color: #333; font-weight: 500;">Winner Ofiong</span>
                </div>
              </td>
              <td style="padding: 1rem; color: #666;">KOKOKAH-0003</td>
              <td style="padding: 1rem; color: #666;">majorsignature@gmail.com</td>
              <td style="padding: 1rem;"><span style="color: #666;">Male</span></td>
              <td style="padding: 1rem; color: #666;">08102929049</td>
              <td style="padding: 1rem;">
                <span class="badge" style="background-color: #6c757d; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">Admin</span>
              </td>
              <td style="padding: 1rem;">
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Edit">
                    <i class="fa fa-edit" style="color: #004A53;"></i>
                  </button>
                  <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Delete">
                    <i class="fa fa-trash" style="color: #dc3545;"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Section -->
      <div class="d-flex justify-content-between align-items-center mt-5 pt-4" style="border-top: 1px solid #e8e8e8;">
        <!-- Previous Button -->
        <button class="btn px-4 py-2" style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
          <i class="fa-solid fa-chevron-left me-2"></i> Previous
        </button>

        <!-- Pagination Info -->
        <div class="d-flex align-items-center gap-3">
          <span class="text-muted fw-semibold" style="font-size: 0.9rem;">Page <strong style="color: #004A53;">1</strong> of <strong style="color: #004A53;">12</strong></span>

          <!-- Page Numbers -->
          <div class="d-flex gap-2">
            <button class="btn btn-sm" style="background-color: #004A53; color: white; border: none; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-weight: 600;">1</button>
            <button class="btn btn-sm" style="border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;">2</button>
            <button class="btn btn-sm" style="border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;">3</button>
            <span style="color: #999;">...</span>
            <button class="btn btn-sm" style="border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;">12</button>
          </div>
        </div>

        <!-- Next Button -->
        <button class="btn px-4 py-2" style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
          Next <i class="fa-solid fa-chevron-right ms-2"></i>
        </button>
      </div>
    </div>
  </div>
</main>

<style>
  .users-main {
    background-color: #ffffff;
  }

  .search-input-custom {
    padding: 0.875rem 1.25rem 0.875rem 2.75rem;
    font-size: 0.95rem;
    border: 2px solid #004A53;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    background-color: white;
    color: #333;
  }

  .search-input-custom::placeholder {
    color: #999;
  }

  .search-input-custom:focus {
    border-color: #004A53;
    box-shadow: 0 0 0 0.2rem rgba(0, 74, 83, 0.15);
    background-color: white;
    color: #333;
    outline: none;
  }

  .search-input-custom:hover {
    border-color: #004A53;
  }

  .filter-select-custom {
    padding: 0.875rem 1.25rem;
    font-size: 0.95rem;
    border: 2px solid #004A53;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    background-color: white;
    color: #333;
  }

  .filter-select-custom:focus {
    border-color: #004A53;
    box-shadow: 0 0 0 0.2rem rgba(0, 74, 83, 0.15);
    outline: none;
  }

  .filter-select-custom:hover {
    border-color: #004A53;
  }

  .users-table tbody tr:hover {
    background-color: #f5f5f5;
    transition: background-color 0.2s ease;
  }

  .users-table tbody tr {
    transition: background-color 0.2s ease;
  }

  .badge {
    font-size: 0.85rem;
    font-weight: 600;
  }

  .btn-light:hover {
    background-color: #f0f0f0;
    border-color: #999 !important;
  }

  .rounded-4 {
    border-radius: 1rem !important;
  }

  .card {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
  }

  /* Pagination Button Styles */
  .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .search-input-custom {
      max-width: 100%;
    }

    .filter-select-custom {
      max-width: 100%;
    }

    .d-flex.gap-3 {
      flex-direction: column;
      gap: 1rem !important;
    }

    .users-table {
      font-size: 0.85rem;
    }

    .users-table th,
    .users-table td {
      padding: 0.75rem !important;
    }
  }
</style>

@endsection
