@extends('layouts.template')

@section('content')
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
                    schools automate admin tasks, boost student performance, and deliver modern digital learning in one
                    seamless platform.
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
                    <img src="https://i.pravatar.cc/40?img=1" class="rounded-circle border" width="40" height="40"
                        alt="">
                    <img src="https://i.pravatar.cc/40?img=2" class="rounded-circle border" width="40" height="40"
                        alt="">
                    <img src="https://i.pravatar.cc/40?img=3" class="rounded-circle border" width="40" height="40"
                        alt="">
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
@endsection
