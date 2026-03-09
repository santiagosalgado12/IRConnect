<?php
$session=session();
?>
<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Inicia sesión en el sitio</title>    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
  <link rel="stylesheet" href="<?= base_url('/css/login.css') . '?v=' . time(); ?>">
  <script src="https://accounts.google.com/gsi/client" async defer></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="<?php echo base_url();?>" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/logo1.png" alt="logo"> 
        <h1 class="sitename">IRConnect</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?= base_url('/#hero');?>">Inicio</a></li>
          <li><a href="<?= base_url('/#about');?>">Acerca del producto</a></li>
          <li><a href="<?= base_url('/#features');?>">Características</a></li>
          <li><a href="<?= base_url('/#pricing');?>" >Compra aquí</a></li>
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
          <li><a href="<?= base_url('/#contact');?>">Contáctanos</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>



    </div>
  </header>

  <?php
  
  // if($error=session()->getFlashdata('error')){
  //   echo "<script>alert(".$error.")</script>";
  // }
  
  ?>

  <div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
	  <img class="backImg" src="<?= base_url('/assets/img/about-2.webp');?>">

        <div class="text">
          <span class="text-1">Inicia sesión en <br> IRConnect</span>
          <span class="text-2">Controla todos tus dispositivos</span>
        </div>
      </div>
      <div class="back">
        <img class="backImg" src="<?= base_url('/assets/img/about-5.webp');?>">
        <div class="text">
          <span class="text-1">Maneja tus dispositivos <br> desde un solo lugar</span>
          <span class="text-2">Regístrate ahora</span>
        </div>
      </div>
    </div>
    <div class="forms">
        <div class="form-content">
          <div class="login-form">
            <div class="title">Inicia sesión</div>
          <form action="<?php echo base_url("/login");?> " method="post">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Usuario o E-mail" required name="username">
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Contraseña" required name="password">
              </div>
			  <div class="checkbox-box">
                <input type="checkbox" name="remember">   <span class="text-2">Recordarme</span><br><br>
              </div>
              <?php if($error=session()->getFlashdata('error')):?>
                <div class="subtitle"><?php echo $error;?></div>
              <?php endif;?>
              <div class="text"><a href="<?php echo base_url("/reset");?>">Olvidaste tu contraseña?</a></div>
              <div class="button input-box">
                <input type="submit" value="Iniciar sesión">

              </div>
              <!-- <div class="button input-box">
                                <div id="g_id_onload"  data-client_id="262027816418-bbsdqj92a0bl5i2cjk18mak0ce1o2ohk.apps.googleusercontent.com"
                data-callback="handleCredentialResponse"
                data-auto_prompt="false">
                </div>
                <div class="g_id_signin"
                data-type="standard"
                data-size="large"
                data-theme="outline"
                data-text="sign_in_with"
                data-shape="rectangular"
                data-logo_alignment="left">
                </div>
              </div> -->
              <div class="text sign-up-text">No tienes una cuenta? <label for="flip">Regístrate</label></div>
            </div>
        </form>
      </div>
        <div class="signup-form">
          <div class="title">Registrarse</div>
        <form action="<?php echo base_url("/register");?> " method="post">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Nombre de usuario" required name="username">
              </div>
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="mail" placeholder="Dirección de E-mail" required name="email">
              </div>
             <div class="input-box">
    <i class="fas fa-lock"></i>
    <input type="password" placeholder="Contraseña" required name="password">
    <div class="tooltip-container">
        La contraseña debe tener al menos 8 caracteres, incluir al menos una letra mayúscula y un numero
    </div>
</div>
			  <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Confirma tu contraseña" required name="password_confirm">
              </div>
              <div class="button input-box">
                <input type="submit" value="Registrarse">
              </div>
              <div class="text sign-up-text">Ya tienes una cuenta? <label for="flip">Inicia sesión</label></div>
            </div>
      </form>
    </div>
    </div>
    </div>
  </div>
  <style>

.tooltip-container {
    display: none;
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 0.95em;
    margin-top: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    width: 100%;
    box-sizing: border-box;
    position: relative;
    z-index: 2;
    /* Evita que se superponga sobre el input */
}

.input-box:focus-within .tooltip-container {
    display: block;
}

/* Responsive para pantallas pequeñas */
@media (max-width: 600px) {
    .tooltip-container {
        font-size: 0.88em;
        padding: 8px 8px;
        margin-top: 6px;
    }
}


  </style>


  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>


<script src="assets/js/main.js"></script>

</body>
</html>