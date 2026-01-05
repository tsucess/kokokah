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
                <div class="d-flex gap-3 align-items-center">
                    <a href="http://" class="btn-yellow-action d-flex gap-1 align-items-center"
                        style="text-decoration: none;"><i class="fa-solid fa-plus"></i>Add Category</a>
                    <a href="http://" class="btn-secondary-action d-flex gap-1 align-items-center"
                        style="text-decoration: none;"><i class="fa-solid fa-plus"></i> Add Term</a>
                </div>
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
        //engagement chart

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


        const engagementChart = new Chart(ctxEngagement, {
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

            /* Value labels on points */
            plugins: [{
                id: 'valueLabels',
                afterDatasetsDraw(chart) {
                    const {
                        ctx
                    } = chart;
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


        // course performance
        const ctx = document
            .getElementById('coursePerformance')
            .getContext('2d');

        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#000000B2';


        let coursePerformanceChart = new Chart(ctx, {
            type: 'bar',

            data: {
                labels: ['English', 'Math', 'Biology', 'Com. Sc.', 'Physics', 'Chemistry', 'Futh. Math', 'Agri',
                    'Literature'
                ],
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
        })
    </script>
@endsection
