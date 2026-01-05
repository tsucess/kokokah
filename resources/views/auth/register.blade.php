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
          <h4 class="auth-heading">Sign up</h4>
          <p class="auth-subheading">Create your account to get started.</p>

          <form id="registerForm" method="POST" action="javascript:void(0);" data-ajax>
            @csrf
            <div class="custom-form-group">
              <label for="firstName" class="custom-label">Enter First Name</label>
              <input type="text" class="form-control-custom" id="firstName" name="firstName" placeholder="Winner" aria-label="First Name" autocomplete="given-name" required>
            </div>

            <div class="custom-form-group">
              <label for="lastName" class="custom-label">Enter Last Name</label>
              <input type="text" class="form-control-custom" id="lastName" name="lastName" placeholder="Effiong" aria-label="Last Name" autocomplete="family-name" required>
            </div>

            <div class="custom-form-group">
              <label for="email" class="custom-label">Enter Email Address</label>
              <input type="email" class="form-control-custom" id="email" name="email" placeholder="user@example.com" aria-label="Email Address" autocomplete="email" required>
            </div>

            <div class="custom-form-group">
              <label for="password" class="custom-label">Enter Password</label>
              <div class="password-input-wrapper">
                <input type="password" class="form-control-custom" id="password" name="password" placeholder="*******" aria-label="Password" aria-describedby="passwordStrength" autocomplete="new-password" required>
                <button class="password-toggle-btn" type="button" id="togglePassword" title="Toggle password visibility">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
              <small class="text-muted d-block mt-1" id="passwordStrength"></small>
            </div>

            <div class="custom-form-group">
              <label for="role" class="custom-label">Select Role</label>
              <select class="form-control-custom" id="role" name="role" aria-label="User Role" required>
                <option value="">-- Select Role --</option>
                <option value="student">Student</option>
                <option value="instructor">Instructor</option>
              </select>
            </div>

            <button type="submit" class="btn primaryButton" id="registerBtn">Sign Up</button>

            <p class="mt-3 text-center">
              Already have an account? <a href="/login" style="color: #FDAF22; text-decoration: none;">Sign in</a>
            </p>
          </form>

          <!-- Divider -->
          <div class="divider">or</div>

          <!-- Social logins -->
          <div class="d-grid gap-2">
            <button class="btn btn-outline-custom" type="button" disabled>
              Continue with Google <i class="fab fa-google text-danger"></i>
            </button>
            <button class="btn btn-outline-custom" type="button" disabled>
              Continue with Facebook <i class="fab fa-facebook text-primary"></i>
            </button>
            <button class="btn btn-outline-custom" type="button" disabled>
              Continue with Apple <i class="fab fa-apple text-dark"></i>
            </button>
          </div>
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

      <!-- API Clients -->
    <script src="/js/api/baseApiClient.js"></script>
    <script src="/js/api/authClient.js"></script>
    <script src="/js/utils/uiHelpers.js"></script>

    <script>
// Store original button text
    UIHelpers.storeButtonText('registerBtn');

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

    // Password strength indicator
    document.getElementById('password').addEventListener('input', (e) => {
      const password = e.target.value;
      const strengthMsg = UIHelpers.getPasswordStrengthMessage(password);
      const strengthElement = document.getElementById('passwordStrength');

      if (password.length === 0) {
        strengthElement.textContent = '';
      } else if (UIHelpers.isValidPassword(password)) {
        strengthElement.textContent = '✓ ' + strengthMsg;
        strengthElement.style.color = 'green';
      } else {
        strengthElement.textContent = '✗ ' + strengthMsg;
        strengthElement.style.color = 'red';
      }
    });

    // Handle register form submission
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const firstName = document.getElementById('firstName').value.trim();
      const lastName = document.getElementById('lastName').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;
      const role = document.getElementById('role').value;

      // Debug: Log the form data
      console.log('Form Data:', {
        firstName,
        lastName,
        email,
        password,
        role
      });

      // Validate inputs
      if (!firstName || !lastName || !email || !password || !role) {
        UIHelpers.showError('Please fill in all fields');
        return;
      }

      if (!UIHelpers.isValidName(firstName)) {
        UIHelpers.showError('First name must be 2-50 characters and contain only letters, spaces, hyphens, or apostrophes');
        return;
      }

      if (!UIHelpers.isValidName(lastName)) {
        UIHelpers.showError('Last name must be 2-50 characters and contain only letters, spaces, hyphens, or apostrophes');
        return;
      }

      if (!UIHelpers.isValidEmail(email)) {
        UIHelpers.showError('Please enter a valid email address');
        return;
      }

      if (!UIHelpers.isValidPassword(password)) {
        UIHelpers.showError(UIHelpers.getPasswordStrengthMessage(password));
        return;
      }

      // Show loading state
      UIHelpers.setButtonLoading('registerBtn', true);
      UIHelpers.showLoadingOverlay(true);

      // Store email for verification page
      sessionStorage.setItem('registerEmail', email);

      // Debug: Log API call
      console.log('Calling API with:', {
        firstName,
        lastName,
        email,
        password,
        role
      });

      // Call register API
      const result = await AuthApiClient.register(firstName, lastName, email, password, role);

      // Debug: Log API response
      console.log('API Response:', result);

      UIHelpers.showLoadingOverlay(false);

      if (result.success) {
        UIHelpers.showSuccess('Registration successful! Redirecting to verification...');
        // Redirect to verification page after 1.5 seconds
        UIHelpers.redirect('/verify', 1500);
      } else {
        UIHelpers.showError(result.message || 'Registration failed');
        UIHelpers.setButtonLoading('registerBtn', false);
      }
    });    </script>
</body>
</html>
