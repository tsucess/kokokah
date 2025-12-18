@extends('layouts.dashboardtemp')

@section('content')
    <main>
        <section class="d-flex flex-column rating-container">
            <header class="m-4 d-flex flex-column rating-header">
                <h1>Reviews & Ratings</h1>
                <p class="rating-subtitle">
                    See what other learners think about the subject.
                </p>
            </header>
            <section class="container-fluid">
                <div class="row g-4">
                    <div class="col col-12 col-lg-6">
                        <div class="review-container">
                            <header class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column review-header-title-container">
                                    <h4 class="review-header-title">English Language</h4>
                                    <div class="d-flex gap align-items-center">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <p class="review-header-subtitle">Based on 2000 reviews</p>
                                </div>
                                <button class="review-btn">View Review</button>
                            </header>
                            <div class="d-flex flex-column gap-2">
                                <h5 class="review-distribution">Rating Distribution</h5>
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 153px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 99px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 63px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 45px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 27px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-12 col-lg-6">
                        <div class="review-container">
                            <header class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column review-header-title-container">
                                    <h4 class="review-header-title">English Language</h4>
                                    <div class="d-flex gap align-items-center">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <p class="review-header-subtitle">Based on 2000 reviews</p>
                                </div>
                                <button class="review-btn">View Review</button>
                            </header>
                            <div class="d-flex flex-column gap-2">
                                <h5 class="review-distribution">Rating Distribution</h5>
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 153px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 99px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 63px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 45px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 27px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-12 col-lg-6">
                        <div class="review-container">
                            <header class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column review-header-title-container">
                                    <h4 class="review-header-title">English Language</h4>
                                    <div class="d-flex gap align-items-center">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <p class="review-header-subtitle">Based on 2000 reviews</p>
                                </div>
                                <button class="review-btn">View Review</button>
                            </header>
                            <div class="d-flex flex-column gap-2">
                                <h5 class="review-distribution">Rating Distribution</h5>
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 153px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 99px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 63px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 45px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 27px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-12 col-lg-6">
                        <div class="review-container">
                            <header class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column review-header-title-container">
                                    <h4 class="review-header-title">English Language</h4>
                                    <div class="d-flex gap align-items-center">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <p class="review-header-subtitle">Based on 2000 reviews</p>
                                </div>
                                <button class="review-btn">View Review</button>
                            </header>
                            <div class="d-flex flex-column gap-2">
                                <h5 class="review-distribution">Rating Distribution</h5>
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 153px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 99px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 63px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 45px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap">
                                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                            <i class="fa-solid fa-star" style="color: #e5e6e7"></i>
                                        </div>
                                        <div class="progress-bar">
                                            <span class="progress-bar-track" style="width: 27px"></span>
                                        </div>
                                        <span class="review-header-rating">4.5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const viewBtns = document.querySelectorAll('button.review-btn')
            viewBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    window.location.href = '/ratingdetails'
                })

            });
        }) 
    </script>
@endsection
