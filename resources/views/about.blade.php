@extends('layouts.template')

@section('content')
    <style>
        .impact {
            font-family: "Sohne", -apple-system, BlinkMacSystemFont, "Segoe UI",
               Roboto, Helvetica, Arial, sans-serif;
        }
    </style>
    <div class = "container-fluid py-5 banner" style="background-color : #FDAF22;  position:relative;">
        <div class="row align-items-lg-center">
            <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                <div class="ps-lg-5">
                    <h2 class = "hero_header typewriter impact">
                    Kokokah is Africa’s Leading Digital Learning and Management Platform for Secondary Schools
                </h2>
                </div>

            </div>
            <div class="col-12 col-lg-6 px-lg-0"><img src="./images/about-hero-img.jpg" alt=""
                    class="w-100 img-fluid about-hero-img " style="max-height: 400px"></div>
        </div>
        <img src="./images/paper-plane.png" alt="" class="paper-plane">
    </div>

    <div class = "container-fluid px-3 px-lg-5 d-flex align-items-center">
        <div class = "row mt-4 bg-white g-4">
            <div class = "col-12 col-md-6 col-lg-6 my-auto fade-section-left">
                <img src = "images/577d4af220704c24ce21f8c1dcc8966b836fa580.jpg" class = "img-fluid w-100 "
                    style="max-width:752px; width:100%; height:382px; border-radius : 15px; object-fit:cover;">
            </div>


            <div class = "col-12 col-md-6 col-lg-6 d-flex flex-column gap-3 justify-content-center fade-section">
                <h3 style="color:#004A53;" class="impact">
                    Kokokah for All.
                </h3>
                <p>
                    Kokokah is a smart, pan-African learning
                    and school management platform built for
                    the realities of African education.
                    Whether you’re a JSS1 student in Ghana, an SSS3
                    student in Kenya, or an educator in South Africa,
                    our mission is simple — to give every learner from
                    any background the opportunity to excel with ease.

                </p>
                <button class = "btn aboutPrimaryButton" type = "button">Discover Kokokah Product</button>
            </div>

        </div>
    </div>


    <div class = "container-fluid px-3 py-5 px-lg-5">
        <div class = "row">

            <div class = "col-12 col-md-6 col-lg-6 my-auto fade-section-left d-flex flex-column gap-3">
                <h4 class = "impact" style="color:#004A53;">
                    Our Mission & Vision
                </h4>


                <div class="d-flex align-items-center gap-3">
                    <div class="outer-square ">
                        <div class="inner-circle"></div>
                    </div>
                    {{-- <span class="fw-bold" style="color:#004A53;">Our Vision</span> --}}
                    <h6 style="color:#004A53;">Our Vision</h6>

                </div>
                <p class = "">
                    We strive for a connected Africa where every
                    student has equal access to quality education.
                </p>

                <div class="d-flex align-items-center gap-3">
                    <div class="outer-square">
                        <div class="inner-circle"></div>
                    </div>
                    {{-- <span class="fw-bold" style="color:#004A53;">Our Mision</span> --}}
                    <h6 style="color:#004A53;">Our Mision</h6>

                </div>
                <p class = "">
                    To empower students, parents, and educators
                    with accessible and affordable AI-powered learning
                    tools designed for African schools and curriculums..
                </p>
            </div>

            <div class = "col-12 col-md-6 col-lg-6 mt-4 my-auto fade-section">
                <img src = "images/c862c3d4125719cc339915f430e0b2bfa58a8971.png" class = "img-fluid w-100"
                    style="max-width:752px; width:100%; height:382px; border-radius : 15px; object-fit:cover;">
            </div>


        </div>
    </div>




    <section class="px-3 py-5 px-lg-5" style="background-color: #F56824;">
        <div class = "container-fluid p-0 m-0">
            <div class = "row mt-4 ">

                <div class ="mb-5 justify-content-center">
                    <h3 class = "fw-bold text-center text-white section-title impact">Our Story</h3>
                </div>
            </div>
            <div class="row">
                <div class = "col-12 col-md-6 col-lg-6 mb-4 mb-mb-0 fade-section-left">
                    <img src = "images/our_story_img.jpg" class = "img-fluid w-100"
                        style="height:auto; display:block; border-radius:20px;">
                </div>


                <div class = "col-12 col-md-6 col-lg-6 my-auto fade-section">
                    <div class="d-flex flex-column gap-2">
                        <p class="text-white">
                            We began as a small but determined platform with a single goal:
                            <b>to help Nigerian secondary school students study and pass their exams.</b>
                            Back then, our offerings were limited — a narrow curriculum, few resources,
                            and a focus solely on preparing students for tests. It worked, but we knew
                            education could be so much more.
                        </p>
                        <p class="text-white">
                            Over time, we listened to students, parents, and educators. We saw the challenges
                            African learners face — from unequal access to quality materials, to the need for
                            engaging, relevant content that goes beyond the exam hall. We realised we had to evolve.
                        </p>
                        <p class="text-white">
                            That’s how <b>Kokokah</b> was born.
                        </p>
                        <p class="text-white">
                            Today, Kokokah is more than just an exam-prep tool — it’s a complete <b>AI-powered
                                Learning Management System</b> designed for African secondary schools and learners from
                            <b>junior secondary to senior secondary level</b> wherever they are on the continent.
                            We’ve expanded from a single-country focus to a <b>pan-African mission.</b>
                        </p>
                        <p class="text-white">
                            With <b>accessible mobile learning,</b> students can learn anywhere, even with
                            low internet connectivity. Our <b>AI chatbot</b>
                            tutor gives instant answers and personalised support.
                            <b>Automated grading and feedback</b> make assessment faster and smarter.
                            Our <b>content integration tools</b> empower educators to bring the best learning materials
                            together
                            in one place.
                        </p>
                        <p class="text-white">
                            We’ve gone from limited resources to limitless possibilities. From local impact to continental
                            reach. From preparing students for the next exam — to preparing them for the future.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <div class = "container-fluid d-flex flex-column gap-4 background px-3 px-lg-5">


        <div class="d-flex flex-column justify-content-center gap-3">
            <h3 class = "text-center section-title impact" style="color:#004A53;">Our Core Values</h3>
            <p class = "text-center ">
                Learning should be accessible, engaging, and
                empowering for every student. These values guide
                how we
                build, innovate, and serve at Kokokah.
            </p>

        </div>


        <div class="d-flex flex-column gap-5">
            <div class="row g-5">

                <!-- Card 1 -->
                <div class="col-md-6 col-lg-6 shakeX-on-scroll">
                    <div class="card h-100 border-1 shadow-sm  rounded-4 position-relative text-center p-3 p-lg-5"
                        style = "border-top-right-radius: 40px; border-bottom-left-radius:40px;">
                        <!-- Icon -->
                        <div class="position-absolute top-0 start-50 translate-middle rounded-4 "
                            style="background: #FFF9F0;">
                            <i class="fa-solid fa-user-circle text-warning fs-3"></i>

                        </div>
                        <!-- Content -->
                        <div class="d-flex flex-column gap-2">
                            <h6>Accessiblility</h6>
                            <p class="text-muted">
                                We make learning available anywhere — even
                                with low internet speeds and everyday
                                devices — so no student is left behind.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-6 col-lg-6 shakeX-on-scroll">
                    <div class="card h-100 border-1 shadow-sm  rounded-4 position-relative text-center p-3 p-lg-5"
                        style = "border-top-right-radius: 40px; border-bottom-left-radius:40px;">
                        <!-- Icon -->
                        <div class="position-absolute top-0 start-50 translate-middle rounded-4"
                            style="background: #FFF9F0;">
                            <i class="fa-solid fa-lightbulb text-warning fs-3"></i>
                        </div>
                        <!-- Content -->
                        <div class="d-flex flex-column gap-2">
                            <h6>Innovation with purpose</h6>
                            <p class="text-muted">
                                Every feature we create — from our AI integration to
                                our marketplace — solves real classroom and study challenges.
                            </p>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row g-5">

                <!-- Card 3 -->
                <div class="col-md-6 col-lg-6 d-flex shakeX-on-scroll">
                    <div class="card h-100 border-1 shadow-sm rounded-4 position-relative text-center p-3 p-lg-5"
                        style="border-top-right-radius:40px; border-bottom-left-radius:40px;">

                        <!-- Icon -->
                        <div class="position-absolute top-0 start-50 translate-middle rounded-4"
                            style="background:#FFF9F0;">
                            <i class="fa-solid fa-graduation-cap text-warning fs-3"></i>
                        </div>

                        <!-- Content -->
                        <div class="d-flex flex-column gap-2">
                            <h6>Excellence in learning</h6>
                            <p class="text-muted">
                                We align our platform to African curricula and
                                global standards, ensuring students get the best
                                tools to master concepts and excel in exams.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col-md-6 col-lg-6 d-flex shakeX-on-scroll">
                    <div class="card h-100 border-1 shadow-sm rounded-4 position-relative text-center p-3 p-lg-5"
                        style="border-top-right-radius:40px; border-bottom-left-radius:40px;">

                        <!-- Icon -->
                        <div class="position-absolute top-0 start-50 translate-middle rounded-4"
                            style="background:#FFF9F0;">
                            <i class="fa-solid fa-shield text-warning fs-3"></i>
                        </div>

                        <!-- Content -->
                        <div class="d-flex flex-column gap-2">
                            <h6>Integrity & Trust</h6>
                            <p class="text-muted">
                                We protect user data, offer transparent pricing,
                                and provide safe, moderated spaces for learning.
                            </p>
                        </div>
                    </div>
                </div>

            </div>



            <div class="row g-5">

                <!-- Card 5 -->
                <div class="col-md-6 col-lg-6 d-flex shakeX-on-scroll">
                    <div class="card border-1 shadow-sm h-100 rounded-4 position-relative text-center p-3 p-lg-5"
                        style = "border-top-right-radius: 40px; border-bottom-left-radius:40px;">
                        <!-- Icon -->
                        <div class="position-absolute top-0 start-50 translate-middle rounded-4"
                            style="background: #FFF9F0;">
                            <i class="fa-solid fa-people-group text-warning fs-3"></i>

                        </div>
                        <!-- Content -->
                        <div class="d-flex flex-column gap-2">
                            <h6>Community & Collaboration</h6>
                            <p class="text-muted">
                                We connect students, parents, and educators,
                                creating a supportive learning ecosystem that
                                works together for student success.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 6  -->
                <div class="col-md-6 col-lg-6 d-flex shakeX-on-scroll">
                    <div class="card border-1 shadow-sm h-100 rounded-4 position-relative text-center p-3 p-lg-5"
                        style = "border-top-right-radius: 40px; border-bottom-left-radius:40px;">
                        <!-- Icon -->
                        <div class="position-absolute top-0 start-50 translate-middle rounded-4"
                            style="background: #FFF9F0;">
                            <i class="fa-solid fa-chart-line text-warning fs-3"></i>


                        </div>
                        <!-- Content -->
                        <div class="d-flex flex-column gap-2">
                            <h6>Lifelong Growth</h6>
                            <p class="text-muted">
                                Through Koodies and our learning tools, we prepare students
                                not only for examinations, but for real-world situations and
                                future opportunities.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger, TextPlugin);

        gsap.utils.toArray(".section-title").forEach((section) => {
            gsap.from(section, {
                scale: 0.6,
                duration: 0.6,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: section,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse"
                }
            })
        });

        gsap.utils.toArray(".fade-section").forEach((section) => {
            gsap.from(section, { // use `from` so it starts offscreen
                opacity: 1,
                x: 100, // start 100px to the right
                duration: 0.2,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: section,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse",
                }
            });
        });

        gsap.utils.toArray(".fade-section-left").forEach((section) => {
            gsap.from(section, { // use `from` so it starts offscreen
                opacity: 1,
                x: -100, // start 100px to the right
                duration: 0.2,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: section,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse",
                }
            })
        });


        gsap.utils.toArray(".shakeX-on-scroll").forEach((el) => {
            gsap.timeline({
                    scrollTrigger: {
                        trigger: el,
                        start: "top 85%",
                        toggleActions: "play reverse play reverse",
                        // markers: true  // uncomment to debug
                    }
                })
                .to(el, {
                    x: -10,
                    duration: 0.1,
                    ease: "power1.inOut"
                })
                .to(el, {
                    x: 10,
                    duration: 0.1,
                    ease: "power1.inOut",
                    yoyo: true,
                    repeat: 5
                })
                .to(el, {
                    x: 0,
                    duration: 0.1,
                    ease: "power1.inOut"
                });
        });

        gsap.utils.toArray(".typewriter").forEach((el) => {
            const text = el.textContent;
            el.textContent = ""; // clear text to start typing

            const letters = text.split("");

            letters.forEach((letter) => {
                const span = document.createElement("span");
                span.textContent = letter;
                span.style.opacity = 0; // hide initially

                el.appendChild(span);
            });

            const spans = el.querySelectorAll("span");

            // spans.forEach(span => {
            //     span.style.setProperty(
            //         "font-family",
            //         'Impact, Haettenschweiler, "Arial Narrow Bold", sans-serif',
            //         "important"
            //     );
            // });
            spans.forEach(span => {
                span.style.fontFamily = "inherit";
            })

            gsap.fromTo(spans, {
                opacity: 0
            }, {
                opacity: 1,
                duration: 0.05, // speed of typing
                stagger: 0.05, // delay between letters
                ease: "none",
                scrollTrigger: {
                    trigger: el,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse",
                    // markers: true   // for debugging
                }
            });
        });
    </script>
@endsection
