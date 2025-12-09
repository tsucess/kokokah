@extends('layouts.usertemplate')
@section('content')
<style>
        main{
            font-family:  "Fredoka", sans-serif;
        }
        p{
            margin-bottom: 0;
        }
        .header-banner{
            background-color: #FDAF22;
            height: 250px;
            width: 100%;
            border-bottom-left-radius: 100px;
            border-bottom-right-radius: 100px;
            position: relative;
        }

        .banner-img{
            position: absolute;
    right: -60px;
    bottom: -90px;
    width: 450px;
    height: auto;
    display: block;

        }
        .result-container{
            border: 1px solid #004A53;
            border-radius: 20px;
            padding: 20px 20px;
            background-color: #FFFFFF;
            gap: 20px;
            max-width: 523px;
            margin-top: 100px;
        }
        .result-div{
            border: 28px solid #004A53;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            font-size: 36px;
            color: #004A53;
            font-weight: 500;
        }
        .result-point-title{
            color: #004A53;
            font-size: 36px;
            font-weight: 600;
        }
        .result-progress-bar{
            background-color: #78787833;
            width: 100%;
            height: 6px;
            border-radius: 3px;
        }
        .result-progress-bar-track{
            background-color: #004A53;
            width: 70%;
            height: 6px;
            border-radius: 3px;
            display: block;
        }
        .result-items-text{
            color: #1C1D1D;
            font-size: 20px;
        }
        .result-items-container{
            display: grid;
            row-gap: 16px;
            column-gap: 50px;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

        }
        @media screen and (min-width:500px) {
            .banner-img{
                width: 600px;
                bottom: -120px;
                right: -80px;
            }
        }
        @media screen and (min-width:1000px) {
            .result-container{
                margin-top: -140px;
                z-index: 1000;
            }
        }


        @media screen and (min-width:1220px) {
            .result-items-container{
                grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
            }
        }
    </style>
</head>
<body>
    <main>
        <section class="container-fluid header-banner"><div ><img src="./images/result-score-img.png" alt=""  class="banner-img"/></div></section>
        <section class="container-fluid pb-5 d-flex flex-column gap-5">
            <div class="d-flex align-items-center result-container">
                <div class="d-flex justify-content-center align-items-center result-div">
                    82%
                </div>
                <div class="d-flex flex-column">
                    <p class="result-point-title">42/100</p>
                    <span style="color: #1C1D1D; font-size: 20px;">Points</span>
                </div>
            </div>
            <div class="result-items-container">
                <div class="d-flex flex-column gap-2  px-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="result-items-text">Math</h3>
                        <p class="result-items-text">8/10</p>
                    </div>
                    <div class="result-progress-bar"><span class="result-progress-bar-track"></span></div>
                </div>
                <div class="d-flex flex-column gap-2  px-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="result-items-text">Math</h3>
                        <p class="result-items-text">8/10</p>
                    </div>
                    <div class="result-progress-bar"><span class="result-progress-bar-track"></span></div>
                </div>
                <div class="d-flex flex-column gap-2  px-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="result-items-text">Math</h3>
                        <p class="result-items-text">8/10</p>
                    </div>
                    <div class="result-progress-bar"><span class="result-progress-bar-track"></span></div>
                </div>
                <div class="d-flex flex-column gap-2  px-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="result-items-text">Math</h3>
                        <p class="result-items-text">8/10</p>
                    </div>
                    <div class="result-progress-bar"><span class="result-progress-bar-track"></span></div>
                </div>
                <div class="d-flex flex-column gap-2  px-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="result-items-text">Math</h3>
                        <p class="result-items-text">8/10</p>
                    </div>
                    <div class="result-progress-bar"><span class="result-progress-bar-track"></span></div>
                </div>

            </div>

        </section>

    </main>

@endsection
