@extends('layouts.template')

@section('content')
    <!-- Hero Section - Yellow Background -->
    <div class="container-fluid" style="background-color: #FDAF22; padding: 60px 40px;">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 col-lg-6">
                <h1 class="fw-bold"
                    style="font-size: 56px; line-height: 1.2; color: #000000; font-family: 'Fredoka', sans-serif; margin-bottom: 24px;">
                    Quality, Mobile-First, Pay-as-you-Go, Curriculum Based Lessons & Practice Tests
                </h1>
                <p class="mb-4" style="font-size: 16px; color: #1C1D1D; line-height: 1.6;">
                    LOW DATA USAGE + OFFLINE ACCESS + SCHOOL MANAGEMENT SYSTEM
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <button class="btn fw-bold px-5 py-3"
                        style="background-color: #004A53; color: white; border: none; border-radius: 8px;">Start Using
                        Kokokah</button>
                    <button class="btn fw-bold px-5 py-3"
                        style="background-color: white; color: #004A53; border: 2px solid #004A53; border-radius: 8px;">Signup
                        Now</button>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 text-center mt-4 mt-md-0">
                <img src="images/LMS.png" class="img-fluid" alt="LMS Illustration">
            </div>
        </div>
    </div>



    <div class = "container  mb-5">
        <div class = "row mt-4  bg-white">
            <div class = "col-12 col-md-6 col-lg-6 my-auto">
                <img src = "images/Video.png" class = "img-fluid">
            </div>


            <div class = "col-12 col-md-6 col-lg-6 pt-2">
                <h3>
                    Kokokah for All.
                </h3>
                <p class = "heroparagraph">
                    Kokokah is a smart, pan-African learning and school management platform
                    built for the realities of African education. Whether you’re a JSS1 student
                    in Ghana, an SSS3 student in Kenya, or an educator in South Africa, our
                    mission is simple — to give every learner from any background the opportunity
                    to excel with ease.
                </p>
                {{-- <button class = "btn text-white ps-4 pe-5" style = "background:#004A53;">Discover Kokokah</button> --}}
                <div class = "button-container d-flex d-md-flex gap-3">
                    <button class="btn primaryButton" type="button">Discover Kokokah</button>
                </div>

            </div>
        </div>

    </div>


    {{-- <div class="container">
    <div class = "row">

    <div class = "d-flex">
    <div class = "w-100 text-center p-5">
        <h1>
            Why Kokokah is the best
    </h1>
        <p class = "heroparagraph">
            Kokokah combines mobile learning, exam preparation and a
            school learning management system, helping schools automate
            tasks efficiently, offering parents high-quality affordable
            learning options and boosting overall student performance.
        </p>
    </div>

    <div class = "flex-shrink-1">
        <img src = "images/trace1.png" class = "img-fluid">
    </div>
</div>
    </div> --}}



    {{-- <div class="container py-3 ">
  <div class="row align-items-center">
    <!-- Text Column -->
    <div class="col-12 col-md-10 text-center">
      <p class="mb-0">
        mobile learning, exam preparation and a school learning management system,
        helping schools automate tasks efficiently, offering parents high-quality
        affordable learning options and boosting overall student performance.
      </p>
    </div>

    <!-- Image Column -->
    <div class="col-12 col-md-2 text-md-end text-center mt-3 mt-md-0">
      <img src="your-image.png" alt="design" class="img-fluid" style="max-width:120px;">
    </div>
  </div>
</div> --}}




    {{-- <div class = "container">
<div class = "row g-2 justify-content-center mx-auto">
        <div class = "d-flex ">

        <div class = "w-100 text-center">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                For Students, <br>
                 Parents & Schools
            </h6>
            <p>
                One platform for all.
            </p>
        </div>

        <div class = "w-100 text-center">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                Accessible mobile <br>
                learning
            </h6>
            <p>
                Study anywhere, anytime — even<br>
                on low internet. Learn on your<br>
                phone, tablet, or computer<br>
                without missing a beat.
            </p>
        </div>


        <div class = "w-100 text-center">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                AI-integrated and <br>
                automated features
            </h6>
            <p>
                Get instant answers, personalized<br>
                feedback, and quick grading with our<br>
                built-in Artificial Intelligence — <br>
                saving time for both students and<br>
                educators.
            </p>
        </div>

    </div>
</div>

</div>



<div class = "container ">
<div class = "row g-2 justify-content-center mx-auto">

        <div class = "d-flex">

        <div class ="w-100 text-center">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                Affordable<br>
                 Subscription plans
            </h6>
            <p>
                Choose a plan that fits your budget<br>
                and needs — monthly, quarterly, or <br>
                yearly, all with full platform access.
            </p>
        </div>

        <div class ="w-100 text-center">
            <i class="fa-solid fa-download" style = "color:#F56824;"></i>
            <h6 class = "mt-3 text-success">
                Virtual Payment
            </h6>
            <p>
                Store and track money for any resource purchase on
                Kokokah - quick, safe, and hassle-free.
            </p>
        </div>

        <div class ="w-100 text-center"></div>

</div>
</div>
</div> --}}


    {{-- <div class = "container-fluid pt-5  text-center" style = "min-height:700px;  background-image: url('images/kokokah_best.png'); background-repeat: no-repeat;  background-size: contain;"> --}}
    <div class = "container-fluid pt-5  text-center"
        style = "min-height:600px;  background-repeat: no-repeat;  background-size: cover;">
        <div class = "row">
            <h4 class = "mb-2">
                Why Kokokah Is the Best
            </h4>

            <p>
                Kokokah combines mobile learning, exam preparation and a school
                learning management system, helping schools automate tasks efficiently,<br>
                offering parents high-quality affordable learning options and boosting
                overall student performance.
            </p>
        </div>
        <div class="container">
            <div class = "row">

                <!-- Feature 1 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card position-relative">
                        <span class="feature-number">01</span>
                        <div class="feature-content">
                            {{-- <div class="feature-icon mb-2">📘</div> --}}
                            <i class="fa-solid fa-download" style="color: #f56824;"></i>
                            <h6>
                                For Students, <br>
                                Parents & Schools
                            </h6>
                            <p>One platform for all.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card position-relative">
                        <span class="feature-number">02</span>
                        <div class="feature-content">
                            {{-- <div class="feature-icon mb-2">📱</div> --}}
                            <i class="fa-solid fa-download" style="color: #f56824;"></i>
                            <h6>
                                Accessible mobile<br>
                                learning
                            </h6>
                            <p>Study anywhere, anytime — even on low internet. Learn on your phone, tablet, or computer
                                without missing a beat.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card position-relative">
                        <span class="feature-number">03</span>
                        <div class="feature-content">
                            {{-- <div class="feature-icon mb-2">🤖</div> --}}
                            <i class="fa-solid fa-download" style="color: #f56824;"></i>
                            <h6>
                                AI-integrated and<br>
                                automated features
                            </h6>
                            <p>Get instant answers, personalized feedback, and quick grading with our built-in AI — saving
                                time for both students and educators.</p>
                        </div>
                    </div>
                </div>

            </div>



            <div class = "row">
                <!-- Feature 4 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card position-relative">
                        <span class="feature-number">01</span>
                        <div class="feature-content">
                            {{-- <div class="feature-icon mb-2">💰</div> --}}
                            <i class="fa-solid fa-download" style="color: #f56824;"></i>
                            <h6>
                                Affordable<br>
                                subscription plans
                            </h6>
                            <p>Choose a plan that fits your budget and needs — monthly, quarterly, or yearly, all with full
                                platform access.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card position-relative">
                        <span class="feature-number">02</span>
                        <div class="feature-content">
                            {{-- <div class="feature-icon mb-2">💳</div> --}}
                            <i class="fa-solid fa-download" style="color: #f56824;"></i>
                            <h5>Virtual payment</h5>
                            <p>Store and track money for any resource purchase on Kokokah — quick, safe, and hassle-free.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Empty Placeholder (Only visible on lg and above) -->
                <div class="col-lg-4 d-none d-lg-block"></div>

            </div>
        </div>

    </div>


    <div class = "container-fluid  bg-container-fluid"
        style="background:url('images/background.png'); background-repeat:no-repeat; background-size:contain;">

        <h3>Our Products</h3>

        <p>
            Kokokah brings you a suite of powerful learning tools
            designed to transform how African students, parents, and
            educators connect, learn, and thrive.
        </p>

        <button class = "btn primaryButton" type = "button">Explore Features</button>

    </div>



    <div class = "container mt-5">
        <div class = "row flex-column-reverse flex-md-row m-2 ourproduct1" style = "background: #F56824;">

            <div class = "col-12 col-md-7 col-lg-7 p-5">
                <h5 class = "text-white"> Passnownow </h5>

                <p class = "text-white">
                    Kokokah houses an exam preparatory platform
                    where African students can prepare adequately
                    for both local and international examinations.
                </p>

                <p class = "text-white">
                    WAEC - NECO - JAMB - GCE - GMAT - SAT - TOEFL - IELTS - GRE - ACT
                </p>

                <div>
                    <button class = "btn primaryButton" type = "button">Explore Passnownow</button>
                </div>
            </div>


            <div class="col-12 col-lg-5 col-md-5 text-center p-5">
                <img src="images/exam_prep.png" class="img-fluid w-100 w-md-75" alt="Exam Prep">
            </div>

        </div>

    </div>


    <div class = "container">
        <div class="row ourproduct2" style = "border-color: #FDAF22; border-width:2px;">
            <!-- Image Section -->
            <div class="col-12 col-md-6 col-lg-6">
                <img src="images/School Admin.png" class="img-fluid" alt="School Admin">
            </div>

            <!-- Text Section -->
            <div class="col-12 col-md-6 col-lg-6  my-auto">
                <h5>School Management System (SMS)</h5>

                <p class = "pe-5">
                    Simplify school administration with fee
                    tracking, digital report cards, attendance, and student
                    portals — all in one secure platform.
                </p>

                <button class = "btn mb-4 primaryButton" type="button">Explore SMS</button>

            </div>
        </div>
    </div>




    <div class = "container">
        <div class = "row flex-column-reverse flex-md-row ourproduct1" style = "background: #004A53;">
            <div class = "col-12 col-md-6 col-lg-6 p-5 my-auto">
                <h4 class = "text-white"> kokokah LMS </h4>
                <p class = "text-white">
                    Your all-in-one digital classroom — structured lessons, AI-powered
                    tutoring, chatrooms, and  academic & non-academic content for secondary school.
                </p>
                <button class = "btn primaryButton" type = "button">Explore LMS</button>
            </div>
            <div class="col-12 col-md-6 text-center">
                <img src="images/lms system.png" class="img-fluid w-100 w-md-75" alt="Exam Prep">
            </div>
        </div>
    </div>

    <div class = "container">
        <div class="row ourproduct2" style="border-radius: 30px; border-color: #FDAF22; border-width:2px;">

            <!-- Image Section -->
            <div class="col-12 col-md-6 my-auto text-center">
                <img src="images/School Admin.png" class="img-fluid w-100 w-md-75" alt="School Admin">
            </div>

            <!-- Text Section -->
            <div class="col-12 col-md-6 my-auto d-flex flex-column">
                <h5>
                    The Marketplace
                </h5>
                <p>
                    Africa’s academic forum for parents, teachers, and
                    tutors to connect. Book trusted tutors for academics,
                    test prep, and special needs learning.
                </p>

                <div class = "mb-3">
                    <button class = "btn primaryButton" type = "button">Explore Marketplace</button>

                </div>

            </div>
        </div>
    </div>


    <div class = "container">
        {{-- <div class="row  my-4 p-2 flex-column-reverse flex-md-row m-2 ourproduct1" style="background: #FDAF22;"> --}}
        <div class = "row flex-column-reverse flex-md-row ourproduct1" style = "background: #FDAF22;">
            <!-- Text Section -->
            <div class="col-12 col-md-6 d-flex flex-column justify-content-center p-5">
                <h5>
                    AI Chatbot
                </h5>
                <p>
                    Your personal academic assistant — ask questions, get explanations,
                    and enjoy instant feedback tailored to your study needs.
                </p>

                <div>
                    <button class = "btn primaryButton" type = "button" style="background: #004A53;">Coming Soon</button>
                </div>
            </div>

            <!-- Image Section -->
            <div class="col-12 col-md-6 text-center">
                <img src="images/lms system.png" class="img-fluid w-100 w-md-75" alt="LMS system">
            </div>
        </div>

    </div>



    <div class = "container">
        <div class="row ourproduct2" style="border-radius: 30px; border-color: #FDAF22; border-width:2px;">

            <!-- Image Section -->
            <div class="col-12 col-md-6 my-auto text-center">
                <img src="images/School Admin.png" class="img-fluid w-100 w-md-75" alt="School Admin">
            </div>

            <!-- Text Section -->
            <div class="col-12 col-md-6 my-auto d-flex flex-column">
                <h5>
                    STEM Labs
                </h5>
                <p>
                    Get hands-on STEM bootcamps, summer schools,
                    and practical learning experiences to prepare
                    students for the future of science & tech.
                </p>

                <div class = "mb-3">
                    <button class = "btn primaryButton" type = "button">Register</button>

                </div>

            </div>
        </div>
    </div>


    <div class = "container">
        <div class = "row mt-4">
            <div class = "col-12 col-md-5 col-lg-5">
                <img src = "images/LMS.png" class = "img-fluid">
            </div>



            <div class = "col-12 col-md-7 col-lg-7">
                <h5>
                    Koodies for Children
                </h5>
                <p>
                    Kokokah combines School Management, Exam Prep, and a
                    Learning Management System (LMS)—helping schools automate admin tasks,
                    boost student performance, and deliver modern digital learning in one
                    seamless platform. Kokokah combines School Management, Exam Prep, and a
                    Learning Management System (LMS)—helping schools automate admin tasks, boost
                    student performance, and deliver modern digital learning in one seamless platform.
                </p>
                <button class = "btn primaryButton" type = "button">Discover Koodies</button>

                <div>
                    <img src = "images/koodies.png" class = "img-fluid  w-md-75 float-end">
                </div>
            </div>

        </div>
    </div>


    <div class="container text-center mt-5 py-5"
        style=" min-height:650px; background-image: url('images/trophys.png'); background-repeat: no-repeat; background-size: cover; background-position: center;">

        <!-- Section Title -->
        <div class="row justify-content-center mb-5">
            <div class="col-12 mt-5">
                <h5 class="fw-bold" style = "margin-top: 130px;">Why People Love Kokokah</h5>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="row g-4 justify-content-center">

            <!-- Testimonial 1 -->
            <div class="col-12 col-md-6 col-lg-5 custom-width">
                <div class="testimonial-card position-relative p-4">
                    <i class="bi bi-quote text-success fs-2 float-start"></i><br>
                    <p class="mt-3">
                        With Kokokah, we conduct online tests, share lessons digitally, and manage
                        school operations all from one dashboard.
                    </p>
                    <i class="bi bi-quote text-success fs-2 float-end"></i>
                    <img src="images/lisa.png" alt="Lisa" class="testimonial-img position-absolute rounded-circle">
                    <p class="fw-bold text-end mb-0">- Lisa</p>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="col-12 col-md-6 col-lg-5 custom-width">
                <div class="testimonial-card position-relative p-4">
                    <i class="bi bi-quote text-success fs-2 float-start"></i><br>
                    <p class="mt-3">
                        With Kokokah, we conduct online tests, share lessons digitally, and manage
                        school operations all from one dashboard.
                    </p>
                    <i class="bi bi-quote text-success fs-2 float-end"></i>
                    <img src="images/jimmy.png" alt="Jimmy" class="testimonial-img position-absolute rounded-circle">
                    <p class="fw-bold text-end mb-0">- Jimmy</p>
                </div>
            </div>

        </div>
    </div>




    <div class = "container-fluid"
        style = "background-image: url('images/founder.png'); background-repeat:no-repeat; background-size:contain;">
        <div class = "row justify-content-center p-5 text-white">

            <div class = "col-12 col-lg-12 col-md-12 text-center">
                <h5 class = "mb-3">Message from the founder</h5>
                <img src = "images/youtube.png" class="img-fluid" style = "width: 800px;">
            </div>

        </div>
    </div>
@endsection
