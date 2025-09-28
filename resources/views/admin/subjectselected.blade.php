@extends('admin.usertemplate')

@section('content')
<main>
<div class="container-fluid pt-4 pb-5" style="max-width: 1400px;">

    <div class="row g-0">

        <div class="col-12 col-lg-8 pe-lg-4">

            <p class="text-muted back-nav mb-4"><i class="bi bi-arrow-left me-2"></i> Back</p>

            <div class="main-content-card">

                <div class="ratio ratio-16x9">
                    <img src="images/Video.png"
                         alt="Video Content"
                         class="img-fluid"
                         style="object-fit: cover;" >
                </div>

                {{-- <div class="d-flex border-bottom px-3 pt-2">
                    <button class="lesson-tab-btn active">Material & Links</button>
                    <button class="lesson-tab-btn">Home Work</button>
                    <button class="lesson-tab-btn">Note</button>
                </div> --}}

                  <!-- Nav pills -->
  <ul class="nav nav-pills mt-3" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-bs-toggle="pill" href="#material">Material & Links</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="pill" href="#work">Home Work</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="pill" href="#note">Note</a>
    </li>
        <li class="nav-item">
      <a class="nav-link" data-bs-toggle="pill" href="#quiz">Quiz</a>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="material" class="container tab-pane active"><br>
      <p class="text-muted small">
         This topic covers the fundamentals of creating effective layouts and
         compositions for websites that provide a better user experience.
    </p>
        <div class="p-4">

                    <div class="d-flex">
                        <a href="#" class="btn btn-download-link d-flex align-items-center" style = "font-size:14px;" download>
                           <i class="fa-solid fa-book me-2 text-secondary"></i> Lectures in PDF format.pdf <i class="bi bi-download ms-2"></i>
                        </a>
                        <a href="#" class="btn btn-download-link d-flex align-items-center" style = "font-size:14px;" download>
                           <i class="fa-solid fa-book me-2 text-secondary"></i> Lectures in PDF format.pdf <i class="bi bi-download ms-2"></i>
                        </a>
                    </div>
                </div>

    </div>
    <div id="work" class="container tab-pane fade"><br>

        <div class="p-4">

                    <div class="d-flex">
                        <a href="#" class="btn btn-download-link d-flex align-items-center" style = "font-size:14px;" download>
                           <i class="fa-solid fa-book me-2 text-secondary"></i> Lectures in PDF format.pdf <i class="bi bi-download ms-2"></i>
                        </a>
                        <a href="#" class="btn btn-download-link d-flex align-items-center" style = "font-size:14px;" download>
                           <i class="fa-solid fa-book me-2 text-secondary"></i> Lectures in PDF format.pdf <i class="bi bi-download ms-2"></i>
                        </a>
                    </div>
                </div>
<p class = "text-muted small">
        <b>Task:</b>This topic covers the fundamentals of
         creating effective layouts and compositions for
         websites that provide a better user experience.
      </p>
      <button class="btn secondaryButton" type="button">Pass Homework</button>
    </div>
    <div id="note" class="container tab-pane fade"><br>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    </div>
    <div id="quiz" class="container tab-pane fade"><br>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    </div>
  </div>



            </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
            <div class="sidebar-messaging">
                <div class="flex-grow-1">
                    </div>

                <div class="message-input-area">
                    <p class="small text-muted mb-3 d-flex align-items-center">
                        <i class="bi bi-paperclip me-2"></i> Message to kodie...
                    </p>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-3 text-secondary fs-5">
                            <i class="bi bi-camera-fill"></i>
                            <i class="bi bi-emoji-smile-fill"></i>
                            <i class="bi bi-mic-fill"></i>
                        </div>
                        <button class="btn btn-send rounded-pill d-flex align-items-center">
                            Send <i class="bi bi-send-fill ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</main>
@endsection
