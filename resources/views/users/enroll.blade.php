@extends('layouts.usertemplate')

@section('content')
<style>
    /* Global font & background */
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: #ffffff;
        color: #222;
        padding: 28px 12px;
    }
    .enroll-btn{
        background-color: #FDAF22;
        padding: 16px 20px;
        color: #000F11;
        font-size: 16px;
        font-weight: 600;
        border-radius: 4px;
    }

    /* Outer card/container */
    .txn-card {
        max-width: 1120px;
        /* wide like screenshot */
        margin: 0 auto;
        border-radius: 6px;
        border: 1px solid #e9e9e9;
        /* subtle border */
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.02);
    }

    /* Header row */
    .txn-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 22px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #333;
        background: #fff;
    }

    /* small right controls */
    .txn-controls .form-select {
        min-height: calc(1.5em + .75rem + 2px);
        padding-top: .25rem;
        padding-bottom: .25rem;
        font-size: 13px;
        border-radius: 6px;
        /* border: 1px solid #e6e6e6;
      background: #f8f9fa; */
    }

    .txn-controls .btn-filter {
        padding: .25rem .5rem;
        font-size: 13px;
    }

    /* List area */
    .txn-list {
        min-height: 420px;
        /* tall list like screenshot */
        background: #fff;
    }

    .txn-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 22px;
        border-bottom: 1px solid #f2f2f2;
        font-size: 14px;
    }

    /* Left column contains checkbox + label */
    .txn-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1 1 auto;
        white-space: nowrap;
        overflow: hidden;
    }

    /* Make checkbox small and vertically centered */
    .txn-left .form-check-input {
        width: 16px;
        height: 16px;
        margin-top: 0;
    }

    /* Subject text */
    .subject {
        font-size: 14px;
        color: #222;
        /* dark text similar to screenshot */
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Price on the right */
    .txn-price {
        min-width: 110px;
        text-align: right;
        font-weight: 600;
        color: #0b0b0b;
        font-size: 14px;
    }

    /* Last row (no border) */
    .txn-row:last-child {
        border-bottom: none;
    }

    /* Payment footer */
    .txn-footer {
        padding: 18px 22px;
        border-top: 1px solid #eee;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .proceed-payment-btn{
        border: 1px solid #004A53;
        border-radius: 4px;
        padding: 16px 20px;
        color: #004A53;
        font-size: 16px;
        font-weight: 600;
        background-color: transparent;
    }



    /* Subtotal text style inside button */
    .subtotal {
        font-weight: 700;
        margin-left: 8px;
    }

    /* Small screens — stack price under label */
    @media (max-width: 576px) {
        .txn-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .txn-price {
            text-align: left;
            margin-top: 8px;
        }

        .txn-header {
            padding: 12px 14px;
        }

        .txn-row {
            padding: 12px 14px;
        }

        .txn-footer {
            padding: 16px 14px;
        }
    }

    /* Back button styling */
    .back-btn {
        background: none;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #004A53;
        font-size: 24px;
        transition: opacity 0.2s ease;
        margin-right: 12px;
    }

    .back-btn:hover {
        opacity: 0.7;
    }

    .back-btn:active {
        opacity: 0.5;
    }

    .header-with-back {
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
@section('content')
    <main>
        <section class="container-fluid p-4 d-flex flex-column gap-4">
            <div class ="d-flex  justify-content-between align-items-center">
                <div class="header-with-back">
                    <button class="back-btn" type="button" id="backBtn" title="Go back">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h1 id="levelTitle">Loading...</h1>
                </div>
                <button class = "enroll-btn" type = "button" id="enrollAllBtn">Enroll in All Subjects - ₦0.00</button>
            </div>
            <div class="txn-card w-100">

                <!-- Header -->
                <div class="txn-header ">
                    <div class="header-title">Transaction History</div>

                    <div class="txn-controls d-flex align-items-center gap-2">
                        <!-- small dropdowns on the right like the screenshot -->
                        <select class="form-select form-select-sm" aria-label="Category select" style="width:165px;">
                            <option selected>All Categories</option>
                            <option>Science</option>
                            <option>Arts</option>
                        </select>

                        <select class="form-select form-select-sm" aria-label="Status select" style="width:140px;">
                            <option selected>All Status</option>
                            <option>Selected</option>
                            <option>Not selected</option>
                        </select>
                    </div>
                </div>

                <!-- List -->
                <div class="txn-list" id="coursesList">
                    <!-- Courses will be loaded here dynamically -->
                    <div class="txn-row">
                        <div class="txn-left">
                            <p class="text-muted">Loading courses...</p>
                        </div>
                    </div>
                </div>




            </div>
            <!-- Footer with proceed button -->

                    <button id="proceedBtn" class="proceed-payment-btn align-self-center">
                        Proceed to Payment - Subtotal: <span id="subtotal" class="subtotal">₦0.00</span>
                    </button>

        </section>
    </main>

    <!-- JS: bootstrap + API integration -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module">
        import CourseApiClient from '{{ asset("js/api/courseApiClient.js") }}';

        let allCourses = [];

        document.addEventListener("DOMContentLoaded", async () => {
            await loadCourses();
            setupBackButton();
        });

        /**
         * Setup back button functionality
         */
        function setupBackButton() {
            const backBtn = document.getElementById('backBtn');
            if (backBtn) {
                backBtn.addEventListener('click', () => {
                    window.history.back();
                });
            }
        }

        /**
         * Format numbers as NGN currency
         */
        function formatNGN(n) {
            n = Number(n) || 0;
            return '₦' + n.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        /**
         * Get level ID from URL query parameter
         */
        function getLevelIdFromURL() {
            const params = new URLSearchParams(window.location.search);
            const levelId = params.get('level_id');
            console.log('URL:', window.location.href);
            console.log('Search params:', window.location.search);
            console.log('Extracted level_id:', levelId);
            return levelId;
        }

        /**
         * Load courses by level ID from API
         */
        async function loadCourses() {
            try {
                const levelId = getLevelIdFromURL();
                if (!levelId) {
                    showError('No level selected. Please go back and select a class.');
                    return;
                }

                console.log('Loading courses for level_id:', levelId);
                console.log('API call parameters:', { level_id: levelId, per_page: 50 });

                // Fetch courses for this level
                const result = await CourseApiClient.getCourses({ level_id: levelId, per_page: 50 });

                console.log('API Response:', result);
                console.log('API Response data.courses.data:', result.data?.courses?.data);

                // Handle API response structure - courses are in result.data.courses.data
                let courses = [];
                if (result.success && result.data) {
                    if (result.data.courses && result.data.courses.data) {
                        // API returns paginated response
                        courses = result.data.courses.data;
                    } else if (Array.isArray(result.data)) {
                        // API returns direct array
                        courses = result.data;
                    }
                }

                if (courses && courses.length > 0) {
                    allCourses = courses;
                    displayCourses(allCourses);
                    updateLevelTitle(levelId);
                    updateEnrollAllButton();
                } else {
                    console.log('No courses found. Response data:', result.data);
                    showError('No courses available for this class.');
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                showError('Failed to load courses. Please try again later.');
            }
        }

        /**
         * Update the level title in the header
         */
        async function updateLevelTitle(levelId) {
            try {
                const result = await CourseApiClient.getLevels();
                if (result.success && result.data) {
                    const level = result.data.find(l => l.id == levelId);
                    if (level) {
                        document.getElementById('levelTitle').textContent = level.name;
                    }
                }
            } catch (error) {
                console.error('Error loading level:', error);
            }
        }

        /**
         * Display courses as checkboxes
         */
        function displayCourses(courses) {
            const coursesList = document.getElementById('coursesList');

            const coursesHtml = courses.map((course, index) => {
                // Check if course is free
                const isFree = course.free === true || course.free === 1 || course.price === 0 || course.price === '0';
                const priceDisplay = isFree ? 'Free Course' : formatNGN(course.price || 0);

                return `
                    <div class="txn-row">
                        <div class="txn-left">
                            <input class="form-check-input check-subject" type="checkbox"
                                   data-price="${course.price || 0}"
                                   data-course-id="${course.id}"
                                   id="cb${index}">
                            <label for="cb${index}" class="subject">${course.title}</label>
                        </div>
                        <div class="txn-price">${priceDisplay}</div>
                    </div>
                `;
            }).join('');

            coursesList.innerHTML = coursesHtml;
            attachCheckboxListeners();
        }

        /**
         * Attach listeners to checkboxes
         */
        function attachCheckboxListeners() {
            const checkboxes = document.querySelectorAll('.check-subject');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateSubtotal);
            });
        }

        /**
         * Calculate and update subtotal
         */
        function updateSubtotal() {
            const checks = document.querySelectorAll('.check-subject:checked');
            let total = 0;
            checks.forEach(cb => {
                const p = Number(cb.dataset.price) || 0;
                total += p;
            });
            document.getElementById('subtotal').textContent = formatNGN(total);
            updateEnrollAllButton();
        }

        /**
         * Update "Enroll in All" button text with 10% discount
         */
        function updateEnrollAllButton() {
            // Calculate total price of all courses
            const totalPrice = allCourses.reduce((sum, course) => sum + (Number(course.price) || 0), 0);

            // Apply 10% discount
            const discountAmount = totalPrice * 0.10;
            const discountedPrice = totalPrice - discountAmount;

            const courseCount = allCourses.length;
            document.getElementById('enrollAllBtn').textContent =
                `Enroll in All ${courseCount} Subjects - ${formatNGN(discountedPrice)}`;
        }

        /**
         * Show error message
         */
        function showError(message) {
            const coursesList = document.getElementById('coursesList');
            coursesList.innerHTML = `<div class="txn-row"><div class="txn-left"><p class="text-danger">${message}</p></div></div>`;
        }

        /**
         * Handle proceed to payment button
         */
        document.getElementById('proceedBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const checked = document.querySelectorAll('.check-subject:checked');
            if (checked.length === 0) {
                alert('Please select at least one subject to proceed.');
            } else {
                const selectedCourses = Array.from(checked).map(cb => cb.dataset.courseId);
                const subtotal = document.getElementById('subtotal').textContent;
                alert(`Proceeding with ${checked.length} subject(s). Subtotal: ${subtotal}`);
                // TODO: Redirect to payment page with selected courses
            }
        });

        /**
         * Handle "Enroll in All" button
         */
        document.getElementById('enrollAllBtn').addEventListener('click', function(e) {
            e.preventDefault();
            // Select all checkboxes
            document.querySelectorAll('.check-subject').forEach(cb => {
                cb.checked = true;
            });
            updateSubtotal();
        });
    </script>
@endsection
