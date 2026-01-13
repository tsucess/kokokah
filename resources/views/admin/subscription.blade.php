@extends('layouts.dashboardtemp')

@section('content')
    <style>
        p {
            margin-bottom: 0;
        }
        .modal-subtitle{
            color: #004A53;
            font-size: 20px;
            font-family: "Fredoka", sans-serif;
        }

        .primaryBtn {
            background-color: #FDAF22;
            padding: 10px 40px;
            border-radius: 4px;
            color: #000F11;
            font-size: 16px;
            font-weight: 600;
        }
        .cancel-btn{
            background-color: transparent;
            padding: 10px 40px;
            border-radius: 4px;
            color: #004A53;
            font-size: 16px;
            font-weight: 600;
            border: 1px solid #004A53;
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
            font-family: "Fredoka", sans-serif;
        }

        .stats-value {
            color: #004A53;
            font-size: 32px;
            font-family: "Fredoka", sans-serif;
        }

        .plan-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
            gap: 30px;
        }

        .plan-card {
            border: 1px solid #CCDBDD;
            border-radius: 20px;
            padding: 30px;
            background-color: transparent;
            gap: 35px;
        }

        .plan-card.accent {
            background-color: #004A53;
        }

        .badge {
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

        .plan-card.accent .badge {
            color: #004A53;
            background-color: #fff;
        }

        .plan-card-title {
            color: #004A53;
            font-size: 18px;
            font-weight: 700;
        }

        .plan-card-text {
            color: #004A53;
            font-size: 18px;
        }

        .plan-card-price {
            font-family: "Fredoka", sans-serif;
            color: #004A53;
            font-weight: 500;
        }

        .plan-card-priceL {
            font-size: 32px;
        }

        .plan-card-priceS {
            font-size: 16px;
        }

        .list-divider {
            background-color: #000000;
            height: 1px;
            width: 100%;
        }

        .list-title {
            font-size: 20px;
            color: #000F11;
            font-family: "Fredoka", sans-serif;
            font-weight: 300;
        }

        .list-item {
            font-size: 15px;
            color: #000F11;
            font-family: "Fredoka", sans-serif;
            font-weight: 300;
        }

        .plan-card.accent .plan-card-title,
        .plan-card.accent .plan-card-text,
        .plan-card.accent .plan-card-price,
        .plan-card.accent .list-item,
        .plan-card.accent .list-title {
            color: #fff;
        }

        .plan-card-btn {
            border: 1px solid #CCDBDD;
            padding: 10px 20px;
            border-radius: 52px;
            color: #000000;
            font-size: 12px;
        }

        .plan-card.accent .list-divider,
        .plan-card.accent .plan-card-btn {
            background-color: #fff;
        }
    </style>
    <main>
        {{-- add/edit subscription modal --}}
        <div class="modal fade" id="addSubscription" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column gap-1">
                            <h1 class="modal-title" style="color: #004A53;" id="modalTitle">Subscription</h1>
                            <p class="modal-subtitle">Kokokah Learning Management System Subscription</p>
                        </div>
                        <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-regular fa-circle-xmark"></i>
                        </button>
                    </div>

                    <form class="modal-form-container" id="">
                        <div class="modal-form">
                            <div class="modal-form-input-border">
                                <label for="subscriptionTitle" class="modal-label">Subscription Tittle</label>
                                <input type="text" class="modal-input" id="subscriptionTitle"
                                    placeholder="e.g., Daily Plan" required>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionDescription" class="modal-label">Subscription Description</label>
                                <textarea class="modal-input" id="subscriptionDescription" placeholder="e.g., Type...." required></textarea>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionPrice" class="modal-label">Subscription Price</label>
                                <input type="number" class="modal-input" id="subscriptionPrice" placeholder="e.g., 100"
                                    required>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionDuration" class="modal-label">Subscription Duration</label>
                                <select type="number" class="modal-input" id="subscriptionDuration">
                                    <option value="daily">Daily</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionPackages" class="modal-label">Packages (separate each item with a
                                    comma)</label>
                                <textarea class="modal-input" id="subscriptionPackages" placeholder="Up to 500 students, Basic reporting....."></textarea>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn primaryBtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4 py-5">
            <header class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold mb-2">Subscription Plans</h1>

                    <p class="text-muted">Our plans are simple, Start Learning Today To Become The Best In Your Academics.
                    </p>
                </div>
                <div>
                    <button data-bs-toggle="modal" data-bs-target="#addSubscription" class="btn-accent-yellow">
                        <i class="fa-solid fa-plus me-2"></i> Add New Subscription
                    </button>
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
            <section class="plan-container">
                <article class="plan-card d-flex flex-column">
                    <div class="d-flex gap-2 align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge">D</div>
                            <div class="d-flex flex-column gap-3">
                                <h3 class="plan-card-title">Daily Plan</h3>
                                <p class="plan-card-text">Access to class note, anytime, anywhere</p>
                                <div class="d-flex align-items-center gap-1 ">
                                    <p class="d-flex align-items-center plan-card-price plan-card-priceL"><i
                                            class="fa-solid fa-naira-sign"></i>300</p>
                                    <p class="plan-card-price plan-card-priceS">/per day</p>
                                </div>
                            </div>
                        </div>
                        <button><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <p class="list-title">What’s Included?</p>
                        <ul class="d-flex flex-column gap-2 ps-0" style="list-style: none;">
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i> Valid
                                for 24hrs</li>
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Access to the subject note</li>
                        </ul>
                        <div class="list-divider"></div>
                    </div>
                    <button class="plan-card-btn align-self-center">Revenue: ₦100,000/mo </button>
                </article>
                <article class="plan-card accent d-flex flex-column">
                    <div class="d-flex gap-2 align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge">W</div>
                            <div class="d-flex flex-column gap-3">
                                <h3 class="plan-card-title">Daily Plan</h3>
                                <p class="plan-card-text">Access to class note, anytime, anywhere</p>
                                <div class="d-flex align-items-center gap-1 ">
                                    <p class="d-flex align-items-center plan-card-price plan-card-priceL"><i
                                            class="fa-solid fa-naira-sign"></i>300</p>
                                    <p class="plan-card-price plan-card-priceS">/per day</p>
                                </div>
                            </div>
                        </div>
                        <button><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <p class="list-title">What’s Included?</p>
                        <ul class="d-flex flex-column gap-2 ps-0" style="list-style: none;">
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Valid
                                for 24hrs</li>
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Access to the subject note</li>
                        </ul>
                        <div class="list-divider"></div>
                    </div>
                    <button class="plan-card-btn align-self-center">Revenue: ₦100,000/mo </button>
                </article>
                <article class="plan-card d-flex flex-column">
                    <div class="d-flex gap-2 align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge">M</div>
                            <div class="d-flex flex-column gap-3">
                                <h3 class="plan-card-title">Daily Plan</h3>
                                <p class="plan-card-text">Access to class note, anytime, anywhere</p>
                                <div class="d-flex align-items-center gap-1 ">
                                    <p class="d-flex align-items-center plan-card-price plan-card-priceL"><i
                                            class="fa-solid fa-naira-sign"></i>300</p>
                                    <p class="plan-card-price plan-card-priceS">/per day</p>
                                </div>
                            </div>
                        </div>
                        <button><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <p class="list-title">What’s Included?</p>
                        <ul class="d-flex flex-column gap-2 ps-0" style="list-style: none;">
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Valid
                                for 24hrs</li>
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Access to the subject note</li>
                        </ul>
                        <div class="list-divider"></div>
                    </div>
                    <button class="plan-card-btn align-self-center">Revenue: ₦100,000/mo </button>
                </article>
            </section>

        </div>
    </main>
@endsection
