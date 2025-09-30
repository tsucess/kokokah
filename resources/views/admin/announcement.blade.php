@extends('admin.usertemplate')

@section('content')
<main>
<div class="container py-4" style="max-width: 1000px;">

    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: var(--color-primary-button: #004A53);">Announcements</h3>
        <p class="text-muted">Stay updated with the latest news and updates from your school/instructor.</p>
    </div>

    <div class="announcements-wrapper">

        <div class="d-none d-md-flex announcement-header announcement-row">
            <div class="col-announcement"><b>Announcement Tittle</b></div>
            <div class="col-message"><b>Message</b></div>
            <div class="col-type"><b>Type</b></div>
            <div class="col-date"><b>Date</b></div>
        </div>

        <div class="announcement-row">
            <div class="col-announcement">Workshop: AI Bootcamp</div>
            <div class="col-message d-flex justify-content-start justify-content-md-center">
                <button class="btn btn-view-note">View note</button>
            </div>
            <div class="col-type">Students</div>
            <div class="col-date">23 August 2025</div>
        </div>

        <div class="announcement-row">
            <div class="col-announcement">Workshop: AI Data Science Bootcamp</div>
            <div class="col-message d-flex justify-content-start justify-content-md-center">
                <button class="btn btn-view-note">View note</button>
            </div>
            <div class="col-type">Students and Teachers</div>
            <div class="col-date">23 August 2025</div>
        </div>

        <div class="announcement-row">
            <div class="col-announcement">Hackathon: Innovation 2026</div>
            <div class="col-message d-flex justify-content-start justify-content-md-center">
                <button class="btn btn-view-note">View note</button>
            </div>
            <div class="col-type">All Students and Alumni</div>
            <div class="col-date">23 August 2025</div>
        </div>

        <div class="announcement-row">
            <div class="col-announcement">Workshop: AI Data Science Bootcamp</div>
            <div class="col-message d-flex justify-content-start justify-content-md-center">
                <button class="btn btn-view-note">View note</button>
            </div>
            <div class="col-type">Students and Teachers</div>
            <div class="col-date">23 August 2025</div>
        </div>

        <div class="announcement-row">
            <div class="col-announcement">Workshop: AI Bootcamp</div>
            <div class="col-message d-flex justify-content-start justify-content-md-center">
                <button class="btn btn-view-note">View note</button>
            </div>
            <div class="col-type">Students</div>
            <div class="col-date">23 August 2025</div>
        </div>

        <div class="announcement-row" style="border-bottom: none;">
            <div class="col-announcement">Hackathon: Innovation 2026</div>
            <div class="col-message d-flex justify-content-start justify-content-md-center">
                <button class="btn btn-view-note">View note</button>
            </div>
            <div class="col-type">All Students and Alumni</div>
            <div class="col-date">23 August 2025</div>
        </div>

    </div>

</div>

</main>
@endsection
