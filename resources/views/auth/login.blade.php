<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kokokah - Sign In</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="{{ asset('css/style.css') }}?v={{ time() }}" rel="stylesheet">
  <link href="{{ asset('css/access.css') }}?v={{ time() }}" rel="stylesheet">

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

          <!-- Alert Container -->
          <div id="alertContainer"></div>

          <!-- Heading -->
          <h4 class="auth-heading">Sign in</h4>
          <p class="auth-subheading">Please login to continue to your account.</p>

          <form id="loginForm" method="POST" action="javascript:void(0);" data-ajax>
                @csrf
                <div class = "pt-3">
                <div class="custom-form-group">

                    <label for="email" class="custom-label">Email</label>

                    <input type="email" class="form-control-custom" id="email" name="email" placeholder="user@example.com" aria-label="Email Address" autocomplete="email" required>
                </div>


                <div class="custom-form-group mb-2">

                    <label for="password" class="custom-label">Password</label>

                    <div class="password-input-wrapper">
                      <input type="password" class="form-control-custom" id="password" name="password" placeholder="*******" aria-label="Password" autocomplete="current-password" required>
                      <button class="password-toggle-btn" type="button" id="togglePassword" title="Toggle password visibility">
                        <i class="fa fa-eye"></i>
                      </button>
                    </div>
                </div>
                </div>

               <div class="auth-checkbox-row">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe">
                        <label class="form-check-label" for="rememberMe">
                            Keep me logged in
                        </label>
                    </div>

                    <div>
                        <a href="/forgot-password">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="btn primaryButton" id="loginBtn">Sign in</button>

                <p class="mt-3 text-center">
                  Don't have an account? <a href="/register" style="color: #FDAF22; text-decoration: none;">Sign up</a>
                </p>
          </form>
        </div>
      </div>

      <!-- Right Column with Image -->
      <div class="col-lg-6 right-col d-none d-lg-block"></div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <script type="module">
    import AuthApiClient from '/js/api/authClient.js';
    import UIHelpers from '/js/utils/uiHelpers.js';

    // Store original button text
    UIHelpers.storeButtonText('loginBtn');

    // Password visibility toggle
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

    // Handle login form submission
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;
      const rememberMe = document.getElementById('rememberMe').checked;

      // Validate inputs
      if (!email || !password) {
        UIHelpers.showError('Please fill in all fields');
        return;
      }

      if (!UIHelpers.isValidEmail(email)) {
        UIHelpers.showError('Please enter a valid email address');
        return;
      }

      // Show loading state
      UIHelpers.setButtonLoading('loginBtn', true);
      UIHelpers.showLoadingOverlay(true);

      // Call login API
      const result = await AuthApiClient.login(email, password);

      UIHelpers.showLoadingOverlay(false);

      if (result.success) {
        UIHelpers.showSuccess('Login successful! Redirecting...');

        // Determine redirect URL based on user role
        let redirectUrl = '/dashboard'; // Default for admin/instructor

        // Get user from result.data.user or result.user
        const user = result.data?.user || result.user;

        if (user && user.role === 'student') {
          redirectUrl = '/usersdashboard';
        }

        // Redirect after 1.5 seconds
        UIHelpers.redirect(redirectUrl, 1500);
      } else {
        UIHelpers.showError(result.message || 'Login failed');
        UIHelpers.setButtonLoading('loginBtn', false);
      }
    });
  </script>
</body>
</html>
