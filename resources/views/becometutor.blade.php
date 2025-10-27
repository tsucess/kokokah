@extends('layouts.template')

@section('content')

<!-- Hero Section - Yellow Background -->
<div class="container-fluid hero-section-yellow">
  <div class="row align-items-center">
    <div class="col-12 col-md-6 col-lg-6">
      <h1 class="fw-bold hero-title">
        Teach. Inspire. Earn. Become a Tutor Today.
      </h1>
      <p class="mb-4 hero-subtitle">
        Join a trusted platform that connects you with students who need your knowledge. Flexible hours, secure payments, and tools to help you succeed.
      </p>
      <button class="btn btn-primary-action btn-full-width">Sign Up to Teach</button>
    </div>
    <div class="col-12 col-md-6 col-lg-6 text-center mt-4 mt-md-0">
      <img src="images/stem.png" class="img-fluid" alt="Tutor Illustration">
    </div>
  </div>
</div>

<!-- Why Become a Tutor Section -->
<div class="container-fluid section-white section-py">
  <div class="container">
    <div class="text-center mb-4 mb-md-5">
      <h2 class="fw-bold section-heading">
        Why Become a Tutor on Kokokah?
      </h2>
    </div>

    <div class="row g-4">
      <!-- Card 1 -->
      <div class="col-md-6 col-lg-5 mx-auto">
        <div class="large-card text-center">
          <div class="mb-3">
            <i class="fa-solid fa-user-circle large-card-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 large-card-title">Flexible Schedule</h5>
          <p class="large-card-text">Teach when it works for you—online or in person.</p>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-6 col-lg-5 mx-auto">
        <div class="large-card text-center">
          <div class="mb-3">
            <i class="fa-solid fa-lightbulb large-card-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 large-card-title">Steady Income</h5>
          <p class="large-card-text">Get paid securely and on time for every lesson.</p>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-6 col-lg-5 mx-auto">
        <div class="large-card text-center">
          <div class="mb-3">
            <i class="fa-solid fa-user-circle large-card-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 large-card-title">Grow Your Impact</h5>
          <p class="large-card-text">Help students excel and change lives through learning.</p>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col-md-6 col-lg-5 mx-auto">
        <div class="large-card text-center">
          <div class="mb-3">
            <i class="fa-solid fa-lightbulb large-card-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 large-card-title">Support & Tools</h5>
          <p class="large-card-text">Lesson tracking, messaging, and performance insights.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Your Teaching Journey Section -->
<div class="container-fluid section-light-gray section-py">
  <div class="container">
    <div class="text-center mb-4 mb-md-5">
      <h2 class="fw-bold section-heading">
        Your Teaching Journey in 3 Simple Steps
      </h2>
    </div>

    <div class="row g-4 justify-content-center">
      <!-- Step 1 -->
      <div class="col-md-6 col-lg-4">
        <div class="step-card">
          <div class="step-number">01</div>
          <div class="mb-3">
            <i class="fa-solid fa-download step-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 step-title">Apply Online</h5>
          <p class="step-text">Complete your tutor profile and submit your qualifications.</p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="col-md-6 col-lg-4">
        <div class="step-card">
          <div class="step-number">02</div>
          <div class="mb-3">
            <i class="fa-solid fa-download step-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 step-title">Get Verified</h5>
          <p class="step-text">We review your credentials to ensure trust and quality.</p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="col-md-6 col-lg-4">
        <div class="step-card">
          <div class="step-number">03</div>
          <div class="mb-3">
            <i class="fa-solid fa-download step-icon"></i>
          </div>
          <h5 class="fw-bold mb-3 step-title">Start Teaching</h5>
          <p class="step-text">Match with parents and students, set your schedule, and begin.</p>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
      <button class="btn btn-primary-action">Start my Application</button>
    </div>
  </div>
</div>

<!-- Teach Your Way Section -->
<div class="container-fluid py-4 py-md-5 teach-section-white">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-4 mb-4 mb-lg-0 ps-0 ps-lg-4">
        <div class="row g-2">
          <div class="col-6">
            <img src="images/stem1.png" class="img-fluid rounded" alt="Teaching">
          </div>
          <div class="col-6">
            <img src="images/stem2.png" class="img-fluid rounded" alt="Teaching">
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <h2 class="fw-bold mb-4 section-heading">
          Teach Your Way, Earn Your Worth
        </h2>
        <p class="hero-subtitle">
          <i class="fa-solid fa-circle-arrow-right teach-arrow-icon"></i>
          Set your hourly rate, choose subjects you love, and decide whether to teach online or in person. We provide the students — you bring the knowledge.
        </p>
      </div>
    </div>
  </div>
</div>

<!-- FAQ Section -->
<div class="container-fluid section-light-gray section-py">
  <div class="container">
    <div class="text-center mb-4 mb-md-5">
      <h2 class="fw-bold section-heading">
        Frequently Asked Questions
      </h2>
    </div>

    <div class="accordion" id="faqAccordion">
      <div class="accordion-item-custom">
        <h2 class="accordion-header">
          <button class="accordion-button accordion-button-custom collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne">
            1. What qualifications do I need?
          </button>
        </h2>
        <div id="faqOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body-custom">
            You'll need relevant teaching or professional experience in your subject area.
          </div>
        </div>
      </div>

      <div class="accordion-item-custom">
        <h2 class="accordion-header">
          <button class="accordion-button accordion-button-custom fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo">
            2. How do payments work?
          </button>
        </h2>
        <div id="faqTwo" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
          <div class="accordion-body-custom">
            You get paid securely and on time for every lesson. Payments are processed through our secure payment system.
          </div>
        </div>
      </div>

      <div class="accordion-item-custom">
        <h2 class="accordion-header">
          <button class="accordion-button accordion-button-custom collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree">
            3. How do I set my hourly rate?
          </button>
        </h2>
        <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body-custom">
            You can set your hourly rate based on your experience and market demand.
          </div>
        </div>
      </div>

      <div class="accordion-item-custom">
        <h2 class="accordion-header">
          <button class="accordion-button accordion-button-custom collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqFour">
            4. Can I teach both online and in-person?
          </button>
        </h2>
        <div id="faqFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body-custom">
            Yes, our platform supports both online and in-person learning options.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- CTA Section - Orange Background -->
<div class="container-fluid hero-section-orange">
  <div class="row align-items-center">
    <div class="col-12 col-md-6 col-lg-6">
      <h2 class="fw-bold mb-4 section-heading teach-section-heading">
        Ready to Start Teaching?
      </h2>
      <p class="mb-4 teach-section-text">
        It only takes 5 minutes to start your application.
      </p>
      <button class="btn btn-yellow-action">Apply to Become a Tutor</button>
    </div>
    <div class="col-12 col-md-6 col-lg-6 text-center mt-4 mt-md-0">
      <img src="images/exam_prep.png" class="img-fluid" alt="CTA Illustration">
    </div>
  </div>
</div>

@endsection

