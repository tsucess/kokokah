@extends('layouts.dashboardtemp')

@section('content')
    <!-- Authentication Check Script -->
    <script>
        // Check if user is authenticated via localStorage token
        (function() {
            const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
            const user = localStorage.getItem('auth_user');

            // If no token or user data, redirect to login
            if (!token || !user) {
                console.warn('No authentication token found. Redirecting to login...');
                window.location.href = '/login';
                return;
            }

            // Token exists, user can access dashboard
            console.log('User authenticated. Token found.');
        })();
    </script>

    <!-- Main -->
    <main>
        <div class="container-fluid px-4 pt-5">
            <div class = "row mb-4">
                <div class="d-flex justify-content-between">

                    <div>
                        <h1 class ="fw-bold">Welcome back, <span id="welcomeUserName" style="text-transform: capitalize">Admin</span></h1>
                            <p class = "text-muted">Here overview of your </p>
                    </div>


                    <div class = "d-flex gap-4 align-items-center">
                        <a href="/createsubject" class="btn-border-green"><i class="fa-solid fa-plus "></i> Add New
                            Course</a>
                        <a href="/adduser" class="btn-accent-yellow"><i class="fa-solid fa-plus"></i> Create New User</a>
                    </div>

                </div>
            </div>

            <!-- Stats -->
            <div class="stats-row" id="statsContainer">
                <div class="stat-card">
                    <img src = "images/abc.png" class = "img-fluid" />
                    {{-- <div class="stat-orb orb-users"><i class="fa-solid fa-users"></i></div> --}}
                    <div class="stat-meta">
                        <div class="label mt-2 inter-font">Total Users</div>
                        <div class="mt-2">
                            <p class="inter-font">
                                <i class="fa-solid fa-square text-success"></i> Male <span id="totalUsersMale">(0)</span>
                                <br />
                                <i class="fa-solid fa-square text-warning"></i> Female <span
                                    id="totalUsersFemale">(0)</span>
                            </p>
                        </div>
                        <div class="value inter-font" id="totalUsers">0</div>
                    </div>
                </div>

                <div class="stat-card">
                    <img src = "images/students.png" class = "img-fluid" />
                    <div class="stat-meta">
                        <div class="label mt-2 inter-font">Students</div>
                        <div class = "mt-2">
                            <p class="inter-font">
                                <i class="fa-solid fa-square text-success"></i> MALE <span id="totalStudentsMale">(0)</span>
                                <br />
                                <i class="fa-solid fa-square text-warning"></i> FEMALE <span
                                    id="totalStudentsFemale">(0)</span>
                            </p>
                        </div>
                        <div class="value inter-font" id="totalStudents">0</div>
                    </div>
                </div>

                <div class="stat-card">
                    <img src = "images/instructor.png" class = "img-fluid" />
                    <div class="stat-meta">
                        <div class="label inter-font">Instructors</div>
                        <div class = "mt-2">
                            <p class="inter-font">
                                <i class="fa-solid fa-square text-success"></i> MALE <span
                                    id="totalInstructorsMale">(0)</span> <br />
                                <i class="fa-solid fa-square text-warning"></i> FEMALE <span
                                    id="totalInstructorsFemale">(0)</span>
                            </p>
                        </div>
                        <div class="value inter-font" id="totalInstructors">0</div>
                    </div>
                </div>

                <div class="stat-card">
                    <img src = "images/abc.png" class = "img-fluid " />
                    <div class="stat-meta">
                        <div class="label inter-font">Active Courses</div>
                        <div class = "mt-2">
                            <p id="coursesByCategory" class="inter-font">
                                <i class="fa-solid fa-square text-success"></i> Loading... <br />
                            </p>
                        </div>
                        <div class="value inter-font" id="totalCourses">0</div>
                    </div>
                </div>
            </div>


            <!-- Chart -->
            <div class = "container-fluid px-0">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class = "information1">
                            <h6 class="fw-bold m-0">Deposits & Subscriptions</h6>
                            <p class="small text-muted">Performance overview</p>
                        </div>
                        <div class="d-flex align-items-center gap-3 information2">
                            <div class="legend-dot"><span class="dot" style="background:var(--brand-green)"></span>
                                <p>Deposits</p></div>
                            <div class="legend-dot"><span class="dot" style="background:var(--brand-yellow)"></span>
                               <p>Subscriptions</p> </div>
                            <select class="form-select form-select-sm w-auto ms-2" style="border-radius:12px;" id="chartPeriodSelector">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="yearly">Yearly</option>
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
                    <div class="d-flex flex-column gap-2">
                        <h6 class = "registeredusers">Recently Registered Users</h6>
                        <p class="small text-muted registeredusers" style="line-height: 1px;">Latest 10 registered users</p>
                    </div>
                    <a href="/users" class=" text-dark fw-semibold text-decoration-none link-alluser">View all users</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style='font-size:14px; padding:1rem;'>Name</th>
                                <th style='font-size:14px; padding:1rem;'>ID</th>
                                <th style='font-size:14px; padding:1rem;'>Role</th>
                                <th style='font-size:14px; padding:1rem;'>Gender</th>
                                <th style='font-size:14px; padding:1rem;'>Email</th>
                                <th style='font-size:14px; padding:1rem;'>Registered</th>
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
                <div class="d-flex justify-content-between align-items-center pt-2"
                 id="recentUsersPagination">

                    <!-- Pagination Info (Left) -->
                    <small class="text-muted fw-semibold" id="recentUsersInfo">Loading...</small>

                    <!-- Page Numbers (Centered) -->
                    <div class="d-flex gap-2 justify-content-center flex-grow-1" id="recentPageNumbers">
                        <!-- Generated dynamically -->
                    </div>

                    <!-- Navigation Buttons (Right) -->
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm" id="recentPrevBtn"
                            onclick="loadRecentUsers(currentPage - 1)"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;"
                            disabled>
                            <i class="fa-solid fa-chevron-left me-2"></i> Previous
                        </button>
                        <button type="button" class="btn btn-sm" id="recentNextBtn"
                            onclick="loadRecentUsers(currentPage + 1)"
                            style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
                            Next <i class="fa-solid fa-chevron-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>



        </div>


    </main>
    <!-- Chart.js (keep after body) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

        <!-- API Clients -->
    <script>
// Import API client
        // Get auth token
        const token = localStorage.getItem('auth_token');
        let currentPage = 1;
        let totalRecentPages = 1;
        const recentUsersPerPage = 10;

        // Fetch dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            loadRecentUsers(1);
            initializeChart();
        });

        // Load dashboard statistics
        async function loadDashboardStats() {
            try {
                const result = await AdminApiClient.getDashboardStats();

                if (!result.success) {
                    console.error('Failed to fetch dashboard stats:', result.message);
                    return;
                }

                const data = result.data;
                // console.log('Dashboard API Response:', data);
                // console.log('Data type:', typeof data);
                // console.log('Has statistics:', data && data.statistics);

                if (data && data.statistics) {
                    const stats = data.statistics;
                    // console.log('Stats:', stats);

                    // Update total users
                    const totalUsers = stats.users?.total || 0;
                    const students = stats.users?.by_role?.students || 0;
                    const instructors = stats.users?.by_role?.instructors || 0;

                    // console.log('Total Users:', totalUsers, 'Students:', students, 'Instructors:', instructors);

                    // Update elements with null checks
                    const totalUsersEl = document.getElementById('totalUsers');
                    if (totalUsersEl) totalUsersEl.textContent = totalUsers;

                    const totalStudentsEl = document.getElementById('totalStudents');
                    if (totalStudentsEl) totalStudentsEl.textContent = students;

                    const totalInstructorsEl = document.getElementById('totalInstructors');
                    if (totalInstructorsEl) totalInstructorsEl.textContent = instructors;

                    const totalCoursesEl = document.getElementById('totalCourses');
                    if (totalCoursesEl) totalCoursesEl.textContent = stats.courses?.total || 0;

                    // Update gender breakdown for users
                    const totalUsersMaleEl = document.getElementById('totalUsersMale');
                    if (totalUsersMaleEl) totalUsersMaleEl.textContent = `(${stats.users?.by_gender?.male || 0})`;

                    const totalUsersFemaleEl = document.getElementById('totalUsersFemale');
                    if (totalUsersFemaleEl) totalUsersFemaleEl.textContent = `(${stats.users?.by_gender?.female || 0})`;

                    // Update gender breakdown for students
                    const totalStudentsMaleEl = document.getElementById('totalStudentsMale');
                    if (totalStudentsMaleEl) totalStudentsMaleEl.textContent =
                        `(${stats.users?.students_by_gender?.male || 0})`;

                    const totalStudentsFemaleEl = document.getElementById('totalStudentsFemale');
                    if (totalStudentsFemaleEl) totalStudentsFemaleEl.textContent =
                        `(${stats.users?.students_by_gender?.female || 0})`;

                    // Update gender breakdown for instructors
                    const totalInstructorsMaleEl = document.getElementById('totalInstructorsMale');
                    if (totalInstructorsMaleEl) totalInstructorsMaleEl.textContent =
                        `(${stats.users?.instructors_by_gender?.male || 0})`;

                    const totalInstructorsFemaleEl = document.getElementById('totalInstructorsFemale');
                    if (totalInstructorsFemaleEl) totalInstructorsFemaleEl.textContent =
                        `(${stats.users?.instructors_by_gender?.female || 0})`;

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
                const result = await AdminApiClient.getRecentUsers(page, recentUsersPerPage);

                if (!result.success) {
                    console.error('Failed to fetch recent users:', result.message);
                    return;
                }

                currentPage = page;
                const users = result.data.data || result.data;
                const pagination = result.data;
                totalRecentPages = pagination.last_page || 1;

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
                  <td style='font-size:14px; padding:1rem;'>${user.first_name} ${user.last_name}</td>
                  <td style='font-size:14px; padding:1rem;'>${user.identifier}</td>
                  <td style='font-size:14px; padding:1rem;'><span class="badge" style="background-color: #004A53; color: white; padding:.5rem;">${user.role}</span></td>
                  <td style='font-size:14px; padding:1rem;'>${user.gender || 'N/A'}</td>
                  <td style='font-size:14px; padding:1rem;'>${user.email}</td>
                  <td style='font-size:14px; padding:1rem;'>${user.formatted_date}</td>
                </tr>
              `;
                        tbody.innerHTML += row;
                    });
                }

                // Update pagination info
                const startItem = (page - 1) * recentUsersPerPage + 1;
                const endItem = Math.min(page * recentUsersPerPage, pagination.total);
                const info = `Showing ${startItem}-${endItem} of ${pagination.total} users`;
                document.getElementById('recentUsersInfo').textContent = info;

                // Update pagination buttons
                document.getElementById('recentPrevBtn').disabled = !pagination.prev_page_url;
                document.getElementById('recentNextBtn').disabled = !pagination.next_page_url;

                // Generate page numbers
                generateRecentPageNumbers(currentPage, totalRecentPages);
            } catch (error) {
                console.error('Error loading recent users:', error);
            }
        }

        // Generate page number buttons for recent users
        function generateRecentPageNumbers(current, total) {
            const pageNumbersDiv = document.getElementById('recentPageNumbers');
            pageNumbersDiv.innerHTML = '';

            // Only show page numbers if there are multiple pages
            if (total <= 1) return;

            let startPage = Math.max(1, current - 1);
            let endPage = Math.min(total, current + 1);

            if (startPage > 1) {
                const btn = document.createElement('button');
                btn.className = 'btn btn-sm';
                btn.style.cssText =
                    'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-size: 0.85rem;';
                btn.textContent = '1';
                btn.onclick = () => loadRecentUsers(1);
                pageNumbersDiv.appendChild(btn);

                if (startPage > 2) {
                    const span = document.createElement('span');
                    span.style.color = '#999';
                    span.style.fontSize = '0.85rem';
                    span.textContent = '...';
                    pageNumbersDiv.appendChild(span);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement('button');
                btn.className = 'btn btn-sm';
                if (i === current) {
                    btn.style.cssText =
                        'background-color: #004A53; color: white; border: none; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.85rem;';
                } else {
                    btn.style.cssText =
                        'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-size: 0.85rem;';
                }
                btn.textContent = i;
                btn.onclick = () => loadRecentUsers(i);
                pageNumbersDiv.appendChild(btn);
            }

            if (endPage < total) {
                if (endPage < total - 1) {
                    const span = document.createElement('span');
                    span.style.color = '#999';
                    span.style.fontSize = '0.85rem';
                    span.textContent = '...';
                    pageNumbersDiv.appendChild(span);
                }

                const btn = document.createElement('button');
                btn.className = 'btn btn-sm';
                btn.style.cssText =
                    'border: 1px solid #ddd; color: #333; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-size: 0.85rem;';
                btn.textContent = total;
                btn.onclick = () => loadRecentUsers(total);
                pageNumbersDiv.appendChild(btn);
            }
        }    </script>

    <script>
        // Chart (with callout bubble plugin)
        let chartInstance = null;
        let chartLabels = [];
        let depositsData = [];
        let subscriptionsData = [];
        let currentChartPeriod = 'monthly';
        let chartDataCache = {};

        // Initialize chart with dynamic data
        async function initializeChart() {
            try {
                // Setup period selector listener
                setupChartPeriodSelector();

                // Load initial chart data
                await updateChartData();
            } catch (error) {
                console.error('Error initializing chart:', error);
                // Create chart with sample data if API fails
                const { labels } = generateChartDataForPeriod(currentChartPeriod);
                chartLabels = labels;
                // Add sample data for testing
                depositsData = labels.map(() => Math.floor(Math.random() * 100000) + 10000);
                subscriptionsData = labels.map(() => Math.floor(Math.random() * 80000) + 5000);
                createChart();
            }
        }

        // Fetch wallet deposits history
        async function fetchDepositsHistory(period) {
            try {
                const token = localStorage.getItem('auth_token') || localStorage.getItem('token');

                const response = await fetch('/api/wallet/transactions?type=deposit&limit=1000', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    return [];
                }

                const data = await response.json();

                if (data.success && data.data) {
                    const deposits = Array.isArray(data.data) ? data.data : [];
                    return deposits;
                }
                return [];
            } catch (error) {
                return [];
            }
        }

        // Fetch wallet course purchases (subscriptions)
        async function fetchSubscriptionHistory(period) {
            try {
                const token = localStorage.getItem('auth_token') || localStorage.getItem('token');

                const response = await fetch('/api/wallet/transactions?type=purchase&limit=1000', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    return [];
                }

                const data = await response.json();

                if (data.success && data.data) {
                    const subscriptions = Array.isArray(data.data) ? data.data : [];
                    return subscriptions;
                }
                return [];
            } catch (error) {
                return [];
            }
        }

        // Aggregate data by period
        function aggregateDataByPeriod(transactions, period) {
            const aggregated = {};
            const now = new Date();

            if (!Array.isArray(transactions)) {
                return aggregated;
            }

            transactions.forEach(transaction => {
                try {
                    // Handle different date field names
                    let dateStr = transaction.date || transaction.created_at || transaction.enrolled_at;
                    if (!dateStr) {
                        return;
                    }

                    const date = new Date(dateStr);
                    if (isNaN(date.getTime())) {
                        return;
                    }

                    let key;

                    switch(period) {
                        case 'daily':
                            // Last 30 days
                            if (now - date > 30 * 24 * 60 * 60 * 1000) return;
                            key = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                            break;
                        case 'weekly':
                            // Last 12 weeks
                            if (now - date > 12 * 7 * 24 * 60 * 60 * 1000) return;
                            const weekNum = Math.ceil((now - date) / (7 * 24 * 60 * 60 * 1000));
                            key = `Week ${weekNum}`;
                            break;
                        case 'monthly':
                            // Last 12 months
                            if (now - date > 12 * 30 * 24 * 60 * 60 * 1000) return;
                            key = date.toLocaleDateString('en-US', { month: 'short', year: '2-digit' });
                            break;
                        case 'yearly':
                            // All years
                            key = date.getFullYear().toString();
                            break;
                    }

                    if (!aggregated[key]) {
                        aggregated[key] = 0;
                    }

                    // Handle different amount field names
                    const amount = parseFloat(transaction.amount || transaction.price || 0);
                    aggregated[key] += amount;
                } catch (error) {
                    // Silently skip errors
                }
            });

            return aggregated;
        }

        // Generate chart labels and data for period
        function generateChartDataForPeriod(period) {
            const now = new Date();
            const labels = [];
            const dateMap = {};

            switch(period) {
                case 'daily':
                    // Last 30 days
                    for (let i = 29; i >= 0; i--) {
                        const date = new Date(now);
                        date.setDate(date.getDate() - i);
                        const label = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        labels.push(label);
                        dateMap[label] = 0;
                    }
                    break;
                case 'weekly':
                    // Last 12 weeks
                    for (let i = 11; i >= 0; i--) {
                        const label = `Week ${i + 1}`;
                        labels.push(label);
                        dateMap[label] = 0;
                    }
                    break;
                case 'monthly':
                    // Last 12 months
                    for (let i = 11; i >= 0; i--) {
                        const date = new Date(now);
                        date.setMonth(date.getMonth() - i);
                        const label = date.toLocaleDateString('en-US', { month: 'short', year: '2-digit' });
                        labels.push(label);
                        dateMap[label] = 0;
                    }
                    break;
                case 'yearly':
                    // Last 5 years
                    for (let i = 4; i >= 0; i--) {
                        const year = now.getFullYear() - i;
                        labels.push(year.toString());
                        dateMap[year.toString()] = 0;
                    }
                    break;
            }

            return { labels, dateMap };
        }

        // Create the chart
        function createChart() {
            // Destroy existing chart if it exists
            if (chartInstance) {
                chartInstance.destroy();
            }

            const ctx = document.getElementById('ieChart').getContext('2d');
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                            label: 'Deposits',
                            data: depositsData,
                            borderColor: getComputedStyle(document.documentElement).getPropertyValue(
                                '--brand-green').trim(),
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            fill: false,
                            tension: 0.45
                        },
                        {
                            label: 'Subscriptions',
                            data: subscriptionsData,
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
                            mode: 'index',
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('en-US', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10000,
                                callback: function(value) {
                                    return new Intl.NumberFormat('en-US', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }).format(value);
                                }
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
                }
            });
        }

        // Setup chart period selector
        function setupChartPeriodSelector() {
            const selector = document.getElementById('chartPeriodSelector');
            if (selector) {
                selector.addEventListener('change', function(e) {
                    currentChartPeriod = e.target.value;
                    updateChartData();
                });
            }
        }

        // Update chart data based on selected period
        async function updateChartData() {
            try {
                // Check cache first
                if (chartDataCache[currentChartPeriod]) {
                    const cached = chartDataCache[currentChartPeriod];
                    chartLabels = cached.labels;
                    depositsData = cached.deposits;
                    subscriptionsData = cached.subscriptions;
                    createChart();
                    return;
                }

                // Fetch fresh data
                const [depositsHistory, subscriptionHistory] = await Promise.all([
                    fetchDepositsHistory(currentChartPeriod),
                    fetchSubscriptionHistory(currentChartPeriod)
                ]);

                // Generate labels and date map
                const { labels, dateMap: depositsMap } = generateChartDataForPeriod(currentChartPeriod);
                const { dateMap: subscriptionsMap } = generateChartDataForPeriod(currentChartPeriod);

                // Aggregate deposits data
                const aggregatedDeposits = aggregateDataByPeriod(depositsHistory, currentChartPeriod);
                Object.keys(aggregatedDeposits).forEach(key => {
                    if (depositsMap.hasOwnProperty(key)) {
                        depositsMap[key] = aggregatedDeposits[key];
                    }
                });

                // Aggregate subscriptions data
                const aggregatedSubscriptions = aggregateDataByPeriod(subscriptionHistory, currentChartPeriod);
                Object.keys(aggregatedSubscriptions).forEach(key => {
                    if (subscriptionsMap.hasOwnProperty(key)) {
                        subscriptionsMap[key] = aggregatedSubscriptions[key];
                    }
                });

                // Convert maps to arrays in label order
                chartLabels = labels;
                depositsData = labels.map(label => depositsMap[label] || 0);
                subscriptionsData = labels.map(label => subscriptionsMap[label] || 0);

                // Cache the data
                chartDataCache[currentChartPeriod] = {
                    labels: chartLabels,
                    deposits: depositsData,
                    subscriptions: subscriptionsData
                };

                // Recreate the chart with new data
                createChart();
            } catch (error) {
                // Create chart with sample data on error
                const { labels } = generateChartDataForPeriod(currentChartPeriod);
                chartLabels = labels;
                depositsData = labels.map(() => Math.floor(Math.random() * 100000) + 10000);
                subscriptionsData = labels.map(() => Math.floor(Math.random() * 80000) + 5000);
                createChart();
            }
        }

        // Load and display user name in welcome message
        function loadWelcomeMessage() {
            try {
                const userStr = localStorage.getItem('auth_user');
                if (userStr) {
                    const user = JSON.parse(userStr);
                    const userName = user.name || user.first_name || 'Admin';
                    const userRole = user.role ? `(${user.role.charAt(0).toUpperCase() + user.role.slice(1)})` : '(Admin)';
                    document.getElementById('welcomeUserName').textContent = `${userName} ${userRole}`;
                }
            } catch (error) {
                console.error('Error loading welcome message:', error);
            }
        }

        // Call on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadWelcomeMessage();
            initializeChart();
        });
    </script>
@endsection
