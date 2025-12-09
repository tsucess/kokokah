@extends('layouts.usertemplate')


@section('content')
<main class="subjects-main">

        <section class="d-flex gap-5 flex-column py-4 container px-5">


            <section class=" d-flex flex-column" style="gap: 30px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1>Notifications & Announcements</h1>
                        <p class="text">Stay updated with the latest news and updates from your school/instructor.</p>
                    </div>
                    <a href="/createannouncement" class="d-flex gap-1 align-items-center py-2 px-3 fs-6 announcement-btn ms-auto"><i class="fa-solid fa-plus"></i> Create New Announcement</a>

                </div>

                <div class="d-flex flex-column " style="gap: 75px;">
                    <div class="row rounded-pill p-2 tab">
                        <div class=" rounded-pill d-flex justify-content-center gap-1  align-items-center col tab-text bg-light">
                            <i class="fa-solid fa-bell"></i> All (20)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1 align-items-center col tab-text">
                            <i class="fa-solid fa-bell"></i> Exams (10)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1 align-items-center col tab-text">
                            <i class="fa-solid fa-bell"></i> Events (5)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1 align-items-center col tab-text">
                            <i class="fa-solid fa-bell"></i> Alert (4)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1 align-items-center col tab-text">
                            <i class="fa-solid fa-bell"></i> General Info (20)
                        </div>
                    </div>
                    <div class='d-flex flex-column notification-container '>
                        <div class="d-flex gap-2 justify-content-between align-items-start">
                            <div class="d-flex flex-column" style="gap: 14px;">
                                <div class="d-flex gap-5 align-items-center">
                                    <h5 class=" fw-semibold notification-title">Mid-term Examination Schedule Released</h5>
                                    <div class="rounded-pill d-flex justify-content-center align-items-center notification-label"><i class="fa-solid fa-circle-info "></i>Info</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center fw-semibold notification-category ">Exam</div>
                            </div>
                            <button class="button"><i class="fa-solid fa-ellipsis-vertical "></i></button>

                        </div>
                        <div class="d-flex gap-1 align-items-center">
                            <i class="fa-solid fa-clock"></i>
                            <span class="notification-date">1 day ago</span>
                        </div>
                        <p class="notification-text">The mid-term examination schedule for all grades has been published. Students can check their exam dates and timings on the student portal. Please ensure you're well-prepared. The mid-term examination schedule for all grades has
                            been published. Students can check their exam dates and timings on the student portal. Please ensure you're well-prepared.</p>

                    </div>
                    <div class='d-flex flex-column notification-container '>
                        <div class="d-flex gap-2 justify-content-between align-items-start">
                            <div class="d-flex flex-column" style="gap: 14px;">
                                <div class="d-flex gap-5 align-items-center">
                                    <h5 class=" fw-semibold notification-title">Mid-term Examination Schedule Released</h5>
                                    <div class="rounded-pill d-flex justify-content-center align-items-center notification-label"><i class="fa-solid fa-circle-info "></i>Info</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center fw-semibold notification-category ">Exam</div>
                            </div>
                            <button class="button"><i class="fa-solid fa-ellipsis-vertical "></i></button>

                        </div>
                        <div class="d-flex gap-1 align-items-center">
                            <i class="fa-solid fa-clock"></i>
                            <span class="notification-date">1 day ago</span>
                        </div>
                        <p class="notification-text">The mid-term examination schedule for all grades has been published. Students can check their exam dates and timings on the student portal. Please ensure you're well-prepared. The mid-term examination schedule for all grades has
                            been published. Students can check their exam dates and timings on the student portal. Please ensure you're well-prepared.</p>

                    </div>


                </div>
            </section>
        </section>
    </main>
@endsection

