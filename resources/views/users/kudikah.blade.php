@extends('admin.usertemplate')

@section('content')
<main>

    <div class="container py-4" style="max-width: 800px;">

    <div class="balance-header">
        <small class="opacity-75">Total Balance</small>
        <span class="d-inline-block opacity-75 ms-2">@</span>
        <h1 class="main-balance-text mt-2 mb-2">₦400,000.00</h1>
        <p class="small opacity-75 mb-0">444 221 224 ***</p>
        <i class="bi bi-bank text-white fs-3 position-absolute" style="bottom: 20px; right: 20px; opacity: 0.5;"></i>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-12">
            <button class="btn secondaryButton w-100 py-3 rounded-3">
                <i class="bi bi-plus me-2"></i> Add Money
            </button>
        </div>
        <div class="col-12">
            <button class="btn primaryButton w-100 py-3 rounded-3">
                Enroll a class
            </button>
        </div>
    </div>

    <h5 class="fw-bold mb-3">Transaction History</h5>

    <div class="d-flex justify-content-end gap-2 mb-4">
        <div class="dropdown">
            <button class="btn filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                All Categories
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Transfers</a></li>
                <li><a class="dropdown-item" href="#">Deposits</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <button class="btn filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                All Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Completed</a></li>
                <li><a class="dropdown-item" href="#">Pending</a></li>
            </ul>
        </div>
    </div>

    <div class="transaction-list">

        <div class="transaction-item">
            <div class="transaction-details">
                <div class="transaction-icon-container">
                    <i class="bi bi-arrow-left-right"></i> </div>
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

</main>
@endsection
