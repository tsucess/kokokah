@extends('layouts.dashboardtemp')

@section('content')
    <style>
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
    <main class="categories-main">
        <div class="container-fluid px-5 py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-start mb-5">
                <div>
                    <h1 class="fw-bold mb-2">Course Categories</h1>
                    <p class="text-muted">Here overview of your</p>
                </div>
                <button class="btn px-4 py-2 fw-semibold" style="background-color: #FDAF22; border: none; color: white;"
                    type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fa-solid fa-plus me-2"></i> Add Category
                </button>
            </div>

            <!-- Modal component -->
            <div class="modal fade" id="addCategoryModal" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 modal-container">
                        <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                            <h1 class="modal-title" id="modalTitle">Add Category</h1>
                            <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </div>
                        <form id="categoryForm" class="modal-form-container">
                            <div class="modal-form">
                                <div class="modal-form-input-border">
                                    <label class="modal-label">Course Name</label>
                                    <input id="categoryName" class="modal-input" type="text" placeholder="e.g. Science"
                                        required />
                                </div>
                                <div class="modal-form-input-border">
                                    <label class="modal-label">Course Description</label>
                                    <textarea id="categoryDesc" class="modal-input" placeholder="Enter description"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="modal-form-btn" id="saveBtn">Add Category</button>
                            <input type="hidden" id="editId">
                        </form>

                    </div>
                </div>
            </div>

            {{-- Delete Confirmation Modal --}}
            <div class="modal fade" id="deleteCategoryModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">Delete Category</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this category?
                        </div>
                        <div class="d-flex gap-2 p-3">
                            <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger-custom"
                                id="confirmDeleteCategoryBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Categories Grid -->
            <div class="row g-4" id="categoriesGrid"></div>
        </div>

<div id="toastContainer" aria-live="polite" aria-atomic="true"></div>

        <script>
              (function() {
                // Config
                const API_URL = "/api/course-category";
                const token = localStorage.getItem('auth_token') || '';
                let categories = [];
                let currentEditId = null;
                let currentDeleteId = null;
                let isSaving = false;

                // DOM refs
                const grid = document.getElementById('categoriesGrid');
                const categoryForm = document.getElementById('categoryForm');
                const categoryNameInput = document.getElementById('categoryName');
                const categoryDescInput = document.getElementById('categoryDesc');
                const modalEl = document.getElementById('addCategoryModal');
                const modalTitle = document.getElementById('modalTitle');

                // ---------- Toast helper ----------
                function showToast(title = '', message = '', type = 'info', timeout = 3500) {
                    // type: 'success' | 'danger' | 'info'
                    const container = document.getElementById('toastContainer');
                    const toastId = 'toast-' + Date.now();

                    const bgClass = (type === 'success') ? 'bg-success text-white' : (type === 'danger') ?
                        'bg-danger text-white' : 'bg-light';
                    const headerClass = (type === 'info') ? '' : '';

                    const toastHtml = `
                            <div id="${toastId}" class="toast ${bgClass}" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body" style="padding:0.75rem;">
                                <strong>${title}</strong>
                                <div style="font-size:0.9rem; margin-top:0.35rem;">${message}</div>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" style="margin-left:auto;"></button>
                            </div>
                            </div>
                        `;
                    container.insertAdjacentHTML('beforeend', toastHtml);
                    const toastEl = document.getElementById(toastId);
                    const bsToast = new bootstrap.Toast(toastEl, {
                        delay: timeout
                    });
                    bsToast.show();

                    // remove from DOM after hidden
                    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
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

                // ---------- Render categories ----------
                function rendercategories() {
                    if (!Array.isArray(categories) || categories.length === 0) {
                        grid.innerHTML = `
                        <div class="p-3 text-muted">No categories found. Click "Add category" to create one.</div>
                        `;
                                        return;
                                    }

                                    grid.innerHTML = categories.map(category => `
                         <div class="col-12">
                                 <div class="card border-0 shadow-sm rounded-3" style="background:#f9f9f9;">
                                     <div class="card-body p-4 d-flex justify-content-between align-items-start">
                                         <div>
                                             <h5 class="fw-bold mb-2">${escapeHtml(category.title)}</h5>
                                             <p class="text-muted mb-0">${category.description}</p>
                                         </div>
                                         <div class="d-flex gap-2">
                                             <button class="action-btn" onclick="editcategory(${category.id})">
                                                 <i class="fa-solid fa-pen"></i>
                                             </button>
                                             <button class=" action-btn delete" onclick="openDeleteModal(${category.id})">
                                                 <i class="fa-solid fa-trash"></i>
                                             </button>
                                         </div>
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
                async function loadcategories() {
                    showSkeletons(3); // chosen: 3 skeleton cards
                    try {
                        const data = await apiFetch(API_URL, {
                            method: 'GET'
                        });
                        categories = Array.isArray(data) ? data : (data.data || []);
                        console.log(categories)
                        rendercategories();
                    } catch (err) {
                        grid.innerHTML = `<div class="p-3 text-danger">Failed to load categories.</div>`;
                        console.error('Load categories error:', err);
                        showToast('Error', 'Failed to load categories. ' + (err.message || ''), 'danger');
                    }
                }

                async function createcategory(title, description) {
                    try {
                        const payload = await apiFetch(API_URL, {
                            method: 'POST',
                            body: JSON.stringify({
                                title,
                                description
                            })
                        });
                        // assume API returns created resource
                        const newcategory = (payload && payload.id) ? payload : (payload.data || payload);
                        categories.push(newcategory);
                        rendercategories();
                        showToast('Success', 'category created successfully.', 'success');
                    } catch (err) {
                        console.error('Create category error:', err);
                        showToast('Error', 'Failed to create category. ' + (err.message || ''), 'danger');
                        throw err;
                    }
                }

                async function updatecategory(id, title, description) {
                    try {
                        const payload = await apiFetch(`${API_URL}/${id}`, {
                            method: 'PUT',
                            body: JSON.stringify({
                                title,
                                description
                            })
                        });
                        const updated = (payload && payload.id) ? payload : (payload.data || payload);

                        const idx = categories.findIndex(t => t.id === id);
                        if (idx > -1) categories[idx] = updated;
                        rendercategories();
                        showToast('Success', 'category updated.', 'success');
                    } catch (err) {
                        console.error('Update category error:', err);
                        showToast('Error', 'Failed to update category. ' + (err.message || ''), 'danger');
                        throw err;
                    }
                }

                async function deletecategoryRequest(id) {
                    try {
                        await apiFetch(`${API_URL}/${id}`, {
                            method: 'DELETE'
                        });
                        categories = categories.filter(t => t.id !== id);
                        rendercategories();
                        showToast('Success', 'category deleted.', 'success');
                    } catch (err) {
                        console.error('Delete category error:', err);
                        showToast('Error', 'Failed to delete category. ' + (err.message || ''), 'danger');
                        throw err;
                    }
                }

                // ---------- Form handling ----------
                categoryForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (isSaving) return;
                    const name = categoryNameInput.value.trim();
                    const description = categoryDescInput.value.trim();
                    if (!name || !description) {
                        showToast('Validation', 'Please enter a category name.', 'info');
                        return;
                    }

                    try {
                        isSaving = true;
                        // disable submit button
                        const submitBtn = categoryForm.querySelector('button[type="submit"]');
                        if (submitBtn) submitBtn.setAttribute('disabled', 'disabled');

                        if (currentEditId) {
                            await updatecategory(currentEditId, name, description);
                            currentEditId = null;
                        } else {
                            await createcategory(name, description);
                        }

                        categoryForm.reset();
                        const bsModal = bootstrap.Modal.getInstance(modalEl);
                        if (bsModal) bsModal.hide();
                    } catch (err) {
                        // errors surfaced in functions
                    } finally {
                        isSaving = false;
                        const submitBtn = categoryForm.querySelector('button[type="submit"]');
                        if (submitBtn) submitBtn.removeAttribute('disabled');
                    }
                });

                // ---------- Modal helpers (exposed to global for inline onclick use) ----------
                window.editcategory = function(id) {
                    const t = categories.find(x => x.id === id);
                    if (!t) return showToast('Error', 'category not found locally.', 'danger');
                    currentEditId = id;
                    categoryNameInput.value = t.title || '';
                    categoryDescInput.value = t.description || '';
                    modalTitle.textContent = 'Edit category';
                    new bootstrap.Modal(modalEl).show();
                };

                window.openDeleteModal = function(id) {
                    currentDeleteId = id;
                    new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
                };

                // Confirm delete button handler
                document.getElementById('confirmDeleteCategoryBtn').addEventListener('click', async () => {
                    if (!currentDeleteId) return;
                    const bs = bootstrap.Modal.getInstance(document.getElementById('deleteCategoryModal'));
                    try {
                        await deletecategoryRequest(currentDeleteId);
                    } catch (err) {
                        // handled in deletecategoryRequest
                    } finally {
                        currentDeleteId = null;
                        if (bs) bs.hide();
                    }
                });

                // Reset add/edit modal on close
                modalEl.addEventListener('hidden.bs.modal', () => {
                    currentEditId = null;
                    modalTitle.textContent = 'Add category';
                    categoryForm.reset();
                });

                // Initial load
                loadcategories();

            })();
            // Sample data
            // const categories = [{
            //         id: 1,
            //         name: "Sciences",
            //         description: "Maths, Physics, Chemistry"
            //     },
            //     {
            //         id: 2,
            //         name: "Arts",
            //         description: "English, Literature, Government"
            //     },
            //     {
            //         id: 3,
            //         name: "Commercial",
            //         description: "Accounting, Commerce"
            //     },
            //     {
            //         id: 4,
            //         name: "General",
            //         description: "Civic Education, ICT"
            //     }
            // ];

            // let currentEditId = null;
            // let currentDeleteId = null;

            // // Render categories
            // function renderCategories() {
            //     const grid = document.getElementById("categoriesGrid");
            //     grid.innerHTML = categories.map(cat => `
            //                 <div class="col-12">
            //                     <div class="card border-0 shadow-sm rounded-3" style="background:#f9f9f9;">
            //                         <div class="card-body p-4 d-flex justify-content-between align-items-start">
            //                             <div>
            //                                 <h5 class="fw-bold mb-2">${cat.name}</h5>
            //                                 <p class="text-muted mb-0">${cat.description}</p>
            //                             </div>
            //                             <div class="d-flex gap-2">
            //                                 <button class="action-btn" onclick="editCategory(${cat.id})">
            //                                     <i class="fa-solid fa-pen"></i>
            //                                 </button>
            //                                 <button class=" action-btn delete" onclick="deleteCategory(${cat.id})">
            //                                     <i class="fa-solid fa-trash"></i>
            //                                 </button>
            //                             </div>
            //                         </div>
            //                     </div>
            //                 </div>
            //             `).join("");
            // }

            // // Handle form submission (Add / Edit)
            // document.getElementById("categoryForm").addEventListener("submit", (e) => {
            //     e.preventDefault();
            //     const name = document.getElementById("catName").value;
            //     const desc = document.getElementById("catDesc").value;

            //     if (currentEditId) {
            //         // Edit existing
            //         const cat = categories.find(c => c.id === currentEditId);
            //         if (cat) {
            //             cat.name = name;
            //             cat.description = desc;
            //         }
            //     } else {
            //         // Add new
            //         categories.push({
            //             id: Math.max(...categories.map(c => c.id), 0) + 1,
            //             name,
            //             description: desc
            //         });
            //     }

            //     renderCategories();
            //     document.getElementById("categoryForm").reset();
            //     currentEditId = null;
            //     bootstrap.Modal.getInstance(document.getElementById("staticBackdrop")).hide();
            // });

            // // Edit category
            // function editCategory(id) {
            //     const cat = categories.find(c => c.id === id);
            //     if (cat) {
            //         currentEditId = id;
            //         document.getElementById("catName").value = cat.name;
            //         document.getElementById("catDesc").value = cat.description;

            //         document.getElementById("saveBtn").textContent = "Update Category";

            //         new bootstrap.Modal(document.getElementById("staticBackdrop")).show();
            //     }
            // }

            // // Delete category
            // function deleteCategory(id) {
            //     currentDeleteId = id;
            //     new bootstrap.Modal(document.getElementById("deleteCategoryModal")).show();
            // }

            // document.getElementById("confirmDeleteCategoryBtn").addEventListener("click", () => {
            //     const index = categories.findIndex(c => c.id === currentDeleteId);
            //     if (index > -1) {
            //         categories.splice(index, 1);
            //         renderCategories();
            //     }
            //     bootstrap.Modal.getInstance(document.getElementById("deleteCategoryModal")).hide();
            // });

            // // Reset modal on close
            // document.getElementById("staticBackdrop").addEventListener("hidden.bs.modal", () => {
            //     currentEditId = null;
            //     document.getElementById("saveBtn").textContent = "Add Category";
            //     document.getElementById("categoryForm").reset();
            // });

            // // Initial load
            // renderCategories();
        </script>

    </main>
@endsection
