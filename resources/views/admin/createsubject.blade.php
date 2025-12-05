@extends('layouts.dashboardtemp')

@section('content')
    <style>
        /* ===== Header Styles ===== */
        .subject-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
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
            margin-bottom: 2rem;
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

                    <button type="button" class="btn btn-publish" id="publishBtn">
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

                    <div class="editor-toolbar">
                        <span title="Bold"><i class="fa-solid fa-bold"></i></span>
                        <span title="Italic"><i class="fa-solid fa-italic"></i></span>
                        <span title="Underline"><i class="fa-solid fa-underline"></i></span>
                        <span title="Strikethrough"><i class="fa-solid fa-strikethrough"></i></span>
                        <span title="Upload"><i class="fa-solid fa-file-arrow-up"></i></span>
                    </div>

                    <textarea class="description-textarea" id="courseDescription" name="courseDescription"
                        placeholder="Write subject description here..." form="courseDetailsForm"></textarea>
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
                    <label>Upload Subject Image</label>
                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                        <input type="text" class="form-control" id="fileNameDisplay" placeholder="No file selected"
                            readonly style="flex: 1;">
                        <button type="button" class="btn btn-publish" id="uploadButton"
                            style="padding: 0.75rem 1.5rem;">
                            Upload File
                        </button>
                    </div>

                    <label
                        style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; border: 2px dashed #ddd; border-radius: 0.375rem; cursor: pointer; transition: all 0.3s ease; background-color: #f9f9f9;"
                        for="fileInput">
                        <i class="fa-solid fa-file-circle-check"
                            style="font-size: 2rem; color: #004A53; margin-bottom: 0.5rem;"></i>
                        <h5 style="margin: 0.5rem 0; color: #333;">Upload Image</h5>
                        <p style="margin: 0; color: #666; font-size: 0.9rem;">PNG, JPEG, GIF (max 2MB)</p>
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

                <img id="publishCourseImage" src="images/publish.png" alt="Course Preview" class="course-image">

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
                    <button type="button" class="btn btn-publish" id="finalPublishBtn">
                        Save Now
                    </button>
                </div>
            </div>
        </div>

        <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3"></div>

    </main>



    <script>
        // ===========================
        //  TOAST NOTIFICATION SYSTEM
        // ===========================
        function showToast(title = '', message = '', type = 'info', timeout = 3500) {
            const container = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();

            const bgClass = (type === 'success') ?
                'bg-success text-white' :
                (type === 'danger') ?
                'bg-danger text-white' :
                'bg-light';

            const toastHtml = `
                        <div id="${toastId}" class="toast ${bgClass}" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body" style="padding:0.75rem;">
                                    <strong>${title}</strong>
                                    <div style="font-size:0.9rem; margin-top:0.35rem;">${message}</div>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                        data-bs-dismiss="toast"></button>
                            </div>
                        </div>
                    `;
            container.insertAdjacentHTML('beforeend', toastHtml);

            const toastEl = document.getElementById(toastId);
            const bsToast = new bootstrap.Toast(toastEl, {
                delay: timeout
            });
            bsToast.show();

            toastEl.addEventListener("hidden.bs.toast", () => toastEl.remove());
        }




        const courseData = {
            title: "",
            category: "",
            level: "",
            duration: "",
            price: "",
            description: "",
            freeCourse: '',
            imageFile: null
        };
        const API_CATEGORIES = "/api/course-category";
        const API_TERMS = "/api/term";
        const API_LEVEL = "/api/level";
        const token = localStorage.getItem('auth_token') || '';

        // Navigation between sections
        document.addEventListener('DOMContentLoaded', () => {
            const navButtons = document.querySelectorAll('.coursebtn');
            const sections = document.querySelectorAll('.content-section');
            const continueButtons = document.querySelectorAll('.continue-btn');
            const backButtons = document.querySelectorAll('.back-btn');
            const courseCategory = document.getElementById('courseCategory')
            const courseTerm = document.getElementById('subjectTerm')
            const courseLevel = document.getElementById('courseLevel')

            function showSection(sectionId) {
                sections.forEach(sec => sec.classList.add('d-none'));
                const section = document.getElementById(sectionId);
                if (section) {
                    section.classList.remove('d-none');
                }

                navButtons.forEach(btn => btn.classList.remove('course-btn-active'));
                const activeBtn = document.querySelector(`[data-section="${sectionId}"]`);
                if (activeBtn) {
                    activeBtn.classList.add('course-btn-active');
                }

                // Populate publish section when navigating to it
                if (sectionId === 'publish') {
                    populatePublishSection();
                }
            }

            loadCategories();
            loadLevel();
            loadTerm();

            async function loadCategories() {
                try {
                    const data = await apiFetch(API_CATEGORIES, {
                        method: 'GET'
                    });
                    categories = unwrapListResponse(data);
                    populateCategorySelect();
                } catch (err) {
                    console.error('Failed to load categories', err);
                    showToast("Error", "Failed to load categories", "danger");
                }
            }


            async function loadTerm() {
                try {
                    const data = await apiFetch(API_TERMS, {
                        method: 'GET'
                    });
                    terms = unwrapListResponse(data);
                    populateTermSelect();
                } catch (err) {
                    console.error('Failed to load terms', err);
                    showToast("Error", "Failed to load terms", "danger");
                }
            }

            function populateCategorySelect() {
                if (!courseCategory) return;
                courseCategory.innerHTML = `<option value="">Select Course Category</option>`;
                categories.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.id + '-' + cat.title;
                    opt.textContent = cat.title ?? `#${cat.id}`;
                    courseCategory.appendChild(opt);
                });
            }

            function populateTermSelect() {
                if (!courseTerm) return;
                courseTerm.innerHTML = `<option value="">Select Term</option>`;
                terms.forEach(term => {
                    const opt = document.createElement('option');
                    opt.value = term.id + '-' + term.name;
                    opt.textContent = term.name ?? `#${term.id}`;
                    courseTerm.appendChild(opt);
                });
            }

            async function loadLevel() {
                try {
                    const data = await apiFetch(API_LEVEL, {
                        method: 'GET'
                    });
                    levels = unwrapListResponse(data);
                    populateLevelSelect();
                } catch (err) {
                    console.error('Failed to load levels', err);
                    showToast("Error", "Failed to load levels", "danger");
                }
            }

            function populateLevelSelect() {
                if (!courseLevel) return;
                courseLevel.innerHTML = `<option value="">Select Level Category</option>`;
                levels.forEach(level => {
                    const opt = document.createElement('option');
                    opt.value = level.id + '-' + level.name;
                    opt.textContent = level.name ?? `#${level.id}`;
                    courseLevel.appendChild(opt);
                });
            }

            courseLevel.addEventListener('change', e => {
                const selectedValue = e.target.value; // "3-SS1" format
                const levelId = selectedValue.split('-')[0];

                const selectedLevel = levels.find(l => l.id == levelId);

                if (selectedLevel) {
                    const curriculumId = selectedLevel.curriculum_category_id;

                    document.getElementById('curriculumCategoryId').value = curriculumId;
                    // console.log("Curriculum Category ID Set To:", curriculumId);
                }
            });



            async function apiFetch(url, opts = {}) {
                const headers = Object.assign({
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                }, opts.headers || {});

                if (token) headers["Authorization"] = `Bearer ${token}`;

                const options = Object.assign({}, opts, {
                    headers
                });
                const res = await fetch(url, options);
                const contentType = res.headers.get('content-type') || '';

                let data = null;
                if (contentType.includes('application/json')) {
                    data = await res.json();
                } else {
                    data = await res.text();
                }

                if (!res.ok) {
                    const message = (data && data.message) ? data.message : (typeof data === 'string' ? data :
                        'Request failed');
                    const err = new Error(message);
                    err.status = res.status;
                    err.payload = data;
                    throw err;
                }
                return data;
            }

            function unwrapListResponse(raw) {
                // Accept forms: array, {data: [...]}, {status:..., response: [...]}, {response: {data: [...]}}
                if (!raw) return [];
                if (Array.isArray(raw)) return raw;
                if (raw.data && Array.isArray(raw.data)) return raw.data;
                if (raw.response && Array.isArray(raw.response)) return raw.response;
                if (raw.response && raw.response.data && Array.isArray(raw.response.data)) return raw.response.data;
                return [];
            }

            function unwrapItemResponse(raw) {
                // Return item object from common shapes
                if (!raw) return null;
                if (raw && raw.id) return raw;
                if (raw.response && raw.response.id) return raw.response;
                if (raw.data && raw.data.id) return raw.data;
                return raw;
            }

            // Get data from form fields
            document.getElementById('courseTitle').addEventListener('input', e => {
                courseData.title = e.target.value;
            });

            document.getElementById('courseCategory').addEventListener('change', e => {
                courseData.category = e.target.value;
            });

            document.getElementById('courseLevel').addEventListener('change', e => {
                courseData.level = e.target.value;
            });

            document.getElementById('courseTime').addEventListener('input', e => {
                courseData.duration = e.target.value;
            });

            document.getElementById('coursePrice').addEventListener('input', e => {
                courseData.price = e.target.value;
            });

            document.getElementById('courseDescription').addEventListener('input', e => {
                courseData.description = e.target.value;
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
                // Update publish section
                document.getElementById('publishSubjectTitle').textContent = courseData.title;
                document.getElementById('publishCategory').textContent = courseData.category.split('-')[1] +
                    ' Category';
                document.getElementById('publishPrice').textContent = courseData.freeCourse ? 'Free Course' :
                    courseData.price + ' Price';
                document.getElementById('publishTime').textContent = courseData.duration + ' Hours';
                document.getElementById('publishLevel').textContent = courseData.level.split('-')[1] + ' Level';
                document.getElementById('publishDescription').textContent = courseData.description;


                // Update course image if file is selected
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
                    if (section === 'media' && !courseData.title && !courseData.price && !courseData
                        .duration) {
                        return
                    }
                    if (section === 'publish' && !courseData.imageFile && !courseData.title && !
                        courseData.price && !courseData.duration) {
                        return
                    }
                    showSection(section);
                });
            });

            continueButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const next = btn.getAttribute('data-next');
                    if (next === 'media' && !courseData.title && !courseData.price && !courseData
                        .duration) {
                        return
                    }
                    if (next === 'publish' && !courseData.imageFile && !courseData.title && !
                        courseData.price && !courseData.duration) {
                        return
                    }
                    showSection(next);
                });
            });

            backButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const back = btn.getAttribute('data-next');
                    showSection(back);
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

            if (courseData.imageFile) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('publishCourseImage').src = e.target.result;
                };
                reader.readAsDataURL(courseData.imageFile);
            }

            function validateBeforePublish() {
                const required = [
                    courseData.title,
                    courseData.category.split('-')[0],
                    courseData.level.split('-')[0],
                    courseData.duration,
                    courseData.description,
                    courseData.imageFile
                ];

                if (!courseData.freeCourse) {
                    required.push(courseData.price);
                }

                return required.every(v => v && v !== "");
            }


            // Publish button handler
            const finalPublishBtn = document.getElementById('finalPublishBtn');
            if (finalPublishBtn) {
                finalPublishBtn.addEventListener('click', async () => {

                    if (!validateBeforePublish()) {
                        alert('Please fill in all required fields');
                        return;
                    }


                    const formData = new FormData();
                    formData.append("title", courseData.title);
                    formData.append("description", courseData.description);

                    // REQUIRED
                    formData.append("curriculum_category_id", document.getElementById(
                        "curriculumCategoryId").value);
                    formData.append("course_category_id", courseData.category.split('-')[0]);
                    formData.append("price", courseData.price);

                    // OPTIONAL
                    formData.append("level_id", courseData.level.split('-')[0]);
                    formData.append("term_id", courseData.term ? courseData.term.split('-')[0] : "");
                    formData.append("duration_hours", courseData.duration);

                    // IMAGE FIELD MUST MATCH LARAVEL
                    formData.append("thumbnail", courseData.imageFile);

                    try {
                        const res = await fetch("/api/courses", {
                            method: "POST",
                            headers: {
                                "Authorization": `Bearer ${token}`
                            },
                            body: formData
                        });

                        const data = await res.json();
                        console.log("Response:", data);
                        if (res.ok) {
                            showToast("Success", "Course created successfully!", "success");
                            setTimeout(() => {
                                window.location.href = "/editsubject";
                            }, 1500);
                        } else {
                            showToast("Error", data.message || "Failed to create course.",
                                "danger");
                        }


                    } catch (error) {
                        console.error(error);
                        showToast("Error", "Network or server error occurred.", "danger");
                    }
                });
            }

            // Save draft button handler
            // const saveDraftBtn = document.getElementById('saveDraftBtn');
            // if (saveDraftBtn) {
            //     saveDraftBtn.addEventListener('click', () => {
            //         const title = document.getElementById('courseTitle').value;
            //         if (!title) {
            //             alert('Please enter a subject title');
            //             return;
            //         }

            //         console.log('Saving draft...');
            //         alert('Course saved as draft!');
            //     });
            // }

            showSection('details');
        });
    </script>
@endsection
