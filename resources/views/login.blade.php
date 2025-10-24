<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Kokokah LMS</title>

    <link rel="icon" type="image/x-icon" href="images/Kokokah_Logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Animations CSS -->
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">

    <!-- Mobile Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">

    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    {{-- <link rel="stylesheet" href="./css/main.css"> --}}


    {{-- @vite(['resources/css/style.css'])
    <link rel="stylesheet" href="../../css/style.css" type="text/css" /> --}}

    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #004A53 0%, #2B6870 100%);
        }

        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .login-btn {
            background-color: #004A53;
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #2B6870;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 74, 83, 0.4);
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-control:focus {
            border-color: #004A53;
            box-shadow: 0 0 0 0.2rem rgba(0, 74, 83, 0.25);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .success-message {
            color: #28a745;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .loading-spinner {
            display: none;
        }

        .loading-spinner.show {
            display: inline-block;
        }
    </style>
</head>

<body class="login-container">

    <div class="container-fluid d-flex align-items-center justify-content-center"
        style="min-height: 100vh; padding: 20px;">
        <div class="row w-100 align-items-center">

            <!-- Left Side (Logo + Illustration + Heading) -->
            <div class="col-lg-6 text-center my-auto px-5 mobile-hidden" data-aos="fade-right">
                <!-- Logo -->
                <div class="mb-4">
                    <img src="{{ asset('images/Kokokah_Logo.png') }}" alt="Logo" class="img-fluid"
                        style="max-height: 80px;">
                </div>

                <!-- Illustration -->
                <div class="mb-4">
                    <img src="{{ asset('images/stem.png') }}" alt="STEM Illustration" class="img-fluid"
                        style="max-height: 300px;">
                </div>

                <!-- Text -->
                <h3 class="fw-bold text-white mb-3">Welcome Back!</h3>
                <p class="text-white-50">Access your learning dashboard and continue your educational journey</p>
            </div>

            <!-- Right Side (Form) -->
            <div class="col-lg-6 px-5" data-aos="fade-left">
                <div class="card login-card border-0 p-5">
                    <!-- Logo for mobile -->
                    <div class="text-center mb-4 mobile-visible">
                        <img src="{{ asset('images/Kokokah_Logo.png') }}" alt="Logo" class="img-fluid"
                            style="max-height: 60px;">
                    </div>

                    <h4 class="fw-bold text-center mb-2" style="color: #004A53;">Login to Kokokah</h4>
                    <p class="text-muted text-center mb-4">Sign in to your account to continue</p>

                    <!-- Alert Messages -->
                    <div id="alertContainer"></div>

                    <form id="loginForm">
                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-500">Email Address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email"
                                required>
                            <div class="error-message" id="emailError"></div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-500">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password"
                                    placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="error-message" id="passwordError"></div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="/forgot-password" class="text-decoration-none" style="color: #004A53;">Forgot
                                password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary login-btn w-100 mb-3" id="loginBtn">
                            <span id="btnText">Login</span>
                            <span class="loading-spinner" id="btnSpinner">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="text-center mb-3">
                        <span class="text-muted">Don't have an account?</span>
                    </div>

                    <!-- Register Link -->
                    <a href="/register" class="btn btn-outline-primary w-100">
                        Create Account
                    </a>
                </div>
            </div>

        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    <!-- Main App JS -->
    @vite(['resources/js/app.js'])

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
        });

        // Import API service
        import {
            authAPI
        } from '/resources/js/services/api.js';

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Handle login form submission
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Clear previous errors
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';
            document.getElementById('alertContainer').innerHTML = '';

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            // Validation
            if (!email) {
                document.getElementById('emailError').textContent = 'Email is required';
                return;
            }
            if (!password) {
                document.getElementById('passwordError').textContent = 'Password is required';
                return;
            }

            // Show loading state
            loginBtn.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.classList.add('show');

            try {
                // Call login API
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        email,
                        password
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Login failed');
                }

                // Save token
                localStorage.setItem('auth_token', data.token);

                // Show success message
                document.getElementById('alertContainer').innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> Login successful! Redirecting...
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

                // Redirect to dashboard
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1500);

            } catch (error) {
                // Show error message
                document.getElementById('alertContainer').innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

                // Reset button
                loginBtn.disabled = false;
                btnText.style.display = 'inline';
                btnSpinner.classList.remove('show');
            }
        });
    </script>

</body>

</html>
