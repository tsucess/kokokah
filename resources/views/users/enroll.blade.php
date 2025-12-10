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
    <script>
        // Format numbers as NGN currency with thousands separator and two decimals
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

        // optional: clicking button can show selected count (demo)
        document.getElementById('proceedBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const checked = document.querySelectorAll('.check-subject:checked').length;
            if (checked === 0) {
                alert('Please select at least one subject to proceed.');
            } else {
                alert('Proceeding with ' + checked + ' subject(s). Subtotal: ' + document.getElementById('subtotal')
                    .textContent);
            }
        });
    </script>
@endsection
