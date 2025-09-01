<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kokokah - Sign Up</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font (optional, choose closest to your design) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff;
    }

    .signup-container {
      min-height: 100vh;
    }

    /* Left column form */
    .signup-form {
      max-width: 420px;
      margin: auto;
    }

    .form-control {
      border-radius: 10px;
      border: 2px solid #0f4d46;
      padding: 12px;
    }

    .btn-primary-custom {
      background-color: #0f4d46;
      border: none;
      border-radius: 6px;
      padding: 12px;
      font-weight: 600;
    }

    .btn-outline-custom {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 10px;
      font-weight: 500;
    }

    .btn-outline-custom i {
      margin-left: 8px;
    }

    /* Right column background image */
    .right-col {
      background: url('images/signup-bg.png') no-repeat center center;
      background-size: cover;
      border-radius: 20px;
      min-height: 100vh;
    }

    .divider {
      text-align: center;
      position: relative;
      margin: 20px 0;
    }

    .divider::before,
    .divider::after {
      content: "";
      position: absolute;
      top: 50%;
      width: 45%;
      height: 1px;
      background: #ddd;
    }

    .divider::before { left: 0; }
    .divider::after { right: 0; }

        /* Custom input with inner top rule + label exactly like your image */
    .kok-field{
      position: relative;
      margin-bottom: 18px;
    }
    /* the inner line across the top INSIDE the input */
    .kok-field::before{
      content:"";
      position:absolute;
      left:14px; right:14px;
      top:14px;
      height:1px;
      background: var(--brand);
      opacity:.5;
      pointer-events:none;
      z-index:1;
    }
    .kok-label{
      position:absolute;
      left:18px;
      top:-10px;
      background:#fff;
      padding:0 6px 0 0; /* slight right padding so the line looks like it continues */
      color: var(--brand);
      font-weight:600;
      font-size:.92rem;
      z-index:2; /* sits above the inner line */
    }
    .kok-control{
      border: 2px solid var(--brand);
      border-radius: var(--radius);
      padding: 22px 48px 14px 16px; /* extra top padding so text sits below the inner rule */
      font-size: 0.98rem;
      line-height: 1.3;
    }
    .kok-control::placeholder{
      color: var(--muted);
      opacity: 1;
    }
    .kok-control:focus{
      box-shadow: 0 0 0 .2rem rgba(14,74,74,.12);
      border-color: var(--brand);
    }
  </style>
</head>
<body>

  <div class="container-fluid signup-container">
    <div class="row ">
      <!-- Left Column -->
      <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="signup-form w-100 px-4">
          <!-- Logo -->
          <div class="mb-3 pt-3 text-center">
            <img src="images/kokokah_Logo.png"  alt="Kokokah Logo" height="50">
          </div>

          <!-- Heading -->
          <h3 class="fw-bold mb-1">Sign up</h3>
          <p class="text-muted mb-4">Please login to continue to your account.</p>

          <!-- Form -->
          <form>
            
            <div class="mb-3 kok-field">
              <input type="email" class="form-control" placeholder="Enter Email Address">
            </div>
            <div class="mb-3 input-group kok-field">
              <input type="password" class="form-control" placeholder="Enter Password">
              <span class="input-group-text bg-white"><i class="fa-regular fa-eye-slash"></i></span>
            </div>
            <button type="submit" class="btn btn-primary-custom w-100">Sign in</button>
          </form>



          <p class="text-center small text-muted">© Copyright Kokokah 2025, All Right Reserved</p>
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
