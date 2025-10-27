@extends('layouts.template')

@section('content')

<!-- Hero Section - Yellow Background -->
<div class="container-fluid hero-section-yellow">
  <div class="row align-items-center">
    <div class="col-12 col-md-9 mb-4 mb-md-0">
      <p class="mb-2 hero-label">Get Started</p>
      <h1 class="fw-bold hero-title">
        Get in touch with us. We're here to assist you.
      </h1>
    </div>
    <div class="col-12 col-md-3 d-flex flex-row justify-content-center justify-content-md-end gap-3">
      <a href="#" class="btn btn-outline-dark social-icon-btn">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="#" class="btn btn-outline-dark social-icon-btn">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="#" class="btn btn-outline-dark social-icon-btn">
        <i class="fab fa-twitter"></i>
      </a>
    </div>
  </div>
</div>

<!-- Contact Form Section -->
<div class="container-fluid section-white section-py">
  <div class="container">
    <form>
      <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
          <label for="yourName" class="form-label fw-bold form-label-custom">Your Name</label>
          <input type="text" class="form-control form-input-custom" id="yourName">
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
          <label for="emailAddress" class="form-label fw-bold form-label-custom">Email Address</label>
          <input type="email" class="form-control form-input-custom" id="emailAddress">
        </div>
        <div class="col-md-4">
          <label for="phoneNumber" class="form-label fw-bold form-label-custom">Phone Number (optional)</label>
          <input type="tel" class="form-control form-input-custom" id="phoneNumber">
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-12">
          <label for="message" class="form-label fw-bold form-label-custom">Message</label>
          <textarea class="form-control form-input-custom" id="message" rows="4"></textarea>
        </div>
      </div>

      <button type="submit" class="btn btn-yellow-action">Leave us a Message</button>
    </form>
  </div>
</div>

<!-- Contact Info Section -->
<div class="container-fluid section-light-gray section-py">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 mb-4 mb-lg-0">
        <p class="hero-label">Contact Info</p>
        <h2 class="fw-bold section-heading">
          We are always happy to assist you
        </h2>
      </div>

      <div class="col-lg-7">
        <div class="row">
          <div class="col-md-6 mb-4 mb-md-0">
            <h6 class="fw-bold section-heading">Email Address</h6>
            <div class="divider-line"></div>
            <p class="mb-0 achievement-card-text fw-bold">help@info.com</p>
            <small class="hero-label">Assistance hours: <br>Monday - Friday 6 am to 8 pm</small>
          </div>
          <div class="col-md-6">
            <h6 class="fw-bold section-heading">Number</h6>
            <div class="divider-line"></div>
            <p class="mb-0 achievement-card-text fw-bold">(81) 292-9049</p>
            <small class="hero-label">Assistance hours: <br>Monday - Friday 6 am to 8 pm</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

