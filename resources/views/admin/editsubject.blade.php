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

        .curriculum-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .curriculum-header-text h5 {
            font-size: 1.25rem;
            color: #004A53;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .curriculum-header-text p {
            color: #666;
            font-size: 0.95rem;
            margin: 0;
        }

        .btn-add-topic {
            background-color: #FDAF22;
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-add-topic:hover {
            background-color: #e59a0f;
        }

        .lesson-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .lesson-item:hover {
            border-color: #004A53;
            background-color: #f9f9f9;
        }

        .lesson-item-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #333;
        }

        .lesson-item-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .lesson-item-actions button {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
            padding: 0.25rem 0.5rem;
        }

        .lesson-item-actions button:hover {
            color: #004A53;
        }

        .btn-add-lesson {
            background-color: white;
            border: 1px solid #004A53;
            color: #004A53;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .btn-add-lesson:hover {
            background-color: #f0f8f9;
        }

        .curriculum-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .modal-backdrop.show {
            background-color: #004a53 !important;
        }

        .modal-dialog {
            max-width: 600px;
            margin: auto;
        }

        .modal-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px 16px;
        }

        .modal-content {
            border: none;
        }

        .modal-header {
            padding: 20px;
        }

        .modal-title {
            font-family: "Fredoka", sans-serif;
            color: #000;
            font-size: 24px;
        }

        .modal-header-btn {
            background-color: transparent;
            border: none;
        }

        .modal-form-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        .modal-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .modal-form-input-border {
            border: 1.5px solid #004a53;
            display: flex;
            flex-direction: column;
            padding: 14px 27px 14px;
            border-radius: 15px;
            position: relative;
        }

        .modal-label {
            position: absolute;
            top: -15px;
            color: #004a53;
            font-size: 14px;
            background-color: white;
            padding: 0px 4px;
            align-self: start;
        }

        .modal-input {
            outline: none;
            border: none;
            font-size: 14px;
            color: #aebaca;
            background-color: transparent;
        }

        .modal-form-btn {
            color: #f2f2f2;
            font-size: 16px;
            font-weight: 600;
            background-color: #004a53;
            padding-block: 16px;
            border: none;
        }

        .upload-file-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .upload-label {
            color: #004a53;
            font-size: 14px;
            font-weight: 600;
        }

        .upload-btn {
            background-color: #004a53;
            color: #ffffff;
            font-size: 16px;
            border: 1.5px solid #004a53;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
            padding-inline: 15px;
        }

        .upload-input {
            border: 1.5px solid #004a53;
            border-radius: 15px;
            padding: 12px 15px;
        }

        .textarea {
            outline: none;
            border: none;
            font-size: 14px;
            color: #aebaca;
            height: 150px;
        }

        .hide {
            display: none;
        }

        @media (max-width: 768px) {
            .curriculum-header {
                flex-direction: column;
            }

            .curriculum-actions {
                flex-direction: column;
            }

            .curriculum-actions button {
                width: 100%;
            }
        }

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

        .curriculum-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-group {
            position: absolute;
            right: 10%;
            top: 10px;
        }
    </style>

    <main>
        <!-- Header Section -->
        <div class="container bg-white">
            <div class="subject-header">
                <div>
                    <h1>Edit Course</h1>
                    <p>Here overview of your</p>
                </div>

                <div class="header-buttons">
                    <button type="button" class="btn btn-draft" id="saveDraftBtn">
                        Save As Draft
                    </button>
                    <button type="button" class="btn btn-draft" id="publishBtn">
                        Publish Course
                    </button>

                    <button type="button" class="btn btn-publish" id="updateBtn">
                        Update Course
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="container bg-white">
            <div class="nav-buttons-container">
                <button type="button" class="coursebtn" data-section="curriculum">
                    <i class="fa-solid fa-circle fa-2xs"></i>
                    Curriculum
                    <i class="fa fa-arrow-right"></i>
                </button>

                <button type="button" class="coursebtn" data-section="details">
                    <i class="fa-solid fa-circle fa-2xs"></i>
                    Course Details
                    <i class="fa fa-arrow-right"></i>
                </button>

                <button type="button" class="coursebtn" data-section="media">
                    <i class="fa-solid fa-circle fa-2xs"></i>
                    Subject Media
                    <i class="fa fa-arrow-right"></i>
                </button>

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
                <h5>Subject Media</h5>
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

        <!-- Curriculum Section -->
        <div class="container bg-white content-section" id="curriculum">
            {{-- add new topic modal --}}
            <div class="modal fade" id="addNewTopicModal" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 modal-container">
                        <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                            <h1 class="modal-title" id="staticBackdropLabel">Add Topic</h1><button type="button"
                                class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="fa-solid fa-circle-xmark"></i></button>
                        </div>
                        <form class="modal-form-container">
                            <div class="modal-form">
                                <div class="modal-form-input-border"><label for=""
                                        class="modal-label">Title</label><input class="modal-input" type="text"
                                        placeholder="Enter Title" /></div>
                                <div class="modal-form-input-border"><label for="" class="modal-label">Topic
                                        Description</label>
                                    <textarea name="" id="" class="modal-input"></textarea>
                                </div>
                            </div><button class="modal-form-btn">Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="curriculum-header">
                <div class="curriculum-header-text">
                    <h5>Curriculum</h5>
                    <p>Manage course topics and lessons</p>
                </div>
                <button type="button" class="btn btn-add-topic" data-bs-toggle="modal"
                    data-bs-target="#addNewTopicModal"><i class="fa-solid fa-plus me-2"></i>Add New Topic </button>
            </div>
            <div class="accordion mb-4" id="curriculumAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" style="position:relative; padding:1rem">
                        <div class="mb-2 d-flex gap-2 align-items-center justify-content-end">
                            <button class="btn btn-sm btn-light " type="button" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-sm btn-light" type="button" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                        <button class="accordion-button d-flex justify-content-between align-items-center" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                            aria-controls="collapseOne">

                            <div class="d-flex align-items-start me-3">
                                <i class="fa-solid fa-book-open me-3"></i>

                                <p class="m-0 fw-bold">Parts of Speech</p>

                            </div>
                        </button>
                        <p class="pt-2">This section covers the fundamental parts of speech in English
                            language.</p>

                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#curriculumAccordion">
                        <div class="accordion-body">
                            <div class="lesson-item">
                                <div class="lesson-item-content"><i class="fa-solid fa-circle-play"></i><span>Nouns</span>
                                </div>
                                <div class="lesson-item-actions"><button type="button" title="Edit"><i
                                            class="fa-solid fa-pen-to-square"></i></button><button type="button"
                                        title="Delete"><i class="fa-solid fa-trash"></i></button></div>
                            </div>
                            <div class="lesson-item">
                                <div class="lesson-item-content"><i
                                        class="fa-solid fa-circle-play"></i><span>Pronouns</span></div>
                                <div class="lesson-item-actions"><button type="button" title="Edit"><i
                                            class="fa-solid fa-pen-to-square"></i></button><button type="button"
                                        title="Delete"><i class="fa-solid fa-trash"></i></button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- add lesson modal --}}
            <div class="modal fade" id="addLessonModal" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 modal-container">
                        <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                            <h1 class="modal-title" id="staticBackdropLabel">New Lesson</h1><button type="button"
                                class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="fa-solid fa-circle-xmark"></i></button>
                        </div>
                        <form class="modal-form-container">
                            <div class="modal-form">
                                <div class="modal-form-input-border"><label for="" class="modal-label">Lesson
                                        Type</label><select name="" id="addContent" class="modal-input">
                                        <option value="image">Image</option>
                                        <option value="youtube">Youtube</option>
                                        <option value="audio">Audio</option>
                                        <option value="content">Content</option>
                                        <option value="document">Document</option>
                                    </select></div>
                                {{-- image container --}}
                                <div class="flex-column gap-3 select-children" id="image-container">
                                    <div class="modal-form-input-border"><label for=""
                                            class="modal-label">Title</label><input class="modal-input" type="text"
                                            placeholder="Art" /></div>
                                    <div class="upload-file-container"><label for="" class="upload-label">Upload
                                            File (Size:2mb, Dimension:400px by 250px)
                                        </label>
                                        <div class="input-group"><input type="text"
                                                class="form-control upload-input p-3"
                                                style="border-top-left-radius:15px; border-bottom-left-radius:15px;"
                                                placeholder="Upload file" aria-label="Recipient’s username"
                                                aria-describedby="basic-addon2" /><button class="upload-btn"
                                                type="button" id="button-addon2">Upload File </button></div>
                                    </div>
                                </div>
                                {{-- youtube container --}}
                                <div class="flex-column gap-3 hide select-children" id="youtube-container">
                                    <div class="modal-form-input-border"><label for=""
                                            class="modal-label">Title</label><input class="modal-input" type="text"
                                            placeholder="Enter title" /></div>
                                    <div class="modal-form-input-border"><label for=""
                                            class="modal-label">Youtube Url</label><input class="modal-input"
                                            type="text" placeholder="Enter url" /></div>
                                </div>
                                {{-- content container  --}}
                                <div class="flex-column gap-3 hide select-children" id="content-container">
                                    <div class="modal-form-input-border"><label for=""
                                            class="modal-label">Title</label><input class="modal-input" type="text"
                                            placeholder="Enter title" /></div>
                                    <div class="modal-form-input-border"><label for="" class="modal-label">Lesson
                                            Content</label>
                                        <textarea name="" id="" class="modal-input"></textarea>
                                    </div>
                                </div>
                                {{-- audio-container --}}
                                <div class="flex-column gap-3 hide select-children" id="audio-container">
                                    <div class="modal-form-input-border"><label for=""
                                            class="modal-label">Title</label><input class="modal-input" type="text"
                                            placeholder="Enter title" /></div>
                                    <div class="upload-file-container"><label for="" class="upload-label">Upload
                                            File (Size:2mb, Dimension:400px by
                                            250px) </label>
                                        <div class="input-group"><input type="text"
                                                class="form-control upload-input p-3"
                                                style="border-top-left-radius:15px; border-bottom-left-radius:15px;"
                                                placeholder="Upload file" aria-label="Recipient’s username"
                                                aria-describedby="basic-addon2" /><button class="upload-btn"
                                                type="button" id="button-addon2">Upload File </button></div>
                                    </div>
                                </div>
                                {{-- document-container --}}
                                <div class="flex-column gap-3 hide select-children" id="document-container">
                                    <div class="modal-form-input-border"><label for="" class="modal-label">Lesson
                                            Type</label>
                                        <select name="" id="" class="modal-input">
                                            <option value="png">Image</option>
                                            <option value="pdf">PDF</option>
                                            <option value="docx">Docx</option>
                                        </select>
                                    </div>
                                    <div class="modal-form-input-border"><label for=""
                                            class="modal-label">Title</label><input class="modal-input" type="text"
                                            placeholder="Enter title" /></div>
                                    <div class="upload-file-container"><label for="" class="upload-label">Upload
                                            File (Size:2mb, Dimension:400px
                                            by 250px) </label>
                                        <div class="input-group"><input type="text"
                                                class="form-control upload-input p-3"
                                                style="border-top-left-radius:15px; border-bottom-left-radius:15px;"
                                                placeholder="Upload file" aria-label="Recipient’s username"
                                                aria-describedby="basic-addon2" /><button class="upload-btn"
                                                type="button" id="button-addon2">Upload File </button></div>
                                    </div>
                                </div>
                            </div><button class="modal-form-btn">Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-add-lesson" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                <i class="fa-solid fa-plus me-2"></i>Add Lesson
            </button>
            <div class="curriculum-actions">
                <button type="button" class="btn btn-continue continue-btn" data-next="details">Continue </button>
            </div>
        </div>
        {{-- Publish Section --}}
        <div class="container bg-white d-none content-section" id="publish">
            <div class="publish-overview">
                <div class="overview-header">
                    <div class="overview-title">
                        <h6>Subject Overview</h6>
                        <h2 id="publishSubjectTitle">English Language</h2>
                    </div>
                    <div class="overview-actions"><button type="button" title="Edit"><i
                                class="fa-solid fa-check-circle"
                                style="color: #004A53; font-size: 1.5rem;"></i></button><button type="button"
                            title="More options"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
                </div>
                <div class="overview-meta">
                    <div class="meta-item"><i class="fa-solid fa-book"></i><span id="publishTopics">0 Topics</span>
                    </div>
                    <div class="meta-item"><i class="fa-solid fa-graduation-cap"></i><span id="publishLessons">0
                            Lessons</span></div>
                    <div class="meta-item"><i class="fa-solid fa-clock"></i><span id="publishTime">0 Hours</span>
                    </div>
                    <div class="meta-item"><i class="fa-solid fa-layer-group"></i><span id="publishLevel">Level</span>
                    </div>
                </div><img id="publishCourseImage" src="images/publish.png" alt="Course Preview" class="course-image">
                <div class="course-description-section">
                    <h6>Subject Description</h6>
                    <p id="publishDescription">This comprehensive course covers essential concepts and skills. Students
                        will learn through interactive lessons,
                        practice exercises,
                        and assessments to build a strong foundation. </p>
                </div>
                <div class="course-description-section">
                    <h6>Key Areas of Study:</h6>
                    <ul class="key-areas-list" id="publishKeyAreas">
                        <li>Fundamental Concepts</li>
                        <li>Practical Applications</li>
                        <li>Advanced Techniques</li>
                        <li>Real-world Examples</li>
                        <li>Assessment & Evaluation</li>
                    </ul>
                </div>
                <div class="curriculum-preview">
                    <h6>Curriculum</h6>
                    <div id="curriculumPreviewContainer">
                        <div class="curriculum-item">
                            <div class="curriculum-item-content">
                                <div class="curriculum-item-icon"><i class="fa-solid fa-book-open"></i></div>
                                <div class="curriculum-item-text">
                                    <h6>Parts of Speech</h6>
                                    <p>Foundation concepts</p>
                                </div>
                            </div>
                            <div class="curriculum-item-meta"><span><i class="fa-solid fa-graduation-cap"></i>5
                                    Lessons</span><span><i class="fa-solid fa-clock"></i>2 Units</span></div>
                            <div class="curriculum-item-check"><i class="fa-solid fa-check-circle"></i></div>
                        </div>
                        <div class="curriculum-item">
                            <div class="curriculum-item-content">
                                <div class="curriculum-item-icon"><i class="fa-solid fa-book-open"></i></div>
                                <div class="curriculum-item-text">
                                    <h6>Sentence Structure</h6>
                                    <p>Building complex sentences</p>
                                </div>
                            </div>
                            <div class="curriculum-item-meta"><span><i class="fa-solid fa-graduation-cap"></i>4
                                    Lessons</span><span><i class="fa-solid fa-clock"></i>2 Units</span></div>
                            <div class="curriculum-item-check"><i class="fa-solid fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="publish-actions">
                <button type="button" class="btn btn-back back-btn" data-next="media">Back </button>
                <button type="button" class="btn btn-publish" id="finalPublishBtn">Publish Now </button>
            </div>
        </div>
    </main>

    <script type="module">
        import CourseApiClient from '{{ asset('js/api/courseApiClient.js') }}';

        // Get course ID from URL
        const courseId = '{{ $courseId }}';

        // Load course data if course ID is provided
        async function loadCourseData() {
            if (!courseId) {
                console.log('No course ID provided');
                return;
            }

            try {
                console.log('Loading course with ID:', courseId);

                // Use CourseApiClient to fetch course data
                const result = await CourseApiClient.getCourse(courseId);

                console.log('Course API result:', result);

                if (!result.success) {
                    console.error('Failed to load course data:', result.message);
                    return;
                }

                const course = result.data || result;

                console.log('Course data received:', course);

                // Populate form fields with course data
                if (course.title) document.getElementById('courseTitle').value = course.title;
                if (course.description) document.getElementById('courseDescription').value = course.description;
                if (course.course_category_id) document.getElementById('courseCategory').value = course.course_category_id;
                if (course.level_id) document.getElementById('courseLevel').value = course.level_id;
                if (course.term_id) document.getElementById('subjectTerm').value = course.term_id;
                if (course.duration_hours) document.getElementById('courseTime').value = course.duration_hours;
                if (course.price) document.getElementById('coursePrice').value = course.price;

                // Set free course checkbox and disable price if free
                const freeCourseCheckbox = document.getElementById('free-course');
                const priceInput = document.getElementById('coursePrice');
                if (course.free) {
                    freeCourseCheckbox.checked = course.free;
                    if (priceInput) priceInput.disabled = true;
                }

                // Store course data for later use in populatePublishSection
                window.courseData = course;

                console.log('Course data loaded successfully:', course);
            } catch (error) {
                console.error('Error loading course data:', error);
            }
        }

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
                if (termsResponse.ok && termsResult) {
                    const termSelect = document.getElementById('subjectTerm');
                    const terms = Array.isArray(termsResult) ? termsResult : [];
                    termSelect.innerHTML = `<option value="">Select Term</option>`;

                    terms.forEach(term => {
                        const option = document.createElement('option');
                        option.value = term.id;
                        option.textContent = term.name;
                        termSelect.appendChild(option);
                    });
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

            // Load dropdown data
            loadDropdownData();

            // Load course data if course ID is provided
            if (courseId) {
                loadCourseData();
            }

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

            // addlessong modal js

            const selectContainer = document.getElementById("addContent");

            function showSelectedContainer(contentType) {
                document
                    .querySelectorAll(".select-children")
                    .forEach((container) => (container.style.display = "none"));

                if (contentType === "image") {
                    document.getElementById("image-container").style.display = "flex";
                }
                if (contentType === "youtube") {
                    document.getElementById("youtube-container").style.display = "flex";
                }
                if (contentType === "audio") {
                    document.getElementById("audio-container").style.display = "flex";
                }
                if (contentType === "content") {
                    document.getElementById("content-container").style.display = "flex";
                }
                if (contentType === "document") {
                    document.getElementById("document-container").style.display = "flex";
                }
            }

            document.addEventListener("DOMContentLoaded", () => {
                showSelectedContainer(selectContainer.value);
            });

            selectContainer.addEventListener("change", (e) => {
                showSelectedContainer(e.target.value);
            });

            function populatePublishSection() {
                // Get data from form fields with correct IDs
                const titleElement = document.getElementById('courseTitle');
                const categoryElement = document.getElementById('courseCategory');
                const levelElement = document.getElementById('courseLevel');
                const timeElement = document.getElementById('courseTime');
                const descriptionElement = document.getElementById('courseDescription');
                const fileInput = document.getElementById('fileInput');

                const title = titleElement ? titleElement.value : 'English Language';
                const category = categoryElement ? categoryElement.value : 'Language';
                const level = levelElement ? levelElement.value : 'JSS 1';
                const time = timeElement ? timeElement.value : '0 Hours';
                const description = descriptionElement ? descriptionElement.value : 'This comprehensive course covers essential concepts and skills.';

                // Update publish section
                const publishTitleEl = document.getElementById('publishSubjectTitle');
                const publishTopicsEl = document.getElementById('publishTopics');
                const publishLessonsEl = document.getElementById('publishLessons');
                const publishTimeEl = document.getElementById('publishTime');
                const publishLevelEl = document.getElementById('publishLevel');
                const publishDescriptionEl = document.getElementById('publishDescription');
                const publishImageEl = document.getElementById('publishCourseImage');

                if (publishTitleEl) publishTitleEl.textContent = title;
                if (publishTopicsEl) publishTopicsEl.textContent = '0 Topics';
                if (publishLessonsEl) publishLessonsEl.textContent = '0 Lessons';
                if (publishTimeEl) publishTimeEl.textContent = time;
                if (publishLevelEl) publishLevelEl.textContent = level;
                if (publishDescriptionEl) publishDescriptionEl.textContent = description;

                // Update course image if file is selected
                if (fileInput && fileInput.files && fileInput.files[0] && publishImageEl) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        publishImageEl.src = e.target.result;
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }

            navButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const section = btn.getAttribute('data-section');
                    showSection(section);
                });
            });

            continueButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const next = btn.getAttribute('data-next');
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

            if (fileInput) {
                fileInput.addEventListener('change', (e) => {
                    if (e.target.files && e.target.files[0]) {
                        fileNameDisplay.value = e.target.files[0].name;
                    }
                });
            }

            // Publish button handler
            const finalPublishBtn = document.getElementById('finalPublishBtn');
            if (finalPublishBtn) {
                finalPublishBtn.addEventListener('click', () => {
                    const title = document.getElementById('subjectTitle').value;
                    const category = document.getElementById('subjectCategory').value;
                    const level = document.getElementById('subjectLevel').value;

                    if (!title || !category || !level) {
                        alert('Please fill in all required fields');
                        return;
                    }

                    // Here you would submit the form to the server
                    console.log('Publishing course:', {
                        title,
                        category,
                        level,
                        time: document.getElementById('subjectTime').value,
                        lessons: document.getElementById('totalLesson').value,
                        description: document.getElementById('subjectDescription').value
                    });

                    alert('Course published successfully!');
                });
            }

            // Save draft button handler
            const saveDraftBtn = document.getElementById('saveDraftBtn');
            if (saveDraftBtn) {
                saveDraftBtn.addEventListener('click', () => {
                    const title = document.getElementById('subjectTitle').value;
                    if (!title) {
                        alert('Please enter a subject title');
                        return;
                    }

                    console.log('Saving draft...');
                    alert('Course saved as draft!');
                });
            }

            showSection('curriculum');
        });
    </script>
@endsection
