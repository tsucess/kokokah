{{-- @extends('admin.usertemplate') --}}
@extends('users.usertemplate')

@section('content')
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

    <!-- Header -->
  <div class="header-section container-fluid">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-12 col-md-6 mt-2">
          <h3>Class</h3>
          <p>Let’s learn something new today!</p>
        </div>

      </div>
    </div>
  </div>

    <div class  = "container position-relative " style="margin-top: -70px; z-index:5;">
        <div class = "row justify-content-between mx-auto">

            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">

                <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                </div>
                <button class = "btn primaryButton mt-2 mb-2" type = "button">JSS 1</button>
                <h5 class = "subjects">Junior Secondary School 1</h5>

                <button class="btn w-100 secondaryButton" type="button">Enroll</button>
            </div>


            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">
            <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
            </div>
                <button class = "btn primaryButton mt-2 mb-2" type = "button">JSS 2</button>
                <h5 class = "subjects">Junior Secondary School 2</h5>
                <button class="btn w-100 secondaryButton" type="button">Enroll</button>
            </div>

            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">
            <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
            </div>
                <button class = "btn primaryButton mt-2 mb-2" type = "button">JSS 3</button>
                <h5 class = "subjects">Junior Secondary School 3</h5>
            <button class="btn w-100 secondaryButton" type="button">Enroll</button>

        </div>
    </div>





    <div class = "row justify-content-between">

            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">

                <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                </div>
                <button class = "btn primaryButton mt-2 mb-2" type = "button">SS 1</button>
                <h5 class = "subjects">Senior Secondary School 1</h5>
                <button class="btn w-100 secondaryButton" type="button">Enroll</button>
            </div>


            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">
            <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
            </div>
                <button class = "btn primaryButton mt-2 mb-2" type = "button">SS 2</button>
                <h5 class = "subjects">Senior Secondary School 2</h5>
                <button class="btn w-100 secondaryButton" type="button">Enroll</button>
            </div>

            <div class = "col-12 col-md-4 col-lg-4 mb-5 p-3 bg-white mysubject">
            <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
            </div>
                <button class = "btn primaryButton mt-2 mb-2" type = "button">SS 3</button>
                <h5 class = "subjects">Senior Secondary School 3</h5>
                <button class="btn w-100 secondaryButton" type="button">Enroll</button>

        </div>
    </div>

    </div>

    <div class="chat-btn-circle">
        <i class="fa-solid fa-comment"></i>
    </div>

</main>
@endsection
