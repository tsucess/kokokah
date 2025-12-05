@extends('layouts.dashboardtemp')

@section('content')
    <main class="activity-logs-main">
        <div class="container-fluid px-5 py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-start mb-5">
                <div>
                    <h1 class="">
                        User Activity Logs</h1>
                    <p class="text-muted" >Here overview of your</p>
                </div>
                <div>
                    <button class="btn px-4 py-2 fw-semibold" style="background-color: #FDAF22; border: none; color: white;">
                        <i class="fa-solid fa-download me-2"></i> Export
                    </button>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                <div class="card-body p-5">
                    <!-- Table Header with Search and Filters -->
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">User Activity Logs</h5>
                        <div class="d-flex gap-3 justify-content-end" style="flex: 1; margin-left: 2rem;">
                            <!-- Search Input -->
                            {{-- <div class="position-relative flex-grow-1" style="max-width: 300px;">
                                <i class="fa-solid fa-search position-absolute top-50 start-0 translate-middle-y ms-3"
                                    style="color: #999;"></i>
                                <input type="text" class="form-control search-input-custom" id="searchInput"
                                    placeholder="Search by Name or Email" aria-label="Search">
                            </div>

                            <!-- Filter Dropdown -->
                            <select class="form-select filter-select-custom" id="filterSelect" style="max-width: 200px;">
                                <option value="">All Classes</option>
                                <option value="enrolled">Enrolled</option>
                                <option value="subscribed">Subscribed</option>
                                <option value="completed">Completed</option>
                                <option value="dropped">Dropped</option>
                            </select> --}}

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
                        <table class="table table-hover align-middle activity-table">
                            <thead>
                                <tr style="background-color: #f0f0f0; border-bottom: 2px solid #e8e8e8;">
                                    <th class="useractivity-table-title">No</th>
                                    <th class="useractivity-table-title">Users</th>
                                    <th class="useractivity-table-title">Action</th>
                                    <th class="useractivity-table-title">Timestamp</th>
                                    <th class="useractivity-table-title">Status</th>
                                </tr>
                            </thead>
                            <tbody id="usersActivitiesTableBody">
                                <!-- Row 1 -->
                                <tr style="border-bottom: 1px solid #e8e8e8;">
                                    <td style="padding: 1rem; color: #666; font-size:14px;">01</td>
                                    <td style="padding: 1rem;">
                                        <div class="d-flex align-items-center">
                                            <img src="images/jimmy.png" class="rounded-circle me-3" alt="User"
                                                width="40" height="40" style="object-fit: cover;">
                                            <span style="color: #333; font-weight: 500; font-size:14px;">Winner Effiong</span>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem; color: #666; font-size:14px;">Enrolled in "React"</td>
                                    <td style="padding: 1rem; color: #666; font-size:14px;">Sept 01, 2025</td>
                                    <td style="padding: 1rem;">
                                        <span class="badge"
                                            style="background-color: #28a745; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-size:14px;">Completed</span>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4"
                        style="border-top: 1px solid #e8e8e8;">
                        <!-- Previous Button -->
                        <button id="prevBtn" class="btn px-4 py-2"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
                            <i class="fa-solid fa-chevron-left me-2"></i> Previous
                        </button>

                        <!-- Pagination Info -->
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted fw-semibold" style="font-size: 0.9rem;">Page <strong
                                    id="currentPageNum" style="color: #004A53;">1</strong> of <strong
                                    id="totalPageNum" style="color: #004A53;">1</strong></span>

                            <!-- Page Numbers -->
                            <div class="d-flex gap-2" id="pageNumbersContainer">
                                <!-- Page numbers will be generated here -->
                            </div>
                        </div>

                        <!-- Next Button -->
                        <button id="nextBtn" class="btn px-4 py-2"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
                            Next <i class="fa-solid fa-chevron-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
    </main>

    <style>
        .activity-logs-main {
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

        .activity-table tbody tr:hover {
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

            .activity-table {
                font-size: 0.85rem;
            }

            .activity-table th,
            .activity-table td {
                padding: 0.75rem !important;
            }
        }
    </style>


    <script type="module">
        import UIHelpers from '{{ asset('js/utils/uiHelpers.js') }}';
        import AdminApiClient from '{{ asset('js/api/adminApiClient.js') }}';

        // Get auth token
        const token = localStorage.getItem('auth_token');
        let currentPage = 1;
        let totalPages = 1;
        let paginationData = null;

        // Fetch dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUsersActivities(1);
            setupPaginationListeners();
        });

        // Setup pagination button listeners
        function setupPaginationListeners() {
            document.getElementById('prevBtn').addEventListener('click', function() {
                if (currentPage > 1) {
                    loadUsersActivities(currentPage - 1);
                }
            });

            document.getElementById('nextBtn').addEventListener('click', function() {
                if (currentPage < totalPages) {
                    loadUsersActivities(currentPage + 1);
                }
            });
        }

        // Load users Activities
        async function loadUsersActivities(page = 1) {
            try {
                const result = await AdminApiClient.getUserActivity({ page: page, per_page: 10 });

                if (result.success && result.data) {
                    currentPage = page;
                    // Handle paginated response from admin dashboard
                    const recentActivityData = result.data.recent_activity || {};
                    const activities = recentActivityData.data || [];
                    paginationData = recentActivityData;
                    totalPages = recentActivityData.last_page || 1;

                    console.log('Activities:', activities);
                    console.log('Pagination:', paginationData);

                    // Update table
                    const tbody = document.getElementById('usersActivitiesTableBody');
                    tbody.innerHTML = '';

                    if (activities.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="5" class="text-center text-muted py-4">No activities found</td></tr>';
                    } else {
                        activities.forEach((activity, index) => {
                            const rowNumber = (currentPage - 1) * 10 + index + 1;
                            const userName = activity.user ? (activity.user.first_name + ' ' + activity.user.last_name) : 'System';
                            const userPhoto = activity.user && activity.user.profile_photo ? 'storage/' + activity.user.profile_photo : 'images/jimmy.png';
                            const actionDescription = activity.description || 'Activity';

                            const row = `
                                <tr style="border-bottom: 1px solid #e8e8e8;">
                                    <td style="padding: 1rem; color: #666; font-size:14px;">${rowNumber}</td>
                                    <td style="padding: 1rem; font-size:14px;">
                                        <div class="d-flex align-items-center">
                                            <img src="${userPhoto}" class="rounded-circle me-3" alt="User"
                                                width="40" height="40" style="object-fit: cover;">
                                            <span style="color: #333; font-weight: 500;">${userName}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem; color: #666; font-size:14px;">${actionDescription}</td>
                                    <td style="padding: 1rem; color: #666; font-size:14px;">${UIHelpers.formatDate(activity.timestamp)}</td>
                                    <td style="padding: 1rem;">
                                        <span class="badge" style="background-color: #28a745; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">Completed</span>
                                    </td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    }

                    // Update pagination info
                    updatePaginationUI();
                } else {
                    console.error('Failed to load activities:', result.message);
                }
            } catch (error) {
                console.error('Error loading activities:', error);
            }
        }

        // Update pagination UI
        function updatePaginationUI() {
            // Update page numbers
            document.getElementById('currentPageNum').textContent = currentPage;
            document.getElementById('totalPageNum').textContent = totalPages;

            // Update Previous/Next buttons
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === totalPages;

            // Generate page number buttons
            const pageNumbersContainer = document.getElementById('pageNumbersContainer');
            pageNumbersContainer.innerHTML = '';

            const maxPagesToShow = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

            if (endPage - startPage < maxPagesToShow - 1) {
                startPage = Math.max(1, endPage - maxPagesToShow + 1);
            }

            // Add first page if not visible
            if (startPage > 1) {
                const btn = document.createElement('button');
                btn.className = 'btn btn-sm';
                btn.textContent = '1';
                btn.style.cssText = 'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
                btn.addEventListener('click', () => loadUsersActivities(1));
                pageNumbersContainer.appendChild(btn);

                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.color = '#999';
                    pageNumbersContainer.appendChild(dots);
                }
            }

            // Add page numbers
            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement('button');
                btn.className = 'btn btn-sm';
                btn.textContent = i;
                if (i === currentPage) {
                    btn.style.cssText = 'background-color: #004A53; color: white; border: none; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-weight: 600;';
                } else {
                    btn.style.cssText = 'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
                    btn.addEventListener('click', () => loadUsersActivities(i));
                }
                pageNumbersContainer.appendChild(btn);
            }

            // Add last page if not visible
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.color = '#999';
                    pageNumbersContainer.appendChild(dots);
                }

                const btn = document.createElement('button');
                btn.className = 'btn btn-sm';
                btn.textContent = totalPages;
                btn.style.cssText = 'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
                btn.addEventListener('click', () => loadUsersActivities(totalPages));
                pageNumbersContainer.appendChild(btn);
            }
        }

    </script>
@endsection
