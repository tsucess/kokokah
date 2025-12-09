@extends('layouts.usertemplate')
@section('content')
 <style>
    .stats-card {
      border: none;
      border-radius: 20px;
      background: #fff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
      transform: translateY(-5px);
    }

    .stats-card:hover {
      transform: translateY(-10px);
    }

    .icon-circle img {
      width: 48px;
      height: 48px;
    }

    @media (max-width: 768px) {
      .header-section {
        text-align: center;
      }
      .header-image {
        margin-top: 1rem;
        width: 150px;
      }
    }
  </style>

<main>
    {{-- <div class="container m-2">
<div class="row">
        <div>
          <h4>Hello Samuel
            <i class="fa-solid fa-hands-clapping text-warning"></i>
          </h4>
          <p>Let`s learn something new today</p>
        </div>

      </div>

    </div> --}}


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

 <!-- Header -->
  <div class="header-section container-fluid">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-12 col-md-6 mt-5">
          <h3>Hello Winner 👋</h3>
          <p>Let’s learn something new today!</p>
        </div>
        <div class="col-12 col-md-6 text-md-end text-center">
          <img src="images/mydashboard.png" alt="Robot"  class="header-image img-fluid position-absolute end-0 bottom-0 translate-middle-y"
             style="max-height: 300px; z-index: 5;  top: 60%;">
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="container" style="margin-top: -40px;">
    <div class="row g-3">

      <div class="col-12 col-md-6">
        <div class="d-flex align-items-center justify-content-between p-4 stats-card h-100">

          <div class="d-flex align-items-center">
            <div class="icon-circle me-3">
              <img src="images/celebration.png" class="img-fluid" alt="Completed">
            </div>
            <div>
              <strong class="fs-2 lh-1 d-block text-dark">24</strong>
              <small class="text-muted text-nowrap">Completed Subject</small>
            </div>
          </div>

          <div class="text-success d-flex flex-column align-items-center ms-auto">
            <i class="fa-solid fa-arrow-trend-up text-success"></i>
            <small class="fw-bold">1.3%</small>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6">
        <div class="d-flex align-items-center justify-content-between p-4 stats-card h-100">

          <div class="d-flex align-items-center">
            <div class="icon-circle me-3">
              <img src="images/note.png" class="img-fluid" alt="Pending">
            </div>
            <div>
              <strong class="fs-2 lh-1 d-block text-dark">07</strong>
              <small class="text-muted text-nowrap">Pending Subject</small>
            </div>
          </div>

          <div class="text-danger d-flex flex-column align-items-center ms-auto">
            <i class="fa-solid fa-arrow-trend-up text-danger"></i>
            <small class="fw-bold">1.3%</small>
          </div>
        </div>
      </div>

    </div>
  </div>


















    <div class  = "container ">
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

            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 " mysubject>

                <div class = "border border-dark p-3 " style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                </div>
                <button class = "btn primaryButton mt-2 mb-2" type = "button">JSS 1</button>
                <h5 class = "subjects">English Language
                    <span class = "float-end">60%</span>
                </h5>
                <div class="progress mb-3" style = "height:10px;">
                <div class="progress-bar" style="width:70%; background:#F56824;"></div>
                </div>
                <button class="btn w-100 secondaryButton" type="button">View Subjects</button>
            </div>


            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">
            <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
            </div>
                <button class = "btn primaryButton mt-2 mb-2" type = "button">JSS 2</button>
                <h5 class = "subjects">Mathematics
                <span class = "float-end">60%</span>
                </h5>
                <div class="progress mb-3" style = "height:10px;">
                <div class="progress-bar" style="width:70%; background:#F56824;"></div>
                </div>
                <button class="btn w-100 secondaryButton" type="button">View Subjects</button>
            </div>

            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">
            <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
            </div>
                <button class = "btn primaryButton mt-2 mb-2" type = "button">JSS 3</button>
                <h5 class = "subjects">Computer Science
                <span class = "float-end">60%</span>
                </h5>
                <div class="progress mb-3" style = "height:10px;">
                <div class="progress-bar" style="width:70%; background:#F56824;"></div>
                </div>
                <button class="btn w-100 secondaryButton" type="button">View Subjects</button>

        </div>
    </div>

    </div>

    <div class="chat-btn-circle">
        <i class="fa-solid fa-comment"></i>
    </div>

</main>
@endsection
