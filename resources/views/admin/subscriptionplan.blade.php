@extends('layouts.dashboardtemp')

@section('content')
<main>
 <section class="d-flex gap-5 flex-column m-0 p-0">
        <div class="d-flex flex-column m-3 subscription-header">
            <h4 class="subscription-title">Pricing Plans</h4>
            <p class="subscription-text">Our plans are simple, Start Learning Today To Become The Best In Your Academics.</p>
        </div>
        <div class="d-flex align-items-center justify-content-center gap-2">
            <span class="toggle-text">Monthly</span>
            <div class=" toggle-container">
                <span class="toggle-switch"></span>
            </div>

            <span class="toggle-text">Yearly</span>
            <div class="toggle-label d-flex justify-content-center align-items-center rounded-pill">35% OFF</div>
        </div>
        </div>

        <div class="container-fluid">
            <div class="row g-4">
                <!-- First two plans -->
                <div class="col-12 col-lg-6">
                    <div class="price-plan">
                        <div class="d-flex flex-column" style="gap: 29px;">
                            <div class="price-plan-header">
                                <h5 class="price-plan-title">Day Plan</h5>
                                <p class="price-plan-subtext">Lorem ipsum dolor sit amet.</p>
                            </div>
                            <div class="price-plan-price-container">
                                <span class="price-plan-price-large">N300/</span>
                                <span class="price-plan-price-small">Day</span>
                            </div>
                            <ul class="price-plan-list d-flex flex-column p-0">
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Valid for 24hrs</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all Class Notes</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all JSSCE Past Questions</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all SSCE Past Questions</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all UMTE / JAMB Past Questions</li>
                            </ul>
                        </div>
                        <button class="d-flex justify-content-center align-items-center price-plan-btn">Get Started</button>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="price-plan">
                        <div class="d-flex flex-column" style="gap: 29px;">
                            <div class="price-plan-header">
                                <h5 class="price-plan-title">Weekly Plan</h5>
                                <p class="price-plan-subtext">Lorem ipsum dolor sit amet.</p>
                            </div>
                            <div class="price-plan-price-container">
                                <span class="price-plan-price-large">N1500/</span>
                                <span class="price-plan-price-small">Week</span>
                            </div>
                            <ul class="price-plan-list d-flex flex-column p-0">
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Valid for 24hrs</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all Class Notes</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all JSSCE Past Questions</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all SSCE Past Questions</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all UMTE / JAMB Past Questions</li>
                            </ul>
                        </div>
                        <button class="d-flex justify-content-center align-items-center price-plan-btn">Get Started</button>
                    </div>
                </div>
            </div>

            <!-- Last plan centered on large screens -->
            <div class="row mt-4">
                <div class="col-12 col-lg-6 offset-lg-3">
                    <div class="price-plan">
                        <div class="d-flex flex-column" style="gap: 29px;">
                            <div class="price-plan-header">
                                <h5 class="price-plan-title">Premium Plan</h5>
                                <p class="price-plan-subtext">Lorem ipsum dolor sit amet.</p>
                            </div>
                            <div class="price-plan-price-container">
                                <span class="price-plan-price-large">N3000/</span>
                                <span class="price-plan-price-small">Month</span>
                            </div>
                            <ul class="price-plan-list d-flex flex-column p-0">
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Valid for 24hrs</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all Class Notes</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all JSSCE Past Questions</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all SSCE Past Questions</li>
                                <li class="price-plan-list-item"><i class="fa-solid fa-circle-check" style="color:#12141D;"></i>Access to all UMTE / JAMB Past Questions</li>
                            </ul>
                        </div>
                        <button class="d-flex justify-content-center align-items-center price-plan-btn">Get Started</button>
                    </div>
                </div>
            </div>
        </div>


    </section>

</main>
@endsection
