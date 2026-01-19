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

        /* DISABLED state */
        .custom-switch .form-check-input:disabled {
            background-color: #cbd5e1;
            border-color: #cbd5e1;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* DISABLED CHECKED state - Active and disabled (for enrolled/free courses) */
        .custom-switch .form-check-input:disabled:checked {
            background-color: #22c55e;
            border-color: #22c55e;
            opacity: 1;
            /* Full opacity to show it's active */
        }

        /* Add visual indicator for disabled checked state */
        .custom-switch .form-check-input:disabled:checked::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background-color: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.3);
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
            .proceed-payment-btn, .enroll-btn {
                font-size: 12px;
                padding: 12px 14px;
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

        /* Subscription Details Modal Styles */
        .subscription-details {
            padding: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            font-size: 14px;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #222;
            font-weight: 600;
        }

        .detail-row.discount .detail-value {
            color: #004A53;
            font-weight: 700;
        }

        .detail-row.total {
            padding: 16px 0;
            font-size: 16px;
            border-top: 2px solid #FDAF22;
            border-bottom: 2px solid #FDAF22;
            background: linear-gradient(90deg, rgba(0, 74, 83, 0.05) 0%, rgba(253, 175, 34, 0.05) 100%);
            border-radius: 8px;
            padding: 16px 12px;
            margin: 8px 0;
        }

        .detail-row.total .detail-label {
            color: #004A53;
        }

        .detail-row.total .detail-value {
            color: #FDAF22;
            font-size: 18px;
        }

        .subscription-details hr {
            margin: 12px 0;
            border: none;
            border-top: 1px solid #e0e0e0;
        }

        /* Modal Header Styling */
        #subscribeAllModal .modal-header {
            background: linear-gradient(135deg, #004A53 0%, #00626d 100%);
            border-bottom: 3px solid #FDAF22;
        }

        #subscribeAllModal .modal-title {
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        #subscribeAllModal .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Modal Body Styling */
        #subscribeAllModal .modal-body {
            background: #f9fafb;
            padding: 28px;
        }

        /* Modal Footer Styling */
        #subscribeAllModal .modal-footer {
            background: white;
            border-top: 1px solid #e0e0e0;
            padding: 16px 28px;
        }

        #subscribeAllModal .btn-secondary {
            background-color: #e0e0e0;
            border-color: #e0e0e0;
            color: #333;
            font-weight: 600;
        }

        #subscribeAllModal .btn-secondary:hover {
            background-color: #d0d0d0;
            border-color: #d0d0d0;
        }

        #subscribeAllModal .btn-primary {
            background-color: #FDAF22;
            border-color: #FDAF22;
            color: #000F11;
            font-weight: 600;
        }

        #subscribeAllModal .btn-primary:hover {
            background-color: #e59a0f;
            border-color: #e59a0f;
        }

        #subscribeAllModal .btn-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(253, 175, 34, 0.25);
        }

        /* Payment Confirmation Modal Styling */
        #paymentConfirmationModal .modal-header {
            background: linear-gradient(135deg, #004A53 0%, #00626d 100%);
            border-bottom: 3px solid #FDAF22;
        }

        #paymentConfirmationModal .modal-title {
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        #paymentConfirmationModal .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Modal Body Styling */
        #paymentConfirmationModal .modal-body {
            background: #f9fafb;
            padding: 28px;
        }

        /* Subscription Details Styling */
        .subscription-details {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e0e0e0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            font-size: 15px;
        }

        .detail-row.discount {
            color: #28a745;
        }

        .detail-row.total {
            padding: 16px 0;
            font-size: 16px;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #004A53;
            font-weight: 600;
        }

        .detail-row.discount .detail-value {
            color: #28a745;
        }

        .detail-row.total .detail-value {
            color: #FDAF22;
            font-size: 18px;
        }

        .subscription-details hr {
            margin: 12px 0;
            border: none;
            border-top: 1px solid #e0e0e0;
        }

        /* Modal Footer Styling */
        #paymentConfirmationModal .modal-footer {
            background: white;
            border-top: 1px solid #e0e0e0;
            padding: 16px 28px;
        }

        #paymentConfirmationModal .btn-secondary {
            background-color: #e0e0e0;
            border-color: #e0e0e0;
            color: #333;
            font-weight: 600;
        }

        #paymentConfirmationModal .btn-secondary:hover {
            background-color: #d0d0d0;
            border-color: #d0d0d0;
        }

        #paymentConfirmationModal .btn-primary {
            background-color: #FDAF22;
            border-color: #FDAF22;
            color: #000F11;
            font-weight: 600;
        }

        #paymentConfirmationModal .btn-primary:hover {
            background-color: #e59a0f;
            border-color: #e59a0f;
        }

        #paymentConfirmationModal .btn-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(253, 175, 34, 0.25);
        }

        /* Plan Selection Warning Modal Styling */
        #planSelectionWarningModal .modal-header {
            background: linear-gradient(135deg, #004A53 0%, #00626d 100%);
            border-bottom: 3px solid #FDAF22;
        }

        #planSelectionWarningModal .modal-title {
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        #planSelectionWarningModal .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Modal Body Styling */
        #planSelectionWarningModal .modal-body {
            background: #f9fafb;
            padding: 28px;
            text-align: center;
        }

        .warning-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .warning-icon {
            font-size: 48px;
            line-height: 1;
        }

        .warning-message {
            font-size: 16px;
            color: #333;
            margin: 0;
            font-weight: 500;
        }

        /* Modal Footer Styling */
        #planSelectionWarningModal .modal-footer {
            background: white;
            border-top: 1px solid #e0e0e0;
            padding: 16px 28px;
            justify-content: center;
        }

        #planSelectionWarningModal .btn-primary {
            background-color: #FDAF22;
            border-color: #FDAF22;
            color: #000F11;
            font-weight: 600;
            padding: 10px 32px;
        }

        #planSelectionWarningModal .btn-primary:hover {
            background-color: #e59a0f;
            border-color: #e59a0f;
        }

        #planSelectionWarningModal .btn-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(253, 175, 34, 0.25);
        }

        /* Subject Selection Warning Modal Styling */
        #subjectSelectionWarningModal .modal-header {
            background: linear-gradient(135deg, #004A53 0%, #00626d 100%);
            border-bottom: 3px solid #FDAF22;
        }

        #subjectSelectionWarningModal .modal-title {
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        #subjectSelectionWarningModal .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Modal Body Styling */
        #subjectSelectionWarningModal .modal-body {
            background: #f9fafb;
            padding: 28px;
            text-align: center;
        }

        /* Modal Footer Styling */
        #subjectSelectionWarningModal .modal-footer {
            background: white;
            border-top: 1px solid #e0e0e0;
            padding: 16px 28px;
            justify-content: center;
        }

        #subjectSelectionWarningModal .btn-primary {
            background-color: #FDAF22;
            border-color: #FDAF22;
            color: #000F11;
            font-weight: 600;
            padding: 10px 32px;
        }

        #subjectSelectionWarningModal .btn-primary:hover {
            background-color: #e59a0f;
            border-color: #e59a0f;
        }

        #subjectSelectionWarningModal .btn-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(253, 175, 34, 0.25);
        }

        /* Plan Selection Notification */
        .plan-notification {
            background: linear-gradient(135deg, #e8f5e9 0%, #f1f8f6 100%);
            border-left: 4px solid #004A53;
            border-radius: 6px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-icon {
            font-size: 24px;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            color: #004A53;
            margin: 0 0 4px 0;
            font-size: 14px;
        }

        .notification-message {
            color: #2e7d32;
            margin: 0;
            font-size: 13px;
            line-height: 1.4;
        }

        .notification-close {
            background: none;
            border: none;
            color: #004A53;
            cursor: pointer;
            font-size: 20px;
            padding: 0;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-close:hover {
            color: #002a2f;
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
                <button class = "enroll-btn" type = "button" id="enrollAllBtn">Subscribe to All Subjects</button>
            </div>
            <section class="d-flex flex-column gap-4">
                <div class="d-flex flex-column flex-md-row  align-items-md-center gap-3">
                    <select name="plan" id="planSelector" class="custom-select-plan">
                        <option value="">Loading plans...</option>
                    </select>
                    <p id="planPriceInfo">Select a plan to see pricing</p>
                </div>
                <div id="planSelectionNotification" class="plan-notification" style="display: none;">
                    <!-- Notification will be shown here -->
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
            <div style=" display: flex; justify-content:center;">
                <button id="proceedBtn" class="proceed-payment-btn">
                    Proceed to Payment
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
                            <div class="gateway-option" style="display: none;">
                                <input type="radio" id="gateway-stripe" name="payment_gateway" value="stripe">
                                <label for="gateway-stripe" class="gateway-label">
                                    <div class="gateway-icon">
                                        <img src="{{ asset('images/stripe.webp') }}" alt="Stripe">
                                    </div>
                                    <div class="gateway-name">Stripe</div>
                                </label>
                            </div>

                            <!-- PayPal -->
                            <div class="gateway-option" style="display: none;">
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

            <!-- Subscribe to All Subjects Modal -->
            <div class="modal fade" id="subscribeAllModal" tabindex="-1" aria-labelledby="subscribeAllModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="subscribeAllModalLabel">Subscribe to All Subjects</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="subscribeAllDetails">
                                <!-- Details will be populated by JavaScript -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary enroll-btn" id="proceedToPaymentBtn">Proceed to Payment</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Confirmation Modal -->
            <div class="modal fade" id="paymentConfirmationModal" tabindex="-1" aria-labelledby="paymentConfirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentConfirmationModalLabel">Confirm Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="paymentConfirmationDetails">
                                <!-- Details will be populated by JavaScript -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary enroll-btn" id="confirmPaymentBtn">Proceed to Payment Gateway</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Selection Warning Modal -->
            <div class="modal fade" id="planSelectionWarningModal" tabindex="-1" aria-labelledby="planSelectionWarningModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="planSelectionWarningModalLabel">Select a Plan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="warning-content">
                                <div class="warning-icon">⚠️</div>
                                <p class="warning-message">Please select a subscription plan first before proceeding.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subject Selection Warning Modal -->
            <div class="modal fade" id="subjectSelectionWarningModal" tabindex="-1" aria-labelledby="subjectSelectionWarningModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="subjectSelectionWarningModalLabel">Select Subjects</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="warning-content">
                                <div class="warning-icon">📚</div>
                                <p class="warning-message">Please select at least one subject to proceed.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                        </div>
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
        let userEnrollments = [];
        let freeSubscriptionPlan = null;
        let selectedPlanId = null;

        document.addEventListener("DOMContentLoaded", async () => {
            await loadSubscriptionPlans();
            await loadUserSubscriptions();
            await loadUserEnrollments();
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
                }
            } catch (error) {
                console.error('Error loading user subscriptions:', error);
                // Don't show error - subscriptions are optional
            }
        }

        /**
         * Load user's enrollments
         */
        async function loadUserEnrollments() {
            try {
                const result = await CourseApiClient.getMyCourses();
                if (result.success && result.data) {
                    // Extract enrolled course IDs from the response
                    const courses = result.data.courses || result.data;
                    if (Array.isArray(courses)) {
                        userEnrollments = courses.map(c => c.course_id || c.id);
                    }
                }
            } catch (error) {
                console.error('Error loading user enrollments:', error);
                // Don't show error - enrollments are optional
            }
        }

        /**
         * Load free subscription plan
         */
        async function loadFreePlan() {
            try {
                const result = await SubscriptionApiClient.getPlans({ active: true });
                if (result.success && result.data) {
                    const plans = result.data.data || result.data;
                    if (Array.isArray(plans)) {
                        freeSubscriptionPlan = plans.find(p => p.duration_type === 'free');
                    }
                }
            } catch (error) {
                console.error('Error loading free plan:', error);
                // Don't show error - free plan is optional
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
         *
         * Enrolled and free courses remain disabled regardless of plan selection
         * Available courses remain enabled for user to manually select
         * Active subscriptions show "Active" badge but don't auto-select other courses
         */
        function updateCoursePricesForPlan(planId, selectedPlan) {
            const courseCards = document.querySelectorAll('.subject-card');

            courseCards.forEach((card, index) => {
                const checkbox = card.querySelector('.check-subject');
                if (checkbox) {
                    // Store selected plan ID for reference
                    checkbox.dataset.selectedPlanId = planId;

                    const courseStatus = checkbox.dataset.courseStatus;
                    const courseId = checkbox.dataset.courseId;

                    // Check if user has active subscription for this specific course and plan
                    const hasActiveSubscriptionForCourse = userSubscriptions.some(sub =>
                        sub.subscription_plan_id == planId &&
                        sub.status === 'active' &&
                        sub.course_ids &&
                        sub.course_ids.includes(parseInt(courseId))
                    );

                    // If course is enrolled or free, keep it disabled
                    if (courseStatus === 'enrolled' || courseStatus === 'free') {
                        checkbox.disabled = true;
                        checkbox.checked = true;
                        card.style.opacity = '0.7';
                    } else if (hasActiveSubscriptionForCourse) {
                        // Disable checkbox and mark as checked for active subscription
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
                        // Available courses: keep enabled and unchecked for user to manually select
                        checkbox.disabled = false;
                        checkbox.checked = false;
                        card.style.opacity = '1';

                        // Remove badge
                        const badge = card.querySelector('.subscription-badge');
                        if (badge) badge.remove();
                    }
                }
            });

            // Hide notification - no auto-selection happening
            hidePlanSelectionNotification();

            // Recalculate subtotal
            updateSubtotal();
        }

        /**
         * Show notification when plan is selected and subjects are auto-selected
         */
        function showPlanSelectionNotification(planTitle, subjectCount) {
            const notificationDiv = document.getElementById('planSelectionNotification');
            const subjectText = subjectCount === 1 ? 'subject' : 'subjects';

            notificationDiv.innerHTML = `
                <div class="notification-icon">✓</div>
                <div class="notification-content">
                    <p class="notification-title">Plan Selected</p>
                    <p class="notification-message">${subjectCount} ${subjectText} automatically selected for the <strong>${planTitle}</strong> plan.</p>
                </div>
                <button type="button" class="notification-close" onclick="document.getElementById('planSelectionNotification').style.display = 'none';">×</button>
            `;
            notificationDiv.style.display = 'flex';
        }

        /**
         * Hide plan selection notification
         */
        function hidePlanSelectionNotification() {
            const notificationDiv = document.getElementById('planSelectionNotification');
            notificationDiv.style.display = 'none';
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

        

                // Fetch courses for this level
                const result = await CourseApiClient.getCourses({
                    level_id: levelId,
                    per_page: 50
                });

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
         * Check if a course is enrolled by the user
         */
        function isUserEnrolled(courseId) {
            return userEnrollments.includes(courseId);
        }

        /**
         * Check if a course is free (has free_subscription = true)
         */
        function isFreeCourse(courseId) {
            // Find the course in allCourses
            const course = allCourses.find(c => c.id === courseId);

            if (!course) {
                return false;
            }

            // Check if course has free_subscription = true
            const isFree = course.free_subscription === true || course.free_subscription === 1 || course.free_subscription === '1';
            return isFree;
        }

        /**
         * Get course status: 'enrolled', 'free', or null
         */
        function getCourseStatus(courseId) {
            const enrolled = isUserEnrolled(courseId);
            const free = isFreeCourse(courseId);


            if (enrolled) {
                return 'enrolled';
            }
            if (free) {
                return 'free';
            }
            return null;
        }

        /**
         * Display courses as subject cards with toggle switches
         *
         * Pricing is based on the selected subscription plan only
         * Individual course prices are NOT used
         *
         * Enrolled and free courses are checked and disabled by default
         */
        function displayCourses(courses) {
            const subjectContainer = document.getElementById('subjectContainer');

            const coursesHtml = courses.map((course, index) => {
                const courseStatus = getCourseStatus(course.id);
                const isDisabled = courseStatus !== null; // Disable if enrolled or free
                const isChecked = courseStatus !== null; // Check if enrolled or free

                let statusBadge = '';
                if (courseStatus === 'enrolled') {
                    statusBadge = '<span style="margin-left: 8px; padding: 4px 8px; background: #FF9800; color: white; border-radius: 4px; font-size: 11px; font-weight: 600;">ENROLLED</span>';
                } else if (courseStatus === 'free') {
                    statusBadge = '<span style="margin-left: 8px; padding: 4px 8px; background: #4CAF50; color: white; border-radius: 4px; font-size: 11px; font-weight: 600;">FREE</span>';
                }

                return `
                    <div class="subject-card d-flex align-items-center gap-2 justify-content-between" style="${isDisabled ? 'opacity: 0.7;' : ''}">
                        <div>
                            <h5 style="margin: 0 0 4px 0; display: flex; align-items: center;">
                                ${course.title}
                                ${statusBadge}
                            </h5>
                        </div>
                        <div class="form-check form-switch custom-switch">
                            <input class="form-check-input check-subject"
                                   type="checkbox"
                                   role="switch"
                                   data-course-id="${course.id}"
                                   data-course-status="${courseStatus || 'available'}"
                                   id="cb${index}"
                                   ${isChecked ? 'checked' : ''}
                                   ${isDisabled ? 'disabled' : ''}>
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
         * Update button states based on selections
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
            // Update the "Subscribe to All" button state
            updateEnrollAllButton();
        }

        /**
         * Update "Subscribe to All" button state
         *
         * When user subscribes to ALL subjects:
         * - Only count non-free, non-enrolled courses
         * - Regular price = Plan price × Number of paid subjects
         * - Discount = 10% off total
         * - Final price = Regular price - (Regular price × 10%)
         */
        function updateEnrollAllButton() {
            const enrollAllBtn = document.getElementById('enrollAllBtn');

            if (!selectedPlanId) {
                enrollAllBtn.disabled = true;
                return;
            }

            // Count only courses that are available for purchase (not enrolled, not free)
            const availableCourses = allCourses.filter(course => {
                const status = getCourseStatus(course.id);
                return status === null; // null means available for purchase
            });

            const paidCourseCount = availableCourses.length;
            const totalCourseCount = allCourses.length;

            // If subscription plan is selected, enable button
            const selectedPlan = allSubscriptionPlans.find(p => p.id == selectedPlanId);
            if (selectedPlan) {
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
         * Handle proceed to payment button (for individual selections)
         * Only includes checked subjects that are NOT disabled (enrolled or free)
         */
        document.getElementById('proceedBtn').addEventListener('click', function(e) {
            e.preventDefault();

            if (!selectedPlanId) {
                const warningModal = new bootstrap.Modal(document.getElementById('planSelectionWarningModal'));
                warningModal.show();
                return;
            }

            // Get only checked AND enabled checkboxes (exclude enrolled/free subjects)
            const checked = document.querySelectorAll('.check-subject:checked:not(:disabled)');

            if (checked.length === 0) {
                const warningModal = new bootstrap.Modal(document.getElementById('subjectSelectionWarningModal'));
                warningModal.show();
                return;
            }

            // Store payment data
            const selectedCourses = Array.from(checked).map(cb => cb.dataset.courseId);
            const selectedPlan = allSubscriptionPlans.find(p => p.id == selectedPlanId);
            const courseCount = checked.length;

            // Calculate price: plan price × number of selected subjects (no discount for partial selection)
            const regularPrice = selectedPlan.price * courseCount;

            pendingPaymentData = {
                courses: selectedCourses,
                planId: selectedPlanId,
                planPrice: selectedPlan.price,
                courseCount: courseCount,
                regularPrice: regularPrice,
                hasDiscount: false,
                isSubscription: true
            };

            // Populate payment confirmation modal
            const detailsHtml = `
                <div class="subscription-details">
                    <div class="detail-row">
                        <span class="detail-label">Plan:</span>
                        <span class="detail-value">${selectedPlan.title}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Subjects to Subscribe:</span>
                        <span class="detail-value">${courseCount}</span>
                    </div>
                    <hr>
                    <div class="detail-row">
                        <span class="detail-label">Price per Subject:</span>
                        <span class="detail-value">${formatNGN(selectedPlan.price)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Subtotal (${courseCount} × ${formatNGN(selectedPlan.price)}):</span>
                        <span class="detail-value">${formatNGN(regularPrice)}</span>
                    </div>
                    <hr>
                    <div class="detail-row total">
                        <span class="detail-label"><strong>Total Amount:</strong></span>
                        <span class="detail-value"><strong>${formatNGN(regularPrice)}</strong></span>
                    </div>
                </div>
            `;

            document.getElementById('paymentConfirmationDetails').innerHTML = detailsHtml;

            // Open payment confirmation modal
            const paymentConfirmationModal = new bootstrap.Modal(document.getElementById('paymentConfirmationModal'));
            paymentConfirmationModal.show();
        });

        /**
         * Handle "Subscribe to All" button
         *
         * Shows a modal with subscription details before proceeding to payment
         */
        document.getElementById('enrollAllBtn').addEventListener('click', function(e) {
            e.preventDefault();

            if (!selectedPlanId) {
                const warningModal = new bootstrap.Modal(document.getElementById('planSelectionWarningModal'));
                warningModal.show();
                return;
            }

            // Get all available courses (not enrolled, not free)
            const availableCourses = allCourses.filter(course => {
                const status = getCourseStatus(course.id);
                return status === null; // null means available for purchase
            });

            const selectedPlan = allSubscriptionPlans.find(p => p.id == selectedPlanId);
            const paidCourseCount = availableCourses.length;
            const totalCourseCount = allCourses.length;
            const freeCount = totalCourseCount - paidCourseCount;

            // Calculate price with 10% discount for subscribing to all subjects
            const regularPrice = selectedPlan.price * paidCourseCount;
            const discountAmount = regularPrice * 0.10;
            const discountedPrice = regularPrice - discountAmount;

            // Store data for payment
            pendingPaymentData = {
                courses: availableCourses.map(c => c.id),
                subtotal: formatNGN(discountedPrice),
                planId: selectedPlanId,
                planPrice: selectedPlan.price,
                courseCount: paidCourseCount,
                regularPrice: regularPrice,
                discountAmount: discountAmount,
                discountedPrice: discountedPrice,
                hasDiscount: true,
                isSubscription: true
            };

            // Populate and show modal
            const detailsHtml = `
                <div class="subscription-details">
                    <div class="detail-row">
                        <span class="detail-label">Plan:</span>
                        <span class="detail-value">${selectedPlan.title}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Subjects:</span>
                        <span class="detail-value">${totalCourseCount}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Free Subjects:</span>
                        <span class="detail-value">${freeCount}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Paid Subjects:</span>
                        <span class="detail-value">${paidCourseCount}</span>
                    </div>
                    <hr>
                    <div class="detail-row">
                        <span class="detail-label">Price per Subject:</span>
                        <span class="detail-value">${formatNGN(selectedPlan.price)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Subtotal (${paidCourseCount} × ${formatNGN(selectedPlan.price)}):</span>
                        <span class="detail-value">${formatNGN(regularPrice)}</span>
                    </div>
                    <div class="detail-row discount">
                        <span class="detail-label">Discount (10%):</span>
                        <span class="detail-value">-${formatNGN(discountAmount)}</span>
                    </div>
                    <hr>
                    <div class="detail-row total">
                        <span class="detail-label"><strong>Total Amount:</strong></span>
                        <span class="detail-value"><strong>${formatNGN(discountedPrice)}</strong></span>
                    </div>
                </div>
            `;

            document.getElementById('subscribeAllDetails').innerHTML = detailsHtml;
            const modal = new bootstrap.Modal(document.getElementById('subscribeAllModal'));
            modal.show();
        });

        /**
         * Handle "Proceed to Payment" button in Subscribe All modal
         */
        document.getElementById('proceedToPaymentBtn').addEventListener('click', function(e) {
            e.preventDefault();

            if (!pendingPaymentData) {
                alert('Payment data not found. Please try again.');
                return;
            }

            // Populate payment confirmation modal
            const detailsHtml = `
                <div class="subscription-details">
                    <div class="detail-row">
                        <span class="detail-label">Plan:</span>
                        <span class="detail-value">${allSubscriptionPlans.find(p => p.id == pendingPaymentData.planId).title}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Subjects to Subscribe:</span>
                        <span class="detail-value">${pendingPaymentData.courseCount}</span>
                    </div>
                    <hr>
                    <div class="detail-row">
                        <span class="detail-label">Price per Subject:</span>
                        <span class="detail-value">${formatNGN(pendingPaymentData.planPrice)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Subtotal (${pendingPaymentData.courseCount} × ${formatNGN(pendingPaymentData.planPrice)}):</span>
                        <span class="detail-value">${formatNGN(pendingPaymentData.regularPrice)}</span>
                    </div>
                    ${pendingPaymentData.hasDiscount ? `
                    <div class="detail-row discount">
                        <span class="detail-label">Discount (10%):</span>
                        <span class="detail-value">-${formatNGN(pendingPaymentData.discountAmount)}</span>
                    </div>
                    ` : ''}
                    <hr>
                    <div class="detail-row total">
                        <span class="detail-label"><strong>Total Amount:</strong></span>
                        <span class="detail-value"><strong>${pendingPaymentData.hasDiscount ? formatNGN(pendingPaymentData.discountedPrice) : formatNGN(pendingPaymentData.regularPrice)}</strong></span>
                    </div>
                </div>
            `;

            document.getElementById('paymentConfirmationDetails').innerHTML = detailsHtml;

            // Close the subscribe all modal
            const subscribeAllModal = bootstrap.Modal.getInstance(document.getElementById('subscribeAllModal'));
            if (subscribeAllModal) {
                subscribeAllModal.hide();
            }

            // Open payment confirmation modal
            const paymentConfirmationModal = new bootstrap.Modal(document.getElementById('paymentConfirmationModal'));
            paymentConfirmationModal.show();
        });

        /**
         * Handle "Proceed to Payment Gateway" button in Payment Confirmation modal
         */
        document.getElementById('confirmPaymentBtn').addEventListener('click', function(e) {
            e.preventDefault();

            // Close the payment confirmation modal
            const paymentConfirmationModal = bootstrap.Modal.getInstance(document.getElementById('paymentConfirmationModal'));
            if (paymentConfirmationModal) {
                paymentConfirmationModal.hide();
            }

            // Open payment gateway modal
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

                            // Emit event for global data refresh
                            if (window.DataRefreshService) {
                                await DataRefreshService.emit(DataRefreshService.EVENTS.COURSE_ENROLLED, {
                                    course_id: courseId,
                                    payment_method: 'wallet'
                                });
                            }
                        } else {
                            failureCount++;
                            console.error(`Failed to purchase course ${courseId}:`, result.message);
                        }
                    }

                    if (successCount > 0) {
                        showSuccessMessage(`Successfully purchased ${successCount} course(s) via Kudikah Wallet!`);

                        // Emit wallet updated event
                        if (window.DataRefreshService) {
                            await DataRefreshService.emit(DataRefreshService.EVENTS.WALLET_UPDATED, {
                                courses_purchased: successCount
                            });
                        }

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
