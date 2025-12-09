{{-- @extends('admin.usertemplate') --}}
@extends('layouts.usertemplate')

@section('content')
<style>
    .card-container{
        display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1rem;
  position: relative;
  z-index: 10;
    }
    .card-item-class{
background-color: #FDAF22;
padding: 4px 28px;
border-radius: 5px;
color: #000F11;
font-size: 12px;
    }
    .enroll-btn{
        border:1px solid #004A53;
        border-radius: 4px;
        padding: 16px 20px;
        color:#004A53 ;
        font-size: 16px;
        font-weight: 600;
    }
</style>
<main>


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

    <div class  = "container position-relative " style="margin-top: -70px;">
        <div class = "card-container">

            <div class = " p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">

                <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                </div>
                <div class = "card-item-class align-self-start" >JSS 1</div>
                <h5 class = "subjects">Junior Secondary School 1</h5>
                <button class="enroll-btn">Enroll</button>
            </div>


            <div class = " p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">

                <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                </div>
                <div class = "card-item-class align-self-start" >JSS 1</div>
                <h5 class = "subjects">Junior Secondary School 1</h5>
                <button class="enroll-btn">Enroll</button>
            </div>

             <div class = " p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">

                <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                </div>
                <div class = "card-item-class align-self-start" >JSS 1</div>
                <h5 class = "subjects">Junior Secondary School 1</h5>
                <button class="enroll-btn">Enroll</button>
            </div>

             <div class = " p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">

                <div class = "border border-dark p-3" style="border-radius: 10px;">
                    <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                </div>
                <div class = "card-item-class align-self-start" >JSS 1</div>
                <h5 class = "subjects">Junior Secondary School 1</h5>
                <button class="enroll-btn">Enroll</button>
            </div>



    </div>

    </div>

    </div>

    <div class="chat-btn-circle">
        <i class="fa-solid fa-comment"></i>
    </div>

</main>
<script>


    document.addEventListener("DOMContentLoaded", () => {
        const enrollBtns = document.querySelectorAll('button.enroll-btn');
        console.log(enrollBtns)

    enrollBtns.forEach(btn => {
        console.log('click')
        btn.addEventListener('click', function(e) {
           console.log('clicked')
           e.preventDefault();
    e.stopPropagation();
            window.location.href = '/userenroll';

    });

    });
});



</script>
@endsection
