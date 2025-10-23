@extends('admin.usertemplate')

@section('content')
<main>
<div class="container py-4" style="max-width: 1200px;">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Your Results</h4>
        <p class="text-muted">Well done! Here's how you performed.</p>
    </div>

    <div class="row g-4">

        <div class="col-12 col-lg-7">

            <div class="section-card">
                <div class="d-flex align-items-center justify-content-start flex-wrap">

                    <div class="chart-container-wrapper me-5 mb-3 mb-md-0">
                        <canvas id="overallScoreChart"></canvas>
                        <div class="chart-center-text">82%</div>
                    </div>

                    <div class="text-start">
                        <strong class="fs-1 lh-1">41/100</strong> <br>
                        <span class="text-muted">Points</span>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <span class="fw-bold">Math</span>
                        <span class="text-muted small">8/10</span>
                    </div>
                    <div class="progress custom-progress">
                        <div class="progress-bar" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <span class="fw-bold">Science</span>
                        <span class="text-muted small">9/10</span>
                    </div>
                    <div class="progress custom-progress">
                        <div class="progress-bar" role="progressbar" style="width: 90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <span class="fw-bold">History</span>
                        <span class="text-muted small">6/10</span>
                    </div>
                    <div class="progress custom-progress">
                        <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <span class="fw-bold">English</span>
                        <span class="text-muted small">7/10</span>
                    </div>
                    <div class="progress custom-progress">
                        <div class="progress-bar" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <span class="fw-bold">Art</span>
                        <span class="text-muted small">10/10</span>
                    </div>
                    <div class="progress custom-progress">
                        <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-12 col-lg-5">

            <div class="section-card" style="height: 70%;">
                <h6 class="fw-bold mb-1">Comparison & Analytics</h6>
                <p class="text-muted small mb-4">You are in the top 15% of your class</p>

                <div class="text-center mb-4">
                    <div style="width: 120px; height: 120px;" class="mx-auto mb-3">
                        <canvas id="candidatesChart"></canvas>
                    </div>
                    <div class="d-flex justify-content-center gap-4">
                        <div class="d-flex align-items-center small">
                            <span class="d-inline-block me-2" style="width: 12px; height: 12px; background-color: var(--bs-progress-green);"></span>
                            Score / Class
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-around text-center mt-4">
                    <div>
                        <strong class="fs-4 d-block">82%</strong>
                        <small class="text-muted">Your Score</small>
                    </div>
                    <div>
                        <strong class="fs-4 d-block">65%</strong>
                        <small class="text-muted">Class Average</small>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Define custom colors from CSS variables
    const darkTeal = getComputedStyle(document.documentElement).getPropertyValue('--bs-dark-teal').trim();
    const lightGray = getComputedStyle(document.documentElement).getPropertyValue('--bs-light-gray').trim();
    const progressGreen = getComputedStyle(document.documentElement).getPropertyValue('--bs-progress-green').trim();

    // 1. Overall Score Chart (82%)
    const overallScoreCtx = document.getElementById('overallScoreChart').getContext('2d');
    new Chart(overallScoreCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [82, 18], // 82% completed, 18% remaining
                backgroundColor: [darkTeal, lightGray],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%', // Controls the thickness of the ring
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });

    // 2. Candidates Chart (65% for class average visualization)
    const candidatesCtx = document.getElementById('candidatesChart').getContext('2d');
    new Chart(candidatesCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [65, 35], // 65% completed, 35% remaining
                backgroundColor: [progressGreen, lightGray],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });
</script>
</main>
@endsection
