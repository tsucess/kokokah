@extends('layouts.usertemplate')
<style>
    /* Global font & background */
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: #ffffff;
        color: #222;
        padding: 28px 12px;
    }
    .enroll-btn{
        background-color: #FDAF22;
        padding: 16px 20px;
        color: #000F11;
        font-size: 16px;
        font-weight: 600;
        border-radius: 4px;
    }

    /* Outer card/container */
    .txn-card {
        max-width: 1120px;
        /* wide like screenshot */
        margin: 0 auto;
        border-radius: 6px;
        border: 1px solid #e9e9e9;
        /* subtle border */
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.02);
    }

    /* Header row */
    .txn-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 22px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #333;
        background: #fff;
    }

    /* small right controls */
    .txn-controls .form-select {
        min-height: calc(1.5em + .75rem + 2px);
        padding-top: .25rem;
        padding-bottom: .25rem;
        font-size: 13px;
        border-radius: 6px;
        /* border: 1px solid #e6e6e6;
      background: #f8f9fa; */
    }

    .txn-controls .btn-filter {
        padding: .25rem .5rem;
        font-size: 13px;
    }

    /* List area */
    .txn-list {
        min-height: 420px;
        /* tall list like screenshot */
        background: #fff;
    }

    .txn-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 22px;
        border-bottom: 1px solid #f2f2f2;
        font-size: 14px;
    }

    /* Left column contains checkbox + label */
    .txn-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1 1 auto;
        white-space: nowrap;
        overflow: hidden;
    }

    /* Make checkbox small and vertically centered */
    .txn-left .form-check-input {
        width: 16px;
        height: 16px;
        margin-top: 0;
    }

    /* Subject text */
    .subject {
        font-size: 14px;
        color: #222;
        /* dark text similar to screenshot */
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Price on the right */
    .txn-price {
        min-width: 110px;
        text-align: right;
        font-weight: 600;
        color: #0b0b0b;
        font-size: 14px;
    }

    /* Last row (no border) */
    .txn-row:last-child {
        border-bottom: none;
    }

    /* Payment footer */
    .txn-footer {
        padding: 18px 22px;
        border-top: 1px solid #eee;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .proceed-payment-btn{
        border: 1px solid #004A53;
        border-radius: 4px;
        padding: 16px 20px;
        color: #004A53;
        font-size: 16px;
        font-weight: 600;
        background-color: transparent;
    }



    /* Subtotal text style inside button */
    .subtotal {
        font-weight: 700;
        margin-left: 8px;
    }

    /* Small screens — stack price under label */
    @media (max-width: 576px) {
        .txn-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .txn-price {
            text-align: left;
            margin-top: 8px;
        }

        .txn-header {
            padding: 12px 14px;
        }

        .txn-row {
            padding: 12px 14px;
        }

        .txn-footer {
            padding: 16px 14px;
        }
    }
</style>
@section('content')
    <main>
        <section class="container-fluid p-4 d-flex flex-column gap-4">
            <div class ="d-flex  justify-content-between align-items-center">
                <h1>Junior Secondary School (JSSS 1)</h1>
                <button class = "enroll-btn" type = "button">Enroll in All 12 Subjects - ₦‎9,000</button>
            </div>
            <div class="txn-card w-100">

                <!-- Header -->
                <div class="txn-header ">
                    <div class="header-title">Transaction History</div>

                    <div class="txn-controls d-flex align-items-center gap-2">
                        <!-- small dropdowns on the right like the screenshot -->
                        <select class="form-select form-select-sm" aria-label="Category select" style="width:165px;">
                            <option selected>All Categories</option>
                            <option>Science</option>
                            <option>Arts</option>
                        </select>

                        <select class="form-select form-select-sm" aria-label="Status select" style="width:140px;">
                            <option selected>All Status</option>
                            <option>Selected</option>
                            <option>Not selected</option>
                        </select>
                    </div>
                </div>

                <!-- List -->
                <div class="txn-list">

                    <!-- Example rows (duplicate as needed) -->
                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb1">

                             <label for="cb1" class="subject">Mathematics</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb2">

                            <label for="cb2" class="subject">English</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb3">
                            <label for="cb3" class="subject">Basic Science</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb4">
                            <div class="subject">Basic Technology</div>
                            <label for="cb4" class="subject">Basic Technology</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb5">
                            <label for="cb5" class="subject">Social Studies</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb6">
                            <label for="cb6" class="subject">Civic Education</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb7">
                            <label for="cb7" class="subject">Agricultural Science</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb8">
                            <label for="cb8" class="subject">Computer Studies</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb9">
                            <label for="cb9" class="subject">Business Studies</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb10">
                            <label for="cb10" class="subject">Physical &amp; Health Education</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb11">
                            <label for="cb11" class="subject">CRS / IRS</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                    <div class="txn-row">
                        <div class="txn-left">
                                <input class="form-check-input check-subject" type="checkbox" data-price="9000"
                                    id="cb12">
                            <label for="cb12" class="subject">French / Yoruba / Igbo</label>
                        </div>
                        <div class="txn-price">₦9,000.00</div>
                    </div>

                </div>




            </div>
            <!-- Footer with proceed button -->

                    <button id="proceedBtn" class="proceed-payment-btn align-self-center">
                        Proceed to Payment - Subtotal: <span id="subtotal" class="subtotal">₦0.00</span>
                    </button>

        </section>
    </main>

    <!-- JS: bootstrap + small script to update subtotal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module">
        import CourseApiClient from '{{ asset("js/api/courseApiClient.js") }}';
        import ToastNotification from '{{ asset("js/utils/toastNotification.js") }}';

        let allCourses = [];

        document.addEventListener("DOMContentLoaded", async () => {
            await loadCourses();
            setupBackButton();
        });

        /**
         * Setup back button functionality
         */
        function setupBackButton() {
            const backBtn = document.getElementById('backBtn');
            if (backBtn) {
                backBtn.addEventListener('click', () => {
                    window.history.back();
                });
            }
        }

        /**
         * Format numbers as NGN currency
         */
        function formatNGN(n) {
            // ensure number
            n = Number(n) || 0;
            return '₦' + n.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Calculate subtotal when any checkbox toggled
        function updateSubtotal() {
            const checks = document.querySelectorAll('.check-subject');
            let total = 0;
            checks.forEach(cb => {
                if (cb.checked) {
                    const p = Number(cb.dataset.price) || 0;
                    total += p;
                }
            });
            document.getElementById('subtotal').textContent = formatNGN(total);
        }

        // Attach listeners
        document.querySelectorAll('.check-subject').forEach(cb => {
            cb.addEventListener('change', updateSubtotal);
        });

            // Apply 10% discount
            const discountAmount = totalPrice * 0.10;
            const discountedPrice = totalPrice - discountAmount;

            const courseCount = allCourses.length;
            document.getElementById('enrollAllBtn').textContent =
                `Enroll in All ${courseCount} Subjects - ${formatNGN(discountedPrice)}`;
        }

        /**
         * Show error message
         */
        function showError(message) {
            const coursesList = document.getElementById('coursesList');
            coursesList.innerHTML = `<div class="txn-row"><div class="txn-left"><p class="text-danger">${message}</p></div></div>`;
            ToastNotification.error('Error', message);
        }

        /**
         * Store payment data for modal confirmation
         */
        let pendingPaymentData = null;

        /**
         * Handle proceed to payment button
         */
        document.getElementById('proceedBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const checked = document.querySelectorAll('.check-subject:checked');

            if (checked.length === 0) {
                ToastNotification.warning('No Selection', 'Please select at least one subject to proceed.');
                return;
            }

            // Store payment data and open modal
            const selectedCourses = Array.from(checked).map(cb => cb.dataset.courseId);
            const subtotal = document.getElementById('subtotal').textContent;
            const enrollAllBtn = document.getElementById('enrollAllBtn');
            const enrollAllPrice = extractPrice(enrollAllBtn.textContent);

            pendingPaymentData = {
                courses: selectedCourses,
                subtotal: subtotal,
                enrollAllPrice: enrollAllPrice,
                courseCount: checked.length
            };

            openPaymentModal();
        });

        /**
         * Handle "Enroll in All" button
         */
        document.getElementById('enrollAllBtn').addEventListener('click', function(e) {
            e.preventDefault();
            // Select all checkboxes
            document.querySelectorAll('.check-subject').forEach(cb => {
                cb.checked = true;
            });
            updateSubtotal();

            // Open payment modal
            const allCourses = document.querySelectorAll('.check-subject');
            const selectedCourses = Array.from(allCourses).map(cb => cb.dataset.courseId);
            const enrollAllBtn = document.getElementById('enrollAllBtn');
            const enrollAllPrice = extractPrice(enrollAllBtn.textContent);

            pendingPaymentData = {
                courses: selectedCourses,
                subtotal: formatNGN(enrollAllPrice),
                enrollAllPrice: enrollAllPrice,
                courseCount: selectedCourses.length
            };

            openPaymentModal();
        });

        /**
         * Open payment gateway modal
         */
        function openPaymentModal() {
            const modal = document.getElementById('paymentGatewayModal');
            modal.style.display = 'flex';
            // Reset gateway selection to Kudikah
            document.getElementById('gateway-kudikah').checked = true;
        }

        /**
         * Close payment gateway modal
         */
        function closePaymentModal() {
            const modal = document.getElementById('paymentGatewayModal');
            modal.style.display = 'none';
            pendingPaymentData = null;
        }

        /**
         * Handle modal close button
         */
        document.getElementById('closePaymentModal').addEventListener('click', closePaymentModal);

        /**
         * Handle modal cancel button
         */
        document.getElementById('cancelPaymentModal').addEventListener('click', closePaymentModal);

        /**
         * Handle modal confirm button
         */
        document.getElementById('confirmPaymentModal').addEventListener('click', function(e) {
            e.preventDefault();

            if (!pendingPaymentData) {
                ToastNotification.error('Error', 'Payment data not found. Please try again.');
                return;
            }

            // Get selected payment gateway from modal
            const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked');
            if (!selectedGateway) {
                ToastNotification.warning('No Selection', 'Please select a payment method.');
                return;
            }

            const gateway = selectedGateway.value;
            const paymentData = {
                ...pendingPaymentData,
                gateway: gateway
            };

            console.log('Payment Data:', paymentData);

            // Close modal and route to payment gateway
            closePaymentModal();
            routeToPaymentGateway(gateway, paymentData);
        });

        /**
         * Close modal when clicking outside of it
         */
        document.getElementById('paymentGatewayModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });

        /**
         * Extract price from text (e.g., "₦14,850.00" -> 14850)
         */
        function extractPrice(text) {
            const match = text.match(/₦([\d,]+\.?\d*)/);
            if (match) {
                return parseFloat(match[1].replace(/,/g, ''));
            }
            return 0;
        }

        /**
         * Route to appropriate payment gateway
         */
        function routeToPaymentGateway(gateway, paymentData) {
            switch(gateway) {
                case 'kudikah':
                    processKudikahPayment(paymentData);
                    break;
                case 'paystack':
                    processPaystackPayment(paymentData);
                    break;
                case 'flutterwave':
                    processFlutterwavePayment(paymentData);
                    break;
                case 'stripe':
                    processStripePayment(paymentData);
                    break;
                case 'paypal':
                    processPayPalPayment(paymentData);
                    break;
                default:
                    ToastNotification.error('Error', 'Invalid payment gateway selected.');
            }
        }

        /**
         * Process Kudikah Wallet Payment
         */
        function processKudikahPayment(paymentData) {
            console.log('Processing Kudikah Wallet Payment:', paymentData);
            const amount = formatNGN(extractPrice(document.getElementById('subtotal').textContent));
            // TODO: Implement Kudikah wallet payment integration
            ToastNotification.info('Processing Payment', `Processing payment via Kudikah Wallet\nAmount: ${amount}`);
        }

        /**
         * Process Paystack Payment
         */
        function processPaystackPayment(paymentData) {
            console.log('Processing Paystack Payment:', paymentData);
            const amount = formatNGN(extractPrice(document.getElementById('subtotal').textContent));
            // TODO: Implement Paystack payment integration
            ToastNotification.info('Processing Payment', `Processing payment via Paystack\nAmount: ${amount}`);
        }

        /**
         * Process Flutterwave Payment
         */
        function processFlutterwavePayment(paymentData) {
            console.log('Processing Flutterwave Payment:', paymentData);
            const amount = formatNGN(extractPrice(document.getElementById('subtotal').textContent));
            // TODO: Implement Flutterwave payment integration
            ToastNotification.info('Processing Payment', `Processing payment via Flutterwave\nAmount: ${amount}`);
        }

        /**
         * Process Stripe Payment
         */
        function processStripePayment(paymentData) {
            console.log('Processing Stripe Payment:', paymentData);
            const amount = formatNGN(extractPrice(document.getElementById('subtotal').textContent));
            // TODO: Implement Stripe payment integration
            ToastNotification.info('Processing Payment', `Processing payment via Stripe\nAmount: ${amount}`);
        }

        /**
         * Process PayPal Payment
         */
        function processPayPalPayment(paymentData) {
            console.log('Processing PayPal Payment:', paymentData);
            const amount = formatNGN(extractPrice(document.getElementById('subtotal').textContent));
            // TODO: Implement PayPal payment integration
            ToastNotification.info('Processing Payment', `Processing payment via PayPal\nAmount: ${amount}`);
        }

    </script>
@endsection
