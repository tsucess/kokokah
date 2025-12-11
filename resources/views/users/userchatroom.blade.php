@extends('layouts.usertemplate')

@section('content')
    <style>
        .menu, .chat-container {
            border: 1px solid #004A53;
        }

        .search-container {
            background-color: #F4F7FC;
            border-radius: 15px;
            padding: 13px 14px;
            gap: 10px;
        }

        .search-input {
            border: none;
            font-size: 16px;
            color: #8E8D93;
            background-color: transparent;
            outline: none;
        }

        .divider {
            height: 1px;
            background-color: #004A53;
            width: 100%;
        }

        .menu-title {
            color: #000000;
            font-size: 20px;
            text-transform: uppercase;
        }

        .menu-btn{
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 10px 12px;
            color: #004A53;
            font-size: 16px;
        }

        .menu-btn-active {
            background-color: #004A53;
            border-radius: 8px;

            color: #FFFFFF;
        }
        .hashtag-container {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #004A53;
            display:flex;
            justify-content: center;
            align-items: center;
        }
        .hashtag-container-active{
            background-color: #fff;
        }
        .hashtag{
            color: #fff;
        }
        .hashtag-active{
            color: #000000;
        }
        .chatroom-header{
            background-color: #FDAF22;
            padding-block: 51px;
        }
        .hashtag-container-large{
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #311507;
        }
        .chatroom-title{
            color: #311507;
            font-size: 32px;
            font-weight: 600;
        }
        .chatroom-subtitle{
            color: #311507;
            font-size: 20px;
        }
        .chatroom-img{
            width: 32px;
            height: 36px;
            border-radius: 50%;
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
    <main>
        <section class="container-fluid p-3">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <aside class="menu " >
                        <div class="search-container d-flex align-items-center mx-4 " style="height:90px;">
                            <i class="fa-solid fa-magnifying-glass" style="color: #8E8D93;"></i>
                            <input type="search" name="" id="" class="search-input"
                                placeholder="Find a Conversation">
                        </div>
                        <div class="divider"></div>
                        <div class="px-4 py-4 d-flex flex-column gap-3">
                            <h3 class="menu-title">CONSERVATION</h3>
                            <div class="d-flex flex-column gap-3">
                                <button class="menu-btn menu-btn-active">
                                    <div class="hashtag-container hashtag-container-active"><i class="fa-solid fa-hashtag fa-2xs hashtag-active"></i></div>
                                    General
                                </button>
                                <button class="menu-btn">
                                    <div class="hashtag-container"><i class="fa-solid fa-hashtag fa-2xs hashtag"></i></div>
                                    Mathematics Help Corner
                                </button>
                                <button class="menu-btn">
                                    <div class="hashtag-container "><i class="fa-solid fa-hashtag fa-2xs hashtag"></i></div>
                                    Science Discussions
                                </button>
                                <button class="menu-btn">
                                    <div class="hashtag-container"><i class="fa-solid fa-hashtag fa-2xs hashtag"></i></div>
                                    Mathematics Help Corner
                                </button>
                                <button class="menu-btn">
                                    <div class="hashtag-container "><i class="fa-solid fa-hashtag fa-2xs hashtag"></i></div>
                                    Science Discussions
                                </button>
                            </div>
                        </div>

                    </aside>
                </div>
                <div class="col-12 col-lg-8">
                    <div class="chat-container">
                        <div class="px-5 py-4" style="height:90px;"><h2>#General</h2></div>
                    <div class="divider"></div>
                    <div>
                        <div class="chatroom-header d-flex flex-column align-items-center">
                            <div class="hashtag-container-large d-flex justify-content-center align-items-center"><i class="fa-solid fa-hashtag fa-xl" style="color:#FDAF22;"></i></div>
                            <div class="d-flex flex-column align-items-center">
                                <h2 class="chatroom-title">Welcome to #General</h2>
                                <h3 class="chatroom-subtitle">This is the start of the #General Channel</h3>
                            </div>
                        </div>
                        <div class="py-4 px-4 d-flex flex-column gap-4">
                            <div class="px-4 d-flex flex-column gap-3">
                                <div class="d-flex align-items-start gap-2">
                                    <img src="./images/winner.png" alt="" class="chatroom-img">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex gap-2 align-items-end">
                                            <h4>Emery Schleifer</h4>
                                            <span>3m</span>
                                        </div>
                                        <p>You can't input the pixel without programming the open-source SMTP feed!</p>
                                    </div>
                                </div>
                                 <div class="d-flex align-items-start gap-2">
                                    <img src="./images/winner.png" alt="" class="chatroom-img">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex gap-2 align-items-end">
                                            <h4>Emery Schleifer</h4>
                                            <span>3m</span>
                                        </div>
                                        <p>You can't input the pixel without programming the open-source SMTP feed! You can't input the pixel without programming the open-source SMTP feed!</p>
                                    </div>
                                </div>
                                 <div class="d-flex align-items-start gap-2">
                                    <img src="./images/winner.png" alt="" class="chatroom-img">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex gap-2 align-items-end">
                                            <h4>Emery Schleifer</h4>
                                            <span>3m</span>
                                        </div>
                                        <p>You can't input the pixel without programming the open-source SMTP feed! You can't input the pixel without programming the open-source SMTP feed!</p>
                                    </div>
                                </div>

                            </div>
                             <div class="message-box w-100 d-flex flex-column gap-1">
                            <div class="d-flex gap-2 align-items-start"><i class="fa-solid fa-paperclip" style="color:#94A3B8;"></i>
                            <textarea class="message-input flex-fill" name="" id="" cols="" rows="" placeholder="Message to kodie..."></textarea></div>

                            <div class="d-flex align-items-center gap-3 justify-content-end mt-auto">
                                <div class="emoji d-flex justify-content-center align-items-center"><i class="fa-solid fa-face-smile"></i></div>
                                <div class="emoji d-flex justify-content-center align-items-center"><i class="fa-solid fa-microphone"></i></i></div>
                            <button class="send-message-btn">Send</button>
                            </div>

                        </div>
                        </div>
                    </div>
                    </div>

                </div>
            </div>

        </section>



    </main>
@endsection
