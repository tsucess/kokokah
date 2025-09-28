@extends('admin.usertemplate')

@section('content')
<main>
    <div class="container m-2">
<div class="row">
        <div>
          <h4>Hello Samuel
            <i class="fa-solid fa-hands-clapping text-warning"></i>
          </h4>
          <p>Let`s learn something new today</p>
        </div>

      </div>

    </div>


    {{-- <div class = "row">
        <div class = "d-flex justify-content-between">

            <div class = "col-12 col-md-6 col-lg-6">
                <img src="images/celebration.png" class="img-fluid" width = 50 height = 50>
                <span>24</span><br>
                <span>Completed Subject</span>

            </div>


            <div class = "col-12 col-md-6 col-lg-6">
                <img src="images/celebration.png" class="img-fluid">
                <span>24<br>
                <span>Completed Subject</span>
            </span>
            </div>

        </div>
    </div> --}}

  <div class="row g-3 m-2">

        <div class="col-12 col-md-6">
            <div class="d-flex align-items-center justify-content-between m-1 p-4 border rounded shadow-sm h-100">

                <div class="d-flex align-items-center">

                    <div class="icon-circle me-3">
                        {{-- <i class="bi bi-patch-check-fill icon-badge text-warning"></i> --}}
                        <img src = "images/celebration.png"  class="img-fluid">
                    </div>

                    <div>
                        <strong class="fs-2 lh-1 d-block">24</strong> <small class="text-muted text-nowrap">Completed Subject</small>
                    </div>
                </div>

                <div class="text-success d-flex flex-column align-items-center ms-auto">
                    {{-- <i class="bi bi-arrow-up text-success fs-5"></i> --}}
                    <i class="fa-solid fa-arrow-trend-up text-success"></i>
                    <small class="fw-bold">1.3%</small>
                </div>

            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="d-flex align-items-center justify-content-between m-1 p-4 border rounded shadow-sm h-100">

                <div class="d-flex align-items-center">

                    <div class="icon-circle me-3">
                        {{-- <i class="bi bi-clipboard-check-fill icon-badge text-info"></i> --}}
                        <img src = "images/note.png"  class="img-fluid">
                    </div>

                    <div>
                        <strong class="fs-2 lh-1 d-block">07</strong>
                        <small class="text-muted text-nowrap">Pending Subject</small>
                    </div>
                </div>

                <div class="text-success d-flex flex-column align-items-center ms-auto">
                    {{-- <i class="bi bi-arrow-up text-success fs-5"></i> --}}
                    <i class="fa-solid fa-arrow-trend-up text-success"></i>
                    <small class="fw-bold text-danger">1.3%</small>
                </div>

            </div>
        </div>

    </div>



    <div class  = "container m-2">
        <div class ="d-flex justify-content-between">
            <div>
            <p class = "usersparagraph">
                Continue reading
            </p>
            </div>

        <div>
            <i class="fa-solid fa-circle-chevron-left"></i>
            <i class="fa-solid fa-circle-chevron-right"></i>

        </div>

        </div>

        <div class = "row justify-content-between">

            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white" mysubject>

                <div class = "border border-dark p-3 " style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                </div>
                <button class = "btn userbutton mt-2 mb-2" type = "button">JSS 1</button>
                <h5 class = "subjects">English Language</h5>
                <div class="progress" style = "height:10px;">
                <div class="progress-bar bg-success" style="width:70%"></div>
                </div>
                <button class="btn secondaryButton" type="button">View Subjects</button>
            </div>


            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">
            <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
            </div>
                <button class = "btn userbutton mt-2 mb-2" type = "button">JSS 2</button>
                <h5 class = "subjects">Mathematics</h5>
                <div class="progress" style = "height:10px;">
                <div class="progress-bar bg-success" style="width:70%"></div>
                </div>
                <button class="btn secondaryButton" type="button">View Subjects</button>
            </div>

            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">
            <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
            </div>
                <button class = "btn userbutton mt-2 mb-2" type = "button">JSS 3</button>
                <h5 class = "subjects">Computer Science</h5>
                <div class="progress" style = "height:10px;">
                <div class="progress-bar bg-success" style="width:70%"></div>
                </div>
                <button class="btn secondaryButton" type="button">View Subjects</button>

        </div>
    </div>

    </div>

    <div class="chat-btn-circle">
        <i class="fa-solid fa-comment"></i>
    </div>

</main>
@endsection
