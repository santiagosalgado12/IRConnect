<?php
$session=session();

$ip=$session->get('esp_ip');

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de televisor</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?php echo base_url("/css/styletv.css") . '?v=' . time(); ?>">
</head>


    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>


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
<div style="margin-top: 2.5rem;">
    <input type="hidden" id="deviceId" value="<?php echo $id;?>" />
    <input type="hidden" id="actionId" value="<?php echo session()->get('action_id');?>" /> <!-- Reemplaza 12345 con el ID real del dispositivo -->
    <input type="hidden" id="led" value="<?php echo $led;?>" />

    <input type="hidden" id="deleteAction" value="<?php echo base_url('/front/eliminar_accion') ?>" />
    <div class="remote-control" data-url-send-signal="<?= base_url('/enviar_senal') ?>">
    <div class="container">
        <div class="d-flex flex-row justify-content-between px-3 py-4 align-items-center">
            <i class="fas fa-chevron-left"></i>
            <span>CONTROL TV</span>
            <i class="fas fa-ellipsis-h"></i>
        </div>

        <div class="d-flex flex-row justify-content-center">
            <div class="menu-grid">
                <div class="d-flex flex-column align-items-center" data-id="1">
                    <i class="fas fa-power-off active"></i>
                    <span class="label">Power</span>
                </div>
                <div class="d-flex flex-column align-items-center" data-id="2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span class="label">Input</span>
                </div>
                <div class="d-flex flex-column align-items-center" data-id="3">
                    <i class="fas fa-cog"></i>
                    <span class="label">Control</span>
                </div>
                <div class="d-flex flex-column align-items-center" data-id="4"> 
                    <i class="fas fa-bars"></i>
                    <span class="label">Menu</span>
                </div>
                <div class="d-flex flex-column align-items-center" data-id="5">
                    <i class="fas fa-circle"></i>
                    <span class="label">Netflix</span>
                </div>
                <div class="d-flex flex-column align-items-center" data-id="6">
                    <i class="fas fa-arrow-left"></i>
                    <span class="label">Back</span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-row mt-4 justify-content-between px-2">
            <div class="d-flex flex-column rounded-bg py-3 px-4 justify-content-center align-items-center">
                <i class="fas fa-chevron-up py-3 control-icon" data-id="7"></i>
                <span class="label py-3">Channel</span>
                <i class="fas fa-chevron-down py-3 control-icon" data-id="8"></i>
            </div>
            <div class="d-flex flex-column align-items-center">
                <div class="d-flex flex-row grey-bg justify-content-center align-items-center">
                    <i class="fas fa-home p-3 home-icon" data-id="9"></i>
                </div>
                <span class="label">Home</span>
            </div>
            <div class="d-flex flex-column rounded-bg py-3 px-4 justify-content-center align-items-center">
                <i class="fas fa-plus py-3 control-icon" data-id="10"></i>
                <span class="label py-3">Volume</span>
                <i class="fas fa-minus py-3 control-icon" data-id="11"></i>
            </div>
        </div>

        <div class="mt-5 pt-4 position-relative d-flex flex-row justify-content-center align-items-center">
            <div class="circle ok-inner position-absolute" data-id="16">
                <span>OK</span>
            </div>
            <div class="circle ok-outer position-absolute"></div>
            <i class="fas fa-caret-right position-absolute control-icon right" data-id="14"></i>
            <i class="fas fa-caret-right position-absolute control-icon bottom" data-id="15"></i>
            <i class="fas fa-caret-right position-absolute control-icon left" data-id="13"></i>
            <i class="fas fa-caret-right position-absolute control-icon top" data-id="12"></i>
        </div>

        <div class="d-flex flex-row justify-content-between mt-5 pt-4 px-3">
            <div class="d-flex flex-row grey-bg">
                <i class="fas fa-ellipsis-h p-3 control-icon" data-id="17"></i>
            </div>
            <div class="d-flex flex-row grey-bg">
                <i class="fas fa-volume-mute p-3 control-icon" data-id="18"></i>
            </div>
        </div>
        <div>
        <button class="button-small" data-id="19">1</button>
        <button class="button-small" data-id="20">2</button>
        <button class="button-small" data-id="21">3</button>
    </div>
    <div>
        <button class="button-small" data-id="22">4</button>
        <button class="button-small" data-id="23">5</button>
        <button class="button-small" data-id="24">6</button>
    </div>
    <div>
        <button class="button-small" data-id="25">7</button>
        <button class="button-small" data-id="26">8</button>
        <button class="button-small" data-id="27">9</button>
    </div>
        <button class="button-rect" data-id="28">0</button>
    </div>
</div>
    </div>
</div>
</div>


<script>
  function deleteAction() {
    const urlElement = document.getElementById('deleteAction');
    const actionElement = document.getElementById('actionId');

    if (urlElement && actionElement) {
      const url = urlElement.value;
      const action_id = actionElement.value;

      if (url && action_id) {
        const payload = new Blob([JSON.stringify({ action_id })], { type: 'application/json' });
        navigator.sendBeacon(url, payload);
      }
    }
  }

  window.addEventListener('beforeunload', function (e) {
    deleteAction();

    // Mensaje de confirmación antes de salir
    const confirmationMessage = '¿Estás seguro de que deseas abandonar esta página?';
    e.returnValue = confirmationMessage;
    return confirmationMessage;
  });

  // Tiempo de inactividad en milisegundos (5 minutos)
  const INACTIVITY_TIME = 3 * 60 * 1000;

  // Variable para almacenar el temporizador
  let inactivityTimer;

  // Función para redirigir al usuario
  function redirectToAnotherRoute() {
    deleteAction();
    window.location.href = '<?php echo base_url('/'); ?>';
  }

  // Función para reiniciar el temporizador de inactividad
  function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(redirectToAnotherRoute, INACTIVITY_TIME);
  }

  // Eventos para detectar actividad del usuario
  window.onload = resetInactivityTimer;
  window.onmousemove = resetInactivityTimer;
  window.onmousedown = resetInactivityTimer; // Detecta clics del mouse
  window.ontouchstart = resetInactivityTimer; // Detecta toques en dispositivos táctiles
  window.onclick = resetInactivityTimer; // Detecta clics
  window.onkeypress = resetInactivityTimer; // Detecta pulsaciones de teclas
  window.addEventListener('scroll', resetInactivityTimer, true); // Detecta desplazamiento

  // Iniciar el temporizador de inactividad al cargar la página
  resetInactivityTimer();
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    const remoteControl = document.querySelector('.remote-control');
    const sendSignalUrl = remoteControl.getAttribute('data-url-send-signal'); // URL para leer señales

    // Seleccionar todos los botones que tienen el atributo "data-id"
    const buttons = document.querySelectorAll('[data-id]');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const functionId = this.getAttribute('data-id'); // ID de la función
            const deviceId = document.getElementById('deviceId').value;
            const action_id = document.getElementById('actionId').value;
            const led = document.getElementById('led').value; // ID del dispositivo            // Llamar a la función que verifica continuamente el CSV
             // ID del dispositivo            // Llamar a la función que verifica continuamente el CSV
            waitForSignal(functionId, deviceId, action_id, led);
        });
    });

    // Función para verificar continuamente el CSV
    async function waitForSignal(functionId, deviceId, action_id, led) {
        try {
          const num=1;

            const response = await fetch(sendSignalUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ deviceId, functionId, action_id, num, led }),
            });

            if (response.status === 500) {
              alert('La señal no está grabada');
            } else if (response.status === 200) {
              const checkSignalUrl = '<?= base_url('/js/verificar_senal') ?>';
              let signalSent = false;

              while (!signalSent) {
                const checkResponse = await fetch(checkSignalUrl, {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                  body: new URLSearchParams({ action_id, deviceId, functionId }),
                });

                if (checkResponse.status === 200) {
                  signalSent = true;
                }
              }
            }
        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }
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