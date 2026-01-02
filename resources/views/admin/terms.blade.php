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

            /* Skeleton card styles */
            .skeleton-grid {
                display: grid;
                gap: 1rem;
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }

            .skeleton-card {
                padding: 1rem;
                border-radius: 8px;
                background: #f3f3f3;
                overflow: hidden;
                min-height: 72px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            }

            .skeleton-line {
                height: 14px;
                background: linear-gradient(90deg, #eee 25%, #e0e0e0 37%, #eee 63%);
                background-size: 400% 100%;
                animation: shimmer 1.2s linear infinite;
                border-radius: 4px;
                margin-bottom: 8px;
            }

            .skeleton-line.short {
                width: 60%;
            }

            .skeleton-line.mid {
                width: 80%;
            }

            @keyframes shimmer {
                0% {
                    background-position: 200% 0
                }

                100% {
                    background-position: -200% 0
                }
            }

            /* Toast container position */
            #toastContainer {
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 1080;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                pointer-events: none;
            }

            .toast {
                pointer-events: auto;
            }
        </style>

        <div class="container-fluid px-4 py-4 d-flex flex-column gap-5">
            <!-- Header Section -->
            <div class="d-flex flex-row align-items-center justify-content-between">
                <div class="header-text">
                    <h1>Academic Terms</h1>
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
        <div class="modal fade" id="addTermModal" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                        <h1 class="modal-title" id="modalTitle">Add Term</h1>
                        <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </div>

                    <form class="modal-form-container" id="termForm">
                        <div class="modal-form">
                            <div class="modal-form-input-border">
                                <label for="termName" class="modal-label">Term Name</label>
                                <input type="text" class="modal-input" id="termName" placeholder="e.g., First Term"
                                    required>
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

        <!-- ========== Place near top or bottom of page (paste these helpers once) ========== -->

        <!-- Toast container (paste once in your page) -->
        <div id="toastContainer" aria-live="polite" aria-atomic="true"></div>

        <!-- ========== End helpers ========== -->
        <!-- ======= Script: copy-paste entire block below to replace your <script>
            section === === = -->


                <!-- API Clients -->
    <script>
// Wait for dependencies to be loaded
function initializeTermsPage() {
    if (typeof CourseApiClient === 'undefined' || typeof ToastNotification === 'undefined') {
        // Dependencies not loaded yet, try again in 100ms
        setTimeout(initializeTermsPage, 100);
        return;
    }

(function() {
                        // Config
                        const token = localStorage.getItem('auth_token') || '';
                        let terms = [];
                        let currentEditId = null;
                        let currentDeleteId = null;
                        let isSaving = false;

                        // DOM refs
                        const grid = document.getElementById('termsGrid');
                        const termForm = document.getElementById('termForm');
                        const termNameInput = document.getElementById('termName');
                        const modalEl = document.getElementById('addTermModal');
                        const modalTitle = document.getElementById('modalTitle');

                        // ---------- Toast helper ----------
                        function showToast(title = '', message = '', type = 'info', timeout = 3500) {
                            const toastType = (type === 'danger') ? 'error' : type;
                            ToastNotification.show(title, message, toastType, timeout);
                        }

                        // ---------- Skeleton UI ----------
                        function showSkeletons(count = 3) {
                            grid.innerHTML = '';
                            const wrap = document.createElement('div');
                            wrap.className = 'skeleton-grid';
                            for (let i = 0; i < count; i++) {
                                const card = document.createElement('div');
                                card.className = 'skeleton-card';
                                card.innerHTML = `
                        <div class="skeleton-line mid"></div>
                        <div class="skeleton-line short"></div>
                    `;
                            wrap.appendChild(card);
                        }
                        grid.appendChild(wrap);
                    }

                    // ---------- Render terms ----------
                    function renderTerms() {
                        if (!Array.isArray(terms) || terms.length === 0) {
                            grid.innerHTML = `
                        <div class="p-3 text-muted">No terms found. Click "Add Term" to create one.</div>
                        `;
                            return;
                        }

                        grid.innerHTML = terms.map(term => `
                        <div class="term-card">
                        <div class="term-card-header d-flex align-items-start justify-content-between">
                            <h3 class="term-card-title mb-0">${escapeHtml(term.name)}</h3>
                            <div class="term-card-actions">
                            <button class="action-btn btn btn-sm btn-link" onclick="editTerm(${term.id})" title="Edit">
                                <i class="fa-solid fa-pen fa-xs"></i>
                            </button>
                            <button class="action-btn btn btn-sm btn-link text-danger" onclick="openDeleteModal(${term.id})" title="Delete">
                                <i class="fa-solid fa-trash fa-xs"></i>
                            </button>
                            </div>
                        </div>
                        </div>
                    `).join('');
                    }

                    // simple escaping to avoid XSS if API returns unexpected HTML
                    function escapeHtml(str = '') {
                        return String(str)
                            .replace(/&/g, "&amp;")
                            .replace(/</g, "&lt;")
                            .replace(/>/g, "&gt;")
                            .replace(/"/g, "&quot;")
                            .replace(/'/g, "&#039;");
                    }

                    // ---------- API helpers ----------
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

                    // ---------- CRUD operations ----------
                    async function loadTerms() {
                        showSkeletons(3); // chosen: 3 skeleton cards
                        try {
                            const result = await CourseApiClient.getTerms();
                            if (result.success) {
                                terms = Array.isArray(result.data) ? result.data : (result.data.data || []);
                                renderTerms();
                            } else {
                                grid.innerHTML = `<div class="p-3 text-danger">Failed to load terms.</div>`;
                                showToast('Error', result.message || 'Failed to load terms.', 'danger');
                            }
                        } catch (err) {
                            grid.innerHTML = `<div class="p-3 text-danger">Failed to load terms.</div>`;
                            console.error('Load terms error:', err);
                            showToast('Error', 'Failed to load terms. ' + (err.message || ''), 'danger');
                        }
                    }

                    async function createTerm(name) {
                        try {
                            const result = await CourseApiClient.createTerm({ name });
                            if (result.success) {
                                const newTerm = result.data;
                                terms.push(newTerm);
                                renderTerms();
                                showToast('Success', 'Term created successfully.', 'success');
                            } else {
                                showToast('Error', result.message || 'Failed to create term.', 'danger');
                                throw new Error(result.message);
                            }
                        } catch (err) {
                            console.error('Create term error:', err);
                            showToast('Error', 'Failed to create term. ' + (err.message || ''), 'danger');
                            throw err;
                        }
                    }

                    async function updateTerm(id, name) {
                        try {
                            const result = await CourseApiClient.updateTerm(id, { name });
                            if (result.success) {
                                const updated = result.data;

                                const idx = terms.findIndex(t => t.id === id);
                                if (idx > -1) terms[idx] = updated;
                                renderTerms();
                                showToast('Success', 'Term updated.', 'success');
                            } else {
                                showToast('Error', result.message || 'Failed to update term.', 'danger');
                                throw new Error(result.message);
                            }
                        } catch (err) {
                            console.error('Update term error:', err);
                            showToast('Error', 'Failed to update term. ' + (err.message || ''), 'danger');
                            throw err;
                        }
                    }

                    async function deleteTermRequest(id) {
                        try {
                            const result = await CourseApiClient.deleteTerm(id);
                            if (result.success) {
                                terms = terms.filter(t => t.id !== id);
                                renderTerms();
                                showToast('Success', 'Term deleted.', 'success');
                            } else {
                                showToast('Error', result.message || 'Failed to delete term.', 'danger');
                                throw new Error(result.message);
                            }
                        } catch (err) {
                            console.error('Delete term error:', err);
                            showToast('Error', 'Failed to delete term. ' + (err.message || ''), 'danger');
                            throw err;
                        }
                    }

                    // ---------- Form handling ----------
                    termForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        if (isSaving) return;
                        const name = termNameInput.value.trim();
                        if (!name) {
                            showToast('Validation', 'Please enter a term name.', 'info');
                            return;
                        }

                        try {
                            isSaving = true;
                            // disable submit button
                            const submitBtn = termForm.querySelector('button[type="submit"]');
                            if (submitBtn) submitBtn.setAttribute('disabled', 'disabled');

                            if (currentEditId) {
                                await updateTerm(currentEditId, name);
                                currentEditId = null;
                            } else {
                                await createTerm(name);
                            }

                            termForm.reset();
                            const bsModal = bootstrap.Modal.getInstance(modalEl);
                            if (bsModal) bsModal.hide();
                        } catch (err) {
                            // errors surfaced in functions
                        } finally {
                            isSaving = false;
                            const submitBtn = termForm.querySelector('button[type="submit"]');
                            if (submitBtn) submitBtn.removeAttribute('disabled');
                        }
                    });

                    // ---------- Modal helpers (exposed to global for inline onclick use) ----------
                    window.editTerm = function(id) {
                        const t = terms.find(x => x.id === id);
                        if (!t) return showToast('Error', 'Term not found locally.', 'danger');
                        currentEditId = id;
                        termNameInput.value = t.name || '';
                        modalTitle.textContent = 'Edit Term';
                        new bootstrap.Modal(modalEl).show();
                    };

                    window.openDeleteModal = function(id) {
                        currentDeleteId = id;
                        new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
                    };

                    // Confirm delete button handler
                    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
                        if (!currentDeleteId) return;
                        const bs = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
                        try {
                            await deleteTermRequest(currentDeleteId);
                        } catch (err) {
                            // handled in deleteTermRequest
                        } finally {
                            currentDeleteId = null;
                            if (bs) bs.hide();
                        }
                    });

                    // Reset add/edit modal on close
                    modalEl.addEventListener('hidden.bs.modal', () => {
                        currentEditId = null;
                        modalTitle.textContent = 'Add Term';
                        termForm.reset();
                    });

                    // Initial load
                    loadTerms();

                })();
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTermsPage);
} else {
    initializeTermsPage();
}
    </script>



                </main>
@endsection
