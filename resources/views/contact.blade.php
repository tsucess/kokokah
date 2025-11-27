@extends('layouts.template')

@section('content')
    <section class="container-fluid px-2 px-lg-5 d-flex flex-column gap-1 justify-content-center align-items-center hero-banner">
        <h2>Get in <span>Touch</span></h2>
        <p>Have questions about Kokokah? Our team is here to help you with anything you need.</p>
    </section>
    <section class="" style="background-color: #F18338;">
        <div class="container-fluid py-5 ">
            <div class="row g-5">
                <div class="col col-12 col-lg-6 d-flex flex-column gap-3 form-container ">
                    <h2 class="form-title">Send us a <span style="color : #FDAF22;">Message</span></h2>
                    <div class="d-flex gap-1 flex-column">
                        <label for="">Full Name</label>
                        <input type="text" name="" id="" placeholder="Enter your full name"
                            class="form-input">
                    </div>
                    <div class="d-flex gap-1 flex-column">
                        <label for="">Email Address</label>
                        <input type="text" name="" id="" placeholder="Enter your email address"
                            class="form-input">
                    </div>
                    <div class="d-flex gap-1 flex-column">
                        <label for="">Subject</label>
                        <input type="text" name="" id="" placeholder="Select a subject"
                            class="form-input">
                    </div>
                    <div class="d-flex gap-1 flex-column">
                        <label for="">Message</label>
                        <textarea name="" id="" class="form-input" placeholder="Type your message here" style="height: 150px;"></textarea>
                    </div>
                    <button class="form-btn">Send Message</button>

                </div>
                <div class="col col-12 col-lg-6">
                    <div class="container-fluid">
                        <div class="row mb-4">
                            <div class="col d-flex flex-column gap-3 contact-container">
                                <h3><span style="color: #FDAF22;">Contact</span> Information</h3>
                                <p>Our team is available Monday through Friday, 9am to 5pm EST. We strive to
                                    respond to all inquiries within 24 hours.</p>
                                <div class="d-flex gap-1 flex-column">
                                    <a href="#"><i class="fa-regular fa-envelope"></i>support@kokokah.com</a>
                                    <a href="#"><i class="fa-solid fa-phone"></i>+234.706.054.5027</a>
                                    <a href="#"><i class="fa-solid fa-location-dot"></i>8a Adebayo Mokuolu Street,
                                        Anthony Village, Lagos, Nigeria.</a>
                                </div>
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="social-icon-container"><i class="fa-brands fa-twitter"></i></div>
                                    <div class="social-icon-container"><i class="fa-brands fa-linkedin-in"></i></div>
                                    <div class="social-icon-container"><i class="fa-brands fa-facebook-f"></i></div>
                                    <div class="social-icon-container"><i class="fa-brands fa-instagram"></i></div>
                                    <div class="social-icon-container"><i class="fa-brands fa-tiktok"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col d-flex flex-column gap-3 contact-container">
                                <h3><span style="color: #FDAF22;">Support</span> Hours</h3>
                                <p>Our customer support team is available during the following hours:</p>
                                <div class="d-flex flex-column gap-1">
                                    <p class="d-flex justify-content-between align-items-center gap-1 day">Monday -
                                        Friday:<span class="time">9:00 AM - 6:00 PM WAT</span></p>
                                    <p class="d-flex justify-content-between align-items-center gap-1 day">Saturday:<span
                                            class="time">10:00 AM - 2:00 PM WAT</span></p>
                                    <p class="d-flex justify-content-between align-items-center gap-1 day">Sunday:<span
                                            class="time">Closed</span></p>
                                </div>
                                <div class="link-text">For urgent matters outside of business hours, please email our
                                    emergency
                                    support team at <a href="link">support@kokokah.com</a></div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
    <section class=" faq-container py-5">
        <h4 class="faq-title mb-5 text-center">Frequently Asked <span style="color: #FDAF22;">Questions</span></h4>

        <div class="accordion d-flex flex-column  mx-auto g-5" id="accordionExample" style="max-width: 800px;">
            <div class="accordion-item faq-item-container g-5">
                <h2 class="accordion-header">
                    <button class="accordion-button faq-item-title" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        What is Kokokah?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body faq-item-text">
                        Kokokah is a smart, pan-African learning and school management platform built for the realities of
                        African education. Whether you’re a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator
                        in South Africa, our mission is simple — to give every learner from any background the opportunity
                        to excel with ease.
                    </div>
                </div>
            </div>

            <div class="accordion-item faq-item-container">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Who can use Kokokah?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body faq-item-text">
                        Kokokah is a smart, pan-African learning and school management platform built for the realities of
                        African education. Whether you’re a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator
                        in South Africa, our mission is simple — to give every learner from any background the opportunity
                        to excel with ease.
                    </div>
                </div>
            </div>

            <div class="accordion-item faq-item-container">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Is there a free trial version?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body faq-item-text">
                        Kokokah is a smart, pan-African learning and school management platform built for the realities of
                        African education. Whether you’re a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator
                        in South Africa, our mission is simple — to give every learner from any background the opportunity
                        to excel with ease.
                    </div>
                </div>
            </div>

            <div class="accordion-item faq-item-container">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        How do I create an account?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body faq-item-text">
                        Kokokah is a smart, pan-African learning and school management platform built for the realities of
                        African education. Whether you’re a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator
                        in South Africa, our mission is simple — to give every learner from any background the opportunity
                        to excel with ease.
                    </div>
                </div>
            </div>

            <div class="accordion-item faq-item-container">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Can I use Kokokah on mobile and desktop?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body faq-item-text">
                        Kokokah is a smart, pan-African learning and school management platform built for the realities of
                        African education. Whether you’re a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator
                        in South Africa, our mission is simple — to give every learner from any background the opportunity
                        to excel with ease.
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
