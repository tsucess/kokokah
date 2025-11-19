@extends('layouts.template')

@section('content')
    <!-- Modal Section -->

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="width:1rem; height:1rem; background-size:1rem;"></button>
                </div>
                <div class="modal-body pt-0 pb-lg-0">
                    <div class=" row">
                        <div class="d-flex justify-content-center justify-content-md-end col col-12 col-lg-5"><img src="./images/Group_20-removebg-preview.png"
                                alt="" style="width: 300px; height:150px;"></div>
                        <div class="d-flex flex-column gap-1  align-items-center col col-12 col-lg-7">

                            <h2 class="title text-center"><span>Hey Champion.</span> Ready to pass smarter and better?</h2>
                            <div class="d-flex align-items-center gap-1">
                                <span class="feature">Short Curriculum-based lessons + Practice tests from anywhere</span>
                                <span class="feature">Low data & Offline use</span>
                                <span class="feature">Score higher</span>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col col-12 col-lg-6">
                                <img src="./images/db368b8f91f2ce3ff4d3b1b6ac05a321bfdc4f9a.png" alt=""
                                    class="img-fluid" />
                            </div>
                            <div class="col col-12 col-lg-6 d-flex flex-column gap-4">
                                <div class="launch-container">We're Launching Soon. Stay Close & Don't Miss a Thing
                                    <div class="node"></div>
                                </div>
                                <div class="d-flex flex-column gap-3">
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
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-youtube" style="color:#F56824;"></i></div>
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-linkedin" style="color:#F56824;"></i></div>
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-x-twitter" style="color:#F56824;"></i></div>
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-instagram" style="color:#F56824;"></i></div>
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-facebook-f" style="color:#F56824;"></i></div>
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
               {{-- <div class="hero-title-container">
  <span class="hero-title" id="typing-text"></span>
</div> --}}
  <h1 class="hero_header">Welcome!</h1>
                <p class="mb-4 hero-subtitle">
                    LOW DATA USAGE + OFFLINE ACCESS + SCHOOL MANAGEMENT SYSTEM
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="/login" class="btn btn-primary-action">Start Using Kokokah</a>
                    <a href="/register" class="btn btn-secondary-action">Signup Now</a>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mt-4 mt-md-0 hero_img text-center">
                <img src="images/LMS.png" class="img-fluid animate__animated animate__pulse hero-img" alt="LMS Illustration" >
            </div>
        </div>
    </div>

    <!-- Kokokah for All Section - White Background -->
    <div class="container-fluid section-white section-py " >
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 col-lg-6 mb-4 mb-md-0 fade-section-left">
                    <img src="images/33d07ac37205dee5ed7d04a51aace312e634c69c.jpg" class="img-fluid" alt="Kokokah Platform" style="max-width:752px; width:100%; height:382px; border-radius : 15px; object-fit:cover;">
                </div>
                <div class="col-12 col-md-6 col-lg-6 ps-md-4 ps-0 fade-section">
                    <h2 class="fw-bold mb-4 section-heading" style="font-size :38px;">
                        Kokokah for All
                    </h2>
                    <p class="mb-4 hero-subtitle">
                        Kokokah is a smart, pan-African learning and school management platform built for the realities of
                        African education. Whether you're a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator
                        in South Africa, our mission is simple — to give every learner from any background the opportunity
                        to excel with ease.
                    </p>
                    <button class="btn primaryButton">Discover Kokokah</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Kokokah is the Best Section -->
    <div class="container-fluid section-light-gray section-py">
        <div class="container">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="fw-bold mb-3 section-heading section-title" style="font-size :38px;">
                    Why Kokokah Is the Best
                </h2>
                <p class="section-description">
                    Kokokah combines mobile learning, exam preparation and a school learning management system, helping
                    schools automate tasks efficiently, offering parents high-quality affordable learning options and
                    boosting overall student performance.
                </p>
            </div>

            <div class="row g-4 features">
                <!-- Feature 1 -->
                <div class="col-12 col-md-6 col-lg-4 feature-item">
                    <div class="feature-card ">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">For Students, Parents & Schools</h5>
                        <p class="feature-card-text">One platform for all.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-12 col-md-6 col-lg-4 feature-item">
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
                <div class="col-12 col-md-6 col-lg-4 feature-item">
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
                <div class="col-12 col-md-6 col-lg-4 feature-item">
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
                <div class="col-12 col-md-6 col-lg-4 feature-item">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">Virtual Payments</h5>
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
                <h2 class="fw-bold mb-3 section-heading section-title" style="font-size :38px;">
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



<div class="d-flex flex-column gap-5 mx-4 mx-md-0">
    <div class = "container fade-section">
        <div class = "row flex-column-reverse flex-md-row ourproduct1 product-section-bordered-red">
            <div class = "col col-12 col-md-7 col-lg-7 p-4 px-5 my-auto d-flex flex-column gap-3">
                <img src="images/Group 1171274797.png" alt="" style="width:200px; height:auto;"/>
                <p style = "color :#004A53;">
                    Kokokah houses an exam preparatory platform
                    where African students can prepare adequately
                    for both local and international examinations.
                </p>
                <p style = "color :#004A53;">
                    WAEC - NECO - JAMB - GCE - GMAT - SAT - TOEFL - IELTS - GRE - ACT
                </p>

                <div>
                    <button class = "primaryButton" type = "button">Coming soon</button>
                </div>
            </div>


            <div class="col col-12 col-md-5 col-lg-5 d-flex align-items-lg-center justify-content-lg-center">
                <img src="images/exam_prep.png" class="img-fluid w-100 w-lg-100 " alt="Exam Prep">
            </div>

        </div>
    </div>


    <div class = "container fade-section-left">
        <div class="row ourproduct2 product-section-orange">
            <!-- Image Section -->
            <div class="col-12 col-md-6 col-lg-6">
                <img src="images/School Admin.png" class="img-fluid" alt="School Admin">
            </div>
            <!-- Text Section -->
            <div class="col-12 col-md-6 col-lg-6  my-auto d-flex flex-column gap-3">
                <h5 class="text-white">School Management System (SMS)</h5>
                <p class = " text-white">
                    Simplify school administration with fee
                    tracking, digital report cards, attendance, and student
                    portals — all in one secure platform.
                </p>

                <button class = " primaryButton " type="button">Coming soon</button>

            </div>
        </div>
    </div>




    <div class = "container fade-section">
        <div class = "row flex-column-reverse flex-md-row ourproduct1 product-section-bordered-green">
            <div class = "col-12 col-md-6 col-lg-7 p-5 my-auto d-flex flex-column gap-3">
                <div class="d-flex align-items-end gap-1"><img src="./images/Kokokah_Logo.png" alt="" style="width:150px; height:auto;" class="object-cover"/><h4 style = "color :#004A53;">Learning Management System</h4></div>
                <p style = "color :#004A53;">
                    Your all-in-one digital classroom — structured lessons, AI-powered
                    tutoring, chatrooms, and  academic & non-academic content for secondary school.
                </p>
                <button class = "primaryButton" type = "button">Coming soon</button>
            </div>
            <div class="col-12 col-md-5 text-center">
                <img src="images/lms system.png" class="img-fluid w-100 w-md-75 h-100" alt="Exam Prep">
            </div>
        </div>
    </div>

    <div class = "container fade-section-left">
        <div class="row ourproduct2 product-section-teal">
            <!-- Image Section -->
            <div class="col-12 col-md-6 my-auto text-center">
                <img src="images/School Admin.png" class="img-fluid w-100 w-md-75" alt="School Admin">
            </div>

            <!-- Text Section -->
            <div class="col-12 col-md-6 my-auto d-flex flex-column gap-3">
                <h5 class="text-white">
                    The Marketplace
                </h5>
                <p class="text-white">
                    Africa’s academic forum for parents, teachers, and
                    tutors to connect. Book trusted tutors for academics,
                    test prep, and special needs learning.
                </p>
                <div class = "">
                    <button class = "primaryButton" type = "button">Coming Soon</button>

                </div>
            </div>
        </div>
    </div>


     <div class = "container fade-section">
        <div class="row ourproduct2 product-section-bordered" >
            <!-- Image Section -->
            <div class="col-12 col-md-6 ">
                <img src="images/School Admin.png" class="img-fluid w-100 w-md-75" alt="School Admin">
            </div>

            <!-- Text Section -->
            <div class="col-12 col-md-6 my-auto d-flex flex-column gap-3">
                <img src="./images/315a2f8c6c60fc789ec0066a0b5bce04b7daa28d.png" alt="" style="width:200px; height:60px;"/>
                <p style = "color :#004A53;">
                    Get hands-on STEM bootcamps, summer schools,
                    and practical learning experiences to prepare
                    students for the future of science & tech.
                </p>

                <div class = "">
                    <button class = "primaryButton" type = "button">Coming soon</button>

                </div>

            </div>
        </div>
    </div>


    <div class = "container fade-section-left">
        {{-- <div class="row  my-4 p-2 flex-column-reverse flex-md-row m-2 ourproduct1 product-section-yellow"> --}}
        <div class = "row flex-column-reverse flex-md-row ourproduct1 product-section-yellow">
            <!-- Text Section -->
            <div class="col-12 col-md-6 d-flex flex-column justify-content-center gap-3 ps-lg-5 pb-4 pb-lg-0">
                <h5>
                    AI Chatbot
                </h5>
                <p>
                    Your personal academic assistant — ask questions, get explanations,
                    and enjoy instant feedback tailored to your study needs.
                </p>

                <div>
                    <button class = "primaryButton greenBtn" type = "button">Coming Soon</button>
                </div>
            </div>

            <!-- Image Section -->
            <div class="col-12 col-md-6 text-center">
                <img src="images/lms system.png" class="img-fluid w-100 w-md-75" alt="LMS system">
            </div>
        </div>
    </div>

     <div class = "container fade-section">
        <div class="row ourproduct2 product-section-bordered" >
            <!-- Image Section -->
            <div class="col-12 col-md-6 my-auto text-center">
                <img src="images/8e967ffd660c50979f3c273bbb9d848dbb48a9db.png" class="d-block w-75 w-md-50 w-lg-25 mx-auto" alt="kudikah">
            </div>

            <!-- Text Section -->
            <div class="col-12 col-md-6 my-auto d-flex flex-column gap-3">
                 <h5 style = "color :#004A53;">Kudikah</h5>
                <p style = "color :#004A53;">
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

    </div>


    <div class = "container">
        <div class = "row mt-4">
            <div class = "col-12 col-md-5 col-lg-5">
                <img src = "images/LMS.png" class = "img-fluid animate__animated animate__pulse hero-img">
            </div>

            <div class = "col-12 col-md-7 col-lg-7 mt-lg-5">
                <h6 class="">
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
                    <img src = "images/koodies.png" class = "img-fluid  w-md-75 float-end slide-up-image">
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
                <h5 class="fw-bold achievement-title section-title">Why People Love Kokokah</h5>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="row g-5 justify-content-center">
            <!-- Testimonial 1 -->
            <div class="col-12 col-md-6 col-lg-5 custom-width tada-on-scroll">
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
            <div class="col-12 col-md-6 col-lg-5 custom-width ">
                <div class="testimonial-card position-relative p-4 tada-on-scroll">
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
                <h5 class = "mb-3 section-title" style="color:#004A53; font-size : 32px;">Message from the founder</h5>
                <img src = "images/youtube.png" class="img-fluid founder-video">
            </div>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        });

gsap.registerPlugin(ScrollTrigger, TextPlugin);



  const phrases = [
    "Quality",
    "Mobile-First",
    "Curriculum Based Lessons & Practice Tests"
  ];

  const tl = gsap.timeline({ repeat: -1, repeatDelay: 0.5 });

  phrases.forEach((phrase) => {
    // Animate text to current phrase
    tl.to(".hero_header", {
      duration: 1,
      text: phrase,
      ease: "none"
    });

    // Small pause on each phrase
    tl.to({}, { duration: 1 });
  });

  gsap.utils.toArray(".fade-section").forEach((section) => {
  gsap.from(section, {          // use `from` so it starts offscreen
    opacity: 1,
    x: 600,                     // start 100px to the right
    duration: 0.1,
    ease: "power2.out",
    scrollTrigger: {
      trigger: section,
      start: "top 85%",
      toggleActions: "play reverse play reverse",
    }
  });
});

gsap.utils.toArray(".fade-section-left").forEach((section) => {
 gsap.from(section, {          // use `from` so it starts offscreen
    opacity: 1,
    x: -600,                     // start 100px to the right
    duration: 0.1,
    ease: "power2.out",
    scrollTrigger: {
      trigger: section,
      start: "top 85%",
      toggleActions: "play reverse play reverse",
    }
  })
});



 gsap.from(".feature-item", {
  scrollTrigger: {
    trigger: ".features",
    start: "top 80%",
    toggleActions: "play reverse play reverse",
  },
  opacity: 0,
  y: 80,
  duration: 0.5,
  ease: "back.out(1.7)", // “pop-out” effect
  stagger: {
    each: 0.5,       // stagger each by 0.2s
    from: "start"    // options: "start", "center", "end", or index
  }
});

gsap.utils.toArray(".section-title").forEach((section) => {
gsap.from(section, {
  scale: 0.6,
  duration: 1,
  ease: "power2.out",
  scrollTrigger: {
    trigger: section,
    start: "top 85%",
    toggleActions: "play reverse play reverse"
  }
})
});


gsap.utils.toArray(".tada-on-scroll").forEach((el) => {
  gsap.timeline({
    scrollTrigger: {
      trigger: el,
      start: "top 75%",
      toggleActions: "play reverse play reverse",
      // markers: true
    }
  })
  .to(el, {
    duration: 0.2,
    scale: 0.9,
    rotation: -3,
    ease: "power2.out"
  })
  .to(el, {
    duration: 0.2,
    scale: 1.1,
    rotation: 3,
    ease: "power2.out",
    yoyo: true,
    repeat: 3
  })
  .to(el, {
    duration: 0.2,
    scale: 1,
    rotation: 0,
    ease: "power2.out"
  });
});

gsap.utils.toArray(".slide-up-image").forEach((img) => {
  gsap.from(img, {
    y: 300,              // start 100px below
    opacity: 0,           // start fully transparent
    duration: 1,        // animation duration
    ease: "power2.out",
    scrollTrigger: {
      trigger: img,
      start: "top 85%",   // when image enters the viewport
      toggleActions: "play reverse play reverse",
      // markers: true    // optional, for debugging
    }
  });
});

    </script>

@endsection
