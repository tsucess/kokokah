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
          <h4 class = "text-dark mb-2">Reset Your Password</h4>
          <p class="mb-4" style = "color:#969696;font:inter;">
            Please set a new password for your account.
          </p>

          <form id="resetForm" method="POST">
            @csrf
            <div class="custom-form-group mb-5">
              <label for="password" class="custom-label">Enter new Password</label>
              <div class="password-input-wrapper">
                <input type="password" class="form-control-custom" id="password" name="password" placeholder="*******" aria-label="New Password" aria-describedby="passwordStrength" autocomplete="new-password" required>
                <button class="password-toggle-btn" type="button" id="togglePassword" title="Toggle password visibility">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
              <small class="text-muted d-block mt-1" id="passwordStrength"></small>
            </div>

            <div class="custom-form-group mb-5">
              <label for="confirmPassword" class="custom-label">Re-enter new Password</label>
              <div class="password-input-wrapper">
                <input type="password" class="form-control-custom" id="confirmPassword" name="passwordConfirm" placeholder="*******" aria-label="Confirm Password" autocomplete="new-password" required>
                <button class="password-toggle-btn" type="button" id="toggleConfirmPassword" title="Toggle password visibility">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
            </div>

            <button type="submit" class="btn primaryButton w-100" id="resetBtn">Set Password</button>
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
    UIHelpers.storeButtonText('resetBtn');

    // Get reset token from URL
    const resetToken = UIHelpers.getUrlParameter('token');
    const email = UIHelpers.getUrlParameter('email');

    if (!resetToken || !email) {
      UIHelpers.showError('Invalid password reset link');
    }

    // Password visibility toggles
    document.getElementById('togglePassword').addEventListener('click', () => {
      const input = document.getElementById('password');
      const icon = document.querySelector('#togglePassword i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', () => {
      const input = document.getElementById('confirmPassword');
      const icon = document.querySelector('#toggleConfirmPassword i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });

    // Password strength indicator
    document.getElementById('password').addEventListener('input', (e) => {
      const password = e.target.value;
      const strengthMsg = UIHelpers.getPasswordStrengthMessage(password);
      const strengthElement = document.getElementById('passwordStrength');

      if (password.length === 0) {
        strengthElement.textContent = '';
      } else if (UIHelpers.isValidPassword(password)) {
        strengthElement.textContent = 'Strong password';
        strengthElement.style.color = 'green';
      } else {
        strengthElement.textContent = strengthMsg;
        strengthElement.style.color = 'red';
      }
    });

    // Handle reset password form submission
    document.getElementById('resetForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const password = document.getElementById('password').value;
      const passwordConfirm = document.getElementById('confirmPassword').value;

      if (!password || !passwordConfirm) {
        UIHelpers.showError('Please fill in all fields');
        return;
      }

      if (password !== passwordConfirm) {
        UIHelpers.showError('Passwords do not match');
        return;
      }

      if (!UIHelpers.isValidPassword(password)) {
        UIHelpers.showError(UIHelpers.getPasswordStrengthMessage(password));
        return;
      }

      UIHelpers.setButtonLoading('resetBtn', true);
      UIHelpers.showLoadingOverlay(true);

      const result = await AuthApiClient.resetPassword(email, resetToken, password, passwordConfirm);

      UIHelpers.showLoadingOverlay(false);

      if (result.success) {
        UIHelpers.showSuccess('Password reset successfully! Redirecting to login...');
        UIHelpers.redirect('/login', 1500);
      } else {
        UIHelpers.showError(result.message || 'Failed to reset password');
        UIHelpers.setButtonLoading('resetBtn', false);
      }
    });
  </script>
</body>
</html>
