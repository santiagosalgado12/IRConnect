<?php
$resultado = [];

foreach ($permisos as $permiso) {
    $idUsuario = $permiso['Id_usuario'];
    $idDispositivo = $permiso['ID_dispositivo'];
    if (isset($resultado[$idUsuario])) {
        $resultado[$idUsuario][] = $idDispositivo;
    } else {
        $resultado[$idUsuario] = [$idDispositivo]; // Creamos el array si no existe
    }
}

$permiso=session()->get('tipo');
#var_dump($resultado);
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
    <link rel="stylesheet" href="<?php echo base_url("/css/permisosstyle.css") . '?v=' . time(); ?>">
    <title>Administrar permisos de <?php echo $user[0]['nombre_usuario']; ?></title>
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



<h1 style="margin-top: 8rem; color: black;"><center>Administrar Permisos de <?php echo $user[0]['nombre_usuario']; ?></center></h1>
<div class="mb-4" style="text-align: center;"> <!-- Centrar el input -->
    <input type="text" id="searchInput" class="form-control mx-auto" placeholder="Buscar por dispositivo o ubicación" 
           style="max-width: 100%; width: 35rem;"> <!-- Ajustar el ancho -->
</div>
<?php
if(!empty($datos)):
?>
<form action="<?php echo base_url('/actualizar_permiso');?>" method="POST">
    <?php foreach ($datos as $ubicacion => $dispositivos): ?>
        <?php if (count($dispositivos) > 0): ?>
            <div class="table-container">
                <table>
                    <caption><?php echo $ubicacion; ?></caption>
                    <tr>
                        <th>Nombre del dispositivo</th>
                        <th>Acceso</th>
                    </tr>
                    <?php foreach ($dispositivos as $disp): ?>
                        <?php
                        if (isset($resultado[$user[0]['ID_usuario']]) && in_array($disp['ID_dispositivo'], $resultado[$user[0]['ID_usuario']])) {
                            $permiso2 = true;
                        } else {
                            $permiso2 = false;
                        }
                        ?>
                        <tr>
                            <td class="device-name"><?php echo $disp['nombre']; ?></td>
                            <td>
                                <input type="hidden" name="user" value="<?php echo $user[0]['ID_usuario']; ?>">
                                <select name="permiso[<?php echo $disp['ID_dispositivo']; ?>]" class="form-select" aria-label="Default select example">
                                    <option value="permitido" <?php echo $permiso2 == true ? 'selected' : ''; ?>>Permitido</option>
                                    <option value="denegado" <?php echo $permiso2 == false ? 'selected' : ''; ?>>Denegado</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <div class="acciones">
        <center><button type="submit" class="button2">Actualizar permisos de <?php echo $user[0]['nombre_usuario']; ?></button></center>
    </div>
</form>
<?php else:?>
<center><h2>No has registrado ningún dispositivo en el sitio</h2></center>
<?php endif;?>

<script>
    // Filtrado en tiempo real para la tabla de dispositivos
    const barraBusqueda = document.getElementById('searchInput');
    const tablas = document.querySelectorAll('.table-container'); // Selecciona todas las tablas

    barraBusqueda.addEventListener('input', function () {
        const valorBusqueda = barraBusqueda.value.toLowerCase();

        tablas.forEach(tabla => {
            const ubicacion = tabla.querySelector('caption').innerText.toLowerCase(); // Obtiene la ubicación de la tabla
            const filas = tabla.querySelectorAll('tr'); // Obtiene las filas de la tabla

            let tablaVisible = false; // Bandera para verificar si la tabla debe mostrarse

            filas.forEach((fila, index) => {
                if (index === 0) return; // Salta la fila del encabezado

                const nombreDispositivo = fila.querySelector('.device-name').innerText.toLowerCase(); // Nombre del dispositivo

                // Mostrar u ocultar la fila según el valor de búsqueda
                if (nombreDispositivo.includes(valorBusqueda) || ubicacion.includes(valorBusqueda)) {
                    fila.style.display = '';
                    tablaVisible = true;
                } else {
                    fila.style.display = 'none';
                }
            });

            // Mostrar u ocultar la tabla completa según si tiene filas visibles
            tabla.style.display = tablaVisible ? '' : 'none';
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
  


</body>
</html>