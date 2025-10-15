<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Kokokah — STEM Registration</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root {
      --teal: #004A53;
      --accent: #F4B23A;
      --input-border: #B5C7C8;
      /* --teal: #4a7a7a; */
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff;
      color: var(--teal);
    }

    /* Main layout */
    .page-wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    .left-panel {
      text-align: center;
      padding: 20px;
      /* padding-top:5px; */
      /* height:900px; */
    }

    .logo {
      /* width: 180px; */
      width:100%;
      max-width: 62%;
      margin-bottom: 40px;
    }

    .stem-illustration {
      max-width: 100%;
      height: auto;
    }

    .left-cta {
      font-weight: 700;
      color: var(--teal);
      margin-top: 15px;
      font-size: 1.25rem;
    }

    .right-panel {
      padding: 40px 30px;
      max-width: 520px;
    }

    .form-title {
      font-weight: 700;
      font-size: 1.05rem;
      color: var(--teal);
    }

    .form-sub {
      color: #6c7e7e;
      font-size: .95rem;
      margin-bottom: 18px;
    }

    /* Improved Input Fields */
    .form-control-custom, .form-select-custom
    {
      width: 100%;
      border-radius: 10px;
      border: 1.8px solid var(--input-border);
      padding: 13px 16px;
      height: 50px;
      background-color: #fff;
      font-size: .96rem;
      color: #18312a;
      transition: all .2s ease;
    }

    .form-control-custom::placeholder
    {
      color: #8a9a9a;
      opacity: 1;
      font-weight: 500;
    }

    .form-control-custom:focus, .form-select-custom:focus
    {
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(0, 74, 83, 0.08);
      outline: none;
    }

    .form-control-custom:hover, .form-select-custom:hover
    {
      border-color: #8fb4b4;
    }

    /* Dropdown Arrow */
    .form-select-custom {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      background-image: linear-gradient(45deg, transparent 50%, var(--teal) 50%),
                        linear-gradient(135deg, var(--teal) 50%, transparent 50%);
      background-position: calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px);
      background-size: 6px 6px, 6px 6px;
      background-repeat: no-repeat;
      padding-right: 2.8rem;
    }

    /* Gender Section */
    .gender-label {
      display: block;
      font-weight: 500;
      font-size: .9rem;
      margin-bottom: 5px;
    }

    .form-check-label {
      font-weight: 500;
      color: #2e4e4e;
    }

    /* Button */
    .btn-next {
      background-color: var(--accent);
      color: #18312a;
      font-weight: 700;
      border: none;
      border-radius: 8px;
      padding: .8rem 1rem;
      width: 100%;
      transition: background-color .2s ease;
    }

    .btn-next:hover {
      background-color: #f8c451;
    }


    .copyright {
      font-size: .82rem;
      color: #9aa5a5;
      margin-top: 18px;
      text-align:center;
    }


    /* Footer */
    .small-footer {
      font-size: .82rem;
      color: #9aa5a5;
      margin-top: 18px;
      text-align:end;
    }

    /* Responsive Adjustments */
    @media (max-width: 991px) {
      .right-panel { padding: 25px 20px; }
      .left-panel { margin-bottom: 30px; }
    }

    @media (max-width: 767px) {
      .page-wrap { padding: 25px 10px; }
      .left-panel { order: -1; }
      .left-cta { font-size: 1.1rem; }
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
      top: -0.6rem;
      left: 0.9rem;
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
  @vite(['resources/css/style.css'])
</head>

<body>
  <div class="container-fluid page-wrap">
    <div class="container">
      <div class="row align-items-center justify-content-center">

        <!-- Left Section -->
        <div class="col-12 col-lg-6 col-md-6 left-panel">
          <img src="images/Kokokah_logo.png" alt="Kokokah Logo" class="img-fluid logo">
          <img src="images/steming.png" alt="STEM Illustration" class="stem-illustration">
          <div class="left-cta">Register to enjoy learning</div>


          {{-- <div class="d-flex flex-column justify-content-end copyright" style="min-height: 280px;">
            <small>© Copyright Kokokah 2025. All Rights Reserved </small>
          </div> --}}

        </div>

        <!-- Right Section -->
        <div class="col-12 col-lg-6 col-md-6 right-panel bg-white">
          <h4>Register for our Science Technology Engineering and Mathematics Masterclass</h4>
          <p>Get Started today!</p>

          <form>
        <div class="mb-4 form-floating">
      <input type="text" class="form-control" id="fullname" placeholder="majorsignature">
      <label for="parentname">Enter Parent/Guardian Name</label>
    </div>

    <div class="mb-4 form-floating">
      <input type="email" class="form-control" id="email" placeholder="majorsignature@gmail.com">
      <label for="parentemail">Enter Parent/Guardian Name Email Address</label>
    </div>

    <div class="mb-4 form-floating">
      <input type="tel" class="form-control" id="email" placeholder="+23412345678">
      <label for="phonenumber">Enter Parent/Guardian Name Phone Number</label>
    </div>

    <div class="mb-4 form-floating">
      <input type="text" class="form-control" id="fullname" placeholder="Enter enrolled school">
      <label for="enrolledschool">Enter Currently Enrolled School</label>
    </div>

    <div class="mb-4 form-floating">
      <input type="text" class="form-control" id="fullname" placeholder="Enter address of enrolled school">
      <label for="contactenrolledschool">Enter Contact Address of Currently Enrolled School</label>
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

{{-- <button type="submit" class="btn-next">Next</button> --}}

<button type="button" class="btn w-100 primaryButton" data-bs-toggle="modal" data-bs-target="#makePayment">Proceed to Make Payment</button>
<button type="button" class="btn mt-3 w-100 secondaryButton">Previous</button>

            {{-- <div class="small-footer mt-4">

              <small>License | More Themes | Documentation | Support</small>
            </div> --}}
  </div>





        </div>
      </div>
    </div>



  <!-- Proceed Payment Modal -->
  <div class="modal fade" id="makePayment" tabindex="-1" aria-labelledby="makePaymentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 position-relative">

      <!-- Close Button -->
      <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

      <!-- Modal Body -->
      <h5 class="text-center mb-3 fw-semibold">Registration Information</h5>
      <p>
        <b>Enrollment Fee - </b> Registration is deemed to be completed upon
        Payment of a Fee of N75,000 to cover Learning, T-Shirts,
        Project Materials and Certification.
      </p>

      <p>
        <b>Maximum Class Size – </b> 50 Students. Kids are separated from Teenagers
      </p>

      <p>
        <b>On Site Location - </b> 8a Adebayo Mokuolu St, Anthony Village Lagos, Nigeria
      </p>

      <h6 class="text-danger mt-5 mb-5">Return to Registration</h6>

      <button type="button" class="btn w-100 primaryButton" data-bs-toggle="modal" data-bs-target="#confirmation">Proceed to Make Payment</button>
    </div>
  </div>
</div>


<!-- Success Modal -->
  <div class="modal fade" id="confirmation" tabindex="-1" aria-labelledby="makePaymentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 position-relative">

      <!-- Close Button -->
      <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

      <!-- Modal Body -->
      <h5 class = "mt-5 text-center">You have successfully made a payment of N100,000</h5>

      <p class = "text-center">
        Check your email for other information.
      </p>

      <button type="button" class="btn w-100 primaryButton" href = "#">Back to Home</button>
    </div>
  </div>
</div>





    <div class = "container">
    <div class = "d-flex mb-4">

        <div class = "col-12 col-md-7 col-lg-7 text-center">
            <small>© Copyright Kokokah 2025. All Rights Reserved </small>
        </div>

        <div class = "col-12 col-md-5 col-lg-5  text-end">
            <small>License | More Themes | Documentation | Support</small>
        </div>

    </div>
    </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
