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
                    <button class="btn px-4 py-2 fw-semibold" id="exportBtn" style="background-color: #FDAF22; border: none; color: white;">
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
                                <option value="">All Activities</option>
                                <optgroup label="Status">
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                    <option value="active">Active</option>
                                </optgroup>
                                <optgroup label="Learning Activities">
                                    <option value="user_registered">User Registration</option>
                                    <option value="course_created">Course Created</option>
                                    <option value="course_enrolled">Course Enrollment</option>
                                    <option value="lesson_completed">Lesson Completed</option>
                                    <option value="quiz_attempted">Quiz Attempted</option>
                                    <option value="course_reviewed">Course Review</option>
                                    <option value="course_completed">Course Completed</option>
                                    <option value="learning_path_enrolled">Learning Path</option>
                                    <option value="certificate_issued">Certificate Issued</option>
                                </optgroup>
                                <optgroup label="Wallet & Kudikah">
                                    <option value="wallet_deposit">Wallet Deposit</option>
                                    <option value="money_transfer">Money Transfer</option>
                                    <option value="reward_earned">Reward Earned</option>
                                    <option value="badge_earned">Badge Earned</option>
                                    <option value="refund_processed">Refund Processed</option>
                                    <option value="points_earned">Points Earned</option>
                                    <option value="payment_completed">Payment</option>
                                </optgroup>
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
                                    <th class="useractivity-table-title">Date</th>
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

        /* .search-border-custom {
            border: 2px solid #004A53;
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            background-color: white;
            transition: all 0.3s ease;
        }

        .search-border-custom:focus-within {
            box-shadow: 0 0 0 0.2rem rgba(0, 74, 83, 0.15);
        }

        .search-input-custom-input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.95rem;
            color: #333;
            flex: 1;
            padding: 0;
        }

        .search-input-custom-input::placeholder {
            color: #999;
        } */

        /* .custom-select {
            padding: 0.625rem 1rem;
            font-size: 0.95rem;
            border: 2px solid #004A53;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            background-color: white;
            color: #333;
            cursor: pointer;
        } */

        /* .custom-select:focus {
            border-color: #004A53;
            box-shadow: 0 0 0 0.2rem rgba(0, 74, 83, 0.15);
            outline: none;
        } */

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


        <!-- API Clients -->
    <script>
// Get auth token
        const token = localStorage.getItem('auth_token');
        let currentPage = 1;
        let totalPages = 1;
        let paginationData = null;
        let allActivities = []; // Store all activities for client-side filtering
        let filteredActivities = []; // Store filtered activities

        // Fetch dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUsersActivities(1);
            setupPaginationListeners();
            setupSearchAndFilterListeners();
        });

        // Setup pagination button listeners
        function setupPaginationListeners() {
            document.getElementById('prevBtn').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    displayFilteredActivities();
                }
            });

            document.getElementById('nextBtn').addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    displayFilteredActivities();
                }
            });
        }

        // Setup search and filter listeners
        function setupSearchAndFilterListeners() {
            const searchInput = document.getElementById('searchInput');
            const filterSelect = document.getElementById('filterSelect');
            const exportBtn = document.getElementById('exportBtn');

            if (searchInput) {
                searchInput.addEventListener('input', debounce(function() {
                    applyFiltersAndSearch();
                }, 300));
            }

            if (filterSelect) {
                filterSelect.addEventListener('change', function() {
                    applyFiltersAndSearch();
                });
            }

            if (exportBtn) {
                exportBtn.addEventListener('click', exportToCSV);
            }
        }

        // Debounce function for search input
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Apply filters and search
        function applyFiltersAndSearch() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
            const filterValue = document.getElementById('filterSelect').value;

            filteredActivities = allActivities.filter(activity => {
                // Filter by status or activity type
                if (filterValue) {
                    // Check if it's a status filter
                    const statusFilters = ['completed', 'pending', 'failed', 'active', 'inactive'];
                    if (statusFilters.includes(filterValue)) {
                        if (activity.status !== filterValue) {
                            return false;
                        }
                    } else {
                        // It's an activity type filter
                        if (activity.type !== filterValue) {
                            return false;
                        }
                    }
                }

                // Filter by search term (name, email, or description)
                if (searchTerm) {
                    const userName = activity.user ? (activity.user.first_name + ' ' + activity.user.last_name).toLowerCase() : '';
                    const userEmail = activity.user ? (activity.user.email || '').toLowerCase() : '';
                    const activityDate = activity.timestamp ? activity.timestamp.split(' ')[0] : '';
                    const activityDescription = (activity.description || '').toLowerCase();

                    const matchesName = userName.includes(searchTerm) || userEmail.includes(searchTerm);
                    const matchesDate = activityDate.includes(searchTerm);
                    const matchesDescription = activityDescription.includes(searchTerm);

                    return matchesName || matchesDate || matchesDescription;
                }

                return true;
            });

            // Reset to page 1 and display filtered results
            currentPage = 1;
            displayFilteredActivities();
        }

        // Display filtered activities
        function displayFilteredActivities() {
            const tbody = document.getElementById('usersActivitiesTableBody');
            tbody.innerHTML = '';

            if (filteredActivities.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">No activities found</td></tr>';
                return;
            }

            // Paginate filtered results (10 per page)
            const itemsPerPage = 10;
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedActivities = filteredActivities.slice(startIndex, endIndex);

            paginatedActivities.forEach((activity, index) => {
                const rowNumber = startIndex + index + 1;
                const userName = activity.user ? (activity.user.first_name + ' ' + activity.user.last_name) : 'System';
                const userPhoto = activity.user && activity.user.profile_photo ? 'storage/' + activity.user.profile_photo : 'images/jimmy.png';
                const actionDescription = activity.description || 'Activity';
                const statusBadgeColor = getStatusBadgeColor(activity.status);
                const activityIcon = getActivityIcon(activity.type);
                const activityTypeLabel = getActivityTypeLabel(activity.type);

                const row = `
                    <tr style="border-bottom: 1px solid #e8e8e8;">
                        <td style="padding: 1rem; color: #666; font-size:14px;">${rowNumber}</td>
                        <td style="padding: 1rem; font-size:14px;">
                            <div class="d-flex align-items-center">
                                <img src="${userPhoto ? userPhoto : 'images/avatar.png'}" class="rounded-circle me-3" alt="User"
                                    width="40"  style="object-fit: cover; aspect-ratio:1/1; object-position: top;">
                                <span style="color: #333; font-weight: 500; text-transform: capitalize;" >${userName}</span>
                            </div>
                        </td>
                        <td style="padding: 1rem; color: #666; font-size:14px;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid ${activityIcon}" style="color: #004A53; width: 20px;"></i>
                                <div>
                                    <div style="font-weight: 500; color: #333;">${activityTypeLabel}</div>
                                    <div style="font-size: 12px; color: #999;">${actionDescription}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1rem; color: #666; font-size:14px;">${UIHelpers.formatDate(activity.timestamp)}</td>
                        <td style="padding: 1rem;">
                            <span class="badge" style="background-color: ${statusBadgeColor}; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">${activity.status.charAt(0).toUpperCase() + activity.status.slice(1)}</span>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            // Update pagination info
            totalPages = Math.ceil(filteredActivities.length / itemsPerPage);
            updatePaginationUI();
        }

        // Get status badge color
        function getStatusBadgeColor(status) {
            const colors = {
                'completed': '#28a745',
                'pending': '#ffc107',
                'failed': '#dc3545',
                'active': '#17a2b8',
                'inactive': '#6c757d'
            };
            return colors[status] || '#6c757d';
        }

        // Get activity icon based on type
        function getActivityIcon(type) {
            const icons = {
                // Learning Activities
                'user_registered': 'fa-user-plus',
                'course_created': 'fa-book',
                'course_enrolled': 'fa-graduation-cap',
                'lesson_completed': 'fa-check-circle',
                'quiz_attempted': 'fa-clipboard-list',
                'course_reviewed': 'fa-star',
                'course_completed': 'fa-trophy',
                'payment_completed': 'fa-credit-card',
                'learning_path_enrolled': 'fa-road',
                'certificate_issued': 'fa-certificate',
                // Kudikah Wallet Activities
                'wallet_deposit': 'fa-wallet',
                'money_transfer': 'fa-exchange-alt',
                'reward_earned': 'fa-gift',
                'badge_earned': 'fa-medal',
                'refund_processed': 'fa-undo',
                'points_earned': 'fa-star-half-alt'
            };
            return icons[type] || 'fa-circle';
        }

        // Get activity type label
        function getActivityTypeLabel(type) {
            const labels = {
                // Learning Activities
                'user_registered': 'User Registration',
                'course_created': 'Course Created',
                'course_enrolled': 'Course Enrollment',
                'lesson_completed': 'Lesson Completed',
                'quiz_attempted': 'Quiz Attempted',
                'course_reviewed': 'Course Review',
                'course_completed': 'Course Completed',
                'payment_completed': 'Payment',
                'learning_path_enrolled': 'Learning Path',
                'certificate_issued': 'Certificate Issued',
                // Kudikah Wallet Activities
                'wallet_deposit': 'Wallet Deposit',
                'money_transfer': 'Money Transfer',
                'reward_earned': 'Reward Earned',
                'badge_earned': 'Badge Earned',
                'refund_processed': 'Refund Processed',
                'points_earned': 'Points Earned'
            };
            return labels[type] || 'Activity';
        }

        // Export to CSV
        function exportToCSV() {
            if (filteredActivities.length === 0) {
                alert('No activities to export');
                return;
            }

            // Prepare CSV content
            let csvContent = 'No,User Name,Activity Type,Description,Timestamp,Status\n';

            filteredActivities.forEach((activity, index) => {
                const userName = activity.user ? (activity.user.first_name + ' ' + activity.user.last_name) : 'System';
                const activityType = getActivityTypeLabel(activity.type);
                const actionDescription = activity.description || 'Activity';
                const timestamp = UIHelpers.formatDate(activity.timestamp);
                const status = activity.status.charAt(0).toUpperCase() + activity.status.slice(1);

                // Escape quotes and wrap in quotes if contains comma
                const escapedName = `"${userName.replace(/"/g, '""')}"`;
                const escapedType = `"${activityType.replace(/"/g, '""')}"`;
                const escapedAction = `"${actionDescription.replace(/"/g, '""')}"`;

                csvContent += `${index + 1},${escapedName},${escapedType},${escapedAction},${timestamp},${status}\n`;
            });

            // Create blob and download
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            link.setAttribute('href', url);
            link.setAttribute('download', `user_activities_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Load users Activities
        async function loadUsersActivities(page = 1) {
            try {
                const result = await AdminApiClient.getUserActivity({ page: page, per_page: 100 });

                if (result.success && result.data) {
                    // Handle paginated response from admin dashboard
                    const recentActivityData = result.data.recent_activity || {};
                    const activities = recentActivityData.data || [];
                    paginationData = recentActivityData;

                    console.log('Activities:', activities);
                    console.log('Pagination:', paginationData);

                    // Use actual status from database
                    allActivities = activities.map(activity => {
                        // Use the status from the activity data if available
                        let status = activity.status || 'completed'; // default to completed

                        // For payment activities, use the payment status if available
                        if (activity.payment && activity.payment.status) {
                            status = activity.payment.status;
                        }

                        return {
                            ...activity,
                            status: status
                        };
                    });

                    // Initialize filtered activities with all activities
                    filteredActivities = [...allActivities];
                    totalPages = Math.ceil(filteredActivities.length / 10);

                    // Display the activities
                    displayFilteredActivities();
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
                btn.addEventListener('click', () => {
                    currentPage = 1;
                    displayFilteredActivities();
                });
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
                    btn.addEventListener('click', () => {
                        currentPage = i;
                        displayFilteredActivities();
                    });
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
                btn.addEventListener('click', () => {
                    currentPage = totalPages;
                    displayFilteredActivities();
                });
                pageNumbersContainer.appendChild(btn);
            }
        }    </script>
@endsection
