@extends('layouts.template' )
@section('content')
<div class="container-fluid banner">
    <div class="row align-items-center">
        <div class="col-12 col-md-7 col-lg-7 p-5 ">
            <h2>
                Teach. Inspire. Earn. Become a Tutor Today.
            </h2>

            <p>
                Join a trusted platform that connects you
                with students who need your knowledge.
                Flexible hours, secure payments,
                and tools to help you succeed.”
            </p>

                <div>
                <button class="btn btn-block w-100 primaryButton" style="background: #004A53;">Sign Up to Teach</button>
                {{-- <button class="btn btn-outline-dark btn-lg">Request a Demo</button> --}}
            </div>

        </div>

        <div class="col-12 col-md-5 col-lg-5 text-center p-5">
            <img src="images/stem.png" alt="stem illustration" class="img-fluid ">
        </div>


    </div>
</div>



<div class = "container-fluid">

    <div class="d-flex justify-content-center mt-3">

    <div class="mt-5">
        <h3 class = "text-center">Why Become a Tutor on Kokokah?</h3>
    </div>

    </div>



<div class="row justify-content-center">

    <!-- Card 1 -->
    <div class="col-md-6 mt-3 mb-4 col-lg-5">
      <div class="card border-1 shadow-sm  rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4 " style="background: #FFF9F0;">
          <i class="fa-solid fa-user-circle text-warning fs-3"></i>

        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Flexible Schedule</h6>
          <p class="text-muted">
            Teach when it works for you—online or in person.
          </p>
        </div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col-md-6 mt-3 mb-4 col-lg-5">
    <div class="card border-1 shadow-sm  rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4" style="background: #FFF9F0;">
          <i class="fa-solid fa-lightbulb text-warning fs-3"></i>
        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Steady Income</h6>
          <p class="text-muted">
            Get paid securely and on time for every lesson.
          </p>
        </div>
      </div>
    </div>

  </div>


  <div class="row justify-content-center">

    <!-- Card 3 -->
    <div class="col-md-6 mt-3 col-lg-5">
      <div class="card border-1 shadow-sm rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4" style="background: #FFF9F0;">
          <i class="fa-solid fa-user-circle text-warning fs-3"></i>

        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Grow Your Impact</h6>
          <p class="text-muted">
            Help students excel and change lives through learning.
          </p>
        </div>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="col-md-6 mt-3 col-lg-5 ">
      <div class="card border-1 shadow-sm rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4" style="background: #FFF9F0;">
          <i class="fa-solid fa-lightbulb text-warning fs-3"></i>

        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Support & Tools</h6>
          <p class="text-muted">
            Lesson tracking, messaging, and performance insights.
          </p>
        </div>
      </div>
    </div>

  </div>

</div>


<div class = "container mt-4 pt-5  text-center">
<div class = "row">
            <h3>
                Your Teaching Journey in 3 Simple Steps
            </h3>

                <!-- Feature 1 -->
    <div class="col-12 col-md-6 col-lg-4">
      <div class="feature-card position-relative">
        <span class="feature-number">01</span>
        <div class="feature-content">
          {{-- <div class="feature-icon mb-2">📘</div> --}}
          <i class="fa-solid fa-download" style="color: #f56824;"></i>
          <h6>Apply Online</h6>
          <p>
            Complete your tutor profile and submit your qualifications.
        </p>
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
          <h6>Get Verified</h6>
          <p>
            We review your credentials to ensure trust and quality.
          </p>
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
          <h6>Start Teaching</h6>
          <p>
        Match with parents and students, set your schedule, and begin.
        </p>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
        <button class="book-btn w-75" href="#">Start my Application</button>
      </div>

</div>
</div>

<div class="container mx-auto my-5">

    <div class="row align-items-center">

        <div class="col-lg-4 mb-4 mb-lg-0 h-25 ">
            <div class="row gx-2">
                <div class="col-6"><img src="images/stem1.png" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="images/stem2.png" alt="" class="img-fluid rounded"></div>
            </div>
        </div>


        <div class="col-12 col-md-8 col-lg-8">
            <h5>
               Teach Your Way, Earn Your Worth
            </h5>

            <ul class="list-unstyled">
                <p>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i>
                Set your hourly rate, choose subjects you love,
                and decide whether to teach online or in person.
                We provide the students — you bring the knowledge.
            </li>
        </p>
            </ul>


    </div>
</div>
  </div>



  <div class="container mx-auto my-5">

    <div class="row align-items-center">

                <div class="col-12 col-md-8 col-lg-8">
            <h5>
               Make a Bigger Impact with Special Needs Tutoring
            </h5>

            <ul class="list-unstyled">
                <p>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i>
                We welcome educators with experience in special
                education — from dyslexia to ADHD support.
                Help every child reach their potential
            </li>
        </p>
            </ul>


    </div>

        <div class="col-lg-4 mb-4 mb-lg-0 h-25 ">
            <div class="row gx-2">
                <div class="col-6"><img src="images/stem1.png" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="images/stem2.png" alt="" class="img-fluid rounded"></div>
            </div>
        </div>

</div>
  </div>


  <section class="faq-section text-center">
      <div class="container">
        <h4 class="fw-bold text-teal mb-5" style="color:#004a53;">Frequently Asked Questions</h4>

        <div class="accordion" id="faqAccordion">

          <!-- Question 1 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne" aria-expanded="false" aria-controls="faqOne">
                1. What qualifications do I need?
              </button>
            </h2>
            <div id="faqOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                You’ll need relevant teaching or professional experience in your subject area.
              </div>
            </div>
          </div>

          <!-- Question 2 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo" aria-expanded="true" aria-controls="faqTwo">
                2. How do payments work?
              </button>
            </h2>
            <div id="faqTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                You may apply for multiple courses, but scholarships
                are usually granted for one course per learner.
              </div>
            </div>
          </div>

          <!-- Question 3 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree" aria-expanded="false" aria-controls="faqThree">
                3. How do I set my hourly rate?
              </button>
            </h2>
            <div id="faqThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                You can set your hourly rate based on your experience and market demand.
              </div>
            </div>
          </div>

          <!-- Question 4 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFour" aria-expanded="false" aria-controls="faqFour">
                4. Can I teach both online and in-person?
              </button>
            </h2>
            <div id="faqFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Yes, our platform supports both online and in-person learning options.
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>


    <div class = "container mb-5">
    <div class = "row flex-column-reverse flex-md-row m-2 ourproduct1" style = "background: #F56824;">

        <div class = "col-12 col-md-6 col-lg-6 p-5 my-auto text-center">
            <h5>Ready to Start Teaching? </h5>

        <p>
            It only takes 5 minutes to start your application.
        </p>


        <div>
        <button class = "btn primaryButton" type = "button">Apply to Become a Tutor</button>
        </div>
        </div>


          <div class="col-12 col-lg-6 col-md-6 text-center p-5">
        <img src="images/exam_prep.png" class="img-fluid w-100 w-md-75" alt="Exam Prep">
    </div>

    </div>

</div>
@endsection
