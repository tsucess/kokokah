@extends('layouts.usertemplate')

@section('content')
<main>
<div class="container-fluid pt-4 pb-5" style="max-width: 1400px;">

    <div class="row mb-4">
        <div class="col-12">
            <p class="text-muted mb-4"><i class="fa-solid fa-chevron-left me-2"></i> Back</p>
        </div>

        <div class="col-12 d-flex flex-column flex-md-row gap-3 mb-4">
            <button class="term-btn active">First Term</button>
            <button class="term-btn">Second Term</button>
            <button class="term-btn">Third Term</button>
        </div>

        <div class="col-12 mb-5">
            <div class="d-flex overflow-auto py-2 border-bottom border-top">
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">1</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">2</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">3</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">4</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">5</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">6</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">7</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">8</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">9</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">10</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">11</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">12</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">13</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">14</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">15</button>
                <button class="btn btn-sm mx-1 text-nowrap rounded-0 answered ">16</button>
                <button class="btn btn-sm btn-outline-secondary mx-1 text-nowrap rounded-0 unanswered">1</button>
                <button class="btn btn-sm btn-outline-secondary mx-1 text-nowrap rounded-0 unanswered">2</button>
                <button class="btn btn-sm btn-outline-secondary mx-1 text-nowrap rounded-0 unanswered">3</button>
                <button class="btn btn-sm btn-outline-secondary mx-1 text-nowrap rounded-0 unanswered">4</button>
                <button class="btn btn-sm btn-outline-secondary mx-1 text-nowrap rounded-0 unanswwered">5</button>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-12 col-lg-8">

            <div class="lesson-item">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5>Lesson 13. Part of Speech</h5>
                        <p>
                            This topic covers the fundamentals of creating effective layouts and compositions for websites that provide a better user experience.
                        </p>
                        <hr style = "border: 1px solid black">
                    </div>
                    {{-- <i class="bi bi-check-circle-fill text-success fs-4 d-none d-sm-block"></i> --}}

                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Completed Homework</span>
                        <span class="completed-badge">0/1</span>
                    </div>
                    <button class="btn-lesson">
                         Lesson</button>
                </div>
            </div>

            <div class="lesson-item" style="background-color: #fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class = "text-dark">Lesson 13. Part of Speech
                        <span class = "float-end"><input type = "checkbox" checked></span>
                        </h5>
                        <p class = "text-dark">
                            This topic covers the fundamentals of creating effective layouts and compositions for websites that provide a better user experience.
                        </p>
                        <hr style = "border: 1px solid black">
                    </div>
                    </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Completed Homework</span>
                        <span class="completed-badge" style = "background: #004A53; color:#fff;">0/1</span> </div>
                    <button class="btn-lesson" style="background: #A3D8DF; color:#fff;">Go to Lesson</button>
                </div>
            </div>

             <div class="lesson-item" style="background-color: #fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class = "text-dark">Lesson 13. Part of Speech
                            <span class = "float-end"><input type = "checkbox" checked></span>
                        </h5>
                        <p class = "text-dark">
                            This topic covers the fundamentals of creating effective layouts and compositions for websites that provide a better user experience.
                        </p>
                        <hr style = "border: 1px solid black">
                    </div>
                    </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Completed Homework</span>
                         <span class="completed-badge" style = "background: #004A53; color:#fff;">0/1</span> </div>
                    <button class="btn-lesson" style="background: #A3D8DF; color:#fff;">Go to Lesson</button>
                </div>
            </div>


             <div class="lesson-item" style="background-color: #fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class = "text-dark">Lesson 13. Part of Speech
                            <span class = "float-end"><input type = "checkbox" checked></span>
                        </h5>
                        <p class = "text-dark">
                            This topic covers the fundamentals of creating effective layouts and compositions for websites that provide a better user experience.
                        </p>
                        <hr style = "border: 1px solid black">
                    </div>
                    </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Completed Homework</span>
                         <span class="completed-badge" style = "background: #004A53; color:#fff;">0/1</span> </div>
                    <button class="btn-lesson" style="background: #A3D8DF; color:#fff;">Go to Lesson</button>
                </div>
            </div>

        </div>

        <div class="col-12 col-lg-4">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    {{-- <h6 class="fw-bold mb-3">Rating in The Group</h6> --}}

                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted">
                            <span class = "text-dark">Rating In The Group</span>
                            <span>1
                                {{-- <i class="fa-solid fa-ribbon ms-2 text-success"></i> --}}
                                <img src = "images/celebrate.png" class = "img-fluid" style = "width:15px; height:15px;">
                            </span>

                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 82%; background-color: #114243;" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Average Point</span>
                            <span>82%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 82%; background-color: #114243;" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Subject Completed</span>
                            <span>91%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 91%; background-color: #114243;" aria-valuenow="91" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">This Subject Includes</h6>

                    <ul class="list-unstyled small">
                        <li class="mb-2"> <img src = "images/celebrate.png" class = "img-fluid" style = "width:15px; height:15px;"> 65 hours on demand videos</li>
                        <li class="mb-2"> <img src = "images/celebrate.png" class = "img-fluid" style = "width:15px; height:15px;"> 45 downloadable resources</li>
                        <li class="mb-2"> <img src = "images/celebrate.png" class = "img-fluid" style = "width:15px; height:15px;"> Access on mobile and laptop</li>
                        <li class="mb-2">  <img src = "images/celebrate.png" class = "img-fluid" style = "width:15px; height:15px;"> 86 Articles</li>
                        <li class="mb-0">  <img src = "images/celebrate.png" class = "img-fluid" style = "width:15px; height:15px;"> Certification of completion</li>
                    </ul>
                </div>
            </div>

        </div>

    </div>

    <div class="chat-btn-circle">
        <i class="fa-solid fa-comment"></i>
    </div>

</div>
</main
@endsection
