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
    
    
    <title>Eventos programados</title>
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



    <h1 style="padding-top: 3rem;">Eventos programados</h1>
 
    <?php
if(!empty($eventos)):?>
    <div class="container">
        <div class="row justify-content-center">
            <?php 
            $contador=1;
            foreach($eventos as $c):?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Evento <?php echo $contador;?></h3>
                        <p class="card-text"><b>Dispositivo:</b> <?php echo $c['dispositivo'];?></p>
                        <p class="card-text"><b>Señal a emitir:</b>  <?php echo $c['funcion'];?></p>
                        <p class="card-text"><b>Tipo:</b>  <?php echo $c['tipo'];?></p>
                        <p class="card-text"><b>Hora:</b>  <?php echo $c['hora'];?></p>
                        <?php if(isset($c['dias'])):?>
                        <p class="card-text"><b>Dia/s:</b>  <?php echo $c['dias'];?></p>
                        <?php else:?>
                        <p class="card-text"><b>Fecha:</b>  <?php echo $c['fecha'];?></p>
                        <?php endif;?>
                        <button class="button2" onclick="deleteEvent('<?php echo base_url('/deleteevent/'.$c['ID_evento']);?>')">Eliminar</button>

                    </div>
                </div>
            </div>
            <?php $contador+=1; endforeach; else:?>
        </div>
    </div>
<h1 style="padding-top: 50px;">No hay eventos creados</h1>
<?php endif;?>
    
<style>
    body {
    height: 100vh;
    background-color: rgb(207, 226, 255); /* Azul marino a azul petróleo */
    color:rgb(0, 0, 0); /* Gris carbón para el texto */
    padding-top: 80px;
}
h1{
  text-align: center;
}
.acciones button{
    margin: 0 auto;
}
.card.mb-4{
  background-color: #0d83fd;
  color: white;
}
.card-text{
  font-size: 1.3rem;
}
.button2 {
    display: inline-block;
    transition: all 0.2s ease-in;
    position: relative;
    overflow: hidden;
    z-index: 1;
    color: #ffffff;
    padding: 0.2em 0,5em;
    cursor: pointer;
    font-size: 18px;
    border-radius: 0.5em;
    background: #2C3E50;
    border: 1px solid #2C3E50;
    box-shadow: 1px 1px 4px #c5c5c5, 0px 0px 3px #ffffff;

}

.button2:active {
    color: #666;
    box-shadow: inset 4px 4px 12px #c5c5c5, inset -4px -4px 12px #ffffff;
}

.button2:before {
    content: "";
    position: absolute;
    left: 50%;
    transform: translateX(-50%) scaleY(1) scaleX(1.25);
    top: 100%;
    width: 140%;
    height: 180%;
    background-color: rgba(0, 0, 0, 0.05);
    border-radius: 50%;
    display: block;
    transition: all 0.5s 0.1s cubic-bezier(0.55, 0, 0.1, 1);
    z-index: -1;
}

.button2:after {
    content: "";
    position: absolute;
    left: 55%;
    transform: translateX(-50%) scaleY(1) scaleX(1.45);
    top: 180%;
    width: 160%;
    height: 190%;
    background-color: #fa8560;
    border-radius: 50%;
    display: block;
    transition: all 0.5s 0.1s cubic-bezier(0.55, 0, 0.1, 1);
    z-index: -1;
}

.button2:hover {
    color: #ffffff;
    border: 1px solid #2C3E50;
}

.button2:hover:before {
    top: -35%;
    background-image: linear-gradient(to right, #2980B9 0%, #4CA1AF 100%);
    transform: translateX(-50%) scaleY(1.3) scaleX(0.8);
}

</style>
<script>

      function deleteEvent(url){
        //CUANDO EL USUARIO TOCA ELIMINAR, SE EJECUTA ESTE JS QUE PREGUNTA SI ESTA SEGURO DE ELIMINAR EL DISPOSITIVO. EN CASO DE ACEPTAR, SE EJECUTA LA URL QUE SE PASA COMO PARAMETRO
        //QUE LLEVA AL USUARIO AL CONTROLADOR ENCARGADO DE ELIMINAR EL REGISTRO DE LA BASE DE DATOS
        if (confirm("¿Estás seguro de que deseas eliminar este evento?")) {
          window.location.href = url;
        }

      }

</script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
</body>
</html>