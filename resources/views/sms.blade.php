@extends('layouts.template')

@section('content')
    <section>
        <section class="container-fluid sms-hero">
            <div class="row py-5 px-3 px-lg-5 g-5">
                <div class="col-12">
                    <div class="d-flex flex-column gap-4 align-items-center">
                        <div class="d-flex flex-column gap-3 align-items-center">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <div class="d-flex flex-column flex-md-row gap-2 align-items-center">
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="fa-solid fa-star fa-2xs" style="color:#FDAF22;"></i>
                                        <i class="fa-solid fa-star fa-2xs" style="color:#FDAF22;"></i>
                                        <i class="fa-solid fa-star fa-2xs" style="color:#FDAF22;"></i>
                                        <i class="fa-solid fa-star fa-2xs" style="color:#FDAF22;"></i>

                                    </div>
                                    <p class="sms-hero-label">4.8 ( form 1243+ reviews)</p>
                                </div>
                                <div class="d-flex flex-column gap-2 align-items-center">
                                    <h1 class="text-center text-white">The All-in-one Platform for Modern Education</h1>
                                    <p class="text-white text-center">Streamline school administration, enhance
                                        communication and
                                        empower educators with a single, intuitive system.</p>
                                </div>
                            </div>
                            <button class="sms-hero-btn">Start 7-days free trial</button>
                        </div>
                        <div class="d-flex align-items-center gap-2 justify-content-center">
                            <div class="d-flex align-items-center gap-1"><i class="fa-solid fa-circle-check fa-2xs"
                                    style="color:#fff;"></i><span class="tagline-text">500 Schools</span></div>
                            <div class="d-flex align-items-center gap-1"><i class="fa-solid fa-circle-check fa-2xs"
                                    style="color:#fff;"></i><span class="tagline-text">500 Schools</span></div>
                            <div class="d-flex align-items-center gap-1"><i class="fa-solid fa-circle-check fa-2xs"
                                    style="color:#fff;"></i><span class="tagline-text">500 Schools</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <img src="./images/sms-hero-img.jpg" alt=""
                        style="border-radius:30px; width:100%; max-height:600px;">
                </div>
            </div>
        </section>
        <section class="container-fluid px-3 px-lg-5 py-5 d-flex flex-column gap-3">
            <h2 class="text-center sms-feature-title">Everything you need to manage your school</h2>
            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-flex flex-column sms-feature-card sms-feature-card-accent h-100">
                        <div class="sms-feature-icon-container sms-feature-icon-container-accent">
                            <img src="./images/grade-feature-icon.png" alt="">
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <h5>Grade Management</h5>
                            <p class="text-white">Lorem ipsum amet eget lectus nibh dignissim enim eros feugiat pellentesque
                                in bibendum...</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-flex flex-column sms-feature-card h-100">
                        <div class="sms-feature-icon-container">
                            <img src="./images/attendance-feature-icon.png" alt="">
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <h5>Attendance Tracking</h5>
                            <p style="color:#000F11;">Lorem ipsum amet eget lectus nibh dignissim enim eros feugiat
                                pellentesque...</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-flex flex-column sms-feature-card h-100">
                        <div class="sms-feature-icon-container">
                            <img src="./images/communication-feature-icon.png" alt="">
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <h5>Communication Tools</h5>
                            <p style="color:#000F11;">Lorem ipsum amet eget lectus nibh dignissim enim eros feugiat
                                pellentesque...</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-flex flex-column sms-feature-card h-100">
                        <div class="sms-feature-icon-container">
                            <img src="./images/signup-feature-icon.png" alt="">
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <h5>Sign Up</h5>
                            <p style="color:#000F11;">Lorem ipsum amet eget lectus nibh dignissim enim eros feugiat
                                pellentesque...</p>
                        </div>
                    </div>
                </div>
            </div>


        </section>
        <section class="container-fluid px-3 px-lg-5 py-5">
            <div class="row g-4">
                <div class="col col-12 col-lg-5">
                    <div><img src="./images/sms-reason-img.png" alt="" class="w-100 h-100"
                            style=" max-height:700px;"></div>

                </div>
                <div class="col col-12 col-lg-7 d-flex flex-column gap-3 align-items-center">
                    <div class="d-flex flex-column gap-2">
                        <h3 class="sms-feature-title text-center">Why schools choose our system</h3>
                        <p class="text-center">Our platform is designed to stramline workflows, enhance parent-teacher
                            collaboration and provide data-driven insights for better decision-making.</p>
                    </div>
                    <div class="row g-4">

                        <div class="col col-12 col-md-6 ">
                            <div class="d-flex flex-column gap-3 sms-reason-card h-100">
                                <div><img src="./images/reason-card-icon.png" alt=""></div>
                                <div class="d-flex flex-column gap-2">
                                    <h5 style="color: #230B34;">Save Time</h5>
                                    <p style="color: #230B34;">Learn from industry leaders and academic experts in hands-on,
                                        specialized workshops.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="d-flex flex-column gap-3 sms-reason-card h-100">
                                <div><img src="./images/reason-card-icon.png" alt=""></div>
                                <div class="d-flex flex-column gap-2">
                                    <h5 style="color: #230B34;">Improve Communication</h5>
                                    <p style="color: #230B34;">Learn from industry leaders and academic experts in hands-on,
                                        specialized workshops.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="d-flex flex-column gap-3 sms-reason-card h-100">
                                <div><img src="./images/reason-card-icon.png" alt=""></div>
                                <div class="d-flex flex-column gap-2">
                                    <h5 style="color: #230B34;">Boost Engagement</h5>
                                    <p style="color: #230B34;">Learn from industry leaders and academic experts in
                                        hands-on, specialized workshops.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="d-flex flex-column gap-3 sms-reason-card h-100">
                                <div><img src="./images/reason-card-icon.png" alt=""></div>
                                <div class="d-flex flex-column gap-2">
                                    <h5 style="color: #230B34;">Data-Driven Insight</h5>
                                    <p style="color: #230B34;">Learn from industry leaders and academic experts in
                                        hands-on, specialized workshops.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- Swiper Container -->
<div class="swiper mySwiper py-5" style="width: 100%;">
  <div class="swiper-wrapper">
    <div class="swiper-slide d-flex flex-column gap-5 flex-lg-row">
        <div class="d-flex flex-column align-items-center gap-2 my-auto">
            <h2>4.8</h2>
            <div class="d-flex align-items-center gap-1">
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;" ></i>
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;"></i>
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;"></i>
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;"></i>
                <i class="fa-regular fa-star fa-xs" style="color: #FFC700;"></i>
            </div>
        </div>
        <div class="d-flex flex-column gap-4 align-items-center align-items-lg-start">
            <div><i class="fa-solid fa-quote-left fa-2xl"></i></div>

            <div class="d-flex align-items-center gap-2">
                <img src="./images/sms-testimonial-img.jpg" alt="" class="swiper-slide-img">
                <div class="d-flex flex-column gap-1">
                    <h6>Rizka Amalia</h6>
                    <p>Penanya</p>
                </div>
            </div>
            <p >Saya sudah banyak mencari tentang pertanyaan yang
saya cari saat ini, dan di Brainly Reborn ini saya mendapatkan jawaban yang akurat dan simple.</p>
        </div>
    </div>
    <div class="swiper-slide d-flex flex-column gap-5 flex-lg-row">
        <div class="d-flex flex-column align-items-center gap-2 my-auto">
            <h2>4.8</h2>
            <div class="d-flex align-items-center gap-1">
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;" ></i>
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;"></i>
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;"></i>
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;"></i>
                <i class="fa-regular fa-star fa-xs" style="color: #FFC700;"></i>
            </div>
        </div>
        <div class="d-flex flex-column gap-4 align-items-center align-items-lg-start">
            <div><i class="fa-solid fa-quote-left fa-2xl"></i></div>

            <div class="d-flex align-items-center gap-2">
                <img src="./images/sms-testimonial-img.jpg" alt="" class="swiper-slide-img">
                <div class="d-flex flex-column gap-1">
                    <h6>Rizka Amalia</h6>
                    <p>Penanya</p>
                </div>
            </div>
            <p >Saya sudah banyak mencari tentang pertanyaan yang
saya cari saat ini, dan di Brainly Reborn ini saya mendapatkan jawaban yang akurat dan simple.</p>
        </div>
    </div>
    <div class="swiper-slide d-flex flex-column gap-5 flex-lg-row">
        <div class="d-flex flex-column align-items-center gap-2 my-auto">
            <h2>4.8</h2>
            <div class="d-flex align-items-center gap-1">
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;" ></i>
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;"></i>
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;"></i>
                <i class="fa-solid fa-star fa-xs" style="color: #FFC700;"></i>
                <i class="fa-regular fa-star fa-xs" style="color: #FFC700;"></i>
            </div>
        </div>
        <div class="d-flex flex-column gap-4 align-items-center align-items-lg-start">
            <div><i class="fa-solid fa-quote-left fa-2xl"></i></div>

            <div class="d-flex align-items-center gap-2">
                <img src="./images/sms-testimonial-img.jpg" alt="" class="swiper-slide-img">
                <div class="d-flex flex-column gap-1">
                    <h6>Rizka Amalia</h6>
                    <p>Penanya</p>
                </div>
            </div>
            <p >Saya sudah banyak mencari tentang pertanyaan yang
saya cari saat ini, dan di Brainly Reborn ini saya mendapatkan jawaban yang akurat dan simple.</p>
        </div>
    </div>

  </div>

  <!-- arrows -->
  <div class="swiper-button-next"><i class="fa-solid fa-angle-right" style="color: #888888;"></i></div>
  <div class="swiper-button-prev"><i class="fa-solid fa-angle-left" style="color: #888888;"></i></div>
</div>



    </section>
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script>
var swiper = new Swiper(".mySwiper", {
  slidesPerView: 1.2,
  centeredSlides: true,
  spaceBetween: 20,
  loop: true,
  grabCursor: true,

  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },

  breakpoints: {
    768: { slidesPerView: 1.5 },
    1024: { slidesPerView: 2 }
  }
});


    </script>
@endsection
