<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Kokokah</title>

    <link rel="icon" type="image/x-icon" href="images/kokokah_favicon.png"  />

     <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">

    <!-- Bootstrap file -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    @vite(['resources/css/style.css'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>

<div class = "container-fluid">
    <!-- Background Image -->
    <img src="images/kokokah_header.png" class="img-fluid" alt="Banner Image" >

</div>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">

        <div class="container">

            <a class="navbar-brand" href="#">
            <img src="{{ asset('images/Kokokah_Logo.png') }}" alt="">
            </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent"  >
            <ul class="navbar-nav me-auto mb-2 ms-3 mb-lg-0 ">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/" >Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link keep_case" href="/about">About Us</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Products</a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Page 1</a></li>
                            <li><a class="dropdown-item" href="#">Page 2</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Page 3</a></li>
                        </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Koodies</a>
                </li>

                <li class="nav-item">
                   <a class="nav-link" href="#">Contact Us</a>
                </li>
          </ul>

      <form class="d-flex gap-4">
        <button class="btn primaryButton" type="button">Explore Kokokah Project</button>
        <button class="btn secondaryButton" type="button">Get a Demo</button>
      </form>

    </div>

  </div>
</nav>


        <div class = "mb-2">
            @yield('content')
        </div>



<!-- testimonial section -->
<div class="container-fluid subscribe">


<div class = "row">
    <div class = "col-12 col-md-6 col-lg-6">
     <h4  style = "color: #004a53;">
    Don’t Miss Out on the Future of Learning!
     </h4>

     <p style = "color:#4d8087;">
        Be the first to get school and study hacks, career tips, and Kokokah
        updates straight to your inbox. Join thousands of students, parents,
        and educators across Africa who are already leveling up with us.
     </p>
    </div>


<div class = "col-12 col-md-6 col-lg-6 my-auto">

<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Enter your mail" aria-label="Enter your mail" aria-describedby="button-addon2" style = "border-start-start-radius: 10px; border-end-start-radius:10px;">
  <button class="btn primaryButton" type="button" id="button-addon2" style = "background: #004A53;">Subscribe Now</button>
</div>

</div>
</div>


</div>
<!-- ending testimonial section -->

<!-- footer section -->
<footer>
<div class="container-fluid">
    <div class =  "row pt-5 mx-auto">
        <div class = "col-12 col-md-5 col-lg-5">
              <img src="images/Contact.png" class="img-fluid">
              <p class = "pt-2">
                        Kokokah combines School Management, Exam Prep, and a
                        Learning Management System (LMS)—helping schools
                        automate admin tasks, boost student performance, and
                        deliver modern digital learning in one seamless platform.
              </p>
        </div>


        <div class = "col-6 col-md-2 col-lg-2 ">
            <p><b>Short Links</b></p>
            <ul class="nav flex-column">
                            <li class="nav-item mb-2"><a href="#" style = "color:white;">Features</a>
                            </li>

                            <li class="nav-item mb-2"><a href="#" style = "color:white;">How it works</a>
                            </li>
                            <li class="nav-item mb-2"><a href="#" style = "color:white;">Security</a>
                            </li>
                            <li class="nav-item mb-2"><a href="#" style = "color:white;">Testimonial</a></li>
                        </ul>
        </div>




        <div class = "col-6 col-md-2 col-lg-2">
            <p><b>Other Pages</b></p>
            <ul class="nav flex-column ">
                            <li class="nav-item mb-2"><a href="#" style = "color:white;">Privacy Policy</a>
                            </li>

                            <li class="nav-item mb-2"><a href="#" style = "color:white;">Terms & Condition</a>
                            </li>
                            <li class="nav-item mb-2"><a href="#" style = "color:white;">404</a>
                            </li>
                        </ul>
        </div>



        <div class = "col-12 col-md-3 col-lg-3">
            <p><b>Connect With Us</b></p>
            <span class="fa-stack">
             <i class="fas fa-circle fa-stack-2x"></i>
            <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
            </span>

            <span class="fa-stack ">
  <i class="fas fa-circle fa-stack-2x"></i>
  <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
</span>

<span class="fa-stack ">
  <i class="fas fa-circle fa-stack-2x"></i>
  <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
</span>
        </div>


    </div>


    <hr class = "w-75 text-white">

    <div class = "row">
        <div class="d-flex justify-content-between">
            <div>
                    <p>Copyright &copy; 2025 All rights reserved </p>
            </div>

            <div>
            <p>
                <a href="#" class = "me-2 text-white">Terms and Conditions </a> |
                <a href="#" class = "text-white">Privacy Policy</a>
            </p>
            </div>

                </div>
    </div>
</div>
            </footer>
    <!-- Ending footer section -->

    <!-- Scripts needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"> </script>
</body>

</html>
