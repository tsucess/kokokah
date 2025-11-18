@extends('layouts.template')

@section('content')
<div class = "container-fluid p-5 banner d-flex align-items-center justify-content-center" style="background-color : #FDAF22; height:501px;">
<h1 class = "heroheading text-center word-drop" style = "font-weight: bolder; max-width:40ch;">
<span>Kokokah</span>
  <span>is</span>
  <span>Africa’s</span>
  <span>Leading</span>
  <span>Digital</span>
  <span>Learning</span>
  <span>and</span>
  <span>Management</span>
  <span>Platform</span>
  <span>for</span>
  <span>Secondary</span>
  <span>Schools</span>
</h1>
</div>

<div class = "container mb-5 d-flex align-items-center" >
<div class = "row mt-4 bg-white">
<div class = "col-12 col-md-6 col-lg-6 my-auto fade-section-left">
<img src = "images/577d4af220704c24ce21f8c1dcc8966b836fa580.jpg" class = "img-fluid w-100 object-cover" style="height:382px; border-radius:16px;">
</div>


<div class = "col-12 col-md-6 col-lg-6 d-flex flex-column gap-3 justify-content-center fade-section">
    <h4>
        Kokokah for All.
    </h4>
    <p class = "heroparagraph">
        Kokokah is a smart, pan-African learning
        and school management platform built for
        the realities of African education.
        Whether you’re a JSS1 student in Ghana, an SSS3
        student in Kenya, or an educator in South Africa,
        our mission is simple — to give every learner from
        any background the opportunity to excel with ease.

    </p>
    <button class = "btn primaryButton"  type = "button">Discover Kokokah Product</button>
</div>

</div>
</div>


<div class = "container mt-5 mb-5">
<div class = "row">

<div class = "col-12 col-md-6 col-lg-6 my-auto fade-section-left">
    <h4 class = "mb-3">
        Our Mission & Vision
    </h4>


<div class="d-flex align-items-center">
    <div class="outer-square me-3">
        <div class="inner-circle"></div>
    </div>
    {{-- <span class="fw-bold" style="color:#004A53;">Our Vision</span> --}}
        <h6 style="color:#004A53;">Our Vision</h6>

</div>
<p class = "heropraragraph ms-5">
            We strive for a connected Africa where every
            student has equal access to quality education.
            </p>

<div class="d-flex align-items-center">
    <div class="outer-square me-3">
        <div class="inner-circle"></div>
    </div>
    {{-- <span class="fw-bold" style="color:#004A53;">Our Mision</span> --}}
    <h6 style="color:#004A53;" >Our Mision</h6>

</div>
<p class = "heroparagraph ms-5">
            To empower students, parents, and educators
           with accessible and affordable AI-powered learning
            tools designed for African schools and curriculums..
</p>



</div>


<div class = "col-12 col-md-6 col-lg-6 mt-4 my-auto fade-section-left">
<img src = "images/c862c3d4125719cc339915f430e0b2bfa58a8971.png" class = "img-fluid" style="height:540px; border-radius:16px;">
</div>


</div>
</div>




<section class="py-5" style="background-color: #F56824;">
<div class = "container" >
<div class = "row mt-4 ">

    <div class ="mb-3 justify-content-center">
        <h4 class = "fw-bold text-center text-white section-title">Our Story</h4>
    </div>

<div class = "col-12 col-md-6 col-lg-6 my-auto">
<img src = "images/our_story_img.jpg" class = "img-fluid" style="height:640px; width:100%; display:block; border-radius:20px;">
</div>


<div class = "col-12 col-md-6 col-lg-6 my-auto">
    <p class="heroparagraph text-white">
We began as a small but determined platform with a single goal:
<b>to help Nigerian secondary school students study and pass their exams.</b>
 Back then, our offerings were limited — a narrow curriculum, few resources,
 and a focus solely on preparing students for tests. It worked, but we knew
 education could be so much more.<br><br>

Over time, we listened to students, parents, and educators. We saw the challenges
African learners face — from unequal access to quality materials, to the need for
engaging, relevant content that goes beyond the exam hall. We realised we had to evolve.<br><br>

That’s how <b>Kokokah</b> was born.<br><br>

Today, Kokokah is more than just an exam-prep tool — it’s a complete <b>AI-powered
Learning Management System</b> designed for African secondary schools and learners from
<b>junior secondary to senior secondary level</b> wherever they are on the continent.
We’ve expanded from a single-country focus to a <b>pan-African mission.</b><br><br>

With <b>accessible mobile learning,</b> students can learn anywhere, even with
low internet connectivity. Our <b>AI chatbot</b>
tutor gives instant answers and personalised support.
<b>Automated grading and feedback</b> make assessment faster and smarter.
Our <b>content integration tools</b> empower educators to bring the best learning materials together in one place.<br><br>

We’ve gone from limited resources to limitless possibilities. From local impact to continental reach. From preparing students for the next exam — to preparing them for the future.
    </p>

</div>

</div>
</div>
</section>
<div class = "container-fluid d-flex flex-column gap-4" style="padding-top: 80px; padding-bottom:100px; background-image: url('images/kokokah_best.png'); background-size: cover; background-repeat: no-repeat;">


    <div class="d-flex flex-column justify-content-center gap-3">
        <h4 class = "text-center section-title">Our Core Values</h4>
        <p class = "text-center pe-5">
            Learning should be accessible, engaging, and
            empowering for every student. These values guide
            how we
            build, innovate, and serve at Kokokah.
        </p>

    </div>



<div class="row justify-content-center">

    <!-- Card 1 -->
    <div class="col-md-6 mb-4 col-lg-5 shakeX-on-scroll">
      <div class="card border-1 shadow-sm  rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4 " style="background: #FFF9F0;">
          <i class="fa-solid fa-user-circle text-warning fs-3"></i>

        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Accessiblility</h6>
          <p class="text-muted">
            We make learning available anywhere — even
            with low internet speeds and everyday
            devices — so no student is left behind.
          </p>
        </div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col-md-6 mb-4 col-lg-5 shakeX-on-scroll">
    <div class="card border-1 shadow-sm  rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4" style="background: #FFF9F0;">
          <i class="fa-solid fa-lightbulb text-warning fs-3"></i>
        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Innovation with purpose</h6>
          <p class="text-muted">
            Every feature we create — from our AI integration to
            our marketplace — solves real classroom and study challenges.
          </p>
        </div>
      </div>
    </div>

  </div>


  <div class="row justify-content-center">

    <!-- Card 3 -->
    <div class="col-md-6 col-lg-5 shakeX-on-scroll">
      <div class="card border-1 shadow-sm rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4" style="background: #FFF9F0;">
          <i class="fa-solid fa-graduation-cap text-warning fs-3"></i>

        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Excellence in learning</h6>
          <p class="text-muted">
                We align our platform to African curricula and
                 global standards, ensuring students get the best
                 tools to master concepts and excel in exams.
          </p>
        </div>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="col-md-6 col-lg-5 shakeX-on-scroll">
      <div class="card border-1 shadow-sm rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4" style="background: #FFF9F0;">
          <i class="fa-solid fa-shield text-warning fs-3"></i>

        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Integrity & Trust</h6>
          <p class="text-muted">
            We protect user data, offer transparent pricing,
            and provide safe, moderated spaces for learning.
          </p>
        </div>
      </div>
    </div>

  </div>


  <div class="row justify-content-center">

    <!-- Card 5 -->
    <div class="col-md-6 col-lg-5  mt-4 shakeX-on-scroll">
      <div class="card border-1 shadow-sm rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4" style="background: #FFF9F0;">
          <i class="fa-solid fa-people-group text-warning fs-3"></i>

        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Community & Collaboration</h6>
          <p class="text-muted">
            We connect students, parents, and educators,
            creating a supportive learning ecosystem that
            works together for student success.
          </p>
        </div>
      </div>
    </div>

    <!-- Card 6  -->
    <div class="col-md-6 col-lg-5 mt-4 shakeX-on-scroll">
      <div class="card border-1 shadow-sm h-100 rounded-4 position-relative text-center mt-3 p-3" style = "border-top-right-radius: 60px; border-bottom-left-radius:60px;">
        <!-- Icon -->
        <div class="position-absolute top-0 start-50 translate-middle rounded-4" style="background: #FFF9F0;">
          <i class="fa-solid fa-chart-line text-warning fs-3"></i>


        </div>
        <!-- Content -->
        <div class="mt-3">
          <h6>Lifelong Growth</h6>
          <p class="text-muted">
            Through Koodies and our learning tools, we prepare students
            not only for examinations, but for real-world situations and
            future opportunities.
          </p>
        </div>
      </div>
    </div>

  </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
<script>
    gsap.registerPlugin(ScrollTrigger, TextPlugin);

    gsap.utils.toArray(".section-title").forEach((section) => {
gsap.from(section, {
  scale: 0.2,
  duration: 1,
  ease: "power2.out",
  scrollTrigger: {
    trigger: section,
    start: "top 85%",
    toggleActions: "play reverse play reverse"
  }
})
});

gsap.utils.toArray(".fade-section").forEach((section) => {
  gsap.from(section, {          // use `from` so it starts offscreen
    opacity: 1,
    x: 100,                     // start 100px to the right
    duration: 0.2,
    ease: "power2.out",
    scrollTrigger: {
      trigger: section,
      start: "top 85%",
      toggleActions: "play reverse play reverse",
    }
  });
});

gsap.utils.toArray(".fade-section-left").forEach((section) => {
 gsap.from(section, {          // use `from` so it starts offscreen
    opacity: 1,
    x: -100,                     // start 100px to the right
    duration: 0.2,
    ease: "power2.out",
    scrollTrigger: {
      trigger: section,
      start: "top 85%",
      toggleActions: "play reverse play reverse",
    }
  })
});


    gsap.utils.toArray(".shakeX-on-scroll").forEach((el) => {
  gsap.timeline({
    scrollTrigger: {
      trigger: el,
      start: "top 85%",
      toggleActions: "play reverse play reverse",
      // markers: true  // uncomment to debug
    }
  })
  .to(el, { x: -10, duration: 0.1, ease: "power1.inOut" })
  .to(el, { x: 10, duration: 0.1, ease: "power1.inOut", yoyo: true, repeat: 5 })
  .to(el, { x: 0, duration: 0.1, ease: "power1.inOut" });
});

gsap.utils.toArray(".word-drop").forEach((el) => {
  // Wrap each word in a span
  const words = el.textContent.split(" ");
  el.textContent = ""; // clear original text

  words.forEach((word, index) => {
    const span = document.createElement("span");
    span.textContent = word;
    span.style.display = "inline-block"; // needed for GSAP transform
    el.appendChild(span);

    // Add a space after each word except the last one
    if (index < words.length - 1) {
      el.appendChild(document.createTextNode(" "));
    }
  });

  // Animate each word dropping from above
  const spans = el.querySelectorAll("span");

  gsap.from(spans, {
    y: -50,             // start 50px above
    opacity: 0,
    duration: 0.6,
    ease: "bounce.out",
    stagger: 0.15,       // delay between words
    scrollTrigger: {
      trigger: el,
      start: "top 85%",
      toggleActions: "play reverse play reverse",
      // markers: true  // for debugging
    }
  });
});




</script>


@endsection
