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
                    type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="fa-solid fa-plus me-2"></i> Add Category
                </button>
            </div>

            <!-- Modal component -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 modal-container">
                        <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                            <h1 class="modal-title" id="staticBackdropLabel">Add Category</h1>
                            <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </div>
                        <form id="categoryForm" class="modal-form-container">
                            <div class="modal-form">
                                <div class="modal-form-input-border">
                                    <label class="modal-label">Course Name</label>
                                    <input id="catName" class="modal-input" type="text" placeholder="e.g. Science"
                                        required />
                                </div>
                                <div class="modal-form-input-border">
                                    <label class="modal-label">Course Description</label>
                                    <textarea id="catDesc" class="modal-input" placeholder="Enter description"></textarea>
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



        <script>
            // Sample data
            const categories = [{
                    id: 1,
                    name: "Sciences",
                    description: "Maths, Physics, Chemistry"
                },
                {
                    id: 2,
                    name: "Arts",
                    description: "English, Literature, Government"
                },
                {
                    id: 3,
                    name: "Commercial",
                    description: "Accounting, Commerce"
                },
                {
                    id: 4,
                    name: "General",
                    description: "Civic Education, ICT"
                }
            ];

            let currentEditId = null;
            let currentDeleteId = null;

            // Render categories
            function renderCategories() {
                const grid = document.getElementById("categoriesGrid");
                grid.innerHTML = categories.map(cat => `
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-3" style="background:#f9f9f9;">
                                    <div class="card-body p-4 d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="fw-bold mb-2">${cat.name}</h5>
                                            <p class="text-muted mb-0">${cat.description}</p>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="action-btn" onclick="editCategory(${cat.id})">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class=" action-btn delete" onclick="deleteCategory(${cat.id})">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join("");
            }

            // Handle form submission (Add / Edit)
            document.getElementById("categoryForm").addEventListener("submit", (e) => {
                e.preventDefault();
                const name = document.getElementById("catName").value;
                const desc = document.getElementById("catDesc").value;

                if (currentEditId) {
                    // Edit existing
                    const cat = categories.find(c => c.id === currentEditId);
                    if (cat) {
                        cat.name = name;
                        cat.description = desc;
                    }
                } else {
                    // Add new
                    categories.push({
                        id: Math.max(...categories.map(c => c.id), 0) + 1,
                        name,
                        description: desc
                    });
                }

                renderCategories();
                document.getElementById("categoryForm").reset();
                currentEditId = null;
                bootstrap.Modal.getInstance(document.getElementById("staticBackdrop")).hide();
            });

            // Edit category
            function editCategory(id) {
                const cat = categories.find(c => c.id === id);
                if (cat) {
                    currentEditId = id;
                    document.getElementById("catName").value = cat.name;
                    document.getElementById("catDesc").value = cat.description;

                    document.getElementById("saveBtn").textContent = "Update Category";

                    new bootstrap.Modal(document.getElementById("staticBackdrop")).show();
                }
            }

            // Delete category
            function deleteCategory(id) {
                currentDeleteId = id;
                new bootstrap.Modal(document.getElementById("deleteCategoryModal")).show();
            }

            document.getElementById("confirmDeleteCategoryBtn").addEventListener("click", () => {
                const index = categories.findIndex(c => c.id === currentDeleteId);
                if (index > -1) {
                    categories.splice(index, 1);
                    renderCategories();
                }
                bootstrap.Modal.getInstance(document.getElementById("deleteCategoryModal")).hide();
            });

            // Reset modal on close
            document.getElementById("staticBackdrop").addEventListener("hidden.bs.modal", () => {
                currentEditId = null;
                document.getElementById("saveBtn").textContent = "Add Category";
                document.getElementById("categoryForm").reset();
            });

            // Initial load
            renderCategories();
        </script>

    </main>
@endsection
