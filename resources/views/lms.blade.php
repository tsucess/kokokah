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
        <img src="images/Video.png" class="img-fluid" alt="Kokokah LMS">
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
<div class="container-fluid section-white section-py">
  <div class="container">
    <div class="text-center mb-4 mb-md-5">
      <h2 class="fw-bold section-heading">
        Our Features
      </h2>
    </div>

    <div class="row g-4">
      <!-- Feature Card 1 -->
      <div class="col-md-6 col-lg-5 mx-auto">
        <div class="large-card">
          <div class="mb-3">
            <i class="fa-solid fa-crown large-card-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 large-card-title">Kokokah Chat Room</h5>
          <p class="large-card-text">We make learning available anywhere — even with low internet speeds and everyday devices — so no student is left behind.</p>
          <button class="btn btn-primary-action w-100">Start a Conversation</button>
        </div>
      </div>

      <!-- Feature Card 2 -->
      <div class="col-md-6 col-lg-5 mx-auto">
        <div class="large-card">
          <div class="mb-3">
            <i class="fa-solid fa-school large-card-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 large-card-title">Academic Content</h5>
          <p class="large-card-text">We deliver up-to-date content across all major subjects, aligned with your class group and school's curriculum.</p>
          <button class="btn btn-primary-action w-100">Get Started</button>
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

