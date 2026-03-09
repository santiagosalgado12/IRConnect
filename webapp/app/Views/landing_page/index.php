<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>IRConnect - Gestión eficiente y comodidad garantizada.</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/logo1.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: iLanding
  * Template URL: https://bootstrapmade.com/ilanding-bootstrap-landing-page-template/
  * Updated: Nov 12 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3885522257327082"
     crossorigin="anonymous"></script>
  
     <meta name="google-adsense-account" content="ca-pub-3885522257327082">

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="<?php echo base_url();?>" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/logo1.png" alt="logo"> 
        <h1 class="sitename">IRConnect</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero">Inicio</a></li>
          <li><a href="#about">Acerca del producto</a></li>
          <li><a href="#features">Características</a></li>
          <li><a href="#pricing">Compra aquí</a></li>
          <!-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a> -->
            <!-- <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul> -->
          </li>
          <li><a href="#contact">Contáctanos</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="<?php echo base_url('/viewlogin');?>">Inicia sesión</a>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
              <div class="company-badge mb-4">
                <i class="bi bi-gear-fill me-2"></i>
                Maneja tus dispositivos a control remoto desde cualquier lugar.
              </div>

              <h1 class="mb-4">
                IRConnect 
              </h1>

              <p class="mb-4 mb-md-5">
                <h5><b>"IRConnect:</b> Gestión eficiente y comodidad garantizada."</h5>
              </p>

              <div class="hero-buttons">
                <a href="#pricing" class="btn btn-primary me-0 me-sm-2 mx-1">Adquiere el tuyo</a>
                <!-- <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="btn btn-link mt-2 mt-sm-0 glightbox">
                  <i class="bi bi-play-circle me-1"></i>
                  Video tutorial
                </a> -->
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
              <video 
                class="img-fluid" 
                autoplay 
                loop 
                muted 
                controls
                style="width: 100%; height: auto; max-width: 100%; border-radius: 8px;"
                poster="assets/img/illustration-1.webp">
                <source src="IRConnect.mp4" type="video/mp4">
                Tu navegador no soporta videos HTML5.
              </video>
            </div>
          </div>
        </div>

        <!-- <div class="row stats-row gy-4 mt-5" data-aos="fade-up" data-aos-delay="500">
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="bi bi-trophy"></i>
              </div>
              <div class="stat-content">
                <h4>3x Won Awards</h4>
                <p class="mb-0">Vestibulum ante ipsum</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="bi bi-briefcase"></i>
              </div>
              <div class="stat-content">
                <h4>6.5k Faucibus</h4>
                <p class="mb-0">Nullam quis ante</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="bi bi-graph-up"></i>
              </div>
              <div class="stat-content">
                <h4>80k Mauris</h4>
                <p class="mb-0">Etiam sit amet orci</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="bi bi-award"></i>
              </div>
              <div class="stat-content">
                <h4>6x Phasellus</h4>
                <p class="mb-0">Vestibulum ante ipsum</p>
              </div>
            </div>
          </div>
        </div>

      </div> -->

    </section>

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4 align-items-center justify-content-between">

          <div class="col-xl-5" data-aos="fade-up" data-aos-delay="200">
            <span class="about-meta">Acerca del producto</span>
            <h2 class="about-title">Olvídate de los controles perdidos. Con IRConnect, todo está en la nube.</h2>
            <p class="about-description">IRConnect es la solución definitiva para controlar dispositivos con infrarrojo desde un sitio web. Nuestro dispositivo permite grabar y emitir señales de controles remotos, facilitando la automatización en hogares, escuelas y oficinas. ¡Dile adiós a los controles físicos y maneja todo con un solo clic!</p>

            <div class="row feature-list-wrapper">
              <div class="col-md-6">
                <ul class="feature-list">
                  <li><i class="bi bi-check-circle-fill"></i> Conecta. Controla. Simplifica.</li>
                  <li><i class="bi bi-check-circle-fill"></i> Dile adiós a los controles remotos</li>
                  <li><i class="bi bi-check-circle-fill"></i> Ahorra tiempo y energía</li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="feature-list">
                  <li><i class="bi bi-check-circle-fill"></i> Fácil instalación y uso</li>
                  <li><i class="bi bi-check-circle-fill"></i> Controla desde cualquier lugar</li>
                  <li><i class="bi bi-check-circle-fill"></i> Sin cables ni configuraciones complicadas</li>
                </ul>
              </div>
            </div>

            <!-- <div class="info-wrapper">
              <div class="row gy-4">
                <div class="col-lg-5">
                  <div class="profile d-flex align-items-center gap-3">
                    <img src="assets/img/pistelli.jpg" alt="CEO Profile" class="profile-image">
                    <div>
                      <h4 class="profile-name">Mario Pistelli</h4>
                      <p class="profile-position">CEO &amp; Founder</p>
                      
                    </div>
                    <img src="assets/img/bobbio.png" alt="CEO Profile" class="profile-image">
                    <div>
                      <h4 class="profile-name">Marcelo Bobbio</h4>
                      <p class="profile-position">CO CEO &amp; CO Founder</p>
                    </div>
                    <img src="assets/img/ripanti.png" alt="CEO Profile" class="profile-image">
                    <div>
                      <h4 class="profile-name">Dario Ripanti</h4>
                      <p class="profile-position">Profesional physical &amp; Physical Teacher</p>
                    </div>
                    <img src="assets/img/pepi.jpg" alt="CEO Profile" class="profile-image">
                    <div>
                      <h4 class="profile-name">Pedro Dominguez</h4>
                      <p class="profile-position">Senior Programmer &amp; </p>
                    </div>
                  </div>
                </div>
                <br>  
                <div class="col-lg-7"><br><br><br><br><br><br><br><br>
                  <div class="contact-info d-flex align-items-center gap-2">
                    <i class="bi bi-telephone-fill"></i>
                    <div>
                      <p class="contact-label">Call us anytime</p>
                      <p class="contact-number">+54 3571 622099</p>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
          <div class="col-xl-6" data-aos="fade-up" data-aos-delay="300">
            <div class="image-wrapper">
              <div class="images position-relative" data-aos="zoom-out" data-aos-delay="400">
                <img src="assets/img/about-5.webp" alt="Business Meeting" class="img-fluid main-image rounded-4">
                <img src="assets/img/about-2.webp" alt="Team Discussion" class="img-fluid small-image rounded-4">
              </div>
              <div class="experience-badge floating">
                <h3>1+ <span>Años</span></h3>
                <p>De desarrollo del producto</p>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Features Section -->
    <section id="features" class="features section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Características</h2>
        <p>Conoce las ventajas y características más destacadas de IRConnect</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="d-flex justify-content-center">

          <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">

            <li class="nav-item">
              <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1">
                <h4>Automatización</h4>
              </a>
            </li><!-- End tab nav item -->

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2">
                <h4>Acceso remoto</h4>
              </a><!-- End tab nav item -->

            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-3">
                <h4>Integración</h4>
              </a>
            </li><!-- End tab nav item -->

          </ul>

        </div>

        <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

          <div class="tab-pane fade active show" id="features-tab-1">
            <div class="row">
              <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                <h3>Automatización Inteligente
                </h3>
                <p class="fst-italic">
                  Controla tus dispositivos sin esfuerzo
                </p>
                <ul>
                  <li><i class="bi bi-check2-all"></i> <span>Emite señales IR desde la web</span></li>
                  <li><i class="bi bi-check2-all"></i> <span>Configuración rápida y sencilla</span></li>
                  <li><i class="bi bi-check2-all"></i> <span>Sin necesidad de controles físicos</span></li>
                </ul>
              </div>
              <div class="col-lg-6 order-1 order-lg-2 text-center">
                <img src="assets/img/features-illustration-1.webp" alt="" class="img-fluid">
              </div>
            </div>
          </div><!-- End tab content item -->

          <div class="tab-pane fade" id="features-tab-2">
            <div class="row">
              <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                <h3>Acceso Remoto</h3>
                <p class="fst-italic">
                  Maneja todo desde cualquier lugar
                </p>
                <ul>
                  <li><i class="bi bi-check2-all"></i> <span>Compatible con cualquier navegador</span></li>
                  <li><i class="bi bi-check2-all"></i> <span>Conexión segura y estable</span></li>
                  <li><i class="bi bi-check2-all"></i> <span>Ideal para hogares, aulas y oficinas</span></li>
                </ul>
              </div>
              <div class="col-lg-6 order-1 order-lg-2 text-center">
                <img src="assets/img/features-illustration-2.webp" alt="" class="img-fluid">
              </div>
            </div>
          </div><!-- End tab content item -->

          <div class="tab-pane fade" id="features-tab-3">
            <div class="row">
              <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                <h3>Integración Total
                </h3>
                <ul>
                  <li><i class="bi bi-check2-all"></i> <span>Compatible con televisores, aires y ventiladores</span></li>
                  <li><i class="bi bi-check2-all"></i> <span>Sincronización con un solo clic</span></li>
                  <li><i class="bi bi-check2-all"></i> <span>Expansible a nuevos dispositivos</span></li>
                </ul>
                <p class="fst-italic">
                  Funciona con múltiples dispositivos IR
                </p>
              </div>
              <div class="col-lg-6 order-1 order-lg-2 text-center">
                <img src="assets/img/features-illustration-3.webp" alt="" class="img-fluid">
              </div>
            </div>
          </div><!-- End tab content item -->

        </div>

      </div>

    </section><!-- /Features Section -->

    <!-- Features Cards Section -->
    <section id="features-cards" class="features-cards section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
            <div class="feature-box orange">
              <i class="bi bi-award"></i>
              <h4>Fácil Configuración</h4>
              <p>Instala y usa IRConnect en minutos, sin necesidad de conocimientos técnicos.</p>
            </div>
          </div><!-- End Feature Borx-->

          <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="feature-box blue">
              <i class="bi bi-patch-check"></i>
              <h4>Control Remoto desde la Web</h4>
              <p>Administra tus dispositivos desde cualquier lugar con nuestra plataforma online.</p>
            </div>
          </div><!-- End Feature Borx-->

          <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
            <div class="feature-box green">
              <i class="bi bi-sunrise"></i>
              <h4>Compatibilidad amplia</h4>
              <p>Podrás controlar diversos dispositivos como aires acondicionados, televisores, ventiladores, entre otros.</p>
            </div>
          </div><!-- End Feature Borx-->

          <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
            <div class="feature-box red">
              <i class="bi bi-shield-check"></i>
              <h4>Seguridad Garantizada</h4>
              <p>El sitio posee distintos métodos de seguridad para los usuarios registrados.</p>
            </div>
          </div><!-- End Feature Borx-->

        </div>

      </div>

    </section><!-- /Features Cards Section -->

    <!-- Features 2 Section -->
    <section id="features-2" class="features-2 section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">

          <div class="col-lg-4">

            <div class="feature-item text-end mb-5" data-aos="fade-right" data-aos-delay="200">
              <div class="d-flex align-items-center justify-content-end gap-4">
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-display"></i>
                </div>
                <div class="feature-content">
                  <h3>Úsalo desde tu computadora</h3>
                  <p>Accede al sitio desde tu computadora y controla tus dispositivos de manera sencilla.</p>
                </div>
               
              </div>
            </div><!-- End .feature-item -->

            <div class="feature-item mb-5" data-aos="fade-left" data-aos-delay="200">
              <div class="d-flex align-items-center gap-4">
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-code-square"></i>
                </div>
                <div class="feature-content">
                  <h3>Sitio web completo</h3>
                  <p>El sitio web de IRConnect posee todas las características necesarias para utilizar el producto. Garantiza la seguridad para los usuarios</p>
                </div>
              </div>
            </div><!-- End .feature-item -->

          </div>

          <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="phone-mockup text-center">
              <img src="assets/img/phone-app-screen.webp" alt="Phone Mockup" class="img-fluid">
            </div>
          </div><!-- End Phone Mockup -->

          <div class="col-lg-4">

            

            <div class="feature-item mb-5" data-aos="fade-left" data-aos-delay="300">
              <div class="d-flex align-items-center gap-4">
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-phone"></i>
                </div>
                <div class="feature-content">
                  <h3>Úsalo con tu celular</h3>
                  <p>Accede al sitio desde tu celular estés donde estés. Solo necesitas acceso a internet.</p>
                </div>
              </div>
            </div><!-- End .feature-item -->

            <div class="feature-item" data-aos="fade-left" data-aos-delay="400">
              <div class="d-flex align-items-center gap-4">
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-browser-chrome"></i>
                </div>
                <div class="feature-content">
                  <h3>Compatible con cualquier navegador</h3>
                  <p>Podrás utilizar tu navegador de preferencia para acceder al sitio.</p>
                </div>
              </div>
            </div><!-- End .feature-item -->

          </div>
        </div>

      </div>

    </section><!-- /Features 2 Section -->
    

    <!-- Testimonials Section -->
<!-- /Services Section -->

    <!-- Pricing Section -->
    <section id="pricing" class="pricing section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Adquiere tu IRConnect</h2>
        <p>Adquiere el producto pagando una única vez accediendo a todos los beneficios</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4 justify-content-center">


          <!-- Standard Plan -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="pricing-card popular">
              <h3>IRConnect</h3>
              <div class="price">
                <span class="currency">US$</span>
                <span class="amount">59.99</span>
              </div>
              <p class="description">Paga el producto utilizando la plataforma PayPal. Con este único pago, obtendrás el producto y todas sus características</p>

              <h4>Elementos incluídos:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Acceso total a las características del producto
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Acceso a una cuenta administrador dentro del sitio web
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Posibilidad de controlar dispositivos de forma ilimitada
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Creación de usuarios ilimitados para poder controlar los dispositivos configurados
                </li>
              </ul>
              <div class="quantity-selector mb-3">
  <label for="quantity" class="form-label">Cantidad:</label>
  <div style="max-width: 150px;">
    <input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="10" oninput="updateTotal()">
  </div>
</div>
<div class="total-display mb-3">
  <strong id="total-label">Total: $</strong><span id="total-amount">59.99</span>
</div>
              <div id="paypal-button-container"></div>
              <p id="result-message"></p>

            </div>
          </div>

          
        </div>

      </div>

    </section><!-- /Pricing Section -->

    <!-- Faq Section -->
    <section class="faq-9 faq section light-background" id="faq">

      <div class="container">
        <div class="row">

          <div class="col-lg-5" data-aos="fade-up">
            <h2 class="faq-title">Tienes alguna pregunta?</h2>
            <p class="faq-description">Estas son algunas de las preguntas más frecuentes de nuestros usuarios</p>
            <div class="faq-arrow d-none d-lg-block" data-aos="fade-up" data-aos-delay="200">
              <svg class="faq-arrow" width="200" height="211" viewBox="0 0 200 211" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M198.804 194.488C189.279 189.596 179.529 185.52 169.407 182.07L169.384 182.049C169.227 181.994 169.07 181.939 168.912 181.884C166.669 181.139 165.906 184.546 167.669 185.615C174.053 189.473 182.761 191.837 189.146 195.695C156.603 195.912 119.781 196.591 91.266 179.049C62.5221 161.368 48.1094 130.695 56.934 98.891C84.5539 98.7247 112.556 84.0176 129.508 62.667C136.396 53.9724 146.193 35.1448 129.773 30.2717C114.292 25.6624 93.7109 41.8875 83.1971 51.3147C70.1109 63.039 59.63 78.433 54.2039 95.0087C52.1221 94.9842 50.0776 94.8683 48.0703 94.6608C30.1803 92.8027 11.2197 83.6338 5.44902 65.1074C-1.88449 41.5699 14.4994 19.0183 27.9202 1.56641C28.6411 0.625793 27.2862 -0.561638 26.5419 0.358501C13.4588 16.4098 -0.221091 34.5242 0.896608 56.5659C1.8218 74.6941 14.221 87.9401 30.4121 94.2058C37.7076 97.0203 45.3454 98.5003 53.0334 98.8449C47.8679 117.532 49.2961 137.487 60.7729 155.283C87.7615 197.081 139.616 201.147 184.786 201.155L174.332 206.827C172.119 208.033 174.345 211.287 176.537 210.105C182.06 207.125 187.582 204.122 193.084 201.144C193.346 201.147 195.161 199.887 195.423 199.868C197.08 198.548 193.084 201.144 195.528 199.81C196.688 199.192 197.846 198.552 199.006 197.935C200.397 197.167 200.007 195.087 198.804 194.488ZM60.8213 88.0427C67.6894 72.648 78.8538 59.1566 92.1207 49.0388C98.8475 43.9065 106.334 39.2953 114.188 36.1439C117.295 34.8947 120.798 33.6609 124.168 33.635C134.365 33.5511 136.354 42.9911 132.638 51.031C120.47 77.4222 86.8639 93.9837 58.0983 94.9666C58.8971 92.6666 59.783 90.3603 60.8213 88.0427Z" fill="currentColor"></path>
              </svg>
            </div>
          </div>

          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="300">
            <div class="faq-container">

              <div class="faq-item faq-active">
                <h3>¿Cómo es el proceso de grabación de señales dentro del sitio?</h3>
                <div class="faq-content">
                  <p>Para conocer como grabar señales de tus controles remotos, <a href="<?php echo base_url('/intrucciones');?>">Pulsa aquí para ver el instructivo</a></p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>¿Cómo registro mi usuario?</h3>
                <div class="faq-content">
                  <p>Una vez hayas adquirido IRConnect, <a href="<?= base_url('/viewlogin');?>">Pulsa aquí</a> para crear tu usuario y vincular el producto </p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>¿Qué necesito para instalar IRConnect?</h3>
                <div class="faq-content">
                  <p>Solo necesitas una conexión Wi-Fi y un navegador web para configurarlo y empezar a controlar tus dispositivos.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>¿Es compatible con todos los dispositivos?</h3>
                <div class="faq-content">
                  <p>Sí, IRConnect es compatible con la mayoría de dispositivos que funcionan con control remoto infrarrojo, incluyendo televisores, ventiladores, aires acondicionados y más.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>¿Necesito conocimientos técnicos para usarlo?</h3>
                <div class="faq-content">
                  <p>No, IRConnect está diseñado para ser fácil de usar. Su configuración es rápida e intuitiva, sin necesidad de programación avanzada.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>¿Puedo controlar mis dispositivos desde fuera de casa?</h3>
                <div class="faq-content">
                  <p>Sí, siempre que tengas conexión a Internet, puedes acceder a IRConnect y controlar tus dispositivos desde cualquier lugar.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>
          </div>

        </div>
      </div>
    </section><!-- /Faq Section -->

    <!-- Call To Action 2 Section -->
<!-- /Call To Action 2 Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contáctanos</h2>
        <p>Completa este formularo para enviarnos un E-mail de contacto a nuestro soporte</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4 g-lg-5">
          <div class="col-lg-5">
            <div class="info-box" data-aos="fade-up" data-aos-delay="200">
              <h3>Nuestra información de contacto</h3>
             

              <div class="info-item" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box">
                  <i class="bi bi-geo-alt"></i>
                </div>
                <div class="content">
                  <h4>Ubicación</h4>
                  <p>2 De Abril 1175</p>
                  <p>X5850 Río Tercero, Córdoba</p>
                </div>
              </div>

              <div class="info-item" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box">
                  <i class="bi bi-telephone"></i>
                </div>
                <div class="content">
                  <h4>Números telefónicos</h4>
                  <p>+54 3571 617228</p>
                  <p>+54 3571 622099</p>
                </div>
              </div>

              <div class="info-item" data-aos="fade-up" data-aos-delay="500">
                <div class="icon-box">
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="content">
                  <h4>Dirección Email</h4>
                  <p>irconnect33@gmail.com</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-7">
            <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
              <h3>Formulario</h3>
              <p>Envíanos un E-mail con tu pregunta</p>

              <!-- base_url('/landing/mail'); -->
              <form action="#" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                <div class="row gy-4">

                  <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Tu nombre" required="">
                  </div>

                  <div class="col-md-6 ">
                    <input type="email" class="form-control" name="email" placeholder="Tu Email" required="">
                  </div>

                  <div class="col-12">
                    <input type="text" class="form-control" name="subject" placeholder="Asunto" required="">
                  </div>

                  <div class="col-12">
                    <textarea class="form-control" name="message" rows="6" placeholder="Mensaje" required=""></textarea>
                  </div>

                  <div class="col-12 text-center">
                    <div class="loading">Cargando...</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Tu mensaje ha sido enviado, gracias!</div>

                    <button type="submit" class="btn">Enviar mensaje</button>
                  </div>

                </div>
              </form>

            </div>
          </div>

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="<?php echo base_url();?>" class="logo d-flex align-items-center">
            <span class="sitename">IRconnect</span>
          </a>
          <div class="footer-contact pt-3">
            <p>2 De Abril 1175</p>
            <p>X5850 Río Tercero, Córdoba</p>
            <p class="mt-3"><strong>Teléfono:</strong> <span>+54 3571 617228</span></p>
            <p><strong>Email:</strong> <span>irconnect33@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Links útiles</h4>
          <ul>
            <li><a href="#hero">Inicio</a></li>
            <li><a href="#about">Acerca del producto</a></li>
            <li><a href="<?php echo base_url('/viewlogin');?>">Inicia sesión</a></li>
            <li><a href="#pricing">Compra el producto</a></li>
          </ul>
        </div>

        

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">IRconnect</strong> <span>Todos los derechos reservados</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->

      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="https://www.paypal.com/sdk/js?client-id=AU7kXT2lcfGitavBqTNmysdQ9Z3tS04lx8PYLnqs41sTEV5LKJvxgUv2kawJJt-aSxlHJdT3vYAmslFv&currency=USD&disable-funding=credit,card,paylater&enable-funding=paypal"></script>

<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
                  const quantity = parseInt(document.getElementById('quantity').value);
        const unitPrice = 59.99;
        const totalAmount = (quantity * unitPrice).toFixed(2);

        

            return fetch("<?= base_url('paypal/createOrder') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                  amount: totalAmount,
                  quantity: quantity,
                  unit_price: unitPrice
                }),
            })
            .then(response => response.json())
            .then(order => order.id);
        },

        onApprove: function(data, actions) {
            return fetch("<?= base_url('paypal/captureOrder') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    orderID: data.orderID
                }),
            })
            .then(response => response.json())
            .then(order => {
                alert("Pago realizado con éxito. Muchas gracias! En instantes le llegará un mail a la dirección ingresada para la compra. ");
            })
            .catch(error => console.error("Error al capturar el pago:", error));
        }
    }).render("#paypal-button-container");

function updateTotal() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const paypalContainer = document.getElementById('paypal-button-container');
    
    if(quantity < 1 || quantity > 10 || quantity === "" || isNaN(quantity)) {
        document.getElementById('total-label').textContent = "Cantidad no válida: ";
        document.getElementById('total-amount').textContent = "seleccione una cantidad entre 1 y 10";
        paypalContainer.style.display = "none"; // Ocultar botón de PayPal
        return;
    }
    
    // Si la cantidad es válida, mostrar el total normal y el botón de PayPal
    document.getElementById('total-label').textContent = "Total: $";
    const unitPrice = 59.99;
    const total = (quantity * unitPrice).toFixed(2);
    document.getElementById('total-amount').textContent = total;
    paypalContainer.style.display = "block"; // Mostrar botón de PayPal
}
</script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>




</body>

</html>