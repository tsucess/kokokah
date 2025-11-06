@extends('layouts.dashboardtemp')


@section('content')
<main>
    <div class = "container">

        <div class = "d-flex justify-content-between">

            <div>
            <h4 class = "fw-bold">Create New Subject</h4>
            <p>Here overview of your</p>
        </div>

        <div class = "d-flex gap-4">
        <button class = "btn rounded coursedraft">
        Save As Draft
        </button>

        <button class = "btn rounded publishcourse">
        Publish Course
        </button>

        </div>

    </div>
    </div>


    <div class = "container">

        <div class = "d-flex gap-4 justify-content-space-between">
        <button  type = "button" class = "btn btn-light borderlessconnectorbutton">
            <i class="fa-solid fa-dot-circle me-2" ></i>
            Create New Subject &nbsp;
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button class = "btn btn-light borderlessconnectorbutton" type = "button" href = "/subjectmedia">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Subject Media
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button class = "btn btn-light borderlessconnectorbutton" type = "button">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Curriculum
            <i class="fa fa-arrow-right me-2"></i>
        </button>

        <button class = "btn btn-light btn-outline-dark border-1 rounded me-3 connectorbutton" type = "button">
            <i class="fa-solid fa-dot-circle me-2"></i>
             Publish Subject
            <i class="fa fa-arrow-right me-2"></i>
        </button>


    </div>

</div>

<div class = "container">
 <section class="subject-overview-card">
        <h3 class="subject-overview-title">Subject Overview</h3>
        <div class="d-flex flex-column gap-3">
            <section class="d-flex flex-column gap">
                <header class="d-flex justify-content-between gap-2 align-items-center ">
                    <div class="d-flex flex-column gap-3">
                        <h4 class="subject-text">English Language</h4>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-book" style="color: #000000;"></i> <span class="subject-subtext">11 Topics</span></div>
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-circle-play" style="color: #000000;"></i> <span class="subject-subtext">11 Lesson</span></div>
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-question" style="color: #000000;"></i> <span class="subject-subtext">10 Quiz</span></div>
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-clock" style="color: #000000;"></i> <span class="subject-subtext">03:50:00 Hours</span></div>
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-landmark" style="color: #000000;"></i> <span class="subject-subtext">Jss 1</span></div>
                        </div>

                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button><i class="fa-solid fa-circle-check"></i></button>
                        <button><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                </header>
                <div><img src="images/publish.png" alt="" class="subject-overview-img"></div>
            </section>
            <article class="d-flex flex-column subject-overview-description-container">
                <h4 class="subject-overview-description-title">Subject Description</h4>
                <div class="d-flex gap-2 flex-column">
                    <p class="subject-overview-description-text">English Language is designed to equip learners with the skills needed to communicate effectively in both spoken and written forms. The subject focuses on grammar, vocabulary, comprehension, writing, and oral communication. Learners
                        will develop the ability to read with understanding, write clearly and creatively, listen attentively, and speak confidently in different contexts. Through interactive lessons, practice exercises, and assessments, students will
                        build a strong foundation in grammar and vocabulary, improve their reading and writing skills, and learn to express themselves fluently and accurately. By the end of the course, learners will not only master the technical aspects
                        of English but also gain the confidence to apply the language in academic, professional, and everyday life. </p>

                    <div>
                        <p class="subject-overview-study-list">Key Areas of Study:</p>
                        <ul>
                            <li>Grammar and Structure </li>
                            <li>Vocabulary Development</li>
                            <li>Reading Comprehension</li>
                            <li>Writing Skills (letters, essays, reports, creative writing)</li>
                            <li>Speaking and Listening Skills </li>
                            <li>Literature Appreciation (poetry, prose, drama)</li>
                        </ul>
                    </div>

                </div>

            </article>
            <section class="d-flex flex-column curriculum-container">
                <h4 class="subject-overview-description-title">Curriculum</h4>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center gap-2 speech-card">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fa-solid fa-book-open-reader"></i>
                            <div class="d-flex flex-column gap-1">
                                <h5 class="speech-card-title">Parts of Speech</h5>
                                <div class="d-flex gap-1 align-items-center">
                                    <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-circle-play" style="color: #000000;"></i> <span class="subject-subtext">11 Lesson</span></div>
                                    <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-question" style="color: #000000;"></i> <span class="subject-subtext">10 Quiz</span></div>

                                </div>
                            </div>
                        </div>
                        <i class="fa-solid fa-circle-check" style="color: #004A53;"></i>
                    </div>
                </div>

            </section>
        </div>
    </section>

</div>

<div class = "row  mt-4">
<div class = "d-flex col-md-12 col-lg-12 justify-content-end ">
    <div class = "d-flex flex-column flex-lg-row gap-3 px-0">
          <button class="btn btn-nav-secondary" style="padding: 12px 120px;">Back</button>
          <button class="btn btn-nav-primary" style="padding: 12px 120px;">Publish Now</button>
        </div>
</div>
</div>

</main>
@endsection
