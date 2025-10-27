@extends('layouts.template')

@section('content')

<!-- Hero Section - Yellow Background -->
<div class="container-fluid hero-section-yellow">
  <div class="row align-items-center">
    <div class="col-12 col-md-6 col-lg-6">
      <h1 class="fw-bold hero-title">
        Quality, Mobile-First, Pay-as-you-Go, Curriculum Based Lessons & Practice Tests
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
          Kokokah is a smart, pan-African learning and school management platform built for the realities of African education. Whether you're a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator in South Africa, our mission is simple — to give every learner from any background the opportunity to excel with ease.
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
        Kokokah combines mobile learning, exam preparation and a school learning management system, helping schools automate tasks efficiently, offering parents high-quality affordable learning options and boosting overall student performance.
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
          <p class="feature-card-text">Study anywhere, anytime — even on low internet. Learn on your phone, tablet, or computer without missing a beat.</p>
        </div>
      </div>

      <!-- Feature 3 -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="mb-3">
            <i class="fa-solid fa-download feature-card-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 feature-card-title">AI-integrated and Automated Features</h5>
          <p class="feature-card-text">Get instant answers, personalized feedback, and quick grading with our built-in AI — saving time for both students and educators.</p>
        </div>
      </div>

      <!-- Feature 4 -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="mb-3">
            <i class="fa-solid fa-download feature-card-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 feature-card-title">Affordable Subscription Plans</h5>
          <p class="feature-card-text">Choose a plan that fits your budget and needs — monthly, quarterly, or yearly, all with full platform access.</p>
        </div>
      </div>

      <!-- Feature 5 -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="mb-3">
            <i class="fa-solid fa-download feature-card-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 feature-card-title">Virtual Payment</h5>
          <p class="feature-card-text">Store and track money for any resource purchase on Kokokah — quick, safe, and hassle-free.</p>
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
        Kokokah brings you a suite of powerful learning tools designed to transform how African students, parents, and educators connect, learn, and thrive.
      </p>
    </div>
    <div class="text-center">
      <button class="btn btn-primary-action">Explore Features</button>
    </div>
  </div>
</div>

@endsection

