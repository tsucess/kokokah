@extends('layouts.dashboardtemp')

@section('content')
    <main class="curriculum-categories-main">
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
                font-size: 22px;
                font-weight: 600;
                color: #004A53;
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
            .curriculum-categories-main {
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
            .categories-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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

        <div class="container-fluid px-4 py-4 d-flex flex-column gap-5">
            <!-- Header Section -->
            <div class="d-flex flex-row align-items-center justify-content-between">
                <div class="header-text">
                    <h1>Curriculum Categories</h1>
                    <p>Manage curriculum types (WAEC, Cambridge, IELTS, Undergraduate)</p>
                </div>
                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCurriculumModal">
                    <i class="fa-solid fa-plus"></i> Add Category
                </button>
            </div>

            <!-- Categories Grid -->
            <div class="categories-grid" id="curriculumGrid">
                <!-- Cards will be populated by JavaScript -->
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div class="modal fade" id="addCurriculumModal" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="modalTitle">Add Curriculum Category</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="modal-form-container" id="curriculumForm">
                        <div class="modal-form">
                            <div class="modal-form-input-border">
                                <label for="" class="modal-label">Course Name</label>
                                <input class="modal-input" type="text" placeholder="Art" id="categoryName" />
                            </div>
                            <div class="modal-form-input-border">
                                <label for="" class="modal-label">Course Description</label>
                                <textarea id="categoryDescription" class="modal-input" placeholder="Enter description"></textarea>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary-custom">Save Category</button>
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

        {{-- <script>
        // Sample data
        const curriculumCategories = [
            { id: 1, name: 'WAEC', description: 'West African Examination Council' },
            { id: 2, name: 'Cambridge', description: 'Cambridge International Examinations' },
            { id: 3, name: 'IELTS', description: 'International English Language Testing System' },
            { id: 4, name: 'Undergraduate', description: 'University Level Courses' }
        ];

        let currentEditId = null;
        let currentDeleteId = null;

        // Render categories
        function renderCategories() {
            const grid = document.getElementById('curriculumGrid');
            grid.innerHTML = curriculumCategories.map(cat => `
                <div class="category-card">
                    <div class="category-card-header">
                        <h3 class="category-card-title">${cat.name}</h3>
                        <div class="category-card-actions">
                            <button class="action-btn" onclick="editCategory(${cat.id})" title="Edit">
                                <i class="fa-solid fa-pen fa-xs"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteCategory(${cat.id})" title="Delete">
                                <i class="fa-solid fa-trash fa-xs"></i>
                            </button>
                        </div>
                    </div>
                    <p class="category-card-description">${cat.description}</p>
                </div>
            `).join('');
        }

        // Add/Edit category
        document.getElementById('curriculumForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('categoryName').value;
            const description = document.getElementById('categoryDescription').value;

            if (currentEditId) {
                const cat = curriculumCategories.find(c => c.id === currentEditId);
                if (cat) {
                    cat.name = name;
                    cat.description = description;
                }
                currentEditId = null;
            } else {
                curriculumCategories.push({
                    id: Math.max(...curriculumCategories.map(c => c.id), 0) + 1,
                    name,
                    description
                });
            }

            renderCategories();
            document.getElementById('curriculumForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('addCurriculumModal')).hide();
        });

        // Edit category
        function editCategory(id) {
            const cat = curriculumCategories.find(c => c.id === id);
            if (cat) {
                currentEditId = id;
                document.getElementById('categoryName').value = cat.name;
                document.getElementById('categoryDescription').value = cat.description;
                document.getElementById('modalTitle').textContent = 'Edit Curriculum Category';
                new bootstrap.Modal(document.getElementById('addCurriculumModal')).show();
            }
        }

        // Delete category
        function deleteCategory(id) {
            currentDeleteId = id;
            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            const index = curriculumCategories.findIndex(c => c.id === currentDeleteId);
            if (index > -1) {
                curriculumCategories.splice(index, 1);
                renderCategories();
            }
            bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
        });

        // Reset modal on close
        document.getElementById('addCurriculumModal').addEventListener('hidden.bs.modal', () => {
            currentEditId = null;
            document.getElementById('modalTitle').textContent = 'Add Curriculum Category';
            document.getElementById('curriculumForm').reset();
        });

        // Initial render
        renderCategories();
    </script> --}}
        {{-- <script>
            const API_URL = "/api/curriculum-category";
            let currentEditId = null;
            let currentDeleteId = null;

            // ----------------------------
            // Toast Notification Function
            // ----------------------------
            function showToast(message, type = "success") {
                const toast = document.createElement("div");
                toast.className = `toast-msg ${type}`;
                toast.innerText = message;

                toast.style.cssText = `
                                    position: fixed;
                                    top: 20px;
                                    right: 20px;
                                    background: ${type === "success" ? "#28a745" : "#dc3545"};
                                    color: #fff;
                                    padding: 12px 18px;
                                    border-radius: 6px;
                                    font-size: 14px;
                                    z-index: 99999;
                                    opacity: 0;
                                    transform: translateY(-10px);
                                    transition: all .3s;
                                `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = "1";
                    toast.style.transform = "translateY(0)";
                }, 100);

                setTimeout(() => {
                    toast.style.opacity = "0";
                    toast.style.transform = "translateY(-10px)";
                }, 2500);

                setTimeout(() => toast.remove(), 3000);
            }

            // ----------------------------
            // Fetch and Render Categories
            // ----------------------------
            async function loadCategories() {
                const token = localStorage.getItem("auth_token");

                try {
                    const res = await fetch(API_URL, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });

                    if (!res.ok) throw new Error("Failed to fetch");

                    const categories = await res.json();
                    renderCategories(categories);

                } catch (err) {
                    showToast("Unable to load categories", "error");
                }
            }

            // Render dynamic cards into grid
            function renderCategories(categories) {
                const grid = document.getElementById("curriculumGrid");

                grid.innerHTML = categories.map(cat => `
                    <div class="category-card">
                        <div class="category-card-header">
                            <h3 class="category-card-title">${cat.title}</h3>
                            <div class="category-card-actions">
                                <button class="action-btn" onclick="editCategory(${cat.id})" title="Edit">
                                    <i class="fa-solid fa-pen fa-xs"></i>
                                </button>
                                <button class="action-btn delete" onclick="openDelete(${cat.id})" title="Delete">
                                    <i class="fa-solid fa-trash fa-xs"></i>
                                </button>
                            </div>
                        </div>
                        <p class="category-card-description">${cat.description || ""}</p>
                    </div>
                `).join('');
                        }

            // ----------------------------
            // Create or Update Category
            // ----------------------------
            document.getElementById("curriculumForm").addEventListener("submit", async (e) => {
                e.preventDefault();

                const name = document.getElementById("categoryName").value;
                const description = document.getElementById("categoryDescription").value;
                const token = localStorage.getItem("auth_token");

                const btn = document.querySelector(".btn-primary-custom");
                btn.disabled = true;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Saving...`;

                const body = JSON.stringify({
                    title: name,
                    description
                });

                const url = currentEditId ? `${API_URL}/${currentEditId}` : API_URL;
                const method = currentEditId ? "PUT" : "POST";

                try {
                    const res = await fetch(url, {
                        method,
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: `Bearer ${token}`
                        },
                        body,
                    });

                    if (!res.ok) throw new Error("Failed");

                    showToast(currentEditId ? "Category updated!" : "Category created!");
                    bootstrap.Modal.getInstance(document.getElementById("addCurriculumModal")).hide();
                    currentEditId = null;

                    loadCategories();

                } catch (err) {
                    showToast("Error saving category", "error");

                } finally {
                    btn.disabled = false;
                    btn.innerHTML = "Save Category";
                }
            });

            // ----------------------------
            // Edit Category
            // ----------------------------
            async function editCategory(id) {
                const token = localStorage.getItem("auth_token");

                try {
                    const res = await fetch(`${API_URL}/${id}`, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });

                    if (!res.ok) throw new Error("Failed");

                    const cat = await res.json();

                    currentEditId = id;

                    document.getElementById("categoryName").value = cat.title;
                    document.getElementById("categoryDescription").value = cat.description || "";
                    document.getElementById("modalTitle").textContent = "Edit Curriculum Category";

                    new bootstrap.Modal(document.getElementById("addCurriculumModal")).show();

                } catch (err) {
                    showToast("Unable to load category details", "error");
                }
            }

            // ----------------------------
            // Delete Category
            // ----------------------------
            function openDelete(id) {
                currentDeleteId = id;
                new bootstrap.Modal(document.getElementById("deleteConfirmModal")).show();
            }

            document.getElementById("confirmDeleteBtn").addEventListener("click", async () => {
                const token = localStorage.getItem("auth_token");

                const btn = document.getElementById("confirmDeleteBtn");
                btn.disabled = true;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Deleting...`;

                try {
                    const res = await fetch(`${API_URL}/${currentDeleteId}`, {
                        method: "DELETE",
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });

                    if (!res.ok) throw new Error("Failed");

                    showToast("Category deleted!");
                    loadCategories();

                } catch (err) {
                    showToast("Delete failed", "error");

                } finally {
                    btn.disabled = false;
                    btn.innerHTML = "Delete";
                    bootstrap.Modal.getInstance(document.getElementById("deleteConfirmModal")).hide();
                }
            });

            // ----------------------------
            // Reset Modal After Close
            // ----------------------------
            document.getElementById("addCurriculumModal").addEventListener("hidden.bs.modal", () => {
                currentEditId = null;
                document.getElementById("modalTitle").textContent = "Add Curriculum Category";
                document.getElementById("curriculumForm").reset();
            });

            // ----------------------------
            // Load categories on page load
            // ----------------------------
            loadCategories();
        </script> --}}



        {{-- <script>
            (function() {
                const API_URL = "/api/curriculum-category";
                const token = () => localStorage.getItem('auth_token') || '';
                let categories = [];
                let currentEditId = null;
                let currentDeleteId = null;
                let isSaving = false;

                // DOM refs (based on your markup)
                const grid = document.getElementById('curriculumGrid');
                const form = document.getElementById('curriculumForm');
                const nameInput = document.getElementById('categoryName');
                const descInput = document.getElementById('categoryDescription');
                const modalEl = document.getElementById('addCurriculumModal');
                const modalTitle = document.getElementById('modalTitle');

                // Ensure toast container & basic styles exist (inject once)
                (function injectHelpers() {
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

                    // inject CSS for skeletons and toasts if not present
                    if (!document.getElementById('curriculum-category-styles')) {
                        const style = document.createElement('style');
                        style.id = 'curriculum-category-styles';
                        style.innerHTML = `
                            /* skeleton grid: 3 columns */
                            .skeleton-grid {
                            display: grid;
                            grid-template-columns: repeat(3, 1fr);
                            gap: 1rem;
                            }
                            .skeleton-card {
                            padding: 1rem;
                            border-radius: 8px;
                            background: #f3f3f3;
                            min-height: 72px;
                            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
                            }
                            .skeleton-line {
                            height: 14px;
                            background: linear-gradient(90deg,#eee 25%,#e0e0e0 37%,#eee 63%);
                            background-size: 400% 100%;
                            animation: shimmer 1.2s linear infinite;
                            border-radius: 4px;
                            margin-bottom: 8px;
                            }
                            .skeleton-line.short { width: 60%; }
                            .skeleton-line.mid { width: 80%; }
                            @keyframes shimmer { 0%{background-position:200% 0}100%{background-position:-200% 0} }

                            /* toast style (uses bootstrap .toast classes if available but ensures readable fallback) */
                            .custom-toast {
                            min-width: 220px;
                            max-width: 320px;
                            padding: 0.6rem;
                            border-radius: 6px;
                            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
                            color: #fff;
                            font-size: 0.95rem;
                            pointer-events: auto;
                            }
                            .custom-toast.success { background: #198754; }
                            .custom-toast.danger { background: #dc3545; }
                            .custom-toast.info { background: #0d6efd; }
                        `;
                        document.head.appendChild(style);
                    }
                })();

                // Toast helper (title optional)
                function showToast(title = '', message = '', type = 'info', timeout = 3500) {
                    const container = document.getElementById('toastContainer');
                    const id = 'toast-' + Date.now();
                    const toast = document.createElement('div');
                    toast.id = id;
                    toast.className = `custom-toast ${type}`;
                    toast.style.pointerEvents = 'auto';

                    toast.innerHTML = `
                    ${title ? `<strong style="display:block;margin-bottom:6px">${escapeHtml(title)}</strong>` : ''}
                    <div style="line-height:1.2">${escapeHtml(message)}</div>
                    `;
                    container.appendChild(toast);

                    // auto remove
                    setTimeout(() => {
                        toast.style.transition = 'opacity .25s, transform .25s';
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-8px)';
                        setTimeout(() => toast.remove(), 300);
                    }, timeout);
                }

                // escape helper
                function escapeHtml(str = '') {
                    return String(str)
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                }

                // show 12 skeleton cards (3 per row)
                function showSkeletons(count = 12) {
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

                // apiFetch helper - centralizes headers + error handling
                async function apiFetch(url, opts = {}) {
                    const headers = Object.assign({
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                    }, opts.headers || {});

                    const tk = token();
                    if (tk) headers["Authorization"] = `Bearer ${tk}`;

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

                // render categories into the existing grid container
                function renderCategories(list = []) {
                    categories = Array.isArray(list) ? list : (list.data || []);
                    if (!Array.isArray(categories) || categories.length === 0) {
                        grid.innerHTML =
                            `<div class="p-3 text-muted">No categories found. Click "Add Category" to create one.</div>`;
                        return;
                    }

                    // create grid layout: we'll create a wrapper with CSS grid similar to skeletons
                    const wrapper = document.createElement('div');
                    wrapper.style.display = 'grid';
                    wrapper.style.gridTemplateColumns = 'repeat(3, 1fr)';
                    wrapper.style.gap = '1rem';

                    categories.forEach(cat => {
                        const col = document.createElement('div');
                        // card structure matching your markup
                        col.innerHTML = `
                            <div class="category-card">
                            <div class="category-card-header d-flex align-items-start justify-content-between">
                                <h3 class="category-card-title mb-0">${escapeHtml(cat.title)}</h3>
                                <div class="category-card-actions">
                                <button class="action-btn btn btn-sm btn-link edit-btn" data-id="${cat.id}" title="Edit">
                                    <i class="fa-solid fa-pen fa-xs"></i>
                                </button>
                                <button class="action-btn btn btn-sm btn-link text-danger delete-btn" data-id="${cat.id}" title="Delete">
                                    <i class="fa-solid fa-trash fa-xs"></i>
                                </button>
                                </div>
                            </div>
                            <p class="category-card-description">${escapeHtml(cat.description || '')}</p>
                            </div>
                        `;
                        // attach handlers to buttons inside this col later
                        wrapper.appendChild(col);
                    });

                    // replace grid content
                    grid.innerHTML = '';
                    grid.appendChild(wrapper);

                    // attach button listeners (delegate)
                    grid.querySelectorAll('.edit-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const id = btn.getAttribute('data-id');
                            return window.editCategory(parseInt(id, 10));
                        });
                    });
                    grid.querySelectorAll('.delete-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const id = btn.getAttribute('data-id');
                            return window.deleteCategory(parseInt(id, 10));
                        });
                    });
                }

                // ---------- CRUD operations ----------
                async function loadCategories() {
                    showSkeletons(12); // 12 cards, 3 per row
                    try {
                        const data = await apiFetch(API_URL, {
                            method: 'GET'
                        });
                        renderCategories(Array.isArray(data) ? data : (data.data || []));
                    } catch (err) {
                        console.error('Load categories error:', err);
                        grid.innerHTML = `<div class="p-3 text-danger">Failed to load categories.</div>`;
                        showToast('Error', 'Failed to load categories. ' + (err.message || ''), 'danger');
                    }
                }

                async function createCategory(title, description) {
                    try {
                        const payload = await apiFetch(API_URL, {
                            method: 'POST',
                            body: JSON.stringify({
                                title,
                                description
                            })
                        });
                        const newCat = (payload && (payload.id || payload.data && payload.data.id)) ? (payload.data ||
                            payload) : payload;
                        showToast('Success', 'Category created successfully.', 'success');
                        return newCat;
                    } catch (err) {
                        console.error('Create category error:', err);
                        showToast('Error', 'Failed to create category. ' + (err.message || ''), 'danger');
                        throw err;
                    }
                }

                async function updateCategory(id, title, description) {
                    try {
                        const payload = await apiFetch(`${API_URL}/${id}`, {
                            method: 'PUT',
                            body: JSON.stringify({
                                title,
                                description
                            })
                        });
                        showToast('Success', 'Category updated.', 'success');
                        return payload;
                    } catch (err) {
                        console.error('Update error:', err);
                        showToast('Error', 'Failed to update category. ' + (err.message || ''), 'danger');
                        throw err;
                    }
                }

                async function deleteCategoryRequest(id) {
                    try {
                        await apiFetch(`${API_URL}/${id}`, {
                            method: 'DELETE'
                        });
                        showToast('Success', 'Category deleted.', 'success');
                        return true;
                    } catch (err) {
                        console.error('Delete error:', err);
                        showToast('Error', 'Failed to delete category. ' + (err.message || ''), 'danger');
                        throw err;
                    }
                }

                // ---------- Form handling ----------
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (isSaving) return;
                    const title = (nameInput.value || '').trim();
                    const description = (descInput.value || '').trim();

                    if (!title) {
                        showToast('Validation', 'Please enter a category title.', 'info');
                        return;
                    }

                    isSaving = true;
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const previousHTML = submitBtn.innerHTML;
                    submitBtn.setAttribute('disabled', 'disabled');
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Saving...`;

                    try {
                        if (currentEditId) {
                            await updateCategory(currentEditId, title, description);
                            currentEditId = null;
                        } else {
                            await createCategory(title, description);
                        }
                        form.reset();
                        const bsModal = bootstrap.Modal.getInstance(modalEl);
                        if (bsModal) bsModal.hide();
                        await loadCategories();
                    } catch (err) {
                        // errors handled in helper functions
                    } finally {
                        isSaving = false;
                        submitBtn.removeAttribute('disabled');
                        submitBtn.innerHTML = previousHTML;
                    }
                });

                // ---------- Global functions used by markup ----------
                window.editCategory = async function(id) {
                    // fetch single item and open modal
                    try {
                        const data = await apiFetch(`${API_URL}/${id}`, {
                            method: 'GET'
                        });
                        const item = data && data.data ? data.data : data;
                        currentEditId = id;
                        nameInput.value = item.title || '';
                        descInput.value = item.description || '';
                        modalTitle.textContent = 'Edit Curriculum Category';
                        new bootstrap.Modal(modalEl).show();
                    } catch (err) {
                        console.error('Failed to load category:', err);
                        showToast('Error', 'Unable to load category details.', 'danger');
                    }
                };

                window.deleteCategory = function(id) {
                    currentDeleteId = id;
                    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
                };

                // confirm delete button
                document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
                    if (!currentDeleteId) return;
                    const btn = this;
                    btn.disabled = true;
                    const prev = btn.innerHTML;
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Deleting...`;

                    try {
                        await deleteCategoryRequest(currentDeleteId);
                        currentDeleteId = null;
                        // refresh list
                        await loadCategories();
                    } catch (err) {
                        // errors already shown
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = prev;
                        const bs = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
                        if (bs) bs.hide();
                    }
                });

                // reset modal on close
                modalEl.addEventListener('hidden.bs.modal', () => {
                    currentEditId = null;
                    modalTitle.textContent = 'Add Curriculum Category';
                    form.reset();
                });

                // initial load
                loadCategories();

            })();
        </script> --}}



        <script>
            (function() {
                const API_URL = "/api/curriculum-category";
                const token = () => localStorage.getItem('auth_token') || '';

                // 1. Get the stored string from localStorage
                const userStr = localStorage.getItem('auth_user');

                // 2. Parse it to a JavaScript object
                const authUser = userStr ? JSON.parse(userStr) : null;

                // 3. Access the ID
                const currentUserId = authUser ? authUser.id : null;



                let categories = [];
                let currentEditId = null;
                let currentDeleteId = null;
                let isSaving = false;

                // DOM refs
                const grid = document.getElementById('curriculumGrid');
                const form = document.getElementById('curriculumForm');
                const nameInput = document.getElementById('categoryName');
                const descInput = document.getElementById('categoryDescription');
                const modalEl = document.getElementById('addCurriculumModal');
                const modalTitle = document.getElementById('modalTitle');

                // inject helpers (skeletons + toast)
                (function injectHelpers() {
                    if (!document.getElementById('toastContainer')) {
                        const tc = document.createElement('div');
                        tc.id = 'toastContainer';
                        tc.style.position = 'fixed';
                        tc.style.top = '1rem';
                        tc.style.right = '1rem';
                        tc.style.zIndex = '1080';
                        tc.style.display = 'flex';
                        tc.style.flexDirection = 'column';
                        tc.style.gap = '0.5rem';
                        document.body.appendChild(tc);
                    }

                    if (!document.getElementById('curriculum-category-styles')) {
                        const style = document.createElement('style');
                        style.id = 'curriculum-category-styles';
                        style.innerHTML = `
                                        .skeleton-grid {
                                        display: grid;
                                        grid-template-columns: repeat(3, 1fr);
                                        gap: 1rem;
                                        }
                                        .skeleton-card {
                                        padding: 1rem;
                                        border-radius: 8px;
                                        background: #f3f3f3;
                                        min-height: 72px;
                                        }
                                        .skeleton-line {
                                        height: 14px;
                                        background: linear-gradient(90deg,#eee 25%,#e0e0e0 37%,#eee 63%);
                                        background-size: 400% 100%;
                                        animation: shimmer 1.2s linear infinite;
                                        border-radius: 4px;
                                        margin-bottom: 8px;
                                        }
                                        .skeleton-line.short { width: 60%; }
                                        .skeleton-line.mid { width: 80%; }
                                        @keyframes shimmer {
                                        0%{background-position:200% 0}
                                        100%{background-position:-200% 0}
                                        }

                                        .custom-toast {
                                        min-width: 220px;
                                        max-width: 320px;
                                        padding: 0.6rem;
                                        border-radius: 6px;
                                        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
                                        color: #fff;
                                        font-size: 0.95rem;
                                        }
                                        .custom-toast.success { background: #198754; }
                                        .custom-toast.danger { background: #dc3545; }
                                        .custom-toast.info { background: #0d6efd; }
                                    `;
                        document.head.appendChild(style);
                    }
                })();

                function showToast(title = '', message = '', type = 'info', timeout = 3500) {
                    const container = document.getElementById('toastContainer');
                    const toast = document.createElement('div');
                    toast.className = `custom-toast ${type}`;
                    toast.innerHTML = `
                                        ${title ? `<strong>${escapeHtml(title)}</strong><br>` : ''}
                                        ${escapeHtml(message)}
                                        `;
                    container.appendChild(toast);
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-8px)';
                        setTimeout(() => toast.remove(), 300);
                    }, timeout);
                }

                function escapeHtml(str = '') {
                    return String(str)
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                }

                function showSkeletons(count = 12) {
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

                async function apiFetch(url, opts = {}) {
                    const headers = {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        ...(opts.headers || {})
                    };

                    const tk = token();
                    if (tk) headers["Authorization"] = `Bearer ${tk}`;

                    const response = await fetch(url, {
                        ...opts,
                        headers
                    });
                    const type = response.headers.get("content-type") || "";
                    const data = type.includes("json") ? await response.json() : await response.text();

                    if (!response.ok) {
                        throw new Error(data?.message || "Request failed");
                    }

                    return data;
                }

                function renderCategories(list = []) {
                    categories = Array.isArray(list) ? list : (list.data || []);
                    if (categories.length === 0) {
                        grid.innerHTML = `<div class="p-3 text-muted">No categories found.</div>`;
                        return;
                    }

                    // const wrapper = document.createElement('div');
                    // wrapper.style.display = 'grid';
                    // wrapper.style.gridTemplateColumns = 'repeat(3, 1fr)';
                    // wrapper.style.gap = '1rem';
                    // wrapper.style.width = '100%'
                    grid.innerHTML = '';

                    categories.forEach(cat => {
                        const card = document.createElement('div');
    card.classList.add('category-card');
                        card.innerHTML = `
                                    <div class="category-card-header d-flex justify-content-between">
                                        <h3 class="category-card-title">${escapeHtml(cat.title)}</h3>
                                        <div class="category-card-actions">
                                        <button class="action-btn edit-btn" data-id="${cat.id}">
                                             <i class="fa-solid fa-pen fa-xs"></i>
                                        </button>
                                        <button class="action-btn delete delete-btn" data-id="${cat.id}">
                                            <i class="fa-solid fa-trash fa-xs"></i>
                                        </button>

                                        </div>
                                    </div>
                                    <p class="category-card-description">${escapeHtml(cat.description || '')}</p>

                                `;
                       grid.appendChild(card);
                    });

                    // grid.innerHTML = '';
                    // grid.appendChild(wrapper);

                    grid.querySelectorAll('.edit-btn').forEach(btn => {
                        btn.onclick = () => window.editCategory(btn.dataset.id);
                    });

                    grid.querySelectorAll('.delete-btn').forEach(btn => {
                        btn.onclick = () => window.deleteCategory(btn.dataset.id);
                    });
                }

                async function loadCategories() {
                    showSkeletons();
                    try {
                        const data = await apiFetch(API_URL, {
                            method: 'GET'
                        });
                        renderCategories(data);
                    } catch {
                        showToast('Error', 'Failed to load categories.', 'danger');
                    }
                }

                async function createCategory(title, description) {
                    return await apiFetch(API_URL, {
                        method: 'POST',
                        body: JSON.stringify({
                            title,
                            description,
                            user_id: currentUserId // ✅ FIXED
                        })
                    });
                }

                async function updateCategory(id, title, description) {
                    return await apiFetch(`${API_URL}/${id}`, {
                        method: 'PUT',
                        body: JSON.stringify({
                            title,
                            description,
                            user_id: currentUserId // ✅ FIXED
                        })
                    });
                }

                async function deleteCategoryRequest(id) {
                    return await apiFetch(`${API_URL}/${id}`, {
                        method: 'DELETE'
                    });
                }

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (isSaving) return;

                    const title = nameInput.value.trim();
                    const description = descInput.value.trim();

                    if (!title) {
                        showToast("Validation", "Title is required", "info");
                        return;
                    }

                    isSaving = true;

                    const btn = form.querySelector('button[type="submit"]');
                    const prev = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Saving...`;

                    try {
                        if (currentEditId) {
                            await updateCategory(currentEditId, title, description);
                            showToast("Success", "Updated successfully", "success");
                        } else {
                            await createCategory(title, description);
                            showToast("Success", "Created successfully", "success");
                        }

                        bootstrap.Modal.getInstance(modalEl)?.hide();
                        form.reset();
                        currentEditId = null;
                        loadCategories();

                    } catch (err) {
                        showToast("Error", err.message || "Save failed", "danger");
                    }

                    btn.disabled = false;
                    btn.innerHTML = prev;
                    isSaving = false;
                });

                window.editCategory = async (id) => {
                    try {
                        const data = await apiFetch(`${API_URL}/${id}`, {
                            method: 'GET'
                        });
                        const item = data || {}

                        currentEditId = id;
                        
                        nameInput.value = item.title || "";
                        descInput.value = item.description || "";
                        modalTitle.textContent = "Edit Curriculum Category";

                        new bootstrap.Modal(modalEl).show();

                    } catch {
                        showToast("Error", "Could not load category", "danger");
                    }
                };

                window.deleteCategory = (id) => {
                    currentDeleteId = id;
                    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
                };

                document.getElementById('confirmDeleteBtn').onclick = async () => {
                    const btn = document.getElementById('confirmDeleteBtn');
                    btn.disabled = true;
                    const prev = btn.innerHTML;
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Deleting...`;

                    try {
                        await deleteCategoryRequest(currentDeleteId);
                        showToast("Success", "Deleted successfully", "success");
                        loadCategories();

                    } catch {
                        showToast("Error", "Delete failed", "danger");
                    }

                    btn.disabled = false;
                    btn.innerHTML = prev;
                    bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
                };

                modalEl.addEventListener('hidden.bs.modal', () => {
                    currentEditId = null;
                    modalTitle.textContent = "Add Curriculum Category";
                    form.reset();
                });

                loadCategories();

            })();
        </script>

    </main>
@endsection
