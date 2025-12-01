@extends('layouts.dashboardtemp')

@section('content')
    <main class="transactions-main">
        <div class="container-fluid px-5 py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h1 class="fw-bold mb-2"
                        style="font-size: 1.2rem; color: #004A53; font-family: 'Fredoka One', sans-serif;">Welcome Back
                        Samuel(Admin)</h1>
                    <p class="text-muted" style="font-size: 0.95rem;">Here overview of your</p>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                <div class="card-body p-4">
                    <!-- Table Header with Search and Filters -->
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">Transactions</h5>
                        <div class="d-flex gap-3 ">
                            <!-- Search Input -->
                            {{-- <div class="position-relative flex-grow-1" style="max-width: 300px;">
              <i class="fa-solid fa-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: #999;"></i>
              <input
                type="text"
                class="form-control search-input-custom"
                id="searchInput"
                placeholder="Search"
                aria-label="Search">
            </div>

            <!-- Filter Dropdown -->
            <select class="form-select filter-select-custom" id="filterSelect" style="max-width: 200px;">
              <option value="">All Status</option>
              <option value="paid">Paid</option>
              <option value="pending">Pending</option>
              <option value="failed">Failed</option>
            </select> --}}
                            <div class="d-flex gap-2 align-items-center search-border-custom">
                                <i class="fa-solid fa-search fa-xs " style="color: #999;"></i>
                                <input type="search" class="search-input-custom-input" id="searchInput"
                                    placeholder="Search by Name or Email" aria-label="Search">
                            </div>

                            <!-- Filter Dropdown -->
                            <select class="custom-select" id="filterSelect">
                                <option value="" style="">All Classes</option>
                                <option value="course">All Courses</option>
                                <option value="category">All Categories</option>
                                <option value="role-student">Students</option>
                                <option value="role-instructor">Instructors</option>
                                <option value="role-admin">Admins</option>
                            </select>


                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle transactions-table">
                            <thead>
                                <tr style="background-color: #f0f0f0; border-bottom: 2px solid #e8e8e8;">
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">ID</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">User</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Date</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Amount</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Plan</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Payment Method
                                    </th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="transactionsTableBody">
                                <tr style="border-bottom: 1px solid #e8e8e8;">
                                    <td colspan="7" class="text-center text-muted py-4" style="font-size:16px;">
                                        <i class="fa-solid fa-spinner fa-spin me-2"></i>Loading transactions...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4"
                        style="border-top: 1px solid #e8e8e8;">
                        <!-- Previous Button -->
                        <button class="btn px-4 py-2" id="prevBtn" onclick="loadTransactions(currentPage - 1)"
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
                        <button class="btn px-4 py-2" id="nextBtn" onclick="loadTransactions(currentPage + 1)"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
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
            loadTransactions(1);

            document.getElementById('searchInput').addEventListener('input', function(e) {
                currentSearch = e.target.value;
                loadTransactions(1);
            });

            document.getElementById('filterSelect').addEventListener('change', function(e) {
                currentFilter = e.target.value;
                loadTransactions(1);
            });
        });

        async function loadTransactions(page = 1) {
            try {
                let url = `/api/admin/transactions?page=${page}&per_page=20`;

                if (currentSearch) {
                    url += `&search=${encodeURIComponent(currentSearch)}`;
                }

                if (currentFilter) {
                    url += `&status=${encodeURIComponent(currentFilter)}`;
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
                    console.error('Failed to fetch transactions:');
                    return;


                }

                const data = await response.json();
                if (data.success && data.data) {
                    currentPage = page;
                    const transactions = data.data.data;
                    const pagination = data.data;
                    totalPages = pagination.last_page;

                    const tbody = document.getElementById('transactionsTableBody');
                    tbody.innerHTML = '';

                    if (transactions.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="7" class="text-center text-muted py-4">No transactions found</td></tr>';
                    } else {
                        transactions.forEach((transaction, index) => {
                            const statusColor = transaction.status === 'success' ? '#28a745' :
                                transaction.status === 'pending' ? '#ffc107' : '#dc3545';
                            const statusText = transaction.status.charAt(0).toUpperCase() + transaction.status
                                .slice(1);

                            const row = `
              <tr style="border-bottom: 1px solid #e8e8e8;">
                <td style="padding: 1rem; color: #666; font-size:14px;">0${index + 1}</td>
                <td style="padding: 1rem; font-size:14px;">
                  <div class="d-flex align-items-center">
                    <img src="images/winner-round.png" class="rounded-circle me-3" width="32" height="32" style="object-fit: cover; background: #f0f0f0;">
                    <span style="color: #333; font-weight: 500;">${transaction.user_name || 'N/A'}</span>
                  </div>
                </td>
                <td style="padding: 1rem; color: #666; font-size:14px;">${new Date(transaction.created_at).toLocaleDateString()}</td>
                <td style="padding: 1rem; color: #666; font-weight: 500; font-size:14px;">${transaction.amount || '0'}</td>
                <td style="padding: 1rem; color: #666; font-size:14px;">${transaction.plan || 'N/A'}</td>
                <td style="padding: 1rem; color: #666; font-size:14px;">${transaction.payment_method || 'N/A'}</td>
                <td style="padding: 1rem; font-size:14px;">
                  <span class="badge" style="background-color: ${statusColor}; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">● ${statusText}</span>
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
                }
            } catch (error) {
                console.error('Error loading transactions:', error);
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
                btn.style.cssText =
                    'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;';
                btn.textContent = '1';
                btn.onclick = () => loadTransactions(1);
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
                btn.onclick = () => loadTransactions(i);
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
                btn.onclick = () => loadTransactions(total);
                pageNumbersDiv.appendChild(btn);
            }
        }
    </script>

    <style>
        .transactions-main {
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

        .transactions-table tbody tr:hover {
            background-color: #f5f5f5;
            transition: background-color 0.2s ease;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .card {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        }

        .badge {
            font-size: 0.85rem;
            font-weight: 600;
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

            .transactions-table {
                font-size: 0.85rem;
            }

            .transactions-table th,
            .transactions-table td {
                padding: 0.75rem !important;
            }
        }
    </style>
@endsection
