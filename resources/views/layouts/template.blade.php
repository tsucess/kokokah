<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>Kokokah</title>

    <link rel="icon" type="image/x-icon" href="images/kokokah_favicon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">

    <!-- Bootstrap file -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    @vite(['resources/css/style.css', 'resources/css/responsiveness.css'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>

    <div class = "container-fluid top_header" style="">
        <!-- Background Image -->
        {{-- <img src="images/kokokah_header.png" class="img-fluid" alt="Banner Image" style="width:100%; height:150px;"> --}}
        <h1>Transforming African Education—One School</h1>
    </div>

    <!-- Navigation Bar - Fixed/Sticky with Overlay -->
    <nav class="navbar navbar-expand-lg sticky-top px-md-3 px-lg-2 px-xl-5" aria-label="Fifth navbar example">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/Kokokah_Logo.png') }}" alt="Kokokah Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample05"
                aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarsExample05">
                <ul class="navbar-nav mb-2 mb-lg-0 mx-auto gap-3 ">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Products</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/lms">LMS</a></li>
                            <li><a class="dropdown-item" href="/sms">SMS</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/koodies">Koodies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact Us</a>
                    </li>
                </ul>

                <div class="d-flex flex-column flex-lg-row gap-3 px-0">
                    <button class="btn-nav-primary">Explore Kokokah</button>
                    <button class="btn-nav-secondary">Get a Demo</button>
                </div>
            </div>
        </div>
    </nav>


    <div class = "mb-2">
        @yield('content')
    </div>



    <!-- Newsletter Section - Yellow Background -->
    <div class="container-fluid py-4 py-md-5" style="background-color: #FDAF22;">
        <div class="container">
            <div class="row align-items-center gap-3 gap-md-0">
                <div class="col-12 col-md-6 col-lg-6 mb-4 mb-md-0">
                    <h2 class="fw-bold mb-3"
                        style="font-size: clamp(22px, 4.5vw, 40px); color: #000000; font-family: 'Fredoka', sans-serif;">
                        Don't Miss Out on the Future of Learning!
                    </h2>
                    <p style="font-size: 16px; color: #1C1D1D; line-height: 1.6;">
                        Be the first to get school and study hacks, career tips, and Kokokah updates straight to your
                        inbox. Join thousands of students, parents, and educators across Africa who are already leveling
                        up with us.
                    </p>
                </div>

                <div class="col-12 col-md-6 col-lg-6">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Enter your email"
                            aria-label="Enter your email"
                            style="border-radius: 8px 0 0 8px; padding: 10px 16px; border: none; font-size: 14px;">
                        <button class="btn fw-bold" type="button"
                            style="background-color: #004A53; color: white; border: none; border-radius: 0 8px 8px 0; padding: 10px 20px; font-size: 14px;">Subscribe
                            Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section - Teal Background -->
    <footer style="background-color: #004A53; color: white; padding: 40px 20px 15px; padding-md: 60px 40px 20px;">
        <div class="container">
            <div class="row mb-4 mb-md-5">
                <div class="col-12 col-md-5 col-lg-5 mb-4 mb-md-0">
                    <img src="images/Contact.png" class="img-fluid mb-3" style="max-width: 150px;">
                    <p style="font-size: 14px; line-height: 1.6;">
                        Kokokah combines School Management, Exam Prep, and a Learning Management System (LMS)—helping
                        schools automate admin tasks, boost student performance, and deliver modern digital learning in
                        one seamless platform.
                    </p>
                </div>

                <div class="col-6 col-md-2 col-lg-2">
                    <h6 class="fw-bold mb-3">Short Links</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#"
                                style="color: white; text-decoration: none;">Features</a></li>
                        <li class="nav-item mb-2"><a href="#" style="color: white; text-decoration: none;">How
                                it works</a></li>
                        <li class="nav-item mb-2"><a href="#"
                                style="color: white; text-decoration: none;">Security</a></li>
                        <li class="nav-item mb-2"><a href="#"
                                style="color: white; text-decoration: none;">Testimonial</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2 col-lg-2">
                    <h6 class="fw-bold mb-3">Other Pages</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#"
                                style="color: white; text-decoration: none;">Privacy Policy</a></li>
                        <li class="nav-item mb-2"><a href="#"
                                style="color: white; text-decoration: none;">Terms & Condition</a></li>
                        <li class="nav-item mb-2"><a href="#"
                                style="color: white; text-decoration: none;">404</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-3 col-lg-3">
                    <h6 class="fw-bold mb-3">Connect With Us</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="#" class="btn btn-light rounded-circle"
                            style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0; min-width: 36px;">
                            <i class="fab fa-facebook-f" style="color: #004A53; font-size: 14px;"></i>
                        </a>
                        <a href="#" class="btn btn-light rounded-circle"
                            style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0; min-width: 36px;">
                            <i class="fab fa-twitter" style="color: #004A53; font-size: 14px;"></i>
                        </a>
                        <a href="#" class="btn btn-light rounded-circle"
                            style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0; min-width: 36px;">
                            <i class="fab fa-instagram" style="color: #004A53; font-size: 14px;"></i>
                        </a>
                        <a href="#" class="btn btn-light rounded-circle"
                            style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0; min-width: 36px;">
                            <i class="fab fa-linkedin-in" style="color: #004A53; font-size: 14px;"></i>
                        </a>
                        <a href="#" class="btn btn-light rounded-circle"
                            style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0; min-width: 36px;">
                            <i class="fab fa-youtube" style="color: #004A53; font-size: 14px;"></i>
                        </a>
                    </div>
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.2);">

            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
                    <p class="mb-0" style="font-size: 14px;">Copyright &copy; 2025 All rights reserved</p>
                    <div>
                        <a href="#"
                            style="color: white; text-decoration: none; font-size: 14px; margin-right: 16px;">Terms and
                            Conditions</a>
                        <a href="#" style="color: white; text-decoration: none; font-size: 14px;">Privacy
                            Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Ending footer section -->

    <!-- Scripts needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
