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

                    <a href = "/login" class="btn btn-link mb-4 p-0"
                        style="color: #313131; font-weight: 500; text-decoration: none;"> <i
                            class="fa fa-arrow-left"></i> Back to login</a>

                    <!-- Alert Container -->
                    <div id="alertContainer"></div>

                    <!-- Heading -->
                    <h4 class = "text-dark mb-2">Verify Email</h4>
                    <p class="mb-4" style = "color:#969696;font:inter;">An authentication code has been sent to your
                        email</p>

                    <form id="verifyForm" method="POST">
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

    <script type="module">
        import AuthApiClient from '{{ asset('js/api/authClient.js') }}';
        import UIHelpers from '{{ asset('js/utils/uiHelpers.js') }}';

        // Store original button text
        UIHelpers.storeButtonText('verifyBtn');

        // Get email from URL or session
        const email = UIHelpers.getUrlParameter('email') || sessionStorage.getItem('registerEmail');

        // Display email on the form
        if (email) {
            document.getElementById('email').value = email;
        }

        // Handle verify form submission
        document.getElementById('verifyForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const code = document.getElementById('verificationCode').value.trim();

            if (!email) {
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

            const result = await AuthApiClient.verifyEmailWithCode(email, code);

            UIHelpers.showLoadingOverlay(false);

            if (result.success) {
                UIHelpers.showSuccess('Email verified successfully! Redirecting to dashboard...');
                UIHelpers.redirect('/dashboard', 1500);
            } else {
                UIHelpers.showError(result.message || 'Verification failed');
                UIHelpers.setButtonLoading('verifyBtn', false);
            }
        });

        // Handle resend code
        document.getElementById('resendLink').addEventListener('click', async (e) => {
            e.preventDefault();

            if (!email) {
                UIHelpers.showError('Email not found. Please register again.');
                return;
            }

            const result = await AuthApiClient.resendVerificationCode(email);

            if (result.success) {
                UIHelpers.showSuccess('Verification code resent to your email');
            } else {
                UIHelpers.showError(result.message || 'Failed to resend code');
            }
        });
    </script>
</body>

</html>
