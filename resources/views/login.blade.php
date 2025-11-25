<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

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


    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- @vite(['resources/css/style.css']) --}}
    {{-- <link rel = "stylesheet" href = "../../css/style.css" type="text/css" /> --}}

</head>

<body>

    <div class="container-fluid min-vh-100 d-flex align-items-center">
        <div class="row w-100">

            <!-- Left Side (Logo + Illustration + Heading) -->
            <div class="col-lg-6 text-center my-auto px-5">
                <!-- Logo -->
                <div class="mb-4">
                    <img src="{{ asset('images/Kokokah_Logo.png') }}" alt="Logo" class="img-fluid"
                        style="max-height: 60px;">
                </div>

                <!-- Illustration -->
                <div class="mb-4">
                    <img src="{{ asset('images/stem.png') }}" alt="STEM Illustration" class="img-fluid">
                </div>

                <!-- Text -->
                <h4 class="fw-bold text-teal">Register to enjoy learning</h4>
            </div>

            <!-- Right Side (Form) -->
            <div class="col-lg-6 px-5">
                <div class="card border-0 shadow-sm p-4">
                    <h4 class="fw-bold text-teal">
                        Register for our Science Technology Engineering and Mathematics Masterclass
                    </h4>
                    <p class="text-muted">Get Started today!</p>

                    <form>
                        <!-- Full Name -->
                        <div class="mb-3">
                            <label class="form-label">Enter Full Name</label>
                            <input type="text" class="form-control" placeholder="John Doe">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Enter Email Address</label>
                            <input type="email" class="form-control" placeholder="example@email.com">
                        </div>

                        <!-- DOB -->
                        <div class="mb-3">
                            <label class="form-label">Enter Date of Birth</label>
                            <input type="date" class="form-control">
                        </div>

                        <!-- Gender -->
                        <div class="mb-3">
                            <label class="form-label">Gender</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="male">
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="female">
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                        </div>

                        <!-- STEM Course -->
                        <div class="mb-3">
                            <label class="form-label">Select STEM Course</label>
                            <select class="form-select">
                                <option>Robotics</option>
                                <option>Engineering</option>
                                <option>Mathematics</option>
                                <option>Science</option>
                                <option>Technology</option>
                            </select>
                        </div>

                        <!-- Future Career -->
                        <div class="mb-3">
                            <label class="form-label">Future Career Interest</label>
                            <input type="text" class="form-control" placeholder="e.g. Software Engineer">
                        </div>

                        <!-- State -->
                        <div class="mb-3">
                            <label class="form-label">Enter State of Residence</label>
                            <input type="text" class="form-control">
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label">Enter Contact Address</label>
                            <textarea class="form-control" rows="2"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn w-100" style="background-color:#004d4d; color:white;">
                            Next
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>


</body>

</html>
