<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kokokah - Sign Up</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

  @vite(['resources/css/style.css'])
  @vite(['resources/css/access.css'])
  <style>


  </style>
</head>
<body>

  <div class="container-fluid signup-container">
    <div class="row">

      <!-- Left Column -->
      <div class="col-lg-6 d-flex">
        <div class="signup-form">
          <!-- Logo -->
          <div class="mb-5  d-flex justify-content-center">
            <img src="images/Kokokah_Logo.png" alt="Kokokah Logo" class = "img-fluid w-75">
          </div>

          <!-- Heading -->
          <h4 class="auth-heading">Sign up</h4>
          <p class="auth-subheading">Please login to continue to your account.</p>


          <div class="custom-form-group">

                    <label for="firstNameInput" class="custom-label">Enter First Name</label>

                    <input type="text" class="form-control-custom" id="firstNameInput" placeholder="Winner">
                </div>

                <div class="custom-form-group">

                    <label for="lastNameInput" class="custom-label">Enter Last Name</label>

                    <input type="text" class="form-control-custom" id="lastNameInput" placeholder="Effiong">
                </div>

                <div class="custom-form-group">

                    <label for="emailaddress" class="custom-label">Enter Email Address</label>

                    <input type="email" class="form-control-custom" id="emailaddress" placeholder="majorsignature@gmail.com">
                </div>


                <div class="custom-form-group">

                    <label for="psw" class="custom-label">Enter Password</label>

                    <input type="password" class="form-control-custom" id="psw" placeholder="*******">
                </div>


                <div class="custom-form-group">

                    <label for="psw" class="custom-label">Select Role</label>

                    {{-- <input type="password" class="form-control-custom" id="psw" placeholder="*******"> --}}

                    <select class="form-control-custom" aria-label="select role">
                            {{-- <option selected>Teacher</option> --}}
                            <option value="1">Student</option>
                             {{-- <option value="2">Parent</option> --}}
                    </select>

                </div>

                <button type="submit" class="btn primaryButton">Sign Up</button>


          <!-- Divider -->
          <div class="divider">or</div>

          <!-- Social logins -->
          <div class="d-grid gap-2">
            <button class="btn btn-outline-custom">
              Continue with Google <i class="fab fa-google text-danger"></i>
            </button>
            <button class="btn btn-outline-custom">
              Continue with Facebook <i class="fab fa-facebook text-primary"></i>
            </button>
            <button class="btn btn-outline-custom">
              Continue with Apple <i class="fab fa-apple text-dark"></i>
            </button>
          </div>

          <!-- Footer link -->
          <p class="auth-footer-link">
            Already have an account? <a href="#">Login</a>
          </p>
          {{-- <p class="mt-5 text-center small text-muted">© Copyright Kokokah 2025, All Right Reserved</p> --}}
        </div>
      </div>

      <!-- Right Column with Image -->
      <div class="col-lg-6 right-col d-none d-lg-block"></div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
