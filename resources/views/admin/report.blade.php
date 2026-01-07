@extends('layouts.dashboardtemp')

@section('content')
    <style>
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px
        }

        .stats-box {
            border: 1px solid #CCDBDD;
            border-radius: 20px;
            padding: 30px;
        }

        .stats-box-title {
            color: #004A53;
            font-size: 16px;
        }

        .stats-box-percentage-large {
            font-style: medium;
            font-size: 32px;
            color: #004A53;
            font-weight: 600;
        }

        .stats-box-percentage-small {
            font-size: 14px;
            color: #1AAE50;
            font-weight: 700;
        }

        .course-performance-container {
            border: 1px solid #C4C4C4;
            border-radius: 24px;
            padding: 16px;
        }

        .course-performance-btn {
            color: #00000080;
            font-size: 10px;
            text-transform: uppercase;
        }

        .chart-menu {
            border: 1px solid #CCDBDD;
            border-radius: 10px;
        }

        .divider {
            width: 1px;
            height: 100%;
            background-color: #CCDBDD;
        }

        .btn-chart-engagement {
            color: #777777;
            font-size: 12px;
            padding: 5px 8px;
        }
    </style>
    <main class="">
        <section class="container-fluid p-4 d-flex flex-column gap-5">
            <header class="d-flex align-items-center justify-content-between gap-2">
                <div>
                    <h1>Report & Analytics</h1>
                    <p>Here overview of your </p>
                </div>
                {{-- <div class="d-flex gap-3 align-items-center">
                    <a href="http://" class="btn-yellow-action d-flex gap-1 align-items-center"
                        style="text-decoration: none;"><i class="fa-solid fa-plus"></i>Add Category</a>
                    <a href="http://" class="btn-secondary-action d-flex gap-1 align-items-center"
                        style="text-decoration: none;"><i class="fa-solid fa-plus"></i> Add Term</a>
                </div> --}}
            </header>
            <section class="d-flex flex-column gap-4">
                <div class="stats-container">
                    <div class="d-flex flex-column gap-4 stats-box">
                        <h4 class="stats-box-title">Total Students</h4>
                        <p class="stats-box-percentage-large">23,453</p>
                        <p class="stats-box-percentage-small">↗️ 5.4%</p>
                    </div>
                    <div class="d-flex flex-column gap-4 stats-box">
                        <h4 class="stats-box-title">Total Teachers</h4>
                        <p class="stats-box-percentage-large">3,456</p>
                        <p class="stats-box-percentage-small">↗️ 5.4%</p>
                    </div>
                    <div class="d-flex flex-column gap-4 stats-box">
                        <h4 class="stats-box-title">Active Subject</h4>
                        <p class="stats-box-percentage-large">112</p>
                        <p class="stats-box-percentage-small">↗️ 5.4%</p>
                    </div>
                    <div class="d-flex flex-column gap-4 stats-box">
                        <h4 class="stats-box-title">Average Subject Progress</h4>
                        <p class="stats-box-percentage-large">75%</p>
                        <p class="stats-box-percentage-small">↗️ 5.4%</p>
                    </div>
                </div>

                <div class="course-performance-container d-flex flex-column gap-4">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <h6 class="fw-bold">Engagement Overview</h6>
                        <div class="chart-menu d-flex gap-1 px-2">
                            <button data-range="day" class="btn-chart-engagement">Day</button>
                            <div class="divider"></div>
                            <button data-range="week" class="btn-chart-engagement">Week</button>
                            <div class="divider"></div>
                            <button data-range="month" class="btn-chart-engagement">Month</button>
                            <div class="divider"></div>
                            <button data-range="year" class="btn-chart-engagement">Year</button>
                        </div>
                    </div>
                    <div>
                        <canvas id="engagementChart"></canvas>
                    </div>
                </div>

                <div class="course-performance-container d-flex flex-column gap-4">
                    <div class="d-flex align-items-center gap-3 justify-content-between">
                        <div>
                            <h6 class="fw-bold">Course Performance</h6>
                            <p>Course completion Rate</p>
                        </div>
                        <button class="course-performance-btn">More</button>
                    </div>
                    <div> <canvas id='coursePerformance'></canvas></div>
                </div>


                {{-- //table section --}}

                <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                    <div class="card-body p-4">
                        <!-- Table Header with Search and Filters -->
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">Student Performance
                                Analytics</h5>
                            <div class="d-flex gap-3 justify-content-end" style="flex: 1; margin-left: 2rem;">
                                <!-- Search Input -->
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
                                        <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">ID</th>
                                        <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Students
                                            Name</th>
                                        <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Subjects
                                        </th>
                                        <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Completion
                                            Rate</th>
                                        <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Average
                                            Score</th>
                                        <th style="color: #333; font-weight: 600; padding: 1rem; font-size:14px;">Last
                                            Active</th>
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
                        <div class="d-flex justify-content-between align-items-center pt-4"
                            <!-- Previous Button -->
                            <button class="btn px-4 py-2" id="prevBtn"
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
                            <button class="btn px-4 py-2" id="nextBtn"
                                style="border: 1px solid #004A53; color: #004A53; font-weight: 500; border-radius: 0.5rem;">
                                Next <i class="fa-solid fa-chevron-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </section>
        </section>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script>
        // Get auth token from localStorage
        const token = localStorage.getItem('auth_token');
        const apiBaseUrl = '/api';

        // Initialize charts
        let engagementChart = null;
        let coursePerformanceChart = null;

        // Fetch dashboard stats
        async function loadDashboardStats() {
            try {
                const response = await fetch(`${apiBaseUrl}/dashboard/admin`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch dashboard stats');

                const data = await response.json();
                if (data.success && data.data.overview) {
                    updateStatsBoxes(data.data.overview);
                }
            } catch (error) {
                console.error('Error loading dashboard stats:', error);
            }
        }

        // Update stats boxes with real data
        function updateStatsBoxes(overview) {
            const statsContainer = document.querySelector('.stats-container');

            const stats = [
                {
                    title: 'Total Students',
                    value: overview.total_students || 0,
                    change: '+5.4%'
                },
                {
                    title: 'Total Teachers',
                    value: overview.total_instructors || 0,
                    change: '+5.4%'
                },
                {
                    title: 'Active Courses',
                    value: overview.published_courses || 0,
                    change: '+5.4%'
                },
                {
                    title: 'Total Enrollments',
                    value: overview.total_enrollments || 0,
                    change: '+5.4%'
                }
            ];

            statsContainer.innerHTML = stats.map(stat => `
                <div class="d-flex flex-column gap-4 stats-box">
                    <h4 class="stats-box-title">${stat.title}</h4>
                    <p class="stats-box-percentage-large">${stat.value.toLocaleString()}</p>
                    <p class="stats-box-percentage-small">↗️ ${stat.change}</p>
                </div>
            `).join('');
        }

        // Fetch engagement analytics
        async function loadEngagementAnalytics() {
            try {
                const response = await fetch(`${apiBaseUrl}/analytics/engagement`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch engagement analytics');

                const data = await response.json();
                if (data.success && data.data) {
                    initializeEngagementChart(data.data);
                }
            } catch (error) {
                console.error('Error loading engagement analytics:', error);
                // Use fallback data if API fails
                initializeEngagementChartWithFallback();
            }
        }

        // Initialize engagement chart with API data
        function initializeEngagementChart(analyticsData) {
            const ctxEngagement = document.getElementById('engagementChart').getContext('2d');

            const gradient = ctxEngagement.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, '#8979FF4D');
            gradient.addColorStop(1, '#8979FF0D');

            // Extract data from analytics response
            const chartData = analyticsData.temporal_patterns?.daily_activity || {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                data: [12, 19, 8, 15, 22, 18, 25]
            };

            engagementChart = new Chart(ctxEngagement, {
                type: 'line',
                data: {
                    labels: chartData.labels || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Engagement',
                        data: chartData.data || [12, 19, 8, 15, 22, 18, 25],
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: '#6366F1',
                        borderWidth: 2,
                        tension: 0.45,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#6366F1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 25
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: '#E5E7EB',
                                borderDash: [4, 4],
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 20,
                                color: '#6B7280',
                                padding: 10,
                            },
                            grid: {
                                color: '#E5E7EB',
                                borderDash: [4, 4],
                                drawBorder: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#111827',
                            padding: 10,
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    }
                },
                plugins: [{
                    id: 'valueLabels',
                    afterDatasetsDraw(chart) {
                        const { ctx } = chart;
                        ctx.save();
                        ctx.font = '12px Inter, sans-serif';
                        ctx.fillStyle = '#6366F1';
                        ctx.textAlign = 'center';

                        chart.data.datasets[0].data.forEach((value, index) => {
                            const point = chart.getDatasetMeta(0).data[index];
                            ctx.fillText(value, point.x, point.y - 10);
                        });

                        ctx.restore();
                    }
                }]
            });

            // Add event listeners for range buttons
            document.querySelectorAll('.chart-menu button').forEach(button => {
                button.addEventListener('click', () => {
                    const range = button.dataset.range;
                    loadEngagementDataByRange(range);
                });
            });
        }

        // Fallback engagement chart initialization
        function initializeEngagementChartWithFallback() {
            const engagementData = {
                day: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    data: [12, 19, 8, 15, 22, 18, 25]
                },
                week: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    data: [120, 150, 180, 160]
                },
                month: {
                    labels: [
                        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ],
                    data: [500, 620, 710, 680, 740, 800, 780, 820, 860, 900, 950, 1000]
                },
                year: {
                    labels: ['2021', '2022', '2023', '2024', '2025'],
                    data: [3200, 3800, 4200, 4600, 5100]
                }
            };

            const ctxEngagement = document.getElementById('engagementChart').getContext('2d');

            const gradient = ctxEngagement.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, '#8979FF4D');
            gradient.addColorStop(1, '#8979FF0D');

            engagementChart = new Chart(ctxEngagement, {
                type: 'line',
                data: {
                    labels: engagementData.day.labels,
                    datasets: [{
                        label: 'Engagement',
                        data: engagementData.day.data,
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: '#6366F1',
                        borderWidth: 2,
                        tension: 0.45,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#6366F1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 25
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: '#E5E7EB',
                                borderDash: [4, 4],
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 20,
                                color: '#6B7280',
                                padding: 10,
                            },
                            grid: {
                                color: '#E5E7EB',
                                borderDash: [4, 4],
                                drawBorder: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#111827',
                            padding: 10,
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    }
                },
                plugins: [{
                    id: 'valueLabels',
                    afterDatasetsDraw(chart) {
                        const { ctx } = chart;
                        ctx.save();
                        ctx.font = '12px Inter, sans-serif';
                        ctx.fillStyle = '#6366F1';
                        ctx.textAlign = 'center';

                        chart.data.datasets[0].data.forEach((value, index) => {
                            const point = chart.getDatasetMeta(0).data[index];
                            ctx.fillText(value, point.x, point.y - 10);
                        });

                        ctx.restore();
                    }
                }]
            });

            document.querySelectorAll('.chart-menu button').forEach(button => {
                button.addEventListener('click', () => {
                    const range = button.dataset.range;
                    engagementChart.data.labels = engagementData[range].labels;
                    engagementChart.data.datasets[0].data = engagementData[range].data;
                    engagementChart.update();
                });
            });
        }

        // Load engagement data by range
        async function loadEngagementDataByRange(range) {
            try {
                const response = await fetch(`${apiBaseUrl}/analytics/engagement`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch engagement data');

                const data = await response.json();
                if (data.success && engagementChart) {
                    // Update chart with new data based on range
                    // This would need the API to support range parameter
                    console.log('Engagement data loaded for range:', range);
                }
            } catch (error) {
                console.error('Error loading engagement data:', error);
            }
        }

        // Fetch and initialize course performance chart
        async function loadCoursePerformance() {
            try {
                const response = await fetch(`${apiBaseUrl}/analytics/course-performance`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch course performance');

                const data = await response.json();
                if (data.success && data.data) {
                    initializeCoursePerformanceChart(data.data);
                } else {
                    initializeCoursePerformanceChartWithFallback();
                }
            } catch (error) {
                console.error('Error loading course performance:', error);
                initializeCoursePerformanceChartWithFallback();
            }
        }

        // Initialize course performance chart with API data
        function initializeCoursePerformanceChart(performanceData) {
            const ctx = document.getElementById('coursePerformance').getContext('2d');

            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#000000B2';

            // Extract course names and performance data
            const courses = Array.isArray(performanceData) ? performanceData : [];
            const labels = courses.map(c => c.course?.title || c.name || 'Unknown').slice(0, 9);
            const performanceValues = courses.map(c => c.completion_rate || c.performance || 0).slice(0, 9);

            coursePerformanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length > 0 ? labels : ['English', 'Math', 'Biology', 'Com. Sc.', 'Physics', 'Chemistry', 'Futh. Math', 'Agri', 'Literature'],
                    datasets: [{
                        label: 'Performance',
                        data: performanceValues.length > 0 ? performanceValues : [35, 10, 55, 8, 30, 91, 15, 55, 70],
                        backgroundColor: '#7086FD',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 5
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grace: '20%',
                            ticks: {
                                stepSize: 20
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Fallback course performance chart
        function initializeCoursePerformanceChartWithFallback() {
            const ctx = document.getElementById('coursePerformance').getContext('2d');

            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#000000B2';

            coursePerformanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['English', 'Math', 'Biology', 'Com. Sc.', 'Physics', 'Chemistry', 'Futh. Math', 'Agri', 'Literature'],
                    datasets: [{
                        label: '2021',
                        data: [35, 10, 55, 8, 30, 91, 15, 55, 70],
                        backgroundColor: '#7086FD',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 5
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grace: '20%',
                            ticks: {
                                stepSize: 20
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        // Fetch student performance data
        let currentPage = 1;
        let totalPages = 1;
        let allStudentData = [];

        async function loadStudentPerformance(page = 1) {
            try {
                const response = await fetch(`${apiBaseUrl}/analytics/student-progress?page=${page}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch student performance');

                const data = await response.json();
                if (data.success && data.data) {
                    allStudentData = Array.isArray(data.data) ? data.data : data.data.data || [];
                    totalPages = data.data.last_page || 1;
                    currentPage = page;
                    renderStudentTable(allStudentData);
                    updatePagination();
                }
            } catch (error) {
                console.error('Error loading student performance:', error);
                // Show loading error in table
                document.getElementById('usersTableBody').innerHTML = `
                    <tr style="border-bottom: 1px solid #e8e8e8;">
                        <td colspan="6" class="text-center text-danger py-4">
                            Failed to load student data. Please try again.
                        </td>
                    </tr>
                `;
            }
        }

        // Render student performance table
        function renderStudentTable(students) {
            const tableBody = document.getElementById('usersTableBody');

            if (!students || students.length === 0) {
                tableBody.innerHTML = `
                    <tr style="border-bottom: 1px solid #e8e8e8;">
                        <td colspan="6" class="text-center text-muted py-4">
                            No student data available
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = students.map((student, index) => `
                <tr style="border-bottom: 1px solid #e8e8e8;">
                    <td style="padding: 1rem; color: #666;">${student.id || index + 1}</td>
                    <td style="padding: 1rem; color: #333; font-weight: 500;">
                        ${student.user?.first_name || student.first_name || 'N/A'} ${student.user?.last_name || student.last_name || ''}
                    </td>
                    <td style="padding: 1rem; color: #666;">
                        ${student.course?.title || student.course_name || 'N/A'}
                    </td>
                    <td style="padding: 1rem;">
                        <span style="background-color: #E8F5E9; color: #2E7D32; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">
                            ${(student.completion_rate || student.progress || 0).toFixed(1)}%
                        </span>
                    </td>
                    <td style="padding: 1rem; color: #333; font-weight: 500;">
                        ${(student.average_score || student.score || 0).toFixed(1)}%
                    </td>
                    <td style="padding: 1rem; color: #999; font-size: 0.875rem;">
                        ${formatDate(student.last_active || student.updated_at || 'N/A')}
                    </td>
                </tr>
            `).join('');
        }

        // Format date helper
        function formatDate(dateString) {
            if (!dateString || dateString === 'N/A') return 'N/A';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } catch (e) {
                return 'N/A';
            }
        }

        // Update pagination controls
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
            let pageHTML = '';
            const maxPages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
            let endPage = Math.min(totalPages, startPage + maxPages - 1);

            if (endPage - startPage < maxPages - 1) {
                startPage = Math.max(1, endPage - maxPages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                pageHTML += `
                    <button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-light'}"
                            style="border: 1px solid #ddd; padding: 0.375rem 0.75rem; border-radius: 0.25rem;"
                            onclick="loadStudentPerformance(${i})">
                        ${i}
                    </button>
                `;
            }

            pageNumbers.innerHTML = pageHTML;

            prevBtn.onclick = () => {
                if (currentPage > 1) loadStudentPerformance(currentPage - 1);
            };

            nextBtn.onclick = () => {
                if (currentPage < totalPages) loadStudentPerformance(currentPage + 1);
            };
        }

        // Search and filter functionality
        document.getElementById('searchInput').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const filtered = allStudentData.filter(student => {
                const name = `${student.user?.first_name || ''} ${student.user?.last_name || ''}`.toLowerCase();
                const email = (student.user?.email || '').toLowerCase();
                return name.includes(searchTerm) || email.includes(searchTerm);
            });
            renderStudentTable(filtered);
        });

        document.getElementById('filterSelect').addEventListener('change', (e) => {
            const filterValue = e.target.value;
            if (!filterValue) {
                renderStudentTable(allStudentData);
                return;
            }

            const filtered = allStudentData.filter(student => {
                if (filterValue === 'course') return student.course_id;
                if (filterValue === 'category') return student.category_id;
                if (filterValue === 'role-student') return student.user?.role === 'student';
                if (filterValue === 'role-instructor') return student.user?.role === 'instructor';
                if (filterValue === 'role-admin') return student.user?.role === 'admin';
                return true;
            });
            renderStudentTable(filtered);
        });

        // Initialize page on load
        document.addEventListener('DOMContentLoaded', () => {
            loadDashboardStats();
            loadEngagementAnalytics();
            loadCoursePerformance();
            loadStudentPerformance(1);
        });
    </script>
@endsection
