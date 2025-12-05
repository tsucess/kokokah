@extends('layouts.dashboardtemp')

@section('content')
    <main class="users-main">
        <div class="container-fluid px-5 py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h1 class="fw-bold mb-2">Welcome Back
                        Samuel(Admin)</h1>
                    <p class="text-muted" style="font-size: 0.95rem;">Here overview of your</p>
                </div>
                <div>
                    <a href="/adduser" class="btn-accent-yellow" style="text-decoration:none;">
                        <i class="fa-solid fa-plus me-2"></i> Create New User
                    </a>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                <div class="card-body p-4">
                    <!-- Table Header with Search and Filters -->
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">All Users List</h5>
                        <div class="d-flex gap-3 justify-content-end" >
                            <!-- Search Input -->
                            <div class="d-flex gap-2 align-items-center search-border-custom"
                                >
                                <i class="fa-solid fa-search fa-xs " style="color: #999;"></i>
                                <input type="search" class="search-input-custom-input"
                                    id="searchInput" placeholder="Search by Name or Email" aria-label="Search">
                            </div>

                            <!-- Filter Dropdown -->
                            <select class="custom-select" id="filterSelect"
                                >
                                <option value="" style="">All Classes</option>
                                <option value="course">All Courses</option>
                                <option value="category">All Categories</option>
                                <option value="role-student">Students</option>
                                <option value="role-instructor">Instructors</option>
                                <option value="role-admin">Admins</option>
                            </select>

                            <!-- View Options -->
                            {{-- <button class="btn btn-light" style="border: 1px solid #ddd; padding: 0.625rem 1rem;" title="List View">
              <i class="fa-solid fa-list" style="color: #004A53;"></i>
            </button>
            <button class="btn btn-light" style="border: 1px solid #ddd; padding: 0.625rem 1rem;" title="Grid View">
              <i class="fa-solid fa-grip" style="color: #999;"></i>
            </button> --}}
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle users-table">
                            <thead>
                                <tr style="background-color: #f0f0f0; border-bottom: 2px solid #e8e8e8;">
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">No</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Users</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">ID</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Email Address
                                    </th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Gender</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Contact</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Role</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <tr style="border-bottom: 1px solid #e8e8e8;">
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-spinner fa-spin me-2"></i>Loading users...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4"
                        style="border-top: 1px solid #e8e8e8;">
                        <!-- Previous Button -->
                        <button class="btn px-4 py-2" id="prevBtn" onclick="loadUsers(currentPage - 1)"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;"
                            disabled>
                            <i class="fa-solid fa-chevron-left me-2"></i> Previous
                        </button>

                        <!-- Pagination Info -->
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted fw-semibold" style="font-size: 0.9rem;">Page <strong
                                    style="color: #004A53;" id="currentPageNum">1</strong> of <strong
                                    style="color: #004A53;" id="totalPageNum">1</strong></span>

                            <!-- Page Numbers -->
                            <div class="d-flex gap-2" id="pageNumbers">
                                <!-- Generated dynamically -->
                            </div>
                        </div>

                        <!-- Next Button -->
                        <button class="btn px-4 py-2" id="nextBtn" onclick="loadUsers(currentPage + 1)"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
                            Next <i class="fa-solid fa-chevron-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
    </main>

    <script type="module">
        import AdminApiClient from '{{ asset('js/api/adminApiClient.js') }}';

        // Get auth token
        const token = localStorage.getItem('auth_token');
        let currentPage = 1;
        let totalPages = 1;
        let currentSearch = '';
        let currentFilter = '';

        // Load users on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers(1);

            // Add event listeners for search and filter
            document.getElementById('searchInput').addEventListener('input', function(e) {
                currentSearch = e.target.value;
                loadUsers(1);
            });

            document.getElementById('filterSelect').addEventListener('change', function(e) {
                currentFilter = e.target.value;
                loadUsers(1);
            });
        });

        // Load users from API
        async function loadUsers(page = 1) {
            try {
                const filters = {
                    page: page,
                    per_page: 20
                };

                // Add search parameter
                if (currentSearch) {
                    filters.search = currentSearch;
                }

                // Add filter parameter
                if (currentFilter && currentFilter.startsWith('role-')) {
                    filters.role = currentFilter.replace('role-', '');
                }

                const result = await AdminApiClient.getUsers(filters);

                if (!result.success) {
                    console.error('Failed to fetch users:', result.message);
                    return;
                }

                currentPage = page;
                const users = result.data.data || result.data;
                const pagination = result.data;
                totalPages = pagination.last_page;

                // Update table
                const tbody = document.getElementById('usersTableBody');
                tbody.innerHTML = '';

                if (users.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="8" class="text-center text-muted py-4">No users found</td></tr>';
                } else {
                    users.forEach((user, index) => {
                        const profilePhoto = user.profile_photo ?
                            `storage/${user.profile_photo}` :
                            'images/winner-round.png';

                        const roleColor = user.role === 'admin' ? '#6c757d' :
                            user.role === 'instructor' ? '#004A53' : '#FDAF22';

                        const row = `
              <tr style="border-bottom: 1px solid #e8e8e8;">
                <td style="padding: 1rem; color: #666; font-size:14px;">${String((page - 1) * 20 + index + 1).padStart(2, '0')}</td>
                <td style="padding: 1rem;">
                  <div class="d-flex align-items-center">
                    <img src="${profilePhoto}" class="rounded-circle me-3" width="33" height="33" style="object-fit: cover; background: #f0f0f0;">
                    <span style="color: #333; font-weight: 500; font-size:14px;">${user.first_name} ${user.last_name}</span>
                  </div>
                </td>
                <td style="padding: 1rem; color: #666; font-size:14px;">KOKOKAH-${String(user.id).padStart(4, '0')}</td>
                <td style="padding: 1rem; color: #666; font-size:14px;">${user.email}</td>
                <td style="padding: 1rem; font-size:14px;"><span style="color: #666;">${user.gender || 'N/A'}</span></td>
                <td style="padding: 1rem; color: #666; font-size:14px;">${user.contact || 'N/A'}</td>
                <td style="padding: 1rem; font-size:14px;">
                  <span class="badge" style="background-color: ${roleColor}; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">${user.role}</span>
                </td>
                <td style="padding: 1rem; font-size:14px;">
                  <div class="d-flex gap-2">
                    <a href="/edituser?id=${user.id}" class="btn btn-sm btn-light" style="border: 1px solid #ddd; padding: 0.2rem 0.5rem;" title="Edit">
                      <i class="fa fa-edit" style="color: #004A53;"></i>
                    </a>
                    <button class="btn btn-sm btn-light delete-btn" data-user-id="${user.id}" style="border: 1px solid #ddd; padding: 0.2rem 0.5rem;" title="Delete">
                      <i class="fa fa-trash" style="color: #dc3545;"></i>
                    </button>
                  </div>
                </td>
              </tr>
            `;
                        tbody.innerHTML += row;
                    });
                }

                // Update pagination info
                document.getElementById('currentPageNum').textContent = currentPage;
                document.getElementById('totalPageNum').textContent = totalPages;

                // Update pagination buttons
                document.getElementById('prevBtn').disabled = !pagination.prev_page_url;
                document.getElementById('nextBtn').disabled = !pagination.next_page_url;

                // Generate page numbers
                generatePageNumbers(currentPage, totalPages);
            } catch (error) {
                console.error('Error loading users:', error);
            }
        }

        // Generate page number buttons
        function generatePageNumbers(current, total) {
            const pageNumbersDiv = document.getElementById('pageNumbers');
            pageNumbersDiv.innerHTML = '';

            let startPage = Math.max(1, current - 1);
            let endPage = Math.min(total, current + 1);

            if (startPage > 1) {
                const btn = document.createElement('button');
                btn.className = 'btn btn-sm';
                btn.style.cssText =
                    'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
                btn.textContent = '1';
                btn.onclick = () => loadUsers(1);
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
                    btn.style.cssText =
                        'background-color: #004A53; color: white; border: none; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-weight: 600;';
                } else {
                    btn.style.cssText =
                        'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
                }
                btn.textContent = i;
                btn.onclick = () => loadUsers(i);
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
                btn.style.cssText =
                    'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
                btn.textContent = total;
                btn.onclick = () => loadUsers(total);
                pageNumbersDiv.appendChild(btn);
            }
        }

        // Delete user functionality
        function attachDeleteListeners() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const userId = btn.getAttribute('data-user-id');

                    // Show confirmation modal
                    if (confirm(
                            'Are you sure you want to delete this user? This action cannot be undone.'
                        )) {
                        try {
                            btn.disabled = true;
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                            const token = localStorage.getItem('auth_token');
                            const result = await AdminApiClient.deleteUser(userId);

                            if (result.success) {
                                // Show success message
                                showDeleteAlert('User deleted successfully!', 'success');
                                // Reload users after 1 second
                                setTimeout(() => {
                                    loadUsers(currentPage);
                                }, 1000);
                            } else {
                                showDeleteAlert(result.message || 'Failed to delete user', 'error');
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fa fa-trash" style="color: #dc3545;"></i>';
                            }
                        } catch (error) {
                            console.error('Error deleting user:', error);
                            showDeleteAlert('An error occurred while deleting the user', 'error');
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fa fa-trash" style="color: #dc3545;"></i>';
                        }
                    }
                });
            });
        }

        // Alert helper function for delete operations
        function showDeleteAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer') || createAlertContainer();
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            `;
            alertContainer.innerHTML = alertHTML;

            // Auto-dismiss after 5 seconds
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

        // Wrap loadUsers to attach delete listeners after loading
        const originalLoadUsers = loadUsers;
            loadUsers = async function(page = 1) {
                await originalLoadUsers(page);
                attachDeleteListeners();
            };
    </script>

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
