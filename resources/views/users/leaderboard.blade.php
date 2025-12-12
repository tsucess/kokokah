@extends('layouts.usertemplate')

@section('content')

<style>
        .leaderboard-title {
            color: #004A53;
            font-size: 32px;
            font-weight: 600;
            font-family: "Fredoka", sans-serif;
        }

        .leaderboard-img {
            width: 40px;
            height: 40px;
        }

        .select {
            background-color: #F5F4F9;
            border-radius: 6px;
            border: none;
            width: 107px;
            height: 27px;
            font-size: 12px;
            color: #777777;
            padding-inline: 4px;
        }

        .filter-btn {
            width: 24px;
            height: 24px;
            border: none;
            outline: none;
            background-color: transparent;
        }

        .search-container {
            background-color: #F5F4F9;
            border-radius: 42px;
            width: 238px;
            height: 30px;
            gap: 4px;
            padding-inline: 5px;
        }

        .search-container input {
            border: none;
            outline: none;
            font-size: 12px;
            color: #8E8D93;
            background-color: transparent;
        }

        .leaderboard-container {
            background-color: #CCDBDD;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            height: 343px;
            box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);
            padding-inline: 30px;
        }

        .leaderboard-stats-container {
            max-width: 960px;
            height: 317px;
            gap: 30px;
            margin-top: auto;
            width: 100%;
        }

        .leaderboard-items-container {
            gap: 14px;
        }

        .leaderboard-winner-container {
            gap: 8px;
        }

        .leaderboard-winner-img {
            width: 50px;
            height: 50px;
            border: 1px solid #000000;
            border-radius: 50%;
            object-position: top;
            object-fit: cover;
        }

        .leaderboard-winner-name {
            font-size: 12px;
            color: #004A53;
        }

        .leaderboard-winner-track {
            height: 24px;
            width: 100%;
            border: 1px solid #000000;
            border-radius: 20px;
        }

        .leaderboard-second-platform {
            background-color: #F56824;
            height: 152px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }

        .leaderboard-second-img {
            width: 70px;
            height: 70px;
        }

        .leaderboard-first-platform {
            background-color: #004A53;
            height: 199px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }

        .leaderboard-first-img {
            width: 100px;
            height: 100px;
        }

        .leaderboard-third-platform {
            background-color: #FDAF22;
            height: 122px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }

        .leaderboard-third-img {
            width: 50px;
            height: 50px;
        }

        .table-footer-container {
            box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0, .25);
            border-bottom-right-radius: 15px;
            border-bottom-left-radius: 15px;
        }

        .leaderboard-table-container {
            margin: 30px 0px 24px;
        }

        table img.avatar {
            width: 41px;
            height: 41px;
            object-fit: fill;
            border-radius: 50%;
            object-position: center;
        }

        table img.img-badge {
            width: 30px;
            height: 30px;
        }

        table thead th {
            color: #000000;
            font-size: 14px;
            font-weight: 600;
        }

        .table-data-text {
            color: #004A53;
            font-size: 16px;
        }

        table.leaderboard-table-container td {
            color: #004A53;
            font-size: 16px;
        }

        table.leaderboard-table-container td.rank-text {
            font-size: 14px;
            color: #A0A0A0;
        }

        .footer-btn {
            border: 1px solid #DEDEDE;
            border-radius: 6px;
            width: 62px;
            height: 21px;
            font-size: 10px;
            color: #404040;
            font-weight: 500;
            background-color: transparent;
        }

        .footer-pagecount {
            color: #CBCCCD;
            font-size: 12.9px;
        }

    </style>
    <main>
    <section class="container-fluid py-4 px-3">
        <section class="d-flex flex-column gap-4">
            <header class="d-flex justify-content-between align-items-center gap-2">
                <div class="d-flex gap-1 align-items-center">
                    <img src="./images/leaderboard-icon.png" alt="" class="leaderboard-img">
                    <h2 class="leaderboard-title">Leaderboard</h2>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex gap-3 align-items-center">
                        <button class="filter-btn"><i class="fa-solid fa-filter" style="color: #525252;"></i></button>
                        <div class="d-flex align-items-center search-container"><i class="fa-solid fa-magnifying-glass fa-2xs" style="color: #8E8D93;"></i> <input type="search" name="" id="" placeholder="Search"></div>
                    </div>
                    <select name="" id="" class="select">
                        <option value="">This month</option>
                    </select>
                </div>
            </header>
            <section class="d-flex justify-content-center leaderboard-container">
                <article class="d-flex align-items-end leaderboard-stats-container">
                    <div class="d-flex flex-column gap-3 w-100">
                        <div class="leaderboard-winner-container">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <img src="./images/little-winner.jpg" alt="" class="leaderboard-winner-img">
                                <h4 class="leaderboard-winner-name">Winner Effiong</h4>
                            </div>
                            <div class="leaderboard-winner-track"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center leaderboard-second-platform">
                            <img src="./images/medal-second-place.png" alt="" class="leaderboard-second-img">
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 w-100">
                        <div class="leaderboard-winner-container">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <img src="./images/little-winner.jpg" alt="" class="leaderboard-winner-img">
                                <h4 class="leaderboard-winner-name">Winner Effiong</h4>
                            </div>
                            <div class="leaderboard-winner-track"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center leaderboard-first-platform">
                            <img src="./images/medal-first-place.png" alt="" class="leaderboard-first-img">
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 w-100">
                        <div class="leaderboard-winner-container">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <img src="./images/little-winner.jpg" alt="" class="leaderboard-winner-img">
                                <h4 class="leaderboard-winner-name">Winner Effiong</h4>
                            </div>
                            <div class="leaderboard-winner-track"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center leaderboard-third-platform">
                            <img src="./images/medal-third-place.png" alt="" class="leaderboard-third-img">
                        </div>
                    </div>
                </article>

            </section>
        </section>
        <section class="table-footer-container">
            <table class="table leaderboard-table-container">
                <thead>
                    <tr>
                        <th scope="col">Rank</th>
                        <th scope="col">Student Name</th>
                        <th scope="col">Student Class</th>
                        <th scope="col">Points</th>
                        <th scope="col">Level</th>
                        <th scope="col">Badge</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row" class="align-middle rank-text">01</td>
                        <td class="d-flex gap-2 align-items-center"><img src="./images/winner.png" alt="" class="avatar"> <span class="table-data-text">Winner Effiong Duff </span></td>
                        <td class="align-middle table-data-text">Jss 1</td>
                        <td class="align-middle"><i class="fa-solid fa-star" style="color: #FDAF22;"></i> <span class="table-data-text">1000</span></td>
                        <td class="align-middle table-data-text">Amateur</td>
                        <td class="align-middle"><img src="./images/badge-icon.png" alt="" class="img-badge"></td>
                    </tr>
                    <tr>
                        <td scope="row" class="align-middle rank-text">01</td>
                        <td class="d-flex gap-2 align-items-center"><img src="./images/winner.png" alt="" class="avatar"> <span class="table-data-text">Winner Effiong Duff </span></td>
                        <td class="align-middle table-data-text">Jss 1</td>
                        <td class="align-middle"><i class="fa-solid fa-star" style="color: #FDAF22;"></i> <span class="table-data-text">1000</span></td>
                        <td class="align-middle table-data-text">Amateur</td>
                        <td class="align-middle"><img src="./images/badge-icon.png" alt="" class="img-badge"></td>
                    </tr>
                    <tr>
                        <td scope="row" class="align-middle rank-text">01</td>
                        <td class="d-flex gap-2 align-items-center"><img src="./images/winner.png" alt="" class="avatar"> <span class="table-data-text">Winner Effiong Duff </span></td>
                        <td class="align-middle table-data-text">Jss 1</td>
                        <td class="align-middle"><i class="fa-solid fa-star" style="color: #FDAF22;"></i> <span class="table-data-text">1000</span></td>
                        <td class="align-middle table-data-text">Amateur</td>
                        <td class="align-middle"><img src="./images/badge-icon.png" alt="" class="img-badge"></td>
                    </tr>
                    <tr>
                        <td scope="row" class="align-middle rank-text">01</td>
                        <td class="d-flex gap-2 align-items-center"><img src="./images/winner.png" alt="" class="avatar"> <span class="table-data-text">Winner Effiong Duff </span></td>
                        <td class="align-middle table-data-text">Jss 1</td>
                        <td class="align-middle"><i class="fa-solid fa-star" style="color: #FDAF22;"></i> <span class="table-data-text">1000</span></td>
                        <td class="align-middle table-data-text">Amateur</td>
                        <td class="align-middle"><img src="./images/badge-icon.png" alt="" class="img-badge"></td>
                    </tr>
                    <tr>
                        <td scope="row" class="align-middle rank-text">01</td>
                        <td class="d-flex gap-2 align-items-center"><img src="./images/winner.png" alt="" class="avatar"> <span class="table-data-text">Winner Effiong Duff </span></td>
                        <td class="align-middle table-data-text">Jss 1</td>
                        <td class="align-middle"><i class="fa-solid fa-star" style="color: #FDAF22;"></i> <span class="table-data-text">1000</span></td>
                        <td class="align-middle table-data-text">Amateur</td>
                        <td class="align-middle"><img src="./images/badge-icon.png" alt="" class="img-badge"></td>
                    </tr>

                </tbody>
            </table>
            <div class="d-flex gap-3 align-items-center justify-content-between">
                <button class="footer-btn">Previous</button>
                <p class="footer-pagecount">Page1of 12</p>
                <button class="footer-btn">Next</button>
            </div>

        </section>

    </section>
    </main>
@endsection
