@extends('layouts.template' )
@section('content')

  <div class="container my-5">
    <div class="profile-card">

      <!-- Profile Header -->
      <div class="row align-items-center mb-4">
        <div class="col-12 col-md-3 text-center mb-3 mb-md-0">
          <img src="images/default-avatar.png" alt="Tutor" class="profile-image">
        </div>

        <div class="col-12 col-md-9">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
              <h5>Winner Effiong Duff</h5>
              <p class="mb-1">English and Mathematics Tutor</p>
              <div class="icon-text mb-1"><i class="bi bi-geo-alt"></i> Lagos, Nigeria</div>
              <div class="icon-text mb-2"><i class="bi bi-briefcase"></i> 5 years of Tutoring Experience</div>
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
                <span class="ms-1">4.5</span>
              </div>
            </div>
            <div class="text-md-end mt-3 mt-md-0">
              <h6>100,000.00/per month</h6>
            </div>
          </div>
        </div>
      </div>

      <!-- Tutor Details -->
      <h6>Tutor Details</h6>
      <p class="mb-4">
        Schedule lessons that fit your child’s routine. Schedule lessons that fit your child’s routine.
        Schedule lessons that fit your child’s routine. Schedule lessons that fit your child’s routine.
        Schedule lessons that fit your child’s routine. Schedule lessons that fit your child’s routine.
      </p>

      <!-- Working Time -->
      <h6 class="section-title">Working Time</h6>
      <p class="mb-4">Monday (4pm–6pm), Tuesday (9am–12pm), Thursday (12pm–4pm)</p>

      <!-- Reviews -->
      <h6 class="section-title">Reviews</h6>
      <div class="review-card">
        <div class="d-flex align-items-start">
          <img src="images/image.png" alt="Reviewer" class="me-3">
          <div>
            <p class="reviewer-name mb-1">Ayomide Peters</p>
            <p class="mb-1 text-muted small">English language tutor. He’s been there all sessions, especially for reading comprehension exercises. I find him excellent in my writing too.</p>

            <div class="stars">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>


          </div>
        </div>
      </div>

      <div class="review-card">
        <div class="d-flex align-items-start">
          <img src="images/image.png" alt="Reviewer" class="me-3">
          <div>
            <p class="reviewer-name mb-1">Anayochie Prince</p>
            <p class="mb-1 text-muted small">English language tutor. He’s been there all sessions, especially for reading comprehension exercises. I find him excellent in my writing too.</p>
            <div class="stars">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Book Button -->
      <div class="text-center mt-4">
        <button class="book-btn w-75" data-bs-toggle="modal" data-bs-target="#bookTutorModal">Book Tutor</button>
      </div>

    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="bookTutorModal" tabindex="-1" aria-labelledby="bookTutorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <h5 class="text-center mb-3 fw-semibold">Book Tutor</h5>
        <form>
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Enter First Name" required>
          </div>
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Enter Last Name" required>
          </div>
          <div class="mb-3">
            <input type="email" class="form-control" placeholder="Enter Email Address" required>
          </div>
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Enter Gender" required>
          </div>
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Number of Students" required>
          </div>
          <button type="submit" class="proceed-btn">Proceed</button>
        </form>
      </div>
    </div>
  </div>
@endsection
