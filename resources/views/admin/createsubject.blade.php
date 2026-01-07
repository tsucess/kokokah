@extends('layouts.dashboardtemp')

@section('content')
    <style>
        /* ===== Header Styles ===== */
        .subject-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: .4rem;
        }

        .subject-header h3 {
            font-size: 2rem;
            color: #004A53;
            font-family: 'Fredoka One', sans-serif;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .subject-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .header-buttons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-draft {
            background-color: white;
            border: 1px solid #004A53;
            color: #004A53;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-draft:hover {
            background-color: #f0f8f9;
        }

        .btn-publish {
            background-color: #FDAF22;
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-publish:hover {
            background-color: #e59a0f;
        }

        /* ===== Navigation Buttons ===== */
        .nav-buttons-container {
            display: flex;
            gap: 1rem;
            margin-bottom: .2rem;
            flex-wrap: wrap;
        }

        .coursebtn {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            min-width: 200px;
            padding: 1rem;
            background-color: white;
            border: 2px solid #e0e0e0;
            color: #004A53;
            font-weight: 500;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .coursebtn:hover {
            border-color: #004A53;
            background-color: #f9f9f9;
        }

        .coursebtn.course-btn-active {
            background-color: #004A53;
            color: white;
            border-color: #004A53;
        }

        /* ===== Section Styles ===== */
        .section-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .section-header h5 {
            font-size: 1.25rem;
            color: #004A53;
            font-weight: 600;
            margin: 0;
        }

        /* ===== Form Styles ===== */
        .form-row-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group-custom {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group-custom label {
            font-weight: 500;
            color: #333;
            font-size: 0.95rem;
        }

        .form-group-custom input,
        .form-group-custom select {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }

        .form-group-custom input:focus,
        .form-group-custom select:focus {
            outline: none;
            border-color: #004A53;
            box-shadow: 0 0 0 3px rgba(0, 74, 83, 0.1);
        }

        .form-group-custom input::placeholder {
            color: #999;
        }

        /* ===== Description Editor ===== */
        .description-section {
            margin-top: 2rem;
        }

        .description-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .editor-toolbar {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            padding: 0.75rem;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-bottom: none;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .editor-toolbar span {
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
            padding: 0.25rem 0.5rem;
        }

        .editor-toolbar span:hover {
            color: #004A53;
        }

        .description-textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0 0 0.375rem 0.375rem;
            font-size: 0.95rem;
            font-family: inherit;
            resize: vertical;
            min-height: 150px;
        }

        .description-textarea:focus {
            outline: none;
            border-color: #004A53;
            box-shadow: 0 0 0 3px rgba(0, 74, 83, 0.1);
        }

        /* ===== Button Styles ===== */
        .button-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn-continue {
            background-color: #FDAF22;
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.75rem 2rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-continue:hover {
            background-color: #e59a0f;
        }

        .btn-back {
            background-color: white;
            border: 1px solid #004A53;
            color: #004A53;
            font-weight: 500;
            padding: 0.75rem 2rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #f0f8f9;
        }

        .accordion-button::before {
            display: none;
        }

        /* ===== Mobile Responsiveness ===== */
        @media (max-width: 768px) {
            .subject-header {
                flex-direction: column;
                gap: 1.5rem;
            }

            .header-buttons {
                width: 100%;
                flex-direction: column;
            }

            .btn-draft,
            .btn-publish {
                width: 100%;
            }

            .nav-buttons-container {
                flex-direction: column;
            }

            .coursebtn {
                min-width: auto;
                width: 100%;
            }

            .form-row-two {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column;
            }

            .btn-continue,
            .btn-back {
                width: 100%;
            }
        }

        .small-check {
            width: 0.8rem;
            height: 0.8rem;
            transform: scale(0.8);
            margin: 0;
            /* optional: keeps alignment clean */
        }
    </style>

    <main>
        <!-- Header Section -->
        <div class="container bg-white">
            <div class="subject-header">
                <div>
                    <h1>Create New Course</h1>
                    <p>Here overview of your</p>
                </div>

                {{-- <div class="header-buttons">
                    <button type="button" class="btn btn-draft" id="saveDraftBtn">
                        Save As Draft
                    </button>

                    <button type="button" class="btn btn-publish" id="finalPublishBtn">
                        Publish Course
                    </button>
                </div> --}}
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="container bg-white">
            <div class="nav-buttons-container">
                <button type="button" class="coursebtn" data-section="details">
                    <i class="fa-solid fa-circle fa-2xs"></i>
                    Create New Subject
                    <i class="fa fa-arrow-right"></i>
                </button>

                <button type="button" class="coursebtn" data-section="media">
                    <i class="fa-solid fa-circle fa-2xs"></i>
                    Subject Media
                    <i class="fa fa-arrow-right"></i>
                </button>

                {{-- <button type="button" class="coursebtn" data-section="curriculum">
                    <i class="fa-solid fa-circle fa-2xs"></i>
                    Curriculum
                    <i class="fa fa-arrow-right"></i>
                </button> --}}

                <button type="button" class="coursebtn" data-section="publish">
                    <i class="fa-solid fa-circle fa-2xs"></i>
                    Additional Information
                    <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Course Details Section -->
        <div class="container bg-white content-section" id="details">
            <div class="section-header">
                <h5>Course Details</h5>
            </div>

            <form id="courseDetailsForm">
                @csrf

                <input type="hidden" class="form-control" id="curriculumCategoryId" name="curriculumCategoryId" required>
                <div class="form-row-two">
                    <div class="form-group-custom">
                        <label for="courseTitle">Course Title</label>
                        <input type="text" class="form-control" id="courseTitle" name="courseTitle"
                            placeholder="Enter Subject Title" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="subjectTerm">Term</label>
                        <select class="form-control" id="subjectTerm" name="subjectTerm" required></select>
                    </div>

                </div>

                <div class="form-row-two">
                    <div class="form-group-custom">
                        <label for="courseCategory">Course Category</label>
                        <select class="form-control" id="courseCategory" name="courseCategory" required>

                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label for="courseLevel">Course Level</label>
                        <select class="form-control" id="courseLevel" name="courseLevel" required></select>
                    </div>
                </div>

                <div class="form-row-two">
                    <div class="form-group-custom">
                        <label for="courseTime">Duration</label>
                        <input type="text" class="form-control" id="courseTime" name="courseTime"
                            placeholder="e.g., 2 hours" required>
                    </div>

                    <div class="form-group-custom">
                        <div class="d-flex align-items-center gap-2">
                            <label for="coursePrice">Price</label>
                            <div class="form-check d-flex gap-1 align-items-center ">
                                <input class="form-check-input small-check" type="checkbox" value="" id="free-course">
                                <label class="form-check-label" for="checkChecked">
                                    Free Course
                                </label>
                            </div>
                        </div>

                        <input type="number" class="form-control" id="coursePrice" name="coursePrice"
                            placeholder="e.g., 200" min="1" required>
                    </div>
                </div>

                <div class="description-section">
                    <p class="description-label">Course Description</p>

                    {{-- <div class="editor-toolbar">
                        <span title="Bold"><i class="fa-solid fa-bold"></i></span>
                        <span title="Italic"><i class="fa-solid fa-italic"></i></span>
                        <span title="Underline"><i class="fa-solid fa-underline"></i></span>
                        <span title="Strikethrough"><i class="fa-solid fa-strikethrough"></i></span>
                        <span title="Upload"><i class="fa-solid fa-file-arrow-up"></i></span>
                    </div>

                    <textarea class="description-textarea" id="courseDescription" name="courseDescription"
                        placeholder="Write subject description here..." form="courseDetailsForm"></textarea> --}}
                    <div id="courseDescription" class="ql-editor"></div>
                </div>
            </form>

            <div class="button-group">
                <button type="button" class="btn btn-continue continue-btn" data-next="media">
                    Continue
                </button>
            </div>
        </div>

        <!-- Media Section -->
        <div class="container bg-white d-none content-section" id="media">
            <div class="section-header">
                <h5>Course Media</h5>
            </div>

            <form id="mediaUploadForm">
                @csrf

                <div class="form-group-custom mb-3">
                    <label>Overview Video URL</label>
                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                        <input type="text" class="form-control" placeholder="https://preview.youtube.com"
                            style="flex: 1;">
                    </div>
                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                        <p>Thumbnail: <span id="fileNameDisplay"></span></p>
                    </div>

                    <label
                        style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; border: 2px dashed #ddd; border-radius: 0.375rem; cursor: pointer; transition: all 0.3s ease; background-color: #f9f9f9;"
                        for="fileInput">
                        <i class="fa-solid fa-file-circle-check"
                            style="font-size: 2rem; color: #004A53; margin-bottom: 0.5rem;"></i>
                        <h5 style="margin: 0.5rem 0; color: #333;">Upload Image</h5>
                        <p style="margin: 0; color: #666; font-size: 0.9rem;"> PNG, JPEG, GIF (max 2MB)</p>
                    </label>
                    <input type="file" id="fileInput" name="file" class="d-none" accept="image/*,.mp4,.webm">
                </div>
            </form>

            <div class="button-group">
                <button type="button" class="btn btn-back back-btn" data-next="details">
                    Previous
                </button>
                <button type="button" class="btn btn-continue continue-btn" data-next="publish">
                    Continue
                </button>
            </div>
        </div>


        <!-- Publish Section -->
        <div class="container bg-white d-none content-section" id="publish">
            <style>
                .publish-overview {
                    background-color: #f9f9f9;
                    border: 1px solid #e0e0e0;
                    border-radius: 0.5rem;
                    padding: 2rem;
                    margin-bottom: 2rem;
                }

                .overview-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 1.5rem;
                }

                .overview-title {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .overview-title h6 {
                    font-size: 0.9rem;
                    color: #666;
                    font-weight: 500;
                    margin: 0;
                }

                .overview-title h2 {
                    font-size: 1.75rem;
                    color: #004A53;
                    font-weight: 600;
                    margin: 0;
                    font-family: 'Fredoka One', sans-serif;
                }

                .overview-meta {
                    display: flex;
                    gap: 1.5rem;
                    flex-wrap: wrap;
                    font-size: 0.9rem;
                    color: #666;
                }

                .meta-item {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .meta-item i {
                    color: #004A53;
                }

                .overview-actions {
                    display: flex;
                    gap: 0.5rem;
                    align-items: center;
                }

                .overview-actions button {
                    background: none;
                    border: none;
                    cursor: pointer;
                    color: #666;
                    padding: 0.5rem;
                    transition: color 0.3s ease;
                }

                .overview-actions button:hover {
                    color: #004A53;
                }

                .course-image {
                    width: 100%;
                    height: 250px;
                    object-fit: cover;
                    border-radius: 0.5rem;
                    margin-bottom: 2rem;
                }

                .course-description-section {
                    margin-bottom: 2rem;
                }

                .course-description-section h6 {
                    font-size: 1rem;
                    color: #004A53;
                    font-weight: 600;
                    margin-bottom: 1rem;
                }

                .course-description-section p {
                    color: #333;
                    line-height: 1.7;
                    margin-bottom: 1rem;
                }

                .key-areas-list {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }

                .key-areas-list li {
                    color: #333;
                    padding: 0.5rem 0;
                    padding-left: 1.5rem;
                    position: relative;
                    line-height: 1.6;
                }

                .key-areas-list li:before {
                    content: "•";
                    position: absolute;
                    left: 0;
                    color: #FDAF22;
                    font-weight: bold;
                    font-size: 1.2rem;
                }

                .curriculum-preview {
                    margin-top: 2rem;
                }

                .curriculum-preview h6 {
                    font-size: 1rem;
                    color: #004A53;
                    font-weight: 600;
                    margin-bottom: 1rem;
                }

                .curriculum-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 1rem;
                    border: 1px solid #e0e0e0;
                    border-radius: 0.375rem;
                    margin-bottom: 0.75rem;
                    background-color: white;
                }

                .curriculum-item-content {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    flex: 1;
                }

                .curriculum-item-icon {
                    color: #004A53;
                    font-size: 1.25rem;
                }

                .curriculum-item-text h6 {
                    font-size: 0.95rem;
                    color: #333;
                    font-weight: 600;
                    margin: 0 0 0.25rem 0;
                }

                .curriculum-item-text p {
                    font-size: 0.85rem;
                    color: #666;
                    margin: 0;
                }

                .curriculum-item-meta {
                    display: flex;
                    gap: 1rem;
                    font-size: 0.85rem;
                    color: #666;
                }

                .curriculum-item-check {
                    color: #004A53;
                    font-size: 1.25rem;
                }

                .publish-actions {
                    display: flex;
                    gap: 1rem;
                    justify-content: flex-end;
                    margin-top: 2rem;
                    flex-wrap: wrap;
                }

                @media (max-width: 768px) {
                    .publish-overview {
                        padding: 1.5rem;
                    }

                    .overview-header {
                        flex-direction: column;
                        gap: 1rem;
                    }

                    .overview-meta {
                        gap: 1rem;
                    }

                    .course-image {
                        height: 200px;
                    }

                    .publish-actions {
                        flex-direction: column;
                    }

                    .publish-actions button {
                        width: 100%;
                    }
                }
            </style>

            <div class="publish-overview">
                <div class="overview-header">
                    <div class="overview-title">
                        <h6>Subject Overview</h6>
                        <h2 id="publishSubjectTitle">English Language</h2>
                    </div>
                    {{-- <div class="overview-actions">
                        <button type="button" title="Edit"><i class="fa-solid fa-check-circle"
                                style="color: #004A53; font-size: 1.5rem;"></i></button>
                        <button type="button" title="More options"><i
                                class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div> --}}
                </div>

                <div class="overview-meta">
                    {{-- <div class="meta-item">
                        <i class="fa-solid fa-book"></i>
                        <span id="publishTopics">0 Topics</span>
                    </div> --}}
                    <div class="meta-item">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span id="publishCategory">0 Category</span>
                    </div>
                    <div class="meta-item">
                        <i class="fa-solid fa-clock"></i>
                        <span id="publishTime">0 Hours</span>
                    </div>
                    <div class="meta-item">
                        <i class="fa-solid fa-layer-group"></i>
                        <span id="publishLevel">Level</span>
                    </div>
                    <div class="meta-item">
                        <i class="fa-solid fa-money-bill"></i>
                        <span id="publishPrice">0 Price</span>
                    </div>
                </div>

                <img id="publishCourseImage" src="{{ asset('images/publish.png') }}" alt="Course Preview" class="course-image">

                <div class="course-description-section">
                    <h6>Subject Description</h6>
                    <p id="publishDescription">
                        This comprehensive course covers essential concepts and skills. Students will learn through
                        interactive lessons, practice exercises, and assessments to build a strong foundation.
                    </p>
                </div>

                <div class="publish-actions">
                    <button type="button" class="btn btn-back back-btn" data-next="media">
                        Back
                    </button>
                    <button type="button" class="btn btn-publish" id="saveNowBtn">
                        Save Now
                    </button>
                </div>
            </div>
        </div>

        <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3"></div>

    </main>

    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        const quill = new Quill('#courseDescription', {
            theme: 'snow'
        });

        // Load dropdown data
        async function loadDropdownData() {
            try {
                const token = localStorage.getItem('auth_token');

                // Load Terms
                const termsResponse = await fetch('/api/term', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const termsResult = await termsResponse.json();
                console.log('Terms API Response:', termsResult);
                if (termsResponse.ok && termsResult) {
                    const termSelect = document.getElementById('subjectTerm');
                    // Handle both array and object with data property
                    const terms = Array.isArray(termsResult) ? termsResult : (termsResult.data || []);
                    console.log('Processed terms:', terms);
                    termSelect.innerHTML = `<option value="">Select Term</option>`;
                    terms.forEach(term => {
                        const option = document.createElement('option');
                        option.value = term.id;
                        option.textContent = term.name;
                        termSelect.appendChild(option);
                    });
                    console.log('Terms loaded successfully. Total:', terms.length);
                } else {
                    console.error('Failed to load terms. Response:', termsResult);
                }

                // Load Course Categories
                const categoriesResponse = await fetch('/api/course-category', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const categoriesResult = await categoriesResponse.json();
                if (categoriesResponse.ok && categoriesResult) {
                    const categorySelect = document.getElementById('courseCategory');
                    const categories = Array.isArray(categoriesResult) ? categoriesResult : [];
                    categorySelect.innerHTML = `<option value="">Select Course Category</option>`;
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.title;
                        categorySelect.appendChild(option);
                    });
                }

                // Load Course Levels
                const levelsResponse = await fetch('/api/level', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const levelsResult = await levelsResponse.json();
                if (levelsResponse.ok && levelsResult) {
                    const levelSelect = document.getElementById('courseLevel');
                    const levels = Array.isArray(levelsResult) ? levelsResult : [];
                    levelSelect.innerHTML = `<option value="">Select Course Level</option>`;
                    levels.forEach(level => {
                        const option = document.createElement('option');
                        option.value = level.id;
                        option.textContent = level.name;
                        levelSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading dropdown data:', error);
            }
        }

        // Navigation between sections
        document.addEventListener('DOMContentLoaded', () => {
            const navButtons = document.querySelectorAll('.coursebtn');
            const sections = document.querySelectorAll('.content-section');
            const continueButtons = document.querySelectorAll('.continue-btn');
            const backButtons = document.querySelectorAll('.back-btn');

            // Initialize courseData object to track form values
            const courseData = {
                title: '',
                category: '',
                categoryName: '',
                level: '',
                levelName: '',
                duration: '',
                description: '',
                price: '',
                freeCourse: false,
                imageFile: null
            };

            // Load dropdown data
            loadDropdownData();

            function showSection(sectionId) {
                // Validate sectionId to prevent invalid selectors
                if (!sectionId || typeof sectionId !== 'string' || sectionId.includes('/')) {
                    console.warn('Invalid section ID:', sectionId);
                    return;
                }

                sections.forEach(sec => sec.classList.add('d-none'));
                const section = document.getElementById(sectionId);
                if (section) {
                    section.classList.remove('d-none');
                }

                navButtons.forEach(btn => btn.classList.remove('course-btn-active'));
                // Use a safer method to find the active button
                const activeBtn = Array.from(navButtons).find(btn => btn.getAttribute('data-section') === sectionId);
                if (activeBtn) {
                    activeBtn.classList.add('course-btn-active');
                }

                // Populate publish section when navigating to it
                if (sectionId === 'publish') {
                    populatePublishSection();
                }
            }



            // Get data from form fields
            document.getElementById('courseTitle').addEventListener('input', e => {
                courseData.title = e.target.value;
            });

            document.getElementById('courseCategory').addEventListener('change', e => {
                courseData.category = e.target.value;
                // Store the category name for display
                const selectedOption = e.target.options[e.target.selectedIndex];
                courseData.categoryName = selectedOption.textContent;
            });

            document.getElementById('courseLevel').addEventListener('change', e => {
                courseData.level = e.target.value;
                // Store the level name for display
                const selectedOption = e.target.options[e.target.selectedIndex];
                courseData.levelName = selectedOption.textContent;
            });

            document.getElementById('courseTime').addEventListener('input', e => {
                courseData.duration = e.target.value;
            });

            document.getElementById('coursePrice').addEventListener('input', e => {
                courseData.price = e.target.value;
            });

            quill.on('text-change', function () {
    courseData.description = quill.getText();
});

            document.getElementById('free-course').addEventListener('change', e => {
                const checked = e.target.checked;

                courseData.freeCourse = checked;
                const priceInput = document.getElementById("coursePrice");

                priceInput.disabled = checked;

                if (checked) {
                    priceInput.value = "";
                    courseData.price = "";
                }
            })

            // File upload
            document.getElementById('fileInput').addEventListener('change', e => {
                courseData.imageFile = e.target.files[0];

                if (courseData.imageFile) {
                    document.getElementById("fileNameDisplay").textContent = courseData.imageFile.name;
                }
            });

            function populatePublishSection() {
                // document.getElementById('overviewUrl').textContent = courseData.url;
                document.getElementById('publishSubjectTitle').textContent = courseData.title;
                document.getElementById('publishCategory').textContent = (courseData.categoryName || 'Category');
                document.getElementById('publishPrice').textContent = courseData.freeCourse ? 'Free Course' :
                    courseData.price + ' Price';
                document.getElementById('publishTime').textContent = courseData.duration + ' Hours';
                document.getElementById('publishLevel').textContent = (courseData.levelName || 'Level');
                document.getElementById('publishDescription').textContent = courseData.description;

                if (fileInput && fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        document.getElementById('publishCourseImage').src = e.target.result;
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }

            navButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const section = btn.getAttribute('data-section');
                    if (section) {
                        showSection(section);
                    }
                });
            });

            continueButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const next = btn.getAttribute('data-next');
                    if (next) {
                        showSection(next);
                    }
                });
            });

            backButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const back = btn.getAttribute('data-next');
                    if (back) {
                        showSection(back);
                    }
                });
            });

            // File upload handler
            const fileInput = document.getElementById('fileInput');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const uploadButton = document.getElementById('uploadButton');

            if (uploadButton) {
                uploadButton.addEventListener('click', () => {
                    fileInput.click();
                });
            }

            if (fileInput) {
                fileInput.addEventListener('change', (e) => {
                    if (e.target.files && e.target.files[0]) {
                        fileNameDisplay.value = e.target.files[0].name;
                    }
                });
            }

            // Helper function to generate slug from title
            function generateSlug(title) {
                return title
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            // Publish button handler
            const finalPublishBtn = document.getElementById('finalPublishBtn');
            if (finalPublishBtn) {
                finalPublishBtn.addEventListener('click', async () => {
                    const title = document.getElementById('courseTitle').value;
                    const category = document.getElementById('courseCategory').value;
                    const level = document.getElementById('courseLevel').value;
                    const description = quill.getText().trim();
                    const term = document.getElementById('subjectTerm').value;


                    if (!title || !category || !level || !description) {
                        alert('Please fill in all required fields');
                        return;
                    }

                    try {
                        // Create FormData for file upload
                        const formData = new FormData();

                        // Add required fields
                        formData.append('title', title);
                        formData.append('slug', generateSlug(title));
                        formData.append('description', description);
                        formData.append('course_category_id', category);
                        formData.append('curriculum_category_id',
                        category); // Using same category for both
                        formData.append('level_id', level);
                        formData.append('price', courseData.price || 0);
                        formData.append('free', courseData.freeCourse ? 1 : 0);
                        formData.append('url', generateSlug(title));

                        // Add optional fields
                        if (term) {
                            formData.append('term_id', term);
                        }

                        const duration = document.getElementById('courseTime').value;
                        if (duration) {
                            formData.append('duration_hours', duration);
                        }

                        // Add image if selected
                        const fileInput = document.getElementById('fileInput');
                        if (fileInput && fileInput.files && fileInput.files[0]) {
                            formData.append('thumbnail', fileInput.files[0]);
                        }

                        const result = await window.CourseApiClient.createCourse(formData);
                        if (result.success) {
                            ToastNotification.success('Success', 'Course published successfully!');
                            // Get the course ID from the response
                            const courseId = result.data?.id || result.id;
                            setTimeout(() => {
                                window.location.href = `/editsubject/${courseId}`;
                            }, 1500);
                        } else {
                            ToastNotification.error('Error', result.message || 'Failed to publish course');
                        }
                    } catch (error) {
                        console.error('Publish error:', error);
                        ToastNotification.error('Error', 'Error publishing course: ' + error.message);
                    }
                });
            }

            // Save Now button handler (from publish section)
            const saveNowBtn = document.getElementById('saveNowBtn');
            if (saveNowBtn) {
                saveNowBtn.addEventListener('click', async () => {
                    const title = document.getElementById('courseTitle').value;
                    const category = document.getElementById('courseCategory').value;
                    const level = document.getElementById('courseLevel').value;
                    const description = quill.getText().trim();
                    const term = document.getElementById('subjectTerm').value;

                    if (!title || !category || !level || !description) {
                        ToastNotification.warning('Warning', 'Please fill in all required fields');
                        return;
                    }

                    try {
                        // Create FormData for file upload
                        const formData = new FormData();
                        console.log(category)
                        // Add required fields
                        formData.append('title', title);
                        formData.append('slug', generateSlug(title));
                        formData.append('description', description);
                        formData.append('course_category_id', category);
                        formData.append('curriculum_category_id',
                        category); // Using same category for both
                        formData.append('level_id', level);
                        formData.append('price', courseData.price || 0);
                        formData.append('free', courseData.freeCourse ? 1 : 0);
                        formData.append('url', generateSlug(title));

                        // Add optional fields
                        if (term) {
                            formData.append('term_id', term);
                        }

                        const duration = document.getElementById('courseTime').value;
                        if (duration) {
                            formData.append('duration_hours', duration);
                        }

                        // Add image if selected
                        const fileInput = document.getElementById('fileInput');
                        if (fileInput && fileInput.files && fileInput.files[0]) {
                            formData.append('thumbnail', fileInput.files[0]);
                        }

                        const result = await window.CourseApiClient.createCourse(formData);
                        if (result.success) {
                            ToastNotification.success('Success', 'Course saved successfully!');
                            // Get the course ID from the response
                            const courseId = result.data?.id || result.id;
                            setTimeout(() => {
                                window.location.href = `/editsubject/${courseId}`;
                            }, 1500);
                        } else {
                            ToastNotification.error('Error', result.message || 'Failed to save course');
                        }
                    } catch (error) {
                        console.error('Save error:', error);
                        ToastNotification.error('Error', 'Error saving course: ' + error.message);
                    }
                });
            }

            // Save draft button handler
            const saveDraftBtn = document.getElementById('saveDraftBtn');
            if (saveDraftBtn) {
                saveDraftBtn.addEventListener('click', async () => {
                    const title = document.getElementById('courseTitle').value;
                    const category = document.getElementById('courseCategory').value;
                    const description = document.getElementById('courseDescription').value;
                    const term = document.getElementById('subjectTerm').value;

                    console.log(title, category, description)
                    if (!title || !category || !description) {
                        ToastNotification.warning('Warning', 'Please fill in all required fields');
                        return;
                    }

                    try {
                        // Create FormData for file upload
                        const formData = new FormData();

                        // Add required fields
                        formData.append('title', title);
                        formData.append('slug', generateSlug(title));
                        formData.append('description', description);
                        formData.append('course_category_id', category);
                        formData.append('curriculum_category_id',
                        category); // Using same category for both
                        formData.append('price', courseData.price || 0);
                        formData.append('free', courseData.freeCourse ? 1 : 0);
                        formData.append('url', generateSlug(title));

                        // Add optional fields
                        const level = document.getElementById('courseLevel').value;
                        if (level) {
                            formData.append('level_id', level);
                        }

                        if (term) {
                            formData.append('term_id', term);
                        }

                        const duration = document.getElementById('courseTime').value;
                        if (duration) {
                            formData.append('duration_hours', duration);
                        }

                        // Add image if selected
                        const fileInput = document.getElementById('fileInput');
                        if (fileInput && fileInput.files && fileInput.files[0]) {
                            formData.append('thumbnail', fileInput.files[0]);
                        }

                        const result = await window.CourseApiClient.createCourse(formData);
                        if (result.success) {
                            ToastNotification.success('Success', 'Course saved as draft!');
                            // Get the course ID from the response
                            const courseId = result.data?.id || result.id;
                            setTimeout(() => {
                                window.location.href = `/editsubject/${courseId}`;
                            }, 1500);
                        } else {
                            ToastNotification.error('Error', result.message || 'Failed to save draft');
                        }
                    } catch (error) {
                        console.error('Save draft error:', error);
                        ToastNotification.error('Error', 'Error saving draft: ' + error.message);
                    }
                });
            }

            showSection('details');
        });
    </script>
@endsection
