<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>Kokokah</title>

    <link rel="icon" type="image/x-icon" href="images/kokokah_favicon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">

    <!-- Animate css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Bootstrap file -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    @vite(['resources/css/style.css', 'resources/css/responsiveness.css'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>

    <div class="container-fluid top_header">
        <!-- Background Image -->
        {{-- <img src="images/kokokah_header.png" class="img-fluid" alt="Banner Image"> --}}
        <h1>Transforming African Education—One School</h1>
    </div>

    <!-- Navigation Bar - Fixed/Sticky with Overlay -->
    <nav class="navbar navbar-expand-lg sticky-top px-md-3 px-lg-2 px-xl-5" aria-label="Fifth navbar example">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/Kokokah_Logo.png') }}" alt="Kokokah Logo"
                    class="animate__animated animate__pulse hero-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample05"
                aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarsExample05">
                <ul class="navbar-nav mb-2 mb-lg-0 mx-auto gap-3 ">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Products</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/lms">LMS</a></li>
                            <li><a class="dropdown-item" href="/sms">SMS</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kokoplay">Kokoplay</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact Us</a>
                    </li>
                </ul>

                <div class="d-flex flex-column flex-lg-row gap-3 px-0">
                    <button class="btn-nav-primary">Explore Kokokah</button>
                    <button class="btn-nav-secondary">Get a Demo</button>
                </div>
            </div>
        </div>
    </nav>


    <div class = "mb-0">
        @yield('content')
    </div>


  <!-- Modal -->
    <div class="modal fade beta-overlay-container" id="test_popupModal" tabindex="-1" data-bs-backdrop="static" >
        <div class="modal-dialog">
            <div class="modal-content beta-dialog-container">
                <div class="modal-body p-0 d-flex flex-column gap-4">
                    <div class="d-flex flex-column gap-2 beta-content-container">
                        <div class="beta-overlay-line"></div>
                        <h2 class="beta-modal-title">
                            Join Our Beta Testing Phase Sessions - Collab with us to test as
                            we prep for Launch
                        </h2>
                        <p class="beta-modal-text">
                            We've designed & developed Kokokah as a super all-in-one EdTech
                            platform built to transform education by solving its three
                            biggest problems namely access, funding and quality for the four
                            core groups in the education ecosystem being students, teachers,
                            parents and schools
                        </p>
                    </div>
                    <form action="" class="d-flex flex-column beta-form-container">
                        <div class="beta-form-input-container">
                            <label for="exampleFormControlInput1" class="form-label beta-form-label">Full Name</label>
                            <input type="text" class="form-control beta-form-input" id="exampleFormControlInput1"
                                placeholder="name@example.com" />
                        </div>
                        <div class="beta-form-input-container">
                            <label for="exampleFormControlInput1" class="form-label beta-form-label">Email address</label>
                            <input type="email" class="form-control beta-form-input" id="exampleFormControlInput1"
                                placeholder="name@example.com" />
                        </div>
                        <div class="d-flex flex-column flex-md-row gap-3">
                            <div class="beta-form-input-container flex-md-fill">
                                <label for="exampleFormControlInput1" class="form-label beta-form-label">Role</label>
                                <select class="form-select beta-form-input beta-border-white custom-select"
                                    aria-label="Default select example">
                                    <option selected>Open this select menu</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="student">Student</option>
                                </select>
                            </div>
                            <div class="beta-form-input-container flex-md-fill">
                                <label for="exampleFormControlInput1" class="form-label beta-form-label">Country</label>
                                <input type="email" class="form-control beta-form-input" id="exampleFormControlInput1"
                                    placeholder="name@example.com" />
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-md-row gap-3">
                            <div class="beta-form-input-container flex-md-fill">
                                <label for="exampleFormControlInput1" class="form-label beta-form-label">Country
                                    Code</label>
                                <select class="form-select beta-form-input beta-border-white custom-select"
                                    aria-label="Default select example">
                                    <option selected>Open this select menu</option>
                                    <option value="+93">Afghanistan (+93)</option>
                                    <option value="+355">Albania (+355)</option>
                                    <option value="+213">Algeria (+213)</option>
                                    <option value="+1">American Samoa (+1)</option>
                                    <option value="+376">Andorra (+376)</option>
                                    <option value="+244">Angola (+244)</option>
                                    <option value="+1">Anguilla (+1)</option>
                                    <option value="+1">Antigua and Barbuda (+1)</option>
                                    <option value="+54">Argentina (+54)</option>
                                    <option value="+374">Armenia (+374)</option>
                                    <option value="+297">Aruba (+297)</option>
                                    <option value="+61">Australia (+61)</option>
                                    <option value="+672">Australian External Territories (+672)</option>
                                    <option value="+43">Austria (+43)</option>
                                    <option value="+994">Azerbaijan (+994)</option>
                                    <option value="+1">Bahamas (+1)</option>
                                    <option value="+973">Bahrain (+973)</option>
                                    <option value="+880">Bangladesh (+880)</option>
                                    <option value="+1">Barbados (+1)</option>
                                    <option value="+375">Belarus (+375)</option>
                                    <option value="+32">Belgium (+32)</option>
                                    <option value="+501">Belize (+501)</option>
                                    <option value="+229">Benin (+229)</option>
                                    <option value="+1">Bermuda (+1)</option>
                                    <option value="+975">Bhutan (+975)</option>
                                    <option value="+591">Bolivia (+591)</option>
                                    <option value="+599">Bonaire, Sint Eustatius and Saba (+599)</option>
                                    <option value="+387">Bosnia and Herzegovina (+387)</option>
                                    <option value="+267">Botswana (+267)</option>
                                    <option value="+55">Brazil (+55)</option>
                                    <option value="+1">British Virgin Islands (+1)</option>
                                    <option value="+673">Brunei Darussalam (+673)</option>
                                    <option value="+359">Bulgaria (+359)</option>
                                    <option value="+226">Burkina Faso (+226)</option>
                                    <option value="+257">Burundi (+257)</option>
                                    <option value="+855">Cambodia (+855)</option>
                                    <option value="+237">Cameroon (+237)</option>
                                    <option value="+1">Canada (+1)</option>
                                    <option value="+238">Cape Verde (+238)</option>
                                    <option value="+1">Cayman Islands (+1)</option>
                                    <option value="+236">Central African Republic (+236)</option>
                                    <option value="+235">Chad (+235)</option>
                                    <option value="+56">Chile (+56)</option>
                                    <option value="+86">China (+86)</option>
                                    <option value="+57">Colombia (+57)</option>
                                    <option value="+269">Comoros (+269)</option>
                                    <option value="+242">Congo (+242)</option>
                                    <option value="+682">Cook Islands (+682)</option>
                                    <option value="+506">Costa Rica (+506)</option>
                                    <option value="+225">Côte d'Ivoire (+225)</option>
                                    <option value="+385">Croatia (+385)</option>
                                    <option value="+53">Cuba (+53)</option>
                                    <option value="+599">Curaçao (+599)</option>
                                    <option value="+357">Cyprus (+357)</option>
                                    <option value="+420">Czech Republic (+420)</option>
                                    <option value="+850">Dem. People's Rep. of Korea (+850)</option>
                                    <option value="+243">Dem. Rep. of the Congo (+243)</option>
                                    <option value="+45">Denmark (+45)</option>
                                    <option value="+246">Diego Garcia (+246)</option>
                                    <option value="+253">Djibouti (+253)</option>
                                    <option value="+1">Dominica (+1)</option>
                                    <option value="+1">Dominican Republic (+1)</option>
                                    <option value="+593">Ecuador (+593)</option>
                                    <option value="+20">Egypt (+20)</option>
                                    <option value="+503">El Salvador (+503)</option>
                                    <option value="+240">Equatorial Guinea (+240)</option>
                                    <option value="+291">Eritrea (+291)</option>
                                    <option value="+372">Estonia (+372)</option>
                                    <option value="+251">Ethiopia (+251)</option>
                                    <option value="+500">Falkland Islands (Malvinas) (+500)</option>
                                    <option value="+298">Faroe Islands (+298)</option>
                                    <option value="+679">Fiji (+679)</option>
                                    <option value="+358">Finland (+358)</option>
                                    <option value="+33">France (+33)</option>
                                    <option value="+594">French Guiana (+594)</option>
                                    <option value="+689">French Polynesia (+689)</option>
                                    <option value="+241">Gabon (+241)</option>
                                    <option value="+220">Gambia (+220)</option>
                                    <option value="+995">Georgia (+995)</option>
                                    <option value="+49">Germany (+49)</option>
                                    <option value="+233">Ghana (+233)</option>
                                    <option value="+350">Gibraltar (+350)</option>
                                    <option value="+30">Greece (+30)</option>
                                    <option value="+299">Greenland (+299)</option>
                                    <option value="+1">Grenada (+1)</option>
                                    <option value="+590">Guadeloupe (+590)</option>
                                    <option value="+1">Guam (+1)</option>
                                    <option value="+502">Guatemala (+502)</option>
                                    <option value="+224">Guinea (+224)</option>
                                    <option value="+245">Guinea-Bissau (+245)</option>
                                    <option value="+592">Guyana (+592)</option>
                                    <option value="+509">Haiti (+509)</option>
                                    <option value="+504">Honduras (+504)</option>
                                    <option value="+852">Hong Kong (+852)</option>
                                    <option value="+36">Hungary (+36)</option>
                                    <option value="+354">Iceland (+354)</option>
                                    <option value="+91">India (+91)</option>
                                    <option value="+62">Indonesia (+62)</option>
                                    <option value="+98">Iran (+98)</option>
                                    <option value="+964">Iraq (+964)</option>
                                    <option value="+353">Ireland (+353)</option>
                                    <option value="+972">Israel (+972)</option>
                                    <option value="+39">Italy (+39)</option>
                                    <option value="+1">Jamaica (+1)</option>
                                    <option value="+81">Japan (+81)</option>
                                    <option value="+962">Jordan (+962)</option>
                                    <option value="+7">Kazakhstan (+7)</option>
                                    <option value="+254">Kenya (+254)</option>
                                    <option value="+686">Kiribati (+686)</option>
                                    <option value="+82">Korea, Republic of (+82)</option>
                                    <option value="+965">Kuwait (+965)</option>
                                    <option value="+996">Kyrgyzstan (+996)</option>
                                    <option value="+856">Lao P.D.R. (+856)</option>
                                    <option value="+371">Latvia (+371)</option>
                                    <option value="+961">Lebanon (+961)</option>
                                    <option value="+266">Lesotho (+266)</option>
                                    <option value="+231">Liberia (+231)</option>
                                    <option value="+218">Libya (+218)</option>
                                    <option value="+423">Liechtenstein (+423)</option>
                                    <option value="+370">Lithuania (+370)</option>
                                    <option value="+352">Luxembourg (+352)</option>
                                    <option value="+853">Macao (+853)</option>
                                    <option value="+261">Madagascar (+261)</option>
                                    <option value="+265">Malawi (+265)</option>
                                    <option value="+60">Malaysia (+60)</option>
                                    <option value="+960">Maldives (+960)</option>
                                    <option value="+223">Mali (+223)</option>
                                    <option value="+356">Malta (+356)</option>
                                    <option value="+692">Marshall Islands (+692)</option>
                                    <option value="+596">Martinique (+596)</option>
                                    <option value="+222">Mauritania (+222)</option>
                                    <option value="+230">Mauritius (+230)</option>
                                    <option value="+52">Mexico (+52)</option>
                                    <option value="+691">Micronesia (+691)</option>
                                    <option value="+373">Moldova (+373)</option>
                                    <option value="+377">Monaco (+377)</option>
                                    <option value="+976">Mongolia (+976)</option>
                                    <option value="+382">Montenegro (+382)</option>
                                    <option value="+1">Montserrat (+1)</option>
                                    <option value="+212">Morocco (+212)</option>
                                    <option value="+258">Mozambique (+258)</option>
                                    <option value="+95">Myanmar (+95)</option>
                                    <option value="+264">Namibia (+264)</option>
                                    <option value="+674">Nauru (+674)</option>
                                    <option value="+977">Nepal (+977)</option>
                                    <option value="+31">Netherlands (+31)</option>
                                    <option value="+687">New Caledonia (+687)</option>
                                    <option value="+64">New Zealand (+64)</option>
                                    <option value="+505">Nicaragua (+505)</option>
                                    <option value="+227">Niger (+227)</option>
                                    <option value="+234">Nigeria (+234)</option>
                                    <option value="+683">Niue (+683)</option>
                                    <option value="+1">Northern Mariana Islands (+1)</option>
                                    <option value="+47">Norway (+47)</option>
                                    <option value="+968">Oman (+968)</option>
                                    <option value="+92">Pakistan (+92)</option>
                                    <option value="+680">Palau (+680)</option>
                                    <option value="+507">Panama (+507)</option>
                                    <option value="+675">Papua New Guinea (+675)</option>
                                    <option value="+595">Paraguay (+595)</option>
                                    <option value="+51">Peru (+51)</option>
                                    <option value="+63">Philippines (+63)</option>
                                    <option value="+48">Poland (+48)</option>
                                    <option value="+351">Portugal (+351)</option>
                                    <option value="+1">Puerto Rico (+1)</option>
                                    <option value="+974">Qatar (+974)</option>
                                    <option value="+40">Romania (+40)</option>
                                    <option value="+7">Russia (+7)</option>
                                    <option value="+250">Rwanda (+250)</option>
                                    <option value="+290">Saint Helena (+290)</option>
                                    <option value="+1">Saint Kitts and Nevis (+1)</option>
                                    <option value="+1">Saint Lucia (+1)</option>
                                    <option value="+508">Saint Pierre and Miquelon (+508)</option>
                                    <option value="+1">Saint Vincent and the Grenadines (+1)</option>
                                    <option value="+685">Samoa (+685)</option>
                                    <option value="+378">San Marino (+378)</option>
                                    <option value="+239">Sao Tome and Principe (+239)</option>
                                    <option value="+966">Saudi Arabia (+966)</option>
                                    <option value="+221">Senegal (+221)</option>
                                    <option value="+381">Serbia (+381)</option>
                                    <option value="+248">Seychelles (+248)</option>
                                    <option value="+232">Sierra Leone (+232)</option>
                                    <option value="+65">Singapore (+65)</option>
                                    <option value="+1">Sint Maarten (+1)</option>
                                    <option value="+421">Slovakia (+421)</option>
                                    <option value="+386">Slovenia (+386)</option>
                                    <option value="+677">Solomon Islands (+677)</option>
                                    <option value="+252">Somalia (+252)</option>
                                    <option value="+27">South Africa (+27)</option>
                                    <option value="+211">South Sudan (+211)</option>
                                    <option value="+34">Spain (+34)</option>
                                    <option value="+94">Sri Lanka (+94)</option>
                                    <option value="+249">Sudan (+249)</option>
                                    <option value="+597">Suriname (+597)</option>
                                    <option value="+268">Swaziland (+268)</option>
                                    <option value="+46">Sweden (+46)</option>
                                    <option value="+41">Switzerland (+41)</option>
                                    <option value="+963">Syria (+963)</option>
                                    <option value="+886">Taiwan (+886)</option>
                                    <option value="+992">Tajikistan (+992)</option>
                                    <option value="+255">Tanzania (+255)</option>
                                    <option value="+66">Thailand (+66)</option>
                                    <option value="+670">Timor-Leste (+670)</option>
                                    <option value="+228">Togo (+228)</option>
                                    <option value="+690">Tokelau (+690)</option>
                                    <option value="+676">Tonga (+676)</option>
                                    <option value="+1">Trinidad and Tobago (+1)</option>
                                    <option value="+216">Tunisia (+216)</option>
                                    <option value="+90">Turkey (+90)</option>
                                    <option value="+993">Turkmenistan (+993)</option>
                                    <option value="+1">Turks and Caicos Islands (+1)</option>
                                    <option value="+688">Tuvalu (+688)</option>
                                    <option value="+256">Uganda (+256)</option>
                                    <option value="+380">Ukraine (+380)</option>
                                    <option value="+971">United Arab Emirates (+971)</option>
                                    <option value="+44">United Kingdom (+44)</option>
                                    <option value="+1">United States (+1)</option>
                                    <option value="+1">United States Virgin Islands (+1)</option>
                                    <option value="+598">Uruguay (+598)</option>
                                    <option value="+998">Uzbekistan (+998)</option>
                                    <option value="+678">Vanuatu (+678)</option>
                                    <option value="+379">Vatican (+379)</option>
                                    <option value="+58">Venezuela (+58)</option>
                                    <option value="+84">Vietnam (+84)</option>
                                    <option value="+681">Wallis and Futuna (+681)</option>
                                    <option value="+967">Yemen (+967)</option>
                                    <option value="+260">Zambia (+260)</option>
                                    <option value="+263">Zimbabwe (+263)</option>
                                </select>
                            </div>
                            <div class="beta-form-input-container flex-md-fill">
                                <label for="exampleFormControlInput1" class="form-label beta-form-label">Phone
                                    Number</label>
                                <input type="email" class="form-control beta-form-input" id="exampleFormControlInput1"
                                    placeholder="name@example.com" />
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="exampleFormControlInput1" class="form-label beta-form-label">Platform</label>

                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" style="border: 1px solid #767676" type="checkbox"
                                        value="" id="checkDefault" />
                                    <label class="form-check-label beta-form-label" for="checkDefault">
                                        IOS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" style="border: 1px solid #767676" type="checkbox"
                                        value="" id="checkChecked" checked />
                                    <label class="form-check-label form-label beta-form-label" for="checkChecked">
                                        Android
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" style="border: 1px solid #767676" type="checkbox"
                                        value="" id="checkChecked" checked />
                                    <label class="form-check-label form-label beta-form-label" for="checkChecked">
                                        Web
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-input-container">
                            <label for="exampleFormControlInput1" class="form-label beta-form-label">Twitter Handle
                                (optional)</label>
                            <input type="email" class="form-control beta-form-input beta-border-white"
                                id="exampleFormControlInput1" placeholder="@username" />
                        </div>
                        <div class="d-flex flex-column gap-1 pt-3">
                            <div class="form-divider"></div>
                            <div class="form-check mb-0">
                                <input class="form-check-input" style="border: 1px solid #767676" type="checkbox"
                                    value="" id="checkDefault" />
                                <label class="form-check-label beta-form-label" for="checkDefault">
                                    I agree to the
                                    <a href="#" class="fw-bold" style="color: #ffbf4d">Terms</a>
                                    &
                                    <a href="#" class="fw-bold" style="color: #ffbf4d">Privacy</a>
                                </label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input" style="border: 1px solid #767676" type="checkbox"
                                    value="" id="checkChecked" checked />
                                <label class="form-check-label beta-form-label" for="checkChecked">
                                    Send me early access tips & updates
                                </label>
                            </div>
                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3 pt-4">
                                <button class="beta-join-btn">Join the Beta</button>
                                <button type="button" class="beta-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Not now
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Newsletter Section - Yellow Background -->
    <div class="container-fluid py-4 py-md-5 newsletter-section">
        <div class="container">
            <div class="row align-items-center gap-3 gap-md-0">
                <div class="col-12 col-md-6 col-lg-6 mb-4 mb-md-0">
                    <h2 class="newsletter-title typewriter">
                        Don't Miss Out on the Future of Learning!
                    </h2>
                    <p class="newsletter-description">
                        Be the first to get school and study hacks, career tips, and Kokokah updates straight to your
                        inbox. Join thousands of students, parents, and educators across Africa who are already leveling
                        up with us.
                    </p>
                </div>

                <div class="col-12 col-md-6 col-lg-6">
                    <div class="input-group">
                        <input type="email" class="form-control newsletter-input p-3" placeholder="Enter your email"
                            aria-label="Enter your email">
                        <button class="btn fw-bold newsletter-button" type="button">Subscribe Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section - Teal Background -->
    <footer class="footer-section">
        <div class="container">
            <div class="row mb-4 mb-md-5">
                <div class="col-12 col-md-5 col-lg-5 mb-4 mb-md-0">
                    <img src="images/Contact.png" class="img-fluid mb-3 footer-logo">
                    <p class="footer-description">
                        Kokokah combines School Management, Exam Prep, and a Learning Management System (LMS)—helping
                        schools automate admin tasks, boost student performance, and deliver modern digital learning in
                        one seamless platform.
                    </p>
                </div>

                <div class="col-6 col-md-2 col-lg-2">
                    <h6 class="footer-heading">Short Links</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="footer-link">Features</a></li>
                        <li class="nav-item mb-2"><a href="#" class="footer-link">How it works</a></li>
                        <li class="nav-item mb-2"><a href="#" class="footer-link">Security</a></li>
                        <li class="nav-item mb-2"><a href="#" class="footer-link">Testimonial</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2 col-lg-2">
                    <h6 class="footer-heading">Other Pages</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="footer-link">Privacy Policy</a></li>
                        <li class="nav-item mb-2"><a href="#" class="footer-link">Terms & Condition</a></li>
                        <li class="nav-item mb-2"><a href="#" class="footer-link">404</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-3 col-lg-3 mt-4">
                    <h6 class="footer-heading">Connect With Us</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-facebook-f footer-social-icon"></i>
                        </a>
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-twitter footer-social-icon"></i>
                        </a>
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-instagram footer-social-icon"></i>
                        </a>
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-linkedin-in footer-social-icon"></i>
                        </a>
                        <a href="#" class="btn btn-light footer-social-btn">
                            <i class="fab fa-youtube footer-social-icon"></i>
                        </a>
                    </div>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
                    <p class="mb-0 footer-copyright">Copyright &copy; 2025 All rights reserved</p>
                    <div>
                        <a href="#" class="footer-bottom-link">Terms and Conditions</a>
                        <a href="#" class="footer-bottom-link">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Ending footer section -->

    

    <!-- Scripts needed -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        gsap.utils.toArray(".typewriter").forEach((el) => {
            const text = el.textContent;
            el.textContent = ""; // clear text to start typing

            const letters = text.split("");

            letters.forEach((letter) => {
                const span = document.createElement("span");
                span.textContent = letter;
                span.style.opacity = 0; // hide initially
                el.appendChild(span);
            });

            const spans = el.querySelectorAll("span");

            gsap.fromTo(spans, {
                opacity: 0
            }, {
                opacity: 1,
                duration: 0.05, // speed of typing
                stagger: 0.05, // delay between letters
                ease: "none",
                scrollTrigger: {
                    trigger: el,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse",
                    // markers: true   // for debugging
                }
            });
        });
    </script>
</body>


</html>
