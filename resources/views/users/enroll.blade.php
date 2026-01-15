@extends('layouts.usertemplate')

@section('content')
    <style>
        /* Global font & background */
        /* body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            color: #222;
            padding: 28px 12px;
        } */

        .enroll-btn {
            background-color: #FDAF22;
            padding: 16px 20px;
            color: #000F11;
            font-size: 16px;
            font-weight: 600;
            border-radius: 4px;
        }

        .subject-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, min(100%, 300px)));
            gap: 20px;
        }

        .subject-card {
            border: 1px solid #CCDBDD;
            padding: 25px;
            border-radius: 20px;
        }
        .custom-select-plan{
            border: 1px solid #000000;
            padding: 10px 20px;
            border-radius: 5px;
            color: #000000;
            font-size: 12px;
        }

        .custom-switch .form-check-input {
            width: 3.5rem;
            /* default is ~2.5rem */
            height: 1.75rem;
            /* default is ~1.25rem */
            cursor: pointer;
        }

        /* make the knob bigger */
        .custom-switch .form-check-input::before {
            width: 1.4rem;
            height: 1.4rem;
        }

        /* OFF state */
        .custom-switch .form-check-input {
            background-color: #cbd5e1;
            border-color: #cbd5e1;
        }

        /* ON state */
        .custom-switch .form-check-input:checked {
            background-color: #22c55e;
            /* green */
            border-color: #22c55e;
        }

        /* focus ring */
        .custom-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(34, 197, 94, 0.25);
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

        .proceed-payment-btn {
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

        /* Payment Gateway Selection */
        .payment-gateway-section {
            padding: 20px 22px;
            border-top: 1px solid #eee;
            background: #f9f9f9;
        }

        .payment-gateway-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 16px;
        }

        .payment-gateways {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 12px;
        }

        .gateway-option {
            position: relative;
        }

        .gateway-option input[type="radio"] {
            display: none;
        }

        .gateway-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
            min-height: 100px;
        }

        .gateway-option input[type="radio"]:checked+.gateway-label {
            border-color: #004A53;
            background-color: #f0f8f9;
        }

        .gateway-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .gateway-name {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        /* Small screens — stack price under label */
        @media (max-width: 576px) {
            .proceed-payment-btn{
                font-size: 12px;
                padding: 15px 15px;
            }
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

        /* Back button styling */
        .back-btn {
            background: none;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #004A53;
            font-size: 24px;
            transition: opacity 0.2s ease;
            margin-right: 12px;
        }

        .back-btn:hover {
            opacity: 0.7;
        }

        .back-btn:active {
            opacity: 0.5;
        }

        .header-with-back {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Payment Modal Styles */
        .payment-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .payment-modal {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease-out;
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

        .payment-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid #e0e0e0;
        }

        .payment-modal-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .payment-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #666;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .payment-modal-close:hover {
            color: #333;
        }

        .payment-modal-body {
            padding: 24px;
        }

        .payment-modal-footer {
            display: flex;
            gap: 12px;
            padding: 24px;
            border-top: 1px solid #e0e0e0;
            justify-content: flex-end;
        }

        .payment-modal-cancel {
            padding: 12px 24px;
            border: 1px solid #e0e0e0;
            background: white;
            color: #333;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-modal-cancel:hover {
            background: #f5f5f5;
            border-color: #ccc;
        }

        .payment-modal-confirm {
            padding: 12px 24px;
            border: none;
            background: #004A53;
            color: white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-modal-confirm:hover {
            background: #003a41;
        }

        .payment-modal-confirm:active {
            transform: scale(0.98);
        }

        /* Payment Gateway Options */
        .payment-gateways {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 12px;
        }

        .gateway-option {
            position: relative;
        }

        .gateway-option input[type="radio"] {
            display: none;
        }

        .gateway-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
            min-height: 120px;
        }

        .gateway-option input[type="radio"]:checked+.gateway-label {
            border-color: #004A53;
            background-color: #f0f8f9;
        }

        .gateway-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            background: #f5f5f5;
            border-radius: 6px;
        }

        .gateway-icon img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .gateway-name {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        /* Responsive modal */
        @media (max-width: 576px) {
            .payment-modal {
                width: 95%;
                max-height: 85vh;
            }

            .payment-modal-header {
                padding: 16px;
            }

            .payment-modal-body {
                padding: 16px;
            }

            .payment-modal-footer {
                padding: 16px;
                flex-direction: column;
            }

            .payment-modal-cancel,
            .payment-modal-confirm {
                width: 100%;
            }

            .payment-gateways {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            }

            .gateway-label {
                min-height: 100px;
                padding: 12px 8px;
            }

            .gateway-icon {
                width: 50px;
                height: 50px;
            }

            .gateway-name {
                font-size: 12px;
            }
        }
    </style>
@section('content')
    <main>
        <section class="container-fluid p-4 d-flex flex-column gap-5">
            <div class ="d-flex flex-column gap-3 flex-md-row justify-content-between align-items-md-center">
                <div class="header-with-back">
                    <button class="back-btn" type="button" id="backBtn" title="Go back">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h1 id="levelTitle">Loading...</h1>
                </div>
                <button class = "enroll-btn" type = "button" id="enrollAllBtn">Enroll in All Subjects - ₦0.00</button>
            </div>
            <section class="d-flex flex-column gap-4">
                <div class="d-flex flex-column flex-md-row  align-items-md-center gap-3">
                    <select name="plan" id="planSelector" class="custom-select-plan">
                        <option value="">Loading plans...</option>
                    </select>
                    <p id="planPriceInfo">Select a plan to see pricing</p>
                </div>
                <div class="subject-container" id="subjectContainer">
                    <!-- Subjects will be loaded dynamically here -->
                </div>
            </section>
            {{-- <div class="txn-card w-100">

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
                <div class="txn-list" id="coursesList">
                    <!-- Courses will be loaded here dynamically -->
                    <div class="txn-row">
                        <div class="txn-left">
                            <p class="text-muted">Loading courses...</p>
                        </div>
                    </div>
                </div>

            </div> --}}

            <!-- Footer with proceed button -->
            <div style="padding: 20px 22px; display: flex; justify-content: center;">
                <button id="proceedBtn" class="proceed-payment-btn">
                    Proceed to Payment - Subtotal: <span id="subtotal" class="subtotal">₦0.00</span>
                </button>
            </div>

            <!-- Payment Gateway Modal -->
            <div id="paymentGatewayModal" class="payment-modal-overlay" style="display: none;">
                <div class="payment-modal">
                    <div class="payment-modal-header">
                        <h2>Select Payment Method</h2>
                        <button type="button" class="payment-modal-close" id="closePaymentModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="payment-modal-body">
                        <div class="payment-gateways">
                            <!-- Kudikah Wallet -->
                            <div class="gateway-option">
                                <input type="radio" id="gateway-kudikah" name="payment_gateway" value="kudikah" checked>
                                <label for="gateway-kudikah" class="gateway-label">
                                    <div class="gateway-icon">
                                        <img src="{{ asset('images/card-icon.png') }}" alt="Kudikah Wallet">
                                    </div>
                                    <div class="gateway-name">Kudikah Wallet</div>
                                </label>
                            </div>

                            <!-- Paystack -->
                            <div class="gateway-option">
                                <input type="radio" id="gateway-paystack" name="payment_gateway" value="paystack">
                                <label for="gateway-paystack" class="gateway-label">
                                    <div class="gateway-icon">
                                        <img src="{{ asset('images/paystack.png') }}" alt="Paystack">
                                    </div>
                                    <div class="gateway-name">Paystack</div>
                                </label>
                            </div>

                            <!-- Flutterwave -->
                            <div class="gateway-option">
                                <input type="radio" id="gateway-flutterwave" name="payment_gateway" value="flutterwave">
                                <label for="gateway-flutterwave" class="gateway-label">
                                    <div class="gateway-icon">
                                        <img src="{{ asset('images/Flutterwave.png') }}" alt="Flutterwave"
                                            style="max-width: 50px; max-height: 50px;">
                                    </div>
                                    <div class="gateway-name">Flutterwave</div>
                                </label>
                            </div>

                            <!-- Stripe -->
                            <div class="gateway-option">
                                <input type="radio" id="gateway-stripe" name="payment_gateway" value="stripe">
                                <label for="gateway-stripe" class="gateway-label">
                                    <div class="gateway-icon">
                                        <img src="{{ asset('images/stripe.webp') }}" alt="Stripe">
                                    </div>
                                    <div class="gateway-name">Stripe</div>
                                </label>
                            </div>

                            <!-- PayPal -->
                            <div class="gateway-option">
                                <input type="radio" id="gateway-paypal" name="payment_gateway" value="paypal">
                                <label for="gateway-paypal" class="gateway-label">
                                    <div class="gateway-icon">
                                        <img src="{{ asset('images/paypal.png') }}" alt="PayPal">
                                    </div>
                                    <div class="gateway-name">PayPal</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="payment-modal-footer">
                        <button type="button" class="payment-modal-cancel" id="cancelPaymentModal">Cancel</button>
                        <button type="button" class="payment-modal-confirm" id="confirmPaymentModal">Proceed with
                            Payment</button>
                    </div>
                </div>
            </div>

        </section>
    </main>

    <!-- JS: bootstrap + API integration -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- API Clients -->
    <script>
        let allCourses = [];
        let allSubscriptionPlans = [];
        let userSubscriptions = [];
        let selectedPlanId = null;

        document.addEventListener("DOMContentLoaded", async () => {
            await loadSubscriptionPlans();
            await loadUserSubscriptions();
            await loadCourses();
            setupBackButton();
            setupPlanSelector();
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
         * Load subscription plans from API
         */
        async function loadSubscriptionPlans() {
            try {
                const result = await SubscriptionApiClient.getPlans({ active: true });
                if (result.success && result.data) {
                    // Handle paginated response
                    const plans = result.data.data || result.data;
                    allSubscriptionPlans = Array.isArray(plans) ? plans : [];
                    populatePlanSelector();
                }
            } catch (error) {
                console.error('Error loading subscription plans:', error);
                showError('Failed to load subscription plans.');
            }
        }

        /**
         * Load user's active subscriptions
         */
        async function loadUserSubscriptions() {
            try {
                const result = await SubscriptionApiClient.getMySubscriptions({ status: 'active' });
                if (result.success && result.data) {
                    // Handle paginated response
                    const subscriptions = result.data.data || result.data;
                    userSubscriptions = Array.isArray(subscriptions) ? subscriptions : [];
                    console.log('User subscriptions:', userSubscriptions);
                }
            } catch (error) {
                console.error('Error loading user subscriptions:', error);
                // Don't show error - subscriptions are optional
            }
        }

        /**
         * Populate plan selector with subscription plans
         */
        function populatePlanSelector() {
            const planSelector = document.getElementById('planSelector');
            if (!planSelector) return;

            if (allSubscriptionPlans.length === 0) {
                planSelector.innerHTML = '<option value="">No plans available</option>';
                return;
            }

            let html = '<option value="">Select a plan</option>';
            allSubscriptionPlans.forEach(plan => {
                html += `<option value="${plan.id}" data-price="${plan.price}" data-duration="${plan.duration}" data-duration-type="${plan.duration_type}">
                    ${plan.title} - ${formatNGN(plan.price)}
                </option>`;
            });
            planSelector.innerHTML = html;
        }

        /**
         * Setup plan selector functionality
         */
        function setupPlanSelector() {
            const planSelector = document.getElementById('planSelector');
            if (planSelector) {
                planSelector.addEventListener('change', (e) => {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const planId = e.target.value;
                    if (planId) {
                        selectedPlanId = planId;
                        updatePlanPricing(planId, selectedOption);
                    }
                });
            }
        }

        /**
         * Update pricing display based on selected plan
         */
        function updatePlanPricing(planId, selectedOption) {
            const planPriceInfo = document.getElementById('planPriceInfo');

            if (!planId) {
                planPriceInfo.textContent = 'Select a plan to see pricing';
                return;
            }

            // Find the selected plan
            const selectedPlan = allSubscriptionPlans.find(p => p.id == planId);
            if (!selectedPlan) {
                planPriceInfo.textContent = 'Plan not found';
                return;
            }

            // Display plan info
            planPriceInfo.innerHTML = `
                <strong>${selectedPlan.title}</strong> - ${formatNGN(selectedPlan.price)} <small style="color: #666;">per subject</small>
            `;

            // Update course prices and toggle states based on plan
            updateCoursePricesForPlan(planId, selectedPlan);
        }

        /**
         * Update toggle states based on selected plan
         *
         * Pricing is purely based on the selected plan (per-subject)
         * No individual course prices are used
         */
        function updateCoursePricesForPlan(planId, selectedPlan) {
            const courseCards = document.querySelectorAll('.subject-card');
            courseCards.forEach((card, index) => {
                const checkbox = card.querySelector('.check-subject');
                if (checkbox) {
                    // Store selected plan ID for reference
                    checkbox.dataset.selectedPlanId = planId;

                    // Check if user has active subscription for this plan
                    const hasActiveSubscription = userSubscriptions.some(sub =>
                        sub.subscription_plan_id == planId && sub.status === 'active'
                    );

                    if (hasActiveSubscription) {
                        // Disable checkbox and mark as checked
                        checkbox.disabled = true;
                        checkbox.checked = true;
                        card.style.opacity = '0.7';

                        // Add badge
                        let badge = card.querySelector('.subscription-badge');
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'subscription-badge';
                            badge.style.cssText = 'position: absolute; top: 10px; right: 10px; background: #22c55e; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;';
                            badge.textContent = 'Active';
                            card.style.position = 'relative';
                            card.appendChild(badge);
                        }
                    } else {
                        // Enable checkbox
                        checkbox.disabled = false;
                        checkbox.checked = false;
                        card.style.opacity = '1';

                        // Remove badge
                        const badge = card.querySelector('.subscription-badge');
                        if (badge) badge.remove();
                    }
                }
            });

            // Recalculate subtotal
            updateSubtotal();
        }

        /**
         * Format numbers as NGN currency
         */
        function formatNGN(n) {
            n = Number(n) || 0;
            return '₦' + n.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        /**
         * Get level ID from URL query parameter
         */
        function getLevelIdFromURL() {
            const params = new URLSearchParams(window.location.search);
            const levelId = params.get('level_id');
            console.log('URL:', window.location.href);
            console.log('Search params:', window.location.search);
            console.log('Extracted level_id:', levelId);
            return levelId;
        }

        /**
         * Load courses by level ID from API
         */
        async function loadCourses() {
            try {
                const levelId = getLevelIdFromURL();
                if (!levelId) {
                    showError('No level selected. Please go back and select a class.');
                    return;
                }

                console.log('Loading courses for level_id:', levelId);
                console.log('API call parameters:', {
                    level_id: levelId,
                    per_page: 50
                });

                // Fetch courses for this level
                const result = await CourseApiClient.getCourses({
                    level_id: levelId,
                    per_page: 50
                });

                console.log('API Response:', result);
                console.log('API Response data.courses.data:', result.data?.courses?.data);

                // Handle API response structure - courses are in result.data.courses.data
                let courses = [];
                if (result.success && result.data) {
                    if (result.data.courses && result.data.courses.data) {
                        // API returns paginated response
                        courses = result.data.courses.data;
                    } else if (Array.isArray(result.data)) {
                        // API returns direct array
                        courses = result.data;
                    }
                }

                if (courses && courses.length > 0) {
                    allCourses = courses;
                    displayCourses(allCourses);
                    updateLevelTitle(levelId);
                    updateEnrollAllButton();
                } else {
                    console.log('No courses found. Response data:', result.data);
                    showError('No courses available for this class.');
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                showError('Failed to load courses. Please try again later.');
            }
        }

        /**
         * Update the level title in the header
         */
        async function updateLevelTitle(levelId) {
            try {
                const result = await CourseApiClient.getLevels();
                if (result.success && result.data) {
                    const level = result.data.find(l => l.id == levelId);
                    if (level) {
                        document.getElementById('levelTitle').textContent = level.name;
                    }
                }
            } catch (error) {
                console.error('Error loading level:', error);
            }
        }

        /**
         * Display courses as subject cards with toggle switches
         *
         * Pricing is based on the selected subscription plan only
         * Individual course prices are NOT used
         */
        function displayCourses(courses) {
            const subjectContainer = document.getElementById('subjectContainer');

            const coursesHtml = courses.map((course, index) => {
                return `
                    <div class="subject-card d-flex align-items-center gap-2 justify-content-between">
                        <div>
                            <h5 style="margin: 0 0 4px 0;">${course.title}</h5>
                        </div>
                        <div class="form-check form-switch custom-switch">
                            <input class="form-check-input check-subject"
                                   type="checkbox"
                                   role="switch"
                                   data-course-id="${course.id}"
                                   id="cb${index}">
                        </div>
                    </div>
                `;
            }).join('');

            subjectContainer.innerHTML = coursesHtml;
            attachCheckboxListeners();
        }

        /**
         * Attach listeners to checkboxes
         */
        function attachCheckboxListeners() {
            const checkboxes = document.querySelectorAll('.check-subject');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateSubtotal);
            });
        }

        /**
         * Calculate and update subtotal
         *
         * For subscription-based enrollment:
         * - Plan price is PER SUBJECT
         * - Subtotal = Plan price × Number of selected subjects
         * - Example: Plan ₦400/subject × 10 subjects = ₦4,000
         *
         * Individual course prices are NOT used
         * All pricing is based on the selected subscription plan
         */
        function updateSubtotal() {
            const checks = document.querySelectorAll('.check-subject:checked');
            let total = 0;

            // Calculate subtotal based on selected plan and number of subjects
            if (selectedPlanId) {
                const selectedPlan = allSubscriptionPlans.find(p => p.id == selectedPlanId);
                if (selectedPlan) {
                    // Plan price is per subject, multiply by number of selected subjects
                    const numSelectedSubjects = checks.length;
                    total = selectedPlan.price * numSelectedSubjects;
                }
            }

            document.getElementById('subtotal').textContent = formatNGN(total);
            updateEnrollAllButton();
        }

        /**
         * Update "Enroll in All" button text with 10% discount
         *
         * When user subscribes to ALL subjects:
         * - Regular price = Plan price × Number of subjects
         * - Discount = 10% off total
         * - Final price = Regular price - (Regular price × 10%)
         */
        function updateEnrollAllButton() {
            const courseCount = allCourses.length;
            const enrollAllBtn = document.getElementById('enrollAllBtn');

            if (!selectedPlanId) {
                enrollAllBtn.textContent = `Enroll in All ${courseCount} Subjects - Select a plan`;
                enrollAllBtn.disabled = true;
                return;
            }

            // If subscription plan is selected, calculate price for all courses
            const selectedPlan = allSubscriptionPlans.find(p => p.id == selectedPlanId);
            if (selectedPlan) {
                // Calculate total price for all subjects (plan price × number of subjects)
                const regularPrice = selectedPlan.price * courseCount;

                // Apply 10% discount for subscribing to all subjects
                const discountAmount = regularPrice * 0.10;
                const discountedPrice = regularPrice - discountAmount;

                // Format button text with discount info
                enrollAllBtn.textContent = `Subscribe to All ${courseCount} Subjects - ${formatNGN(regularPrice)} (Save 10% - ${formatNGN(discountedPrice)})`;
                enrollAllBtn.disabled = false;
            }
        }

        /**
         * Show error message
         */
        function showError(message) {
            const subjectContainer = document.getElementById('subjectContainer');
            if (subjectContainer) {
                subjectContainer.innerHTML =
                    `<div style="text-align: center; padding: 40px; grid-column: 1/-1;"><p class="text-danger" style="font-size: 16px; color: #dc3545;">${message}</p></div>`;
            } else {
                console.error('Error:', message);
            }
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

            if (!selectedPlanId) {
                alert('Please select a subscription plan first.');
                return;
            }

            const checked = document.querySelectorAll('.check-subject:checked');

            if (checked.length === 0) {
                alert('Please select at least one subject to proceed.');
                return;
            }

            // Store payment data and open modal
            const selectedCourses = Array.from(checked).map(cb => cb.dataset.courseId);
            const subtotal = document.getElementById('subtotal').textContent;
            const selectedPlan = allSubscriptionPlans.find(p => p.id == selectedPlanId);
            const courseCount = checked.length;

            // Calculate price: plan price × number of selected subjects (no discount for partial selection)
            const totalPrice = selectedPlan.price * courseCount;

            pendingPaymentData = {
                courses: selectedCourses,
                subtotal: subtotal,
                planId: selectedPlanId,
                planPrice: selectedPlan.price,
                courseCount: courseCount,
                totalPrice: totalPrice,
                hasDiscount: false,
                isSubscription: true
            };

            openPaymentModal();
        });

        /**
         * Handle "Enroll in All" button
         */
        document.getElementById('enrollAllBtn').addEventListener('click', function(e) {
            e.preventDefault();

            if (!selectedPlanId) {
                alert('Please select a subscription plan first.');
                return;
            }

            // Select all non-disabled checkboxes
            document.querySelectorAll('.check-subject:not(:disabled)').forEach(cb => {
                cb.checked = true;
            });
            updateSubtotal();

            // Get selected courses
            const selectedCheckboxes = document.querySelectorAll('.check-subject:checked');
            const selectedCourses = Array.from(selectedCheckboxes).map(cb => cb.dataset.courseId);

            const selectedPlan = allSubscriptionPlans.find(p => p.id == selectedPlanId);
            const courseCount = selectedCourses.length;

            // Calculate price with 10% discount for subscribing to all subjects
            const regularPrice = selectedPlan.price * courseCount;
            const discountAmount = regularPrice * 0.10;
            const discountedPrice = regularPrice - discountAmount;

            pendingPaymentData = {
                courses: selectedCourses,
                subtotal: formatNGN(discountedPrice),
                planId: selectedPlanId,
                planPrice: selectedPlan.price,
                courseCount: courseCount,
                regularPrice: regularPrice,
                discountAmount: discountAmount,
                discountedPrice: discountedPrice,
                hasDiscount: true,
                isSubscription: true
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
                alert('Payment data not found. Please try again.');
                return;
            }

            // Get selected payment gateway from modal
            const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked');
            if (!selectedGateway) {
                alert('Please select a payment method.');
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
            switch (gateway) {
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
                    alert('Invalid payment gateway selected.');
            }
        }

        /**
         * Process Kudikah Wallet Payment
         */
        async function processKudikahPayment(paymentData) {
            try {
                showLoadingState('Processing Kudikah Wallet payment...');

                // If this is a subscription payment
                if (paymentData.isSubscription) {
                    const subscriptionData = {
                        subscription_plan_id: paymentData.planId,
                        amount_paid: paymentData.planPrice,
                        payment_reference: 'kudikah_wallet_' + Date.now(),
                        course_ids: paymentData.courses
                    };

                    const result = await SubscriptionApiClient.subscribe(subscriptionData);

                    if (result.success) {
                        showSuccessMessage(`Successfully subscribed to plan via Kudikah Wallet!`);
                        // Reload page to show updated subscriptions
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showErrorMessage(result.message || 'Failed to subscribe to plan.');
                    }
                } else {
                    // Original course purchase logic
                    let successCount = 0;
                    let failureCount = 0;
                    const courseIds = paymentData.courses;

                    // Process each course individually using WalletApiClient
                    for (const courseId of courseIds) {
                        const result = await WalletApiClient.purchaseCourse(courseId);

                        if (result.success) {
                            successCount++;
                        } else {
                            failureCount++;
                            console.error(`Failed to purchase course ${courseId}:`, result.message);
                        }
                    }

                    if (successCount > 0) {
                        showSuccessMessage(`Successfully purchased ${successCount} course(s) via Kudikah Wallet!`);
                        setTimeout(() => {
                            window.location.href = '/usersubject';
                        }, 2000);
                    } else {
                        showErrorMessage(`Failed to purchase courses. Please try again.`);
                    }
                }
            } catch (error) {
                console.error('Kudikah payment error:', error);
                showErrorMessage('Error processing Kudikah Wallet payment: ' + error.message);
            }
        }

        /**
         * Process Paystack Payment
         */
        async function processPaystackPayment(paymentData) {
            try {
                showLoadingState('Initializing Paystack payment...');

                if (paymentData.isSubscription) {
                    // For subscription, redirect to subscription payment
                    const subscriptionData = {
                        subscription_plan_id: paymentData.planId,
                        course_ids: paymentData.courses,
                        gateway: 'paystack'
                    };

                    const result = await PaymentApiClient.initializeSubscriptionPayment(subscriptionData);

                    if (result.success && result.data.gateway_data && result.data.gateway_data.authorization_url) {
                        window.location.href = result.data.gateway_data.authorization_url;
                    } else {
                        showErrorMessage(result.message || 'Failed to initialize Paystack payment.');
                    }
                } else {
                    // Original course purchase logic
                    const courseId = paymentData.courses[0];

                    const paymentRequest = {
                        course_id: courseId,
                        gateway: 'paystack'
                    };

                    const result = await PaymentApiClient.initializeCoursePayment(paymentRequest);

                    if (result.success && result.data.gateway_data && result.data.gateway_data.authorization_url) {
                        window.location.href = result.data.gateway_data.authorization_url;
                    } else {
                        showErrorMessage(result.message || 'Failed to initialize Paystack payment.');
                    }
                }
            } catch (error) {
                console.error('Paystack payment error:', error);
                showErrorMessage('Error initializing Paystack payment: ' + error.message);
            }
        }

        /**
         * Process Flutterwave Payment
         */
        async function processFlutterwavePayment(paymentData) {
            try {
                showLoadingState('Initializing Flutterwave payment...');

                if (paymentData.isSubscription) {
                    const subscriptionData = {
                        subscription_plan_id: paymentData.planId,
                        course_ids: paymentData.courses,
                        gateway: 'flutterwave'
                    };

                    const result = await PaymentApiClient.initializeSubscriptionPayment(subscriptionData);

                    if (result.success && result.data.gateway_data && result.data.gateway_data.authorization_url) {
                        window.location.href = result.data.gateway_data.authorization_url;
                    } else {
                        showErrorMessage(result.message || 'Failed to initialize Flutterwave payment.');
                    }
                } else {
                    const courseId = paymentData.courses[0];

                    const paymentRequest = {
                        course_id: courseId,
                        gateway: 'flutterwave'
                    };

                    const result = await PaymentApiClient.initializeCoursePayment(paymentRequest);

                    if (result.success && result.data.gateway_data && result.data.gateway_data.authorization_url) {
                        window.location.href = result.data.gateway_data.authorization_url;
                    } else {
                        showErrorMessage(result.message || 'Failed to initialize Flutterwave payment.');
                    }
                }
            } catch (error) {
                console.error('Flutterwave payment error:', error);
                showErrorMessage('Error initializing Flutterwave payment: ' + error.message);
            }
        }

        /**
         * Process Stripe Payment
         */
        async function processStripePayment(paymentData) {
            try {
                showLoadingState('Initializing Stripe payment...');

                if (paymentData.isSubscription) {
                    const subscriptionData = {
                        subscription_plan_id: paymentData.planId,
                        course_ids: paymentData.courses,
                        gateway: 'stripe'
                    };

                    const result = await PaymentApiClient.initializeSubscriptionPayment(subscriptionData);

                    if (result.success && result.data.gateway_data && result.data.gateway_data.authorization_url) {
                        window.location.href = result.data.gateway_data.authorization_url;
                    } else {
                        showErrorMessage(result.message || 'Failed to initialize Stripe payment.');
                    }
                } else {
                    const courseId = paymentData.courses[0];

                    const paymentRequest = {
                        course_id: courseId,
                        gateway: 'stripe'
                    };

                    const result = await PaymentApiClient.initializeCoursePayment(paymentRequest);

                    if (result.success && result.data.gateway_data && result.data.gateway_data.authorization_url) {
                        window.location.href = result.data.gateway_data.authorization_url;
                    } else {
                        showErrorMessage(result.message || 'Failed to initialize Stripe payment.');
                    }
                }
            } catch (error) {
                console.error('Stripe payment error:', error);
                showErrorMessage('Error initializing Stripe payment: ' + error.message);
            }
        }

        /**
         * Process PayPal Payment
         */
        async function processPayPalPayment(paymentData) {
            try {
                showLoadingState('Initializing PayPal payment...');

                if (paymentData.isSubscription) {
                    const subscriptionData = {
                        subscription_plan_id: paymentData.planId,
                        course_ids: paymentData.courses,
                        gateway: 'paypal'
                    };

                    const result = await PaymentApiClient.initializeSubscriptionPayment(subscriptionData);

                    if (result.success && result.data.gateway_data && result.data.gateway_data.authorization_url) {
                        window.location.href = result.data.gateway_data.authorization_url;
                    } else {
                        showErrorMessage(result.message || 'Failed to initialize PayPal payment.');
                    }
                } else {
                    const courseId = paymentData.courses[0];

                    const paymentRequest = {
                        course_id: courseId,
                        gateway: 'paypal'
                    };

                    const result = await PaymentApiClient.initializeCoursePayment(paymentRequest);

                    if (result.success && result.data.gateway_data && result.data.gateway_data.authorization_url) {
                        window.location.href = result.data.gateway_data.authorization_url;
                    } else {
                        showErrorMessage(result.message || 'Failed to initialize PayPal payment.');
                    }
                }
            } catch (error) {
                console.error('PayPal payment error:', error);
                showErrorMessage('Error initializing PayPal payment: ' + error.message);
            }
        }

        /**
         * Show loading state
         */
        function showLoadingState(message) {
            const modal = document.getElementById('paymentGatewayModal');
            const body = modal.querySelector('.payment-modal-body');
            body.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <p style="font-size: 16px; color: #004A53; font-weight: 500;">${message}</p>
                </div>
            `;
        }

        /**
         * Show success message
         */
        function showSuccessMessage(message) {
            const modal = document.getElementById('paymentGatewayModal');
            const body = modal.querySelector('.payment-modal-body');
            body.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <div style="font-size: 48px; margin-bottom: 16px;">✅</div>
                    <p style="color: #28a745; font-weight: 600;">${message}</p>
                </div>
            `;
        }

        /**
         * Show error message
         */
        function showErrorMessage(message) {
            const modal = document.getElementById('paymentGatewayModal');
            const body = modal.querySelector('.payment-modal-body');
            body.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <div style="font-size: 48px; margin-bottom: 16px;">❌</div>
                    <p style="color: #dc3545; font-weight: 600;">${message}</p>
                    <button type="button" class="btn btn-primary mt-3" onclick="location.reload()">Try Again</button>
                </div>
            `;
        }
    </script>
@endsection
