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

  <!-- Custom CSS -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('css/access.css') }}" rel="stylesheet">

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

          <!-- Alert Container -->
          <div id="alertContainer"></div>

          <!-- Heading -->
          <h4 class = "text-dark mb-2">Forgot your Password?</h4>
          <p class="mb-5" style = "color:#969696;font:inter;">Enter your email below to recover your password.</p>

          <form id="forgotForm" method="POST" action="javascript:void(0);">
            @csrf
            <div class="custom-form-group">
              <label for="email" class="custom-label">Enter Email Address</label>
              <input type="email" class="form-control-custom" id="email" name="email" placeholder="majorsignature@gmail.com" aria-label="Email Address" autocomplete="email" required>
            </div>

            <button type="submit" class="btn primaryButton w-100" id="forgotBtn">Submit</button>
          </form>

        </div>
      </div>

      <!-- Right Column with Image -->
      <div class="col-lg-6 right-col-passwordretrieve d-none d-lg-block"></div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <script type="module">
    import AuthApiClient from '{{ asset('js/api/authClient.js') }}';
    import UIHelpers from '{{ asset('js/utils/uiHelpers.js') }}';

    // Store original button text
    UIHelpers.storeButtonText('forgotBtn');

    // Handle forgot password form submission
    document.getElementById('forgotForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const email = document.getElementById('email').value.trim();

      if (!email) {
        UIHelpers.showError('Please enter your email address');
        return;
      }

      if (!UIHelpers.isValidEmail(email)) {
        UIHelpers.showError('Please enter a valid email address');
        return;
      }

      UIHelpers.setButtonLoading('forgotBtn', true);
      UIHelpers.showLoadingOverlay(true);

      const result = await AuthApiClient.sendPasswordResetLink(email);

      UIHelpers.showLoadingOverlay(false);

      if (result.success) {
        UIHelpers.showSuccess('Password reset link sent to your email!');
        document.getElementById('forgotForm').reset();
        UIHelpers.setButtonLoading('forgotBtn', false);
      } else {
        UIHelpers.showError(result.message || 'Failed to send reset link');
        UIHelpers.setButtonLoading('forgotBtn', false);
      }
    });
  </script>
</body>
</html>
