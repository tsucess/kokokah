@extends('layouts.template')
@section('content')
<div class="position-relative text-white text-center py-5" style="background-image: url('images/heros.jpg'); background-size: contain; background-position: center; min-height: 400px;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5);"></div>
    <div class="position-relative h-100 d-flex flex-column justify-content-center align-items-center px-3">
        <h1 class="display-4 fw-bold">Transforming African Education—One School at a Time.</h1>
        <p class="lead col-lg-8 mx-auto">Kolokab combines School Management, Exam Prep, and a Learning Management System (LMS)—helping schools automate admin tasks, boost student performance, and deliver modern digital learning in one seamless platform.</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-2 align-items-center mb-4">
        <div class="col-sm-8 col-md-9">
            <input type="search" class="form-control rounded-pill" placeholder="Search Product">
        </div>
        <div class="col-sm-4 col-md-3">
            <button class="btn btn-dark w-100 rounded-pill">Search</button>
        </div>
    </div>
    <div class="row g-2 justify-content-center mb-5">
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Teen Health</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Style</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Beauty</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Life & Politics</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Health & Wellness</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Love</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Finance</span>
        </div>
    </div>

---

### Card Layout 🖼️

The three-column card layout is a classic grid pattern. You'll use a `row` containing three `col-md-4` columns, with each column holding a Bootstrap `card` component.

```html
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="your-card-image.jpg" class="card-img-top" alt="...">
                    <span class="position-absolute top-0 start-0 bg-dark text-white p-2 rounded-end m-2">TEEN HEALTH</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold">6 Signs Your Teen Needs More Support During Recovery</h5>
                    <p class="card-text text-muted">Teenagers navigating the journey of recovery can face unique challenges that go unnoticed by parents and caregivers...</p>
                    <a href="#" class="btn btn-link text-decoration-none p-0">Read More</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="your-card-image.jpg" class="card-img-top" alt="...">
                    <span class="position-absolute top-0 start-0 bg-dark text-white p-2 rounded-end m-2">TEEN HEALTH</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold">6 Signs Your Teen Needs More Support During Recovery</h5>
                    <p class="card-text text-muted">Teenagers navigating the journey of recovery can face unique challenges that go unnoticed by parents and caregivers...</p>
                    <a href="#" class="btn btn-link text-decoration-none p-0">Read More</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="your-card-image.jpg" class="card-img-top" alt="...">
                    <span class="position-absolute top-0 start-0 bg-dark text-white p-2 rounded-end m-2">TEEN HEALTH</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold">6 Signs Your Teen Needs More Support During Recovery</h5>
                    <p class="card-text text-muted">Teenagers navigating the journey of recovery can face unique challenges that go unnoticed by parents and caregivers...</p>
                    <a href="#" class="btn btn-link text-decoration-none p-0">Read More</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
