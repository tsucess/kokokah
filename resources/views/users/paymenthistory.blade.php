@extends('layouts.usertemplate')
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
                    <div class="d-flex flex-column gap-3 align-items-md-center flex-md-row justify-content-between align-items-start mb-5">
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">Payment History</h5>
                        <div class="d-flex gap-3 flex-column flex-md-row">

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
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Transaction ID</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Subject</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Plan</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Amount</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Subscription Date</th>
                                    <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Expiry Date
                                    </th>
                                    {{-- <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Status</th> --}}
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

@endsection
