@extends('layouts.dashboardtemp')
@section('content')
    <main class="subjects-main">
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
        <div class="container-fluid px-5 py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-start mb-5">
                <div>
                    <h1 class="fw-bold mb-2">All Subjects</h1>
                    <p class="text-muted" style="font-size: 0.95rem;">Here overview of your</p>
                </div>
                <div>
                    <a href='/createsubject' class="btn px-4 py-2 fw-semibold"
                        style="background-color: #004A53; border: none; color: white;">
                        <i class="fa-solid fa-plus me-2"></i> Create New Subject
                    </a>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row g-4 mb-5">
                 <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4"
                        style="background: linear-gradient(135deg, #004A53 0%, #006b7d 100%); border: 1px solid #e8e8e8;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <p class="text-white-50 mb-2" style="font-size: 0.9rem;">Total Subjects</p>
                                    <h3 class="fw-bold text-white mb-0" id="totalSubjects">0</h3>
                                </div>
                                <div style="background: rgba(255,255,255,0.2); padding: 0.75rem; border-radius: 0.75rem;">
                                    <i class="fa-solid fa-book text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                 <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4"
                         style="background: linear-gradient(135deg, #FDAF22 0%, #ffc857 100%); border: 1px solid #e8e8e8;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <p class="text-white-50 mb-2" style="font-size: 0.9rem;">Published Subjects</p>
                                    {{-- <h3 class="fw-bold text-white mb-0">50</h3> --}}
                                    <h3 class="fw-bold text-white mb-0" id="activeSubjects">0</h3>
                                </div>
                                <div style="background: rgba(255,255,255,0.2); padding: 0.75rem; border-radius: 0.75rem;">
                                    <i class="fa-solid fa-users text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               

                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4"
                        style="background: linear-gradient(135deg, #6c757d 0%, #8a92a0 100%); border: 1px solid #e8e8e8;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <p class="text-white-50 mb-2" style="font-size: 0.9rem;">Drafted Subjects</p>
                                    {{-- <h3 class="fw-bold text-white mb-0">100</h3> --}}
                                    <h3 class="fw-bold text-white mb-0" id="draftCourses">0</h3>
                                </div>
                                <div style="background: rgba(255,255,255,0.2); padding: 0.75rem; border-radius: 0.75rem;">
                                    <i class="fa-solid fa-file-pen text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4"
                        style="background: linear-gradient(135deg, #28a745 0%, #3dd65f 100%); border: 1px solid #e8e8e8;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <p class="text-white-50 mb-2" style="font-size: 0.9rem;">Free Subjects</p>
                                    {{-- <h3 class="fw-bold text-white mb-0">50</h3> --}}
                                    <h3 class="fw-bold text-white mb-0" id="freeCourses">0</h3>
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
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">Subjects</h5>
                        <div class="d-flex gap-3 justify-content-end" style="flex: 1; margin-left: 2rem;">
                            <div class="d-flex gap-2 align-items-center search-border-custom">
                                <i class="fa-solid fa-search fa-xs " style="color: #999;"></i>
                                <input type="search" class="search-input-custom-input" id="searchInput"
                                    placeholder="Search by Name or Email" aria-label="Search">
                            </div>

                            <!-- Filter Dropdown -->
                            <select class="custom-select" id="filterSelect">
                                <option value="" style="">All Classes</option>
                                <option value="course">All Subjects</option>
                                <option value="category">All Categories</option>
                                <option value="role-student">Students</option>
                                <option value="role-instructor">Instructors</option>
                                <option value="role-admin">Admins</option>
                            </select>

                            <!-- View Options -->
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle subjects-table">
                            <thead>
                                <tr style="background-color: #f0f0f0; border-bottom: 2px solid #e8e8e8;">
                                    <th class="allSubject">No</th>
                                    <th class="allSubject">Subject Name</th>
                                    <th class="allSubject">Date Created</th>
                                    <th class="allSubject">Progress</th>
                                    <th class="allSubject">Ratings</th>
                                    <th class="allSubject">Status</th>
                                    <th class="allSubject">Action</th>
                                </tr>
                            </thead>
                            <tbody id="coursesTableBody">
                                <!-- Row 1 -->
                                <tr style="border-bottom: 1px solid #e8e8e8;">
                                    <td style="padding: 1rem; color: #666; font-size:14px;">01</td>
                                    <td style="padding: 1rem;">
                                        <div class="d-flex align-items-center">
                                            <img src="https://randomuser.me/api/portraits/men/1.jpg"
                                                class="rounded-circle me-3" width="40" height="40"
                                                style="object-fit: cover;">
                                            <span style="color: #333; font-weight: 500; font-size:14px;">English
                                                Language</span>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem; color: #666; font-size:14px;">02 August 2025</td>
                                    <td style="padding: 1rem;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress" style="width: 100px; height: 6px;">
                                                <div class="progress-bar" style="width: 70%; background-color: #28a745;">
                                                </div>
                                            </div>
                                            <span style="color: #666; font-size:14px;">70%</span>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem; color: #666; font-size:14px; "><i class="fa fa-star"
                                            style="color: #FDAF22;"></i> <span>4.5</span> </td>
                                    <td style="padding: 1rem;">
                                        <span class="badge"
                                            style="background-color: #28a745; color: white; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">Published</span>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <div class="d-flex gap-2">
                                            <a href="/editsubject" class="btn btn-sm btn-light"
                                                style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Edit">
                                                <i class="fa fa-edit" style="color: #004A53;"></i>
                                            </a>
                                            <button class="btn btn-sm btn-light"
                                                style="border: 1px solid #ddd; padding: 0.5rem 0.75rem;" title="Delete">
                                                <i class="fa fa-trash" style="color: #dc3545;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4"
                        style="border-top: 1px solid #e8e8e8;">
                        <!-- Previous Button -->
                        <button class="btn px-4 py-2"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
                            <i class="fa-solid fa-chevron-left me-2"></i> Previous
                        </button>

                        <!-- Pagination Info -->
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted fw-semibold" style="font-size: 0.9rem;">Page <strong
                                    style="color: #004A53;" id="currentPageDisplay">1</strong> of <strong
                                    style="color: #004A53;" id="totalPagesDisplay">1</strong></span>

                            <!-- Page Numbers -->
                            <div class="d-flex gap-2" id="paginationNumbers">
                                <!-- Dynamically generated page buttons -->
                            </div>
                        </div>

                        <!-- Next Button -->
                        <button class="btn px-4 py-2"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
                            Next <i class="fa-solid fa-chevron-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const API_COURSES = "/api/courses";
        const token = localStorage.getItem('auth_token');
        let currentPage = 1;
        let totalPages = 1;
        let paginationData = {};

        document.addEventListener("DOMContentLoaded", () => {
            loadCourses(1);
            setupPaginationListeners();
        });

        function setupPaginationListeners() {
            const prevBtn = document.querySelector('button:has(i.fa-chevron-left)');
            const nextBtn = document.querySelector('button:has(i.fa-chevron-right)');

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        loadCourses(currentPage - 1);
                    }
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        loadCourses(currentPage + 1);
                    }
                });
            }
        }

        async function loadCourses(page = 1) {
            try {
                const response = await fetch(`${API_COURSES}?page=${page}&per_page=12`,{
                            method: "GET",
                            headers: {
                                "Authorization": `Bearer ${token}`
                            }
            });
                const result = await response.json();

                if (!result.success) {
                    console.error("API Error:", result.message);
                    return;
                }

                const coursesData = result.data;

                console.log("Courses Response:", coursesData);

                currentPage = page;
                paginationData = coursesData.courses;
                totalPages = coursesData.courses.last_page || 1;

                populateTable(coursesData.courses.data);
                updateStats(coursesData.courses.data);
                updatePaginationUI();

            } catch (error) {
                console.error("Fetch Error:", error);
            }
        }


        /* -------------------------
           Populate Table
        ---------------------------- */
        function populateTable(courses) {
            const tbody = document.getElementById("coursesTableBody");
            tbody.innerHTML = "";

            if (!courses.length) {
                tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    No courses found.
                </td>
            </tr>`;
                return;
            }

            courses.forEach((course, index) => {
                const rowNumber = (currentPage - 1) * 12 + index + 1;
                const progress = course.progress || 0;
                const rating = course.average_rating || 0;
                const ratingDisplay = rating > 0 ? rating.toFixed(1) : '0.0';

                // Generate star rating display
                const fullStars = Math.floor(rating);
                const hasHalfStar = rating % 1 >= 0.5;
                let starsHtml = '';

                for (let i = 0; i < 5; i++) {
                    if (i < fullStars) {
                        starsHtml += '<i class="fa fa-star" style="color:#FDAF22;"></i>';
                    } else if (i === fullStars && hasHalfStar) {
                        starsHtml += '<i class="fa fa-star-half-alt" style="color:#FDAF22;"></i>';
                    } else {
                        starsHtml += '<i class="fa fa-star" style="color:#ddd;"></i>';
                    }
                }

                const row = `
            <tr style="border-bottom: 1px solid #e8e8e8;">
                <td style='font-size:14px;'>${rowNumber}</td>

                <td style='font-size:14px;'>${course.title}</td>

                <td style='font-size:14px;'>${formatDate(course.created_at)}</td>

                <td style='font-size:14px;'>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress" style="width: 100px; height: 6px; background-color: #e9ecef;">
                            <div class="progress-bar" style="width: ${progress}%; background-color: #004A53;"></div>
                        </div>
                        <span>${progress.toFixed(1)}%</span>
                    </div>
                </td>

                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        ${starsHtml}
                        <span style='font-size:14px;'>${ratingDisplay}</span>
                    </div>
                </td>

                <td>
                    <span class="badge"
                          style="background-color:${course.status === 'published' ? '#28a745' : '#6c757d'};
                                 color:white; padding:0.5rem 0.75rem; border-radius:0.5rem; font-size:14px;">
                        ${course.status}
                    </span>
                </td>

                <td>
                    <div class="d-flex gap-2">
                        <a href="/editsubject?id=${course.id}"
                           class="btn btn-sm btn-light" style="border:1px solid #ddd;">
                            <i class="fa fa-edit" style="color:#004A53;"></i>
                        </a>

                        <button onclick="deleteCourse(${course.id})"
                                class="btn btn-sm btn-light" style="border:1px solid #ddd;">
                            <i class="fa fa-trash" style="color:#dc3545;"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
                tbody.insertAdjacentHTML("beforeend", row);
            });
        }

        /* -------------------------
           Update Dashboard Stats
        ---------------------------- */
        function updateStats(courses) {

            // Total subjects count
            document.getElementById("totalSubjects").innerText = courses.length;

            document.getElementById("activeSubjects").innerText =
                courses.filter(c => c.status === "published").length;

            document.getElementById("draftCourses").innerText =
                courses.filter(c => c.status === "draft").length;

            document.getElementById("freeCourses").innerText =
                courses.filter(c => c.free_subscription === true || c.free_subscription === 1).length;
        }


        /* -------------------------
           Update Pagination UI
        ---------------------------- */
        function updatePaginationUI() {
            // Update page info
            const currentPageDisplay = document.getElementById('currentPageDisplay');
            const totalPagesDisplay = document.getElementById('totalPagesDisplay');

            if (currentPageDisplay) currentPageDisplay.textContent = currentPage;
            if (totalPagesDisplay) totalPagesDisplay.textContent = totalPages;

            // Update Previous button
            const prevBtn = document.querySelector('button:has(i.fa-chevron-left)');
            if (prevBtn) {
                prevBtn.disabled = currentPage === 1;
                prevBtn.style.opacity = currentPage === 1 ? '0.5' : '1';
                prevBtn.style.cursor = currentPage === 1 ? 'not-allowed' : 'pointer';
            }

            // Update Next button
            const nextBtn = document.querySelector('button:has(i.fa-chevron-right)');
            if (nextBtn) {
                nextBtn.disabled = currentPage >= totalPages;
                nextBtn.style.opacity = currentPage >= totalPages ? '0.5' : '1';
                nextBtn.style.cursor = currentPage >= totalPages ? 'not-allowed' : 'pointer';
            }

            // Generate page number buttons
            generatePageNumbers();
        }

        /* -------------------------
           Generate Page Number Buttons
        ---------------------------- */
        function generatePageNumbers() {
            const paginationContainer = document.getElementById('paginationNumbers');
            paginationContainer.innerHTML = '';

            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            // Adjust start page if we're near the end
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // Add first page button if not visible
            if (startPage > 1) {
                const firstBtn = createPageButton(1);
                paginationContainer.appendChild(firstBtn);

                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.color = '#999';
                    paginationContainer.appendChild(dots);
                }
            }

            // Add page number buttons
            for (let i = startPage; i <= endPage; i++) {
                const btn = createPageButton(i);
                paginationContainer.appendChild(btn);
            }

            // Add last page button if not visible
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.color = '#999';
                    paginationContainer.appendChild(dots);
                }

                const lastBtn = createPageButton(totalPages);
                paginationContainer.appendChild(lastBtn);
            }
        }

        /* -------------------------
           Create Page Button
        ---------------------------- */
        function createPageButton(pageNum) {
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm';
            btn.textContent = pageNum;
            btn.type = 'button';

            if (pageNum === currentPage) {
                btn.style.backgroundColor = '#004A53';
                btn.style.color = 'white';
                btn.style.border = 'none';
                btn.style.width = '2.5rem';
                btn.style.height = '2.5rem';
                btn.style.borderRadius = '0.5rem';
                btn.style.fontWeight = '600';
            } else {
                btn.style.border = '1px solid #ddd';
                btn.style.color = '#333';
                btn.style.width = '2.5rem';
                btn.style.height = '2.5rem';
                btn.style.borderRadius = '0.5rem';
            }

            btn.addEventListener('click', () => {
                loadCourses(pageNum);
            });

            return btn;
        }

        /* -------------------------
           Helper: Format date
        ---------------------------- */
        function formatDate(dateString) {
            if (!dateString) return "N/A";
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", {
                day: "2-digit",
                month: "long",
                year: "numeric"
            });
        }
    </script>
@endsection
