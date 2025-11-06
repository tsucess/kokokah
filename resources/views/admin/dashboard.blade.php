@extends('layouts.dashboardtemp')

@section('content')
    <!-- Main -->
    <main>
        <div class="container">
            <div class = "row">
                <div class="d-flex justify-content-between">

                    <div>
                        <h4 class ="fw-bold">Welcome back, <span id="first_name">Samuel</span> <span
                                id="role">(Admin)</span></h4>
                        <p class = "text-muted">Here overview of your </p>
                    </div>


                    <div class = "d-flex ms-auto">
                        <button class="btn btn-nav-secondary me-3"><i class="fa-solid fa-plus me-2"></i> Add New
                            Course</button>
                        <button class="btn btn-nav-primary"><i class="fa-solid fa-plus me-2"></i> Create New User</button>
                    </div>

                </div>
            </div>

            <!-- Stats -->
            <div class="stats-row" id="statsContainer">
                <div class="stat-card">
                    <img src = "images/abc.png" class = "img-fluid" />
                    {{-- <div class="stat-orb orb-users"><i class="fa-solid fa-users"></i></div> --}}
                    <div class="stat-meta">
                        <div class="label mt-2">Total Users</div>
                        <div class="mt-2">
                            <p>
                                <i class="fa-solid fa-square text-success"></i> Male <span id="totalUsersMale">(0)</span> <br />
                                <i class="fa-solid fa-square text-warning"></i> Female <span id="totalUsersFemale">(0)</span>
                            </p>
                        </div>
                        <div class="value" id="totalUsers">0</div>
                    </div>
                </div>

                <div class="stat-card">
                    <img src = "images/students.png" class = "img-fluid" />
                    <div class="stat-meta">
                        <div class="label mt-2">Students</div>
                        <div class = "mt-2">
                            <p>
                                <i class="fa-solid fa-square text-success"></i> MALE <span id="totalStudentsMale">(0)</span> <br />
                                <i class="fa-solid fa-square text-warning"></i> FEMALE <span id="totalStudentsFemale">(0)</span>
                            </p>
                        </div>
                        <div class="value" id="totalStudents">0</div>
                    </div>
                </div>

                <div class="stat-card">
                    <img src = "images/instructor.png" class = "img-fluid" />
                    <div class="stat-meta">
                        <div class="label">Instructors</div>
                        <div class = "mt-2">
                            <p>
                                <i class="fa-solid fa-square text-success"></i> MALE <span id="totalInstructorsMale">(0)</span> <br />
                                <i class="fa-solid fa-square text-warning"></i> FEMALE <span id="totalInstructorsFemale">(0)</span>
                            </p>
                        </div>
                        <div class="value" id="totalInstructors">0</div>
                    </div>
                </div>

                <div class="stat-card">
                    <img src = "images/abc.png" class = "img-fluid" />
                    <div class="stat-meta">
                        <div class="label">Active Courses</div>
                        <div class = "mt-2">
                            <p id="coursesByCategory">
                                <i class="fa-solid fa-square text-success"></i> Loading... <br />
                            </p>
                        </div>
                        <div class="value" id="totalCourses">0</div>
                    </div>
                </div>
            </div>


            <!-- Chart -->
            <div class = "container">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class = "information1">
                            <h6 class="fw-bold m-0">Income & Expense</h6>
                            <p class="small text-muted">Performance overview</p>
                        </div>
                        <div class="d-flex align-items-center gap-3 information2">
                            <div class="legend-dot"><span class="dot" style="background:var(--brand-green)"></span>
                                Income</div>
                            <div class="legend-dot"><span class="dot" style="background:var(--brand-yellow)"></span>
                                Profit</div>
                            <select class="form-select form-select-sm w-auto ms-2" style="border-radius:12px;">
                                <option selected>Yearly</option>
                                <option>Monthly</option>
                            </select>
                        </div>
                    </div>

                    <div style="height:320px;">
                        <canvas id="ieChart"></canvas>
                    </div>

                </div>
            </div>

            <!-- Recently Registered Users table (restored) -->
            <div class="table-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class = "registeredusers">Recently Registered Users</h6>
                        <p class="small text-muted registeredusers" style="line-height: 1px;">Latest 10 registered users</p>
                    </div>
                    <a href="/users" class="small text-dark fw-semibold text-decoration-none">View all users</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Role</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody id="recentUsersTableBody">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-spinner fa-spin me-2"></i>Loading users...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Recently Registered Users -->
                <div class="d-flex justify-content-between align-items-center mt-3" id="recentUsersPagination">
                    <small class="text-muted" id="recentUsersInfo">Loading...</small>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="prevBtn"
                            onclick="loadRecentUsers(currentPage - 1)" disabled>
                            <i class="fa-solid fa-chevron-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="nextBtn"
                            onclick="loadRecentUsers(currentPage + 1)">
                            Next <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>



        </div>

        <!-- Chart.js (keep after body) -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

        <script>
            // Get auth token
            const token = localStorage.getItem('auth_token');
            let currentPage = 1;

            // Fetch dashboard data on page load
            document.addEventListener('DOMContentLoaded', function() {
                loadDashboardStats();
                loadRecentUsers(1);
            });

            // Load dashboard statistics
            async function loadDashboardStats() {
                try {
                    const response = await fetch('/api/admin/dashboard', {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        console.error('Failed to fetch dashboard stats:', response.status);
                        return;
                    }

                    const data = await response.json();
                    console.log('Dashboard API Response:', data);

                    if (data.success && data.data && data.data.statistics) {
                        const stats = data.data.statistics;
                        console.log('Stats:', stats);

                        // Update total users
                        const totalUsers = stats.users.total;
                        const students = stats.users.by_role.students;
                        const instructors = stats.users.by_role.instructors;

                        console.log('Total Users:', totalUsers, 'Students:', students, 'Instructors:', instructors);

                        // Update elements with null checks
                        const totalUsersEl = document.getElementById('totalUsers');
                        if (totalUsersEl) totalUsersEl.textContent = totalUsers;

                        const totalStudentsEl = document.getElementById('totalStudents');
                        if (totalStudentsEl) totalStudentsEl.textContent = students;

                        const totalInstructorsEl = document.getElementById('totalInstructors');
                        if (totalInstructorsEl) totalInstructorsEl.textContent = instructors;

                        const totalCoursesEl = document.getElementById('totalCourses');
                        if (totalCoursesEl) totalCoursesEl.textContent = stats.courses.total;

                        // Update gender breakdown for users
                        const totalUsersMaleEl = document.getElementById('totalUsersMale');
                        if (totalUsersMaleEl) totalUsersMaleEl.textContent = `(${stats.users.by_gender.male})`;

                        const totalUsersFemaleEl = document.getElementById('totalUsersFemale');
                        if (totalUsersFemaleEl) totalUsersFemaleEl.textContent = `(${stats.users.by_gender.female})`;

                        // Update gender breakdown for students
                        const totalStudentsMaleEl = document.getElementById('totalStudentsMale');
                        if (totalStudentsMaleEl) totalStudentsMaleEl.textContent = `(${stats.users.students_by_gender.male})`;

                        const totalStudentsFemaleEl = document.getElementById('totalStudentsFemale');
                        if (totalStudentsFemaleEl) totalStudentsFemaleEl.textContent = `(${stats.users.students_by_gender.female})`;

                        // Update gender breakdown for instructors
                        const totalInstructorsMaleEl = document.getElementById('totalInstructorsMale');
                        if (totalInstructorsMaleEl) totalInstructorsMaleEl.textContent = `(${stats.users.instructors_by_gender.male})`;

                        const totalInstructorsFemaleEl = document.getElementById('totalInstructorsFemale');
                        if (totalInstructorsFemaleEl) totalInstructorsFemaleEl.textContent = `(${stats.users.instructors_by_gender.female})`;

                        // Update courses by category
                        const coursesByCategoryEl = document.getElementById('coursesByCategory');
                        if (coursesByCategoryEl && stats.courses.by_category) {
                            let categoryHtml = '';
                            const categories = Object.entries(stats.courses.by_category);
                            categories.forEach((entry, index) => {
                                // const [categoryName, count] = entry;
                                // const color = index % 2 === 0 ? 'text-success' : 'text-warning';
                                // categoryHtml += `<i class="fa-solid fa-square ${color}"></i> ${categoryName} <span>(${count})</span>`;
                                // if (index < categories.length - 1) categoryHtml += ' <br />';
                            });
                            coursesByCategoryEl.innerHTML = categoryHtml;
                        }

                        // Calculate and update percentages (if elements exist)
                        const studentPercent = totalUsers > 0 ? Math.round((students / totalUsers) * 100) : 0;
                        const instructorPercent = totalUsers > 0 ? Math.round((instructors / totalUsers) * 100) : 0;

                        const studentPercentEl = document.getElementById('studentPercent');
                        if (studentPercentEl) studentPercentEl.textContent = studentPercent + '%';

                        const instructorPercentEl = document.getElementById('instructorPercent');
                        if (instructorPercentEl) instructorPercentEl.textContent = instructorPercent + '%';
                    } else {
                        console.error('Unexpected response structure:', data);
                    }
                } catch (error) {
                    console.error('Error loading dashboard stats:', error);
                }
            }

            // Load recently registered users
            async function loadRecentUsers(page = 1) {
                try {
                    const response = await fetch(`/api/admin/users/recent?page=${page}&per_page=10`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        console.error('Failed to fetch recent users');
                        return;
                    }

                    const data = await response.json();
                    if (data.success && data.data) {
                        currentPage = page;
                        const users = data.data.data;
                        const pagination = data.data;

                        // Update table
                        const tbody = document.getElementById('recentUsersTableBody');
                        tbody.innerHTML = '';

                        if (users.length === 0) {
                            tbody.innerHTML =
                                '<tr><td colspan="6" class="text-center text-muted py-4">No users found</td></tr>';
                        } else {
                            users.forEach((user, index) => {
                                const statusBadge = user.is_active ?
                                    '<span class="badge text-success" style="background: #DCFCE7;"><i class="fa fa-circle p-1 text-success" style="font-size:10px;"></i>Active</span>' :
                                    '<span class="badge bg-danger text-white"><i class="fa fa-circle p-1 text-white" style="font-size:10px;"></i>Inactive</span>';

                                const row = `
                <tr>
                  <td>${user.first_name} ${user.last_name}</td>
                  <td>${user.identifier}</td>
                  <td><span class="badge" style="background-color: #004A53; color: white;">${user.role}</span></td>
                  <td>${user.gender || 'N/A'}</td>
                  <td>${user.email}</td>
                  <td>${user.formatted_date}</td>
                </tr>
              `;
                                tbody.innerHTML += row;
                            });
                        }

                        // Update pagination info
                        const info = `Showing ${users.length} of ${pagination.total} users`;
                        document.getElementById('recentUsersInfo').textContent = info;

                        // Update pagination buttons
                        document.getElementById('prevBtn').disabled = !pagination.prev_page_url;
                        document.getElementById('nextBtn').disabled = !pagination.next_page_url;
                    }
                } catch (error) {
                    console.error('Error loading recent users:', error);
                }
            }
        </script>

        <script>
            // Chart (with callout bubble plugin)
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const incomeData = [68, 64, 80, 86, 92, 88, 70, 78, 82, 90, 84, 72];
            const profitData = [32, 40, 34, 30, 44, 52, 60, 58, 62, 60, 66, 22];
            const julyIndex = months.indexOf('Jul');

            const calloutPlugin = {
                id: 'callout',
                afterDatasetsDraw(chart) {
                    const {
                        ctx,
                        chartArea: {
                            top,
                            left,
                            right
                        }
                    } = chart;
                    const meta = chart.getDatasetMeta(0);
                    const point = meta.data[julyIndex];
                    if (!point) return;

                    const x = point.x;
                    const y = point.y - 24;
                    const text1 = '$77,000';
                    const text2 = '09 Projects';

                    ctx.save();
                    ctx.font = '600 12px Inter, sans-serif';
                    const w = Math.max(ctx.measureText(text1).width, ctx.measureText(text2).width) + 16;
                    const h = 38;
                    const r = 8;

                    const bx = Math.min(Math.max(x - w / 2, left + 6), right - w - 6);
                    const by = Math.max(y - h, top + 6);

                    ctx.fillStyle = '#FFFFFF';
                    ctx.strokeStyle = '#E5EAF0';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(bx + r, by);
                    ctx.arcTo(bx + w, by, bx + w, by + r, r);
                    ctx.arcTo(bx + w, by + h, bx + w - r, by + h, r);
                    ctx.arcTo(bx, by + h, bx, by + h - r, r);
                    ctx.arcTo(bx, by, bx + r, by, r);
                    ctx.closePath();
                    ctx.fill();
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.moveTo(x - 6, by + h);
                    ctx.lineTo(x, by + h + 8);
                    ctx.lineTo(x + 6, by + h);
                    ctx.closePath();
                    ctx.fill();
                    ctx.stroke();

                    ctx.fillStyle = '#0F1C24';
                    ctx.fillText(text1, bx + 8, by + 16);
                    ctx.fillStyle = '#6B737A';
                    ctx.fillText(text2, bx + 8, by + 30);
                    ctx.restore();
                }
            };

            const ctx = document.getElementById('ieChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                            label: 'Income',
                            data: incomeData,
                            borderColor: getComputedStyle(document.documentElement).getPropertyValue(
                                '--brand-green').trim(),
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            fill: false,
                            tension: 0.45
                        },
                        {
                            label: 'Profit',
                            data: profitData,
                            borderColor: getComputedStyle(document.documentElement).getPropertyValue(
                                '--brand-yellow').trim(),
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            fill: false,
                            tension: 0.45
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            intersect: false,
                            mode: 'index'
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 100,
                            ticks: {
                                stepSize: 10,
                                callback: v => v + '%'
                            },
                            grid: {
                                color: '#EFF3F6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    elements: {
                        line: {
                            borderWidth: 2
                        }
                    }
                },
                plugins: [calloutPlugin]
            });
        </script>

    </main>
@endsection
