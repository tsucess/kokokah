@extends('layouts.dashboardtemp')

@section('content')
<main class="subjects-main">
 <section class="d-flex flex-column gap-3 p-4">
        <div class="d-flex align-items-center gap-3">
            <button><i class="fa-solid fa-arrow-left" style="color: #000000;"></i></button>
            <p class="back-arrow">Back to Notifications & Announcements</p>
        </div>
        <header class="d-flex justify-content-between">
            <div class="d-flex flex-column gap-2">
                <h1>Create New Announcement</h1>
                <p class="announcement-subtitle">Fill in the details below to publish a new announcement for students.</p>
            </div>
            <div class="d-flex gap-3 align-items-center justify-content-center w-50">
                <button class="cancel-btn announment-btn">Cancel</button>
                <button class="draft-btn announment-btn">Save As Draft</button>
                <button class="publish-btn announment-btn">Publish</button>
            </div>
        </header>
        <section class="container-fluid">
            <div class="row g-4">
                <section class="col col-12 col-lg-7 d-flex flex-column gap-5">
                    <div class="d-flex flex-column gap-5">
                        <div class="d-flex flex-column input-container">
                            <label for="" class="label">Announcement Title</label>
                            <input type="text" name="" id="" placeholder="winner" class="input-text">
                        </div>
                        <div class="d-flex flex-column input-container">
                            <label for="" class="label">Select Feedback Type *</label>
                            <select name="" id="">
                                <option value="">Exams</option>
                            </select>
                        </div>
                    </div>
                    <div class="priority-container d-flex flex-column">
                        <h6 class="priority-title">Priority</h6>
                        <div class="d-flex gap-3 align-items-center">
                            <div class="badge d-flex justify-content-center align-items-center preview-card-badge "><i class="fa-solid fa-circle-info" style="color: #000000;"></i>Info</div>
                            <div class="badge d-flex justify-content-center align-items-center preview-card-badge urgent-badge"><i class="fa-solid fa-circle-info" style="color: #F56824"></i>Urgent</div>
                            <div class="badge d-flex justify-content-center align-items-center preview-card-badge warning-badge"><i class="fa-solid fa-circle-info" style="color: #FDAF22"></i>Warning</div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-5">
                        <div class="d-flex flex-column input-container">
                            <label for="" class="label">Audience</label>
                            <select name="" id="">
                                <option value="">All students</option>
                            </select>
                        </div>
                        <div class="d-flex flex-column input-container">
                            <label for="" class="label">Date & Time (optional)</label>
                            <input type="text" name="" id="" placeholder="dd/mm/yyyy" class="input-text">
                        </div>
                        <div class="d-flex flex-column textarea-container">
                            <label for="" class="label">Description</label>
                            <textarea type="text" name="" id="" placeholder="Write announcement details here...." class="textarea-text"></textarea>
                        </div>

                    </div>

                </section>
                <article class="col col-12 col-lg-5 d-flex flex-column preview-container">
                    <h5 class="preview-title">Preview</h5>
                    <div class="preview-card">
                        <div class="d-flex align-items-center gap-3">
                            <h6 class="preview-card-title">Mid-term Examination Schedule Released</h6>
                            <div class="badge d-flex justify-content-center align-items-center preview-card-badge "><i class="fa-solid fa-circle-info" style="color: #000000;"></i>Info</div>
                        </div>
                        <p class="preview-card-text">The mid-term examination schedule for all grades has been published. Students can check their exam dates and timings on the student portal. Please ensure you're well-prepared. The mid-term examination schedule for all grades has
                            been published. Students can check their exam dates and timings on the student portal. Please ensure you're well-prepared.</p>
                    </div>
                </article>
            </div>
        </section>


    </section>
 </main>
@endsection