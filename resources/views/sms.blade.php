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



<div class="container-fluid  py-5 pt-5 pb-5" style="background-color:#CCDBDD;">
  <div class="row align-items-center text-center position-relative">

    <!-- Left illustration -->
    <div class="col-2 d-none d-md-block">
      <img src="images/tree.png" alt="Trees" class="img-fluid">
    </div>

    <!-- Main Content -->
    <div class="col-md-8 mx-auto">
      <h1>
        We’re Building to Serve You Better
      </h1>

      <p class="mt-3 text-muted px-md-5" style = "color:#333;">
        Kokokah combines <strong>School Management</strong>, <strong>Exam Prep</strong>,
        and a <strong>Learning Management System (LMS)</strong>—helping schools automate
        admin tasks, boost student performance, and deliver modern digital learning in one seamless platform.
      </p>

      <!-- Subscribe form -->
      <form class="d-flex justify-content-center mt-4">
        <input type="email" class="form-control form-control-lg rounded-start-pill w-50"
               placeholder="Enter your email">
        <button type="submit" class="btn btn-lg rounded-end-pill px-4 text-white"
                style="background-color:#004d4d;">
          Subscribe
        </button>
      </form>

      <!-- Avatars + text -->
      <div class="d-flex justify-content-center align-items-center gap-2 mt-4">
        <img src="https://i.pravatar.cc/40?img=1" class="rounded-circle border" width="40" height="40" alt="">
        <img src="https://i.pravatar.cc/40?img=2" class="rounded-circle border" width="40" height="40" alt="">
        <img src="https://i.pravatar.cc/40?img=3" class="rounded-circle border" width="40" height="40" alt="">
        <span class="text-muted small">Join 39k other creatives</span>
      </div>
    </div>

    <!-- Right illustration -->
    <div class="col-2 d-none d-md-block">
      <img src="images/sms-person.png" alt="Person" class="img-fluid">
    </div>

  </div>
</div>

</div>
</div>
</div>
</body>
</html>
