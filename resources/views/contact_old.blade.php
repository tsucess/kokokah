@extends('layouts.template')

@section('content')
<div class="container-fluid py-5" style="background-color:#FDAF22;">
    <div class="row align-items-center">

        <div class="col-md-9 text-dark p-5">
            <h6 class="text-dark">Get Started</h6>
            <h1 class = "text-dark">Get in touch with us. <br>We're here to assist you.</h1>
        </div>

        <div class="col-md-3 d-flex flex-column align-items-center">
            <a href="#" class="btn btn-outline-dark rounded-circle my-2"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="btn btn-outline-dark rounded-circle my-2"><i class="fab fa-instagram"></i></a>
            <a href="#" class="btn btn-outline-dark rounded-circle my-2"><i class="fab fa-twitter"></i></a>
        </div>

    </div>

</div>


<div class="container my-5">
    <form>
        <div class="row mb-3">
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="yourName" class="form-label"><b>Your Name</b></label>
                <input type="text" class="form-control border-top-0 border-end-0 border-start-0 rounded-0 p-0" id="yourName">
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="emailAddress" class="form-label"><b>Email Address</b></label>
                <input type="email" class="form-control border-top-0 border-end-0 border-start-0 rounded-0 p-0" id="emailAddress">
            </div>
            <div class="col-md-4">
                <label for="phoneNumber" class="form-label"><b>Phone Number (optional)</b></label>
                <input type="tel" class="form-control border-top-0 border-end-0 border-start-0 rounded-0 p-0" id="phoneNumber">
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <label for="message" class="form-label"><b>Message</b></label>
                <textarea class="form-control border-top-0 border-end-0 border-start-0 rounded-0 p-0" id="message" rows="3"></textarea>
            </div>
        </div>
        <button type="submit" class="btn text-white px-5 py-3 navButton">Leave us a Message</button>
    </form>
</div>



<div class="container my-5">
    <div class="row mx-auto align-items-start">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <p>Contact Info</p>
            <h4 class="text-dark">
                We are always happy <br>
                to assist you
            </h4>
        </div>

        <div class="col-lg-7">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h6 class="fw-bold">Email Address</h6>
                    <div class="divider bg-dark mb-2" style="height: 3px; width: 40px;"></div>
                    <p class="mb-0">help@info.com</p>
                    <small class="text-muted">Assistance hours: <br>Monday - Friday 6 am to 8 pm</small>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">Number</h6>
                    <div class="divider bg-dark mb-2" style="height: 3px; width: 40px;"></div>
                    <p class="mb-0">(81) 292-9049</p>
                    <small class="text-muted">Assistance hours: <br>Monday - Friday 6 am to 8 pm</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
