@extends('layouts.dashboardtemp')

@section('content')
    <style>
        .stats-container{
            border: 1px solid #CCDBDD;
            padding: 30px;
            gap: 30px;
            border-radius: 20px;
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
            <section>
                <div class="stats-container d-flex flex-column"></div>
            </section>
        </div>
    </main>
@endsection
