@extends('layouts.template')

@section('content')
<div class = "container-fluid p-5 banner">
<div class = "row mt-4">

<div class = "col-12 col-md-7 col-lg-7 my-auto">
<h1 class = "heroheading">
    Transform How you Study, Teach, Track Learning, and Succeed!
</h1>
<p class = "mb-4">
    An all-in-one affordable, mobile LMS for African students, schools, and educators
</p>

<div class = "d-flex flex-column flex-sm-row gap-2">
    <button class = "btn primaryButton" style = "background:#004A53;">Create an account</button>
</div>




</div>


<div class = "col-12 col-md-5 col-lg-5">
<img src = "images/pricing.png" class = "img-fluid">
</div>

</div>

</div>


<div class = "row  mt-5 justify-content-center text-center" style = "width: 100%;">
    <h4 class = "text-dark">Subscription</h4>
    {{-- <p class = "font-family: inter, sans-serif; text-align: center; color: #6c757d; max-width: 600px; margin: 0 auto;" > --}}
        <p>
        Choose a plan that’s right for your needs.
        Enjoy quality educational needs at a price you can afford.
    </p>


    <div class="d-flex align-items-center justify-content-center  my-4">

  <!-- Monthly label -->
  <span class="fw-bold">Monthly</span>

  <!-- Toggle switch -->
  <div class="form-check form-switch m-0">
    <input class="form-check-input" type="checkbox" id="billingSwitch">
  </div>

  <!-- Yearly label -->
  <span class="fw-bold">Yearly</span>

  <!-- Discount badge -->
  <span class="badge rounded-pill border border-success text-success px-3 py-2 off">
    35% OFF
  </span>
</div>

</div>


<div class="container py-5">
  <div class="row justify-content-center">

    <!-- Free Plan -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm pt-1" style="height:370px;">
        <div class="card-body text-center pt-5">
          <h4 class = "text-dark">Free</h4>
          <h5>₦0</h5>
          <p>user / mo</p>
          <hr style="width: 220px; padding: auto;">
          <p><b>100</b> User Active</p>
          <hr style="width: 220px; padding: auto;">
          {{-- <a href="#" class="btn  w-100 navButton">Select Plan</a> --}}
          <button style="background:#F56824; color: #fff; border-color:#F56824; padding: 5px 35px; border-radius:5px;">Select Plan</button>
          <p class="text-center mt-3 fw-bold">Display stars in Google organic search result and showcase.</p>
        </div>
      </div>

               <div class = "text-center" style="height:30px; background:#E8E8E8; font-inter; font-style:regular; font-weight: 500; font-size: 16px; line-height: 20px; color:#000; padding-top:7px;">
            All features option
        </div>


          <div class="card shadow-sm pt-1" style = "height:330px;">
        <div class="card-body  text-center">
          <ul class="list-unstyled text-start">
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp;<span class = "pricingcolor"> Free Custom Domain*</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Unlimited Bandwidth</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Contributors</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Basic Website Metrics</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">SEO/Schema Extension</span></li></p>
            <p><li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger pe-2"></i>&nbsp; <span class = "pricingcolor">Customer Support</span></li></p>
            <p><li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger pe-2"></i>&nbsp; <span class = "pricingcolor">Project Roles</span></li></p>
          </ul>
        </div>
      </div>
    </div>

    <!-- Basic Plan -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm pt-1" style="height:370px;">
        <div class="card-body text-center pt-5">

          <h4>Basic</h4>
          <h5 class="fw-bold">₦1500</h5>
          <p> user / mo</p>
           <hr style="width: 220px; padding: auto;">
          <p><b>500</b> User Active</p>
          <hr style="width: 220px; padding: auto;">
          {{-- <a href="#" class="btn w-100 navButton">Select Plan</a> --}}
          <button style="background:#F56824; color: #fff; border-color:#F56824; padding: 5px 35px; border-radius:5px;">Select Plan</button>
          <p class="text-center mt-3 fw-bold">Display stars in Google organic search result and showcase.</p>
        </div>
      </div>

               <div class = "text-center" style="height:30px; background:#E8E8E8; font-inter; font-style:regular; font-weight: 500; font-size: 16px; line-height: 20px; color:#000; padding-top:7px;">
            All features option
        </div>


          <div class="card shadow-sm pt-1" style = "height:330px;">
        <div class="card-body  text-center">
          <ul class="list-unstyled text-start">
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp;<span class = "pricingcolor"> Free Custom Domain*</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Unlimited Bandwidth</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Contributors</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Basic Website Metrics</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">SEO/Schema Extension</span></li></p>
            <p><li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger pe-2"></i>&nbsp; <span class = "pricingcolor">Customer Support</span></li></p>
            <p><li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger pe-2"></i>&nbsp; <span class = "pricingcolor">Project Roles</span></li></p>
          </ul>
        </div>
      </div>
    </div>

    <!-- Standard Plan (Highlighted) -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm" style = "height:370px;">
        <div class="card-header text-white text-center"  style="background:#F56824;">
          Popular Plan
        </div>
        <div class="card-body text-center" style="margin-top:-7px;">

          <h4>Standard</h4>
          <h5>₦1500 </h5>
          <p>user / mo</p>
          <hr style="width: 220px; padding: auto;">
          <p><b>1000</b> User Active</p>
          <hr style="width: 220px; padding: auto;">
          {{-- <a href="#" class="btn w-100 navButton">Select Plan</a> --}}
          <button style="background:#F56824; color: #fff; border-color:#F56824; padding: 5px 35px; border-radius:5px;">Select Plan</button>
        <p class="text-center mt-3 fw-bold">Display stars in Google organic search result and showcase.</p>
        </div>
      </div>

               <div class = "text-center" style="height:30px; background:#E8E8E8; font-inter; font-style:regular; font-weight: 500; font-size: 16px; line-height: 20px; color:#000; padding-top:7px;">
            All features option
        </div>


          <div class="card shadow-sm pt-1" style = "height:330px;">
        <div class="card-body  text-center">
      <ul class="list-unstyled text-start">
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp;<span class = "pricingcolor"> Free Custom Domain*</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Unlimited Bandwidth</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Contributors</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Basic Website Metrics</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">SEO/Schema Extension</span></li></p>
            <p><li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger pe-2"></i>&nbsp; <span class = "pricingcolor">Customer Support</span></li></p>
            <p><li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger pe-2"></i>&nbsp; <span class = "pricingcolor">Project Roles</span></li></p>
          </ul>
        </div>
      </div>
    </div>

    <!-- Professional Plan -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm pt-1" style = "height:370px;">
        <div class="card-body pt-5 text-center">
          <h4>Professional</h4>
          <h5>₦1500</h5>
          <p>user / mo</p>
          <hr style="width: 220px; padding: auto;">
          <p><b>10,000</b> User Active</p>
          <hr style="width: 220px; padding: auto;">
          {{-- <a href="#" class="btn w-100 navButton">Select Plan</a> --}}
          <button style="background:#F56824; color: #fff; border-color:#F56824; padding: 5px 35px; border-radius:5px;">Select Plan</button>

          <p class="text-center mt-3 fw-bold">Display stars in Google organic search result and showcase.</p>
        </div>
      </div>

          <div class = "text-center" style="height:30px; background:#E8E8E8; font-inter; font-style:regular; font-weight: 500; font-size: 16px; line-height: 20px; color:#000; padding-top:7px;">
            All features option
        </div>

          <div class="card shadow-sm pt-1" style = "height:330px;">
        <div class="card-body  text-center">
          <ul class="list-unstyled text-start">
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp;<span class = "pricingcolor"> Free Custom Domain*</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Unlimited Bandwidth</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Contributors</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">Basic Website Metrics</span></li></p>
            <p><li><i class="fa-solid fa-circle-check pe-2"></i>&nbsp; <span class = "pricingcolor">SEO/Schema Extension</span></li></p>
            <p><li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger pe-2"></i>&nbsp; <span class = "pricingcolor">Customer Support</span></li></p>
            <p><li class="text-muted"><i class="fa-solid fa-circle-xmark text-danger pe-2"></i>&nbsp; <span class = "pricingcolor">Project Roles</span></li></p>
          </ul>
        </div>
      </div>


    </div>





</div>
</div>

@endsection
