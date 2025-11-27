@extends('layouts.template')

@section('content')
    {{-- mascot  --}}


  <img src="./images/book-icon.png" class="kokokah-logo" />

    <!-- Modal Section -->

    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pb-3 px-4 pt-4">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="width:1rem; height:1rem; background-size:1rem;"></button>
                </div>
                <div class="modal-body pt-0 pb-lg-0">
                    <div class="row d-flex flex-column flex-md-row  justify-content-center justify-content-md-end">
                        <div class=" col col-12 col-lg-5 d-flex justify-content-center"><img
                                src="./images/Group_20-removebg-preview.png" alt=""
                                style="width: 300px; height:150px;"></div>
                        <div class="d-flex flex-column gap-1 align-items-center justify-content-center col col-12 col-lg-7">

                            <h2 class="title text-center"><span>Hey Champion.</span> Ready to pass smarter and better?</h2>
                            <div class="d-flex align-items-center gap-1">
                                <span class="feature">Short Curriculum-based lessons + Practice tests from anywhere</span>
                                <span class="feature">Low data & Offline use</span>
                                <span class="feature">Score higher</span>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col col-12 col-lg-6">
                                <img src="./images/db368b8f91f2ce3ff4d3b1b6ac05a321bfdc4f9a.png" alt=""
                                    class="img-fluid" />
                            </div>
                            <div class="col col-12 col-lg-6 d-flex flex-column gap-4">
                                <div class="launch-container">We're Launching Soon. Stay Close & Don't Miss a Thing
                                    <div class="node"></div>
                                </div>
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex flex-column input-container">
                                        <label for="" class="label">Enter first name</label>
                                        <input type="text" name="" id="" class="modal-form-input">
                                    </div>
                                    <div class="d-flex flex-column input-container">
                                        <label for="" class="label">Enter last name</label>
                                        <input type="text" name="" id="" class="modal-form-input">
                                    </div>
                                    <div class="d-flex flex-column input-container">
                                        <label for="" class="label">Enter email address</label>
                                        <input type="email" name="" id="" class="modal-form-input">
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-5">
                                    <button class="align-self-center form-btn">Join Kokokah & Start Learning Now</button>
                                    <div class="d-flex gap-4 align-items-center justify-content-center m">
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-youtube" style="color:#F56824;"></i></div>
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-linkedin" style="color:#F56824;"></i></div>
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-x-twitter" style="color:#F56824;"></i></div>
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-instagram" style="color:#F56824;"></i></div>
                                        <div class="d-flex align-items-center justify-content-center icon-container"><i
                                                class="fa-brands fa-facebook-f" style="color:#F56824;"></i></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-accent">kokokah.com</div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Modal -->
    <div class="modal fade beta-overlay-container" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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


    <!-- Hero Section - Yellow Background -->
    <div class="container-fluid hero-section-yellow">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 col-lg-6">
                {{-- <div class="hero-title-container">
  <span class="hero-title" id="typing-text"></span>
</div> --}}
                <h1 class="hero_header">Welcome!</h1>
                <p class="mb-4 hero-subtitle">
                    LOW DATA USAGE + OFFLINE ACCESS + SCHOOL MANAGEMENT SYSTEM
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-primary-action">Start Using
                        Kokokah</a>
                    <a data-bs-toggle="modal" data-bs-target="#exampleModal" class=" btn btn-secondary-action">Signup
                        Now</a>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mt-5  mt-md-0 hero_img_container  text-center">
                <img src="images/LMS.png" class="img-fluid animate__animated animate__pulse hero-img"
                    alt="LMS Illustration">
            </div>
        </div>
    </div>

    <!-- Kokokah for All Section - White Background -->
    <div class="container-fluid section-white section-py ">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 col-lg-6 mb-4 mb-md-0 fade-section-left">
                    <img src="images/33d07ac37205dee5ed7d04a51aace312e634c69c.jpg" class="img-fluid"
                        alt="Kokokah Platform"
                        style="max-width:752px; width:100%; height:382px; border-radius : 15px; object-fit:cover;">
                </div>
                <div class="col-12 col-md-6 col-lg-6 ps-md-4 fade-section">
                    <h2 class="fw-bold mb-4 section-heading">
                        Kokokah for All
                    </h2>
                    <p class="mb-4 hero-subtitle">
                        Kokokah is a smart, pan-African learning and school management platform built for the realities of
                        African education. Whether you're a JSS1 student in Ghana, an SSS3 student in Kenya, or an educator
                        in South Africa, our mission is simple — to give every learner from any background the opportunity
                        to excel with ease.
                    </p>
                    <button class="btn primaryButton">Discover Kokokah</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Kokokah is the Best Section -->
    <div class="container-fluid section-light-gray section-py">
        <div class="container">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="fw-bold mb-3 section-heading section-title">
                    Why Kokokah Is the Best
                </h2>
                <p class="section-description">
                    Kokokah combines mobile learning, exam preparation and a school learning management system, helping
                    schools automate tasks efficiently, offering parents high-quality affordable learning options and
                    boosting overall student performance.
                </p>
            </div>

            <div class="row g-4 features">
                <!-- Feature 1 -->
                <div class="col-12 col-md-6 col-lg-4 feature-item">
                    <div class="feature-card ">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">For Students, Parents & Schools</h5>
                        <p class="feature-card-text">One platform for all.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-12 col-md-6 col-lg-4 feature-item">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">Accessible Mobile Learning</h5>
                        <p class="feature-card-text">Study anywhere, anytime — even on low internet. Learn on your phone,
                            tablet, or computer without missing a beat.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-12 col-md-6 col-lg-4 feature-item">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">AI-integrated and Automated Features</h5>
                        <p class="feature-card-text">Get instant answers, personalized feedback, and quick grading with our
                            built-in AI — saving time for both students and educators.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-12 col-md-6 col-lg-4 feature-item">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">Affordable Subscription Plans</h5>
                        <p class="feature-card-text">Choose a plan that fits your budget and needs — monthly, quarterly, or
                            yearly, all with full platform access.</p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-12 col-md-6 col-lg-4 feature-item">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fa-solid fa-download feature-card-icon"></i>
                        </div>
                        <h5 class="fw-bold mb-3 feature-card-title">Virtual Payments</h5>
                        <p class="feature-card-text">Store and track money for any resource purchase on Kokokah — quick,
                            safe, and hassle-free.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Products Section -->
    <div class="container-fluid section-white section-py">
        <div class="container">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="fw-bold mb-3 section-heading section-title">
                    Our Products
                </h2>
                <p class="section-description">
                    Kokokah brings you a suite of powerful learning tools designed to transform how African students,
                    parents, and educators connect, learn, and thrive.
                </p>
            </div>
            <div class="text-center">
                <button class="btn primaryButton">Explore Features</button>
            </div>
        </div>
    </div>



    <div class="d-flex flex-column gap-5 mx-4 mx-md-0">

        <div class = "container fade-section">
            <div class = "row flex-column-reverse flex-md-row ourproduct1 product-section-bordered-red ">
                <div class = "col col-12 col-md-7 col-lg-7 p-4 p-lg-5  my-auto d-flex flex-column gap-3">
                    <img src="images/Group 1171274797.png" alt="" class="w-75"
                        style=" height:auto; max-width:200px;" />
                    <p style = "color :#004A53;">
                        Kokokah houses an exam preparatory platform
                        where African students can prepare adequately
                        for both local and international examinations.
                    </p>
                    <p style = "color :#004A53;">
                        WAEC - NECO - JAMB - GCE - GMAT - SAT - TOEFL - IELTS - GRE - ACT
                    </p>

                    <div class="d-flex justify-content-center justify-content-md-start">
                        <button class = "primaryButton" type = "button">Coming soon</button>
                    </div>
                </div>


                <div class="col col-12 col-md-5 col-lg-5 d-flex align-items-lg-center justify-content-lg-center px-0">
                    <img src="images/exam_prep.png" class="img-fluid w-100 " alt="Exam Prep" style="height: auto;">
                </div>

            </div>
        </div>


        <div class = "container fade-section-left">
            <div class="row ourproduct2 product-section-orange">
                <!-- Image Section -->
                <div class="col-12 col-md-6 col-lg-6 px-0">
                    <img src="images/School Admin.png" class="img-fluid" alt="School Admin">
                </div>
                <!-- Text Section -->
                <div class="col-12 col-md-6 col-lg-6 p-4 p-lg-5  my-auto d-flex flex-column gap-3">
                    <h4 class="text-white">School Management System (SMS)</h4>
                    <p class = " text-white">
                        Simplify school administration with fee
                        tracking, digital report cards, attendance, and student
                        portals — all in one secure platform.
                    </p>
                    <div class="d-flex justify-content-center justify-content-md-start">
                        <button class = " primaryButton " type="button">Coming soon</button>
                    </div>

                </div>
            </div>
        </div>




        <div class = "container fade-section">
            <div class = "row flex-column-reverse flex-md-row ourproduct1 product-section-bordered-green">
                <div class = "col-12 col-md-7 col-lg-7 p-4 p-lg-5 my-auto d-flex flex-column gap-3">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-2"><img
                            src="./images/Kokokah_Logo.png" alt="" style="width:200px; height:auto;"
                            class="object-cover" />
                        <h4 style = "color :#004A53;">Learning Management System</h4>
                    </div>
                    <p style = "color :#004A53;">
                        Your all-in-one digital classroom — structured lessons, AI-powered
                        tutoring, chatrooms, and  academic & non-academic content for secondary school.
                    </p>
                    <div class="d-flex justify-content-center justify-content-md-start">
                        <button class = "primaryButton" type = "button">Coming soon</button>
                    </div>
                </div>
                <div class="col-12 col-md-5 text-center p-0">
                    <img src="images/lms system.png" class="img-fluid w-100 w-md-75 h-100" alt="Exam Prep">
                </div>
            </div>
        </div>

        <div class = "container fade-section-left">
            <div class="row ourproduct2 product-section-teal">
                <!-- Image Section -->
                <div class="col-12 col-md-6 my-auto text-center">
                    <img src="images/School Admin.png" class="img-fluid w-100 w-md-75" alt="School Admin">
                </div>

                <!-- Text Section -->
                <div class="col-12 col-md-6 p-4 p-lg-5 my-auto d-flex flex-column gap-3">
                    <h4 class="text-white">
                        The Marketplace
                    </h4>
                    <p class="text-white">
                        Africa’s academic forum for parents, teachers, and
                        tutors to connect. Book trusted tutors for academics,
                        test prep, and special needs learning.
                    </p>
                    <div class="d-flex justify-content-center justify-content-md-start">
                        <button class = "primaryButton" type = "button">Coming Soon</button>

                    </div>
                </div>
            </div>
        </div>


        <div class = "container fade-section">
            <div class="row ourproduct2 product-section-bordered">
                <!-- Image Section -->
                <div class="col-12 col-md-6 ">
                    <img src="images/School Admin.png" class="img-fluid w-100 w-md-75" alt="School Admin">
                </div>

                <!-- Text Section -->
                <div class="col-12 col-md-6 p-4 p-lg-5 my-auto d-flex flex-column gap-3">
                    <img src="./images/315a2f8c6c60fc789ec0066a0b5bce04b7daa28d.png" alt=""
                        style="width:200px; height:60px;" />
                    <p style = "color :#004A53;">
                        Get hands-on STEM bootcamps, summer schools,
                        and practical learning experiences to prepare
                        students for the future of science & tech.
                    </p>

                    <div class="d-flex justify-content-center justify-content-md-start">
                        <button class = "primaryButton" type = "button">Coming soon</button>

                    </div>

                </div>
            </div>
        </div>


        <div class = "container fade-section-left">
            {{-- <div class="row  my-4 p-2 flex-column-reverse flex-md-row m-2 ourproduct1 product-section-yellow"> --}}
            <div class = "row flex-column-reverse flex-md-row ourproduct1 product-section-yellow">
                <!-- Text Section -->
                <div class="col-12 col-md-6 d-flex flex-column justify-content-center gap-3  p-4 ps-lg-5 pb-4 pb-lg-0">
                    <h4>
                        AI Chatbot
                    </h4>
                    <p>
                        Your personal academic assistant — ask questions, get explanations,
                        and enjoy instant feedback tailored to your study needs.
                    </p>

                    <div class="d-flex justify-content-center justify-content-md-start">
                        <button class = "primaryButton greenBtn" type = "button">Coming Soon</button>
                    </div>
                </div>

                <!-- Image Section -->
                <div class="col-12 col-md-6 text-center">
                    <img src="images/lms system.png" class="img-fluid w-100 w-md-75" alt="LMS system">
                </div>
            </div>
        </div>

        <div class = "container fade-section">
            <div class="row ourproduct2 product-section-bordered">
                <!-- Image Section -->
                <div class="col-12 col-md-6 my-auto text-center">
                    <img src="images/8e967ffd660c50979f3c273bbb9d848dbb48a9db.png"
                        class="d-block w-75 w-md-50 w-lg-25 mx-auto" alt="kudikah">
                </div>

                <!-- Text Section -->
                <div class="col-12 col-md-6 my-auto p-4 p-lg-5 d-flex flex-column gap-3">
                    <h4 style = "color :#004A53;">Kudikah</h4>
                    <p style = "color :#004A53;">
                        Get hands-on STEM bootcamps, summer schools,
                        and practical learning experiences to prepare
                        students for the future of science & tech.
                    </p>

                    <div class="d-flex justify-content-center justify-content-md-start">
                        <button class = "primaryButton" type = "button">Coming soon</button>

                    </div>

                </div>
            </div>
        </div>

    </div>


    <div class = "container">
        <div class = "row mt-4">
            <div class = "col-12 col-md-5 col-lg-5">
                <img src = "images/LMS.png" class = "img-fluid animate__animated animate__pulse hero-img">
            </div>

            <div class = "col-12 col-md-7 col-lg-7 mt-lg-5 d-flex flex-column gap-3">
                <h3 class="" style="color :#004A53;">
                    Kokoplay
                </h3>
                <p>
                    Kokokah combines School Management, Exam Prep, and a
                    Learning Management System (LMS)—helping schools automate admin tasks,
                    boost student performance, and deliver modern digital learning in one
                    seamless platform. Kokokah combines School Management, Exam Prep, and a
                    Learning Management System (LMS)—helping schools automate admin tasks, boost
                    student performance, and deliver modern digital learning in one seamless platform.
                </p>
                <div class="d-flex justify-content-center justify-content-md-start">
                    <button class = "primaryButton" type = "button">Coming Soon</button>
                </div>

                <div>
                    <img src = "images/koodies.png"
                        class = "img-fluid w-100 w-md-50 h-auto float-end slide-up-image img-margin">
                </div>
            </div>

        </div>
    </div>


    <div class="container-fluid text-center mt-5 px-3 px-lg-5 py-5 h-100 achievement-section">
        <p class="achievement-label">Kokokah has industry-leading renewals of above 80%
        </p>
        <!-- Section Title -->
        <div class="row justify-content-center mb-5">
            <div class="col-12 mt-5">
                <h5 class="fw-bold achievement-title section-title">Why People Love Kokokah</h5>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="row g-5 justify-content-center">
            <!-- Testimonial 1 -->
            <div class="col-12 col-md-6 col-lg-5 custom-width tada-on-scroll">
                <div class="testimonial-card position-relative p-4">
                    <i class="bi bi-quote text-success fs-2 float-start"></i><br>
                    <p class="mt-3">
                        With Kokokah, we conduct online tests, share lessons digitally, and manage
                        school operations all from one dashboard.
                    </p>
                    <i class="bi bi-quote text-success fs-2 float-end"></i>
                    <img src="images/lisa.png" alt="Lisa" class="testimonial-img position-absolute rounded-circle">
                    <p class="fw-bold text-end mb-0">- Lisa</p>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="col-12 col-md-6 col-lg-5 custom-width tada-on-scroll">
                <div class="testimonial-card position-relative p-4 ">
                    <i class="bi bi-quote text-success fs-2 float-start"></i><br>
                    <p class="mt-3">
                        With Kokokah, we conduct online tests, share lessons digitally, and manage
                        school operations all from one dashboard.
                    </p>
                    <i class="bi bi-quote text-success fs-2 float-end"></i>
                    <img src="images/jimmy.png" alt="Jimmy" class="testimonial-img position-absolute rounded-circle">
                    <p class="fw-bold text-end mb-0">- Jimmy</p>
                </div>
            </div>

        </div>
    </div>




    <div class = "container-fluid founder-section">
        <div class = "row justify-content-center px-2 py-4 text-white">

            <div class = "col-12 col-lg-12 col-md-12 text-center">
                <h5 class = "mb-3 section-title" style="color:#004A53;">Message from the founder</h5>
                <img src = "images/youtube.png" class="img-fluid founder-video">
            </div>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        });

        gsap.registerPlugin(ScrollTrigger, TextPlugin);



        const phrases = [
            "Quality",
            "Mobile-First",
            "Curriculum Based Lessons & Practice Tests"
        ];

        const tl = gsap.timeline({
            repeat: -1,
            repeatDelay: 0.5
        });

        phrases.forEach((phrase) => {
            // Animate text to current phrase
            tl.to(".hero_header", {
                duration: 1,
                text: phrase,
                ease: "none"
            });

            // Small pause on each phrase
            tl.to({}, {
                duration: 1
            });
        });

        gsap.utils.toArray(".fade-section").forEach((section) => {
            gsap.from(section, { // use `from` so it starts offscreen
                opacity: 1,
                x: 600, // start 100px to the right
                duration: 0.1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: section,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse",
                }
            });
        });

        gsap.utils.toArray(".fade-section-left").forEach((section) => {
            gsap.from(section, { // use `from` so it starts offscreen
                opacity: 1,
                x: -600, // start 100px to the right
                duration: 0.1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: section,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse",
                }
            })
        });



        gsap.from(".feature-item", {
            scrollTrigger: {
                trigger: ".features",
                start: "top 80%",
                toggleActions: "play reverse play reverse",
            },
            opacity: 0,
            y: 80,
            duration: 0.5,
            ease: "back.out(1.7)", // “pop-out” effect
            stagger: {
                each: 0.5, // stagger each by 0.2s
                from: "start" // options: "start", "center", "end", or index
            }
        });

        gsap.utils.toArray(".section-title").forEach((section) => {
            gsap.from(section, {
                scale: 0.6,
                duration: 1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: section,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse"
                }
            })
        });


        gsap.utils.toArray(".tada-on-scroll").forEach((el) => {
            gsap.timeline({
                    scrollTrigger: {
                        trigger: el,
                        start: "top 75%",
                        toggleActions: "play reverse play reverse",
                        // markers: true
                    }
                })
                .to(el, {
                    duration: 0.2,
                    scale: 0.9,
                    rotation: -3,
                    ease: "power2.out"
                })
                .to(el, {
                    duration: 0.2,
                    scale: 1.1,
                    rotation: 3,
                    ease: "power2.out",
                    yoyo: true,
                    repeat: 3
                })
                .to(el, {
                    duration: 0.2,
                    scale: 1,
                    rotation: 0,
                    ease: "power2.out"
                });
        });

        gsap.utils.toArray(".slide-up-image").forEach((img) => {
            gsap.from(img, {
                y: 300, // start 100px below
                opacity: 0, // start fully transparent
                duration: 1, // animation duration
                ease: "power2.out",
                scrollTrigger: {
                    trigger: img,
                    start: "top 85%", // when image enters the viewport
                    toggleActions: "play reverse play reverse",
                    // markers: true    // optional, for debugging
                }
            });
        });
const logo = document.querySelector(".kokokah-logo");

gsap.set(logo, { xPercent: -50, yPercent: -50 });

window.addEventListener("mousemove", (e) => {
  gsap.to(logo, {
    x: e.clientX,
    y: e.clientY,
    rotation: (e.clientX / window.innerWidth - 0.5) * 15,
    duration: 0.35,
    ease: "power3.out"
  });
});


    </script>
@endsection
