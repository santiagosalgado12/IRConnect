<?php
    $session = session();

    $permiso = $session->get("tipo");
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="icon" type="image/png" href="<?php echo base_url("/img/logo1.png") ;?>">
 
    <title>Dispositivos</title>
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
    

    <br><br><br><h1 style="margin-top: 2.5rem;">Dispositivo <?php echo $esp[0]['ubicacion']; ?></h1>
    <div class="acciones">

    <?php if($session->get('tipo')==1): ?>
    <a href="<?php echo base_url("/new_device");?>"><button class="button2">Vincular nuevo dispositivo</button></a><br><br>
    <form action="<?php echo base_url('/programsignal');?>" method="post">
      <input type="hidden" name="id" value="<?php echo $esp[0]['ID_dispositivo'];?>">
    <button class="button2" type="submit">Programar envío de señales automático</button>
    </form>
    <br>
    <a href="<?php echo base_url('/viewevents');?>"><button class="button2" type="submit">Ver eventos programados</button></a>



    <?php endif;?>
    <?php if(isset($datos) && !empty($datos)):?>

      </div>
    
      <?php
      if(isset($error)){
          echo "<h1>".$error."</h1>";
        }

      if(isset($success)){
          echo "<h1>".$success."</h1>";
        }
      ?>


<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="table-responsive">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre de dispositivo">

<table class="table table-bordered table-info mx-auto" style="background-color:rgb(120, 185, 255);">
<br><br>
<thead>
    <th>Dispositivos</th>
    <th>Acciones</th>
</thead>


<tbody>
    <?php foreach ($datos as $d):?>
      <?php 
            if ($d["ID_tipo"] == 1) {
                $url = base_url('/prueba_aircontrol');
                $grabar=base_url('/grabar_aire');
            } elseif ($d["ID_tipo"] == 2) {
                $url = base_url('/prueba_control');
                $grabar=base_url('/grabar_tele');
            } else {
                $url = base_url('/prueba_ventiladorcontrol');
                $grabar=base_url('/grabar_ventilador'); 
            }
        ?>
    <tr>
        <td><?php echo $d["nombre"];?> (Led <?php echo $d['led'];?>) </td>
    
        <td>
        <div class="botones-tabla">
        <form action="<?php echo $url;?>" method="post">
              <input type="hidden" name="id" value="<?php echo $d['ID_dispositivo'];?>">
              <input type="hidden" name="led" value="<?php echo $d['led'];?>">
              <button class="button2" type="submit">Controlar</button>
            </form>
            <?php
            if($permiso==1):?>  
            <form action="<?php echo base_url('/edit_device');?>" method="post">
              <input type="hidden" name="id" value="<?php echo $d['ID_dispositivo'];?>">
              <button class="button2" type="submit">Editar</button>
            </form>
            <button class="button2" onclick="deleteDevice('<?php echo base_url('/delete_device/'.$d['ID_dispositivo']);?>')">Eliminar</button>
            <form action="<?php echo $grabar;?>" method="post">
              <input type="hidden" name="id" value="<?php echo $d['ID_dispositivo'];?>">
              <button class="button2" type="submit">Grabar señales</button>
            </form>
            <form action="<?php echo base_url('/registros');?>" method="post">
              <input type="hidden" name="id" value="<?php echo $d['ID_dispositivo'];?>">
              <button class="button2" type="submit">Ver historial</button>
            </form>
            <?php endif;?>
          </div>
        </td>
    </tr>
    <?php endforeach;?>
</tbody>
</table>
</div>
        </div>
    </div>
</div>
    
    <?php else:?>
      <br><br>
    <h1>No hay dispositivos vinculados a este IRConnect</h1>
    <?php endif;?>

    
    <script>
    // Filtrado en tiempo real para la tabla de dispositivos
    const barraBusqueda = document.getElementById('searchInput');
    const filasDispositivos = document.querySelectorAll('table.table tbody tr'); // Selecciona las filas de la tabla

    barraBusqueda.addEventListener('input', function() {
        const valorBusqueda = barraBusqueda.value.toLowerCase();

        filasDispositivos.forEach(fila => {
            const nombreDispositivo = fila.querySelector('td:nth-child(1)').innerText.toLowerCase(); // Primera columna: Nombre del dispositivo

            // Mostrar u ocultar la fila según el valor de búsqueda
            if (nombreDispositivo.includes(valorBusqueda)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    });
</script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>   

<script>

      function deleteDevice(url){
        //CUANDO EL USUARIO TOCA ELIMINAR, SE EJECUTA ESTE JS QUE PREGUNTA SI ESTA SEGURO DE ELIMINAR EL DISPOSITIVO. EN CASO DE ACEPTAR, SE EJECUTA LA URL QUE SE PASA COMO PARAMETRO
        //QUE LLEVA AL USUARIO AL CONTROLADOR ENCARGADO DE ELIMINAR EL REGISTRO DE LA BASE DE DATOS
        if (confirm("¿Estás seguro de que deseas eliminar este dispositivo? Se eliminaran todos los accesos y las señales asociadas al mismo")) {
          window.location.href = url;
        }

      }

</script>


<style>
  body {
    max-height: 200vh;
    background-color: rgb(207, 226, 255);
    /* Azul marino a azul petróleo */
    color: #333333; /* Gris carbón para el texto */
}

/* Barra de navegación */
.textos {
    font-size: larger;
    font-weight: 600;
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
    font-size: 14px;
    border-radius: 0.5em;
    background: #0d83fd;
    border: 1px solid #0d83fd;
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
    background-image: linear-gradient(to right, #0369d6 0%, #0369d6 100%);
    transform: translateX(-50%) scaleY(1.3) scaleX(0.8);
}


  table {
    background-color:rgb(120, 185, 255);
  }

  td,tr,th {
    border: 1px solid #2C3E50;
    padding: 8px;
    text-align: center;
    font-size: 23px;
  } 
  .acciones{
    text-align: center;
    margin: 0 auto;
    margin-bottom: 33px;
    
  } 
  .acciones button {
    font-size: 18px;
    padding: 15px 30px;
    
  }

  h1{
    text-align: center;
    margin-bottom: 2.5rem;
    color: black;
    
    
  }
  .botones-tabla .button2{
    font-size: 18px;
    width: 70%;
    margin: 10px;
    min-width: 120px;
    max-width: 200px;
  }

  /* Ajustes para responsive en móviles */
  @media (max-width: 768px) {
    .botones-tabla {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }
    
    .botones-tabla .button2 {
      width: auto;
      min-width: 100px;
      max-width: 150px;
      font-size: 14px;
      padding: 8px 16px;
      margin: 4px;
    }
    
    td, tr, th {
      font-size: 16px;
      padding: 6px;
    }
    
    .table-responsive {
      font-size: 14px;
    }
  }

  /* Ajustes específicos para cuando solo hay un botón (usuarios no admin) */
  .botones-tabla form:only-child .button2 {
    width: auto;
    min-width: 100px;
    max-width: 150px;
  }
</style>
</body>
</html>