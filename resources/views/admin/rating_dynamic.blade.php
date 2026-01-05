@extends('layouts.dashboardtemp')

@section('content')
    <main>
        <section class="d-flex flex-column rating-container">
            <header class="m-4 d-flex flex-column rating-header">
                <h1>Reviews & Ratings</h1>
                <p class="rating-subtitle">
                    See what learners think about your courses.
                </p>
            </header>
            <section class="container-fluid">
                <div class="row g-4">
                    @forelse($courses as $course)
                    <div class="col col-12 col-lg-6">
                        <div class="review-container">
                            <header class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column review-header-title-container">
                                    <h4 class="review-header-title">{{ $course['title'] }}</h4>
                                    <div class="d-flex gap align-items-center">
                                        <div class="d-flex align-items-center gap">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star" style="color: {{ $i <= round($course['average_rating']) ? '#fdaf22' : '#e5e6e7' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="review-header-rating">{{ number_format($course['average_rating'], 1) }}</span>
                                    </div>
                                    <p class="review-header-subtitle">Based on {{ $course['total_reviews'] }} reviews</p>
                                </div>
                                <a href="{{ route('admin.rating.show', $course['id']) }}" class="review-btn">View Review</a>
                            </header>
                            <div class="d-flex flex-column gap-2">
                                <h5 class="review-distribution">Rating Distribution</h5>
                                <div class="d-flex flex-column gap-1">
                                    @for($rating = 5; $rating >= 1; $rating--)
                                        @php
                                            $count = $course['rating_distribution'][$rating] ?? 0;
                                            $total = $course['total_reviews'] ?: 1;
                                            $percentage = ($count / $total) * 100;
                                            $barWidth = max(27, $percentage * 2);
                                        @endphp
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa-solid fa-star" style="color: {{ $i <= $rating ? '#fdaf22' : '#e5e6e7' }}"></i>
                                                @endfor
                                            </div>
                                            <div class="progress-bar">
                                                <span class="progress-bar-track" style="width: {{ $barWidth }}px"></span>
                                            </div>
                                            <span class="review-header-rating">{{ $count }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col col-12">
                        <div class="alert alert-info" role="alert">
                            <i class="fa-solid fa-info-circle"></i>
                            No courses found. Create a course to start receiving reviews.
                        </div>
                    </div>
                    @endforelse
                </div>
            </section>
        </section>
    </main>
@endsection

