@extends('admin.dashboardtemp')

@section('content')
<main>
    <div class = "container">

        <div class = "d-flex justify-content-between">

            <div>
            <h3>Create New Course</h3>
            <p>Here overview of your</p>
        </div>

        <div>
        <button class = "btn rounded coursedraft">
        Save As Draft
        </button>

        <button class = "btn rounded publishcourse">
        Publish Course
        </button>

        </div>

    </div>
    </div>


    <div class = "d-flex row">

        <button class = "btn btn-light btn-outline-dark border rounded w-25 me-4">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Course Media
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button class = "btn btn-light border btn-outline-success rounded w-25 me-4">
            <i class="fa fa-arrow-left me-2"></i>
            Course Media
            <i class="fa fa-arrow-right me-2"></i>
        </button>


    </div>
</main>
@endsection
