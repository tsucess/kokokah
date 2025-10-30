@extends('layouts.dashboardtemp')

@section('content')
<main>

    <div class = "container">
        <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class = "fw-bold">Subject Category</h4>
      <p class="text-muted mb-0">Here overview of your</p>
    </div>
    {{-- <button class="btn btn-teal text-white rounded-pill px-4" style="background:#0d9488;">
      <i class="fa fa-plus me-2"></i>Create New Course
    </button> --}}

    <div>
    <button class="btn-nav-curriculum">
      <i class="fa fa-plus me-2"></i>Add Category
    </button>
    </div>

  </div>
    </div>

    <div class = "container">
          <div class="accordion" id="courseAccordion">

        <div class="accordion-item">
            <div class="accordion-header" id="headingOne" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <p class = "fw-bold" style = "color:#000;">Sciences</p>
                <div class="d-flex">
                    <i class="fa-solid fa-pen pe-2" aria-label="Edit"></i>
                    <i class="fa-solid fa-trash" aria-label="Delete"></i>
                </div>
            </div>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#courseAccordion">
                <div class="accordion-body">
                    Mathematics, Physics, Chemistry, Further Mathematics, Agricultural Science
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <div class="accordion-header collapsed" id="headingTwo" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <p class = "fw-bold" style = "color:#000;">Arts</p>
                <div class="d-flex">
                    <i class="fa-solid fa-pen pe-2" aria-label="Edit"></i>
                    <i class="fa-solid fa-trash" aria-label="Delete"></i>
                </div>
            </div>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#courseAccordion">
                <div class="accordion-body">
                  English Language, Literature-in-English, Government, CRS
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <div class="accordion-header collapsed" id="headingThree" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <p class = "fw-bold" style = "color:#000;">Commercial</p>
                <div class="d-flex">
                    <i class="fa-solid fa-pen pe-2" aria-label="Edit"></i>
                    <i class="fa-solid fa-trash" aria-label="Delete"></i>
                </div>
            </div>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#courseAccordion">
                <div class="accordion-body">
                    Accounting, Economics, Commerce
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <div class="accordion-header collapsed" id="headingFour" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <p class = "fw-bold" style = "color:#000;">General</p>
                <div class="d-flex">
                    <i class="fa-solid fa-pen pe-2" aria-label="Edit"></i>
                    <i class="fa-solid fa-trash" aria-label="Delete"></i>
                </div>
            </div>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#courseAccordion">
                <div class="accordion-body">
                    Civic Education,  ICT,  Physical and Health Education
                </div>
            </div>
        </div>

    </div>
    </div>

</main>
@endsection
















{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .accordion-item {
            border: 1px solid rgba(0, 0, 0, .125); /* Subtle border for separation */
            margin-bottom: 10px;
        }

        .accordion-header {
            background-color: #f8f9fa; /* Light grey header background */
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .accordion-header h5 {
            margin-bottom: 0;
            font-weight: bold; /* Category name bold */
        }

        .accordion-body {
            padding: 1rem 1.25rem;
            color: #6c757d; /* Lighter text for the subjects */
        }

        .action-icons i {
            margin-left: 15px;
            font-size: 1.1rem;
            color: #6c757d;
            cursor: pointer;
        }

        .accordion-button:not(.collapsed) {
            color: #000; /* Text color when expanded */
            background-color: #e9ecef; /* Slightly darker background when expanded */
            box-shadow: none;
        }

        /* Hide the default Bootstrap accordion caret/icon */
        .accordion-button::after {
            display: none;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="accordion" id="courseAccordion">

        <div class="accordion-item">
            <div class="accordion-header" id="headingOne" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <h5 class="w-100">Sciences</h5>
                <div class="action-icons">
                    <i class="bi bi-pencil" aria-label="Edit"></i>
                    <i class="bi bi-trash" aria-label="Delete"></i>
                </div>
            </div>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#courseAccordion">
                <div class="accordion-body">
                    **Mathematics**, **Physics**, **Chemistry**, **Further Mathematics**, **Agricultural Science**
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <div class="accordion-header collapsed" id="headingTwo" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <h5 class="w-100">Arts</h5>
                <div class="action-icons">
                    <i class="bi bi-pencil" aria-label="Edit"></i>
                    <i class="bi bi-trash" aria-label="Delete"></i>
                </div>
            </div>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#courseAccordion">
                <div class="accordion-body">
                    **English Language**, **Literature-in-English**, **Government**, **CRS**
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <div class="accordion-header collapsed" id="headingThree" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <h5 class="w-100">Commercial</h5>
                <div class="action-icons">
                    <i class="bi bi-pencil" aria-label="Edit"></i>
                    <i class="bi bi-trash" aria-label="Delete"></i>
                </div>
            </div>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#courseAccordion">
                <div class="accordion-body">
                    **Accounting**, **Economics**, **Commerce**
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <div class="accordion-header collapsed" id="headingFour" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <h5 class="w-100">General</h5>
                <div class="action-icons">
                    <i class="bi bi-pencil" aria-label="Edit"></i>
                    <i class="bi bi-trash" aria-label="Delete"></i>
                </div>
            </div>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#courseAccordion">
                <div class="accordion-body">
                    **Civic Education**, **ICT**, **Physical and Health Education**
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</body>
</html> --}}
