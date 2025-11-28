@extends('layouts.dashboardtemp')

@section('content')
<main class="levels-main">
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
            padding: 16px !important;
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
        .levels-main {
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
            font-size: 16px;
        }

        .add-btn:hover {
            background-color: #e59a0f;
        }

        /* ===== CARDS GRID ===== */
        .levels-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .level-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            text-align: center;
        }

        .level-card:hover {
            box-shadow: 0 4px 16px rgba(0, 74, 83, 0.1);
            border-color: #004A53;
        }

        .level-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .level-card-title {
            font-size: 20px;
            color: #004A53;
            font-weight: 600;
            margin: 0;
        }

        .level-card-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            background-color: white;
            border: 1px solid #ddd;
            color: #666;
            width: 26px;
            height: 26px;
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

        .level-card-description {
            color: #666;
            font-size: 13px;
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

            .levels-grid {
                grid-template-columns: repeat(2, 1fr);
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

            .levels-grid {
                grid-template-columns: 1fr;
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
                <h1 class="text-white">Levels & Classes</h1>
                <p>Manage education levels (JS1-SS3, Grade 1-11, 100level-500level)</p>
            </div>
            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addLevelModal">
                <i class="fa-solid fa-plus"></i> Add Level
            </button>
        </div>

        <!-- Levels Grid -->
        <div class="levels-grid" id="levelsGrid">
            <!-- Cards will be populated by JavaScript -->
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addLevelModal" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="modalTitle">Add Level</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-form-container" id="levelForm">
                    <div class="modal-form">
                        <div class="modal-form-input-border">
                            <label for="levelName" class="modal-label">Level Name</label>
                            <input class="modal-input" type="text" id="levelName" placeholder="e.g., JS1" required/>
                        </div>
                        <div class="modal-form-input-border">
                            <label for="levelType" class="modal-label">Level Type</label>
                            <textarea id="levelType"  class="modal-input" placeholder="Enter level type" ></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-custom">Save Level</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Delete Level</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this level? This action cannot be undone.</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger-custom" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample data
        const levels = [
            { id: 1, name: 'JS1', description: 'Junior Secondary 1' },
            { id: 2, name: 'JS2', description: 'Junior Secondary 2' },
            { id: 3, name: 'JS3', description: 'Junior Secondary 3' },
            { id: 4, name: 'SS1', description: 'Senior Secondary 1' },
            { id: 5, name: 'SS2', description: 'Senior Secondary 2' },
            { id: 6, name: 'SS3', description: 'Senior Secondary 3' },
            { id: 7, name: 'Grade 1', description: 'Primary Grade 1' },
            { id: 8, name: 'Grade 6', description: 'Primary Grade 6' },
            { id: 9, name: '100level', description: 'University 100 Level' },
            { id: 10, name: '500level', description: 'University 500 Level' }
        ];

        let currentEditId = null;
        let currentDeleteId = null;

        // Render levels
        function renderLevels() {
            const grid = document.getElementById('levelsGrid');
            grid.innerHTML = levels.map(level => `
                <div class="level-card">
                    <div class="level-card-header">
                        <h3 class="level-card-title">${level.name}</h3>
                        <div class="level-card-actions">
                            <button class="action-btn" onclick="editLevel(${level.id})" title="Edit">
                                <i class="fa-solid fa-pen fa-xs"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteLevel(${level.id})" title="Delete">
                                <i class="fa-solid fa-trash fa-xs"></i>
                            </button>
                        </div>
                    </div>
                    <p class="level-card-description">${level.description}</p>
                </div>
            `).join('');
        }

        // Add/Edit level
        document.getElementById('levelForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('levelName').value;
            const description = document.getElementById('levelType').value;

            if (currentEditId) {
                const level = levels.find(l => l.id === currentEditId);
                if (level) {
                    level.name = name;
                    level.description = description;
                }
                currentEditId = null;
            } else {
                levels.push({
                    id: Math.max(...levels.map(l => l.id), 0) + 1,
                    name,
                    description
                });
            }

            renderLevels();
            document.getElementById('levelForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('addLevelModal')).hide();
        });

        // Edit level
        function editLevel(id) {
            const level = levels.find(l => l.id === id);
            if (level) {
                currentEditId = id;
                document.getElementById('levelName').value = level.name;
                document.getElementById('levelType').value = level.description;
                document.getElementById('modalTitle').textContent = 'Edit Level';
                new bootstrap.Modal(document.getElementById('addLevelModal')).show();
            }
        }

        // Delete level
        function deleteLevel(id) {
            currentDeleteId = id;
            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            const index = levels.findIndex(l => l.id === currentDeleteId);
            if (index > -1) {
                levels.splice(index, 1);
                renderLevels();
            }
            bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
        });

        // Reset modal on close
        document.getElementById('addLevelModal').addEventListener('hidden.bs.modal', () => {
            currentEditId = null;
            document.getElementById('modalTitle').textContent = 'Add Level';
            document.getElementById('levelForm').reset();
        });

        // Initial render
        renderLevels();
    </script>
</main>
@endsection

