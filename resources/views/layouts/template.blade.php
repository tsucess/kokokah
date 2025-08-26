<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KOKOKAH</title>

    <link rel="icon" type="image/x-icon" href="images/Kokokah_Logo.png" />


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



    @vite(['resources/css/style.css'])
    <link rel = "stylesheet" href = "../../css/style.css" type="text/css" />

    <!-- Custom Styles -->
    <style>
        .banner-text {
            font-family: 'Poppins', sans-serif;
            color: #045a5a;
            /* Adjust color */
            font-size: clamp(1.2rem, 3vw, 2.5rem);
            /* Responsive font size */
            line-height: 1.2;
        }


        .container__footer {
            /* border-top: 1px solid var(--color-ash); */
            background: #004A53;
            margin-top: 2rem;
            text-align: center;
        }

        .footer__container .row {
            font-size: 0.8rem;
            padding: 0.5rem 0;
            justify-content: space-between;
        }

        .footer__container a {
            text-decoration: none;
            color: var(--color-bg-ash);
        }

        .footer__container a:hover {
            text-decoration: none;
            color: var(--color-primary-blue);
        }

        .navbar-nav .nav-item {
            background: ash;
        }

        body {
            margin: 0;
            padding: 0;
        }

.bullet-point
 {
  width: 1rem;
  height: 1rem;
  background-color: #006064; /* A dark teal color */
  flex-shrink: 0;
  margin-top: 0.25rem; /* Adjust for vertical alignment with the text */
}

.custom-shadow {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
    </style>
</head>

<body>

    {{-- <div class = "container-fluid"> --}}
        <!-- Bootstrap 5 Responsive Banner -->
        {{-- <div class="position-relative text-center"> --}}
            <div class = "container-fluid">
            <div class = "row">
            <!-- Background Image -->
            <img src="images/kokokah_header.png" class="img-fluid w-100" alt="Banner Image"
                style="height: auto; object-fit: cover;">
        </div>
            </div>

        {{-- <div class = "row bg-success"> --}}
        <header class="container__header grid grid-cols-2 items-center  shadow py-10">

            <nav class="container navbar navbar-expand-lg ">

                <div class="d-flex align-items-center">
                <a class="navbar-brand" href="/"><img src="{{ asset('images/Kokokah_Logo.png') }}"
                        alt=""></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon fa fa-bars"></span></button>
                </div>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item"><x-nav-link href="/"
                                active="{{ request()->is('/') }}">Home</x-nav-link></li>

                        <li class="nav-item"><x-nav-link href="/about" active="{{ request()->is('about') }}">About
                                us</x-nav-link></li>

                        <li class="nav-item"><x-nav-link href="/products"
                                active="{{ request()->is('products') }}">Products</x-nav-link></li>


                        <li class="nav-item"><x-nav-link href="/koodies"
                                active="{{ request()->is('koodies') }}">Koodies</x-nav-link></li>
                        </li>

                        <li class="nav-item"><x-nav-link href="/contact" active="{{ request()->is('contact') }}">Contact
                                Us</x-nav-link></li>
                        </li>

                    </ul>
                    <div class="nav-btn">

                            <a href="{{ url('explore') }}" class="btn navButton">Explore Kokokah Products</a>
                            <a href="{{ url('demo') }}" class="btn btn-outline-success text-success demo">Get a Demo</a>

                    </div>

                </div>
            </nav>
        </header>
    {{-- </div> --}}

        <div>
            @yield('content')
        </div>




        {{-- FOOTER SECTION --}}
        <div class="container-fluid container__footer">

        <section>
        <div class = "row subscribe">

    <div class = "col-12 col-md-6 col-lg-6 mt-5">
     <h3 class = "mb-2">
    Don’t Miss Out on the Future of Learning!
     </h3>

     <p>
        Be the first to get school and study hacks, career tips, and Kokokah
        updates straight to your inbox. Join thousands of students, parents,
        and educators across Africa who are already leveling up with us.
     </p>
    </div>


    <div class = "col-12 col-md-6 col-lg-6 my-auto">

<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Enter your mail" aria-label="Enter your mail" aria-describedby="button-addon2">
  <button class="btn btn-outline-secondary" type="button" id="button-addon2" style = "background: #004A53;">Subscribe Now</button>
</div>

    </div>
</div>
        </section>

            <footer>
                <div class="container row mx-auto">



                    <div class="col-12 col-md-5 col-lg-5 p-4 mb-3 text-white address">
                        <img src="images/Contact.png" class="img-fluid d-block"><br>
                        Kokokah combines School Management, Exam Prep, and a
                        Learning Management System (LMS)—helping schools
                        automate admin tasks, boost student performance, and
                        deliver modern digital learning in one seamless platform.


                        {{-- <a href="https://www.google.com/maps/search/8+Adebayo+Muokolu+Street,+Anthony+Village,+Lagos,+Nigeria./@6.558127,3.3687749,17z/data=!3m1!4b1?entry=ttu&g_ep=EgoyMDI0MTIxMC4wIKXMDSoASAFQAw%3D%3D"
                            class="text-decoration-none" target="_blank"><span>8 Adebayo Muokolu Street, Anthony
                                Village, Lagos, Nigeria.</span></a> --}}
                    </div>
                    <div class="col-12 col-md-3 col-lg-2 mb-3 p-0 text-white">
                        <h5 class="title mt-4">Short Links</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2"><a href="/products" class="nav-link p-0 text-white">Products</a>
                            </li>

                            <li class="nav-item mb-2"><a href="/howitworks" class="nav-link p-0 text-white">How it
                                    works</a>
                            </li>
                            <li class="nav-item mb-2"><a href="/security" class="nav-link p-0 text-white">Security</a>
                            </li>
                            <li class="nav-item mb-2"><a href="/testimonial"
                                    class="nav-link p-0 text-white">Testimonial</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-2 col-lg-2 mb-3 p-0 text-white">
                        <h5 class="title mt-4">Other pages</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2"><a href="/privacy" class="nav-link p-0 text-white">Privacy
                                    Policy</a></li>
                            <li class="nav-item mb-2"><a href="/terms" class="nav-link p-0 text-white">Terms &
                                    Conditions</a></li>
                            <li class="nav-item mb-2"><a href="/404" class="nav-link p-0 text-white">404</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-2  col-lg-2 mb-3 p-0 text-white">
                        <h5 class="title mt-4">Connect with us</h5>
                        <ul class="list-unstyled d-flex m-0">
                            <li class="ms-4">
                                <a class="text-decoration-none text-white" href="#"><i
                                        class="fa-brands fa-twitter"></i></a>
                            </li>
                            <li class="ms-4">
                                <a class="text-decoration-none text-white" href="#"><i
                                        class="fa-brands fa-facebook"></i></a>
                            </li>
                            <li class="ms-4">
                                <a class="text-decoration-none text-white" href="#"><i
                                        class="fa-brands fa-instagram"></i></a>
                            </li>
                            <li class="ms-4">
                                <a class="text-decoration-none text-white" href="#"><i
                                        class="fa-brands fa-linkedin-in"></i></a>
                            </li>
                        </ul>

                    </div>
                </div>

                <div
                    class="container d-flex flex-column flex-sm-row mb-4 justify-content-between text-white border-top copy__right">
                    <p>Copyright &copy; 2024 Passnownow </p>
                    <p><a href="#" class = "me-2 text-white">Terms and Conditions </a>
                        <a href="#" class = "text-white">Privacy Policy</a>
                    </p>

                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
