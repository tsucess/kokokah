@extends('layouts.dashboardtemp')

@section('content')
<main class="students-main">
  <div class="container-fluid px-5 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-start mb-2">
      <div>
        <h1 class="fw-bold mb-2" style="font-size: 1.2rem; color: #004A53; font-family: 'Fredoka One', sans-serif;">Welcome Back Samuel(Admin)</h1>
        <p class="text-muted" style="font-size: 0.95rem;">Here overview of your</p>
      </div>
      <div>
        <button class="btn px-3 py-2 fw-semibold" style="background-color: #004A53; border: none; color: white;" onclick="openCreateStudentModal()">
          <i class="fa-solid fa-plus me-2"></i> Create New Student
        </button>
      </div>
    </div>

    <!-- Table Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
      <div class="card-body p-4">
        <!-- Table Header with Search and Filters -->
        <div class="d-flex justify-content-between align-items-center mb-5">
          <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">All Students List</h5>
          <div class="d-flex gap-3" style="flex: 1; margin-left: 2rem;">
            <!-- Search Input -->
            <div class="position-relative flex-grow-1" style="max-width: 300px;">
              <i class="fa-solid fa-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: #999;"></i>
              <input
                type="text"
                class="form-control search-input-custom"
                id="searchInput"
                placeholder="Search by Name or ID"
                aria-label="Search">
            </div>

            <!-- Filter Dropdown -->
            <select class="form-select filter-select-custom" id="filterSelect" style="max-width: 200px;">
              <option value="">All Classes</option>
              <option value="ss1">SS1</option>
              <option value="ss2">SS2</option>
              <option value="ss3">SS3</option>
              <option value="jss1">JSS1</option>
              <option value="jss2">JSS2</option>
              <option value="jss3">JSS3</option>
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
        <table class="table table-hover align-middle students-table">
          <thead>
            <tr style="background-color: #f0f0f0; border-bottom: 2px solid #e8e8e8;">
              <th style="color: #333; font-weight: 600; padding: 1rem;">No</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Students</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">ID</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Email Address</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Gender</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Contact</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Location</th>
              <th style="color: #333; font-weight: 600; padding: 1rem;">Action</th>
            </tr>
          </thead>
          <tbody id="studentsTableBody">
            <tr style="border-bottom: 1px solid #e8e8e8;">
              <td colspan="8" class="text-center text-muted py-4">
                <i class="fa-solid fa-spinner fa-spin me-2"></i>Loading students...
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Section -->
      <div class="d-flex justify-content-between align-items-center mt-5 pt-4" style="border-top: 1px solid #e8e8e8;">
        <!-- Previous Button -->
        <button class="btn px-4 py-2" id="prevBtn" onclick="loadStudents(currentPage - 1)" style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;" disabled>
          <i class="fa-solid fa-chevron-left me-2"></i> Previous
        </button>

        <!-- Pagination Info -->
        <div class="d-flex align-items-center gap-3">
          <span class="text-muted fw-semibold" style="font-size: 0.9rem;">Page <strong style="color: #004A53;" id="currentPageNum">1</strong> of <strong style="color: #004A53;" id="totalPageNum">1</strong></span>

          <!-- Page Numbers -->
          <div class="d-flex gap-2" id="pageNumbers">
            <!-- Generated dynamically -->
          </div>
        </div>

        <!-- Next Button -->
        <button class="btn px-4 py-2" id="nextBtn" onclick="loadStudents(currentPage + 1)" style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
          Next <i class="fa-solid fa-chevron-right ms-2"></i>
        </button>
      </div>
    </div>
  </div>
</main>

<script>
  const token = localStorage.getItem('auth_token');
  let currentPage = 1;
  let totalPages = 1;
  let currentSearch = '';
  let currentFilter = '';

  document.addEventListener('DOMContentLoaded', function() {
    loadStudents(1);

    document.getElementById('searchInput').addEventListener('input', function(e) {
      currentSearch = e.target.value;
      loadStudents(1);
    });

    document.getElementById('filterSelect').addEventListener('change', function(e) {
      currentFilter = e.target.value;
      loadStudents(1);
    });
  });

  async function loadStudents(page = 1) {
    try {
      let url = `/api/admin/users?page=${page}&per_page=20&role=student`;

      if (currentSearch) {
        url += `&search=${encodeURIComponent(currentSearch)}`;
      }

      if (currentFilter) {
        url += `&level=${encodeURIComponent(currentFilter)}`;
      }

      url += `&t=${Date.now()}`;

      const response = await fetch(url, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        },
        cache: 'no-store'
      });

      if (!response.ok) {
        console.error('Failed to fetch students');
        return;
      }

      const data = await response.json();
      if (data.success && data.data) {
        currentPage = page;
        const students = data.data.data;
        const pagination = data.data;
        totalPages = pagination.last_page;

        const tbody = document.getElementById('studentsTableBody');
        tbody.innerHTML = '';

        if (students.length === 0) {
          tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No students found</td></tr>';
        } else {
          students.forEach((student, index) => {
            const profilePhoto = student.profile_photo
              ? `storage/${student.profile_photo}`
              : 'images/winner-round.png';

            const row = `
              <tr style="border-bottom: 1px solid #e8e8e8;">
                <td style="padding: 1rem; color: #666;">${String((page - 1) * 20 + index + 1).padStart(2, '0')}</td>
                <td style="padding: 1rem;">
                  <div class="d-flex align-items-center">
                    <img src="${profilePhoto}" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover; background: #f0f0f0;">
                    <span style="color: #333; font-weight: 500;">${student.first_name} ${student.last_name}</span>
                  </div>
                </td>
                <td style="padding: 1rem; color: #666;">KOKOKAH-${String(student.id).padStart(4, '0')}</td>
                <td style="padding: 1rem; color: #666;">${student.email}</td>
                <td style="padding: 1rem;"><span style="color: #666;">${student.gender || 'N/A'}</span></td>
                <td style="padding: 1rem; color: #666;">${student.contact || 'N/A'}</td>
                <td style="padding: 1rem; color: #666;">${student.address || 'N/A'}</td>
                <td style="padding: 1rem;">
                  <div class="d-flex gap-2">
                    <a href="/edituser?id=${student.id}" class="btn btn-sm btn-light" style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Edit">
                      <i class="fa fa-edit" style="color: #004A53;"></i>
                    </a>
                    <button class="btn btn-sm btn-light delete-btn" data-user-id="${student.id}" style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Delete">
                      <i class="fa fa-trash" style="color: #dc3545;"></i>
                    </button>
                  </div>
                </td>
              </tr>
            `;
            tbody.innerHTML += row;
          });
        }

        document.getElementById('currentPageNum').textContent = currentPage;
        document.getElementById('totalPageNum').textContent = totalPages;
        document.getElementById('prevBtn').disabled = !pagination.prev_page_url;
        document.getElementById('nextBtn').disabled = !pagination.next_page_url;

        generatePageNumbers(currentPage, totalPages);
        attachDeleteListeners();
      }
    } catch (error) {
      console.error('Error loading students:', error);
    }
  }

  function generatePageNumbers(current, total) {
    const pageNumbersDiv = document.getElementById('pageNumbers');
    pageNumbersDiv.innerHTML = '';

    let startPage = Math.max(1, current - 1);
    let endPage = Math.min(total, current + 1);

    if (startPage > 1) {
      const btn = document.createElement('button');
      btn.className = 'btn btn-sm';
      btn.style.cssText = 'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
      btn.textContent = '1';
      btn.onclick = () => loadStudents(1);
      pageNumbersDiv.appendChild(btn);

      if (startPage > 2) {
        const span = document.createElement('span');
        span.style.color = '#999';
        span.textContent = '...';
        pageNumbersDiv.appendChild(span);
      }
    }

    for (let i = startPage; i <= endPage; i++) {
      const btn = document.createElement('button');
      btn.className = 'btn btn-sm';
      if (i === current) {
        btn.style.cssText = 'background-color: #004A53; color: white; border: none; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-weight: 600;';
      } else {
        btn.style.cssText = 'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
      }
      btn.textContent = i;
      btn.onclick = () => loadStudents(i);
      pageNumbersDiv.appendChild(btn);
    }

    if (endPage < total) {
      if (endPage < total - 1) {
        const span = document.createElement('span');
        span.style.color = '#999';
        span.textContent = '...';
        pageNumbersDiv.appendChild(span);
      }

      const btn = document.createElement('button');
      btn.className = 'btn btn-sm';
      btn.style.cssText = 'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
      btn.textContent = total;
      btn.onclick = () => loadStudents(total);
      pageNumbersDiv.appendChild(btn);
    }
  }

  function attachDeleteListeners() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(btn => {
      btn.removeEventListener('click', handleDelete);
      btn.addEventListener('click', handleDelete);
    });
  }

  async function handleDelete(e) {
    e.preventDefault();
    const btn = e.currentTarget;
    const userId = btn.getAttribute('data-user-id');

    if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
      try {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        const response = await fetch(`/api/admin/users/${userId}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });

        const data = await response.json();

        if (response.ok) {
          showAlert('Student deleted successfully!', 'success');
          setTimeout(() => {
            loadStudents(currentPage);
          }, 1000);
        } else {
          showAlert(data.message || 'Failed to delete student', 'error');
          btn.disabled = false;
          btn.innerHTML = '<i class="fa fa-trash" style="color: #dc3545;"></i>';
        }
      } catch (error) {
        console.error('Error deleting student:', error);
        showAlert('An error occurred while deleting the student', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-trash" style="color: #dc3545;"></i>';
      }
    }
  }

  function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer') || createAlertContainer();
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHTML = `
      <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `;
    alertContainer.innerHTML = alertHTML;

    setTimeout(() => {
      const alert = alertContainer.querySelector('.alert');
      if (alert) {
        alert.remove();
      }
    }, 5000);
  }

  function createAlertContainer() {
    const container = document.createElement('div');
    container.id = 'alertContainer';
    document.body.appendChild(container);
    return container;
  }

  function openCreateStudentModal() {
    alert('Create New Student feature coming soon!');
  }
</script>

<style>
  .students-main {
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

  .students-table tbody tr:hover {
    background-color: #f5f5f5;
    transition: background-color 0.2s ease;
  }

  .rounded-4 {
    border-radius: 1rem !important;
  }

  .card {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
  }

  .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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

    .students-table {
      font-size: 0.85rem;
    }

    .students-table th,
    .students-table td {
      padding: 0.75rem !important;
    }
  }
</style>

@endsection

