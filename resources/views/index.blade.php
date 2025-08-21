@extends('layouts.template')

@section('content')
<div class = "container-fluid p-5 banner">
<div class = "row mt-4">
<div class = "col-12 col-md-6 col-lg-6 my-auto">
{{-- <img src = "images/Platform.png" class = "img-fluid"> --}}
<h1>
    All-In-One smarter, easier and affordable learning with Kokokah
</h1>
<p class = "mt-3">
    We connect African students and educators to high-quality,
    affordable smart learning platforms, accessible anytime and anywhere.
</p>

<div class = "d-flex flex-column flex-sm-row gap-2">
    <button class = "btn text-white ps-4 pe-5 w-100 w-sm-auto" style = "background:#004A53;">Explore Kokokah Features</button>
    <button class = "btn btn-outline-success text-success ps-5 pe-5 w-100 w-sm-auto" >Get a Demo</button>
</div>


{{-- <div class="d-flex mt-4 justify-content-start">
<div class="rounded-circle d-flex align-items-center justify-content-center me-3  flex-shrink-0"
           style="width:50px; height:50px; background-color:#d3e3e3;">
        <i class="fa-solid fa-users" style="color:#004A53; font-size:24px;"></i>
      </div>
      <div class = "pe-2">
        <h6 class="mb-1">100,000</h6>
        <p class="mb-0 small">Users</p>
      </div>


      <div class="rounded-circle d-flex align-items-center justify-content-center m-1 mx-auto"
           style="width:50px; height:50px; background-color:#d3e3e3;">
        <i class="fa-solid fa-book-open" style="color:#004A53; font-size:24px;"></i>
      </div>
      <div class = "ms-2 pe-2" style = "width:150px;">
        <h6 class="mb-1">100,000</h6>
        <p class="mb-0 small">Learning Materials</p>
      </div>

       <div class="rounded-circle d-flex align-items-center justify-content-center m-1 mx-auto"
           style="width:50px; height:50px; background-color:#d3e3e3;">
        <i class="fa-solid fa-users" style="color:#004A53; font-size:24px;"></i>
      </div>
      <div class = "ms-2 pe-2">
        <h6 class="mb-1">100,000</h6>
        <p class="mb-0 small">Users</p>
      </div>

      <div class="rounded-circle d-flex align-items-center justify-content-center m-1 mx-auto"
           style="width:50px; height:50px; background-color:#d3e3e3;">
        <i class="fa-solid fa-book-open" style="color:#004A53; font-size:24px;"></i>
      </div>
      <div class = "ms-2 pe-2" style = "width:150px;">
        <h6 class="mb-1">100,000</h6>
        <p class="mb-0 small">Learning Materials</p>
      </div>

    </div> --}}

</div>


<div class = "col-12 col-md-6 col-lg-6">
<img src = "images/LMS.png" class = "img-fluid">
</div>

</div>


<div class="row justify-content-start">

    <!-- Users -->
    <div class="col-6 col-md-3 d-flex flex-column align-items-center mb-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
             style="width:50px; height:50px; background-color:#d3e3e3;">
            <i class="fa-solid fa-users" style="color:#004A53; font-size:24px;"></i>
        </div>
        <h6 class="mb-1 text-center">100,000</h6>
        <p class="mb-0 small text-center">Users</p>
    </div>

    <!-- Learning Materials -->
    <div class="col-6 col-md-3 d-flex flex-column align-items-center mb-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
             style="width:50px; height:50px; background-color:#d3e3e3;">
            <i class="fa-solid fa-book-open" style="color:#004A53; font-size:24px;"></i>
        </div>
        <h6 class="mb-1 text-center">100,000</h6>
        <p class="mb-0 small text-center">Learning Materials</p>
    </div>

    <!-- Users (duplicate example) -->
    <div class="col-6 col-md-3 d-flex flex-column align-items-center mb-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
             style="width:50px; height:50px; background-color:#d3e3e3;">
            <i class="fa-solid fa-users" style="color:#004A53; font-size:24px;"></i>
        </div>
        <h6 class="mb-1 text-center">100,000</h6>
        <p class="mb-0 small text-center">Users</p>
    </div>

    <!-- Learning Materials (duplicate example) -->
    <div class="col-6 col-md-3 d-flex flex-column align-items-center mb-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
             style="width:50px; height:50px; background-color:#d3e3e3;">
            <i class="fa-solid fa-book-open" style="color:#004A53; font-size:24px;"></i>
        </div>
        <h6 class="mb-1 text-center">100,000</h6>
        <p class="mb-0 small text-center">Learning Materials</p>
    </div>

</div>




</div>

<div class = "container-fluid mb-5">
<div class = "row mt-4 bg-white">
<div class = "col-12 col-md-6 col-lg-6 my-auto">
<img src = "images/Video.png" class = "img-fluid">
</div>


<div class = "col-12 col-md-6 col-lg-6 my-auto">
    <h2>
        Kokokah for All.
    </h2>
    <p>
        Kokokah is a smart, pan-African learning and school management platform
        built for the realities of African education. Whether you’re a JSS1 student
         in Ghana, an SSS3 student in Kenya, or an educator in South Africa, our
         mission is simple — to give every learner from any background the opportunity
         to excel with ease.
    </p>
    <button class = "btn text-white ps-4 pe-5" style = "background:#004A53;">Discover Kokokah</button>
</div>

</div>
</div>


<div class = "container-fluid">
    <div class = "row d-flex justify-content-between">

        <div class = "col-12 col-md-3 col-lg-3">
    </div>

    <div class = "col-12 col-md-6 col-lg-6 text-center">
        {{-- <img src = "images/Kokokah_works.png" class = "img-fluid w-50"> --}}
        <h2>
            Why Kokokah is the best
    </h2>
        <p>
            Kokokah combines mobile learning, exam preparation and a
            school learning management system, helping schools automate
            tasks efficiently, offering parents high-quality affordable
            learning options and boosting overall student performance.
        </p>
    </div>

    <div class = "col-12 col-md-3 col-lg-3">
        <img src = "images/trace1.png" class = "img-fluid w-25 float-end">
    </div>
</div>


<div class = "row d-flex justify-content-between">

        <div class = "col-12 col-md-3 col-lg-3">
    </div>

    <div class = "d-flex justify-content-between col-12 col-md-6 col-lg-6">
        <div class ="w-75">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                For Students, <br>
                 Parents & Schools
            </h6>
            <p>
                One platform for all.
            </p>
        </div>

        <div class ="w-75">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                Accessible mobile <br>
                learning
            </h6>
            <p>
                Study anywhere, anytime — even on low internet.
                Learn on your phone, tablet, or computer without missing a beat.
            </p>
        </div>


        <div class ="w-75">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                AI-integrated and <br>
                automated features
            </h6>
            <p>
                Get instant answers, personalized feedback, and quick
                grading with our built-in Artificial Intelligence — saving
                time for both students and educators.
            </p>
        </div>

    </div>


    <div class = "col-12 col-md-3 col-lg-3">
    </div>

</div>


<div class = "row d-flex justify-content-between">

        <div class = "col-12 col-md-3 col-lg-3">
    </div>

    <div class = "d-flex justify-content-between col-12 col-md-6 col-lg-6">
        <div class ="w-75">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                Affordable<br>
                 Subscription plans
            </h6>
            <p>
                Choose a plan that fits your budget
                and needs — monthly, quarterly, or yearly,
                all with full platform access.
            </p>
        </div>

        <div class ="w-75">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                Virtual Payment
            </h6>
            <p>
                Store and track money for any resource purchase on
                Kokokah - quick, safe, and hassle-free.
            </p>
        </div>


        <div class ="w-75">

        </div>

    </div>


    <div class = "col-12 col-md-3 col-lg-3">
    </div>

</div>




<div class = "row d-flex justify-content-between">

        <div class = "col-12 col-md-3 col-lg-3">
            <img src = "images/Kokokah_works2.png" class = "p-2 img-fluid w-25">
    </div>

            <div class = "col-12 col-md-6 col-lg-6">
    </div>

            <div class = "col-12 col-md-3 col-lg-3">
    </div>
</div>


<div class = "row">
    <div class = "col col-md-2 col-lg-2">

    </div>

    <div class = "col col-md-8 col-lg-8 text-center">
    <h2 class = "fw-bold" style = "color:#004A53;" >Kokokah for
    <span class = "fw-bold" style = "color: #F56824;">You.</span>
    </h2>
    <p>
Kokokah brings you a suite of powerful learning tools designed to
transform how African students, parents, and educators connect, learn, and thrive.
    </p>
    <button class = "btn text-white ps-4 pe-5" style = "background:#004A53;">Explore Features</button>
</div>

<div class = "col col-md-2 col-lg-2">
    <img src = "images/Ellipse 24.png" class = "img-fluid w-25 float-end" />
    </div>


</div>


<div class = "row d-flex justify-content-between">

        <div class = "col-12 col-md-3 col-lg-3">
            <img src = "images/Ellipse 23.png" class = "p-2 img-fluid w-25">
    </div>

            <div class = "col-12 col-md-6 col-lg-6">
    </div>

            <div class = "col-12 col-md-3 col-lg-3">
    </div>
</div>

<div class="row w-100 mx-auto my-4 p-4 jumbotron">

    <!-- Text Section -->
    <div class="col-12 col-md-6 d-flex flex-column justify-content-center  p-3">
        <h2>
            Passnownow
        </h2>
        <p>
            Kokokah houses an exam preparatory platform
            where African students can prepare adequately
            for both local and international examinations.
        </p>

        <p>
            WAEC - NECO - JAMB - GCE - GMAT - SAT - TOEFL - IELTS - GRE - ACT
        </p>

        <div>
        <button class = "btn text-white" style = "background:#004A53;">Passnownow</button>
        </div>

    </div>

    <!-- Image Section -->
    <div class="col-12 col-md-6 text-center p-3">
        <img src="images/exam_prep.png" class="img-fluid w-75" alt="Exam Prep">
    </div>
</div>


<div class="row w-100 mx-auto my-4 p-2 border border-dark" style="border-radius: 30px;">

    <!-- Image Section -->
    <div class="col-12 col-md-6 text-center">
        <img src="images/School Admin.png" class="img-fluid w-75" alt="School Admin">
    </div>

    <!-- Text Section -->
    <div class="col-12 col-md-6 d-flex flex-column">
        <h3 class ="mt-4">
            School Management System (SMS)
        </h3>
        <p class="fs-5">
            Simplify school administration with fee
             tracking, digital report cards, attendance, and student
              portals — all in one secure platform.
        </p>

                <div>
        <button class = "btn text-white" style = "background:#004A53;">Explore SMS</button>
        </div>

    </div>
</div>



<div class="row w-100 mx-auto my-4 p-4 jumbotron">

    <!-- Text Section -->
    <div class="col-12 col-md-6 d-flex flex-column justify-content-center p-3">
        <h3>
            kokokah LMS
        </h3>
        <p class="fs-5">
            Your all-in-one digital classroom — structured lessons, AI-powered
            tutoring, chatrooms, and  academic & non-academic content for secondary school.
        </p>

          <div>
        <button class = "btn text-white" style = "background:#004A53;">Explore LMS</button>
        </div>
    </div>

    <!-- Image Section -->
    <div class="col-12 col-md-6 text-center p-3">
        <img src="images/lms system.png" class="img-fluid w-75" alt="LMS system">
    </div>
</div>


<div class="row w-100 mx-auto my-4 p-2 border border-dark" style="border-radius: 30px;">

    <!-- Image Section -->
    <div class="col-12 col-md-6 text-center">
        <img src="images/School Admin.png" class="img-fluid w-75" alt="School Admin">
    </div>

    <!-- Text Section -->
    <div class="col-12 col-md-6 d-flex flex-column">
        <h3 class ="mt-4">
            The Marketplace
        </h3>
        <p class="fs-5">
           Africa’s academic forum for parents, teachers, and
           tutors to connect. Book trusted tutors for academics,
           test prep, and special needs learning.
        </p>

                <div>
        <button class = "btn text-white" style = "background:#004A53;">Explore Marketplace</button>
        </div>

    </div>
</div>


<div class="row w-100 mx-auto my-4 p-4 jumbotron">

    <!-- Text Section -->
    <div class="col-12 col-md-6 d-flex flex-column justify-content-center p-3">
        <h3>
            AI Chatbot
        </h3>
        <p class="fs-5">
            Your personal academic assistant — ask questions, get explanations,
            and enjoy instant feedback tailored to your study needs.
        </p>

          <div>
        <button class = "btn text-white" style = "background:#004A53;">Get a Demo</button>
        </div>
    </div>

    <!-- Image Section -->
    <div class="col-12 col-md-6 text-center p-3">
        <img src="images/lms system.png" class="img-fluid w-75" alt="LMS system">
    </div>
</div>


<div class = "row mt-4">
<div class = "col-12 col-md-6 col-lg-6">
<img src = "images/LMS.png" class = "img-fluid">
</div>



<div class = "col-12 col-md-6 col-lg-6 my-auto">
<h2 class="fw-bold fs-1" style="color: #004A53;">
            Koodies for <span style="color: #F56824;">Children</span>
        </h2>
<p class = "mt-3">
    Kokokah combines School Management, Exam Prep, and a
    Learning Management System (LMS)—helping schools automate admin tasks,
    boost student performance, and deliver modern digital learning in one
    seamless platform. Kokokah combines School Management, Exam Prep, and a
    Learning Management System (LMS)—helping schools automate admin tasks, boost
    student performance, and deliver modern digital learning in one seamless platform.
</p>
<button class = "btn text-white ps-4 pe-5" style = "background:#004A53;">Discover Koodies</button>

<div>
<img src = "images/koodies.png" class = "img-fluid w-50 float-end">
</div>
</div>

</div>


<div class = "row">

<div class = "col-12 col-md-2 col-lg-2">
</div>

<div class = "col-12 col-md-8 col-lg-8 mt-5">
    <h2 class = "text-center">
        See how Kokokah transforms your school’s learning,
        exams, and operations—all in under a minute.
    </h2>
</div>

<div class = "col-12 col-md-2 col-lg-2">
</div>

</div>




<div class = "row">

<div class = "col-12 col-md-2 col-lg-2">
</div>

<div class = "col-12 col-md-8 col-lg-8 mt-4">
    <img src = "images/youtube.png" class="img-fluid w-100 h-100">
</div>

<div class = "col-12 col-md-2 col-lg-2">
</div>

<div class = "col-12 col-md-3 col-lg-3">
            <img src = "images/Kokokah_works2.png" class = "p-2 img-fluid w-25">
    </div>

</div>


</div>


@endsection
