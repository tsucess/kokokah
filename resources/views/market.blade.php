@extends('layouts.template' )
@section('content')
<div class="container-fluid banner">
    <div class="row align-items-center">
        <div class="col-12 col-md-8 col-lg-8 p-5 ">
            <h1 class = "heroheading">
                Find the Right Tutor for Your Child, Anytime, Anywhere.
            </h1>

            <p>
                Connect with trusted tutors for secondary school
                subjects and special needs education. Flexible,
                affordable, and personalized for your child’s success.
            </p>

            <div class = "button-container d-flex d-md-flex gap-3">
                <button class="btn primaryButton" type="button" style="background: #004A53;">Find a Tutor</button>
                <button class="btn secondaryButton" type="button" style="background: transparent; font-weight:bold;">Become a Tutor</button>
            </div>

        </div>

        <div class="col-12 col-md-4 col-lg-4 text-center p-5">
            <img src="images/stem.png" alt="stem illustration" class="img-fluid ">
        </div>


    </div>
</div>


<div class="container">
                <div class = "row mt-5">

                    <h4 class = "text-center mb-2">How It Works for Parents & Students</h4>

    <!-- Feature 1 -->
    <div class="col-12 col-md-6 col-lg-4 text-center ">
      <div class="feature-card position-relative">
        <span class="feature-number">01</span>
        <div class="feature-content">
          {{-- <div class="feature-icon mb-2">📘</div> --}}
          <i class="fa-solid fa-download" style="color: #f56824;"></i>
          <h6>Browse Tutors</h6>
          <p>
            Explore verified tutors by subject,<br>
            class, and specialization.
            </p>
        </div>
      </div>
    </div>

    <!-- Feature 2 -->
    <div class="col-12 col-md-6 col-lg-4 text-center">
      <div class="feature-card position-relative">
        <span class="feature-number">02</span>
        <div class="feature-content">
          {{-- <div class="feature-icon mb-2">📱</div> --}}
          <i class="fa-solid fa-download" style="color: #f56824;"></i>
          <h6>Check Profiles</h6>
          <p>See tutor qualifications, reviews, and teaching style.</p>
        </div>
      </div>
    </div>

    <!-- Feature 3 -->
    <div class="col-12 col-md-6 col-lg-4 text-center">
      <div class="feature-card position-relative">
        <span class="feature-number">03</span>
        <div class="feature-content">
          {{-- <div class="feature-icon mb-2">🤖</div> --}}
          <i class="fa-solid fa-download" style="color: #f56824;"></i>
          <h6>Book a Session</h6>
          <p>Schedule lessons that fit your child’s routine.</p>
        </div>
      </div>
    </div>

            </div>
</div>


<div class="container my-5">
    <div class="row g-4 justify-content-center">
         <h4 class = "text-center mb-2">Top Tutors in Your Area</h4>
      <!-- Card 1 -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-4">
        <div class="tutor-card">
          <img src="images/winner.png" alt="Tutor" class="tutor-image">
          <div class="tutor-body">
            <h6>Winner Effiong Duff <span class="float-end location">(Lagos)</span></h6>
            <p class="mb-1">English and Mathematics Tutor</p>
            <p class="price">100,000/per month</p>
            <p class="experience mb-2"><i class="bi bi-briefcase"></i> 5 years of Tutoring Experience</p>
            <div class="d-flex align-items-center">
              <div class="stars me-2">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <small>4.5</small>
            </div>
          </div>
          {{-- <div class="view-profile">
            <a href="#">View Profile</a>
          </div> --}}
          <div class = mb-2>
            <button class="btn secondaryButton" type="button" style="width: 340px; margin:5px; background: transparent; font-weight:bold;">View Profile</button>
          </div>

        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-4">
        <div class="tutor-card">
          <img src="images/winner.png" alt="Tutor" class="tutor-image">
          <div class="tutor-body">
            <h6>Winner Effiong Duff <span class="float-end location">(Lagos)</span></h6>
            <p class="mb-1">English and Mathematics Tutor</p>
            <p class="price">100,000/per month</p>
            <p class="experience mb-2"><i class="bi bi-briefcase"></i> 5 years of Tutoring Experience</p>
            <div class="d-flex align-items-center">
              <div class="stars me-2">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <small>4.5</small>
            </div>
          </div>
          {{-- <div class="view-profile">
            <a href="#">View Profile</a>
          </div> --}}
          <div class = mb-2>
            <button class="btn secondaryButton" type="button" style="width: 340px; margin:5px; background: transparent; font-weight:bold;">View Profile</button>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-4">
        <div class="tutor-card">
          <img src="images/winner.png" alt="Tutor" class="tutor-image">
          <div class="tutor-body">
            <h6>Winner Effiong Duff <span class="float-end location">(Lagos)</span></h6>
            <p class="mb-1">English and Mathematics Tutor</p>
            <p class="price">100,000/per month</p>
            <p class="experience mb-2"><i class="bi bi-briefcase"></i> 5 years of Tutoring Experience</p>
            <div class="d-flex align-items-center">
              <div class="stars me-2">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <small>4.5</small>
            </div>
          </div>
          {{-- <div class="view-profile">
            <a href="#">View Profile</a>
          </div> --}}
          <div class = mb-2>
            <button class="btn secondaryButton" type="button" style="width: 340px; margin:5px; background: transparent; font-weight:bold;">View Profile</button>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="container my-5">
    <div class="row g-4 justify-content-center">
         <h4 class = "text-center mb-2">Dedicated Support for Special Needs Learning</h4>
         <p class = "text-center">
            Every child deserves quality education.
            Our platform connects you with tutors trained in <br>
            dyslexia support, ADHD-friendly learning,
            autism education, and more.
         </p>

      <!-- Card 1 -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-4">
        <div class="tutor-card">
          <img src="images/winner.png" alt="Tutor" class="tutor-image">
          <div class="tutor-body">
            <h6>Winner Effiong Duff <span class="float-end location">(Lagos)</span></h6>
            <p class="mb-1">English and Mathematics Tutor</p>
            <p class="price">100,000/per month</p>
            <p class="experience mb-2"><i class="bi bi-briefcase"></i> 5 years of Tutoring Experience</p>
            <div class="d-flex align-items-center">
              <div class="stars me-2">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <small>4.5</small>
            </div>
          </div>
          {{-- <div class="view-profile">
            <a href="#">View Profile</a>
          </div> --}}
          <div class = mb-2>
            <button class="btn secondaryButton" type="button" style="width: 340px; margin:5px; background: transparent; font-weight:bold;">View Profile</button>
          </div>

        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-4">
        <div class="tutor-card">
          <img src="images/winner.png" alt="Tutor" class="tutor-image">
          <div class="tutor-body">
            <h6>Winner Effiong Duff <span class="float-end location">(Lagos)</span></h6>
            <p class="mb-1">English and Mathematics Tutor</p>
            <p class="price">100,000/per month</p>
            <p class="experience mb-2"><i class="bi bi-briefcase"></i> 5 years of Tutoring Experience</p>
            <div class="d-flex align-items-center">
              <div class="stars me-2">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <small>4.5</small>
            </div>
          </div>
          {{-- <div class="view-profile">
            <a href="#">View Profile</a>
          </div> --}}
          <div class = mb-2>
            <button class="btn secondaryButton" type="button" style="width: 340px; margin:5px; background: transparent; font-weight:bold;">View Profile</button>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-4">
        <div class="tutor-card">
          <img src="images/winner.png" alt="Tutor" class="tutor-image">
          <div class="tutor-body">
            <h6>Winner Effiong Duff <span class="float-end location">(Lagos)</span></h6>
            <p class="mb-1">English and Mathematics Tutor</p>
            <p class="price">100,000/per month</p>
            <p class="experience mb-2"><i class="bi bi-briefcase"></i> 5 years of Tutoring Experience</p>
            <div class="d-flex align-items-center">
              <div class="stars me-2">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <small>4.5</small>
            </div>
          </div>
          {{-- <div class="view-profile">
            <a href="#">View Profile</a>
          </div> --}}
          <div class = mb-2>
            <button class="btn secondaryButton" type="button" style="width: 340px; margin:5px; background: transparent; font-weight:bold;">View Profile</button>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="container mx-auto my-5">

    <div class="row align-items-center">

        <div class="col-lg-4 mb-4 mb-lg-0 h-25 ">
            <div class="row gx-2">
                <div class="col-6"><img src="images/stem1.png" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="images/stem2.png" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="images/stem3.png" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="images/stem4.png" alt="" class="img-fluid rounded"></div>
            </div>
        </div>


        <div class="col-12 col-md-8 col-lg-8">
            <h4>
               Share Your Knowledge. Become a Tutor.”
            </h4>
            <p>
                Join our growing community of educators and help shape the future.
                Flexible schedule, secure payments, and a platform designed to support
                your teaching journey.
            </p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> <b>Apply Online → Fill out our simple application form.</b></li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> <b>Get Verified → We review your qualifications and teaching experience.</b></li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> <b>Start Teaching → Connect with students and earn doing what you love.</b></li>
            </ul>
            <div class = "button-container d-flex d-md-flex gap-3">
                <button class="btn w-100 primaryButton" type="button">Apply for a Tutor</button>
        </div>

    </div>
</div>
  </div>
@endsection

