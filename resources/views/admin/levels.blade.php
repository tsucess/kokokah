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

            .select-input {
                background-color: transparent;
                border: none
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

        <div class="container-fluid px-4 py-4 d-flex flex-column gap-5">
            <!-- Header Section -->
            <div class="d-flex flex-row align-items-center justify-content-between">
                <div class="header-text">
                    <h1>Levels & Classes</h1>
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
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h1 class="modal-title" id="modalTitle">Add Level</h1>
                        <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </div>
                    <form class="modal-form-container" id="levelForm">
                        <div class="modal-form">
                            <div class="modal-form-input-border">
                                <label for="levelName" class="modal-label">Level Name</label>
                                <input class="modal-input" type="text" id="levelName" placeholder="e.g., JS1" required />
                            </div>
                            <div class="modal-form-input-border">
                                <label for="levelType" class="modal-label">Level Categories</label>
                                <select name="" id="levelType" class="select-input">
                                    <option value="">Select Curriculum Category</option>
                                </select>
                            </div>
                            <div class="modal-form-input-border">
                                <label class="modal-label">Description <span
                                        class="text-muted">(Optional)</span></label>
                                <textarea id="levelDescription" name="description" class="modal-input" rows="3"
                                    placeholder="Enter a short description..."></textarea>
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
        <!-- Add Level Modal -->
        {{-- <div class="modal fade" id="addLevelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">

                    <!-- Header -->
                    <div class="modal-header bg-light border-0 rounded-top-4">
                        <h5 class="modal-title fw-bold">Add New Level</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body px-4">

                        <form id="addLevelForm">

                            <!-- Level Name -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Level Name</label>
                                <input type="text" name="name" class="form-control form-control-lg rounded-3"
                                    placeholder="Enter level name" required>
                            </div>

                            <!-- Curriculum Category -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Curriculum Category</label>
                                <select name="curriculum_category_id" class="form-select form-select-lg rounded-3" required>
                                    <option value="">Select Curriculum Category</option>
                                    <!-- Dynamically filled -->
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description <span
                                        class="text-muted">(Optional)</span></label>
                                <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Enter a short description..."></textarea>
                            </div>

                        </form>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" form="addLevelForm" class="btn btn-primary rounded-3 px-4 fw-semibold">
                            Save Level
                        </button>
                    </div>

                </div>
            </div>
        </div> --}}


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

        <script type="module">
            import CourseApiClient from '{{ asset('js/api/courseApiClient.js') }}';
            import ToastNotification from '{{ asset('js/utils/toastNotification.js') }}';

            (function() {
                // Config
                const token = localStorage.getItem('auth_token') || '';
                let levels = [];
                let categories = [];
                let currentEditId = null;
                let currentDeleteId = null;
                let isSaving = false;

                // DOM refs
                const grid = document.getElementById('levelsGrid');
                const form = document.getElementById('levelForm');
                const levelNameInput = document.getElementById('levelName');
                const levelTypeSelect = document.getElementById('levelType');
                const levelDescription = document.getElementById('levelDescription');
                const modalEl = document.getElementById('addLevelModal');
                const modalTitle = document.getElementById('modalTitle');

                // Ensure toast container exists
                (function ensureToastContainer() {
                    if (!document.getElementById('toastContainer')) {
                        const tc = document.createElement('div');
                        tc.id = 'toastContainer';
                        tc.setAttribute('aria-live', 'polite');
                        tc.setAttribute('aria-atomic', 'true');
                        tc.style.position = 'fixed';
                        tc.style.top = '1rem';
                        tc.style.right = '1rem';
                        tc.style.zIndex = '1080';
                        tc.style.display = 'flex';
                        tc.style.flexDirection = 'column';
                        tc.style.gap = '0.5rem';
                        document.body.appendChild(tc);
                    }
                })();

                // ---------- Toast helper ----------
                function showToast(title = '', message = '', type = 'info', timeout = 3500) {
                    const toastType = (type === 'danger') ? 'error' : type;
                    ToastNotification.show(title, message, toastType, timeout);
                }

                // ---------- Escaping ----------
                function escapeHtml(str = '') {
                    return String(str)
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                }

                // ---------- Skeleton UI ----------
                function showSkeletons(count = 3) {
                    grid.innerHTML = '';
                    const wrap = document.createElement('div');
                    wrap.className = 'skeleton-grid';
                    // basic inline styles for layout if you don't have CSS
                    wrap.style.display = 'grid';
                    wrap.style.gridTemplateColumns = 'repeat(3, 1fr)';
                    wrap.style.gap = '1rem';

                    for (let i = 0; i < count; i++) {
                        const card = document.createElement('div');
                        card.className = 'skeleton-card';
                        card.style.padding = '1rem';
                        card.style.borderRadius = '8px';
                        card.style.background = '#f3f3f3';
                        card.innerHTML = `
                        <div style="height:14px;width:80%;background:#e9e9e9;border-radius:4px;margin-bottom:8px;"></div>
                        <div style="height:12px;width:60%;background:#eee;border-radius:4px"></div>
                    `;
                        wrap.appendChild(card);
                    }
                    grid.appendChild(wrap);
                }

                // ---------- API helper ----------
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

                // ---------- Data parsing helpers ----------
                function unwrapItemResponse(raw) {
                    // Return item object from common shapes
                    if (!raw) return null;
                    if (raw && raw.id) return raw;
                    if (raw.response && raw.response.id) return raw.response;
                    if (raw.data && raw.data.id) return raw.data;
                    return raw;
                }

                // ---------- CRUD operations ----------
                async function loadCategories() {
                    try {
                        const result = await CourseApiClient.getCurriculumCategories();
                        if (result.success) {
                            categories = Array.isArray(result.data) ? result.data : (result.data.data || []);
                            populateCategorySelect();
                        } else {
                            showToast('Error', result.message || 'Failed to load curriculum categories', 'danger');
                        }
                    } catch (err) {
                        console.error('Failed to load categories', err);
                        showToast('Error', 'Failed to load curriculum categories', 'danger');
                    }
                }

                function populateCategorySelect() {
                    if (!levelTypeSelect) return;
                    levelTypeSelect.innerHTML = `<option value="">Select Curriculum Category</option>`;
                    categories.forEach(cat => {
                        const opt = document.createElement('option');
                        opt.value = cat.id;
                        opt.textContent = cat.title ?? cat.name ?? `#${cat.id}`;
                        levelTypeSelect.appendChild(opt);
                    });
                }

                async function loadLevels() {
                    showSkeletons(3);
                    try {
                        const result = await CourseApiClient.getLevels();
                        if (result.success) {
                            levels = Array.isArray(result.data) ? result.data : (result.data.data || []);
                            renderLevels();
                        } else {
                            grid.innerHTML = `<div class="p-3 text-danger">Failed to load levels.</div>`;
                            showToast('Error', result.message || 'Failed to load levels.', 'danger');
                        }
                    } catch (err) {
                        console.error('Load levels error:', err);
                        grid.innerHTML = `<div class="p-3 text-danger">Failed to load levels.</div>`;
                        showToast('Error', 'Failed to load levels. ' + (err.message || ''), 'danger');
                    }
                }

                async function createLevel(name, curriculum_category_id, description) {
                    try {
                        const result = await CourseApiClient.createLevel({
                            name,
                            curriculum_category_id,
                            description
                        });
                        if (result.success) {
                            await loadLevels();
                            showToast('Success', 'Level created successfully.', 'success');
                        } else {
                            showToast('Error', result.message || 'Failed to create level.', 'danger');
                            throw new Error(result.message);
                        }
                    } catch (err) {
                        console.error('Create level error:', err);
                        showToast('Error', 'Failed to create level. ' + (err.message || ''), 'danger');
                        throw err;
                    }
                }

                async function updateLevel(id, name, curriculum_category_id, description) {
                    try {
                        const result = await CourseApiClient.updateLevel(id, {
                            name,
                            curriculum_category_id,
                            description
                        });
                        if (result.success) {
                            await loadLevels();
                            showToast('Success', 'Level updated.', 'success');
                        } else {
                            showToast('Error', result.message || 'Failed to update level.', 'danger');
                            throw new Error(result.message);
                        }
                    } catch (err) {
                        console.error('Update level error:', err);
                        showToast('Error', 'Failed to update level. ' + (err.message || ''), 'danger');
                        throw err;
                    }
                }

                async function deleteLevelRequest(id) {
                    try {
                        const result = await CourseApiClient.deleteLevel(id);
                        if (result.success) {
                            levels = levels.filter(l => l.id !== id);
                            renderLevels();
                            showToast('Success', 'Level deleted.', 'success');
                        } else {
                            showToast('Error', result.message || 'Failed to delete level.', 'danger');
                            throw new Error(result.message);
                        }
                    } catch (err) {
                        console.error('Delete level error:', err);
                        showToast('Error', 'Failed to delete level. ' + (err.message || ''), 'danger');
                        throw err;
                    }
                }

                // ---------- Render ----------
                function renderLevels() {
                    if (!Array.isArray(levels) || levels.length === 0) {
                        grid.innerHTML =
                            `<div class="p-3 text-muted">No levels found. Click "Add Level" to create one.</div>`;
                        return;
                    }

                    grid.innerHTML = levels.map(level => {
                        const cat = categories.find(c => c.id === level.curriculum_category_id) || {};
                        const subtitle = level.description || cat.title || cat.name || '';
                        return `
                                <div class="level-card">
                                    <div class="level-card-header d-flex align-items-start justify-content-between">
                                        <h3 class="level-card-title mb-0">${escapeHtml(level.name)}</h3>
                                        <div class="level-card-actions">
                                            <button class="action-btn btn btn-sm btn-link" onclick="editLevel(${level.id})" title="Edit">
                                                <i class="fa-solid fa-pen fa-xs"></i>
                                            </button>
                                            <button class="action-btn btn btn-sm btn-link text-danger" onclick="openDeleteModal(${level.id})" title="Delete">
                                                <i class="fa-solid fa-trash fa-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="level-card-description">${escapeHtml(subtitle)}</p>
                                </div>
                            `;
                    }).join('');
                }

                // ---------- Form handling ----------
                // form.addEventListener('submit', async (e) => {
                //     e.preventDefault();
                //     if (isSaving) return;

                //     const name = (levelNameInput.value || '').trim();
                //     const curriculum_category_id = levelTypeSelect.value || null;
                //     const description = (levelTypeSelect.selectedOptions[0] && levelTypeSelect.selectedOptions[
                //             0].dataset && levelTypeSelect.selectedOptions[0].dataset.desc) ? levelTypeSelect
                //         .selectedOptions[0].dataset.desc : '';

                //     // Note: description input is taken from select option's text only in your current template.
                //     // If you have a separate description input in modal, replace the line above to read it.
                //     // For now, let user supply description via the select's selected option text or leave blank.

                //     if (!name) {
                //         showToast('Validation', 'Please enter a level name.', 'info');
                //         return;
                //     }
                //     if (!curriculum_category_id) {
                //         showToast('Validation', 'Please select a curriculum category.', 'info');
                //         return;
                //     }

                //     try {
                //         isSaving = true;
                //         const submitBtn = form.querySelector('button[type="submit"]');
                //         if (submitBtn) {
                //             submitBtn.setAttribute('disabled', 'disabled');
                //             submitBtn.dataset.prev = submitBtn.innerHTML;
                //             submitBtn.innerHTML =
                //                 `<span class="spinner-border spinner-border-sm"></span> Saving...`;
                //         }

                //         if (currentEditId) {
                //             await updateLevel(currentEditId, name, Number(curriculum_category_id), description);
                //             currentEditId = null;
                //         } else {
                //             await createLevel(name, Number(curriculum_category_id), description);
                //         }

                //         form.reset();
                //         const bsModal = bootstrap.Modal.getInstance(modalEl);
                //         if (bsModal) bsModal.hide();
                //     } catch (err) {
                //         // errors handled in helpers
                //     } finally {
                //         isSaving = false;
                //         const submitBtn = form.querySelector('button[type="submit"]');
                //         if (submitBtn) {
                //             submitBtn.removeAttribute('disabled');
                //             if (submitBtn.dataset && submitBtn.dataset.prev) submitBtn.innerHTML = submitBtn
                //                 .dataset.prev;
                //         }
                //     }
                // });

                // ---------- Modal helpers (global for inline onclick) ----------
                // window.editLevel = async function(id) {
                //     // find locally first
                //     const local = levels.find(x => x.id === id);
                //     if (local) {
                //         currentEditId = id;
                //         levelNameInput.value = local.name || '';
                //         // set select value
                //         levelTypeSelect.value = local.curriculum_category_id || '';
                //         levelDescription.value = local.description || '';
                //         modalTitle.textContent = 'Edit Level';
                //         new bootstrap.Modal(modalEl).show();
                //         return;
                //     }

                //     // fallback: fetch single item
                //     try {
                //         const data = await apiFetch(`${API_LEVEL}/${id}`, {
                //             method: 'GET'
                //         });
                //         const item = unwrapItemResponse(data) || data;
                //         console.log(item);
                //         if (!item) return showToast('Error', 'Level not found', 'danger');
                //         currentEditId = item.id;
                //         levelNameInput.value = item.name || '';
                //         levelTypeSelect.value = item.curriculum_category_id || '';
                //         levelDescription.value = item.description || '';
                //         modalTitle.textContent = 'Edit Level';
                //         new bootstrap.Modal(modalEl).show();
                //     } catch (err) {
                //         console.error('Failed loading level', err);
                //         showToast('Error', 'Could not load level details', 'danger');
                //     }
                // };

                // ---------- Form handling ----------
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (isSaving) return;

                    const name = (levelNameInput.value || '').trim();
                    const curriculum_category_id = levelTypeSelect.value || null;
                    const description = (levelDescription.value || '').trim(); // <- use textarea value

                    if (!name) {
                        showToast('Validation', 'Please enter a level name.', 'info');
                        return;
                    }
                    if (!curriculum_category_id) {
                        showToast('Validation', 'Please select a curriculum category.', 'info');
                        return;
                    }

                    try {
                        isSaving = true;
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.setAttribute('disabled', 'disabled');
                            submitBtn.dataset.prev = submitBtn.innerHTML;
                            submitBtn.innerHTML =
                                `<span class="spinner-border spinner-border-sm"></span> Saving...`;
                        }

                        if (currentEditId) {
                            await updateLevel(currentEditId, name, Number(curriculum_category_id), description);
                            currentEditId = null;
                        } else {
                            await createLevel(name, Number(curriculum_category_id), description);
                        }

                        form.reset();
                        levelDescription.value = ''; // reset textarea
                        const bsModal = bootstrap.Modal.getInstance(modalEl);
                        if (bsModal) bsModal.hide();
                    } catch (err) {
                        // errors handled in helpers
                    } finally {
                        isSaving = false;
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.removeAttribute('disabled');
                            if (submitBtn.dataset && submitBtn.dataset.prev) submitBtn.innerHTML = submitBtn
                                .dataset.prev;
                        }
                    }
                });

                // ---------- Edit modal ----------
                window.editLevel = async function(id) {
                    const local = levels.find(x => x.id === id);
                    if (local) {
                        currentEditId = id;
                        levelNameInput.value = local.name || '';
                        levelTypeSelect.value = local.curriculum_category_id || '';
                        levelDescription.value = local.description || ''; // <- populate textarea
                        modalTitle.textContent = 'Edit Level';
                        new bootstrap.Modal(modalEl).show();
                        return;
                    }

                    try {
                        const data = await apiFetch(`${API_LEVEL}/${id}`, {
                            method: 'GET'
                        });
                        const item = unwrapItemResponse(data) || data;
                        if (!item) return showToast('Error', 'Level not found', 'danger');

                        currentEditId = item.id;
                        levelNameInput.value = item.name || '';
                        levelTypeSelect.value = item.curriculum_category_id || '';
                        levelDescription.value = item.description || ''; // <- populate textarea
                        modalTitle.textContent = 'Edit Level';
                        new bootstrap.Modal(modalEl).show();
                    } catch (err) {
                        console.error('Failed loading level', err);
                        showToast('Error', 'Could not load level details', 'danger');
                    }
                };


                window.openDeleteModal = function(id) {
                    currentDeleteId = id;
                    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
                };

                // Confirm delete
                document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
                    if (!currentDeleteId) return;
                    const bs = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
                    const btn = document.getElementById('confirmDeleteBtn');
                    btn.disabled = true;
                    const prev = btn.innerHTML;
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Deleting...`;

                    try {
                        await deleteLevelRequest(currentDeleteId);
                        currentDeleteId = null;
                    } catch (err) {
                        // handled in deleteLevelRequest
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = prev;
                        if (bs) bs.hide();
                    }
                });

                // Reset modal on close
                modalEl.addEventListener('hidden.bs.modal', () => {
                    currentEditId = null;
                    modalTitle.textContent = 'Add Level';
                    form.reset();
                });

                // Initial load (categories first, then levels)
                (async function init() {
                    await loadCategories();
                    await loadLevels();
                })();

            })();
        </script>

    </main>
@endsection
