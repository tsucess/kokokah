@extends('layouts.dashboardtemp')

@section('content')
<main class="categories-main">
    <div class="container-fluid px-5 py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-start mb-5">
            <div>
                <h1 class="fw-bold mb-2" style="font-size: 2.5rem; color: #004A53; font-family: 'Fredoka One', sans-serif;">Curriculum Categories</h1>
                <p class="text-muted" style="font-size: 0.95rem;">Here overview of your</p>
            </div>
            <button class="btn px-4 py-2 fw-semibold" style="background-color: #FDAF22; border: none; color: white;" onclick="openAddCategoryModal()">
                <i class="fa-solid fa-plus me-2"></i> Add Category
            </button>
        </div>

        <!-- Categories Grid -->
        <div class="row g-4" id="categoriesContainer">
            <!-- Categories will be loaded here dynamically -->
        </div>
    </div>
</main>

<script>
    // Load categories on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadCategories();
    });

    async function loadCategories() {
        try {
            const response = await fetch('/api/category', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                cache: 'no-store'
            });

            if (!response.ok) {
                throw new Error('Failed to load categories');
            }

            const categories = await response.json();

            const container = document.getElementById('categoriesContainer');
            container.innerHTML = '';

            if (!categories || categories.length === 0) {
                container.innerHTML = '<div class="col-12"><p class="text-muted text-center">No categories found. Create one to get started!</p></div>';
                return;
            }

            categories.forEach(category => {
                // Group courses by level
                const coursesByLevel = {};
                if (category.courses && category.courses.length > 0) {
                    category.courses.forEach(course => {
                        const levelName = course.level ? course.level.name : 'Unassigned';
                        if (!coursesByLevel[levelName]) {
                            coursesByLevel[levelName] = [];
                        }
                        coursesByLevel[levelName].push(course.title);
                    });
                }

                // Build levels and courses display
                let levelsHtml = '';
                if (Object.keys(coursesByLevel).length > 0) {
                    Object.entries(coursesByLevel).forEach(([level, courses]) => {
                        levelsHtml += `
                            <div class="mb-3">
                                <strong style="color: #004A53; font-size: 0.95rem;">${level}</strong>
                                <p class="text-muted mb-0" style="font-size: 0.85rem; margin-left: 1rem;">
                                    ${courses.join(', ')}
                                </p>
                            </div>
                        `;
                    });
                } else {
                    levelsHtml = '<p class="text-muted mb-0" style="font-size: 0.9rem;">No courses yet</p>';
                }

                const categoryCard = `
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                            <div class="card-body p-4 d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-3" style="color: #1a1a1a; font-size: 1.1rem;">${category.title}</h5>
                                    <div style="margin-left: 0.5rem;">
                                        ${levelsHtml}
                                    </div>
                                </div>
                                <div class="d-flex gap-2 ms-3">
                                    <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;" onclick="editCategory(${category.id})">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;" onclick="deleteCategory(${category.id})">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += categoryCard;
            });
        } catch (error) {
            console.error('Error loading categories:', error);
            document.getElementById('categoriesContainer').innerHTML = '<div class="col-12"><p class="text-danger text-center">Error loading categories</p></div>';
        }
    }

    function openAddCategoryModal() {
        // TODO: Implement add category modal
        alert('Add Category feature coming soon!');
    }

    function editCategory(categoryId) {
        // TODO: Implement edit category
        alert('Edit Category feature coming soon!');
    }

    async function deleteCategory(categoryId) {
        if (!confirm('Are you sure you want to delete this category?')) {
            return;
        }

        try {
            const token = localStorage.getItem('auth_token');
            const response = await fetch(`/api/category/${categoryId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                cache: 'no-store'
            });

            if (!response.ok) {
                throw new Error('Failed to delete category');
            }

            alert('Category deleted successfully!');
            loadCategories();
        } catch (error) {
            console.error('Error deleting category:', error);
            alert('Error deleting category');
        }
    }
</script>
@endsection

