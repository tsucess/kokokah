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
                        <img src="images/Kokokah_Logo.png" alt="Kokokah Logo" class = "img-fluid w-75">
                    </div>

                    <a href = "/login" class="btn btn-link mb-4 p-0"
                        style="color: #313131; font-weight: 500; text-decoration: none;"> <i
                            class="fa fa-arrow-left"></i> Back to login</a>

                    <!-- Alert Container -->
                    <div id="alertContainer"></div>

                    <!-- Heading -->
                    <h4 class = "text-dark mb-2">Verify code</h4>
                    <p class="mb-4" style = "color:#969696;font:inter;">An authentication code has been sent to your
                        email</p>

                    <form id="verifyForm" method="POST" action="javascript:void(0);">
                        @csrf
                        <div class="custom-form-group">

                        <label for="verifycode" class="custom-label">Enter code</label>

                        <input type="text" class="form-control-custom" id="verifycode" placeholder="80EAS33">
                        </div>
                        <p>
                            Didn’t receive a code?
                            <a href = "#" id="resendLink" style="color: #FDAF22; text-decoration: none; cursor: pointer;">Resend</a>
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

        // Get email from URL or session (check both registerEmail and resetEmail)
        let email = UIHelpers.getUrlParameter('email') ||
                    sessionStorage.getItem('registerEmail') ||
                    sessionStorage.getItem('resetEmail');

        console.log('Page loaded - Email from URL:', UIHelpers.getUrlParameter('email'));
        console.log('Page loaded - Email from registerEmail:', sessionStorage.getItem('registerEmail'));
        console.log('Page loaded - Email from resetEmail:', sessionStorage.getItem('resetEmail'));
        console.log('Final email value:', email);

        // Validate email on page load
        if (!email) {
            UIHelpers.showError('Email not found. Please register again.');
            console.error('Email not found on page load');
        }

        // Handle verify form submission
        document.getElementById('verifyForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const code = document.getElementById('verifycode').value.trim();

            console.log('Verify form submitted - Email:', email, 'Code:', code);

            if (!email) {
                UIHelpers.showError('Email not found. Please try again.');
                return;
            }

            if (!code) {
                UIHelpers.showError('Please enter the verification code');
                return;
            }

            if (code.length !== 6) {
                UIHelpers.showError('Verification code must be 6 digits');
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

            console.log('Resend button clicked - Email:', email);

            if (!email) {
                UIHelpers.showError('Email not found. Please try again.');
                console.error('Email not found when resending');
                return;
            }

            // Disable resend link during request
            const resendLink = document.getElementById('resendLink');
            const originalText = resendLink.textContent;
            resendLink.style.pointerEvents = 'none';
            resendLink.style.opacity = '0.5';
            resendLink.textContent = 'Sending...';

            console.log('Calling resendVerificationCode API with email:', email);

            const result = await AuthApiClient.resendVerificationCode(email);

            console.log('Resend API Response:', result);

            // Re-enable resend link
            resendLink.style.pointerEvents = 'auto';
            resendLink.style.opacity = '1';
            resendLink.textContent = originalText;

            if (result.success) {
                UIHelpers.showSuccess('Verification code resent to your email');
                // Clear the code input field
                document.getElementById('verifycode').value = '';
            } else {
                UIHelpers.showError(result.message || 'Failed to resend code');
            }
        });
    </script>
</body>

</html>
