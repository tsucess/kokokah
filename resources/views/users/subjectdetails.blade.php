@extends('layouts.usertemplate')
@section('content')
    <style>
        .box {
            border: 1px solid #CCDBDD;
            padding: 20px;
        }

        .box-title {
            color: #000F11;
            font-size: 20px;
            font-weight: 600;
        }

        .box-progress-bar {
            background-color: #D9D9D9;
            border-radius: 4px;
            height: 6px;
            width: 208px;
        }

        .progress-track {
            background-color: #F56824;
            border-radius: 4px;
            height: 6px;
            width: 108px;
        }

        .video-box {
            border: 1px solid #CFD0D1;
            border-radius: 16px;
        }
        .lecture-box{
            border: 1px solid #000000;
            border-radius: 20px;
            padding: 20px
        }
        .lecture-download-btn{
            background-color: #D9D9D9;
            border-radius: 15px;
            padding: 19px 27px;
            color: #1C1D1D;
            font-size: 18px;
        }
        .lecture-text{
            font-size: 16px;
            color: #7F8285;
        }
        .mark-complete-btn{
            background-color: #3BA0AC;
            padding:8px 16px ;
            border-radius: 8px;
            font-size: 16px;
            color: #FFFFFF;
            font-weight: 600;
        }
        .nav-btn{
            color: #000F11;
            font-size:20px;
        }
        .divider{
            height: 1px;
            width: 100%;
            background-color: #000000;
        }
        .mark-lesson-btn{
            color: #1C1D1D;
            font-size: 16px;
        }
        .message-box-container{
            border: 1px solid #004A53;
            border-radius: 15px;
            padding: 20px;
            min-height: 556px;
        }
        .message-box{
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 6px -2px #10182808, 0 12px 16px -4px #10182814;
            border-radius: 24px;
            padding: 16px;
            height: 144px;
            margin-top: auto;
        }
        .message-input{
            color: #475569;
            border: none;
            background-color: transparent;
            font-size: 16px;
        }
        .emoji{
            border: 1px solid #CBD5E1;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 16px
        }
        .send-message-btn{
            background-color: #FDAF22;
            padding: 10px 16px;
            border-radius: 50px;
            color: #000F11;
            font-size: 14px;
            font-weight: 700;
        }
    </style>
    <main class="py-4 px-3">

        <section class="container-fluid d-flex flex-column gap-4">
            <h1>Lesson 13: Part of Speech</h1>
            <div class="row g-3">
                <div class="col-12 col-lg-6">
                    <div class="d-flex align-items-center gap-2 justify-content-between box mb-4">
                        <h3 class="box-title">Lesson 2 of 15</h3>
                        <div class="box-progress-bar">
                            <div class="progress-track"></div>
                        </div>
                    </div>
                    <div class="video-box mb-3">
                        <img src="./images/Video.png" alt="" class="img-fluid">
                    </div>

                    <ul class="nav nav-underline nav-fill mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Material & Links</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Quiz</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Note</a>
                        </li>
                    </ul>
                    <div class="lecture-box d-flex flex-column gap-3 mb-4">
                        <p class="lecture-text">This topic covers the fundamentals of creating effective layouts and compositions for websites that provide a better user experience. This topic covers the fundamentals of creating effective layouts and compositions for websites that provide a better user experience.</p>
                        <button class="d-flex gap-3 align-items-center align-self-start lecture-download-btn"><i class="fa-solid fa-book"></i>
                        Lectures in PDF format.pdf
                        <i class="fa-solid fa-download"></i>
                    </button>
                    </div>
                    <div class="d-flex align-items-center gap-2 justify-content-between">
                        <button class="nav-btn">Previous Lesson</button>
                        <button class="mark-complete-btn">Mark Lesson Complete</button>
                        <button class="nav-btn">Next Lesson</button>
                    </div>

                </div>
                <div class="col-12 col-lg-6">
                    <div class="box d-flex flex-column gap-3 mb-4">
                        <h3 class="box-title">Lesson 2 of 15</h3>
                        <div class="box-progress-bar"><div class="progress-track"></div></div>
                        <h4 class="box-title">25% completed</h4>
                        <div class="divider"></div>
                        <button class="d-flex gap-2 align-items-center mark-lesson-btn"><i class="fa-solid fa-book"></i>Mark Lesson Complete</button>
                    </div>
                    <div class="message-box-container d-flex align-items-end">
                        <div class="message-box w-100 d-flex flex-column gap-1">
                            <div class="d-flex gap-2 align-items-start"><i class="fa-solid fa-paperclip" style="color:#94A3B8;"></i>
                            <textarea class="message-input flex-fill" name="" id="" cols="" rows="" placeholder="Message to kodie..."></textarea></div>

                            <div class="d-flex align-items-center gap-3 justify-content-end mt-auto">
                                <div class="emoji d-flex justify-content-center align-items-center"><i class="fa-solid fa-face-smile"></i></div>
                            <button class="send-message-btn">Send</button>
                            </div>
                        
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </main>
@endsection
