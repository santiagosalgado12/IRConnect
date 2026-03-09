<?php
    $session = session();

    $permiso = $session->get("tipo");
?>  


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo base_url("/img/logo1.png") ;?>">

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
    <link rel="stylesheet" href="<?php echo base_url("/css/userinfostyle.css") . '?v=' . time(); ?>"> 
    <link rel="stylesheet" href="<?php echo base_url("/css/formstyle.css") . '?v=' . time(); ?>"> 
    <title>Información de usuario</title>
</head>
<body>
  
    
        <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
    
          <a href="<?php echo base_url("/") ;?>" class="logo d-flex align-items-center me-auto me-xl-0">
            <!-- Uncomment the line below if you also wish to use an image logo -->
           <img src="<?php echo base_url("/img/logo1.png") ;?>"  alt="logo"> 
            <h1 class="sitename">IRConnect</h1>
          </a>
    
          <nav id="navmenu" class="navmenu">
            <ul>
              <li><a href="<?php echo base_url("/") ;?>">Inicio</a></li>
              <li><a href="<?php echo base_url("/userInfo");?>">Mi usuario</a></li>
              <?php if($permiso==1):?>
              <li><a href="<?php echo base_url("/showUsers");?>">Administrar mis usuarios</a></li>
              <?php endif;?>
              <li><a href="<?php echo base_url("/logout");?>">Cerrar sesión</a></li>
              </li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
          </nav>
    
    
        </div>
      </header> 
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #0357b1;">
                <div class="card-body" >


                    <h1 style="color: white;">Información de tu usuario</h1>
                    <ul class="list-group fs-5" >
                        <li class="list-group-item border-dark" style="background-color: #0d83fd; color:white">
                            <b>Nombre de usuario: </b>
                            <?php echo $data[0]["nombre_usuario"]; ?>
                            <a href="<?php echo base_url("/generate/cambiar_usuario"); ?>">
                                <button class="btn btn-primary btn-sm" style="background-color: white; color: black">Cambiar</button>
                            </a>
                        </li>
                        <li class="list-group-item border-dark" style="background-color: #0d83fd; color:white">
                            <b>Contraseña: </b>********
                            <a href="<?php echo base_url("/generate/recuperar_contrasena"); ?>">
                                <button class="btn btn-primary btn-sm" style="background-color: white; color: black">Restablecer</button>
                            </a>
                        </li>
                        <li class="list-group-item border-dark" style="background-color: #0d83fd; color:white">
                            <b>Dirección de e-mail: </b>
                            <?php echo $data[0]["email"]; ?>
                            <a href="<?php echo base_url("/change_email"); ?>"></a>
                        </li>
                        <li class="list-group-item border-dark" style="background-color: #0d83fd; color:white">
                            <b>Fecha de creación: </b>
                            <?php echo $data[0]["fecha_creacion"]; ?>
                        </li>

                        <?php if (isset($admin)): ?>
                            <li class="list-group-item border-dark" style="background-color: #0d83fd; color:white"><b>Tipo de usuario: </b>Profesor</li>
                            <li class="list-group-item border-dark" style="background-color: #0d83fd; color:white"><b>Administrador: </b><?php echo $admin[0]["nombre_usuario"]; ?></li>
                        <?php else: ?>
                            <li class="list-group-item border-dark" style="background-color: #0d83fd; color:white"><b>Tipo de usuario: </b>Administrador</li>
                        <?php endif; ?>
                    </ul>
                    <h3>
                        <?php 
                        if ($session->getFlashdata("error")) {
                            echo $session->getFlashdata("error");
                        }
                        if ($session->getFlashdata("success")) {
                            echo $session->getFlashdata("success");
                        }
                        ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- SCRIPTS PARA NAV -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>   
</body>
</html>