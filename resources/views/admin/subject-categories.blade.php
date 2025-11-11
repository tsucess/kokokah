@extends('layouts.dashboardtemp')

@section('content')
<main class="subject-categories-main">
    <style>
        /* ===== BOOTSTRAP MODAL CUSTOMIZATION ===== */
        .modal-backdrop.show {
            background-color: rgba(0, 74, 83, 0.5) !important;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid #e0e0e0;
            padding: 20px;
        }

        .modal-title {
            font-family: "Fredoka One", sans-serif;
            color: #004A53;
            font-size: 22px;
            font-weight: 600;
        }

        .btn-close {
            color: #666;
            opacity: 1;
        }

        .btn-close:hover {
            color: #004A53;
        }

        .modal-body {
            padding: 30px 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #004A53;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 1.5px solid #004A53;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #FDAF22;
            box-shadow: 0 0 0 0.2rem rgba(253, 175, 34, 0.25);
            color: #333;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            gap: 10px;
        }

        .btn-primary-custom {
            background-color: #FDAF22;
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: #e59a0f;
            color: white;
        }

        .btn-secondary-custom {
            background-color: #6c757d;
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background-color: #5a6268;
            color: white;
        }

        .btn-danger-custom {
            background-color: #dc3545;
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-danger-custom:hover {
            background-color: #c82333;
            color: white;
        }

        /* ===== PAGE STYLES ===== */
        .subject-categories-main {
            background-color: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-text h1 {
            font-size: 32px;
            color: #004A53;
            font-family: 'Fredoka One', sans-serif;
            font-weight: 600;
            margin: 0 0 5px 0;
        }

        .header-text p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        .add-btn {
            background-color: #FDAF22;
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-btn:hover {
            background-color: #e59a0f;
        }

        /* ===== CARDS GRID ===== */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .category-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .category-card:hover {
            box-shadow: 0 4px 16px rgba(0, 74, 83, 0.1);
            border-color: #004A53;
        }

        .category-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .category-card-title {
            font-size: 18px;
            color: #004A53;
            font-weight: 600;
            margin: 0;
        }

        .category-card-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            background-color: white;
            border: 1px solid #ddd;
            color: #666;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            border-color: #004A53;
            color: #004A53;
            background-color: #f0f8f9;
        }

        .action-btn.delete:hover {
            border-color: #dc3545;
            color: #dc3545;
            background-color: #ffe5e5;
        }

        .category-card-description {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-text h1 {
                font-size: 24px;
            }

            .add-btn {
                width: 100%;
                justify-content: center;
            }

            .categories-grid {
                grid-template-columns: 1fr;
            }

            .modal-dialog {
                max-width: 95%;
                margin: 20px auto;
            }

            .modal-body {
                padding: 20px 15px;
            }

            .modal-title {
                font-size: 20px;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding: 0 10px;
            }

            .header-text h1 {
                font-size: 20px;
            }

            .modal-form-input-border {
                padding: 0px 15px 15px;
            }

            .modal-label {
                font-size: 12px;
            }

            .modal-input {
                font-size: 13px;
            }

            .modal-form-btn {
                font-size: 14px;
                padding: 12px 16px;
            }
        }
    </style>

    <div class="container-fluid px-4 py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-text">
                <h1>Subject Categories</h1>
                <p>Manage subject types (Art, Science, Commercial)</p>
            </div>
            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                <i class="fa-solid fa-plus"></i> Add Category
            </button>
        </div>

        <!-- Categories Grid -->
        <div class="categories-grid" id="subjectGrid">
            <!-- Cards will be populated by JavaScript -->
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addSubjectModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="modalTitle">Add Subject Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="categoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" placeholder="e.g., Science" required>
                        </div>
                        <div class="form-group">
                            <label for="categoryDescription" class="form-label">Subjects</label>
                            <input type="text" class="form-control" id="categoryDescription" placeholder="e.g., Physics, Chemistry, Biology">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-custom">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Delete Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this category? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger-custom" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample data
        const subjectCategories = [
            { id: 1, name: 'Science', description: 'Physics, Chemistry, Biology, Mathematics' },
            { id: 2, name: 'Art', description: 'English Language, Literature, History, Government' },
            { id: 3, name: 'Commercial', description: 'Accounting, Commerce, Economics' }
        ];

        let currentEditId = null;
        let currentDeleteId = null;

        // Render categories
        function renderCategories() {
            const grid = document.getElementById('subjectGrid');
            grid.innerHTML = subjectCategories.map(cat => `
                <div class="category-card">
                    <div class="category-card-header">
                        <h3 class="category-card-title">${cat.name}</h3>
                        <div class="category-card-actions">
                            <button class="action-btn" onclick="editCategory(${cat.id})" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteCategory(${cat.id})" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="category-card-description">${cat.description}</p>
                </div>
            `).join('');
        }

        // Add/Edit category
        document.getElementById('subjectForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('categoryName').value;
            const description = document.getElementById('categoryDescription').value;

            if (currentEditId) {
                const cat = subjectCategories.find(c => c.id === currentEditId);
                if (cat) {
                    cat.name = name;
                    cat.description = description;
                }
                currentEditId = null;
            } else {
                subjectCategories.push({
                    id: Math.max(...subjectCategories.map(c => c.id), 0) + 1,
                    name,
                    description
                });
            }

            renderCategories();
            document.getElementById('subjectForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('addSubjectModal')).hide();
        });

        // Edit category
        function editCategory(id) {
            const cat = subjectCategories.find(c => c.id === id);
            if (cat) {
                currentEditId = id;
                document.getElementById('categoryName').value = cat.name;
                document.getElementById('categoryDescription').value = cat.description;
                document.getElementById('modalTitle').textContent = 'Edit Subject Category';
                new bootstrap.Modal(document.getElementById('addSubjectModal')).show();
            }
        }

        // Delete category
        function deleteCategory(id) {
            currentDeleteId = id;
            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            const index = subjectCategories.findIndex(c => c.id === currentDeleteId);
            if (index > -1) {
                subjectCategories.splice(index, 1);
                renderCategories();
            }
            bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
        });

        // Reset modal on close
        document.getElementById('addSubjectModal').addEventListener('hidden.bs.modal', () => {
            currentEditId = null;
            document.getElementById('modalTitle').textContent = 'Add Subject Category';
            document.getElementById('subjectForm').reset();
        });

        // Initial render
        renderCategories();
    </script>
</main>
@endsection

