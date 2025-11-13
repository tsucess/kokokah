@extends('layouts.template')

@section('content')

<!-- Hero Section - Yellow Background -->
<div class="container-fluid hero-section-yellow">
  <div class="row align-items-center">
    <div class="col-12 col-md-6 col-lg-6">
      <h1 class="fw-bold hero-title">
        Your Complete Ecosystem for Learning and Growth
      </h1>
      <p class="mb-4 hero-subtitle">
        Kokokah's learning management system is built to revolutionize how students study, how teachers teach, and how schools manage learning.
      </p>
      <button class="btn btn-primary-action">Create a free account</button>
    </div>
    <div class="col-12 col-md-6 col-lg-6 text-center mt-4 mt-md-0">
      <img src="images/objects.png" class="img-fluid" alt="LMS Illustration">
    </div>
  </div>
</div>

<!-- Kokokah LMS Section - White Background -->
<div class="container-fluid section-white section-py">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-12 col-md-6 col-lg-6 mb-4 mb-md-0">
        <img src="images/llm-hero-img.jpg" class="img-fluid" alt="Kokokah LMS" style="border-radius: 16px;">
      </div>
      <div class="col-12 col-md-6 col-lg-6 ps-md-4 ps-0">
        <h2 class="fw-bold mb-4 section-heading">
          Kokokah LMS
        </h2>
        <p class="mb-4 hero-subtitle">
          Kokokah is a smart, pan-African learning and school management platform built for the realities of African education. Whether you're a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator in South Africa, our mission is simple — to give every learner from any background the opportunity to excel with ease.
        </p>
        <button class="btn btn-primary-action">Create a free account</button>
      </div>
    </div>
  </div>
</div>

<!-- Achievements Section -->
<div class="container-fluid section-light-gray section-py">
  <div class="container">
    <div class="text-center mb-4 mb-md-5">
      <h2 class="fw-bold section-heading">
        Achievements/Statistics Section
      </h2>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="achievement-card">
          <div class="d-flex align-items-start">
            <div class="me-3 achievement-card-dot"></div>
            <p class="mb-0 achievement-card-text">Over 50,000 hours of lessons delivered across Africa</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="achievement-card">
          <div class="d-flex align-items-start">
            <div class="me-3 achievement-card-dot"></div>
            <p class="mb-0 achievement-card-text">5000+ secondary students have excelled in exams with Kokokah</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="achievement-card">
          <div class="d-flex align-items-start">
            <div class="me-3 achievement-card-dot"></div>
            <p class="mb-0 achievement-card-text">Over 650 educators empowered with digital teaching resources</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="achievement-card">
          <div class="d-flex align-items-start">
            <div class="me-3 achievement-card-dot"></div>
            <p class="mb-0 achievement-card-text">24/7 study time and practice. No restrictions</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="achievement-card">
          <div class="d-flex align-items-start">
            <div class="me-3 achievement-card-dot"></div>
            <p class="mb-0 achievement-card-text">95% of Users Report Faster Payments and smoother learning experiences with our integrated wallet system.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="achievement-card">
          <div class="d-flex align-items-start">
            <div class="me-3 achievement-card-dot"></div>
            <p class="mb-0 achievement-card-text">Scalable for Growth: Expand easily as your school adds new campuses or students. Best growing Pan-African learning community</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Our Features Section -->
<div class="container-fluid section-white section-py" style="background-color: #F56824; position:relative;">
   <img src="./images/lightbulb-icon.png" alt="" style="width:100px; height:100px; position:absolute; left:30px; top:20px; "/>
  <div class="container">
    <div class="text-center mb-4 mb-md-5">
      <h4 class="fw-bold text-white">
        Our Features
      </h4>
    </div>
    <div class="d-flex flex-column gap-4">
        <!-- first row -->
    <div class="d-flex flex-column gap-4 flex-lg-row">
      <!-- Feature Card 1 -->
        <div class="w-100 w-lg-50">
            <div class="d-flex flex-column large-card h-100">
                    <div class="d-flex justify-content-center align-items-center large-card-icon-container">
                        <img src="./images/crown-icon.png" alt="">
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-column gap-1">
                            <h5 class="large-card-title">Kokokah Chat Room</h5>
                            <div class="large-card-text"><p>Learning and growth don’t happen in isolation. Our chat room is a community where students, teachers, parents and guardians across Africa discuss topics, share knowledge, and interact all within a safe, moderated digital space.</p></div>
                        </div>
                        <button class="large-card-btn w-100">Start a Conversation</button>
                    </div>
            </div>
        </div>

      <!-- Feature Card 2 -->
      <div class="w-100 w-lg-50">
        <div class="d-flex flex-column large-card">
            <div class="d-flex justify-content-center align-items-center large-card-icon-container">
                <img src="./images/award-icon.png" alt="">
            </div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-column gap-1">
                    <h5 class="large-card-title">Kokokah Chat Room</h5>
                    <div class="d-flex flex-column"><p class="large-card-text mb-0">We deliver up-to-date content across all major subjects, aligned with your class group and school’s curriculum. Teachers gain ready access to comprehensive lesson notes, while students benefit from structured study guides organized by topics — making teaching seamless and exam preparation more effective.</p>
                        <ul class="large-card-text">
                        <li>Science</li>
                        <li>Arts</li>
                        <li>Commercial</li>
                        <li>General</li></ul>
                    </div>
                </div>
                <button class="large-card-btn w-100">Start a Conversation</button>
            </div>
        </div>
      </div>
    </div>

    <!-- second row -->
    <div class="d-flex flex-column gap-4 flex-lg-row">
      <!-- Feature Card 1 -->
      <div class="w-100 w-lg-50">
        <div class="d-flex flex-column large-card h-100">
            <div class="d-flex justify-content-center align-items-center large-card-icon-container">
                <img src="./images/face-icon.png" alt="">
            </div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-column gap-1">
                    <h5 class="large-card-title">Non-Academic Content</h5>
                    <div class="d-flex flex-column"><p class="large-card-text">Because real success goes beyond exams, our LMS also offers practical skills that prepare students for life after school.</p>
                       <ul class="large-card-text">
                        <li>Technical Skills</li>
                        <li>Soft Skills</li></ul></div>
                </div>
                <button class="large-card-btn w-100">Start a Conversation</button>
            </div>
        </div>
      </div>

      <!-- Feature Card 2 -->
      <div class="w-100 w-lg-50">
        <div class="d-flex flex-column large-card h-100">
            <div class="d-flex justify-content-center align-items-center large-card-icon-container">
                <img src="./images/speed-icon.png" alt=""></div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-column gap-1">
                    <h5 class="large-card-title">AI-Powered Academic Assistant</h5>
                    <div ><p class="large-card-text mb-0">Our built-in AI tutor works like your personal academic ChatGPT. It explains tough concepts, answers questions, and supports both independent study and classroom teaching — anytime, anywhere.
</p>

                    </div>
                </div>
                <button class="large-card-btn w-100">Start a Conversation</button>
            </div>
        </div>
      </div>
    </div>


    <!-- Third row -->
    <div class="d-flex">
      <!-- Feature Card 1 -->
      <div class="w-100 ">
        <div class="d-flex flex-column large-card h-100">
            <div class="d-flex justify-content-center align-items-center large-card-icon-container">
                <img src="./images/face-icon.png" alt=""></div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-column gap-1">
                    <h5 class="large-card-title">Virtual Wallet Integration</h5>
                    <div class="d-flex flex-column"><p class="large-card-text mb-0">Your money, your way — all within Kokokah.</p>
                    <ul class="large-card-text mb-0">
                        <li>Seamless Payments: Deposit funds once and use your wallet to pay for courses, tutoring sessions, STEM bootcamps, or any service on the Kokokah platform.</li>
                        <li>Flexibility: No need to juggle multiple payment methods — everything happens securely in-app.</li>
                    <li>Withdrawals: Need your funds back? You can withdraw according to our platform’s simple, transparent directives.</li></ul>
                <p class="large-card-text"> Parents can preload wallets for their kids, making payments easier and teaching financial responsibility.</p></div>
                </div>
                <button class="large-card-btn w-100">Start a Conversation</button>
            </div>
        </div>
      </div>
    </div>
</div>
</div>
  </div>

<!-- Why Choose Kokokah LMS Section -->
<div class="container-fluid section-light-gray section-py">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <img src="images/koodies.png" class="img-fluid" alt="Why Choose Kokokah">
      </div>
      <div class="col-lg-6 ps-lg-4 ps-0">
        <h2 class="fw-bold mb-4 section-heading">
          Why Choose Kokokah LMS
        </h2>
        <div class="row g-3">
          <div class="col-12">
            <div class="achievement-card">
              <p class="achievement-card-text mb-0">A complete academic and non-academic platform — like an academic Udemy, built for Africa.</p>
            </div>
          </div>
          <div class="col-12">
            <div class="achievement-card">
              <p class="achievement-card-text mb-0">AI-powered learning that adapts to each student's needs.</p>
            </div>
          </div>
          <div class="col-12">
            <div class="achievement-card">
              <p class="achievement-card-text mb-0">Engaging community support with chat rooms and collaboration.</p>
            </div>
          </div>
          <div class="col-12">
            <div class="achievement-card">
              <p class="achievement-card-text mb-0">Verified curriculum content to align with both local and international standards.</p>
            </div>
          </div>
        </div>
        <div class="mt-4">
          <button class="btn btn-primary-action btn-large">See a Demo</button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

