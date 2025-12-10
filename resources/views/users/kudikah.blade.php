@extends('layouts.usertemplate')

@section('content')
    <main>
        <style>
            .addmoney-btn {
                border: 2px solid #004A53;
                padding: 16px 20px;
                color: #004A53;
                font-size: 16px;
                width: 100%;
                font-weight: 600;
                border-radius: 4px;
            }

            .enroll-btn {
                border-radius: 4px;
                padding: 16px 20px;
                color: #000F11;
                font-size: 16px;
                width: 100%;
                font-weight: 600;
                background: #FDAF22;
            }

            .form-title {
                font-size: 16px;
                color: #000;
                font-weight: 700;
                font-family: 'fredoka';
            }

            .form-divider {
                border: 1px solid #BFBFBF;
            }

            .input-border {
                border: 1.5px solid #004A53;
                border-radius: 10px;
                padding: 14px 27px;
                position: relative;
                margin-top: 12px;
            }

            .form-input {
                background-color: transparent;
                font-size: 14px;
                color: #8E8E93;
                outline: none;
                border: none;
            }

            .form-label {
                color: #004A53;
                font-size: 14px;
                background-color: #fff;
                padding: 2px 5px;
                font-weight: 600;
                position: absolute;
                left: 14px;
                top: -12px;
            }

            .checkbox-label {
                color: #000000;
                font-size: 16px;
            }
            .form-header-text{
                color: #311507;
                font-size:12px;
                font-weight: 600;
            }
            .toggle-btn{
                background-color: #CCDBDD;
                width: 24px;
                height: 15px;
                border: none;
            }
        </style>
        <section class="container-fluid p-4">
            <div class="row g-4">


                <div class="col-12 col-lg-7 ">

                    <div class="balance-header d-flex flex-column ">
                        <div>
                            <small class="opacity-75 balance-header-title">Total Balance</small>
                            <i class="fa-regular fa-eye" style="color:#fff;"></i>
                        </div>
                        <h1 class="main-balance-text mb-4">₦400,000.00</h1>
                        <p class="balance-header-cardNo mb-0">444 221 224 ***</p>
                        {{-- <i class="bi bi-bank text-white fs-3 position-absolute" style="bottom: 20px; right: 20px; opacity: 0.5;"></i> --}}
                        <img src="./images/card-icon.png" alt="" class="position-absolute "
                            style="bottom:20px; right:100px;" />
                    </div>

                    <div class="row g-3 mb-5">
                        <div class="col-12">
                            <button class="addmoney-btn d-flex gap-1 align-items-center justify-content-center">
                                <i class="bi bi-plus me-2"></i> Add Money
                            </button>
                        </div>
                        <div class="col-12">
                            <button class="enroll-btn">
                                Enroll a class
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-3 shadow-sm">
<div class="d-flex align-items-center justify-content-between p-3">
                    <h5 class="fw-bold">Transaction History</h5>

                    <div class="d-flex  gap-2">
                        <div class="dropdown">
                            <button class="btn filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                All Categories
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Transfers</a></li>
                                <li><a class="dropdown-item" href="#">Deposits</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                All Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Completed</a></li>
                                <li><a class="dropdown-item" href="#">Pending</a></li>
                            </ul>
                        </div>
                    </div>
                    </div>

                    <div class="transaction-list p-3">

                        <div class="transaction-item">
                            <div class="transaction-details">
                                <div class="transaction-icon-container">
                                    <i class="bi bi-arrow-left-right"></i>
                                </div>
                                <div>
                                    <span class="d-block">Transfer from Winner Effiong Duff</span>
                                    <small class="text-muted">Sep 4th, 3:14:29 PM</small>
                                </div>
                            </div>
                            <span class="transaction-amount">+₦9,000.00</span>
                        </div>

                        <div class="transaction-item">
                            <div class="transaction-details">
                                <div class="transaction-icon-container">
                                    <i class="bi bi-arrow-left-right"></i>
                                </div>
                                <div>
                                    <span class="d-block">Transfer from Winner Effiong Duff</span>
                                    <small class="text-muted">Sep 4th, 3:14:29 PM</small>
                                </div>
                            </div>
                            <span class="transaction-amount">+₦9,000.00</span>
                        </div>

                        <div class="transaction-item">
                            <div class="transaction-details">
                                <div class="transaction-icon-container">
                                    <i class="bi bi-arrow-left-right"></i>
                                </div>
                                <div>
                                    <span class="d-block">Transfer from Winner Effiong Duff</span>
                                    <small class="text-muted">Sep 4th, 3:14:29 PM</small>
                                </div>
                            </div>
                            <span class="transaction-amount">+₦9,000.00</span>
                        </div>

                        <div class="transaction-item" style="border-bottom: none;">
                            <div class="transaction-details">
                                <div class="transaction-icon-container">
                                    <i class="bi bi-arrow-left-right"></i>
                                </div>
                                <div>
                                    <span class="d-block">Transfer from Winner Effiong Duff</span>
                                    <small class="text-muted">Sep 4th, 3:14:29 PM</small>
                                </div>
                            </div>
                            <span class="transaction-amount">+₦9,000.00</span>
                        </div>

                    </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 d-flex flex-column gap-5">
                    <div class="d-flex flex-column gap-4">
                        <div class="position-relative"><img src="./images/card-img.png" alt="" class="w-100"
                                style="height:225px; border-radius:20px;">
                            <img src="./images/logos_mastercard.png" alt="" class="position-absolute"
                                style="right:20px; bottom:40px;" />
                            <img src="./images/card-chip-icon.png" alt="" class="position-absolute"
                                style="right:30px; top:20px;" />

                            <div class="position-absolute d-flex flex-column gap-3" style="bottom: 40px; left:30px;">
                                <div class="d-flex flex-column gap-1">
                                    <p style="font-size: 24px; color:#fff;">444 221 224 3456 </p>
                                    <div class="d-flex flex-column">
                                        <p style="font-size: 14px; color:#fff;">Valid Thur</p>
                                        <p style="font-size: 14px; color:#fff;">03/30</p>
                                    </div>
                                </div>
                                <p style="font-size: 24px; color:#fff;">Winner Effiong Duff</p>
                            </div>
                        </div>
                        <button class="addmoney-btn">Edit</button>
                    </div>
                    <form action="" class="rounded-2 overflow-hidden shadow-sm">
                        <div class="p-3 d-flex justify-content-between" style="background-color: #F89A6D;">
                            <p class="form-header-text">Add a new payment method</p>
                            <button class="toggle-btn d-flex justify-content-center align-items-center"><i class="fa-solid fa-chevron-down fa-sm"></i></button>
                        </div>
                        <div class="d-flex flex-column gap-3 p-3 bg-white">
                            <h4 class="form-title">Card Details</h4>
                            <div class="d-flex flex-column gap-4">
                                <div class="form-divider"></div>
                                <div class="input-border">
                                    <label for="exampleFormControlInput1" class="form-label">Enter Card holder
                                        Name</label>
                                    <input type="email" class="form-input" id="exampleFormControlInput1"
                                        placeholder="name@example.com">
                                </div>
                                <div class="input-border">
                                    <label for="exampleFormControlInput1" class="form-label">Card Number</label>
                                    <input type="password" class="form-input" id="exampleFormControlInput1"
                                        placeholder="name@example.com">
                                </div>
                                <div class="input-border">
                                    <label for="exampleFormControlInput1" class="form-label">Expired Date</label>
                                    <input type="password" class="form-input" id="exampleFormControlInput1"
                                        placeholder="name@example.com">
                                </div>
                                <div class="input-border">
                                    <label for="exampleFormControlInput1" class="form-label">CVV</label>
                                    <input type="password" class="form-input" id="exampleFormControlInput1"
                                        placeholder="name@example.com">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                                    <label class="checkbox-label" for="checkDefault">
                                        Secure this card. <span>Why is it important?</span>
                                    </label>
                                </div>

                            </div>
                            <button class="enroll-btn">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
