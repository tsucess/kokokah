@extends('layouts.dashboardtemp')

@section('content')
<style>
        .review-header-title {
            font-size: 32px;
            font-family: "Fredoka", sans-serif;
            font-weight: 600;
            color: #004A53;
        }

        .rating-text {
            color: #004A53;
            font-size: 16px;
        }

        .rating-header-subtitle {
            color: #1C1D1D;
            font-size: 18px;
        }

        .rating-distribution-container {
            max-width: 682px;
            width: 100%;
            gap: 16px;
        }

        .rating-distribution-title {
            color: #000000;
            font-size: 20px;
            font-weight: 700;
        }

        .rating-distribution-rating-container {
            gap: 14px;
        }

        .progress-bar {
            width: 198px;
            height: 6px;
            border-radius: 3px;
            background-color: #78787833;
        }

        .progress-bar-track {
            background-color: #004a53;
            height: 6px;
            border-radius: 3px;
        }

        .reviews-container {
            gap: 16px;
        }

        .reviews-header-title {
            color: #000000;
            font-size: 20px;
        }

        .reviews-btn {
            color: #777777;
            font-size: 12px;
        }

        button {
            background-color: transparent;
            border: none;
        }

        .most-recent-container {
            width: 107px;
            height: 27px;
            background-color: #F5F4F9;
            border-radius: 6px;
            font-size: 12px;
            color: #777777;
            outline: none;
            border: none;
            padding: 1px;
        }

        .reviews-card {
            border-radius: 10px;
            padding: 24px;
            gap: 16px;
            box-shadow: 0 11px 24px 0 rgba(0, 0, 0, 0.11);
        }

        .reviews-card-date {
            color: #999999;
            font-size: 12px;
        }

        .reviews-card-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: fill;
        }

        .reviews-card-name {
            font-size: 14px;
            color: #333333;
            font-weight: 700;
        }

        .reviews-card-review {
            color: #555555;
            font-size: 14px;
        }

        .reviews-card-hepful {
            color: #999999;
            font-size: 12px;
        }

        .footer-pagecount {
            color: #CBCCCD;
            font-size: 12.9px;
        }

        .footer-btn {
            border-radius: 6px;
            border: 1px solid #DEDEDE;
            color: #404040;
            font-weight: 500;
            width: 63px;
            height: 21px;
            font-size: 10px;
        }
    </style>
    <main class="">
         <section class="container-fluid p-4 d-flex gap-5 flex-column">
        <header class="d-flex gap-1 flex-column">
            <h2 class="review-header-title">English Language</h2>
            <div class="d-flex gap-1 align-items-center">
                <div class="d-flex gap-1">
                    <i class="fa-solid fa-star" style="color : #FDAF22;"></i>
                    <i class="fa-solid fa-star" style="color : #FDAF22;"></i>
                    <i class="fa-solid fa-star" style="color : #FDAF22;"></i>
                    <i class="fa-solid fa-star" style="color : #FDAF22;"></i>
                    <i class="fa-solid fa-star" style="color: #E5E6E7;"></i>

                </div>
                <span class="rating-text">4.5</span>
            </div>
            <p class="rating-header-subtitle">Based on 2000 reviews</p>
        </header>
        <section class="rating-distribution-container">
            <h4 class="d-flex flex-column rating-distribution-title">Rating Distribution</h4>
            <div class="d-flex flex-column  rating-distribution-rating-container">
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
                    <span class="rating-text">4.5</span>
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
                    <span class="rating-text">4.5</span>
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
                        <span class="progress-bar-track" style="width: 65px"></span>
                    </div>
                    <span class="rating-text">4.5</span>
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
                    <span class="rating-text">4.5</span>
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
                        <span class="progress-bar-track" style="width: 26px"></span>
                    </div>
                    <span class="rating-text">4.5</span>
                </div>

            </div>
        </section>
        <section class="d-flex flex-column reviews-container">
            <header class="d-flex gap-4 align-items-center justify-content-between">
                <h4 class="reviews-header-title">Reviews</h4>
                <div class="d-flex gap-1 align-items-center">
                    <button class="reviews-btn"> Sort by</button>
                    <select name="" id="" class="most-recent-container">
                        <option value="" class="reviews-btn">Most Recent</option>
                    </select>
                    <button class="reviews-btn">Filter by</button>
                </div>
            </header>
            <article class="d-flex flex-column reviews-card">
                <div class="d-flex gap-3 justify-content-between">
                    <div class="d-flex flex-column gap-2 ">
                        <div class="d-flex gap-1 align-items-center">
                            <img src="./images/winner.png" alt="" class="reviews-card-img">
                            <h5 class="reviews-card-name">Ayomide Peters</h5>
                        </div>
                        <div class="d-flex align-items-center gap">
                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                            <i class="fa-solid fa-star" style="color: #9B9B9B"></i>
                        </div>
                    </div>
                    <p class="align-self-end reviews-card-date mb-0 ">April 5, 2024</p>
                </div>
                <div class="d-flex flex-column gap-3">
                    <p class="reviews-card-review ">English Language has become my favorite subject this term. The lessons were clear and interactive, especially the reading comprehension exercises. I feel more confident in my writing now.</p>
                    <div class="d-flex gap-3 align-self-end align-items-center">
                        <span class="reviews-card-hepful">Helpful</span>
                        <button><i class="fa-solid fa-thumbs-up" style="color: #999999;"></i></button>
                    </div>
                </div>
            </article>
            <article class="d-flex flex-column reviews-card">
                <div class="d-flex gap-3 justify-content-between">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex gap-1 align-items-center">
                            <img src="./images/winner.png" alt="" class="reviews-card-img">
                            <h5 class="reviews-card-name">Ayomide Peters</h5>
                        </div>
                        <div class="d-flex align-items-center gap">
                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                            <i class="fa-solid fa-star" style="color: #fdaf22"></i>
                            <i class="fa-solid fa-star" style="color: #9B9B9B"></i>
                        </div>
                    </div>
                    <p class="align-self-end reviews-card-date mb-0">April 5, 2024</p>
                </div>
                <div class="d-flex flex-column gap-3">
                    <p class="reviews-card-review">English Language has become my favorite subject this term. The lessons were clear and interactive, especially the reading comprehension exercises. I feel more confident in my writing now.</p>
                    <div class="d-flex gap-3 align-self-end align-items-center">
                        <span class="reviews-card-hepful">Helpful</span>
                        <button><i class="fa-solid fa-thumbs-up" style="color: #999999;"></i></button>
                    </div>
                </div>
            </article>
        </section>
        <footer class="d-flex gap-2 align-items-center justify-content-between">
            <button class="footer-btn">Previous</button>
            <p class="footer-pagecount">Page1of 12</p>
            <button class="footer-btn">Next</button>
        </footer>
    </section>
    </main>
@endsection
