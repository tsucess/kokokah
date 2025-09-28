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
                    <a class="nav-link active" aria-current="page" href="#" >Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link keep_case" href="#">About Us</a>
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


        <div>
            @yield('content')
        </div>




        {{-- FOOTER SECTION --}}


        <!-- ENDING FOOTER SECTION -->

    <!-- Scripts needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"> </script>
</body>

</html>
