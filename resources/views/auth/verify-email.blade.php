<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kokokah - Verify Email</title>

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
                    <div class="mb-5 d-flex justify-content-center">
                        <img src="images/logo-auth.png" alt="Kokokah Logo" class = "img-fluid w-50">
                    </div>

                    <a href = "/login" class="btn btn-link mb-4 p-0"
                        style="color: #313131; font-weight: 500; text-decoration: none;"> <i
                            class="fa fa-arrow-left"></i> Back to login</a>

                    <!-- Alert Container -->
                    <div id="alertContainer"></div>

                    <!-- Heading -->
                    <h4 class = "text-dark mb-2">Verify Email</h4>
                    <p class="mb-4" style = "color:#969696;font:inter;">An authentication code has been sent to your
                        email</p>

                    <form id="verifyForm" method="POST" action="javascript:void(0);" data-ajax>
                        @csrf
                        <div class="custom-form-group">
                            <label for="email" class="custom-label">Email Address</label>
                            <input type="email" class="form-control-custom" id="email" name="email"
                                placeholder="your@email.com" aria-label="Email Address" autocomplete="email" readonly>
                            <small class="text-muted d-block mt-1">Verification code sent to this email</small>
                        </div>

                        <div class="custom-form-group">
                            <label for="verificationCode" class="custom-label">Enter code</label>
                            <input type="text" class="form-control-custom" id="verificationCode" name="code"
                                placeholder="000000" maxlength="6" aria-label="Verification Code" inputmode="numeric"
                                required>
                        </div>

                        <p>
                            Did not receive a code?
                            <a href="#" id="resendLink"
                                style="color: #FDAF22; text-decoration: none; cursor: pointer;">Resend</a>
                        </p>

                        <button type="submit" class="btn primaryButton w-100" id="verifyBtn">Verify</button>
                    </form>
                </div>
            </div>

            <!-- Right Column with Image -->
            <div class="col-lg-6 right-col-passwordverify d-none d-lg-block"></div>
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
        UIHelpers.storeButtonText('verifyBtn');

        // Get email from URL or session
        let email = UIHelpers.getUrlParameter('email') || sessionStorage.getItem('registerEmail');

        // Display email on the form
        if (email) {
            document.getElementById('email').value = email;
        } else {
            // If no email found, show error
            UIHelpers.showError('Email not found. Please register again.');
        }

        // Handle verify form submission
        document.getElementById('verifyForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Get email from input field (in case it was updated)
            const currentEmail = document.getElementById('email').value.trim();
            const code = document.getElementById('verificationCode').value.trim();

            if (!currentEmail) {
                UIHelpers.showError('Email not found. Please register again.');
                return;
            }

            if (!code) {
                UIHelpers.showError('Please enter the verification code');
                return;
            }

            if (!UIHelpers.isValidCode(code)) {
                UIHelpers.showError('Verification code must be exactly 6 digits');
                return;
            }

            UIHelpers.setButtonLoading('verifyBtn', true);
            UIHelpers.showLoadingOverlay(true);

            const result = await AuthApiClient.verifyEmailWithCode(currentEmail, code);

            UIHelpers.showLoadingOverlay(false);

            if (result.success) {
                UIHelpers.showSuccess('Email verified successfully! Redirecting to dashboard...');

                // Determine redirect URL based on user role
                let redirectUrl = '/dashboard'; // Default for admin/instructor

                // Get user from result.data.user or result.user
                const user = result.data?.user || result.user;

                if (user && user.role === 'student') {
                  redirectUrl = '/usersdashboard';
                }

                UIHelpers.redirect(redirectUrl, 1500);
            } else {
                UIHelpers.showError(result.message || 'Verification failed');
                UIHelpers.setButtonLoading('verifyBtn', false);
            }
        });

        // Handle resend code
        document.getElementById('resendLink').addEventListener('click', async (e) => {
            e.preventDefault();

            console.log('Resend button clicked');

            // Get email from input field
            const currentEmail = document.getElementById('email').value.trim();

            console.log('Current email:', currentEmail);

            if (!currentEmail) {
                UIHelpers.showError('Email not found. Please register again.');
                return;
            }

            // Disable resend link during request
            const resendLink = document.getElementById('resendLink');
            const originalText = resendLink.textContent;
            resendLink.style.pointerEvents = 'none';
            resendLink.style.opacity = '0.5';
            resendLink.textContent = 'Sending...';

            console.log('Calling resendVerificationCode API with email:', currentEmail);

            const result = await AuthApiClient.resendVerificationCode(currentEmail);

            console.log('API Response:', result);

            // Re-enable resend link
            resendLink.style.pointerEvents = 'auto';
            resendLink.style.opacity = '1';
            resendLink.textContent = originalText;

            if (result.success) {
                UIHelpers.showSuccess('Verification code resent to your email');
                // Clear the code input field
                document.getElementById('verificationCode').value = '';
            } else {
                UIHelpers.showError(result.message || 'Failed to resend code');
            }
        });
    </script>
</body>

</html>
