@extends('layouts.usertemplate')

@section('content')
<style>
        .header-title {
            font-size: 32px;
            color: #004A53;
            font-family: "Fredoka", sans-serif;
            font-weight: 600;
        }

        .header-subtitle {
            font-size: 18px;
            color: #1C1D1D;
        }

        .feature-container {
            max-width: 632px;
        }

        .feature-item-container {
            background-color: #F5F6F8;
            height: 220px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .feature-title {
            color: #1C1D1D;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
        }

        .feature-text {
            padding: 10px;
            color: #1C1D1D;
            font-size: 18px;
            text-align: center;
        }

        .share-feedback-container {
            border-radius: 20px;
            border: 1px solid #80A4A9;
            gap: 34px;
            padding: 20px;
            max-width: 977px;
        }

        .share-feedback-btn {
            background-color: #FDAF22;
            color: #000F11;
            font-size: 16px;
            width: 341px;
            height: 60px;
            border: none;
            font-weight: 600;
        }

        .share-feedback-title {
            font-size: 20px;
            font-weight: 600;
            color: #004A53;
            font-family: "Fredoka", sans-serif;
        }

        .input-area {
            border: 1.5px solid #004A53;
            border-radius: 10px;
            height: 62px;
            padding: 0px 24px 27px;
        }

        .label {
            color: #004A53;
            font-size: 14px;
            position: relative;
            top: -12px;
            background-color: #fff;
            align-self: flex-start;
            font-weight: 500;
            padding-inline: 2px;
        }

        .input {
            border: none;
            outline: none;
            color: #8E8E93;
            font-size: 14px;
        }

        select {
            font-size: 14px;
            border: none;
            outline: none;
            color: #AEBACA;
            background-color: transparent;
        }

        .rate-title {
            color: #004A53;
            font-size: 14px;
        }

        .textarea-area {
            border: 1.5px solid #004A53;
            border-radius: 10px;
            height: 167px;
            padding: 0px 24px 27px;
        }
    </style>


<main>
    <section class="container-fluid d-flex flex-column gap-4 px-4 py-5">
        <header class="d-flex flex-column gap-2">
            <h3 class="header-title">We Value Your Feedback</h3>
            <p class="header-subtitle">Your input helps us build products and experiences. Whether you’ve found a bug, have a feature request, or just want to share your thoughts, we’re here to listen.</p>
        </header>
        <section class="container feature-container">
            <div class="row gx-5 gy-4">
                <div class="col col-12 col-lg-6  ">
                    <div class="feature-item-container d-flex flex-column gap-4 align-items-center justify-content-center">
                        <div><i class="fa-solid fa-bug fa-2xl" style="color: #000000;"></i></div>
                        <div class="d-flex flex-column gap-1">
                            <h4 class="feature-title">Report Bugs</h4>
                            <p class="feature-text">Found something broken? Let us know so we can fix it quickly.</p>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-lg-6 ">
                    <div class="feature-item-container d-flex flex-column gap-4 align-items-center justify-content-center">
                        <div><i class="fa-regular fa-lightbulb fa-2xl" style="color: #000000;"></i></div>
                        <div class="d-flex flex-column gap-1">
                            <h4 class="feature-title">Request Features</h4>
                            <p class="feature-text">Have an idea for improvement? Share your suggestions with us.</p>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-lg-6 ">
                    <div class="feature-item-container d-flex flex-column gap-4 align-items-center justify-content-center">
                        <div><i class="fa-regular fa-comment fa-2xl" style="color: #000000;"></i></div>
                        <div class="d-flex flex-column gap-1">
                            <h4 class="feature-title">General Feedback</h4>
                            <p class="feature-text">Share your thoughts on how we can make things better for you.</p>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-lg-6 ">
                    <div class="feature-item-container d-flex flex-column gap-4 align-items-center justify-content-center">
                        <div><i class="fa-regular fa-heart fa-2xl" style="color: #000000;"></i></div>
                        <div class="d-flex flex-column gap-1">
                            <h4 class="feature-title">We Listen</h4>
                            <p class="feature-text">Every piece of feedback help us create a better product and experience for you.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="container-fluid d-flex flex-column share-feedback-container">
            <div class="d-flex flex-column gap-4">
                <header class="d-flex flex-column gap-1">
                    <h5 class="share-feedback-title">Share Your Feedback</h5>
                    <p class="header-subtitle">Help us improve by sharing your thoughts, reporting bugs, or suggesting new features.</p>
                </header>
                <form action="" class="d-flex flex-column gap-4">
                    <div class="d-flex flex-column input-area">
                        <label for="" class="label">Enter First Name</label>
                        <input type="text" name="" id="" placeholder="Winner" class="input">
                    </div>
                    <div class="d-flex flex-column input-area">
                        <label for="" class="label">Enter Last Name</label>
                        <input type="text" name="" id="" placeholder="Effiong" class="input">
                    </div>
                    <div class="d-flex flex-column input-area">
                        <label for="" class="label">Select Feedback Type *</label>
                        <select name="" id="">
                        <option value="">student</option>
                    </select>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <h6 class="rate-title">How would you rate your overall experience?</h6>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-star fa-lg" style="color: #E5E6E7;"></i>
                            <i class="fa-solid fa-star fa-lg" style="color: #E5E6E7;"></i>
                            <i class="fa-solid fa-star fa-lg" style="color: #E5E6E7;"></i>
                            <i class="fa-solid fa-star fa-lg" style="color: #E5E6E7;"></i>
                            <i class="fa-solid fa-star fa-lg" style="color: #E5E6E7;"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column input-area">
                        <label for="" class="label">Subject</label>
                        <input type="text" name="" id="" placeholder="Brief summary of your feedback" class="input">
                    </div>
                    <div class="d-flex flex-column textarea-area">
                        <label for="" class="label">Message *</label>
                        <textarea name="" id="" class="input" placeholder="Please provide detailed information about your feedback......"></textarea>
                    </div>
                </form>
            </div>
            <button class="align-self-center share-feedback-btn">Submit Feedback</button>
        </section>
    </section>
</main>
@endsection
