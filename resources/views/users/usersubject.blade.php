{{-- @extends('admin.usertemplate') --}}
@extends('layouts.usertemplate')

@section('content')
    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            position: relative;
            z-index: 10;
        }

        .card-item-class {
            background-color: #FDAF22;
            padding: 4px 28px;
            border-radius: 5px;
            color: #000F11;
            font-size: 12px;
        }

        .view-btn {
            border: 1px solid #004A53;
            border-radius: 4px;
            padding: 16px 20px;
            color: #004A53;
            font-size: 16px;
            font-weight: 600;
            z-index: 9999;
        }
    </style>
    <main>
        <div class="container m-2">
            <div class="row">
                <div>
                    <h4>Hello Samuel
                        <i class="fa-solid fa-hands-clapping text-warning"></i>
                    </h4>
                    <p>Let`s learn something new today</p>
                </div>

            </div>

        </div>

        <div class  = "container ">
            <div class = "card-container">
                <div class = " p-3 bg-white mysubject d-flex flex-column gap-3 w-100">
                    <div class = "border border-dark p-3" style="border-radius: 10px;">
                        <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                    </div>
                    <div class = "card-item-class align-self-start">JSS 1</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class = "subjects">Computer Science</h5>
                        <h5 class = "subjects">60%</h5>
                    </div>

                    <div class="progress " style = "height:6px; backgtound-color:#D9D9D9;">
                        <div class="progress-bar" style="width:70%; background:#004A53; height:100%;"></div>
                    </div>
                    <button class="view-btn" type="button">View Subjects</button>

                </div>
                <div class = " p-3 bg-white mysubject d-flex flex-column gap-3 w-100">
                    <div class = "border border-dark p-3" style="border-radius: 10px;">
                        <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                    </div>
                    <div class = "card-item-class align-self-start">JSS 1</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class = "subjects">Computer Science</h5>
                        <h5 class = "subjects">60%</h5>
                    </div>

                    <div class="progress " style = "height:6px; backgtound-color:#D9D9D9;">
                        <div class="progress-bar" style="width:70%; background:#004A53; height:100%;"></div>
                    </div>
                    <button class="view-btn" type="button">View Subjects</button>

                </div>
                <div class = " p-3 bg-white mysubject d-flex flex-column gap-3 w-100">
                    <div class = "border border-dark p-3" style="border-radius: 10px;">
                        <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                    </div>
                    <div class = "card-item-class align-self-start">JSS 1</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class = "subjects">Computer Science</h5>
                        <h5 class = "subjects">60%</h5>
                    </div>

                    <div class="progress " style = "height:6px; backgtound-color:#D9D9D9;">
                        <div class="progress-bar" style="width:70%; background:#004A53; height:100%;"></div>
                    </div>
                    <button class="view-btn" type="button">View Subjects</button>

                </div>
                <div class = " p-3 bg-white mysubject d-flex flex-column gap-3 w-100">
                    <div class = "border border-dark p-3" style="border-radius: 10px;">
                        <img src = "images/Kokokah_Logo.png" class = "img-fluid" />
                    </div>
                    <div class = "card-item-class align-self-start">JSS 1</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class = "subjects">Computer Science</h5>
                        <h5 class = "subjects">60%</h5>
                    </div>

                    <div class="progress " style = "height:6px; backgtound-color:#D9D9D9;">
                        <div class="progress-bar" style="width:70%; background:#004A53; height:100%;"></div>
                    </div>
                    <button class="view-btn" type="button">View Subjects</button>

                </div>


            </div>

        </div>

        <div class="chat-btn-circle">
            <i class="fa-solid fa-comment"></i>
        </div>

    </main>
    <script>
   document.addEventListener('DOMContentLoaded', () => {
    const viewBtns = document.querySelectorAll('button.view-btn');
    console.log(viewBtns); // check if buttons exist

    viewBtns.forEach(btn => {
        console.log(btn)
        btn.addEventListener('click', (e) => {
            console.log('clicked');
            e.preventDefault()
            e.stopPropagation()
            window.location.href = '/termsubject';
        });
    });
});
</script>
@endsection
