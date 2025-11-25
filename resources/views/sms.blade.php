<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KOKOKAH</title>
    <link rel="icon" type="image/x-icon" href="images/kokokah_favicon.png"  />

     <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">

    <!-- Bootstrap file -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- @vite(['resources/css/style.css']) --}}

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">

        <div class="container-fluid " style = "padding-left: 60px; padding-right:60px;">

            <a class="navbar-brand" href="#">
            <img src="{{ asset('images/Kokokah_Logo.png') }}" alt="">
            </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent"  >
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 ">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/" >Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link keep_case" href="/about">About Us</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Products</a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/lms">LMS</a></li>
                            <li><a class="dropdown-item" href="/sms">SMS</a></li>
                            {{-- <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Page 3</a></li> --}}
                        </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/koodies">Koodies</a>
                </li>

                {{-- <li class="nav-item">
                    <a class="nav-link" href="/stem">Stem</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/market">Market Place</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/pricing">Pricing</a>
                </li> --}}

                <li class="nav-item">
                   <a class="nav-link" href="/contact">Contact Us</a>
                </li>
          </ul>

      <form class="d-flex gap-4">
        <button class="btn primaryButton" type="button">Explore Kokokah Project</button>
        <button class="btn secondaryButton" type="button">Get a Demo</button>
      </form>

    </div>

  </div>
</nav>



<div class="container-fluid   pt-5" style="background-color:#CCDBDD;">
  <div class="row align-items-center text-center position-relative">



    <!-- Main Content -->
    <div class="col-md-12 text-center mx-auto">
      <h1 class = "heroheading">
        We’re Building to Serve You Better
      </h1>

      <p class="px-md-5 text-center heroparagraph">
        Kokokah combines <strong>School Management</strong>, <strong>Exam Prep</strong>,
        and a <strong>Learning Management System (LMS)</strong>—helping <br>
        schools automate admin tasks, boost student performance, and deliver modern digital learning in one seamless platform.
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



  </div>



<div class = "d-flex justify-content-between">
<!-- Left illustration -->
    <div class="col-2 d-none d-md-block" style = "margin-top:100px;">
      <img src="images/tree.png" alt="Trees" class="img-fluid">
    </div>

<!-- Right illustration -->
    <div class="col-2 d-none d-md-block">
      <img src="images/sms-person.png" alt="Person" class="img-fluid">
    </div>

</div>


</div>

  <!-- Scripts needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"> </script>
</body>
</html>
