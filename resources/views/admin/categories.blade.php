@extends('layouts.dashboardtemp')

@section('content')
<main class="categories-main">
    <div class="container-fluid px-5 py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-start mb-5">
            <div>
                <h1 class="fw-bold mb-2">Course Categories</h1>
                <p class="text-muted" >Here overview of your</p>
            </div>
            <button class="btn px-4 py-2 fw-semibold" style="background-color: #FDAF22; border: none; color: white;" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                <i class="fa-solid fa-plus me-2"></i> Add Category
            </button>
        </div>

        <!-- Modal component -->
           <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 modal-container">
                <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                    <h1 class="modal-title" id="staticBackdropLabel">Add Category</h1>
                    <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-solid fa-circle-xmark"></i>
        </button>
                </div>
                <form class="modal-form-container">
                    <div class="modal-form">
                        <div class="modal-form-input-border">
                            <label for="" class="modal-label">Course Name</label>
                            <input class="modal-input" type="text" placeholder="Art" />
                        </div>
                        <div class="modal-form-input-border">
                            <label for="" class="modal-label">Course Description</label>
                            <textarea  class="modal-input" placeholder="Enter description" ></textarea>
                        </div>
                    </div>
                    <button class="modal-form-btn">Add Category</button>
                </form>
            </div>
        </div>
    </div>

        <!-- Categories Grid -->
        <div class="row g-4">
            <!-- Sciences Card -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                    <div class="card-body p-4 d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2" style="color: #1a1a1a; font-size: 1.1rem;">Sciences</h5>
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">Mathematic, Physics, Chemistry, Further Mathematics, Agricultural Science</p>
                        </div>
                        <div class="d-flex gap-2 ms-3">
                            <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Arts Card -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                    <div class="card-body p-4 d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2" style="color: #1a1a1a; font-size: 1.1rem;">Arts</h5>
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">English Language, Literature-in-English, Government, CRS</p>
                        </div>
                        <div class="d-flex gap-2 ms-3">
                            <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commercial Card -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                    <div class="card-body p-4 d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2" style="color: #1a1a1a; font-size: 1.1rem;">Commercial</h5>
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">Accounting, Commerce, Economics</p>
                        </div>
                        <div class="d-flex gap-2 ms-3">
                            <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Card -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3" style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                    <div class="card-body p-4 d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2" style="color: #1a1a1a; font-size: 1.1rem;">General</h5>
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">Civic Education, ICT, Physical and Health Education</p>
                        </div>
                        <div class="d-flex gap-2 ms-3">
                            <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; color: #666;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
