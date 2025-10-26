<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kokokah - Sign Up</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- css styles -->


  <!-- Google Font -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

  @vite(['resources/css/style.css'])
  @vite(['resources/css/access.css'])

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
          <h4 class = "text-dark mb-2">Sign in</h4>
          <p class="mb-5" style = "color:#969696;font:inter;">Please login to continue to your account.</p>

                <div class = "pt-3">
                <div class="custom-form-group">

                    <label for="emailaddress" class="custom-label">Email</label>

                    <input type="email" class="form-control-custom" id="emailaddress" placeholder="majorsignature@gmail.com">
                </div>


                <div class="custom-form-group mb-2">

                    <label for="psw" class="custom-label">Password</label>

                    <input type="password" class="form-control-custom" id="psw" placeholder="*******">
                </div>
                </div>


               {{-- <div class = "d-flex justify-content-between">

                <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Keep me logged in
                 </label>
                </div>

               <div class = "mb-5">
                <a class = "text-decoration-none text-dark" href = "#" style = "color:#232323;">Forgot Password?</a>
               </div>


               </div> --}}

               <div class="d-flex justify-content-between align-items-center mb-4">

    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault" style="font-size: 0.95rem;">
            <b>Keep me logged in</b>
        </label>
    </div>

    <div>
        <a class="text-decoration-none" href="#" style="color:#232323; font-size: 0.95rem;">
            <b>Forgot Password?</b>
        </a>
    </div>
</div>


                <button type="submit" class="btn primaryButton text-dark w-100">Sign in</button>



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
