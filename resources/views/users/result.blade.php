@extends('layouts.usertemplate')
@section('content')
    <style>
        main {
            font-family: "Fredoka", sans-serif;
            overflow-x: hidden;
        }

        p {
            margin-bottom: 0;
        }

        .header-banner {
            background-color: #FDAF22;
            height: 200px;
            width: 100%;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            position: relative;
        }

        .banner-img {
            position: absolute;
            right: -60px;
            bottom: -90px;
            width: 400px;
            height: auto;
            display: block;
            z-index: 30;
        }

        .result-container {
            border: 1px solid #004A53;
            border-radius: 20px;
            padding: 20px 20px;
            background-color: #FFFFFF;
            gap: 20px;
            max-width: 523px;
            margin-top: 100px;
        }

        .result-div {
            border: 28px solid #004A53;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            font-size: 36px;
            color: #004A53;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .result-point-title {
            color: #004A53;
            font-size: 36px;
            font-weight: 600;
        }

        .result-progress-bar {
            background-color: #78787833;
            width: 100%;
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
        }

        .result-progress-bar-track {
            background-color: #004A53;
            height: 6px;
            border-radius: 3px;
            display: block;
            transition: width 0.3s ease;
        }

        .result-items-text {
            color: #1C1D1D;
            font-size: 20px;
        }

        .result-items-container {
            display: grid;
            row-gap: 20px;
            column-gap: 20px;
            grid-template-columns: repeat(2, 1fr);
        }

        @media screen and (max-width: 768px) {
            .result-items-container {
                grid-template-columns: 1fr;
            }
        }

        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 400px;
        }

        .error-message {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin: 20px;
        }

        .success-badge {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 10px;
        }

        .failed-badge {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 10px;
        }

        .quiz-title {
            color: #004A53;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .attempt-info {
            color: #7F8285;
            font-size: 14px;
            margin-top: 10px;
        }

        @media screen and (min-width:500px) {
            .banner-img {
                width: 600px;
                bottom: -120px;
                right: -80px;
            }
        }

        @media screen and (min-width:1000px) {
            .result-container {
                margin-top: -140px;
                z-index: 1000;
            }
        }


    </style>

    <main>
        <section class="container-fluid header-banner d-flex align-items-center">
            <h3>Result & Score</h3>
            <div><img src="{{ asset('images/result-score-img.png') }}" alt="Result Score" class="banner-img" /></div>
        </section>

        <!-- Loading State -->
        <div id="loadingState" class="loading-spinner">
            <p style="text-align: center; padding: 40px; color: #004A53; font-weight: 500;">Loading results...</p>
        </div>

        <!-- Error State -->
        <div id="errorState" class="d-none" style="margin-top:60px;">
            <div class="error-message" id="errorMessage"></div>
        </div>

        <!-- Results Content -->
        <div id="resultsContent" class="d-none">
            <!-- Overall Score Container (overlapping banner on left) -->
            <div class="container-fluid" style="position: relative; z-index: 10; margin-top: -100px; padding-left: 20px;">
                <div class="d-flex align-items-center result-container">
                    <div class="d-flex justify-content-center align-items-center result-div">
                        <span id="percentageScore">0%</span>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="result-point-title"><span id="scorePoints">0</span>/<span id="maxPoints">0</span></p>
                        <span style="color: #1C1D1D; font-size: 20px;">Points</span>
                        <div id="passStatus"></div>
                        <p class="attempt-info" id="attemptInfo"></p>
                    </div>
                </div>
            </div>

            <!-- Course Results Container -->
            <section class="container-fluid pb-5 d-flex flex-column gap-2">
                <!-- Quiz Title -->
                <div class="px-3 mt-5">
                    <h2 class="quiz-title" id="quizTitle">Loading...</h2>
                </div>

                <!-- Course Results Grid -->
                <div class="result-items-container" id="questionsContainer">
                    <!-- Course results will be dynamically loaded here -->
                </div>
            </section>
        </div>
    </main>

    <script>
        let quizId = null;
        let allQuizResults = [];

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', async () => {
            // Get quiz ID from URL query parameter (try multiple parameter names)
            const urlParams = new URLSearchParams(window.location.search);
            quizId = urlParams.get('quiz_id') || urlParams.get('quizId') || urlParams.get('id');

            console.log('URL:', window.location.href);
            console.log('Query params:', Object.fromEntries(urlParams));
            console.log('Quiz ID:', quizId);

            // Load all quiz results for the user
            await loadAllQuizResults();
        });

        /**
         * Load all quiz results for the user across all courses
         */
        async function loadAllQuizResults() {
            try {
                // If a specific quiz ID is provided, load that quiz's results
                if (quizId) {
                    const response = await window.QuizApiClient.getQuizResults(quizId);
                    if (response.success && response.data) {
                        allQuizResults = [response.data];
                        displayResults(allQuizResults);
                    } else {
                        showError(response.message || 'Failed to load quiz results');
                    }
                } else {
                    // Fetch all quiz results directly from the API
                    try {
                        console.log('Starting to load all quiz results...');
                        const token = localStorage.getItem('auth_token');
                        const response = await fetch('/api/users/all-quiz-results', {
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();
                        console.log('All Quiz Results Response:', data);

                        if (data.success && data.data && Array.isArray(data.data)) {
                            const quizResults = data.data;
                            console.log('Quiz Results:', quizResults);

                            if (quizResults.length > 0) {
                                displayResults(quizResults);
                            } else {
                                showError('No quiz results found. Please complete some quizzes first.');
                            }
                        } else {
                            showError('Failed to load quiz results');
                        }
                    } catch (error) {
                        console.error('Error loading quiz results:', error);
                        showError('Error loading quiz results. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error loading quiz results:', error);
                showError('Error loading quiz results. Please try again.');
            }
        }

        /**
         * Display quiz results grouped by course
         */
        function displayResults(courseResultsArray) {
            if (!courseResultsArray || courseResultsArray.length === 0) {
                showError('No results found.');
                return;
            }

            // Calculate overall statistics across all courses
            let totalScore = 0;
            let totalMaxScore = 0;
            let totalCourses = 0;
            let passedCourses = 0;

            courseResultsArray.forEach(courseData => {
                totalScore += courseData.total_points_earned || 0;
                totalMaxScore += courseData.total_points_possible || 0;
                totalCourses++;
                if (courseData.passed) {
                    passedCourses++;
                }
            });

            // Calculate overall percentage
            const overallPercentage = totalMaxScore > 0 ? (totalScore / totalMaxScore) * 100 : 0;

            // Update overall score display
            document.getElementById('quizTitle').textContent = 'Overall Quiz Results';
            document.getElementById('percentageScore').textContent = `${Math.round(overallPercentage)}%`;
            document.getElementById('scorePoints').textContent = Math.round(totalScore);
            document.getElementById('maxPoints').textContent = Math.round(totalMaxScore);

            // Update pass status
            const passStatus = document.getElementById('passStatus');
            if (overallPercentage >= 70) {
                passStatus.innerHTML = '<div class="success-badge">✓ Overall Passed</div>';
            } else {
                passStatus.innerHTML = '<div class="failed-badge">✗ Needs Improvement</div>';
            }

            // Update attempt info
            const attemptInfo = document.getElementById('attemptInfo');
            attemptInfo.textContent = `${totalCourses} Course${totalCourses !== 1 ? 's' : ''} • ${passedCourses} Passed`;

            // Display individual course results
            displayIndividualCourseResults(courseResultsArray);

            // Hide loading and show results
            document.getElementById('loadingState').classList.add('d-none');
            document.getElementById('resultsContent').classList.remove('d-none');
        }

        /**
         * Display individual course results with aggregated quiz scores
         */
        function displayIndividualCourseResults(courseResultsArray) {
            const questionsContainer = document.getElementById('questionsContainer');
            questionsContainer.innerHTML = '';

            courseResultsArray.forEach((courseData, index) => {
                const { course, total_points_earned, total_points_possible, percentage, passed, quizzes } = courseData;

                const courseDiv = document.createElement('div');
                courseDiv.className = 'd-flex flex-column gap-2 p-3 border rounded';
                courseDiv.style.borderColor = '#004A53';

                const courseTitle = course ? course.title : 'Unknown Course';
                const quizCount = quizzes ? quizzes.length : 0;

                courseDiv.innerHTML = `
                    <div class="d-flex flex-column gap-1 mb-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="result-items-text mb-0">${courseTitle}</h3>
                            <span class="badge ${passed ? 'bg-success' : 'bg-danger'}">
                                ${passed ? '✓ Passed' : '✗ Failed'}
                            </span>
                        </div>
                        <small class="text-muted">${quizCount} Quiz${quizCount !== 1 ? 'zes' : ''}</small>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted">Total Score</span>
                        <p class="result-items-text">${total_points_earned}/${total_points_possible}</p>
                    </div>
                    <div class="result-progress-bar">
                        <span class="result-progress-bar-track" style="width: ${percentage}%"></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted">Percentage</span>
                        <p class="result-items-text">${Math.round(percentage)}%</p>
                    </div>
                `;

                questionsContainer.appendChild(courseDiv);
            });
        }

        /**
         * Show error message
         */
        function showError(message) {
            document.getElementById('loadingState').classList.add('d-none');
            document.getElementById('errorState').classList.remove('d-none');
            document.getElementById('errorMessage').textContent = message;
        }

        // Make functions globally accessible if needed
        window.loadAllQuizResults = loadAllQuizResults;
    </script>
@endsection
