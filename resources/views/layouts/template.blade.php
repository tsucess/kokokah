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

    <!-- Animate css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Bootstrap file -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsiveness.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">

    {{-- @vite(['resources/css/style.css', 'resources/css/responsiveness.css']) --}}

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- swiper.js --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
</head>

<body>

    <div class="container-fluid top_header">
        <!-- Background Image -->
        {{-- <img src="images/kokokah_header.png" class="img-fluid" alt="Banner Image"> --}}
        <h1 class="text-center">Transforming classrooms, building futures, empowering tomorrow’s leaders</h1>
    </div>

    <!-- Navigation Bar - Fixed/Sticky with Overlay -->
    <nav class="navbar navbar-expand-lg sticky-top px-md-3 px-lg-2 px-xl-5" aria-label="Fifth navbar example">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/Kokokah_Logo.png') }}" alt="Kokokah Logo"
                    class="animate__animated animate__pulse hero-img w-100">
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
                        <ul class="dropdown-menu dropdown-fit">
                            <li><a class="dropdown-item" href="/lms">Learning Management System(LMS)</a></li>
                            <li><a class="dropdown-item" href="/sms">School Management System(SMS)</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kokoplay">Kokoplay</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact Us</a>
                    </li>
                </ul>

                {{-- <div class="d-flex flex-column flex-lg-row gap-3 px-0">
                    <button class="btn-nav-primary">Explore Kokokah</button>
                    <button class="btn-nav-secondary">Get a Demo</button>
                </div> --}}
            </div>
        </div>
    </nav>


    <div class = "mb-0">
        @yield('content')
    </div>



    <!-- Newsletter Section - Yellow Background -->
    <div class="container-fluid py-4 py-md-5 newsletter-section">
        <div class="container">
            <div class="row align-items-center gap-3 gap-md-0">
                <div class="col-12 col-md-6 col-lg-6 mb-4 mb-md-0">
                    <h2 class="newsletter-title typewriter">
                        Don't Miss Out on the Future of Learning!
                    </h2>
                    <p class="newsletter-description">
                        Be the first to get school and study hacks, career tips, and Kokokah updates straight to your
                        inbox. Join thousands of students, parents, and educators across Africa who are already leveling
                        up with us.
                    </p>
                </div>

                <div class="col-12 col-md-6 col-lg-6">
                    <div class="input-group">
                        <input type="email" class="form-control newsletter-input p-3" placeholder="Enter your email"
                            aria-label="Enter your email">
                        <button class="btn fw-bold newsletter-button" type="button">Subscribe Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section - Teal Background -->
    <footer class="footer-section">
        <div class="container">
            <div class="row mb-4 mb-md-5">
                <div class="col-12 col-md-5 col-lg-5 mb-4 mb-md-0">
                    <img src="images/Contact.png" class="img-fluid mb-3 footer-logo">
                    <p class="footer-description">
                        Kokokah combines School Management, Exam Prep, and a Learning Management System (LMS)—helping
                        schools automate admin tasks, boost student performance, and deliver modern digital learning in
                        one seamless platform.
                    </p>
                </div>

                <div class="col-6 col-md-2 col-lg-2">
                    <h6 class="footer-heading">Short Links</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="/" class="footer-link">Home</a></li>
                        <li class="nav-item mb-2"><a href="/about" class="footer-link">About Us</a></li>
                        <li class="nav-item mb-2"><a href="/lms" class="footer-link">Lms</a></li>
                        <li class="nav-item mb-2"><a href="/sms" class="footer-link">Sms</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2 col-lg-2">
                    <h6 class="footer-heading">Other Pages</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="/kokoplay" class="footer-link">Kokoplay</a></li>
                        <li class="nav-item mb-2"><a href="/contact" class="footer-link">Contact</a></li>
                        {{-- <li class="nav-item mb-2"><a href="#" class="footer-link">404</a></li> --}}
                    </ul>
                </div>

                <div class="col-12 col-md-3 col-lg-3 mt-4">
                    <h6 class="footer-heading">Connect With Us</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-facebook-f footer-social-icon"></i>
                        </a>
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-twitter footer-social-icon"></i>
                        </a>
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-instagram footer-social-icon"></i>
                        </a>
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-linkedin-in footer-social-icon"></i>
                        </a>
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-youtube footer-social-icon"></i>
                        </a>
                    </div>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
                    <p class="mb-0 footer-copyright">Copyright &copy; 2025 All rights reserved</p>
                    <div>
                        <a href="#" class="footer-bottom-link">Terms and Conditions</a>
                        <a href="#" class="footer-bottom-link">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Ending footer section -->

    <!-- Scripts needed -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        gsap.utils.toArray(".typewriter").forEach((el) => {
            const text = el.textContent;
            el.textContent = ""; // clear text to start typing

            const letters = text.split("");

            letters.forEach((letter) => {
                const span = document.createElement("span");
                span.textContent = letter;
                span.style.opacity = 0; // hide initially
                el.appendChild(span);
            });

            const spans = el.querySelectorAll("span");

            gsap.fromTo(spans, {
                opacity: 0
            }, {
                opacity: 1,
                duration: 0.05, // speed of typing
                stagger: 0.05, // delay between letters
                ease: "none",
                scrollTrigger: {
                    trigger: el,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse",
                    // markers: true   // for debugging
                }
            });
        });
    </script>

    <!-- Kokokah Logo Loader -->
    <script src="{{ asset('js/utils/kokokahLoader.js') }}"></script>
</body>

</html>
