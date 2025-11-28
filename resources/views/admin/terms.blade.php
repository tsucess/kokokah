@extends('layouts.dashboardtemp')

@section('content')
    <main class="terms-main">
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
            .terms-main {
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
            .terms-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }

            .term-card {
                background: white;
                border: 1px solid #e0e0e0;
                border-radius: 12px;
                padding: 20px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
            }

            .term-card:hover {
                box-shadow: 0 4px 16px rgba(0, 74, 83, 0.1);
                border-color: #004A53;
            }

            .term-card-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 15px;
            }

            .term-card-title {
                font-size: 18px;
                color: #004A53;
                font-weight: 600;
                margin: 0;
            }

            .term-card-actions {
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

            .term-card-info {
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

                .terms-grid {
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
                    <h1 class="text-white">Academic Terms</h1>
                    <p>Manage academic terms (First Term, Second Term, Third Term)</p>
                </div>
                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addTermModal">
                    <i class="fa-solid fa-plus"></i> Add Term
                </button>
            </div>

            <!-- Terms Grid -->
            <div class="terms-grid" id="termsGrid">
                <!-- Cards will be populated by JavaScript -->
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div class="modal fade" id="addTermModal"  data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="modalTitle">Add Term</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form class="modal-form-container" id="termForm">
                        <div class="modal-form">
                            <div class="modal-form-input-border">
                                <label for="termName" class="modal-label">Term Name</label>
                                <input type="text" class="modal-input" id="termName" placeholder="e.g., First Term"
                                    required>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="startDate" class="modal-label">Start Date</label>
                                <input type="date" class="modal-input"
                                    id="startDate" required>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="endDate" class="modal-label">End Date</label>
                                <input type="date" class="modal-input" id="endDate" required>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary-custom">Save Term</button>
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
                        <h1 class="modal-title">Delete Term</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this term? This action cannot be undone.</p>
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
            const terms = [{
                    id: 1,
                    name: 'First Term',
                    startDate: '2024-01-15',
                    endDate: '2024-04-15'
                },
                {
                    id: 2,
                    name: 'Second Term',
                    startDate: '2024-05-01',
                    endDate: '2024-08-01'
                },
                {
                    id: 3,
                    name: 'Third Term',
                    startDate: '2024-09-01',
                    endDate: '2024-12-15'
                }
            ];

            let currentEditId = null;
            let currentDeleteId = null;

            // Format date for display
            function formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            // Render terms
            function renderTerms() {
                const grid = document.getElementById('termsGrid');
                grid.innerHTML = terms.map(term => `
                <div class="term-card">
                    <div class="term-card-header">
                        <h3 class="term-card-title">${term.name}</h3>
                        <div class="term-card-actions">
                            <button class="action-btn" onclick="editTerm(${term.id})" title="Edit">
                                <i class="fa-solid fa-pen fa-xs"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteTerm(${term.id})" title="Delete">
                                <i class="fa-solid fa-trash fa-xs"></i>
                            </button>
                        </div>
                    </div>
                    <p class="term-card-info">
                        <i class="fa-solid fa-calendar me-2" style="color: #004A53;"></i>
                        ${formatDate(term.startDate)} - ${formatDate(term.endDate)}
                    </p>
                </div>
            `).join('');
            }

            // Add/Edit term
            document.getElementById('termForm').addEventListener('submit', (e) => {
                e.preventDefault();
                const name = document.getElementById('termName').value;
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                if (currentEditId) {
                    const term = terms.find(t => t.id === currentEditId);
                    if (term) {
                        term.name = name;
                        term.startDate = startDate;
                        term.endDate = endDate;
                    }
                    currentEditId = null;
                } else {
                    terms.push({
                        id: Math.max(...terms.map(t => t.id), 0) + 1,
                        name,
                        startDate,
                        endDate
                    });
                }

                renderTerms();
                document.getElementById('termForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('addTermModal')).hide();
            });

            // Edit term
            function editTerm(id) {
                const term = terms.find(t => t.id === id);
                if (term) {
                    currentEditId = id;
                    document.getElementById('termName').value = term.name;
                    document.getElementById('startDate').value = term.startDate;
                    document.getElementById('endDate').value = term.endDate;
                    document.getElementById('modalTitle').textContent = 'Edit Term';
                    new bootstrap.Modal(document.getElementById('addTermModal')).show();
                }
            }

            // Delete term
            function deleteTerm(id) {
                currentDeleteId = id;
                new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
            }

            document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
                const index = terms.findIndex(t => t.id === currentDeleteId);
                if (index > -1) {
                    terms.splice(index, 1);
                    renderTerms();
                }
                bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
            });

            // Reset modal on close
            document.getElementById('addTermModal').addEventListener('hidden.bs.modal', () => {
                currentEditId = null;
                document.getElementById('modalTitle').textContent = 'Add Term';
                document.getElementById('termForm').reset();
            });

            // Initial render
            renderTerms();
        </script>
    </main>
@endsection
