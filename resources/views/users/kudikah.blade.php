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

            .form-header-text {
                color: #311507;
                font-size: 12px;
                font-weight: 600;
            }

            .toggle-btn {
                background-color: #CCDBDD;
                width: 24px;
                height: 15px;
                border: none;
            }

            /* Payment Method Modal Styles */
            .payment-method-modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                animation: fadeIn 0.3s ease-in-out;
            }

            .payment-method-modal.show {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .payment-method-content {
                background-color: #fff;
                padding: 30px;
                border-radius: 12px;
                max-width: 500px;
                width: 90%;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                animation: slideUp 0.3s ease-in-out;
            }

            .payment-method-header {
                font-size: 24px;
                font-weight: 700;
                color: #004A53;
                margin-bottom: 10px;
                font-family: 'fredoka';
            }

            .payment-method-subtitle {
                font-size: 14px;
                color: #666;
                margin-bottom: 25px;
            }

            .payment-method-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .payment-method-item {
                display: flex;
                align-items: center;
                padding: 16px;
                border: 2px solid #e0e0e0;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
                background-color: #f9f9f9;
            }

            .payment-method-item:hover {
                border-color: #004A53;
                background-color: #f0f8f9;
                transform: translateX(4px);
            }

            .payment-method-item.wallet {
                border-color: #FDAF22;
                background-color: #fffbf0;
            }

            .payment-method-item.wallet:hover {
                border-color: #FDAF22;
                background-color: #fff8e6;
            }

            .payment-method-icon {
                font-size: 28px;
                margin-right: 16px;
                width: 40px;
                text-align: center;
            }

            .payment-method-info {
                flex: 1;
            }

            .payment-method-name {
                font-weight: 600;
                color: #1c1d1d;
                font-size: 16px;
                margin-bottom: 4px;
            }

            .payment-method-desc {
                font-size: 13px;
                color: #999;
            }

            .payment-method-close {
                position: absolute;
                top: 15px;
                right: 15px;
                font-size: 28px;
                font-weight: bold;
                color: #999;
                cursor: pointer;
                border: none;
                background: none;
                padding: 0;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .payment-method-close:hover {
                color: #004A53;
            }

            .payment-method-content {
                position: relative;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes slideUp {
                from {
                    transform: translateY(30px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            /* Toast Notification Styles */
            .toast-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #28a745;
                color: white;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                z-index: 9999;
                animation: slideIn 0.3s ease-in-out;
                max-width: 400px;
            }

            .toast-notification.error {
                background-color: #dc3545;
            }

            .toast-notification.warning {
                background-color: #ffc107;
                color: #000;
            }

            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }

                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }

            .toast-notification.hide {
                animation: slideOut 0.3s ease-in-out forwards;
            }

            /* Loading spinner */
            .spinner-border {
                display: inline-block;
                width: 1rem;
                height: 1rem;
                vertical-align: text-bottom;
                border: 0.25em solid currentColor;
                border-right-color: transparent;
                border-radius: 50%;
                animation: spinner-border 0.75s linear infinite;
            }

            @keyframes spinner-border {
                to {
                    transform: rotate(360deg);
                }
            }

            /* Full Page Loader - Kokokah Style */
            .page-loader {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, rgba(0, 74, 83, 0.95) 0%, rgba(43, 104, 112, 0.95) 100%);
                z-index: 10000;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                gap: 30px;
            }

            .page-loader.active {
                display: flex;
            }

            .loader-spinner {
                width: 60px;
                height: 60px;
                border: 4px solid rgba(255, 255, 255, 0.2);
                border-top: 4px solid #FDAF22;
                border-right: 4px solid #FDAF22;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            .loader-text {
                color: #fff;
                font-size: 18px;
                font-weight: 600;
                text-align: center;
                font-family: 'fredoka';
            }

            .loader-subtext {
                color: rgba(255, 255, 255, 0.8);
                font-size: 14px;
                text-align: center;
            }

            .call-to-action-container {
                border: 1px solid #C4C4C4;
                padding: 14px;
                border-radius: 15px;
                max-width: 130px;
            }

            .icon-container {
                background-color: #CCDBDD;
                width: 50px;
                height: 50px;
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .call-action-text {
                color: #004A53;
                font-size: 12px;
            }

            @media screen and (min-width:768px) {
                .call-to-action-container {
                    padding: 14px 20px;
                }
            }
        </style>
        <!-- Toast Notification -->
        <div id="toastNotification" class="toast-notification" style="display: none;">
            <div class="toast-content">
                <span id="toastMessage"></span>
            </div>
        </div>

        <div class="modal fade" id="addCard" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h1 class="modal-title" id="modalTitle">Add New Card</h1>
                        <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </div>
                    <form class="modal-form-container" id="addCard">
                        <div class="modal-form">
                            <div class="modal-form-input-border">
                                <label for="levelName" class="modal-label">Enter Card holder Name</label>
                                <input class="modal-input" type="text" id="levelName" placeholder="e.g., JS1" required />
                            </div>
                            <div class="modal-form-input-border">
                                <label for="levelName" class="modal-label">Card Number</label>
                                <input class="modal-input" type="text" id="levelName" placeholder="e.g., JS1" required />
                            </div>
                            <div class="modal-form-input-border">
                                <label for="levelName" class="modal-label">Expired Date</label>
                                <input class="modal-input" type="text" id="levelName" placeholder="e.g., JS1" required />
                            </div>
                            <div class="modal-form-input-border">
                                <label for="levelName" class="modal-label">CVV</label>
                                <input class="modal-input" type="text" id="levelName" placeholder="e.g., JS1" required />
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="checkChecked" checked>
                                <label class="form-check-label" for="checkChecked">
                                    Secure this card.  <a href=''>Why is it important?</a>
                                </label>
                            </div>


                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn primaryButton">Save New Card</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Page Loader -->
        <div id="pageLoader" class="page-loader">
            <div class="loader-spinner"></div>
            <div class="loader-text">Processing Payment</div>
            <div class="loader-subtext">Please wait while we redirect you to the payment gateway...</div>
        </div>

        <section class="container-fluid px-2 py-4 px-lg-4">
            <div class="row g-4 mb-4">


                <div class="col-12 col-lg-7">

                    <div class="balance-header d-flex flex-column ">
                        <div>
                            <small class="opacity-75 balance-header-title">Total Balance</small>
                            <i class="fa-regular fa-eye" style="color:#fff; cursor: pointer;" id="toggleBalance"></i>
                        </div>
                        <h1 class="main-balance-text mb-4" id="walletBalance">₦0.00</h1>
                        <p class="balance-header-cardNo mb-0" id="cardNumber">Loading...</p>
                        {{-- <i class="bi bi-bank text-white fs-3 position-absolute" style="bottom: 20px; right: 20px; opacity: 0.5;"></i> --}}
                        <img src="./images/card-icon.png" alt="" class="position-absolute "
                            style="bottom:20px; right:60px;" />
                    </div>

                    <div class="d-flex align-items-center gap-3 justify-content-center">
                        <button class="call-to-action-container d-flex flex-column gap-2 align-items-center w-100 h-100">
                            <div class="icon-container"><i class="fa-solid fa-money-bill" style="color: #004A53;"></i></div>
                            <p class="call-action-text">Add Money</p>
                        </button>
                        <button class="call-to-action-container d-flex flex-column gap-2 align-items-center w-100 h-100">
                            <div class="icon-container"><i class="fa-solid fa-money-bill-transfer"
                                    style="color: #004A53;"></i></div>
                            <p class="call-action-text">Transfer Money</p>
                        </button>
                        <button class="call-to-action-container d-flex flex-column gap-2 align-items-center w-100 h-100">
                            <div class="icon-container"><i class="fa-solid fa-clipboard-list" style="color: #004A53;"></i>
                            </div>
                            <p class="call-action-text">Enroll Subject</p>
                        </button>

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
                                    <p style="font-size: 24px; color:#fff;" id="cardNumberDisplay">**** **** **** ****</p>
                                    <div class="d-flex flex-column">
                                        <p style="font-size: 14px; color:#fff;">Valid Till</p>
                                        <p style="font-size: 14px; color:#fff;" id="cardExpiry">MM/YY</p>
                                    </div>
                                </div>
                                <p style="font-size: 24px; color:#fff;" id="cardHolderName">Card Holder Name</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button data-bs-toggle="modal" data-bs-target="#addCard"
                                class="call-to-action-container d-flex flex-column gap-2 align-items-center w-100 h-100">
                                <div class="icon-container"><i class="fa-solid fa-plus" style="color: #004A53;"></i>
                                </div>
                                <p class="call-action-text">Add Card</p>
                            </button>
                            <button
                                class="call-to-action-container d-flex flex-column gap-2 align-items-center w-100 h-100">
                                <div class="icon-container"><i class="fa-solid fa-pen-to-square"
                                        style="color: #004A53;"></i></div>
                                <p class="call-action-text">Edit Card</p>
                            </button>
                            <button
                                class="call-to-action-container d-flex flex-column gap-2 align-items-center w-100 h-100">
                                <div class="icon-container" style="background-color: #FFE6E6;"><i
                                        class="fa-solid fa-xmark" style="color: #FF383C;"></i></div>
                                <p class="call-action-text">Delete Card</p>
                            </button>

                        </div>
                        {{-- <button class="addmoney-btn" id="editCardBtn">Edit</button> --}}
                    </div>
                    {{-- <form id="cardDetailsForm" class="rounded-2 overflow-hidden shadow-sm">
                        <div class="p-3 d-flex justify-content-between" style="background-color: #F89A6D;">
                            <p class="form-header-text">Add a new payment method</p>
                            <button type="button" class="toggle-btn d-flex justify-content-center align-items-center"><i class="fa-solid fa-chevron-down fa-sm"></i></button>
                        </div>
                        <div class="d-flex flex-column gap-3 p-3 bg-white">
                            <h4 class="form-title">Card Details</h4>
                            <div class="d-flex flex-column gap-4">
                                <div class="form-divider"></div>
                                <div class="input-border">
                                    <label for="formCardHolderName" class="form-label">Enter Card holder Name</label>
                                    <input type="text" class="form-input" id="formCardHolderName"
                                        placeholder="John Doe" required>
                                    <small class="text-danger d-none" id="cardHolderNameError"></small>
                                </div>
                                <div class="input-border">
                                    <label for="formCardNumber" class="form-label">Card Number</label>
                                    <input type="text" class="form-input" id="formCardNumber"
                                        placeholder="1234 5678 9012 3456" inputmode="numeric" required>
                                    <small class="text-danger d-none" id="cardNumberError"></small>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="input-border">
                                            <label for="formExpiryDate" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-input" id="formExpiryDate"
                                                placeholder="MM/YY" inputmode="numeric" required>
                                            <small class="text-danger d-none" id="expiryDateError"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-border">
                                            <label for="formCvv" class="form-label">CVV</label>
                                            <input type="password" class="form-input" id="formCvv"
                                                placeholder="123" inputmode="numeric" maxlength="4" required>
                                            <small class="text-danger d-none" id="cvvError"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="isDefault">
                                    <label class="checkbox-label" for="isDefault">
                                        Set as default payment method
                                    </label>
                                </div>

                            </div>
                            <button type="submit" class="enroll-btn" id="saveCardBtn">Save Card</button>
                        </div>

                    </form> --}}
                </div>
            </div>
            <div>
                <div class="bg-white rounded-3 shadow-sm">
                    <div
                        class="d-flex flex-column flex-md-row gap-3 align-items-start align-items-md-center justify-content-between p-3">
                        <h5 class="fw-bold">Transaction History</h5>

                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false" id="categoryFilter">
                                    All Categories
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"
                                            onclick="filterTransactions('all')">All</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="filterTransactions('transfer')">Transfers</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="filterTransactions('deposit')">Deposits</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="filterTransactions('purchase')">Purchases</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <button class="btn filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false" id="statusFilter">
                                    All Status
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"
                                            onclick="filterTransactions('all', 'all')">All</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="filterTransactions('all', 'completed')">Completed</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="filterTransactions('all', 'pending')">Pending</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="filterTransactions('all', 'failed')">Failed</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="transaction-list p-3" id="transactionList">
                        <div style="text-align: center; padding: 40px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3">Loading transactions...</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <!-- Amount Input Modal -->
        <div id="amountModal" class="payment-method-modal">
            <div class="payment-method-content">
                <button class="payment-method-close" onclick="closeAmountModal()">&times;</button>
                <h2 class="payment-method-header">Add Money to Wallet</h2>
                <p class="payment-method-subtitle">Enter the amount you want to add</p>

                <div style="padding: 20px 0;">
                    <div class="input-border">
                        <label for="depositAmount" class="form-label">Amount (₦)</label>
                        <input type="number" class="form-input" id="depositAmount" placeholder="Enter amount"
                            min="100" step="100" required>
                        <small class="text-danger d-none" id="amountErrorMsg"></small>
                    </div>
                    <div
                        style="margin-top: 15px; padding: 12px; background-color: #f0f8f9; border-radius: 8px; border-left: 4px solid #004A53;">
                        <small style="color: #666;">Minimum amount: ₦100</small>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 20px;">
                    <button type="button" class="addmoney-btn" style="flex: 1; background: white;"
                        onclick="closeAmountModal()">Cancel</button>
                    <button type="button" class="enroll-btn" style="flex: 1;"
                        onclick="proceedToGatewaySelection()">Continue</button>
                </div>
            </div>
        </div>

        <!-- Payment Gateway Modal for Adding Money to Wallet -->
        <div id="paymentGatewayModal" class="payment-method-modal">
            <div class="payment-method-content">
                <button class="payment-method-close" onclick="closePaymentGatewayModal()">&times;</button>
                <h2 class="payment-method-header">Select Payment Method</h2>
                <p class="payment-method-subtitle">Choose your preferred payment gateway</p>

                <div class="payment-method-list">
                    <!-- Paystack -->
                    <div class="payment-method-item" onclick="selectPaymentGateway('paystack')">
                        <div class="payment-method-icon">
                            <img src="./images/paystack.png" alt="Paystack"
                                style="height: 40px; width: auto; object-fit: contain;">
                        </div>
                        <div class="payment-method-info">
                            <div class="payment-method-name">Paystack</div>
                            <div class="payment-method-desc">Fast and secure payment</div>
                        </div>
                    </div>

                    <!-- Flutterwave -->
                    <div class="payment-method-item" onclick="selectPaymentGateway('flutterwave')">
                        <div class="payment-method-icon">
                            <img src="./images/Flutterwave.png" alt="Flutterwave"
                                style="height: 40px; max-width: 40px; width: auto; object-fit: contain;">
                        </div>
                        <div class="payment-method-info">
                            <div class="payment-method-name">Flutterwave</div>
                            <div class="payment-method-desc">Multiple payment options</div>
                        </div>
                    </div>

                    <!-- Stripe -->
                    <div class="payment-method-item" onclick="selectPaymentGateway('stripe')">
                        <div class="payment-method-icon">
                            <img src="./images/stripe.webp" alt="Stripe"
                                style="height: 40px; width: auto; object-fit: contain;">
                        </div>
                        <div class="payment-method-info">
                            <div class="payment-method-name">Stripe</div>
                            <div class="payment-method-desc">International payments</div>
                        </div>
                    </div>

                    <!-- PayPal -->
                    <div class="payment-method-item" onclick="selectPaymentGateway('paypal')">
                        <div class="payment-method-icon">
                            <img src="./images/paypal.png" alt="PayPal"
                                style="height: 40px; width: auto; object-fit: contain;">
                        </div>
                        <div class="payment-method-info">
                            <div class="payment-method-name">PayPal</div>
                            <div class="payment-method-desc">Secure online payment</div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 20px;">
                    <button type="button" class="addmoney-btn" style="flex: 1; background: white;"
                        onclick="closePaymentGatewayModal()">Cancel</button>
                    <button type="button" class="enroll-btn" style="flex: 1;"
                        onclick="proceedWithGateway()">Continue</button>
                </div>
            </div>
        </div>
    </main>

    <!-- JS: Bootstrap + API integration -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- API Clients -->
    <script>
        let currentTypeFilter = 'all';
        let currentStatusFilter = 'all';
        let currentCard = null; // Store current displayed card for editing

        // Initialize page on load
        document.addEventListener('DOMContentLoaded', async () => {
            // Hide loader in case user cancelled payment and came back
            hidePageLoader();

            await loadWalletData();
            await loadTransactions();
            setupEventListeners();
        });

        /**
         * Load wallet data (balance, stats, etc.)
         */
        async function loadWalletData() {
            try {
                const result = await WalletApiClient.getWallet();

                if (result.success && result.data) {
                    const data = result.data;

                    // Update balance
                    const balance = data.balance || 0;
                    document.getElementById('walletBalance').textContent = formatNGN(balance);
                } else {
                    showToast('Failed to load wallet data', 'error');
                }

                // Load saved payment methods and display default card
                await loadAndDisplayPaymentMethods();

            } catch (error) {
                console.error('Error loading wallet data:', error);
                showToast('Error loading wallet data: ' + error.message, 'error');
            }
        }

        /**
         * Load payment methods and display the default one on the card
         */
        async function loadAndDisplayPaymentMethods() {
            try {
                const result = await WalletApiClient.getPaymentMethods();

                if (result.success && result.data && result.data.length > 0) {
                    // Find the default payment method
                    const defaultMethod = result.data.find(method => method.is_default);

                    if (defaultMethod) {
                        // Display the default card
                        displayCardDetails(defaultMethod);
                    } else {
                        // If no default, display the first card
                        displayCardDetails(result.data[0]);
                    }
                } else {
                    // No saved cards, show placeholder
                    displayCardPlaceholder();
                }
            } catch (error) {
                console.error('Error loading payment methods:', error);
                displayCardPlaceholder();
            }
        }

        /**
         * Display card details on the card display
         */
        function displayCardDetails(card) {
            // Store current card for editing
            currentCard = card;

            // Display masked card number
            const maskedCard = card.masked_card || `**** **** **** ${card.card_last_four}`;
            document.getElementById('cardNumberDisplay').textContent = maskedCard;
            document.getElementById('cardNumber').textContent = maskedCard;

            // Display card holder name
            const cardHolderName = card.card_holder_name || 'User Name';
            document.getElementById('cardHolderName').textContent = cardHolderName;

            // Display card expiry
            const cardExpiry = card.expiry_date || 'MM/YY';
            document.getElementById('cardExpiry').textContent = cardExpiry;
        }

        /**
         * Display placeholder when no cards are saved
         */
        function displayCardPlaceholder() {
            document.getElementById('cardNumberDisplay').textContent = '**** **** **** ****';
            document.getElementById('cardNumber').textContent = '**** **** **** ****';
            document.getElementById('cardHolderName').textContent = 'User Name';
            document.getElementById('cardExpiry').textContent = 'MM/YY';
        }

        /**
         * Load transactions
         */
        async function loadTransactions() {
            try {
                const filters = {
                    type: currentTypeFilter === 'all' ? null : currentTypeFilter,
                    status: currentStatusFilter === 'all' ? null : currentStatusFilter,
                    per_page: 50
                };

                const result = await WalletApiClient.getTransactions(filters);

                if (result.success && result.data) {
                    displayTransactions(result.data);
                } else {
                    showToast('Failed to load transactions', 'error');
                }
            } catch (error) {
                console.error('Error loading transactions:', error);
                showToast('Error loading transactions: ' + error.message, 'error');
            }
        }

        /**
         * Display transactions in the UI
         */
        function displayTransactions(transactions) {
            const transactionList = document.getElementById('transactionList');

            if (!transactions || transactions.length === 0) {
                transactionList.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <p class="text-muted">No transactions found</p>
                    </div>
                `;
                return;
            }

            let html = '';
            transactions.forEach((transaction, index) => {
                const isLast = index === transactions.length - 1;
                const borderStyle = isLast ? 'border-bottom: none;' : '';

                const icon = getTransactionIcon(transaction.type);
                const description = getTransactionDescription(transaction);
                const amount = transaction.amount || 0;
                const amountClass = transaction.type === 'debit' ? 'text-danger' : 'text-success';
                const amountSign = transaction.type === 'debit' ? '-' : '+';

                const date = new Date(transaction.created_at).toLocaleString('en-NG', {
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

                html += `
                    <div class="transaction-item " style="${borderStyle}">
                        <div class="transaction-details">
                            <div class="transaction-icon-container">
                                ${icon}
                            </div>
                            <div>
                                <h6 class="d-block">${description}</h6>
                                <p class="text-muted">${date}</p>
                            </div>
                        </div>
                        <p class="transaction-amount ${amountClass}">${amountSign}${formatNGN(amount)}</p>
                    </div>
                `;
            });

            transactionList.innerHTML = html;
        }

        /**
         * Get transaction icon based on type
         */
        function getTransactionIcon(type) {
            const icons = {
                'transfer': '<i class="bi bi-arrow-left-right"></i>',
                'deposit': '<i class="bi bi-arrow-down"></i>',
                'purchase': '<i class="bi bi-bag"></i>',
                'reward': '<i class="bi bi-gift"></i>',
                'debit': '<i class="bi bi-arrow-up"></i>'
            };
            return icons[type] || '<i class="bi bi-arrow-left-right"></i>';
        }

        /**
         * Get transaction description
         */
        function getTransactionDescription(transaction) {
            const descriptions = {
                'transfer': `Transfer to ${transaction.recipient_name || 'User'}`,
                'deposit': `Deposit via ${transaction.gateway || 'Payment Gateway'}`,
                'purchase': `Course Purchase - ${transaction.course_name || 'Course'}`,
                'reward': `Login Reward`,
                'debit': `Debit - ${transaction.description || 'Transaction'}`
            };
            return descriptions[transaction.type] || transaction.description || 'Transaction';
        }

        /**
         * Filter transactions
         */
        window.filterTransactions = async function(type, status) {
            if (type !== undefined && type !== 'all') {
                currentTypeFilter = type;
            }
            if (status !== undefined && status !== 'all') {
                currentStatusFilter = status;
            }
            await loadTransactions();
        };

        /**
         * Setup event listeners
         */
        function setupEventListeners() {
            // Add Money button - Show amount input modal first
            document.getElementById('addMoneyBtn').addEventListener('click', () => {
                openAmountModal();
            });

            // Enroll Class button
            document.getElementById('enrollClassBtn').addEventListener('click', () => {
                window.location.href = '/userclass';
            });

            // Edit Card button
            document.getElementById('editCardBtn').addEventListener('click', () => {
                if (currentCard) {
                    populateCardForm(currentCard);
                    // Scroll to form
                    document.getElementById('cardDetailsForm').scrollIntoView({
                        behavior: 'smooth'
                    });
                } else {
                    showToast('No card to edit. Please save a card first.', 'warning');
                }
            });

            // Toggle balance visibility
            document.getElementById('toggleBalance').addEventListener('click', () => {
                const balance = document.getElementById('walletBalance');
                if (balance.textContent === '₦0.00') {
                    balance.textContent = '••••••';
                } else {
                    loadWalletData();
                }
            });

            // Card Details Form submission
            const cardForm = document.getElementById('cardDetailsForm');
            if (cardForm) {
                cardForm.addEventListener('submit', handleSaveCard);
            }

            // Format card number input
            const cardNumberInput = document.getElementById('formCardNumber');
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', formatCardNumber);
            }

            // Format expiry date input
            const expiryInput = document.getElementById('formExpiryDate');
            if (expiryInput) {
                expiryInput.addEventListener('input', formatExpiryDate);
            }
        }

        /**
         * Open amount input modal
         */
        function openAmountModal() {
            const modal = document.getElementById('amountModal');
            if (modal) {
                modal.classList.add('show');
                document.getElementById('depositAmount').focus();
            }
        }

        /**
         * Close amount input modal
         */
        function closeAmountModal() {
            const modal = document.getElementById('amountModal');
            if (modal) {
                modal.classList.remove('show');
            }
            // Reset form
            document.getElementById('depositAmount').value = '';
            document.getElementById('amountErrorMsg').classList.add('d-none');
        }

        /**
         * Open payment gateway modal
         */
        function openPaymentGatewayModal() {
            const modal = document.getElementById('paymentGatewayModal');
            if (modal) {
                modal.classList.add('show');
            }
        }

        /**
         * Close payment gateway modal
         */
        function closePaymentGatewayModal() {
            const modal = document.getElementById('paymentGatewayModal');
            if (modal) {
                modal.classList.remove('show');
            }
            // Reset gateway selection
            modal.dataset.selectedGateway = '';
            document.querySelectorAll('#paymentGatewayModal .payment-method-item').forEach(item => {
                item.style.borderColor = '#e0e0e0';
                item.style.backgroundColor = '#f9f9f9';
            });
        }

        /**
         * Proceed from amount modal to gateway selection
         */
        function proceedToGatewaySelection() {
            const amountInput = document.getElementById('depositAmount');
            const amount = parseFloat(amountInput.value);
            const amountError = document.getElementById('amountErrorMsg');

            // Validate amount
            if (!amount || amount < 100) {
                amountError.textContent = 'Amount must be at least ₦100';
                amountError.classList.remove('d-none');
                return;
            }

            // Store amount in session/data attribute
            const modal = document.getElementById('paymentGatewayModal');
            modal.dataset.depositAmount = amount;

            amountError.classList.add('d-none');

            // Close amount modal and open gateway modal
            closeAmountModal();
            openPaymentGatewayModal();
        }

        /**
         * Handle payment gateway selection
         */
        function selectPaymentGateway(gateway) {
            const modal = document.getElementById('paymentGatewayModal');
            modal.dataset.selectedGateway = gateway;

            // Update UI to show selected gateway
            document.querySelectorAll('#paymentGatewayModal .payment-method-item').forEach(item => {
                item.style.borderColor = '#e0e0e0';
                item.style.backgroundColor = '#f9f9f9';
            });

            event.currentTarget.style.borderColor = '#004A53';
            event.currentTarget.style.backgroundColor = '#f0f8f9';
        }

        /**
         * Proceed with selected payment gateway to add money
         */
        async function proceedWithGateway() {
            const modal = document.getElementById('paymentGatewayModal');
            const selectedGateway = modal.dataset.selectedGateway;
            const depositAmount = parseFloat(modal.dataset.depositAmount);

            if (!selectedGateway) {
                showToast('Please select a payment gateway', 'warning');
                return;
            }

            if (!depositAmount || depositAmount < 100) {
                showToast('Invalid amount. Please enter at least ₦100', 'warning');
                return;
            }

            try {
                // Show loading state
                const continueBtn = event.target;
                const originalText = continueBtn.textContent;
                continueBtn.disabled = true;
                continueBtn.textContent = 'Processing...';

                console.log('Initializing wallet deposit:', {
                    amount: depositAmount,
                    gateway: selectedGateway,
                    currency: 'NGN'
                });

                // Initialize wallet deposit with selected gateway
                const result = await PaymentApiClient.initializeWalletDeposit({
                    amount: depositAmount,
                    gateway: selectedGateway,
                    currency: 'NGN'
                });

                console.log('Payment initialization response:', result);

                if (result.success && result.data) {
                    // Close modal
                    closePaymentGatewayModal();

                    // Redirect to payment gateway
                    const authUrl = result.data.gateway_data?.authorization_url || result.data.gateway_data?.link;
                    if (authUrl) {
                        // Show page loader to prevent user interaction
                        showPageLoader();
                        setTimeout(() => {
                            window.location.href = authUrl;
                        }, 500);
                    } else {
                        showToast('Payment gateway URL not found', 'error');
                        continueBtn.disabled = false;
                        continueBtn.textContent = originalText;
                    }
                } else {
                    showToast(result.message || 'Failed to initialize payment', 'error');
                    continueBtn.disabled = false;
                    continueBtn.textContent = originalText;
                }
            } catch (error) {
                console.error('Payment initialization error:', error);
                showToast('Error initializing payment: ' + error.message, 'error');
                const continueBtn = event.target;
                continueBtn.disabled = false;
                continueBtn.textContent = 'Continue';
            }
        }

        /**
         * Format card number with spaces (1234 5678 9012 3456)
         */
        function formatCardNumber(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = '';

            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }

            e.target.value = formattedValue;
        }

        /**
         * Format expiry date (MM/YY)
         */
        function formatExpiryDate(e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }

            e.target.value = value;
        }

        /**
         * Populate card form with current card details for editing
         */
        function populateCardForm(card) {
            // Populate form fields with card data
            document.getElementById('formCardHolderName').value = card.card_holder_name || '';

            // For card number, we can't show the full number (it's encrypted)
            // So we'll show a message that they need to enter the full card number
            document.getElementById('formCardNumber').value = '';
            document.getElementById('formCardNumber').placeholder = 'Enter full card number to update';

            // Populate expiry date
            document.getElementById('formExpiryDate').value = card.expiry_date || '';

            // CVV field should be empty for security
            document.getElementById('formCvv').value = '';
            document.getElementById('formCvv').placeholder = 'Enter CVV to update';

            // Set default checkbox
            document.getElementById('isDefault').checked = card.is_default || false;

            // Update form header and button text
            document.querySelector('.form-header-text').textContent = 'Update Payment Method';
            document.getElementById('saveCardBtn').textContent = 'Update Card';
            document.getElementById('saveCardBtn').dataset.cardId = card.id;
        }

        /**
         * Handle save card form submission (both add and update)
         */
        async function handleSaveCard(e) {
            e.preventDefault();

            // Get form values
            const cardHolderName = document.getElementById('formCardHolderName').value.trim();
            const cardNumber = document.getElementById('formCardNumber').value.replace(/\s/g, '');
            const expiryDate = document.getElementById('formExpiryDate').value.trim();
            const cvv = document.getElementById('formCvv').value.trim();
            const isDefault = document.getElementById('isDefault').checked;
            const saveBtn = document.getElementById('saveCardBtn');
            const cardId = saveBtn.dataset.cardId;

            // Check if this is an update or add
            const isUpdate = cardId && cardId !== '';

            // For updates, card number and CVV are optional (user can keep existing)
            if (!isUpdate) {
                // For new cards, validate all fields
                if (!validateCardForm(cardHolderName, cardNumber, expiryDate, cvv)) {
                    return;
                }
            } else {
                // For updates, at least card holder name is required
                if (!cardHolderName) {
                    showToast('Card holder name is required', 'error');
                    return;
                }
            }

            try {
                // Show loading state
                const originalText = saveBtn.textContent;
                saveBtn.disabled = true;
                saveBtn.textContent = isUpdate ? 'Updating...' : 'Saving...';

                // Prepare payload
                const payload = {
                    card_holder_name: cardHolderName,
                    expiry_date: expiryDate,
                    is_default: isDefault
                };

                // Only include card number and CVV if provided
                if (cardNumber) {
                    payload.card_number = cardNumber;
                }
                if (cvv) {
                    payload.cvv = cvv;
                }

                // Call API to save or update card
                const result = await WalletApiClient.addPaymentMethod(payload);

                if (result.success) {
                    const message = isUpdate ? 'Card updated successfully!' : 'Card saved successfully!';
                    showToast(message, 'success');

                    // Reset form and clear edit mode
                    resetCardForm();

                    // Reload wallet data to show updated card info
                    await loadWalletData();

                    // Optionally reload payment methods list
                    // await loadPaymentMethods();
                } else {
                    showToast(result.message || 'Failed to save card', 'error');
                }
            } catch (error) {
                console.error('Error saving card:', error);
                showToast('Error saving card: ' + error.message, 'error');
            } finally {
                // Restore button state
                const saveBtn = document.getElementById('saveCardBtn');
                saveBtn.disabled = false;
                saveBtn.textContent = 'Save Card';
            }
        }

        /**
         * Reset card form to initial state
         */
        function resetCardForm() {
            // Reset form fields
            document.getElementById('cardDetailsForm').reset();

            // Reset form header and button text
            document.querySelector('.form-header-text').textContent = 'Add a new payment method';
            const saveBtn = document.getElementById('saveCardBtn');
            saveBtn.textContent = 'Save Card';
            delete saveBtn.dataset.cardId;

            // Reset placeholders
            document.getElementById('formCardNumber').placeholder = '1234 5678 9012 3456';
            document.getElementById('formCvv').placeholder = '123';

            // Clear current card
            currentCard = null;
        }

        /**
         * Validate card form inputs
         */
        function validateCardForm(cardHolderName, cardNumber, expiryDate, cvv) {
            let isValid = true;

            // Clear previous errors
            document.getElementById('cardHolderNameError').classList.add('d-none');
            document.getElementById('cardNumberError').classList.add('d-none');
            document.getElementById('expiryDateError').classList.add('d-none');
            document.getElementById('cvvError').classList.add('d-none');

            // Validate cardholder name
            if (!cardHolderName || cardHolderName.length < 3) {
                showError('cardHolderNameError', 'Cardholder name must be at least 3 characters');
                isValid = false;
            }

            // Validate card number (13-19 digits)
            if (!cardNumber || !/^\d{13,19}$/.test(cardNumber)) {
                showError('cardNumberError', 'Card number must be 13-19 digits');
                isValid = false;
            }

            // Validate expiry date (MM/YY format)
            if (!expiryDate || !/^\d{2}\/\d{2}$/.test(expiryDate)) {
                showError('expiryDateError', 'Expiry date must be in MM/YY format');
                isValid = false;
            } else {
                // Check if expiry date is valid
                const [month, year] = expiryDate.split('/');
                const monthNum = parseInt(month);
                if (monthNum < 1 || monthNum > 12) {
                    showError('expiryDateError', 'Invalid month (01-12)');
                    isValid = false;
                }
            }

            // Validate CVV (3-4 digits)
            if (!cvv || !/^\d{3,4}$/.test(cvv)) {
                showError('cvvError', 'CVV must be 3-4 digits');
                isValid = false;
            }

            return isValid;
        }

        /**
         * Show validation error
         */
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.classList.remove('d-none');
        }

        /**
         * Format number as NGN currency
         */
        function formatNGN(n) {
            n = Number(n) || 0;
            return '₦' + n.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        /**
         * Show toast notification
         */
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toastNotification');
            const toastMessage = document.getElementById('toastMessage');

            toastMessage.textContent = message;
            toast.className = `toast-notification ${type}`;
            toast.style.display = 'block';

            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.style.display = 'none';
                    toast.classList.remove('hide');
                }, 300);
            }, 3000);
        }

        /**
         * Show page loader overlay
         */
        function showPageLoader() {
            const loader = document.getElementById('pageLoader');
            if (loader) {
                loader.classList.add('active');
            }
        }

        /**
         * Hide page loader overlay
         */
        function hidePageLoader() {
            const loader = document.getElementById('pageLoader');
            if (loader) {
                loader.classList.remove('active');
            }
        }
    </script>
@endsection
