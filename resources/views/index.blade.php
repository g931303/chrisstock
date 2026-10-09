<!doctype html>
<html lang="en">

<head>
  <!-- Required Meta Tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Document Title, Description, and Author -->
  <title>Chrisstock_Prophet</title>
  <meta name="description" content="Chrisstock_Prophet: AI price forecasts for Taiwan stocks.">
  <meta name="author" content="Chris Yu">

  <!-- Favicon and Touch Icons -->
  <link rel="icon" type="image/png" sizes="512x512" href="./assets/favicon/favicon-512x512.png">

  <!-- Google Fonts Files -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&family=Satisfy&display=swap" rel="stylesheet">

  <!-- CSS Files -->
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/navbars/navbar-1/assets/css/navbar-1.css"> 
 
  <!-- BSB Head -->
  <style>
    /* Template navbar CSS adds a big bottom margin to the last menu item; not wanted under the audio player */
    #audio-player { margin-bottom: 0 !important; }
    .audio-list { max-height: 18rem; max-height: min(18rem, 45vh); overflow-y: auto; overscroll-behavior: contain; }
    .audio-list .dropdown-item { white-space: normal; }

    /* Dancing music note + equalizer next to "Audio" while a song plays (the badge is hidden otherwise) */
    #audio-playing { display: inline-flex; align-items: flex-end; gap: 3px; margin-left: 4px; vertical-align: -2px; }
    #audio-playing.d-none { display: none !important; }
    #audio-playing .note { display: inline-block; font-size: 1.15em; line-height: 1; transform-origin: 50% 90%;
      animation: note-dance .6s ease-in-out infinite alternate, note-color 2.4s linear infinite; }
    #audio-playing .eq { display: inline-flex; align-items: flex-end; gap: 2px; height: 14px; }
    #audio-playing .eq i { width: 3px; height: 100%; border-radius: 2px; transform-origin: bottom;
      background: linear-gradient(to top, #0d6efd, #d63384, #fd7e14); animation: eq-bounce .9s ease-in-out infinite; }
    #audio-playing .eq i:nth-child(2) { animation-duration: .7s; animation-delay: -.3s; }
    #audio-playing .eq i:nth-child(3) { animation-duration: 1.1s; animation-delay: -.5s; }
    #audio-playing .eq i:nth-child(4) { animation-duration: .8s; animation-delay: -.2s; }
    @keyframes note-dance {
      0%   { transform: translateY(0)    rotate(-14deg) scale(1); }
      50%  { transform: translateY(-5px) rotate(0deg)   scale(1.25); }
      100% { transform: translateY(0)    rotate(14deg)  scale(1); }
    }
    @keyframes note-color {
      0%, 100% { color: #0d6efd; text-shadow: 0 0 6px rgba(13,110,253,.8); }
      33%      { color: #d63384; text-shadow: 0 0 8px rgba(214,51,132,.9); }
      66%      { color: #fd7e14; text-shadow: 0 0 8px rgba(253,126,20,.9); }
    }
    @keyframes eq-bounce { 0%, 100% { transform: scaleY(.25); } 50% { transform: scaleY(1); } }
    @media (prefers-reduced-motion: reduce) { #audio-playing .note, #audio-playing .eq i { animation: none; } }

    /* Phone tweaks: Bootstrap 5 already scales display-* headings; trim the rest */
    @media (max-width: 575.98px) {
      .display-1 { font-size: 2.25rem; }
      .display-3 { font-size: 1.85rem; line-height: 1.25; }
      .fs-4 { font-size: 1.05rem !important; }
      .fs-5 { font-size: 1rem !important; }
      h3.h1, .h1 { font-size: 1.6rem; }
      .h2 { font-size: 1.35rem; }
      .card-body.p-4 { padding: 1rem !important; }
      #senti-tables .table { font-size: 0.8rem; }
      #senti-tables > div { overflow-x: auto; }
      .visitor-badge { font-size: 0.75rem; padding: 0.25rem 0.75rem !important; margin: 0.5rem !important; }
    }
  </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#bsb-tpl-navbar" data-bs-smooth-scroll="true" tabindex="0">
  <!-- Visitor Counter -->
  <div class="visitor-badge position-fixed bottom-0 end-0 m-3 px-3 py-2 bg-primary text-white rounded-pill shadow" style="z-index: 1050;">
    Visitors: {{ $visitCount ?? 0 }}
  </div>

  <!-- Header -->
  <header id="header" class="sticky-top bsb-tpl-header-sticky bsb-tpl-header-sticky-animationX">
  <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Navbar 1 - Bootstrap Brain Component -->
    <nav id="scrollspyNav" class="navbar navbar-expand-lg bsb-tpl-bg-blue bsb-navbar bsb-navbar-hover bsb-navbar-caret bsb-tpl-navbar-sticky" data-bsb-sticky-target="#header">
      <div class="container">
        <a class="navbar-brand" href="index.html">
          <img src="/image/logo.png" class="bsb-tpl-logo" alt="">
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
          </svg>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <ul id="bsb-tpl-navbar" class="navbar-nav justify-content-end flex-grow-1">
              <li class="nav-item">
                <a class="nav-link active" href="#scrollspyNav" aria-current="page" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasNavbar">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#scrollspyServices" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasNavbar">Services</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#scrollspyPortfolio" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasNavbar">Portfolio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#scrollspyAbout" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasNavbar">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#scrollspyTeam" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasNavbar">Team</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#scrollspyPricing" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasNavbar">Pricing</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#scrollspyBlog" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasNavbar">Blog</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#scrollspyContact" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasNavbar">Contact</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Audio <span id="audio-playing" class="d-none" aria-hidden="true"><span class="note">&#9835;</span><span class="eq"><i></i><i></i><i></i><i></i></span></span></a>
                <ul class="dropdown-menu dropdown-menu-end audio-menu">
                  @if(!empty($tracks))
                    <li><a class="dropdown-item fw-semibold" href="#!" id="audio-play-all">&#9654; Play all</a></li>
                    <li><a class="dropdown-item fw-semibold" href="#!" id="audio-shuffle">&#128256; Shuffle</a></li>
                    <li><hr class="dropdown-divider"></li>
                  @endif
                  <!-- Song list scrolls on its own when long; Play all / Shuffle and the player stay in view -->
                  <li><ul class="list-unstyled mb-0 audio-list">
                  @forelse($tracks ?? [] as $track => $mtime)
                    <li><a class="dropdown-item audio-track" href="#!" data-src="{{ asset('audio/' . rawurlencode($track)) }}?v={{ $mtime }}">{{ preg_replace('/\.[^.]+$/', '', $track) }}</a></li>
                  @empty
                    <li><span class="dropdown-item-text text-secondary">No music yet</span></li>
                  @endforelse
                  </ul></li>
                  <!-- Player: shown under the list after a track is picked; keeps playing when the menu closes -->
                  <li id="audio-player" class="d-none px-3 pt-2 pb-1 border-top mt-2">
                    <div class="d-flex align-items-center gap-2 mb-1 small">
                      <span class="text-truncate fw-semibold" id="audio-title"></span>
                      <button type="button" class="btn-close btn-sm ms-auto" id="audio-close" aria-label="Stop music"></button>
                    </div>
                    <audio id="audio-el" controls preload="none" style="width: 260px; max-width: 100%;"></audio>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

  </header>

  
  <!-- Main -->
  <main id="main">

    <!-- Section - Services -->
    <!-- Service 3 - Bootstrap Brain Component -->
    <section id="scrollspyServices" class="py-5 py-xl-8 bsb-section-py-xxl-1">
      <div class="container mb-5 mb-md-6 mb-xl-10">
        <div class="row justify-content-md-center">
          <div class="col-12 col-md-10 col-lg-9 col-xl-8 col-xxl-7 text-center">
            <h2 class="display-3 fw-bolder mb-4">You will get the <br>perfect <mark class="bsb-tpl-highlight bsb-tpl-highlight-yellow"><span class="bsb-tpl-font-hw display-1 text-accent fw-normal">resolutions &amp; solutions</span></mark> with our proficient services.</h2>
          </div>
        </div>
      </div>

      <div class="container overflow-hidden">
        <div class="row gy-5 gx-md-4 gy-lg-0 gx-xxl-5 justify-content-center">
          <div class="col-11 col-sm-6 col-lg-3">
            <div class="badge bsb-tpl-bg-yellow text-primary p-3 mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-pie-chart" viewBox="0 0 16 16">
                <path d="M7.5 1.018a7 7 0 0 0-4.79 11.566L7.5 7.793V1.018zm1 0V7.5h6.482A7.001 7.001 0 0 0 8.5 1.018zM14.982 8.5H8.207l-4.79 4.79A7 7 0 0 0 14.982 8.5zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z" />
              </svg>
            </div>
            <h4 class="mb-3">Stock history price </h4>
            <p class="mb-3 text-secondary">We can help you to understand your target market and identify new opportunities for growth. We offer a variety of market research services, interviews, and focus groups.</p>
            <a href="#!" class="fw-bold text-decoration-none link-primary">
              Learn More
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z" />
              </svg>
            </a>
          </div>
          <div class="col-11 col-sm-6 col-lg-3">
            <div class="badge bsb-tpl-bg-green text-primary p-3 mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-aspect-ratio" viewBox="0 0 16 16">
                <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5v-9zM1.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z" />
                <path d="M2 4.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1H3v2.5a.5.5 0 0 1-1 0v-3zm12 7a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H13V8.5a.5.5 0 0 1 1 0v3z" />
              </svg>
            </div>
            <h4 class="mb-3">Stock price predictions</h4>
            <p class="mb-3 text-secondary">We can help you to create a visually appealing and user-friendly website. We take into account your brand identity and target audience when designing your website.</p>
            <a href="#!" class="fw-bold text-decoration-none link-primary">
              Learn More
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z" />
              </svg>
            </a>
          </div>
          <div class="col-11 col-sm-6 col-lg-3">
            <div class="badge bsb-tpl-bg-pink text-primary p-3 mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-airplane-engines" viewBox="0 0 16 16">
                <path d="M8 0c-.787 0-1.292.592-1.572 1.151A4.347 4.347 0 0 0 6 3v3.691l-2 1V7.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.191l-1.17.585A1.5 1.5 0 0 0 0 10.618V12a.5.5 0 0 0 .582.493l1.631-.272.313.937a.5.5 0 0 0 .948 0l.405-1.214 2.21-.369.375 2.253-1.318 1.318A.5.5 0 0 0 5.5 16h5a.5.5 0 0 0 .354-.854l-1.318-1.318.375-2.253 2.21.369.405 1.214a.5.5 0 0 0 .948 0l.313-.937 1.63.272A.5.5 0 0 0 16 12v-1.382a1.5 1.5 0 0 0-.83-1.342L14 8.691V7.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v.191l-2-1V3c0-.568-.14-1.271-.428-1.849C9.292.591 8.787 0 8 0ZM7 3c0-.432.11-.979.322-1.401C7.542 1.159 7.787 1 8 1c.213 0 .458.158.678.599C8.889 2.02 9 2.569 9 3v4a.5.5 0 0 0 .276.447l5.448 2.724a.5.5 0 0 1 .276.447v.792l-5.418-.903a.5.5 0 0 0-.575.41l-.5 3a.5.5 0 0 0 .14.437l.646.646H6.707l.647-.646a.5.5 0 0 0 .14-.436l-.5-3a.5.5 0 0 0-.576-.411L1 11.41v-.792a.5.5 0 0 1 .276-.447l5.448-2.724A.5.5 0 0 0 7 7V3Z" />
              </svg>
            </div>
            <h4 class="mb-3">Stock Market Sentimental</h4>
            <p class="mb-3 text-secondary">We can help you to improve your website's visibility in search engine results pages (SERPs). This can lead to more traffic to your website and more conversions.</p>
            <a href="#!" class="fw-bold text-decoration-none link-primary">
              Learn More
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z" />
              </svg>
            </a>
          </div>
          <div class="col-11 col-sm-6 col-lg-3">
            <div class="badge bsb-tpl-bg-cyan text-primary p-3 mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-alarm" viewBox="0 0 16 16">
                <path d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5z" />
                <path d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1h-3zm1.038 3.018a6.093 6.093 0 0 1 .924 0 6 6 0 1 1-.924 0zM0 3.5c0 .753.333 1.429.86 1.887A8.035 8.035 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5zM13.5 1c-.753 0-1.429.333-1.887.86a8.035 8.035 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1z" />
              </svg>
            </div>
            <h4 class="mb-3">Content Marketing</h4>
            <p class="mb-3 text-secondary">We can help you to promote your business online through a variety of digital marketing channels, including SEO, PPC, social media marketing, and email marketing.</p>
            <a href="#!" class="fw-bold text-decoration-none link-primary">
              Learn More
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Call To Action 1 - Bootstrap Brain Component -->
    <section class="bsb-cta-1 px-2 bsb-overlay" style="background-image: url('/image/cta-img-2.jpg'); background-size: cover; background-position: center;">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-9 col-lg-8 col-xl-8 col-xxl-7">
            <h3 class="fs-5 mb-3 text-white text-uppercase"><mark class="text-white bsb-tpl-highlight">Unlock Fresh Prospects</mark></h3>
            <h2 class="display-3 text-white fw-bolder mb-4 pe-xl-5">We are the stcok price predictions agency studio delivering customer's fortune secret website.</h2>
            <a href="#!" class="btn btn-accent bsb-btn-3xl rounded mb-0 text-nowrap">Join Us</a>
          </div>
        </div>
      </div>
    </section>

    <!-- Section - Portfolio -->
    <!-- Project 2 - Bootstrap Brain Component -->
    <section id="scrollspyPortfolio" class="py-5 py-xl-8 bsb-section-py-xxl-1">
      <div class="container mb-5 mb-md-6 mb-xl-10">
        <div class="row justify-content-md-center">
          <div class="col-12 col-md-10 col-lg-9 col-xl-8 col-xxl-7 text-center">
            <h2 class="display-3 fw-bolder mb-4">See how ChrisStock <br><mark class="bsb-tpl-highlight bsb-tpl-highlight-yellow"><span class="bsb-tpl-font-hw display-1 text-accent fw-normal">forecasts</span></mark> Taiwan's market.</h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-12 col-xl-11">
            <!-- ChrisStock project video (public/chrisstock_video.html); the frame is sized to the player's height so it never scrolls -->
            <iframe id="chrisstock-video" class="d-block w-100 border-0 rounded" src="/chrisstock_video.html?embed=1&amp;v={{ is_file(public_path('chrisstock_video.html')) ? filemtime(public_path('chrisstock_video.html')) : 0 }}" title="ChrisStock project video" scrolling="no" allow="autoplay; fullscreen" style="aspect-ratio: 16 / 10; background: #0d1620; overflow: hidden;"></iframe>
            <script>
              (() => {
                const f = document.getElementById('chrisstock-video');
                const setHeight = h => { if (h) Object.assign(f.style, { aspectRatio: 'auto', height: h + 'px' }); };
                const measure = () => { try { setHeight(Math.ceil(f.contentDocument.body.getBoundingClientRect().height)); } catch (e) {} };
                window.addEventListener('message', e => { if (e.origin === location.origin && e.data) setHeight(e.data.chrisstockVideoHeight); });
                f.addEventListener('load', () => { measure(); setTimeout(measure, 500); });
                window.addEventListener('resize', measure);
              })();
            </script>
          </div>
        </div>
      </div>

      <div class="container overflow-hidden">
        <div class="row gy-2 g-md-2 g-xl-3 bsb-project-2-grid">
          <div class="col-12 col-md-4 bsb-project-2-item">
            <figure class="rounded rounded-3 overflow-hidden bsb-overlay-hover m-0">
              <a href="#!">
                <img class="img-fluid bsb-scale bsb-hover-scale-up" src="/image/project-portrait-1-1.jpg" alt="">
              </a>
              <figcaption>
                <h3 class="text-white bsb-hover-fadeInUp">Chris Guru</h3>
                <div class="text-white bsb-hover-fadeInDown">C.E.O.</div>
              </figcaption>
            </figure>
          </div>

          <div class="col-12 col-md-4 bsb-project-2-item">
            <figure class="rounded rounded-3 overflow-hidden bsb-overlay-hover m-0">
              <a href="#!">
                <img class="img-fluid bsb-scale-up bsb-hover-scale" src="/image/project-portrait-3-1.jpg" alt="">
              </a>
              <figcaption>
                <h3 class="text-white bsb-hover-fadeInLeft">Jungle Hunter</h3>
                <div class="text-white bsb-hover-fadeInRight">Adventure</div>
              </figcaption>
            </figure>
          </div>

          <div class="col-12 col-md-4 bsb-project-2-item">
            <figure class="rounded rounded-3 overflow-hidden bsb-overlay-hover m-0">
              <a href="#!">
                <img class="img-fluid bsb-scale bsb-hover-scale-up" src="/image/project-portrait-4-1.jpg" alt="">
              </a>
              <figcaption>
                <h3 class="text-white bsb-hover-fadeInUp">From Heaven</h3>
                <div class="text-white bsb-hover-fadeInDown">Dreamer</div>
              </figcaption>
            </figure>
          </div>

          <div class="col-12 col-md-4 bsb-project-2-item">
            <figure class="rounded rounded-3 overflow-hidden bsb-overlay-hover m-0">
              <a href="#!">
                <img class="img-fluid bsb-scale bsb-hover-scale-up" src="/image/project-portrait-5-1.jpg" alt="">
              </a>
              <figcaption>
                <h3 class="text-white bsb-hover-fadeInUp">Extremly Master Piece</h3>
                <div class="text-white bsb-hover-fadeInDown">professional</div>
              </figcaption>
            </figure>
          </div>

          <div class="col-12 col-md-4 bsb-project-2-item">
            <figure class="rounded rounded-3 overflow-hidden bsb-overlay-hover m-0">
              <a href="#!">
                <img class="img-fluid bsb-scale bsb-hover-scale-up" src="/image/project-portrait-7-1.jpg" alt="">
              </a>
              <figcaption>
                <h3 class="text-white bsb-hover-fadeInUp">Iowa Frigate</h3>
                <div class="text-white bsb-hover-fadeInDown">Muscle</div>
              </figcaption>
            </figure>
          </div>

          <div class="col-12 col-md-4 bsb-project-2-item">
            <figure class="rounded rounded-3 overflow-hidden bsb-overlay-hover m-0">
              <a href="#!">
                <img class="img-fluid bsb-scale-up bsb-hover-scale" src="/image/" alt="">
              </a>
              <figcaption>
                <h3 class="text-white bsb-hover-fadeInLeft">Garbage Nurse</h3>
                <div class="text-white bsb-hover-fadeInRight">Original</div>
              </figcaption>
            </figure>
          </div>

          <div class="col-12 col-md-4 bsb-project-2-item">
            <figure class="rounded rounded-3 overflow-hidden bsb-overlay-hover m-0">
              <a href="#!">
                <img class="img-fluid bsb-scale-up bsb-hover-scale" src="/image/project-landscape-3-1.jpg" alt="">
              </a>
              <figcaption>
                <h3 class="text-white bsb-hover-fadeInLeft">Innovative Day</h3>
                <div class="text-white bsb-hover-fadeInRight">Photography</div>
              </figcaption>
            </figure>
          </div>
        </div>
      </div>
    </section>

    <!-- Section - About -->
    <section id="scrollspyAbout" class="bsb-tpl-bg-alice-blue py-5 py-xl-8 bsb-section-py-xxl-1">
      <!-- FAQ 1 - Bootstrap Brain Component -->
      <div class="container">
        <div class="row gy-5 gy-lg-0 align-items-lg-center">
          <div class="col-12 col-lg-6">
            <img class="img-fluid rounded" loading="lazy" src="/image/ai-stock-price-prediction.jpg" alt="">
          </div>
          <div class="col-12 col-lg-6">
            <div class="row justify-content-xl-end">
              <div class="col-12 col-xl-11">
                <h2 class="display-3 fw-bolder mb-4">Our <mark class="bsb-tpl-highlight bsb-tpl-highlight-blue"><span class="bsb-tpl-font-hw display-1 text-accent fw-normal">optimistic</span></mark><br> methods will let you prefer us.</h2>
                <p class="fs-4 mb-5">Here are the leading reasons to prefer us for your brand. We believe in transparency without any hidden barriers.</p>
                <div class="accordion accordion-flush" id="accordionExample">
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Highly Competitive Rates
                      </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        We offer some of the most competitive rates in the industry, without sacrificing quality. We understand that cost is an important factor when choosing a service provider, and we are committed to providing our clients with the best possible value for their money.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Contemporary Skills
                      </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Our team is made up of highly skilled and experienced professionals who are up-to-date on the latest trends and technologies. We are constantly investing in our team's development to ensure that we can provide our clients with the highest level of service.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Top Notch Support
                      </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        We are committed to providing our clients with top-notch support. Our team is available 24/7 to answer your questions and resolve any issues you may encounter. We believe that our support is one of our greatest strengths, and we are proud to offer it to our clients.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Fact 1 - Bootstrap Brain Component -->
      <div class="container pt-5 pt-xl-8 bsb-section-pt-xxl-1">

        <!-- Stock Selector - shared across all model buttons -->
        <div class="row justify-content-center mb-4">
          <div class="col-12 col-md-6 col-lg-4 text-center">
            <label for="stock_selector" class="fw-bold fs-5 mb-2 d-block">Select Stock Number</label>
            <select id="stock_selector" class="form-select form-select-lg text-center">
              @forelse($stocks ?? [] as $s)
                <option value="{{ $s }}">{{ $s }}</option>
              @empty
                <option value="" disabled>No stocks configured</option>
              @endforelse
            </select>
            <a href="{{ route('manage.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Manage Stock List</a>
          </div>
        </div>

        <div class="row gy-4">
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 border-bottom border-primary shadow-sm">
              <div class="card-body text-center p-4 p-xxl-5">
                <h3 class="h1 mb-2">LSTM</h3>
                <span class="badge bg-success mb-2 d-none perf-best-badge" data-model="lstm"></span>
                <form action="{{route('lstm.lstm_cal')}}" method="post">
                @csrf
                <input type="hidden" name="stock_number" class="stock-input">
                <input type="image" src="/image/button.jpg" alt="Submit" width="60" height="60">
                </form>
				<p class="fs-5 mb-0 text-secondary">Basic tyro approved</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 border-bottom border-primary shadow-sm">
              <div class="card-body text-center p-4 p-xxl-5">
                
                <h3 class="h1 mb-2">CNN_LSTM</h3>
                <span class="badge bg-success mb-2 d-none perf-best-badge" data-model="cnn_lstm"></span>
				<form action="{{route('cnn_lstm.cnn_lstm_cal')}}" method="post">
                @csrf
                <input type="hidden" name="stock_number" class="stock-input">
                <input type="image" src="/image/button.jpg" alt="Submit" width="60" height="60">
                </form>
                <p class="fs-5 mb-0 text-secondary">Advanced approved</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 border-bottom border-primary shadow-sm">
              <div class="card-body text-center p-4 p-xxl-5">
                
                <h3 class="h1 mb-2">GRU</h3>
                <span class="badge bg-success mb-2 d-none perf-best-badge" data-model="gru"></span>
				<form action="{{route('gru.gru_cal')}}" method="post">
                @csrf
                <input type="hidden" name="stock_number" class="stock-input">
                <input type="image" src="/image/button.jpg" alt="Submit" width="60" height="60">
                </form>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 border-bottom border-primary shadow-sm">
              <div class="card-body text-center p-4 p-xxl-5">
                
                <h3 class="h1 mb-2">GRU_Plus</h3>
                <span class="badge bg-success mb-2 d-none perf-best-badge" data-model="gru_keras"></span>
				<form action="{{route('gru_keras.gru_keras_cal')}}" method="post">
                @csrf
                <input type="hidden" name="stock_number" class="stock-input">
                <input type="image" src="/image/button.jpg" alt="Submit" width="60" height="60">
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Model performance (test-set scores from model_metrics.json) -->
        <div class="card border-0 shadow-sm mt-5">
          <div class="card-body p-4">
            <h3 class="h2 mb-1">Model performance <span class="text-secondary fs-5" id="perf-stock"></span></h3>
            <p class="text-secondary mb-3">How well each model predicted the next day's close on the test days it never saw in training. <span id="perf-run"></span></p>
            <div id="perf-best" class="mb-3"></div>
            <div id="perf-status" class="text-secondary">Loading…</div>
            <div class="table-responsive">
              <table class="table table-sm align-middle mb-0 d-none" id="perf-table">
                <thead><tr>
                  <th>Model</th><th class="text-end">MAPE %</th><th class="text-end perf-mae">MAE (NT$)</th>
                  <th class="text-end">RMSE</th><th class="text-end">R2</th><th class="text-end">Direction %</th>
                  <th class="text-end">Data up to</th>
                </tr></thead>
                <tbody id="perf-body"></tbody>
              </table>
            </div>
            <div id="perf-note" class="small text-secondary mt-3"></div>
          </div>
        </div>

        <!-- Prediction vs real: animated replay of the last test days (results/BACKTEST, via /model-backtest) -->
        <style>
          #race-svg { display: block; width: 100%; height: auto; touch-action: pan-y; }
          #race-svg .race-axis { font-size: 12px; fill: #52514e; font-variant-numeric: tabular-nums; }
          #race-svg .race-date { font-size: 13px; font-weight: 600; fill: #0b0b0b; }
          .race-key { display: inline-flex; align-items: center; gap: 6px; font-size: 14px; }
          .race-key[aria-pressed="false"] { opacity: .45; text-decoration: line-through; }
          .race-swatch { display: inline-block; width: 14px; height: 4px; border-radius: 2px; }
          .race-swatch-base { background: repeating-linear-gradient(90deg, #8c8b86 0 4px, transparent 4px 7px); }
          .race-cell { transition: border-color .3s, box-shadow .3s, opacity .3s; }
          .race-cell.race-lead { border-color: #0b0b0b !important; box-shadow: 0 0 0 1px #0b0b0b; }
          .race-cell.race-off { opacity: .4; }
          .race-num { font-variant-numeric: tabular-nums; }
          .race-bar { height: 4px; background: #ecebe6; border-radius: 2px; overflow: hidden; margin-top: 6px; }
          .race-bar span { display: block; height: 100%; width: 0; border-radius: 2px; }
          #race-tip { position: absolute; top: 8px; pointer-events: none; background: #fff; border: 1px solid #dedcd5; border-radius: 6px;
                      box-shadow: 0 4px 14px rgba(0,0,0,.12); padding: 8px 10px; font-size: 13px; min-width: 170px; }
          #race-tip td { padding: 1px 4px; font-variant-numeric: tabular-nums; }
        </style>
        <div class="card border-0 shadow-sm mt-4" id="race-card">
          <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
              <h3 class="h2 mb-0">Watch the models chase the price <span class="text-secondary fs-5" id="race-stock"></span></h3>
              <button type="button" class="btn btn-sm btn-outline-secondary ms-auto d-none" id="race-replay">&#8635; Replay</button>
            </div>
            <p class="text-secondary mb-3">Every day in the test period, each model guessed the next day's close without seeing it. The black line is what really happened.</p>
            <div id="race-status" class="text-secondary">Loading…</div>
            <div id="race-legend" class="d-flex flex-wrap gap-3 mb-2"></div>
            <div class="position-relative">
              <svg id="race-svg" viewBox="0 0 900 340" role="img" aria-label="Real closing price and each model's next-day prediction"></svg>
              <div id="race-tip" class="d-none"></div>
            </div>
            <div id="race-board" class="row g-2 mt-2"></div>
            <div id="race-last2" class="table-responsive mt-3"></div>
            <p id="race-note" class="small text-secondary mt-3 mb-0"></p>
          </div>
        </div>

        <!-- Sentiment before vs after (results of compare_senti.py) -->
        <div class="card border-0 shadow-sm mt-5">
          <div class="card-body p-4">
            <h3 class="h2 mb-1">Does news sentiment help? <span class="text-secondary fs-5" id="senti-stock"></span></h3>
            <p class="text-secondary mb-3" id="senti-meta">Before = model trained on price only. After = price + daily news sentiment. Same data, same test period.</p>
            <div id="senti-status" class="text-secondary">Loading…</div>
            <div id="senti-tables" class="row gy-4"></div>
            <div id="senti-note" class="small text-secondary mt-3"></div>
            <a id="senti-plot-link" href="#" target="_blank" class="d-none">
              <img id="senti-plot" src="" alt="Before vs after sentiment plot" class="img-fluid border rounded mt-3">
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Section - Team -->
    <!-- Team 1 - Bootstrap Brain Component -->
    <section id="scrollspyTeam" class="py-5 py-xl-8 bsb-section-py-xxl-1">
      <div class="container mb-5 mb-md-6 mb-xl-10">
        <div class="row justify-content-md-center">
          <div class="col-12 col-md-10 col-lg-9 col-xl-8 col-xxl-7 text-center">
            <h2 class="display-3 fw-bolder mb-4">We are a group of <br><mark class="bsb-tpl-highlight bsb-tpl-highlight-yellow"><span class="bsb-tpl-font-hw display-1 text-accent fw-normal">innovative</span></mark>, experienced, and proficient teams.</h2>
          </div>
        </div>
      </div>

      <div class="container overflow-hidden">
        <div class="row gy-4 gy-lg-0 gx-xxl-5">
          <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 border-bottom border-primary shadow-sm overflow-hidden">
              <div class="card-body p-0">
                <figure class="m-0 p-0">
                  <img class="img-fluid" loading="lazy" src="/image/team-img-1.jpg" alt="">
                  <figcaption class="m-0 p-4">
                    <h4 class="mb-1">Chris Yu</h4>
                    <p class="text-secondary mb-0">C.E.O.</p>
                  </figcaption>
                </figure>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 border-bottom border-primary shadow-sm overflow-hidden">
              <div class="card-body p-0">
                <figure class="m-0 p-0">
                  <img class="img-fluid" loading="lazy" src="/image/team-img-2.jpg" alt="">
                  <figcaption class="m-0 p-4">
                    <h4 class="mb-1">Evander Mac</h4>
                    <p class="text-secondary mb-0">Art Director</p>
                  </figcaption>
                </figure>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 border-bottom border-primary shadow-sm overflow-hidden">
              <div class="card-body p-0">
                <figure class="m-0 p-0">
                  <img class="img-fluid" loading="lazy" src="/image/team-img-3.jpg" alt="">
                  <figcaption class="m-0 p-4">
                    <h4 class="mb-1">Taytum Elia</h4>
                    <p class="text-secondary mb-0">Investment Planner</p>
                  </figcaption>
                </figure>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 border-bottom border-primary shadow-sm overflow-hidden">
              <div class="card-body p-0">
                <figure class="m-0 p-0">
                  <img class="img-fluid" loading="lazy" src="/image/team-img-4.jpg" alt="">
                  <figcaption class="m-0 p-4">
                    <h4 class="mb-1">Wylder Elio</h4>
                    <p class="text-secondary mb-0">Financial Analyst</p>
                  </figcaption>
                </figure>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    

    <!-- Section - Testimonial -->
    <!-- Testimonial 3 - Bootstrap Brain Component -->
    <section class="py-5 py-xl-8 bsb-section-py-xxl-1">
      <div class="container mb-5 mb-md-6 mb-xl-10">
        <div class="row justify-content-md-center">
          <div class="col-12 col-md-10 col-lg-9 col-xl-8 col-xxl-7 text-center">
            <h2 class="display-3 fw-bolder mb-4">We believe in client <br><mark class="bsb-tpl-highlight bsb-tpl-highlight-yellow"><span class="bsb-tpl-font-hw display-1 text-accent fw-normal">satisfaction</span></mark>. Here are some testimonials by our worthy clients.</h2>
          </div>
        </div>
      </div>

      <div class="container overflow-hidden">
        <div class="row gy-4 gy-md-0 gx-xxl-5">
          <div class="col-12 col-md-4">
            <div class="card border-0 border-bottom border-primary shadow-sm">
              <div class="card-body p-4 p-xxl-5">
                <figure>
                  <img class="img-fluid rounded rounded-circle mb-4 border border-5" loading="lazy" src="/image/testimonial-img-1.jpg" alt="Luna John">
                  <figcaption>
                    <div class="bsb-ratings text-warning mb-3" data-bsb-star="5" data-bsb-star-off="0"></div>
                    <blockquote class="bsb-blockquote-icon mb-4">We were so impressed with the work they did for us. They were able to take our vision and turn it into a reality, and they did it all on time and within budget. We would highly recommend them to anyone looking for a reliable partner.</blockquote>
                    <h4 class="mb-2">Luna John</h4>
                    <h5 class="fs-6 text-secondary mb-0">UX Designer</h5>
                  </figcaption>
                </figure>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="card border-0 border-bottom border-primary shadow-sm">
              <div class="card-body p-4 p-xxl-5">
                <figure>
                  <img class="img-fluid rounded rounded-circle mb-4 border border-5" loading="lazy" src="/image/testimonial-img-2.jpg" alt="Mark Smith">
                  <figcaption>
                    <div class="bsb-ratings text-warning mb-3" data-bsb-star="4" data-bsb-star-off="1"></div>
                    <blockquote class="bsb-blockquote-icon mb-4">We were looking for a company that could help us develop a new website that was both visually appealing and user-friendly. We are so happy with the results, and we would highly recommend them to anyone looking for a new website.</blockquote>
                    <h4 class="mb-2">Mark Smith</h4>
                    <h5 class="fs-6 text-secondary mb-0">Marketing Specialist</h5>
                  </figcaption>
                </figure>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="card border-0 border-bottom border-primary shadow-sm">
              <div class="card-body p-4 p-xxl-5">
                <figure>
                  <img class="img-fluid rounded rounded-circle mb-4 border border-5" loading="lazy" src="/image/testimonial-img-4.jpg" alt="Luke Reeves">
                  <figcaption>
                    <div class="bsb-ratings text-warning mb-3" data-bsb-star="5" data-bsb-star-off="0"></div>
                    <blockquote class="bsb-blockquote-icon mb-4">We were looking for a company that could help us with our branding. We needed a website and marketing materials. They were able to create a brand identity that we loved. They worked with us to develop a logo that represented our company.</blockquote>
                    <h4 class="mb-2">Luke Reeves</h4>
                    <h5 class="fs-6 text-secondary mb-0">Sales Manager</h5>
                  </figcaption>
                </figure>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section - Blog -->
    <!-- Blog 2 - Bootstrap Brain Component -->
    <section id="scrollspyBlog" class="bsb-tpl-bg-linen py-5 py-xl-8 bsb-section-py-xxl-1">
      <div class="container">
        <div class="row gy-5 gy-lg-0 align-items-center">
          <div class="col-12 col-lg-4">
            <h2 class="display-3 fw-bolder mb-4">Our <mark class="bsb-tpl-highlight bsb-tpl-highlight-yellow"><span class="bsb-tpl-font-hw display-1 text-accent fw-normal">Blog</span></mark></h2>
            <p class="fs-4 mb-4 mb-xl-5">Stay tuned and updated by the latest updates from our blog.</p>
            <a href="#!" class="btn bsb-btn-2xl btn-primary rounded-pill">More Plans</a>
          </div>
          <div class="col-12 col-lg-8">
            <div class="row justify-content-xl-end">
              <div class="col-12 col-xl-11">
                <div class="row gy-4 gy-xxl-5 gx-xxl-5">
                  <div class="col-12 col-lg-6">
                    <article>
                      <figure class="rounded overflow-hidden mb-3 bsb-overlay-hover">
                        <a href="#!">
                          <img class="img-fluid bsb-scale bsb-hover-scale-up" loading="lazy" src="/image/blog-image-1.jpg" alt="">
                        </a>
                        <figcaption>
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-eye text-white bsb-hover-fadeInLeft" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                          </svg>
                          <h4 class="h6 text-white bsb-hover-fadeInRight mt-2">Read More</h4>
                        </figcaption>
                      </figure>
                      <div class="entry-header mb-3">
                        <ul class="entry-meta list-unstyled d-flex mb-2">
                          <li>
                            <a class="link-primary text-decoration-none" href="#!">Business</a>
                          </li>
                        </ul>
                        <h2 class="entry-title h4 mb-0">
                          <a class="link-dark text-decoration-none" href="#!">How to Improve Your Project Management Skills</a>
                        </h2>
                      </div>
                      <div class="entry-footer">
                        <ul class="entry-meta list-unstyled d-flex align-items-center mb-0">
                          <li>
                            <a class="fs-7 link-secondary text-decoration-none d-flex align-items-center" href="#!">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z" />
                                <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                              </svg>
                              <span class="ms-2 fs-7">7 Feb 2023</span>
                            </a>
                          </li>
                          <li>
                            <span class="px-3">&bull;</span>
                          </li>
                          <li>
                            <a class="link-secondary text-decoration-none d-flex align-items-center" href="#!">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                                <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                                <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z" />
                              </svg>
                              <span class="ms-2 fs-7">55</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </article>
                  </div>
                  <div class="col-12 col-lg-6">
                    <article>
                      <figure class="rounded overflow-hidden mb-3 bsb-overlay-hover">
                        <a href="#!">
                          <img class="img-fluid bsb-scale bsb-hover-scale-up" loading="lazy" src="/image/blog-image-2.jpg" alt="">
                        </a>
                        <figcaption>
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-eye text-white bsb-hover-fadeInLeft" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                          </svg>
                          <h4 class="h6 text-white bsb-hover-fadeInRight mt-2">Read More</h4>
                        </figcaption>
                      </figure>
                      <div class="entry-header mb-3">
                        <ul class="entry-meta list-unstyled d-flex mb-2">
                          <li>
                            <a class="link-primary text-decoration-none" href="#!">Technology</a>
                          </li>
                        </ul>
                        <h2 class="entry-title h4 mb-0">
                          <a class="link-dark text-decoration-none" href="#!">Modern Cybersecurity Trends to Watch in 2023</a>
                        </h2>
                      </div>
                      <div class="entry-footer">
                        <ul class="entry-meta list-unstyled d-flex align-items-center mb-0">
                          <li>
                            <a class="fs-7 link-secondary text-decoration-none d-flex align-items-center" href="#!">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z" />
                                <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                              </svg>
                              <span class="ms-2 fs-7">12 Aug 2023</span>
                            </a>
                          </li>
                          <li>
                            <span class="px-3">&bull;</span>
                          </li>
                          <li>
                            <a class="link-secondary text-decoration-none d-flex align-items-center" href="#!">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                                <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                                <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z" />
                              </svg>
                              <span class="ms-2 fs-7">39</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </article>
                  </div>
                  <div class="col-12 col-lg-6">
                    <article>
                      <figure class="rounded overflow-hidden mb-3 bsb-overlay-hover">
                        <a href="#!">
                          <img class="img-fluid bsb-scale bsb-hover-scale-up" loading="lazy" src="/image/blog-image-3.jpg" alt="">
                        </a>
                        <figcaption>
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-eye text-white bsb-hover-fadeInLeft" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                          </svg>
                          <h4 class="h6 text-white bsb-hover-fadeInRight mt-2">Read More</h4>
                        </figcaption>
                      </figure>
                      <div class="entry-header mb-3">
                        <ul class="entry-meta list-unstyled d-flex mb-2">
                          <li>
                            <a class="link-primary text-decoration-none" href="#!">Health</a>
                          </li>
                        </ul>
                        <h2 class="entry-title h4 mb-0">
                          <a class="link-dark text-decoration-none" href="#!">Health Care Job Growth Outpaces Other Industries</a>
                        </h2>
                      </div>
                      <div class="entry-footer">
                        <ul class="entry-meta list-unstyled d-flex align-items-center mb-0">
                          <li>
                            <a class="fs-7 link-secondary text-decoration-none d-flex align-items-center" href="#!">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z" />
                                <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                              </svg>
                              <span class="ms-2 fs-7">21 Dec 2023</span>
                            </a>
                          </li>
                          <li>
                            <span class="px-3">&bull;</span>
                          </li>
                          <li>
                            <a class="link-secondary text-decoration-none d-flex align-items-center" href="#!">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                                <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                                <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z" />
                              </svg>
                              <span class="ms-2 fs-7">61</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </article>
                  </div>
                  <div class="col-12 col-lg-6">
                    <article>
                      <figure class="rounded overflow-hidden mb-3 bsb-overlay-hover">
                        <a href="#!">
                          <img class="img-fluid bsb-scale bsb-hover-scale-up" loading="lazy" src="/image/blog-image-4.jpg" alt="">
                        </a>
                        <figcaption>
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-eye text-white bsb-hover-fadeInLeft" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                          </svg>
                          <h4 class="h6 text-white bsb-hover-fadeInRight mt-2">Read More</h4>
                        </figcaption>
                      </figure>
                      <div class="entry-header mb-3">
                        <ul class="entry-meta list-unstyled d-flex mb-2">
                          <li>
                            <a class="link-primary text-decoration-none" href="#!">Networking</a>
                          </li>
                        </ul>
                        <h2 class="entry-title h4 mb-0">
                          <a class="link-dark text-decoration-none" href="#!">Five Essential Network Security Trends to Watch</a>
                        </h2>
                      </div>
                      <div class="entry-footer">
                        <ul class="entry-meta list-unstyled d-flex align-items-center mb-0">
                          <li>
                            <a class="fs-7 link-secondary text-decoration-none d-flex align-items-center" href="#!">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z" />
                                <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                              </svg>
                              <span class="ms-2 fs-7">21 Feb 2023</span>
                            </a>
                          </li>
                          <li>
                            <span class="px-3">&bull;</span>
                          </li>
                          <li>
                            <a class="link-secondary text-decoration-none d-flex align-items-center" href="#!">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                                <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                                <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z" />
                              </svg>
                              <span class="ms-2 fs-7">61</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </article>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section - Contact -->
    <!-- Contact 2 - Bootstrap Brain Component -->
    <section id="scrollspyContact" class="py-5 py-xl-8 bsb-section-py-xxl-1">
      <div class="container">
        <div class="row gy-3 gy-md-4 gy-lg-0 align-items-lg-center">
          <div class="col-12 col-lg-6">
            <img class="img-fluid rounded" loading="lazy" src="/image/contact-img-1.jpg" alt="">
          </div>
          <div class="col-12 col-lg-6">
            <div class="row justify-content-xl-center">
              <div class="col-12 col-xl-11">
                <h2 class="h1 mb-3">Get in touch</h2>
                <p class="lead fs-4 text-secondary mb-5">We're always on the lookout to work with new clients. If you're interested in working with us, please get in touch in one of the following ways.</p>
                <div class="d-flex mb-4">
                  <div class="me-4 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-geo" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="mb-3">Address</h4>
                    <address class="mb-0 text-secondary">8014 Edith Blvd NE, Albuquerque, New York, United States</address>
                  </div>
                </div>
                <div class="d-flex mb-4">
                  <div class="me-4 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-telephone-outbound" viewBox="0 0 16 16">
                      <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511zM11 .5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-4.146 4.147a.5.5 0 0 1-.708-.708L14.293 1H11.5a.5.5 0 0 1-.5-.5z" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="mb-3">Phone</h4>
                    <p class="mb-0">
                      <a class="link-secondary text-decoration-none" href="tel:+15057922430">(505) 792-2430</a>
                    </p>
                  </div>
                </div>
                <div class="d-flex">
                  <div class="me-4 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-envelope-at" viewBox="0 0 16 16">
                      <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z" />
                      <path d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648Zm-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="mb-3">E-Mail</h4>
                    <p>
                      <a class="link-secondary text-decoration-none" href="mailto:demo@yourdomain.com">demo@yourdomain.com</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Footer 2 - Bootstrap Brain Component -->
  <footer class="footer">

    <!-- Widgets - Bootstrap Brain Component -->
    <section class="bg-light py-4 py-md-5 py-xl-8 border-top border-light">
      <div class="container overflow-hidden">
        <div class="row gy-4 gy-lg-0 justify-content-xl-between">
          <div class="col-12 col-md-4 col-lg-3 col-xl-2">
            <div class="widget">
              <a href="#!">
                <img src="./assets/img/branding/wave-logo.svg" alt="" class="bsb-tpl-logo-footer">
              </a>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-3 col-xl-2">
            <div class="widget">
              <h4 class="widget-title mb-4">Get in Touch</h4>
              <address class="mb-4">8014 Edith Blvd NE, Albuquerque, New York, United States</address>
              <p class="mb-1">
                <a class="link-secondary text-decoration-none" href="tel:+15057922430">(505) 792-2430</a>
              </p>
              <p class="mb-0">
                <a class="link-secondary text-decoration-none" href="mailto:demo@yourdomain.com">demo@yourdomain.com</a>
              </p>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-3 col-xl-2">
            <div class="widget">
              <h4 class="widget-title mb-4">Learn More</h4>
              <ul class="list-unstyled">
                <li class="mb-2">
                  <a href="#!" class="link-secondary text-decoration-none">About</a>
                </li>
                <li class="mb-2">
                  <a href="#!" class="link-secondary text-decoration-none">Contact</a>
                </li>
                <li class="mb-2">
                  <a href="#!" class="link-secondary text-decoration-none">Advertise</a>
                </li>
                <li class="mb-2">
                  <a href="#!" class="link-secondary text-decoration-none">Terms of Service</a>
                </li>
                <li class="mb-0">
                  <a href="#!" class="link-secondary text-decoration-none">Privacy Policy</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-12 col-lg-3 col-xl-4">
            <div class="widget">
              <h4 class="widget-title mb-4">Our Newsletter</h4>
              <p class="mb-4">Subscribe to our newsletter to get our news & discounts delivered to you.</p>
              <form action="#!">
                <div class="row gy-4">
                  <div class="col-12">
                    <div class="input-group">
                      <span class="input-group-text" id="email-newsletter-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                          <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                        </svg>
                      </span>
                      <input type="email" class="form-control" id="email-newsletter" value="" placeholder="Email Address" aria-label="email-newsletter" aria-describedby="email-newsletter-addon" required>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-grid">
                      <button class="btn btn-primary" type="submit">Subscribe</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Copyright - Bootstrap Brain Component -->
    <div class="bg-light py-4 py-md-5 py-xl-8 border-top border-light-subtle">
      <div class="container overflow-hidden">
        <div class="row gy-4 gy-md-0">
          <div class="col-xs-12 col-md-7 order-1 order-md-0">
            <div class="copyright text-center text-md-start">
              &copy; 2023. All Rights Reserved.
            </div>
            <div class="credits text-secondary text-center text-md-start mt-2 fs-7">
              Built by <a href="https://bootstrapbrain.com/" class="link-secondary text-decoration-none">BootstrapBrain</a> with <span class="text-primary">&#9829;</span>
            </div>
          </div>

          <div class="col-xs-12 col-md-5 order-0 order-md-1">
            <ul class="nav justify-content-center justify-content-md-end">
              <li class="nav-item">
                <a class="nav-link link-dark" href="#!">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                  </svg>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link link-dark" href="#!">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                    <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
                  </svg>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link link-dark" href="#!">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                    <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                  </svg>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link link-dark" href="#!">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                    <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                  </svg>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </footer>

  <!-- Javascript Files: Vendors -->
  <script src="https://unpkg.com/jquery@3.6.1/dist/jquery.min.js"></script>
  <script src="https://unpkg.com/isotope-layout@3.0.6/dist/isotope.pkgd.min.js"></script>
  <script src="https://unpkg.com/isotope-packery@2.0.1/packery-mode.pkgd.min.js"></script>
  <script src="https://unpkg.com/imagesloaded@5.0.0/imagesloaded.pkgd.min.js"></script>

  <!-- Javascript Files: Controllers -->
  <script src="./assets/controller/project-2.js"></script>
  <script src="./assets/controller/wave-bsb.js"></script>

  <!-- Sync stock selector value into all form hidden inputs -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const selector = document.getElementById('stock_selector');
      if (!selector) {
        console.error('stock_selector element not found on page');
        return;
      }

      function syncStock() {
        document.querySelectorAll('.stock-input').forEach(function (input) {
          input.value = selector.value;
        });
      }

      syncStock();
      selector.addEventListener('change', syncStock);

      // Guarantee the hidden field is fresh at the moment of submit,
      // regardless of load/change event timing.
      document.querySelectorAll('.stock-input').forEach(function (input) {
        const form = input.closest('form');
        if (form) {
          form.addEventListener('submit', syncStock);
        }
      });
    });
  </script>

  <!-- Model performance panel: follows the stock selector -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const selector = document.getElementById('stock_selector');
      const status   = document.getElementById('perf-status');
      const tableEl  = document.getElementById('perf-table');
      const body     = document.getElementById('perf-body');
      const bestBox  = document.getElementById('perf-best');
      const note     = document.getElementById('perf-note');
      if (!selector || !status) return;

      const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
      const num = (v, d = 2) => v == null ? '–' : Number(v).toFixed(d);

      // green = better than the simple guess in the reference row (MAPE: lower than "tomorrow = today";
      // Direction %: above "always the more common move" by more than luck would explain)
      const good = on => on ? 'text-success fw-semibold' : '';

      // ▲/▼ change since the data date before the newest one; green = the model got better, red = worse
      const LOWER_IS_BETTER = { mape: true, mae: true, rmse: true, r2: false, direction: false };
      function change(m, key, digits) {
        const was = m.previous ? m.previous[key] : null;
        if (was == null || m[key] == null) return '';
        const diff = m[key] - was, size = Math.abs(diff).toFixed(digits);
        const tip = `title="${num(was, digits)} with data up to ${esc(m.previous.data_date)}"`;
        if (Number(size) === 0) return `<small class="d-block text-secondary" ${tip}>no change</small>`;
        const better = LOWER_IS_BETTER[key] ? diff < 0 : diff > 0;
        return `<small class="d-block ${better ? 'text-success' : 'text-danger'}" ${tip}>${diff < 0 ? '&#9660;' : '&#9650;'} ${size}</small>`;
      }

      // the bar to beat, shown as the last row: same test days for every model, so take the first value saved
      function guessRow(models) {
        const first = key => (models.find(m => m[key] != null) || {})[key];
        const mape = first('naive_mape'), always = first('always');
        if (mape == null && always == null) return '';   // scores saved before these fields existed
        return `
          <tr class="text-secondary fst-italic">
            <td>Simple guess</td>
            <td class="text-end">${num(mape, 3)}</td>
            <td class="perf-mae"></td><td></td><td></td>
            <td class="text-end">${num(always, 1)}</td>
            <td></td>
          </tr>`;
      }

      function load() {
        const stock = selector.value;
        document.getElementById('perf-stock').textContent = stock ? `— ${stock}` : '';
        status.textContent = 'Loading…';
        status.classList.remove('d-none');
        tableEl.classList.add('d-none');
        body.innerHTML = '';
        bestBox.innerHTML = '';
        document.getElementById('perf-run').textContent = '';
        note.textContent = '';
        document.querySelectorAll('.perf-best-badge').forEach(b => b.classList.add('d-none'));
        if (!stock) return;

        fetch(`/model-metrics/${encodeURIComponent(stock)}`)
          .then(r => r.json())
          .then(d => {
            if (d.error) { status.textContent = d.error; return; }
            status.classList.add('d-none');
            // newest data date, and the data date before it that the ▲/▼ changes compare against
            const latest = list => list.filter(Boolean).sort().at(-1);
            const newest = latest(d.models.map(m => m.data_date));
            const before = latest(d.models.map(m => m.previous && m.previous.data_date));
            document.getElementById('perf-run').textContent =
              (newest ? `Newest data: ${newest}` + (before ? `, compared with ${before} (▲/▼ under each number).` : '.') : '') +
              (d.run_time ? ` Last run: ${d.run_time}.` : '');
            const bestKey = d.best ? d.models.find(m => m.model === d.best.model).key : null;

            body.innerHTML = d.models.map(m => `
              <tr class="${m.key === bestKey ? 'table-success' : ''}">
                <td class="fw-semibold">${esc(m.model)}${m.key === bestKey ? ' &#9733;' : ''}</td>
                <td class="text-end"><span class="${good(m.beats_naive === true)}">${num(m.mape, 3)}</span>${change(m, 'mape', 3)}</td>
                <td class="text-end perf-mae">${num(m.mae)}${change(m, 'mae', 2)}</td>
                <td class="text-end">${num(m.rmse)}${change(m, 'rmse', 2)}</td>
                <td class="text-end">${num(m.r2, 4)}${change(m, 'r2', 4)}</td>
                <td class="text-end"><span class="${good(m.dir_skill === true)}">${num(m.direction, 1)}</span>${change(m, 'direction', 1)}</td>
                <td class="text-end text-secondary">${esc(m.data_date ?? '–')}</td>
              </tr>`).join('') + guessRow(d.models);
            tableEl.classList.remove('d-none');
            // MAE only exists for runs made after it was added; hide the column until some model has it
            const hasMae = d.models.some(m => m.mae != null);
            tableEl.querySelectorAll('.perf-mae').forEach(c => c.classList.toggle('d-none', !hasMae));

            if (d.best) {
              bestBox.innerHTML =
                `<span class="fs-5">Best fit for <strong>${esc(stock)}</strong>: ` +
                `<span class="badge bg-success fs-6">${esc(d.best.model)}</span></span> ` +
                (d.best.has_skill
                  ? '<span class="text-success ms-2">beats both simple guesses — real skill on the test days</span>'
                  : '<span class="text-secondary ms-2">lowest error of the four, but not yet clearly better than simple guessing</span>');
              const card = document.querySelector(`.perf-best-badge[data-model="${bestKey}"]`);
              if (card) { card.textContent = `Best for ${stock}`; card.classList.remove('d-none'); }
            }

            note.textContent =
              'Best fit = lowest MAPE (average % error of the next-day close). MAE and RMSE are in NT$ per share; lower is better. ' +
              'R2 is close to 1 for every model because prices move little day to day, so it does not separate them. ' +
              'Direction % is how often the up/down call was right. ' +
              '"Simple guess" is the bar to beat: for MAPE, predicting that tomorrow closes at today\'s price; ' +
              'for Direction %, always calling the move that was more common in the test period (usually "up"). ' +
              'A model\'s number turns green only when it beats that bar (for Direction %, by more than luck would explain).';
          })
          .catch(() => { status.textContent = 'Could not load the model scores.'; });
      }

      load();
      selector.addEventListener('change', load);
    });
  </script>

  <!-- "Watch the models chase the price": animated replay, starts when scrolled into view, follows the stock selector -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const selector = document.getElementById('stock_selector');
      const svg      = document.getElementById('race-svg');
      const status   = document.getElementById('race-status');
      const legend   = document.getElementById('race-legend');
      const board    = document.getElementById('race-board');
      const note     = document.getElementById('race-note');
      const tip      = document.getElementById('race-tip');
      const replay   = document.getElementById('race-replay');
      const last2    = document.getElementById('race-last2');
      if (!selector || !svg) return;

      const NS = 'http://www.w3.org/2000/svg';
      const COLORS = { lstm: '#2a78d6', cnn_lstm: '#eb6834', gru: '#1baf7a', gru_keras: '#eda100' };
      const INK = '#0b0b0b', NAIVE = '#8c8b86';
      const L = 62, R = 16, T = 22, B = 30, DURATION = 9000;
      let W = 900, H = 340;   // set from the card width in build(), so text stays readable on phones
      const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
      const money = v => Number(v).toLocaleString('en-US', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
      const el = (tag, attrs, parent) => {
        const e = document.createElementNS(NS, tag);
        for (const k in attrs) e.setAttribute(k, attrs[k]);
        if (parent) parent.appendChild(e);
        return e;
      };

      let d = null, raf = null, onScreen = false, started = false, done = false, shown = -1;
      const hidden = new Set();

      // ── chart ──────────────────────────────────────────────────────────────
      function build() {
        svg.innerHTML = '';
        W = Math.max(320, Math.round(svg.parentElement.clientWidth || 900));
        H = W < 600 ? Math.round(W * 0.8) : Math.min(380, Math.round(W * 0.38));
        svg.setAttribute('viewBox', `0 0 ${W} ${H}`);
        const n = d.dates.length;
        const all = d.real.concat(d.prev, ...d.models.map(m => m.pred));
        let lo = Math.min(...all), hi = Math.max(...all);
        // round grid steps (1, 2, 2.5 or 5 × a power of ten), about four of them
        const raw = (hi - lo) / 4 || 1, mag = Math.pow(10, Math.floor(Math.log10(raw)));
        const tick = [1, 2, 2.5, 5, 10].map(s => s * mag).find(s => s >= raw);
        lo = Math.floor(lo / tick) * tick; hi = Math.ceil(hi / tick) * tick;
        d.x = i => L + (W - L - R) * (n === 1 ? 0.5 : i / (n - 1));
        d.y = v => T + (H - T - B) * (1 - (v - lo) / (hi - lo));

        const grid = el('g', {}, svg);
        for (let v = lo; v <= hi + tick / 2; v += tick) {
          const yy = d.y(v);
          el('line', { x1: L, x2: W - R, y1: yy, y2: yy, stroke: '#ecebe6', 'stroke-width': 1 }, grid);
          el('text', { x: L - 8, y: yy + 4, 'text-anchor': 'end', class: 'race-axis' }, grid)
            .textContent = v.toLocaleString('en-US', { maximumFractionDigits: tick < 1 ? 2 : 0 });
        }
        [0, Math.floor((n - 1) / 2), n - 1].forEach((i, k) => {
          el('text', { x: d.x(i), y: H - 8, 'text-anchor': ['start', 'middle', 'end'][k], class: 'race-axis' }, grid).textContent = d.dates[i];
        });

        // everything drawn inside a clip rectangle that grows left → right as the replay runs
        const clip = el('clipPath', { id: 'race-clip' }, el('defs', {}, svg));
        d.clip = el('rect', { x: 0, y: 0, width: L, height: H }, clip);
        const lines = el('g', { 'clip-path': 'url(#race-clip)' }, svg);
        const path = vals => vals.map((v, i) => (i ? 'L' : 'M') + d.x(i).toFixed(1) + ' ' + d.y(v).toFixed(1)).join(' ');
        d.paths = { naive: el('path', { d: path(d.prev), fill: 'none', stroke: NAIVE, 'stroke-width': 1.5, 'stroke-dasharray': '4 4' }, lines) };
        d.models.forEach(m => {
          d.paths[m.key] = el('path', { d: path(m.pred), fill: 'none', stroke: COLORS[m.key], 'stroke-width': 2, 'stroke-linejoin': 'round', 'stroke-linecap': 'round' }, lines);
        });
        d.paths.real = el('path', { d: path(d.real), fill: 'none', stroke: INK, 'stroke-width': 2.5, 'stroke-linejoin': 'round', 'stroke-linecap': 'round' }, lines);

        // moving "today" line, date and the head of each line
        d.cursor  = el('line', { y1: T, y2: H - B, stroke: '#c9c8c2', 'stroke-width': 1 }, svg);
        d.dateTag = el('text', { y: T - 6, class: 'race-date', 'text-anchor': 'middle' }, svg);
        d.heads = {};
        d.models.forEach(m => { d.heads[m.key] = el('circle', { r: 4.5, fill: COLORS[m.key], stroke: '#fff', 'stroke-width': 2 }, svg); });
        d.heads.real = el('circle', { r: 5.5, fill: INK, stroke: '#fff', 'stroke-width': 2 }, svg);

        d.hover = el('line', { y1: T, y2: H - B, stroke: '#52514e', 'stroke-width': 1, visibility: 'hidden' }, svg);
        d.hoverDots = el('g', { visibility: 'hidden' }, svg);
      }

      // ── legend (click to hide / show a line) ──────────────────────────────
      function buildLegend() {
        const keys = [{ key: 'real', name: 'Real close', color: INK, fixed: true }]
          .concat(d.models.map(m => ({ key: m.key, name: m.model, color: COLORS[m.key] })))
          .concat([{ key: 'naive', name: 'Tomorrow = today', color: null }]);
        legend.innerHTML = keys.map(k => `
          <button type="button" class="btn btn-sm btn-link p-0 text-dark text-decoration-none race-key" data-key="${k.key}"
                  aria-pressed="${!hidden.has(k.key)}" ${k.fixed ? 'disabled style="opacity:1"' : ''}>
            <span class="race-swatch ${k.color ? '' : 'race-swatch-base'}" style="${k.color ? 'background:' + k.color : ''}"></span>${esc(k.name)}
          </button>`).join('');
        legend.querySelectorAll('.race-key:not([disabled])').forEach(b => b.addEventListener('click', () => {
          hidden.has(b.dataset.key) ? hidden.delete(b.dataset.key) : hidden.add(b.dataset.key);
          b.setAttribute('aria-pressed', !hidden.has(b.dataset.key));
          applyHidden();
        }));
      }
      function applyHidden() {
        for (const [key, p] of Object.entries(d.paths)) p.setAttribute('visibility', hidden.has(key) ? 'hidden' : 'visible');
        for (const [key, c] of Object.entries(d.heads)) c.setAttribute('visibility', hidden.has(key) || done ? 'hidden' : 'visible');
        for (const [key, cell] of Object.entries(d.cells)) cell.classList.toggle('race-off', hidden.has(key === 'always' ? 'naive' : key));
        updateBoard(shown, true);
        if (done) lastTwoDays();   // keep the 2-day table in step with the legend
      }

      // ── scoreboard: up/down calls right so far ────────────────────────────
      function tally(upto, src = d) {   // src: the newest run (d) or the one before it (d.previous)
        const right = {}; let moved = 0, ups = 0;
        for (let i = 0; i < upto; i++) {
          const move = src.real[i] - src.prev[i];
          if (move === 0) continue;   // a flat day has no direction to get right
          moved++; if (move > 0) ups++;
          src.models.forEach(m => { if (Math.sign(m.pred[i] - src.prev[i]) === Math.sign(move)) right[m.key] = (right[m.key] || 0) + 1; });
        }
        return { right, moved, ups };
      }
      function buildBoard() {
        const whole = tally(d.dates.length);
        d.alwaysUp = whole.ups * 2 >= whole.moved;   // the simple guess: always call the period's more common move
        const cells = d.models.map(m => ({ key: m.key, name: m.model, color: COLORS[m.key] }))
          .concat([{ key: 'always', name: d.alwaysUp ? 'Always guess "up"' : 'Always guess "down"', color: NAIVE }]);
        board.innerHTML = cells.map(c => `
          <div class="col-6 col-md">
            <div class="race-cell border rounded p-2 h-100" data-key="${c.key}">
              <div class="small fw-semibold d-flex align-items-center gap-2"><span class="race-swatch ${c.key === 'always' ? 'race-swatch-base' : ''}" style="${c.key === 'always' ? '' : 'background:' + c.color}"></span>${esc(c.name)}</div>
              <div class="fs-4 fw-semibold race-num">–</div>
              <div class="small text-secondary race-sub">up/down calls right</div>
              <div class="race-bar"><span style="background:${c.color}"></span></div>
              <div class="small race-change"></div>
            </div>
          </div>`).join('');
        d.cells = {};
        board.querySelectorAll('.race-cell').forEach(c => { d.cells[c.dataset.key] = c; });
      }
      function updateBoard(upto, force) {
        if (upto < 0 || (!force && upto === shown)) return;
        shown = upto;
        const { right, moved, ups } = tally(upto);
        const score = Object.assign({ always: d.alwaysUp ? ups : moved - ups }, right);
        const visible = d.models.filter(m => !hidden.has(m.key)).map(m => score[m.key] || 0);
        const lead = visible.length ? Math.max(...visible) : -1;
        for (const [key, cell] of Object.entries(d.cells)) {
          const k = score[key] || 0, pct = moved ? k / moved * 100 : 0;
          cell.querySelector('.race-num').textContent = moved ? Math.round(pct) + '%' : '–';
          cell.querySelector('.race-sub').textContent = `${k} of ${moved} up/down calls right`;
          cell.querySelector('.race-bar span').style.width = pct + '%';
          cell.classList.toggle('race-lead', key !== 'always' && !hidden.has(key) && moved > 0 && k === lead);
        }
      }

      // ── replay ────────────────────────────────────────────────────────────
      function drawAt(p) {
        const n = d.dates.length, i = Math.floor(p), j = Math.min(i + 1, n - 1), f = p - i;
        const xp = d.x(i) + (d.x(j) - d.x(i)) * f;
        d.clip.setAttribute('width', xp + 3);
        d.cursor.setAttribute('x1', xp); d.cursor.setAttribute('x2', xp);
        d.dateTag.setAttribute('x', Math.min(Math.max(xp, L + 40), W - R - 40));
        d.dateTag.textContent = d.dates[Math.round(p)];
        const at = vals => vals[i] + (vals[j] - vals[i]) * f;
        const put = (c, v) => { c.setAttribute('cx', xp); c.setAttribute('cy', d.y(v)); };
        put(d.heads.real, at(d.real));
        d.models.forEach(m => put(d.heads[m.key], at(m.pred)));
        updateBoard(i + 1);
      }
      function play() {
        cancelAnimationFrame(raf);
        done = false; shown = -1;
        tip.classList.add('d-none'); d.hover.setAttribute('visibility', 'hidden'); d.hoverDots.setAttribute('visibility', 'hidden');
        [d.cursor, d.dateTag].forEach(e => e.setAttribute('visibility', 'visible'));
        applyHidden();
        note.textContent = ''; last2.innerHTML = '';
        board.querySelectorAll('.race-change').forEach(s => { s.innerHTML = ''; });
        const last = d.dates.length - 1;
        if (reduceMotion || last === 0) { drawAt(last); finish(); return; }
        let t0 = null;
        const step = now => {
          if (t0 === null) t0 = now;
          const p = Math.min(1, (now - t0) / DURATION) * last;
          drawAt(p);
          if (p < last) raf = requestAnimationFrame(step); else finish();
        };
        raf = requestAnimationFrame(step);
      }
      function finish() {
        done = true;
        [d.cursor, d.dateTag].forEach(e => e.setAttribute('visibility', 'hidden'));
        applyHidden();
        replay.classList.remove('d-none');
        const { right, moved, ups } = tally(d.dates.length);
        const base = d.alwaysUp ? ups : moved - ups;
        const k = Math.max(...d.models.map(m => right[m.key] || 0));
        const leaders = d.models.filter(m => (right[m.key] || 0) === k).map(m => m.model);
        const names = leaders.length > 1 ? leaders.slice(0, -1).join(', ') + ' and ' + leaders.at(-1) : leaders[0];
        // ahead only counts when the gap is bigger than luck would explain (one-sided binomial test, p < 0.05)
        const p = moved ? base / moved : 0.5, se = Math.sqrt(moved * p * (1 - p));
        const verdict = k <= base ? 'No model beat that simple guess in this stretch. '
          : se > 0 && (k - base) / se > 1.645 ? `That is a real lead over the simple guess, more than luck would explain. `
          : `That is ${k - base === 1 ? 'one call' : (k - base) + ' calls'} ahead of the simple guess, too small to tell apart from luck. `;
        note.textContent =
          `Over these ${d.dates.length} days, ${names} called the next day's direction right ${k} times out of ${moved}; ` +
          `simply guessing "${d.alwaysUp ? 'up' : 'down'}" every day got ${base}. ` + verdict +
          'Notice how the coloured lines mostly trail the black one by a day: that is what "tomorrow = today" (dashed) looks like too. ' +
          'Hover or tap the chart to read each day.';
        compareRuns(right, moved, base);
        lastTwoDays();
      }

      // each card: change in % right since the run on the data date before the newest one
      function compareRuns(right, moved, base) {
        const p = d.previous && d.previous.dates && d.previous.dates.length ? d.previous : null;
        const was = p ? tally(p.dates.length, p) : null;
        const pct = (k, n) => n ? k / n * 100 : null;
        for (const [key, cell] of Object.entries(d.cells)) {
          const slot = cell.querySelector('.race-change');
          if (!was) { slot.innerHTML = ''; continue; }
          const now  = pct(key === 'always' ? base : right[key] || 0, moved);
          const then = pct(key === 'always' ? (d.alwaysUp ? was.ups : was.moved - was.ups) : was.right[key], was.moved);
          const inBoth = key === 'always' || p.models.some(m => m.key === key);   // a model new since the previous run has nothing to compare
          if (now == null || then == null || !inBoth) { slot.innerHTML = ''; continue; }
          const diff = now - then;
          slot.innerHTML = Math.abs(diff) < 0.05
            ? `<span class="text-secondary">no change vs data up to ${esc(p.dates.at(-1))}</span>`
            : `<span class="${diff > 0 ? 'text-success' : 'text-danger'}">${diff > 0 ? '&#9650;' : '&#9660;'} ${Math.abs(diff).toFixed(1)} pts</span>` +
              ` <span class="text-secondary">vs data up to ${esc(p.dates.at(-1))}</span>`;
        }
      }

      // the newest day and the day before it: what really happened, and each model's up/down call
      function lastTwoDays() {
        const n = d.dates.length;
        if (n < 2) { last2.innerHTML = ''; return; }
        const days = [n - 2, n - 1];
        const arrow = v => v > 0 ? '&#9650;' : v < 0 ? '&#9660;' : '&#8212;';
        const pctMove = i => (d.real[i] - d.prev[i]) / d.prev[i] * 100;
        const callCell = (pred, i) => {
          const call = Math.sign(pred - d.prev[i]), real = Math.sign(d.real[i] - d.prev[i]);
          const mark = real === 0 ? '' : call === real ? ' <span class="text-success">&#10003; right</span>' : ' <span class="text-danger">&#10007; wrong</span>';
          return `<td class="text-center">${arrow(call)} ${call > 0 ? 'up' : call < 0 ? 'down' : 'flat'}${mark}</td>`;
        };
        const rows = d.models.filter(m => !hidden.has(m.key)).map(m => `
          <tr><td><span class="race-swatch me-2" style="background:${COLORS[m.key]}"></span>${esc(m.model)}</td>${days.map(i => callCell(m.pred[i], i)).join('')}</tr>`).join('');
        last2.innerHTML = `
          <h4 class="h6 mb-2">Newest day vs the day before</h4>
          <table class="table table-sm align-middle mb-0">
            <thead><tr><th></th>${days.map((i, k) => `<th class="text-center">${esc(d.dates[i])}<div class="small fw-normal text-secondary">${k ? 'newest day' : 'day before'}</div></th>`).join('')}</tr></thead>
            <tbody>
              <tr class="fw-semibold"><td>Real close</td>${days.map(i => `<td class="text-center">${money(d.real[i])} <span class="${pctMove(i) > 0 ? 'text-danger' : pctMove(i) < 0 ? 'text-success' : 'text-secondary'}">${arrow(pctMove(i))} ${Math.abs(pctMove(i)).toFixed(2)}%</span></td>`).join('')}</tr>
              ${rows}
            </tbody>
          </table>`;
      }

      // ── hover: crosshair + values for the nearest day (after the replay) ──
      svg.addEventListener('pointermove', e => {
        if (!d || !done) return;
        const box = svg.getBoundingClientRect(), n = d.dates.length;
        const mx = (e.clientX - box.left) * W / box.width;
        const i = Math.max(0, Math.min(n - 1, Math.round((mx - L) / (W - L - R) * (n - 1))));
        const xp = d.x(i);
        d.hover.setAttribute('x1', xp); d.hover.setAttribute('x2', xp); d.hover.setAttribute('visibility', 'visible');
        d.hoverDots.innerHTML = '';
        const rows = [['Real close', INK, d.real[i], 'real']]
          .concat(d.models.map(m => [m.model, COLORS[m.key], m.pred[i], m.key]))
          .concat([['Tomorrow = today', NAIVE, d.prev[i], 'naive']])
          .filter(r => !hidden.has(r[3]));
        rows.forEach(([, c, v, key]) => { if (key !== 'naive') el('circle', { cx: xp, cy: d.y(v), r: 4, fill: c, stroke: '#fff', 'stroke-width': 2 }, d.hoverDots); });
        d.hoverDots.setAttribute('visibility', 'visible');
        tip.innerHTML = `<div class="fw-semibold mb-1">${esc(d.dates[i])}</div><table>${rows.map(([name, c, v, key]) =>
          `<tr><td><span class="race-swatch ${key === 'naive' ? 'race-swatch-base' : ''}" style="${key === 'naive' ? '' : 'background:' + c}"></span></td><td>${esc(name)}</td><td class="text-end fw-semibold">${money(v)}</td></tr>`).join('')}</table>`;
        tip.classList.remove('d-none');
        const px = xp / W * box.width;
        tip.style.left = (px > box.width / 2 ? px - tip.offsetWidth - 12 : px + 12) + 'px';
      });
      svg.addEventListener('pointerleave', () => {
        if (!d) return;
        tip.classList.add('d-none'); d.hover.setAttribute('visibility', 'hidden'); d.hoverDots.setAttribute('visibility', 'hidden');
      });

      // ── load per stock; play once the card is on screen ──────────────────
      function load() {
        const stock = selector.value;
        cancelAnimationFrame(raf);
        d = null; started = false; done = false; shown = -1;
        document.getElementById('race-stock').textContent = stock ? `— ${stock}` : '';
        status.textContent = 'Loading…'; status.classList.remove('d-none');
        [svg, legend, board].forEach(e => { e.innerHTML = ''; });
        note.textContent = ''; last2.innerHTML = ''; tip.classList.add('d-none'); replay.classList.add('d-none');
        if (!stock) return;
        fetch(`/model-backtest/${encodeURIComponent(stock)}`)
          .then(r => r.json())
          .then(data => {
            if (data.error || !data.dates || !data.dates.length) { status.textContent = data.error || 'No data yet.'; return; }
            if (stock !== selector.value) return;   // the visitor already picked another stock
            d = data;
            status.classList.add('d-none');
            build(); buildBoard(); buildLegend(); applyHidden(); drawAt(0);
            if (onScreen) { started = true; play(); }
          })
          .catch(() => { status.textContent = 'Could not load the day-by-day results.'; });
      }

      if ('IntersectionObserver' in window) {
        new IntersectionObserver(([e]) => {
          onScreen = e.isIntersecting;
          if (onScreen && d && !started) { started = true; play(); }
        }, { threshold: 0.5 }).observe(svg);   // the chart itself, so a tall card on a phone still starts it
      } else onScreen = true;

      // redraw at the new width after a resize or phone rotation (only once the replay has finished)
      let resizeTimer = null;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          if (!d || !done || Math.round(svg.parentElement.clientWidth) === W) return;
          build(); applyHidden(); drawAt(d.dates.length - 1); finish();
        }, 200);
      });

      replay.addEventListener('click', () => { if (d) play(); });
      selector.addEventListener('change', load);
      load();
    });
  </script>

  <!-- Sentiment before vs after panel: follows the stock selector -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const selector = document.getElementById('stock_selector');
      const status   = document.getElementById('senti-status');
      const tables   = document.getElementById('senti-tables');
      const note     = document.getElementById('senti-note');
      const plotLink = document.getElementById('senti-plot-link');
      const plotImg  = document.getElementById('senti-plot');
      if (!selector || !status) return;

      const esc  = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
      const fmt  = (v, d = 2) => Number(v).toFixed(d);
      const sign = (v, d = 2) => (v > 0 ? '+' : '') + fmt(v, d);
      const badge = { better: 'bg-success', worse: 'bg-danger', same: 'bg-secondary' };

      // "2/3" → green when every seed beat the baseline, red when none did
      function beatsCell(s) {
        if (s == null) return '<td class="text-end text-secondary">–</td>';
        const [k, n] = String(s).split('/').map(Number);
        const cls = k === n ? 'text-success fw-semibold' : (k === 0 ? 'text-danger' : '');
        return `<td class="text-end ${cls}">${esc(s)}</td>`;
      }

      function table(model, subset, rows) {
        const hasBase = rows.some(r => r.baseline != null);
        const body = rows.map(r => `
          <tr>
            <td>${esc(r.metric)}</td>
            <td class="text-end">${fmt(r.before)}</td>
            <td class="text-end">${fmt(r.after)}</td>
            <td class="text-end">${sign(r.change)}</td>
            <td><span class="badge ${badge[r.verdict]}">${esc(r.verdict)}</span></td>
            <td class="text-end">${esc(r.seeds_improved)}</td>
            ${hasBase ? `<td class="text-end text-secondary">${r.baseline == null ? '–' : fmt(r.baseline)}</td>${beatsCell(r.after_beats)}` : ''}
          </tr>`).join('');
        return `
          <div class="col-12 col-lg-6">
            <h4 class="h5 mb-2">${esc(model)} <small class="text-secondary">(${esc(subset)})</small></h4>
            <div class="table-responsive">
              <table class="table table-sm align-middle mb-0">
                <thead><tr><th>Metric</th><th class="text-end">Before</th><th class="text-end">After</th>
                  <th class="text-end">Change</th><th>Verdict</th><th class="text-end">Seeds better</th>
                  ${hasBase ? '<th class="text-end">Naive</th><th class="text-end">After beats naive</th>' : ''}</tr></thead>
                <tbody>${body}</tbody>
              </table>
            </div>
          </div>`;
      }

      function load() {
        const stock = selector.value;
        document.getElementById('senti-stock').textContent = stock ? `— ${stock}` : '';
        status.textContent = 'Loading…';
        tables.innerHTML = '';
        note.textContent = '';
        plotLink.classList.add('d-none');
        if (!stock) return;

        fetch(`/senti-compare/${encodeURIComponent(stock)}`)
          .then(r => r.json())
          .then(d => {
            if (d.error) { status.textContent = d.error; return; }
            status.textContent = `Test period ${d.test_start} → ${d.test_end} (${d.test_days} trading days), run ${d.run_date}.`;
            let html = '';
            for (const [model, subsets] of Object.entries(d.models)) {
              for (const [subset, rows] of Object.entries(subsets)) html += table(model, subset, rows);
            }
            tables.innerHTML = html;
            note.textContent =
              `${d.news_days} of ${d.test_days} test days had real news as model input. ` +
              (d.news_days < 20 ? 'That is too few to judge yet — differences are mostly training noise. ' : '') +
              'Lower is better for MAPE, MAE and RMSE; higher is better for R2 and Direction %. ' +
              '"Seeds better" counts how many repeated training runs improved with sentiment. ' +
              '"Naive" is the score of simply predicting that tomorrow closes at today\'s price ' +
              '(for Direction %, a 50% coin flip); "After beats naive" counts the runs that did better than that.';
            plotImg.src = `${d.plot_url}?t=${d.run_date}`;
            plotLink.href = plotImg.src;
            plotLink.classList.remove('d-none');
          })
          .catch(() => { status.textContent = 'Could not load the sentiment comparison.'; });
      }

      load();
      selector.addEventListener('change', load);
    });
  </script>

  <script>
    // Navbar Audio menu: play the chosen track in the player under the list
    document.addEventListener('DOMContentLoaded', function () {
      const player = document.getElementById('audio-player');
      const audio  = document.getElementById('audio-el');
      const title  = document.getElementById('audio-title');
      const badge  = document.getElementById('audio-playing');

      // Music note next to "Audio" while something is playing
      audio.addEventListener('play',  () => badge.classList.remove('d-none'));
      audio.addEventListener('pause', () => badge.classList.add('d-none'));

      function playItem(item) {
        document.querySelectorAll('.audio-track').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        item.scrollIntoView({ block: 'nearest' });   // keep the playing song visible in a long list
        title.textContent = item.textContent;
        audio.src = item.dataset.src;
        player.classList.remove('d-none');
        audio.play().catch(() => {});
      }

      // Play all: every song in turn, starting over after the last, until Stop is pressed.
      // Shuffle: the same, in random order, reshuffled each round (never the same song twice in a row).
      // A song saved in two formats (e.g. .m4a and .wav) is played once, using the first listed.
      const playAll = document.getElementById('audio-play-all');
      const shuffleBtn = document.getElementById('audio-shuffle');
      const LABELS = { all: '&#9654; Play all', shuffle: '&#128256; Shuffle' };
      let queue = null, pos = 0, mode = null;

      function stopPlayAll() {
        queue = null; mode = null;
        if (playAll) playAll.innerHTML = LABELS.all;
        if (shuffleBtn) shuffleBtn.innerHTML = LABELS.shuffle;
      }
      function uniqueSongs() {
        const seen = new Set();
        return [...document.querySelectorAll('.audio-track')].filter(i => {
          const name = i.textContent.trim();
          return !seen.has(name) && seen.add(name);
        });
      }
      function shuffled(list, avoidFirst) {
        const a = list.slice();
        for (let i = a.length - 1; i > 0; i--) { const j = Math.floor(Math.random() * (i + 1)); [a[i], a[j]] = [a[j], a[i]]; }
        if (a.length > 1 && a[0] === avoidFirst) [a[0], a[1]] = [a[1], a[0]];
        return a;
      }
      function startQueue(newMode) {
        stopPlayAll();
        mode = newMode;
        queue = mode === 'shuffle' ? shuffled(uniqueSongs()) : uniqueSongs();
        pos = 0;
        (mode === 'shuffle' ? shuffleBtn : playAll).innerHTML = '&#9632; Stop';
        playItem(queue[0]);
      }

      [[playAll, 'all'], [shuffleBtn, 'shuffle']].forEach(([btn, m]) => {
        if (!btn) return;
        btn.addEventListener('click', e => {
          e.preventDefault();
          if (mode === m) {                  // this mode is playing: the button is now Stop
            audio.pause();
            stopPlayAll();
            return;
          }
          startQueue(m);                     // also switches straight from the other mode
        });
      });

      function nextSong() {
        if (!queue) return;
        pos++;
        if (pos >= queue.length) {
          pos = 0;
          if (mode === 'shuffle') queue = shuffled(queue, queue[queue.length - 1]);
        }
        playItem(queue[pos]);
      }
      // A broken or missing file is skipped instead of stopping Play all; give up only if every song fails in a row
      let failures = 0;
      audio.addEventListener('playing', () => { failures = 0; });
      audio.addEventListener('ended', nextSong);
      audio.addEventListener('error', () => {
        if (!queue) return;
        if (++failures >= queue.length) { audio.pause(); stopPlayAll(); return; }
        nextSong();
      });

      // A song that stops getting data from the server is reloaded at the same spot after 10 s;
      // if it is still stuck 10 s later, Play all / Shuffle move on to the next song
      let lastTime = -1, stuckFor = 0;
      setInterval(() => {
        if (audio.paused || !audio.src) { stuckFor = 0; return; }
        stuckFor = audio.currentTime === lastTime ? stuckFor + 5 : 0;
        lastTime = audio.currentTime;
        if (stuckFor === 10) {
          const at = audio.currentTime;
          audio.load();
          audio.addEventListener('loadedmetadata', () => { audio.currentTime = at; }, { once: true });
          audio.play().catch(() => {});
        } else if (stuckFor >= 20) {
          stuckFor = 0;
          nextSong();
        }
      }, 5000);

      document.querySelectorAll('.audio-track').forEach(item => {
        item.addEventListener('click', e => {
          e.preventDefault();
          stopPlayAll();                     // picking one song plays just that song
          playItem(item);
        });
      });

      document.getElementById('audio-close').addEventListener('click', () => {
        audio.pause();
        stopPlayAll();
        player.classList.add('d-none');
        document.querySelectorAll('.audio-track').forEach(i => i.classList.remove('active'));
      });
    });
  </script>

  <!-- BSB Body End -->
</body>

</html>
