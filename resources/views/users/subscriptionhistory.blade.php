@extends('layouts.usertemplate')
@section('content')

<style>
    .transactions-main {
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .search-border-custom {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 8px 12px;
        background-color: #fff;
    }

    .search-input-custom-input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 0.95rem;
    }

    .custom-select {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 8px 12px;
        background-color: #fff;
        font-size: 0.95rem;
        cursor: pointer;
    }

    .transactions-table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-failed {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

 <main class="transactions-main">
        <div class="container-fluid px-5 py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h1 class="fw-bold mb-2"
                        style="font-size: 1.2rem; color: #004A53; font-family: 'Fredoka One', sans-serif;" id="welcomeMessage">Welcome Back</h1>
                    <p class="text-muted" style="font-size: 0.95rem;">Here's an overview of your subscription and payment history</p>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                <div class="card-body p-4">
                    <!-- Table Header with Search and Filters -->
                    <div class="d-flex flex-column gap-3 align-items-md-center flex-md-row justify-content-between align-items-start mb-5">
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">Subscription History</h5>
                        <div class="d-flex gap-3 flex-column flex-md-row">

                            <div class="d-flex gap-2 align-items-center search-border-custom">
                                <i class="fa-solid fa-search fa-xs " style="color: #999;"></i>
                                <input type="search" class="search-input-custom-input" id="searchInput"
                                    placeholder="Search by Course Name" aria-label="Search">
                            </div>

                            <!-- Filter Dropdown -->
                            <select class="custom-select" id="filterSelect">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="dropped">Dropped</option>
                                <option value="paused">Paused</option>
                                <option value="cancelled">Cancelled</option>
                            </select>


                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle transactions-table">
                            <thead>
                                <tr style="background-color: #f0f0f0; border-bottom: 2px solid #e8e8e8;">
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">ID</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Course</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Amount</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Enrollment Date</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Status</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Progress</th>
                                </tr>
                            </thead>
                            <tbody id="transactionsTableBody">
                                <tr style="border-bottom: 1px solid #e8e8e8;">
                                    <td colspan="6" class="text-center text-muted py-4" style="font-size:16px;">
                                        <i class="fa-solid fa-spinner fa-spin me-2"></i>Loading subscription history...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-between align-items-center gap-2 mt-5 pt-4"
                        style="border-top: 1px solid #e8e8e8;">
                        <!-- Previous Button -->
                        <button class="btn px-1 py-2 d-flex align-items-center gap-1 px-md-3" id="prevBtn" onclick="loadTransactions(currentPage - 1)"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;"
                            disabled>
                            <i class="fa-solid fa-chevron-left"></i> Previous
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
                        <button class="btn px-1 py-2 d-flex align-items-center gap-1 px-md-3" id="nextBtn" onclick="loadTransactions(currentPage + 1)"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
                            Next <i class="fa-solid fa-chevron-right "></i>
                        </button>
                    </div>
                </div>
            </div>
    </main>

    <script type="module">
        import UserApiClient from '/js/api/userApiClient.js';
        import ToastNotification from '/js/utils/toastNotification.js';

        let currentPage = 1;
        let totalPages = 1;
        let allSubscriptions = [];
        let filteredSubscriptions = [];

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', async () => {
            await loadUserProfile();
            await loadSubscriptionHistory();
            setupEventListeners();
        });

        /**
         * Load user profile to display welcome message
         */
        async function loadUserProfile() {
            try {
                const response = await UserApiClient.getProfile();
                if (response.success && response.data) {
                    const user = response.data;
                    const welcomeMessage = document.getElementById('welcomeMessage');
                    welcomeMessage.textContent = `Welcome Back ${user.first_name} ${user.last_name}`;
                }
            } catch (error) {
                console.error('Error loading user profile:', error);
            }
        }

        /**
         * Load subscription history from API (enrollments + transactions)
         */
        async function loadSubscriptionHistory(page = 1) {
            try {
                console.log('Loading subscription history for page:', page);

                const token = localStorage.getItem('auth_token');
                const response = await fetch(`/api/users/subscription-history?page=${page}&per_page=10`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                console.log('Subscription History Response:', data);

                if (data.success && data.data) {
                    allSubscriptions = data.data || [];
                    totalPages = data.pagination?.last_page || 1;
                    currentPage = page;

                    console.log('Subscriptions loaded:', allSubscriptions);
                    console.log('Total pages:', totalPages);

                    displaySubscriptions(allSubscriptions);
                    updatePagination();
                } else {
                    showError('Failed to load subscription history');
                }
            } catch (error) {
                console.error('Error loading subscription history:', error);
                showError('Error loading subscription history. Please try again.');
            }
        }

        /**
         * Display subscriptions in table
         */
        function displaySubscriptions(subscriptions) {
            const tableBody = document.getElementById('transactionsTableBody');
            tableBody.innerHTML = '';

            if (!subscriptions || subscriptions.length === 0) {
                tableBody.innerHTML = `
                    <tr style="border-bottom: 1px solid #e8e8e8;">
                        <td colspan="6" class="text-center text-muted py-4" style="font-size:16px;">
                            No subscription history found
                        </td>
                    </tr>
                `;
                return;
            }

            subscriptions.forEach((subscription) => {
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #e8e8e8';

                const enrollmentId = subscription.id || 'N/A';
                const courseName = subscription.course?.title || 'N/A';
                const amount = subscription.amount ? `₦${parseFloat(subscription.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}` : 'N/A';
                const enrollmentDate = subscription.date ? new Date(subscription.date).toLocaleDateString('en-NG') : 'N/A';
                const status = subscription.status || 'active';
                const progress = subscription.progress || 0;

                const statusBadgeClass = getStatusBadgeClass(status);

                row.innerHTML = `
                    <td style="padding: 1rem; font-size:14px;">${enrollmentId}</td>
                    <td style="padding: 1rem; font-size:14px;">${courseName}</td>
                    <td style="padding: 1rem; font-size:14px; font-weight: 500;">${amount}</td>
                    <td style="padding: 1rem; font-size:14px;">${enrollmentDate}</td>
                    <td style="padding: 1rem; font-size:14px;">
                        <span class="status-badge ${statusBadgeClass}">
                            ${capitalizeFirst(status)}
                        </span>
                    </td>
                    <td style="padding: 1rem; font-size:14px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 100px; height: 6px; background-color: #e0e0e0; border-radius: 3px; overflow: hidden;">
                                <div style="width: ${progress}%; height: 100%; background-color: #004A53;"></div>
                            </div>
                            <span style="font-weight: 500;">${progress}%</span>
                        </div>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        }

        /**
         * Get status badge CSS class
         */
        function getStatusBadgeClass(status) {
            switch (status.toLowerCase()) {
                case 'active':
                    return 'status-completed';
                case 'completed':
                    return 'status-completed';
                case 'paused':
                    return 'status-pending';
                case 'dropped':
                case 'cancelled':
                    return 'status-failed';
                default:
                    return 'status-pending';
            }
        }

        /**
         * Capitalize first letter
         */
        function capitalizeFirst(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
        }

        /**
         * Update pagination controls
         */
        function updatePagination() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const currentPageNum = document.getElementById('currentPageNum');
            const totalPageNum = document.getElementById('totalPageNum');
            const pageNumbers = document.getElementById('pageNumbers');

            currentPageNum.textContent = currentPage;
            totalPageNum.textContent = totalPages;

            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;

            // Generate page numbers
            pageNumbers.innerHTML = '';
            const maxPages = Math.min(5, totalPages);
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + maxPages - 1);

            if (endPage - startPage < maxPages - 1) {
                startPage = Math.max(1, endPage - maxPages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = 'btn btn-sm';
                pageBtn.textContent = i;
                pageBtn.style.borderRadius = '0.5rem';
                pageBtn.style.border = i === currentPage ? '1px solid #004A53' : '1px solid #e0e0e0';
                pageBtn.style.color = i === currentPage ? '#fff' : '#004A53';
                pageBtn.style.backgroundColor = i === currentPage ? '#004A53' : '#fff';
                pageBtn.onclick = () => loadSubscriptionHistory(i);
                pageNumbers.appendChild(pageBtn);
            }
        }

        /**
         * Setup event listeners
         */
        function setupEventListeners() {
            const searchInput = document.getElementById('searchInput');
            const filterSelect = document.getElementById('filterSelect');

            searchInput.addEventListener('input', filterSubscriptions);
            filterSelect.addEventListener('change', filterSubscriptions);

            // Pagination buttons
            document.getElementById('prevBtn').addEventListener('click', () => {
                if (currentPage > 1) {
                    loadSubscriptionHistory(currentPage - 1);
                }
            });

            document.getElementById('nextBtn').addEventListener('click', () => {
                if (currentPage < totalPages) {
                    loadSubscriptionHistory(currentPage + 1);
                }
            });
        }

        /**
         * Filter subscriptions based on search and status
         */
        function filterSubscriptions() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const filterSelect = document.getElementById('filterSelect').value;

            filteredSubscriptions = allSubscriptions.filter(subscription => {
                const courseName = (subscription.course?.title || '').toLowerCase();
                const status = (subscription.status || '').toLowerCase();

                const matchesSearch = courseName.includes(searchInput);
                const matchesFilter = !filterSelect || status === filterSelect.toLowerCase();

                return matchesSearch && matchesFilter;
            });

            displaySubscriptions(filteredSubscriptions);
        }

        /**
         * Show error message
         */
        function showError(message) {
            ToastNotification.show(message, 'error');
        }

        // Make functions globally accessible
        window.loadSubscriptionHistory = loadSubscriptionHistory;
    </script>

@endsection
