@extends('layouts.template')

@section('content')

<!-- Modal Section -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="d-flex flex-column gap-3 flex-lg-row align-items-center row">
            <div class="d-flex justify-content-end col"><img src="./images/Group_20-removebg-preview.png" alt="" style="width: 300px; height:200px;"></div>
             <div class="d-flex flex-column gap-1 col">
                
            <h2 class="title"><span>Hey Champion.</span> Ready to pass smarter and better?</h2>
            <div class="d-flex align-items-center gap-2">
                <span class="feature">Short Curriculum-based lessons + Practice tests from anywhere</span>
                <span class="feature">Low data & Offline use</span>
                <span class="feature">Score higher</span>
            </div>
        </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <img src="./images/db368b8f91f2ce3ff4d3b1b6ac05a321bfdc4f9a.png" alt="" class="img-fluid">
                </div>
                 <div class="col d-flex flex-column gap-5">
                    <div class="launch-container">We're Launching Soon. Stay Close & Don't Miss a Thing
                        <div class="node"></div>
                    </div>
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex flex-column input-container">
                            <label for="" class="label">Enter first name</label>
                            <input type="text" name="" id="" class="modal-form-input">
                        </div>
                        <div class="d-flex flex-column input-container">
                            <label for="" class="label">Enter last name</label>
                            <input type="text" name="" id="" class="modal-form-input">
                        </div>
                        <div class="d-flex flex-column input-container">
                            <label for="" class="label">Enter email address</label>
                            <input type="email" name="" id="" class="modal-form-input">
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <button class="align-self-center form-btn">Join Kokokah & Start Learning Now</button>
                        <div class="d-flex gap-4 align-items-center justify-content-center">
                            <div class="d-flex align-items-center justify-content-center icon-container"><i class="fa-brands fa-youtube" style="color:#F56824;"></i></div>
                            <div class="d-flex align-items-center justify-content-center icon-container"><i class="fa-brands fa-linkedin" style="color:#F56824;"></i></div>
                            <div class="d-flex align-items-center justify-content-center icon-container"><i class="fa-brands fa-x-twitter" style="color:#F56824;"></i></div>
                            <div class="d-flex align-items-center justify-content-center icon-container"><i class="fa-brands fa-instagram" style="color:#F56824;"></i></div>
                            <div class="d-flex align-items-center justify-content-center icon-container"><i class="fa-brands fa-facebook-f" style="color:#F56824;"></i></div>
                        </div>
                    </div>
                    
                 </div>
            </div>
            </div>
      </div>
      <div class="modal-footer">
        <div class="footer-accent">kokokah.com</div>
      </div>
    </div>
  </div>
</div>


    <!-- Hero Section - Yellow Background -->
    <div class="container-fluid hero-section-yellow">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 col-lg-6">
                <h1 class="fw-bold hero-title">
                    Quality, Mobile-First, Pay-as-you-Go, Curriculum Based Lessons & Practice Tests for Secondary School Students, Teachers & Schools.
                </h1>
                <p class="mb-4 hero-subtitle">
                    LOW DATA USAGE + OFFLINE ACCESS + SCHOOL MANAGEMENT SYSTEM
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="/login" class="btn btn-primary-action">Start Using Kokokah</a>
                    <a href="/register" class="btn btn-secondary-action">Signup Now</a>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 text-center mt-4 mt-md-0 hero_img">
                <img src="images/LMS.png" class="img-fluid" alt="LMS Illustration">
            </div>
        </div>
    </div>

    <!-- Kokokah for All Section - White Background -->
    <div class="container-fluid section-white section-py">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 col-lg-6 mb-4 mb-md-0">
                    <img src="images/Video.png" class="img-fluid" alt="Kokokah Platform">
                </div>
                <div class="col-12 col-md-6 col-lg-6 ps-md-4 ps-0">
                    <h2 class="fw-bold mb-4 section-heading">
                        Kokokah for All
                    </h2>
                    <p class="mb-4 hero-subtitle">
                        Kokokah is a smart, pan-African learning and school management platform built for the realities of
                        African education. Whether you're a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator
                        in South Africa, our mission is simple — to give every learner from any background the opportunity
                        to excel with ease.
                    </p>
                    <button class="btn btn-primary-action">Discover Kokokah</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Kokokah is the Best Section -->
    <div class="container-fluid section-light-gray section-py">
        <div class="container">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="fw-bold mb-3 section-heading">
                    Why Kokokah Is the Best
                </h2>
                <p class="section-description">
                    Kokokah combines mobile learning, exam preparation and a school learning management system, helping
                    schools automate tasks efficiently, offering parents high-quality affordable learning options and
                    boosting overall student performance.
                </p>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">For Students, Parents & Schools</h5>
                        <p class="feature-card-text">One platform for all.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">Accessible Mobile Learning</h5>
                        <p class="feature-card-text">Study anywhere, anytime — even on low internet. Learn on your phone,
                            tablet, or computer without missing a beat.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">AI-integrated and Automated Features</h5>
                        <p class="feature-card-text">Get instant answers, personalized feedback, and quick grading with our
                            built-in AI — saving time for both students and educators.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">Affordable Subscription Plans</h5>
                        <p class="feature-card-text">Choose a plan that fits your budget and needs — monthly, quarterly, or
                            yearly, all with full platform access.</p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">Virtual Payment</h5>
                        <p class="feature-card-text">Store and track money for any resource purchase on Kokokah — quick,
                            safe, and hassle-free.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Products Section -->
    <div class="container-fluid section-white section-py">
        <div class="container">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="fw-bold mb-3 section-heading">
                    Our Products
                </h2>
                <p class="section-description">
                    Kokokah brings you a suite of powerful learning tools designed to transform how African students,
                    parents, and educators connect, learn, and thrive.
                </p>
            </div>
            <div class="text-center">
                <button class="btn primaryButton">Explore Features</button>
            </div>
        </div>
    </div>




    <div class = "container mt-5">
        <div class = "row flex-column-reverse flex-md-row m-2 ourproduct1 product-section-orange">
            <div class = "col-12 col-md-7 col-lg-7 p-4 px-5 my-auto">
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
                    <button class = "  primaryButton" type = "button">Coming soon</button>
                </div>
            </div>


            <div class="col-12 col-lg-5 col-md-5 text-center p-2">
                <img src="images/exam_prep.png" class="img-fluid w-100 w-md-75" alt="Exam Prep">
            </div>

        </div>
    </div>


    <div class = "container">
        <div class="row ourproduct2 product-section-bordered">
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

                <button class = " primaryButton " type="button">Coming soon</button>

            </div>
        </div>
    </div>




    <div class = "container">
        <div class = "row flex-column-reverse flex-md-row ourproduct1 product-section-teal">
            <div class = "col-12 col-md-6 col-lg-6 p-5 my-auto">
                <h4 class = "text-white">kokokah LMS</h4>
                <p class = "text-white">
                    Your all-in-one digital classroom — structured lessons, AI-powered
                    tutoring, chatrooms, and  academic & non-academic content for secondary school.
                </p>
                <button class = "primaryButton" type = "button">Coming soon</button>
            </div>
            <div class="col-12 col-md-6 text-center">
                <img src="images/lms system.png" class="img-fluid w-100 w-md-75" alt="Exam Prep">
            </div>
        </div>
    </div>

    <div class = "container">
        <div class="row ourproduct2 product-section-bordered">
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
                    <button class = "primaryButton" type = "button">Coming Soon</button>

                </div>
            </div>
        </div>
    </div>


    <div class = "container">
        {{-- <div class="row  my-4 p-2 flex-column-reverse flex-md-row m-2 ourproduct1 product-section-yellow"> --}}
        <div class = "row flex-column-reverse flex-md-row ourproduct1 product-section-yellow">
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
                    <button class = "primaryButton greenBtn" type = "button" >Coming Soon</button>
                </div>
            </div>

            <!-- Image Section -->
            <div class="col-12 col-md-6 text-center">
                <img src="images/lms system.png" class="img-fluid w-100 w-md-75" alt="LMS system">
            </div>
        </div>
    </div>



    <div class = "container">
        <div class="row ourproduct2 product-section-bordered" style="background-color : #F56824;">
            <!-- Image Section -->
            <div class="col-12 col-md-6 my-auto text-center">
                <img src="images/School Admin.png" class="img-fluid w-100 w-md-75" alt="School Admin">
            </div>

            <!-- Text Section -->
            <div class="col-12 col-md-6 my-auto d-flex flex-column">
                <h5 class="text-white">
                    STEM Labs
                </h5>
                <p class="text-white">
                    Get hands-on STEM bootcamps, summer schools,
                    and practical learning experiences to prepare
                    students for the future of science & tech.
                </p>

                <div class = "mb-3">
                    <button class = "primaryButton" type = "button">Coming soon</button>

                </div>

            </div>
        </div>
    </div>


    <div class = "container">
        <div class = "row mt-4">
            <div class = "col-12 col-md-5 col-lg-5">
                <img src = "images/LMS.png" class = "img-fluid">
            </div>

            <div class = "col-12 col-md-7 col-lg-7 mt-lg-5">
                <h6>
                    Kokoplay
                </h6>
                <p>
                    Kokokah combines School Management, Exam Prep, and a
                    Learning Management System (LMS)—helping schools automate admin tasks,
                    boost student performance, and deliver modern digital learning in one
                    seamless platform. Kokokah combines School Management, Exam Prep, and a
                    Learning Management System (LMS)—helping schools automate admin tasks, boost
                    student performance, and deliver modern digital learning in one seamless platform.
                </p>
                <button class = "primaryButton mt-3" type = "button">Coming Soon</button>

                <div>
                    <img src = "images/koodies.png" class = "img-fluid  w-md-75 float-end">
                </div>
            </div>

        </div>
    </div>


    <div class="container text-center mt-5 py-5 achievement-section">
    <p class="achievement-label">Kokokah has industry-leading renewals of above 80%
</p>
        <!-- Section Title -->
        <div class="row justify-content-center mb-5">
            <div class="col-12 mt-5">
                <h5 class="fw-bold achievement-title">Why People Love Kokokah</h5>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="row g-5 justify-content-center">
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




    <div class = "container-fluid founder-section">
        <div class = "row justify-content-center p-5 text-white">

            <div class = "col-12 col-lg-12 col-md-12 text-center">
                <h5 class = "mb-3 " style="color:#004A53; font-size : 32px;">Message from the founder</h5>
                <img src = "images/youtube.png" class="img-fluid founder-video">
            </div>

        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
      });
    </script>
@endsection
