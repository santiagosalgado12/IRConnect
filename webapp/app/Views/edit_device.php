<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo base_url("/img/logo1.png") ;?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
    <link rel="stylesheet" href="<?php echo base_url("/css/form.css") . '?v=' . time(); ?>">
    <title>Editar dispositivo</title>
</head>
<body>


    <?php
    $session = session();

    $permiso = $session->get("tipo");
?>

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


      <div class="row">
      <div class="col-md-12">
    <form action="<?php echo base_url("/edit_device/update");?>" method="post">
    <h1>Editar dispositivo seleccionado</h1>
    <input type="hidden" name="id" value="<?php echo $device[0]['ID_dispositivo'];?>">
    <fieldset>
        <label for="name">Nombre del dispositivo</label>
        <input type="text" name="name" value="<?php echo $device[0]['nombre'];?>" required id="name">
    </fieldset>

    <fieldset>
        <label for="device_type">Tipo de dispositivo</label>
        <select name="device_type" id="device_type" required>
            <option value="tv" <?php echo $device[0]['ID_tipo']==2 ? 'selected' : '';?> >TV</option>
            <option value="aire_acondicionado" <?php echo $device[0]['ID_tipo']==1 ? 'selected' : '';?> >Aire Acondicionado</option>
            <option value="ventilador" <?php echo $device[0]['ID_tipo']==3 ? 'selected' : '';?> >Ventilador</option>
        </select>

    </fieldset>

    <fieldset>
        <label for="led">LED con el que controla el dispositivo</label>
        <select name="led" id="led" required>
            <option value="1" <?php echo $device[0]['led']==1 ? 'selected' : '';?> >LED 1</option>
            <option value="2" <?php echo $device[0]['led']==2 ? 'selected' : '';?> >LED 2</option>
            <option value="3" <?php echo $device[0]['led']==3 ? 'selected' : '';?> >LED 3</option>
        </select>
    </fieldset>

    <button type="submit">Actualizar</button>
        
    </form>
    </div>
    </div>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>