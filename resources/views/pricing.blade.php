@extends('layouts.template')

@section('content')
<div class = "container-fluid p-5 banner">
<div class = "row mt-4">

<div class = "col-12 col-md-6 col-lg-6 my-auto">
<h1>
    Transform How you Study,
    Teach, Track Learning,
    and Succeed!
</h1>
<p class = "mt-3">
    An all-in-one affordable, mobile LMS for African students, schools, and educators
</p>

<div class = "d-flex flex-column flex-sm-row gap-2">
    <button class = "btn text-white ps-4 pe-5 " style = "background:#004A53;">Create an account</button>
</div>




</div>


<div class = "col-12 col-md-6 col-lg-6">
<img src = "images/pricing.png" class = "img-fluid">
</div>

</div>

</div>


<div class = "row justify-content-center text-center">
    <h2>Subscription</h2>
    <p class = "font-family: inter, sans-serif; text-align: center; color: #6c757d; max-width: 600px; margin: 0 auto;" >
        Choose a plan that’s right for your needs.
        Enjoy quality educational needs at a price you can afford.
    </p>


    <div class="d-flex align-items-center justify-content-center gap-3 my-4">

  <!-- Monthly label -->
  <span class="fw-bold">Monthly</span>

  <!-- Toggle switch -->
  <div class="form-check form-switch m-0">
    <input class="form-check-input" type="checkbox" id="billingSwitch">
  </div>

  <!-- Yearly label -->
  <span class="fw-bold">Yearly</span>

  <!-- Discount badge -->
  <span class="badge rounded-pill border border-success text-success px-3 py-2">
    35% OFF
  </span>
</div>

</div>


<div class="container py-5">
  <div class="row justify-content-center">

    <!-- Free Plan -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm h-100 pt-1">
        <div class="card-body text-center pt-5">
          <h5 class="card-title fw-bold">Free</h5>
          <h3 class="fw-bold">₦0
          </h3>
          <p>user / mo</p>
          <hr style="width: 220px; padding: auto;">
          <p class="text-muted">100 User Active</p>
          <hr style="width: 220px; padding: auto;">
          <a href="#" class="btn  w-100 navButton">Select Plan</a>
          <hr>
          <ul class="list-unstyled text-start">
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor"> Free Custom Domain*</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp; <span class = "pricingcolor">Unlimited Bandwidth</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp; <span class = "pricingcolor">Contributors</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp; <span class = "pricingcolor">Basic Website Metrics</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp; <span class = "pricingcolor">SEO/Schema Extension</span></li>
            <li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger"></i>&nbsp; <span class = "pricingcolor">Customer Support</span></li>
            <li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger"></i>&nbsp; <span class = "pricingcolor">Project Roles</span></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Basic Plan -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm h-100 pt-1">
        <div class="card-body text-center pt-5">

          <h5 class="card-title fw-bold">Basic</h5>
          <h3 class="fw-bold">₦1500</h3>
          <p> user / mo</p>
           <hr style="width: 220px; padding: auto;">
          <p class="text-muted">500 User Active</p>
          <hr style="width: 220px; padding: auto;">
          <a href="#" class="btn w-100 navButton">Select Plan</a>
          <hr>
          <ul class="list-unstyled text-start">
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Free Custom Domain*</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Unlimited Bandwidth</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Contributors</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Basic Website Metrics</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">SEO/Schema Extension</span></li>
            <li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger"></i>&nbsp; <span class = "pricingcolor">Customer Support</span></li>
            <li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger"></i>&nbsp; <span class = "pricingcolor">Project Roles</span></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Standard Plan (Highlighted) -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm border border-success h-100">
        <div class="card-header bg-success text-white text-center">
          Popular Plan
        </div>
        <div class="card-body text-center">

          <h5 class="card-title fw-bold">Standard</h5>
          <h3 class="fw-bold">₦1500 </h3>
          <p>user / mo</p>
          <hr style="width: 220px; padding: auto;">
          <p class="text-muted">1000 User Active</p>
          <hr style="width: 220px; padding: auto;">
          <a href="#" class="btn w-100 navButton">Select Plan</a>
          <hr>
          <ul class="list-unstyled text-start">
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Free Custom Domain*</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Unlimited Bandwidth<</span>/li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Contributors</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Basic Website Metrics</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">SEO/Schema Extension</span></li>
            <li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger"></i>&nbsp; <span class = "pricingcolor">Customer Support</span></li>
            <li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger"></i>&nbsp; <span class = "pricingcolor">Project Roles</span></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Professional Plan -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm pt-1 h-100">
        <div class="card-body pt-5 text-center">
          <h5 class="card-title fw-bold">Professional</h5>
          <h3 class="fw-bold">₦1500</h3>
          <p>user / mo</p>
          <hr style="width: 220px; padding: auto;">
          <p class="text-muted">10,000 User Active</p>
          <hr style="width: 220px; padding: auto;">
          <a href="#" class="btn w-100 navButton">Select Plan</a>
          <hr>
          <ul class="list-unstyled text-start">
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Free Custom Domain*</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Unlimited Bandwidth</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Contributors</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">Basic Website Metrics</span></li>
            <li><i class="fa-solid fa-circle-check"></i>&nbsp;<span class = "pricingcolor">SEO/Schema Extension</span></li>
            <li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger"></i>&nbsp; <span class = "pricingcolor">Customer Support</span></li>
            <li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger"></i>&nbsp; <span class = "pricingcolor">Project Roles</span></li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection
