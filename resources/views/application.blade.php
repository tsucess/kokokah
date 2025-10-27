@extends('layouts.template' )
<style>
    body {
      background-color: #f9f9f9;
      font-family: 'Segoe UI', sans-serif;
    }

    .profile-card {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
      padding: 2rem;
    }

    .book-btn {
      background-color: #FFB400;
      color: #fff;
      font-weight: 600;
      border: none;
      padding: 12px 25px;
      border-radius: 6px;
      transition: 0.3s ease;
    }

    .book-btn:hover {
      background-color: #e0a000;
    }

    .modal-content {
      border-radius: 10px;
      padding: 20px;
    }

    .modal-backdrop.show {
      opacity: 0.85;
    }

    .proceed-btn {
      background-color: #FFB400;
      color: #fff;
      font-weight: 600;
      border: none;
      padding: 12px;
      border-radius: 6px;
      width: 100%;
      transition: 0.3s;
    }

    .proceed-btn:hover {
      background-color: #e0a000;
    }

    /* Spinner modal */
    .spinner-border {
      width: 3rem;
      height: 3rem;
    }

    .form-floating {
      position: relative;
      margin-bottom: 1rem;
    }

    /* The input box */
    .form-control {
      border: 1.8px solid var(--teal);
      border-radius: 8px;
      height: 55px;
      padding: 1rem 1rem .5rem 1rem;
      font-size: 0.95rem;
      color: #333;
    }

    /* Label styling */
    .form-floating > label {
      color: var(--teal);
      font-weight: 600;
      font-size: 0.85rem;
      padding: 0 .4rem;
      background-color: white;
      /* top: -0.6rem;
      left: 0.9rem; */
      position: absolute;
    }

    /* Remove default Bootstrap label animation */
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
      opacity: 1;
      height: 20px;
      transform: none;
    }

    /* Focus border color */
    .form-control:focus {
      border-color: var(--teal);
      box-shadow: none;
    }
  </style>
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
                <button class="btn btn-block w-100 primaryButton application-button">Sign Up to Teach</button>
                {{-- <button class="btn btn-outline-dark btn-lg">Request a Demo</button> --}}
            </div>

        </div>

        <div class="col-12 col-md-5 col-lg-5 text-center p-5">
            <img src="images/stem.png" alt="stem illustration" class="img-fluid ">
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
               What You’ll Need to Join
            </h4>

            <ul class="list-unstyled">
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right requirement-icon"></i> <b>Valid teaching qualification or strong subject expertise</b></li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right requirement-icon"></i> <b>Passion for teaching and mentoring students</b></li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right requirement-icon"></i> <b>Ability to commit to scheduled sessions</b></li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right requirement-icon"></i> <b>(Optional) Experience in special needs education is a plus</b></li>
            </ul>
            <div class = "button-container d-flex d-md-flex gap-3">
                <button class="btn w-100 primaryButton" type="button">Apply for a Tutor</button>
        </div>

    </div>
</div>
  </div>



  <div class="container my-5">
    <div class="row text-center">

      <h5 class="fw-bold mb-3">Tutor Application Form</h5>

        <form>
        <div class="mb-4 form-floating">
      <input type="text" class="form-control" id="fullname" placeholder="majorsignature">
      <label for="parentname" class="form-label-floating">Enter Parent/Guardian Name</label>
    </div>

    <div class="mb-4 form-floating">
      <input type="email" class="form-control" id="email" placeholder="majorsignature@gmail.com">
      <label for="parentemail" class="form-label-floating">Enter Parent/Guardian Name Email Address</label>
    </div>

    <div class="mb-4 form-floating">
      <input type="tel" class="form-control" id="email" placeholder="+23412345678">
      <label for="phonenumber" class="form-label-floating">Enter Parent/Guardian Name Phone Number</label>
    </div>

    <div class="mb-4 form-floating">
      <input type="text" class="form-control" id="fullname" placeholder="Enter enrolled school">
      <label for="enrolledschool" class="form-label-floating">Enter Currently Enrolled School</label>
    </div>

    <div class="mb-4 form-floating">
      <input type="text" class="form-control" id="fullname" placeholder="Enter address of enrolled school">
      <label for="contactenrolledschool" class="form-label-floating">Enter Contact Address of Currently Enrolled School</label>
    </div>

        <div class="mb-4">
              <label class="gender-label">Are you a Kokokah User?</label>
              <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="user" id="yes" value="yes" checked>
                  <label class="form-check-label" for="male">Yes</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="no" value="no">
                  <label class="form-check-label" for="female">No</label>
                </div>
              </div>
            </div>


    <div class="mb-4">
              <label class="gender-label">Participated in previous Passnownow Programs?</label>
              <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="male" value="male" checked>
                  <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                  <label class="form-check-label" for="female">Female</label>
                </div>
              </div>
            </div>

</form>

      <button class="book-btn" data-bs-toggle="modal" data-bs-target="#bookTutorModal">Book Tutor</button>
    </div>
  </div>

  <!-- BOOKING FORM MODAL -->
  <div class="modal fade" id="bookTutorModal" tabindex="-1" aria-labelledby="bookTutorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <h5 class="text-center mb-3 fw-semibold">Book Tutor</h5>
        <form id="bookingForm">
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
            <input type="number" class="form-control" placeholder="Number of Students" required>
          </div>
          <button type="submit" class="proceed-btn">Proceed</button>
        </form>
      </div>
    </div>
  </div>

  <!-- PROCESSING MODAL -->
  <div class="modal fade" id="processingModal" tabindex="-1" aria-labelledby="processingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered text-center">
      <div class="modal-content">
        <div class="p-4">
          <div class="spinner-border text-warning mb-3" role="status"></div>
          <h6 class="fw-semibold mb-2">Processing your booking...</h6>
          <p class="text-muted small mb-0">Please wait while we confirm your request.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- SUCCESS MODAL -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-center">
      <div class="modal-content">
        <div class="p-4">
          <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
          <h6 class="fw-semibold mb-2">Booking Successful!</h6>
          <p class="text-muted small mb-3">Your tutor will contact you shortly.</p>
          <button type="button" class="btn btn-warning text-white fw-semibold" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const bookingForm = document.getElementById('bookingForm');
    const bookTutorModal = new bootstrap.Modal(document.getElementById('bookTutorModal'));
    const processingModal = new bootstrap.Modal(document.getElementById('processingModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));

    bookingForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Close booking form
      bookTutorModal.hide();

      // Show processing modal
      processingModal.show();

      // Simulate processing delay (e.g. API request)
      setTimeout(() => {
        processingModal.hide();
        successModal.show();
      }, 2500);
    });
  </script>


@endsection
