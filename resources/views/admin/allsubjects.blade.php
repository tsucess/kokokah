@extends('layouts.dashboardtemp')
@section('content')

<main class="subjects-main">
  <div class="container-fluid px-5 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-start mb-5">
      <div>
        <h1 class="fw-bold mb-2" style="font-size: 2.5rem; color: #004A53; font-family: 'Fredoka One', sans-serif;">All Subjects</h1>
        <p class="text-muted" style="font-size: 0.95rem;">Here overview of your</p>
      </div>
      <div>
        <button class="btn px-4 py-2 fw-semibold" style="background-color: #004A53; border: none; color: white;">
          <i class="fa-solid fa-plus me-2"></i> Create New Course
        </button>
      </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-5">
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #004A53 0%, #006b7d 100%); border: 1px solid #e8e8e8;">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <p class="text-white-50 mb-2" style="font-size: 0.9rem;">Active Subjects</p>
                <h3 class="fw-bold text-white mb-0">50</h3>
              </div>
              <div style="background: rgba(255,255,255,0.2); padding: 0.75rem; border-radius: 0.75rem;">
                <i class="fa-solid fa-book text-white" style="font-size: 1.5rem;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #FDAF22 0%, #ffc857 100%); border: 1px solid #e8e8e8;">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <p class="text-white-50 mb-2" style="font-size: 0.9rem; color: rgba(0,0,0,0.3);">Pending Students</p>
                <h3 class="fw-bold text-white mb-0" style="color: #333;">308</h3>
              </div>
              <div style="background: rgba(255,255,255,0.3); padding: 0.75rem; border-radius: 0.75rem;">
                <i class="fa-solid fa-users text-white" style="font-size: 1.5rem; color: #333;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #6c757d 0%, #8a92a0 100%); border: 1px solid #e8e8e8;">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <p class="text-white-50 mb-2" style="font-size: 0.9rem;">Draft Courses</p>
                <h3 class="fw-bold text-white mb-0">100</h3>
              </div>
              <div style="background: rgba(255,255,255,0.2); padding: 0.75rem; border-radius: 0.75rem;">
                <i class="fa-solid fa-file-pen text-white" style="font-size: 1.5rem;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #28a745 0%, #3dd65f 100%); border: 1px solid #e8e8e8;">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <p class="text-white-50 mb-2" style="font-size: 0.9rem;">Free Subjects</p>
                <h3 class="fw-bold text-white mb-0">50</h3>
              </div>
              <div style="background: rgba(255,255,255,0.2); padding: 0.75rem; border-radius: 0.75rem;">
                <i class="fa-solid fa-gift text-white" style="font-size: 1.5rem;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
      <div class="card-body p-5">
        <!-- Table Header with Search and Filters -->
        <div class="d-flex justify-content-between align-items-center mb-5">
          <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">Courses</h5>
          <div class="d-flex gap-3" style="flex: 1; margin-left: 2rem;">
            <!-- Search Input -->
            <div class="position-relative flex-grow-1" style="max-width: 300px;">
              <i class="fa-solid fa-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: #999;"></i>
              <input
                type="text"
                class="form-control search-input-custom"
                id="searchInput"
                placeholder="Search by Course Name"
                aria-label="Search">
            </div>

            <!-- Filter Dropdown -->
            <select class="form-select filter-select-custom" id="filterSelect" style="max-width: 200px;">
              <option value="">All Status</option>
              <option value="published">Published</option>
              <option value="draft">Draft</option>
              <option value="archived">Archived</option>
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
          <table class="table table-hover align-middle subjects-table">
            <thead>
              <tr style="background-color: #f0f0f0; border-bottom: 2px solid #e8e8e8;">
                <th style="color: #333; font-weight: 600; padding: 1rem;">No</th>
                <th style="color: #333; font-weight: 600; padding: 1rem;">Course Name</th>
                <th style="color: #333; font-weight: 600; padding: 1rem;">Date Created</th>
                <th style="color: #333; font-weight: 600; padding: 1rem;">Progress</th>
                <th style="color: #333; font-weight: 600; padding: 1rem;">Ratings</th>
                <th style="color: #333; font-weight: 600; padding: 1rem;">Status</th>
                <th style="color: #333; font-weight: 600; padding: 1rem;">Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Row 1 -->
              <tr style="border-bottom: 1px solid #e8e8e8;">
                <td style="padding: 1rem; color: #666;">01</td>
                <td style="padding: 1rem;">
                  <div class="d-flex align-items-center">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                    <span style="color: #333; font-weight: 500;">English Language</span>
                  </div>
                </td>
                <td style="padding: 1rem; color: #666;">02 August 2025</td>
                <td style="padding: 1rem;">
                  <div class="d-flex align-items-center gap-2">
                    <div class="progress" style="width: 100px; height: 6px;">
                      <div class="progress-bar" style="width: 70%; background-color: #28a745;"></div>
                    </div>
                    <span style="color: #666; font-size: 0.85rem;">70%</span>
                  </div>
                </td>
                <td style="padding: 1rem; color: #666;"><i class="fa fa-star" style="color: #FDAF22;"></i> 4.5</td>
                <td style="padding: 1rem;">
                  <span class="badge" style="background-color: #28a745; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">Published</span>
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

              <!-- Row 2 -->
              <tr style="border-bottom: 1px solid #e8e8e8;">
                <td style="padding: 1rem; color: #666;">02</td>
                <td style="padding: 1rem;">
                  <div class="d-flex align-items-center">
                    <img src="https://randomuser.me/api/portraits/men/2.jpg" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                    <span style="color: #333; font-weight: 500;">Mathematics</span>
                  </div>
                </td>
                <td style="padding: 1rem; color: #666;">02 August 2025</td>
                <td style="padding: 1rem;">
                  <div class="d-flex align-items-center gap-2">
                    <div class="progress" style="width: 100px; height: 6px;">
                      <div class="progress-bar" style="width: 85%; background-color: #28a745;"></div>
                    </div>
                    <span style="color: #666; font-size: 0.85rem;">85%</span>
                  </div>
                </td>
                <td style="padding: 1rem; color: #666;"><i class="fa fa-star" style="color: #FDAF22;"></i> 4.8</td>
                <td style="padding: 1rem;">
                  <span class="badge" style="background-color: #28a745; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">Published</span>
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

              <!-- Row 3 -->
              <tr style="border-bottom: 1px solid #e8e8e8;">
                <td style="padding: 1rem; color: #666;">03</td>
                <td style="padding: 1rem;">
                  <div class="d-flex align-items-center">
                    <img src="https://randomuser.me/api/portraits/men/3.jpg" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                    <span style="color: #333; font-weight: 500;">Science</span>
                  </div>
                </td>
                <td style="padding: 1rem; color: #666;">15 July 2025</td>
                <td style="padding: 1rem;">
                  <div class="d-flex align-items-center gap-2">
                    <div class="progress" style="width: 100px; height: 6px;">
                      <div class="progress-bar" style="width: 45%; background-color: #ffc107;"></div>
                    </div>
                    <span style="color: #666; font-size: 0.85rem;">45%</span>
                  </div>
                </td>
                <td style="padding: 1rem; color: #666;"><i class="fa fa-star" style="color: #FDAF22;"></i> 4.2</td>
                <td style="padding: 1rem;">
                  <span class="badge" style="background-color: #ffc107; color: #333; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">Draft</span>
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
  </div>
</main>

<style>
  .subjects-main {
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

  .subjects-table tbody tr:hover {
    background-color: #f5f5f5;
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

    .subjects-table {
      font-size: 0.85rem;
    }

    .subjects-table th,
    .subjects-table td {
      padding: 0.75rem !important;
    }
  }
</style>

@endsection
