@extends('layouts.dashboardtemp')

@section('content')
    <style>
        p{
            margin-bottom: 0;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
            gap: 30px;
        }

        .stats-container {
            border: 1px solid #CCDBDD;
            padding: 30px;
            gap: 30px;
            border-radius: 20px;
        }

        .stats-title {
            color: #004A53;
            font-size: 16px;
            font-family: 'fredoka one';
        }

        .stats-value {
            color: #004A53;
            font-size: 32px;
            font-family: 'fredoka one';
        }
        .plan-container{
            display: grid;
             grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
             gap: 30px;
        }
        .plan-card{
            border: 1px solid #CCDBDD;
            border-radius: 20px;
            padding: 30px;
            background-color: transparent;
            gap: 35px;
        }
        .badge{
            background-color: #CCDBDD;
            width: 51px;
            height: 51px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #004A53;
            font-size: 32px;
        }
        .plan-card-title{
color: #004A53;
font-size: 18px;
font-weight: 700;
        }
        .plan-card-text{
color: #004A53;
font-size: 18px;
        }
        .plan-card-price{
            font-family: 'fredoka one';
            color: #004A53;
            font-weight: 500;
        }
        .plan-card-priceL{
            font-size: 32px;
        }
        .plan-card-priceS{
            font-size: 16px;
        }
        .list-divider{
            background-color: #000000;
            height: 1px;
            width: 100%;
        }
        .list-title{
            font-size: 20px;
            color: #000F11;
            font-family: 'fredoka one';
            font-weight: 300;
        }
        .list-item{
            font-size: 15px;
            color: #000F11;
            font-family: 'fredoka one';
            font-weight: 300;
        }
    </style>
    <main>
        <div class="container-fluid px-4 py-5">
            <header class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold mb-2">Subscription Plans</h1>

                    <p class="text-muted">Our plans are simple, Start Learning Today To Become The Best In Your Academics.
                    </p>
                </div>
                <div>
                    <a href="" class="btn-accent-yellow">
                        <i class="fa-solid fa-plus me-2"></i> Add New Subscription
                    </a>
                </div>
            </header>
            <section class="stats mb-5">
                <div class="stats-container d-flex flex-column">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3 justify-content-between">
                            <h3 class="stats-title">Monthly Revenue</h3>
                            <div><i class="fa-solid fa-naira-sign"></i></div>
                        </div>
                        <div class="d-flex gap-1 align-items-center stats-value"><i class="fa-solid fa-naira-sign"></i>
                            <h4 class="stats-value">100,000,000</h4>
                        </div>
                    </div>
                    <p>↗️ +2% this month</p>
                </div>
                <div class="stats-container d-flex flex-column">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3 justify-content-between">
                            <h3 class="stats-title">Active Subscription</h3>
                            <div><i class="fa-solid fa-naira-sign"></i></div>
                        </div>

                        <h4 class="stats-value">124</h4>

                    </div>
                    <p>↗️ +2% this month</p>
                </div>
            </section>
            <section>
                <article class="plan-card d-flex flex-column">
                    <div class="d-flex gap-2 align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge">D</div>
                            <div class="d-flex flex-column gap-3">
                                <h3 class="plan-card-title">Daily Plan</h3>
                                <p class="plan-card-text">Access to class note, anytime, anywhere</p>
                                <div class="d-flex align-items-center gap-1 ">
                                    <p class="d-flex align-items-center plan-card-price plan-card-priceL"><i class="fa-solid fa-naira-sign"></i>300</p>
                                    <p class="plan-card-price plan-card-priceS">/per day</p>
                                </div>
                            </div>
                        </div>
                        <button><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <p class="list-title">What’s Included?</p>
                        <ul class="d-flex flex-column gap-2 ps-0" style="list-style: none;">
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i> Valid for 24hrs</li>
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i> Access to the subject note</li>
                        </ul>
                        <div class="list-divider"></div>
                    </div>
                </article>
            </section>
        </div>
    </main>
@endsection
