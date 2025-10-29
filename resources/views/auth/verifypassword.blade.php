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
          <div class="mb-5 d-flex justify-content-center">
            <img src="images/Kokokah_Logo.png" alt="Kokokah Logo" class = "img-fluid w-75">
          </div>

          <a href = "/login" class="btn btn-link mb-4 p-0" style="color: #313131; font-weight: 500; text-decoration: none;"> <i class="fa fa-arrow-left"></i> Back to login</a>
          <!-- Heading -->
          <h4 class = "text-dark mb-2">Verify code</h4>
          <p class="mb-4" style = "color:#969696;font:inter;">An authentication code has been sent to your email</p>

                <div class="custom-form-group">

                    <label for="verifycode" class="custom-label">Enter code</label>

                    <input type="text" class="form-control-custom" id="verifycode" placeholder="80EAS33">
                </div>
                <p>
                    Didn’t receive a code?
                    <a href = "#" style = "color:red; text-decoration:none;">Resend</a>
                </p>


                <button type="submit" class="btn primaryButton w-100">Verify</button>

        </div>
      </div>

      <!-- Right Column with Image -->
      <div class="col-lg-6 right-col-passwordverify d-none d-lg-block"></div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
