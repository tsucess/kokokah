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

        /* ===== Drag and Drop Styles ===== */
        .draggable-topic {
            cursor: grab;
            transition: all 0.2s ease;
        }

        .draggable-topic:active {
            cursor: grabbing;
        }

        .draggable-topic.drag-over {
            background-color: #f0f8f9;
            border-left: 4px solid #004A53;
        }

        .draggable-topic.dragging {
            opacity: 0.5;
            background-color: #e8f4f6;
        }

        .drag-handle {
            cursor: grab;
            color: #004A53;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
        }

        .drag-handle:hover {
            color: #FDAF22;
        }

        .draggable-lesson {
            cursor: grab;
            transition: all 0.2s ease;
        }

        .draggable-lesson:active {
            cursor: grabbing;
        }

        .draggable-lesson.drag-over {
            background-color: #f0f8f9;
            border-left: 3px solid #FDAF22;
            padding-left: 0.5rem;
        }

        .draggable-lesson.dragging {
            opacity: 0.5;
            background-color: #e8f4f6;
        }

        .lesson-drag-handle {
            cursor: grab;
            color: #FDAF22;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
        }

        .lesson-drag-handle:hover {
            color: #004A53;
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

        .select-quiz-children {
            display: none;
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
                    <button type="button" class="btn btn-draft" id="saveDraftBtn" style="display: none;">
                        Save As Draft
                    </button>
                    <button type="button" class="btn btn-draft" id="publishBtn" style="display: none;">
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
                        <input type="text" class="form-control" id="overviewVideoUrl"
                            placeholder="https://preview.youtube.com" style="flex: 1;">
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
                                        class="modal-label">Title</label>
                                    <input class="modal-input" type="text" placeholder="Enter Title" />
                                </div>
                                {{-- <div class="modal-form-input-border">
                                    <label for="" class="modal-label">Topic
                                        Description</label>
                                    <textarea name="" id="" class="modal-input"></textarea>
                                </div> --}}
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
                <!-- Topics will be dynamically loaded here -->
            </div>
            {{-- add lesson modal --}}
            <div class="modal fade" id="addLessonModal" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="lessonModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 modal-container">
                        <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                            <h1 class="modal-title" id="lessonModalLabel">New Lesson</h1><button type="button"
                                class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="fa-solid fa-circle-xmark"></i></button>
                        </div>
                        <form class="modal-form-container">
                            <div class="modal-form">
                                <div class="modal-form-input-border">
                                    <label for="" class="modal-label">Lesson Title</label>
                                    <input class="modal-input" type="text" placeholder="Enter title" />
                                </div>
                                <div class="modal-form-input-border"><label for="" class="modal-label">Lesson
                                        Type</label><select name="" id="addContent" class="modal-input">
                                        {{-- <option value="image">Image</option> --}}
                                        <option value="youtube">Youtube Url</option>
                                        {{-- <option value="audio">Audio</option> --}}
                                        <option value="content">Content</option>
                                        <option value="document">Document</option>
                                    </select>
                                </div>

                                {{-- image container --}}
                                <div class="flex-column gap-3 select-children" id="image-container"
                                    style="display: none">
                                    <div class="modal-form-input-border">
                                        <label for="" class="modal-label">Title</label>
                                        <input class="modal-input" type="text" placeholder="Art" />
                                    </div>
                                    <div class="upload-file-container">
                                        <label for="" class="upload-label">Upload File (Size:2mb, Dimension:400px
                                            by 250px) </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control upload-input p-3"
                                                style="border-top-left-radius:15px; border-bottom-left-radius:15px;"
                                                placeholder="Upload file" aria-label="Recipient’s username"
                                                aria-describedby="basic-addon2" />
                                            <button class="upload-btn" type="button" data-upload-type="image">Upload
                                                File </button>
                                        </div>
                                    </div>
                                    <input type="file" id="imageFileInput" style="display: none;" accept="image/*" />
                                </div>
                                {{-- youtube container --}}
                                <div class="flex-column gap-3 hide select-children" id="youtube-container">
                                    <div class="modal-form-input-border"><label for=""
                                            class="modal-label">Youtube Url</label><input class="modal-input"
                                            type="text" placeholder="Enter url" /></div>
                                </div>
                                {{-- content container  --}}
                                <div class="flex-column gap-3 hide select-children" id="content-container">
                                    <div class="modal-form-input-border"><label for="" class="modal-label">Lesson
                                            Content</label>
                                        <textarea name="" id="" class="modal-input" placeholder="Enter lesson content"></textarea>
                                    </div>
                                </div>
                                {{-- <div class="modal-form-input-border" id="content-container">
                                    <label for="" class="modal-label">Lesson Content</label>
                                    <textarea class="modal-input" placeholder="Enter lesson content"></textarea>
                                </div> --}}
                                {{-- audio-container --}}
                                <div class="flex-column gap-3 hide select-children" id="audio-container">
                                    <div class="upload-file-container"><label for="" class="upload-label">Upload
                                            File (Size:2mb, Dimension:400px by 250px) </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control upload-input p-3"
                                                style="border-top-left-radius:15px; border-bottom-left-radius:15px;"
                                                placeholder="Upload file" aria-label="Recipient’s username"
                                                aria-describedby="basic-addon2" />
                                            <button class="upload-btn" type="button" data-upload-type="audio">Upload
                                                File </button>
                                        </div>
                                    </div>
                                    <input type="file" id="audioFileInput" style="display: none;" accept="audio/*" />
                                </div>
                                {{-- document-container --}}
                                <div class="flex-column gap-3 hide select-children" id="document-container">
                                    <div class="modal-form-input-border">
                                        <label for="" class="modal-label">File Type</label>
                                        <select name="" id="" class="modal-input">
                                            <option value="image">Image</option>
                                            <option value="audio">Audio</option>
                                            <option value="video">Video</option>
                                            <option value="pdf">PDF</option>
                                            <option value="docx">Docx</option>
                                        </select>
                                    </div>
                                    <div class="upload-file-container"><label for="" class="upload-label">Upload
                                            File (Size:2mb, Dimension:400px by 250px) </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control upload-input p-3"
                                                style="border-top-left-radius:15px; border-bottom-left-radius:15px;"
                                                placeholder="Upload file" aria-label="Recipient’s username"
                                                aria-describedby="basic-addon2" />
                                            <button class="upload-btn" type="button" data-upload-type="document">Upload
                                                File
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" id="documentFileInput" style="display: none;"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" />
                                </div>
                            </div>
                            <button type="button" class="modal-form-btn" onclick="saveLessonHandler()">Save</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- interactive-quiz-modal --}}
            <div class="modal fade" id="quiz-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 modal-container">
                        <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                            <h1 class="modal-title d-flex gap-1 align-items-center" id="staticBackdropLabel">
                                <i class="fa-solid fa-chevron-left fa-2xs" style="color: #333333"></i>Interactive Quiz
                            </h1>
                            <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </div>
                        <form class="modal-form-container">
                            <div class="modal-form">
                                <div class="modal-form-input-border">
                                    <label for="" class="modal-label">Question</label>
                                    <input class="modal-input" type="text"
                                        placeholder="Identify the type of noun in this sentence: The herd of cows is grazing." />
                                </div>
                                <div class="modal-form-input-border">
                                    <label for="" class="modal-label">Question Type * </label>
                                    <select name="" id="quiz-choice" class="modal-input">
                                        <option value="multiple-choice">Multiple Choice</option>
                                        <option value="alternative-choice">Alternative Choice</option>
                                    </select>
                                </div>
                                <div class="flex-column gap-3 select-quiz-children" id="multiple-choice-container">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="modal-form-input-border flex-md-fill">
                                            <label for="" class="modal-label">Option One</label>
                                            <input class="modal-input" type="text" placeholder="10" />
                                        </div>
                                        <div class="modal-form-input-border flex-md-fill">
                                            <label for="" class="modal-label">Option Two</label>
                                            <input class="modal-input" type="text" placeholder="10" />
                                        </div>

                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="modal-form-input-border flex-md-fill">
                                            <label for="" class="modal-label">Option Three</label>
                                            <input class="modal-input" type="text" placeholder="10" />
                                        </div>
                                        <div class="modal-form-input-border flex-md-fill">
                                            <label for="" class="modal-label">Option Four</label>
                                            <input class="modal-input" type="text" placeholder="10" />
                                        </div>

                                    </div>

                                </div>

                                <div class="w-100 select-quiz-children" id='alternative-choice-container'>
                                    <div class="d-flex align-items-center gap-2 flex-md-fill"
                                        >
                                        <div class="modal-form-input-border flex-md-fill">
                                            <label for="" class="modal-label">Option One</label>
                                            <input class="modal-input" type="text" placeholder="10" />
                                        </div>
                                        <div class="modal-form-input-border flex-md-fill">
                                            <label for="" class="modal-label">Option Two</label>
                                            <input class="modal-input" type="text" placeholder="10" />
                                        </div>

                                    </div>
                                </div>

                                <div class="d-flex flex-column gap-2 flex-md-row">
                                    <div class="modal-form-input-border flex-md-fill">
                                        <label for="" class="modal-label">Correct Answer</label>
                                        <input class="modal-input" type="text" placeholder="10" />
                                    </div>
                                    <div class="modal-form-input-border flex-md-fill">
                                        <label for="" class="modal-label">Assigned Mark</label>
                                        <input class="modal-input" type="text" placeholder="10" />
                                    </div>
                                </div>
                            </div>
                            <button class="modal-form-btn">Add Category</button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- OLD CONTENT REMOVED --}}

            {{-- <button type="button" class="btn btn-add-lesson" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                <i class="fa-solid fa-plus me-2"></i>Add Lesson
            </button> --}}
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
        import TopicApiClient from '{{ asset('js/api/topicApiClient.js') }}';
        import LessonApiClient from '{{ asset('js/api/lessonApiClient.js') }}';
        import ToastNotification from '{{ asset('js/utils/toastNotification.js') }}';

        // Get course ID from URL
        const courseId = '{{ $courseId }}';

        // Load course data if course ID is provided
        async function loadCourseData() {
            if (!courseId) {
                console.log('No course ID provided');
                return;
            }

            try {

                // Use CourseApiClient to fetch course data
                const result = await CourseApiClient.getCourse(courseId);

                if (!result.success) {
                    console.error('Failed to load course data:', result.message);
                    return;
                }

                const course = result.data || result;
                console.log('Course updated successfully:', result.data);

                // Populate form fields with course data
                if (course.title) document.getElementById('courseTitle').value = course.title;
                if (course.description) document.getElementById('courseDescription').value = course.description;
                if (course.course_category_id) document.getElementById('courseCategory').value = course
                    .course_category_id;
                if (course.level_id) document.getElementById('courseLevel').value = course.level_id;
                if (course.term_id) document.getElementById('subjectTerm').value = course.term_id;
                if (course.duration_hours) document.getElementById('courseTime').value = course.duration_hours;
                if (course.price) document.getElementById('coursePrice').value = course.price;
                if (course.url) document.getElementById('overviewVideoUrl').value = course.url;

                // Set free course checkbox and disable price if free
                const freeCourseCheckbox = document.getElementById('free-course');
                const priceInput = document.getElementById('coursePrice');
                if (course.free) {
                    freeCourseCheckbox.checked = course.free;
                    if (priceInput) priceInput.disabled = true;
                }

                // Store course data for later use in populatePublishSection
                window.courseData = course;

                // Update button visibility based on course status
                updateButtonVisibility(course.status);
            } catch (error) {
                console.error('Error loading course data:', error);
            }
        }

        // Update button visibility based on course status
        function updateButtonVisibility(status) {
            const saveDraftBtn = document.getElementById('saveDraftBtn');
            const publishBtn = document.getElementById('publishBtn');

            // Hide both buttons first
            if (saveDraftBtn) saveDraftBtn.style.display = 'none';
            if (publishBtn) publishBtn.style.display = 'none';

            // Show appropriate buttons based on status
            if (status === 'draft') {
                // For draft courses: show "Publish Course" and "Save As Draft" buttons
                if (publishBtn) publishBtn.style.display = 'inline-block';
                // if (saveDraftBtn) saveDraftBtn.style.display = 'inline-block';
            } else if (status === 'published') {
                // For published courses: show "Save As Draft" button only
                if (saveDraftBtn) saveDraftBtn.style.display = 'inline-block';
            }
        }

        // Load topics for the course
        async function loadTopics() {
            if (!courseId) {
                console.log('No course ID provided');
                return;
            }

            try {
                const result = await TopicApiClient.getTopicsByCourse(courseId);

                if (!result.success) {
                    console.error('Failed to load topics:', result.message);
                    return;
                }

                const topics = result.data || [];
                console.log('Topics loaded:', topics);

                // Store topics for later use
                window.courseTopics = topics;

                // Display topics in the curriculum accordion
                displayTopics(topics);
            } catch (error) {
                console.error('Error loading topics:', error);
            }
        }

        // Display topics in the curriculum accordion
        function displayTopics(topics) {
            const accordion = document.getElementById('curriculumAccordion');
            if (!accordion) return;

            // Clear all existing accordion items
            accordion.innerHTML = '';

            // If no topics, show a message
            if (topics.length === 0) {
                accordion.innerHTML = '<p class="text-muted">No topics yet. Click "Add New Topic" to create one.</p>';
                return;
            }

            // Add each topic to the accordion
            topics.forEach((topic, index) => {
                const topicHtml = createTopicAccordionItem(topic, index);
                accordion.insertAdjacentHTML('beforeend', topicHtml);
            });

            // Attach event listeners to the new items
            attachTopicEventListeners();
        }

        // Create HTML for a topic accordion item
        function createTopicAccordionItem(topic, index) {
            const collapseId = `collapse-topic-${topic.id}`;
            const lessonsHtml = topic.lessons && topic.lessons.length > 0 ?
                topic.lessons.map((lesson, lessonIndex) => `
                    <div class="lesson-item draggable-lesson" draggable="true" data-lesson-id="${lesson.id}" data-topic-id="${topic.id}" data-lesson-index="${lessonIndex}">
                        <div class="lesson-item-content">
                            <i class="fa-solid fa-grip-vertical lesson-drag-handle" title="Drag to reorder"></i>
                            <i class="fa-solid fa-circle-play"></i>
                            <span>${lesson.title}</span>
                        </div>
                        <div class="lesson-item-actions">
                            <button type="button" title="Edit" class="edit-lesson-btn" data-lesson-id="${lesson.id}"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button type="button" title="Delete" class="delete-lesson-btn" data-lesson-id="${lesson.id}"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                `).join('') :
                '<p class="text-muted">No lessons yet. Click "Add Lesson" to create one.</p>';

            return `
                <div class="accordion-item draggable-topic" draggable="true" data-topic-id="${topic.id}" data-topic-index="${index}">
                    <h2 class="accordion-header" style="position:relative; padding:1rem">
                        <div class="mb-2 d-flex gap-2 align-items-center justify-content-end">
                            <button class="btn btn-sm btn-light edit-topic-btn" type="button" title="Edit" data-topic-id="${topic.id}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-sm btn-light delete-topic-btn" type="button" title="Delete" data-topic-id="${topic.id}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                        <button class="accordion-button d-flex justify-content-between align-items-center" type="button"
                            data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false"
                            aria-controls="${collapseId}">
                            <div class="d-flex align-items-start me-3">
                                <i class="fa-solid fa-grip-vertical drag-handle" title="Drag to reorder"></i>
                                <i class="fa-solid fa-book-open me-3"></i>
                                <p class="m-0 fw-bold">${topic.title}</p>
                            </div>
                        </button>
                        ${topic.description ? `<p class="pt-2">${topic.description}</p>` : ''}
                    </h2>
                    <div id="${collapseId}" class="accordion-collapse collapse" data-bs-parent="#curriculumAccordion">
                        <div class="accordion-body">
                            ${lessonsHtml}
                            <button type="button" class="btn btn-add-lesson mt-3 add-lesson-btn" data-topic-id="${topic.id}">
                                <i class="fa-solid fa-plus me-2"></i>Add Lesson
                            </button>
                             <button
      type="button"
      class="btn btn-add-lesson mt-3"
      data-bs-toggle="modal"
      data-bs-target="#quiz-modal"
    >
      <i class="fa-solid fa-plus me-2"></i>Add Quiz
    </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Attach event listeners to topic and lesson buttons
        function attachTopicEventListeners() {
            // Edit topic buttons
            document.querySelectorAll('.edit-topic-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const topicId = btn.getAttribute('data-topic-id');
                    editTopic(topicId);
                });
            });

            // Delete topic buttons
            document.querySelectorAll('.delete-topic-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const topicId = btn.getAttribute('data-topic-id');
                    deleteTopic(topicId);
                });
            });

            // Add lesson buttons
            document.querySelectorAll('.add-lesson-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const topicId = btn.getAttribute('data-topic-id');
                    addLesson(topicId);
                });
            });

            // Edit lesson buttons
            document.querySelectorAll('.edit-lesson-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const lessonId = btn.getAttribute('data-lesson-id');
                    editLesson(lessonId);
                });
            });

            // Delete lesson buttons
            document.querySelectorAll('.delete-lesson-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const lessonId = btn.getAttribute('data-lesson-id');
                    deleteLesson(lessonId);
                });
            });

            // Drag and drop for topics
            document.querySelectorAll('.draggable-topic').forEach(topic => {
                topic.addEventListener('dragstart', handleTopicDragStart);
                topic.addEventListener('dragend', handleTopicDragEnd);
                topic.addEventListener('dragover', handleTopicDragOver);
                topic.addEventListener('drop', handleTopicDrop);
                topic.addEventListener('dragleave', handleTopicDragLeave);
            });

            // Drag and drop for lessons
            document.querySelectorAll('.draggable-lesson').forEach(lesson => {
                lesson.addEventListener('dragstart', handleLessonDragStart);
                lesson.addEventListener('dragend', handleLessonDragEnd);
                lesson.addEventListener('dragover', handleLessonDragOver);
                lesson.addEventListener('drop', handleLessonDrop);
                lesson.addEventListener('dragleave', handleLessonDragLeave);
            });

            // Prevent accordion from closing during drag by preventing clicks on drag handles
            document.querySelectorAll('.drag-handle').forEach(handle => {
                handle.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
                handle.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            document.querySelectorAll('.lesson-drag-handle').forEach(handle => {
                handle.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
                handle.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            // Prevent accordion button from closing during drag
            document.querySelectorAll('.accordion-button').forEach(btn => {
                btn.addEventListener('mousedown', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
                btn.addEventListener('click', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
            });
        }

        // Load dropdown data
        async function loadDropdownData() {
            return new Promise(async (resolve) => {
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

                    resolve();
                } catch (error) {
                    console.error('Error loading dropdown data:', error);
                    resolve();
                }
            });
        }

        // Navigation between sections
        document.addEventListener('DOMContentLoaded', async () => {
            const navButtons = document.querySelectorAll('.coursebtn');
            const sections = document.querySelectorAll('.content-section');
            const continueButtons = document.querySelectorAll('.continue-btn');
            const backButtons = document.querySelectorAll('.back-btn');

            // Load dropdown data first
            await loadDropdownData();

            // Load course data if course ID is provided (after dropdowns are loaded)
            if (courseId) {
                await loadCourseData();
                // Load topics for the course
                await loadTopics();
                // Populate the publish section with course data
                populatePublishSection();
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

            // addlessong modal js - Note: selectContainer is no longer used with simplified lesson modal
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

            // Only attach event listeners if selectContainer exists
            if (selectContainer) {
                selectContainer.addEventListener("change", (e) => {
                    showSelectedContainer(e.target.value);
                });
            }

            //select quiz logic
            const selectQuizContainer = document.getElementById("quiz-choice");

            function showSelectedQuizContainer(quizType) {
                // Hide all quiz containers
                document.querySelectorAll(".select-quiz-children").forEach((container) => {
                    container.style.display = "none";
                });

                // Show only the selected container
                if (quizType === "multiple-choice") {
                    document.getElementById("multiple-choice-container").style.display = "flex";
                } else if (quizType === "alternative-choice") {
                    document.getElementById("alternative-choice-container").style.display = "flex";
                }
            }
            showSelectedQuizContainer(selectQuizContainer.value);

            // Only attach event listeners if selectContainer exists
            if (selectQuizContainer) {
                selectQuizContainer.addEventListener("change", (e) => {
                    showSelectedQuizContainer(e.target.value);
                });
            }

            function populatePublishSection() {
                // Get data from form fields with correct IDs
                const titleElement = document.getElementById('courseTitle');
                const categoryElement = document.getElementById('courseCategory');
                const levelElement = document.getElementById('courseLevel');
                const timeElement = document.getElementById('courseTime');
                const descriptionElement = document.getElementById('courseDescription');
                const fileInput = document.getElementById('fileInput');

                const title = titleElement ? titleElement.value : 'English Language';

                // Get category name from selected option
                let category = 'Language';
                if (categoryElement && categoryElement.selectedOptions && categoryElement.selectedOptions[0]) {
                    category = categoryElement.selectedOptions[0].text;
                } else if (window.courseData && window.courseData.course_category && window.courseData
                    .course_category.title) {
                    category = window.courseData.course_category.title;
                }

                // Get level name from selected option
                let level = 'JSS 1';
                if (levelElement && levelElement.selectedOptions && levelElement.selectedOptions[0]) {
                    level = levelElement.selectedOptions[0].text;
                } else if (window.courseData && window.courseData.level && window.courseData.level.name) {
                    level = window.courseData.level.name;
                }

                const time = timeElement ? timeElement.value : '0 Hours';
                const description = descriptionElement ? descriptionElement.value :
                    'This comprehensive course covers essential concepts and skills.';

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

                // Update course image
                if (publishImageEl) {
                    // If a new file is selected, use the file preview
                    if (fileInput && fileInput.files && fileInput.files[0]) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            publishImageEl.src = e.target.result;
                        };
                        reader.readAsDataURL(fileInput.files[0]);
                    }
                    // Otherwise, use the initial thumbnail from the database
                    else if (window.courseData && window.courseData.thumbnail_url) {
                        publishImageEl.src = window.courseData.thumbnail_url;
                    }
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

            // Free course checkbox handler
            const freeCourseCheckbox = document.getElementById('free-course');
            const priceInput = document.getElementById('coursePrice');
            if (freeCourseCheckbox) {
                freeCourseCheckbox.addEventListener('change', (e) => {
                    if (priceInput) {
                        priceInput.disabled = e.target.checked;
                        if (e.target.checked) {
                            priceInput.value = '0';
                        }
                    }
                });
            }

            // Update course function
            async function updateCourse(newStatus = null) {
                try {
                    // Get form data
                    const title = document.getElementById('courseTitle').value;
                    const description = document.getElementById('courseDescription').value;
                    const courseCategory = document.getElementById('courseCategory').value;
                    const courseLevel = document.getElementById('courseLevel').value;
                    const term = document.getElementById('subjectTerm').value;
                    const duration = document.getElementById('courseTime').value;
                    const price = document.getElementById('coursePrice').value;
                    const freeCourse = document.getElementById('free-course').checked;
                    const overviewUrl = document.getElementById('overviewVideoUrl').value;

                    // Validate required fields
                    if (!title || !description || !courseCategory || !courseLevel) {
                        ToastNotification.warning('Validation Error', 'Please fill in all required fields');
                        return;
                    }

                    // Create FormData for file upload support
                    const formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('title', title);
                    formData.append('description', description);
                    formData.append('course_category_id', courseCategory);
                    formData.append('level_id', courseLevel);
                    formData.append('term_id', term || null);
                    formData.append('duration_hours', duration || 0);
                    formData.append('price', freeCourse ? 0 : price);
                    formData.append('free', freeCourse ? 1 : 0);
                    formData.append('url', overviewUrl);

                    // Only set status if newStatus is provided (for Save As Draft or Publish)
                    if (newStatus) {
                        formData.append('status', newStatus);
                    }

                    // Add thumbnail if a new file is selected
                    const fileInput = document.getElementById('fileInput');
                    if (fileInput && fileInput.files && fileInput.files[0]) {
                        formData.append('thumbnail', fileInput.files[0]);
                    }

                    // Debug: Log FormData contents
                    console.log('FormData contents:');
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    // Use fetch to update course (FormData works better with fetch)
                    const token = localStorage.getItem('auth_token');
                    const response = await fetch(`/api/courses/${courseId}`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        console.error('Update error:', result);
                        console.error('Validation errors:', result.errors);

                        // Show validation errors if available
                        if (result.errors && Object.keys(result.errors).length > 0) {
                            const errorMessages = Object.values(result.errors)
                                .flat()
                                .join(', ');
                            ToastNotification.error('Validation Error', errorMessages);
                        } else {
                            ToastNotification.error('Update Failed', result.message ||
                                'Failed to update course');
                        }
                        return;
                    }

                    console.log('Course updated successfully:', result);

                    // Show success message based on action
                    if (newStatus === 'published') {
                        ToastNotification.success('Success', 'Course published successfully!');
                    } else if (newStatus === 'draft') {
                        ToastNotification.success('Success', 'Course saved as draft!');
                    } else {
                        ToastNotification.success('Success', 'Course updated successfully!');
                    }

                    // Reload course data to reflect changes
                    await loadCourseData();
                } catch (error) {
                    console.error('Error updating course:', error);
                    ToastNotification.error('Error', 'An error occurred while updating the course');
                }
            }

            // Header button handlers
            const headerUpdateBtn = document.getElementById('updateBtn');
            if (headerUpdateBtn) {
                headerUpdateBtn.addEventListener('click', () => {
                    // Update course without changing status
                    updateCourse(null);
                });
            }

            const headerSaveDraftBtn = document.getElementById('saveDraftBtn');
            if (headerSaveDraftBtn) {
                headerSaveDraftBtn.addEventListener('click', () => {
                    // Save as draft (change status to draft)
                    updateCourse('draft');
                });
            }

            const headerPublishBtn = document.getElementById('publishBtn');
            if (headerPublishBtn) {
                headerPublishBtn.addEventListener('click', () => {
                    // Publish course (change status to published)
                    updateCourse('published');
                });
            }

            // Publish button handler (from publish section)
            const finalPublishBtn = document.getElementById('finalPublishBtn');
            if (finalPublishBtn) {
                finalPublishBtn.addEventListener('click', () => {
                    updateCourse('published');
                });
            }

            // Add new topic button handler
            const addTopicBtn = document.querySelector('[data-bs-target="#addNewTopicModal"]');
            if (addTopicBtn) {
                addTopicBtn.addEventListener('click', () => {
                    // Reset form
                    const form = document.querySelector('#addNewTopicModal form');
                    if (form) form.reset();
                    // Store that we're adding a new topic (not editing)
                    window.editingTopicId = null;
                });
            }

            // Save topic button handler - use a more specific selector
            const topicModal = document.getElementById('addNewTopicModal');
            if (topicModal) {
                const saveTopicBtn = topicModal.querySelector('.modal-form-btn');
                if (saveTopicBtn) {
                    saveTopicBtn.addEventListener('click', async (e) => {
                        e.preventDefault();
                        await saveTopicHandler();
                    });
                }
            }

            showSection('curriculum');
        });

        // ===== Drag and Drop Handlers for Topics =====
        let draggedTopic = null;
        let isDragging = false;
        let dragStartTime = 0;

        function handleTopicDragStart(e) {
            isDragging = true;
            dragStartTime = Date.now();
            draggedTopic = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
            e.stopPropagation();
        }

        function handleTopicDragEnd(e) {
            // Keep isDragging true for a brief moment to prevent accordion click
            setTimeout(() => {
                isDragging = false;
            }, 100);
            this.classList.remove('dragging');
            document.querySelectorAll('.draggable-topic').forEach(topic => {
                topic.classList.remove('drag-over');
            });
        }

        function handleTopicDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            e.dataTransfer.dropEffect = 'move';

            if (this !== draggedTopic) {
                this.classList.add('drag-over');
            }
            return false;
        }

        function handleTopicDragLeave(e) {
            this.classList.remove('drag-over');
        }

        async function handleTopicDrop(e) {
            if (e.stopPropagation) {
                e.stopPropagation();
            }

            if (draggedTopic !== this) {
                const draggedTopicId = draggedTopic.getAttribute('data-topic-id');
                const targetTopicId = this.getAttribute('data-topic-id');

                try {
                    // Swap the topics in the array
                    const draggedIndex = window.courseTopics.findIndex(t => t.id == draggedTopicId);
                    const targetIndex = window.courseTopics.findIndex(t => t.id == targetTopicId);

                    if (draggedIndex !== -1 && targetIndex !== -1) {
                        // Swap order values
                        const temp = window.courseTopics[draggedIndex].order;
                        window.courseTopics[draggedIndex].order = window.courseTopics[targetIndex].order;
                        window.courseTopics[targetIndex].order = temp;

                        // Update both topics on the server
                        await TopicApiClient.updateTopic(draggedTopicId, {
                            order: window.courseTopics[draggedIndex].order
                        });
                        await TopicApiClient.updateTopic(targetTopicId, {
                            order: window.courseTopics[targetIndex].order
                        });

                        // Reload topics to refresh the display
                        await loadTopics();
                        ToastNotification.success('Success', 'Topic order updated');
                    }
                } catch (error) {
                    console.error('Error updating topic order:', error);
                    ToastNotification.error('Error', 'Failed to update topic order');
                }
            }

            this.classList.remove('drag-over');
            return false;
        }

        // ===== Drag and Drop Handlers for Lessons =====
        let draggedLesson = null;

        function handleLessonDragStart(e) {
            isDragging = true;
            dragStartTime = Date.now();
            draggedLesson = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
            e.stopPropagation();
        }

        function handleLessonDragEnd(e) {
            // Keep isDragging true for a brief moment to prevent accordion click
            setTimeout(() => {
                isDragging = false;
            }, 100);
            this.classList.remove('dragging');
            document.querySelectorAll('.draggable-lesson').forEach(lesson => {
                lesson.classList.remove('drag-over');
            });
        }

        function handleLessonDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            e.dataTransfer.dropEffect = 'move';

            if (this !== draggedLesson) {
                this.classList.add('drag-over');
            }
            return false;
        }

        function handleLessonDragLeave(e) {
            this.classList.remove('drag-over');
        }

        async function handleLessonDrop(e) {
            if (e.stopPropagation) {
                e.stopPropagation();
            }

            if (draggedLesson !== this) {
                const draggedLessonId = draggedLesson.getAttribute('data-lesson-id');
                const draggedTopicId = draggedLesson.getAttribute('data-topic-id');
                const targetLessonId = this.getAttribute('data-lesson-id');
                const targetTopicId = this.getAttribute('data-topic-id');

                try {
                    // Only allow reordering within the same topic
                    if (draggedTopicId === targetTopicId) {
                        const topic = window.courseTopics.find(t => t.id == draggedTopicId);
                        if (topic && topic.lessons) {
                            const draggedIndex = topic.lessons.findIndex(l => l.id == draggedLessonId);
                            const targetIndex = topic.lessons.findIndex(l => l.id == targetLessonId);

                            if (draggedIndex !== -1 && targetIndex !== -1) {
                                // Swap order values
                                const temp = topic.lessons[draggedIndex].order;
                                topic.lessons[draggedIndex].order = topic.lessons[targetIndex].order;
                                topic.lessons[targetIndex].order = temp;

                                // Update both lessons on the server
                                await LessonApiClient.updateLesson(draggedLessonId, {
                                    order: topic.lessons[draggedIndex].order
                                });
                                await LessonApiClient.updateLesson(targetLessonId, {
                                    order: topic.lessons[targetIndex].order
                                });

                                // Reload topics to refresh the display
                                await loadTopics();
                                ToastNotification.success('Success', 'Lesson order updated');
                            }
                        }
                    } else {
                        ToastNotification.warning('Info', 'Lessons can only be reordered within the same topic');
                    }
                } catch (error) {
                    console.error('Error updating lesson order:', error);
                    ToastNotification.error('Error', 'Failed to update lesson order');
                }
            }

            this.classList.remove('drag-over');
            return false;
        }

        // Topic management functions
        async function editTopic(topicId) {
            try {
                const topic = window.courseTopics.find(t => t.id == topicId);
                if (!topic) {
                    ToastNotification.error('Error', 'Topic not found');
                    return;
                }

                // Populate modal with topic data
                const modal = document.getElementById('addNewTopicModal');
                const titleInput = modal.querySelector('input[placeholder="Enter Title"]');
                const descInput = modal.querySelector('textarea');

                if (titleInput) titleInput.value = topic.title;
                if (descInput) descInput.value = topic.description || '';

                // Store the topic ID being edited
                window.editingTopicId = topicId;

                // Show modal
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            } catch (error) {
                console.error('Error editing topic:', error);
                ToastNotification.error('Error', 'Failed to load topic');
            }
        }

        async function deleteTopic(topicId) {
            if (!confirm(
                    'Are you sure you want to delete this topic? All lessons in this topic will also be deleted.')) {
                return;
            }

            try {
                const result = await TopicApiClient.deleteTopic(topicId);
                ToastNotification.success('Success', 'Topic deleted successfully');
                await loadTopics();
            } catch (error) {
                console.error('Error deleting topic:', error);
                ToastNotification.error('Error', 'Failed to delete topic');
            }
        }

        async function saveTopicHandler() {
            try {
                const modal = document.getElementById('addNewTopicModal');
                const titleInput = modal.querySelector('input[placeholder="Enter Title"]');
                const descInput = modal.querySelector('textarea');

                const title = titleInput ? titleInput.value.trim() : '';
                const description = descInput ? descInput.value.trim() : '';

                if (!title) {
                    ToastNotification.warning('Validation', 'Please enter a topic title');
                    return;
                }

                const topicData = {
                    title: title,
                    description: description,
                    course_id: courseId,
                    order: window.courseTopics ? window.courseTopics.length + 1 : 1
                };

                let result;
                if (window.editingTopicId) {
                    // Update existing topic
                    result = await TopicApiClient.updateTopic(window.editingTopicId, topicData);
                    ToastNotification.success('Success', 'Topic updated successfully');
                } else {
                    // Create new topic
                    result = await TopicApiClient.createTopic(topicData);
                    ToastNotification.success('Success', 'Topic created successfully');
                }

                // Close modal
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) modalInstance.hide();

                // Reload topics
                await loadTopics();
            } catch (error) {
                console.error('Error saving topic:', error);
                ToastNotification.error('Error', 'Failed to save topic');
            }
        }

        window.addLesson = function(topicId) {
            // Store the topic ID for the lesson
            window.currentTopicId = topicId;
            // Clear the form
            const modal = document.getElementById('addLessonModal');
            if (!modal) {
                ToastNotification.error('Error', 'Lesson modal not found');
                return;
            }
            const titleInput = modal.querySelector('input[placeholder="Enter title"]');
            const lessonTypeSelect = document.getElementById('addContent');

            if (titleInput) titleInput.value = '';

            // Reset lesson type to default (youtube)
            if (lessonTypeSelect) {
                lessonTypeSelect.value = 'youtube';
                // Trigger change event to show the correct container
                lessonTypeSelect.dispatchEvent(new Event('change'));
            }

            // Clear all input fields
            const allInputs = modal.querySelectorAll('input[type="text"], textarea');
            allInputs.forEach(input => input.value = '');

            window.editingLessonId = null;
            // Show the add lesson modal
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        };

        // Handle lesson type selection
        const lessonTypeSelect = document.getElementById('addContent');
        if (lessonTypeSelect) {
            lessonTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;

                // Hide all containers first
                document.getElementById('image-container').style.display = 'none';
                document.getElementById('youtube-container').classList.add('hide');
                document.getElementById('content-container').classList.add('hide');
                document.getElementById('audio-container').classList.add('hide');
                document.getElementById('document-container').classList.add('hide');

                // Show the selected container
                switch (selectedType) {
                    case 'youtube':
                        document.getElementById('youtube-container').classList.remove('hide');
                        break;
                    case 'content':
                        document.getElementById('content-container').classList.remove('hide');
                        break;
                    case 'document':
                        document.getElementById('document-container').classList.remove('hide');
                        break;
                    case 'image':
                        document.getElementById('image-container').style.display = 'flex';
                        break;
                    default:
                        break;
                }
            });
        }

        // Handle file upload buttons
        const uploadButtons = document.querySelectorAll('.upload-btn');
        uploadButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const uploadType = this.getAttribute('data-upload-type');
                const fileInputId = uploadType + 'FileInput';
                const fileInput = document.getElementById(fileInputId);

                if (fileInput) {
                    fileInput.click();
                }
            });
        });

        // Handle file input changes
        const imageFileInput = document.getElementById('imageFileInput');
        if (imageFileInput) {
            imageFileInput.addEventListener('change', function(e) {
                handleFileUpload(e, 'image');
            });
        }

        const audioFileInput = document.getElementById('audioFileInput');
        if (audioFileInput) {
            audioFileInput.addEventListener('change', function(e) {
                handleFileUpload(e, 'audio');
            });
        }

        const documentFileInput = document.getElementById('documentFileInput');
        if (documentFileInput) {
            documentFileInput.addEventListener('change', function(e) {
                handleFileUpload(e, 'document');
            });
        }

        // Function to handle file uploads
        function handleFileUpload(event, uploadType) {
            const file = event.target.files[0];
            if (!file) return;

            // Get the corresponding input field to display the file name
            const modal = document.getElementById('addLessonModal');
            let inputField;

            switch (uploadType) {
                case 'image':
                    inputField = modal.querySelector('#image-container input[type="text"]');
                    break;
                case 'audio':
                    inputField = modal.querySelector('#audio-container input[type="text"]');
                    break;
                case 'document':
                    inputField = modal.querySelector('#document-container input[type="text"]');
                    break;
            }

            if (inputField) {
                inputField.value = file.name;
                // Store the file object for later upload
                inputField.dataset.file = file;
            }

            // Show success message
            ToastNotification.success('Success', `File "${file.name}" selected successfully`);
        }

        window.saveLessonHandler = async function() {
            try {
                const modal = document.getElementById('addLessonModal');
                if (!modal) {
                    ToastNotification.error('Error', 'Lesson modal not found');
                    return;
                }

                const titleInput = modal.querySelector('input[placeholder="Enter title"]');
                const lessonTypeSelect = document.getElementById('addContent');
                const lessonType = lessonTypeSelect ? lessonTypeSelect.value : 'content';

                const title = titleInput ? titleInput.value.trim() : '';

                if (!title) {
                    ToastNotification.warning('Validation', 'Please enter a lesson title');
                    return;
                }

                // Use FormData to support file uploads
                const formData = new FormData();
                formData.append('title', title);
                formData.append('topic_id', window.currentTopicId);
                formData.append('course_id', courseId);
                formData.append('lesson_type', lessonType);

                // Collect data based on lesson type
                switch (lessonType) {
                    case 'youtube':
                        const youtubeInput = modal.querySelector('#youtube-container input[type="text"]');
                        const youtubeUrl = youtubeInput ? youtubeInput.value.trim() : '';
                        if (!youtubeUrl) {
                            ToastNotification.warning('Validation', 'Please enter a YouTube URL');
                            return;
                        }
                        formData.append('video_url', youtubeUrl);
                        break;

                    case 'content':
                        const contentInput = modal.querySelector('#content-container textarea');
                        const content = contentInput ? contentInput.value.trim() : '';
                        if (!content) {
                            ToastNotification.warning('Validation', 'Please enter lesson content');
                            return;
                        }
                        formData.append('content', content);
                        break;

                    case 'document':
                        const documentInput = modal.querySelector('#document-container input[type="text"]');
                        const documentFile = documentInput ? documentInput.dataset.file : null;
                        if (!documentFile) {
                            ToastNotification.warning('Validation', 'Please upload a document');
                            return;
                        }
                        formData.append('attachment', documentFile);
                        break;

                    case 'image':
                        const imageInput = modal.querySelector('#image-container input[type="text"]');
                        const imageFile = imageInput ? imageInput.dataset.file : null;
                        if (!imageFile) {
                            ToastNotification.warning('Validation', 'Please upload an image');
                            return;
                        }
                        formData.append('attachment', imageFile);
                        break;
                }

                let result;
                if (window.editingLessonId) {
                    // Update existing lesson
                    formData.append('_method', 'PUT');
                    result = await LessonApiClient.updateLesson(window.editingLessonId, formData);
                    ToastNotification.success('Success', 'Lesson updated successfully');
                } else {
                    // Create new lesson
                    result = await LessonApiClient.createLesson(courseId, formData);
                    ToastNotification.success('Success', 'Lesson created successfully');
                }

                // Close modal
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) modalInstance.hide();

                // Reload topics to refresh lessons
                await loadTopics();
            } catch (error) {
                console.error('Error saving lesson:', error);
                ToastNotification.error('Error', 'Failed to save lesson');
            }
        };

        window.editLesson = async function(lessonId) {
            try {
                const topic = window.courseTopics.find(t =>
                    t.lessons && t.lessons.some(l => l.id == lessonId)
                );

                if (!topic) {
                    ToastNotification.error('Error', 'Lesson not found');
                    return;
                }

                const lesson = topic.lessons.find(l => l.id == lessonId);
                const modal = document.getElementById('addLessonModal');
                const titleInput = modal.querySelector('input[placeholder="Enter title"]');
                const lessonTypeSelect = document.getElementById('addContent');

                if (titleInput) titleInput.value = lesson.title;

                // Set lesson type based on lesson data
                let lessonType = lesson.lesson_type || 'content';
                if (lessonTypeSelect) {
                    lessonTypeSelect.value = lessonType;
                    // Trigger change event to show the correct container
                    lessonTypeSelect.dispatchEvent(new Event('change'));
                }

                // Populate the appropriate field based on lesson type
                switch (lessonType) {
                    case 'youtube':
                        const youtubeInput = modal.querySelector('#youtube-container input[type="text"]');
                        if (youtubeInput) youtubeInput.value = lesson.video_url || '';
                        break;
                    case 'content':
                        const contentInput = modal.querySelector('#content-container textarea');
                        if (contentInput) contentInput.value = lesson.content || '';
                        break;
                    case 'document':
                    case 'image':
                        const attachmentInput = modal.querySelector(`#${lessonType}-container input[type="text"]`);
                        if (attachmentInput) attachmentInput.value = lesson.attachment || '';
                        break;
                }

                window.editingLessonId = lessonId;
                window.currentTopicId = topic.id;
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            } catch (error) {
                console.error('Error editing lesson:', error);
                ToastNotification.error('Error', 'Failed to load lesson');
            }
        };

        window.deleteLesson = async function(lessonId) {
            if (!confirm('Are you sure you want to delete this lesson?')) {
                return;
            }

            try {
                await LessonApiClient.deleteLesson(lessonId);
                ToastNotification.success('Success', 'Lesson deleted successfully');
                await loadTopics();
            } catch (error) {
                console.error('Error deleting lesson:', error);
                ToastNotification.error('Error', 'Failed to delete lesson');
            }
        };
    </script>
@endsection
