<?php
    $session = session();

    $permiso = $session->get("tipo");
?>
<!DOCTYPE html>
<html lang="es">
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
    
    
    <title>Control Remoto Aire acondicionado</title>
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

<input type="hidden" id="deviceId" value="<?php echo $id;?>" />
    <input type="hidden" id="actionId" value="<?php echo session()->get('action_id');?>" /> <!-- Reemplaza 12345 con el ID real del dispositivo -->

    <input type="hidden" id="deleteAction" value="<?php echo base_url('/front/eliminar_accion') ?>" />

    <div class="remote-control" data-url-send-signal="<?= base_url('/air/enviar_senal') ?>"></div>

    <h1 style="padding-top: 3rem;">Configuraciones del aire seleccionado</h1>
    <?php if($permiso==1):?>
      <div class="acciones">
        <center><button class="button2" type="button" onclick="openForm()">Crear configuración</button></center>
      </div>
    <?php endif;?>
    
<?php
if(!empty($config)):?>
    
    <div class="contenedores" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; width: 60rem; margin: auto;">
    <?php 
    $contador=1;
    foreach($config as $c):?>
    
    <div class="card" style="width: 18rem; margin-top:40px;">
        <div class="card-body">
            <h3 class="card-title">Configuración <?php echo $contador;?></h3>
            <p class="card-text"><b>Temperatura:</b> <?php echo $c['temperatura'];?> °C</p>
            <p class="card-text"><b>Swing:</b>  <?php echo $c['swing'];?></p>
            <p class="card-text"><b>Modo:</b>  <?php echo $c['modo'];?></p>
            <p class="card-text"><b>Fan speed:</b>  <?php echo $c['fanspeed'];?></p>
            <button class="button2" data-id="<?php echo $c['ID_configuracion'];?>" >Emitir</button>
            <?php if($permiso==1):?>
              <button class="button2" onclick="deleteSignal('<?php echo base_url('/deleteConfig/'.$c['ID_senal']);?>')">Eliminar</button>

            <?php endif;?>
        </div>
    </div>

    <?php $contador+=1; endforeach; else:?>
</div>


<h1 style="padding-top: 50px;">No hay configuraciones creadas</h1>

<?php endif;?>

<style>
  body {
    height: 100vh;
    background-color: rgb(207, 226, 255); /* Azul marino a azul petróleo */
    color:rgb(0, 0, 0); /* Gris carbón para el texto */
    padding-top: 80px;
}
.card {
    width: 18rem;
    margin-top: 40px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}
.card-text{
  font-size: 20px;
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
.acciones button {
    font-size: 18px;
    padding: 15px 30px;
  }
  h1{
    text-align: center;
    margin-bottom: 37px;
    color: black;
    
    
  }

  /* Estilos para el formulario emergente */
 /* Estilos para el modal - SOLO CAJAS */
 .modal {
    display: none; 
    position: fixed; 
    z-index: 1000; /* Aseguramos que esté por encima de todo */
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgba(0, 0, 0, 0.4); 
    padding-top: 20px; /* Reducimos el padding superior */
    box-sizing: border-box;
  }

  .modal-content {
    background-color: #fefefe;
    margin: auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 90%; /* Más ancho en móviles */
    max-width: 500px; /* Máximo ancho en pantallas grandes */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: relative;
    top: 50%; /* Centrado vertical */
    transform: translateY(-50%); /* Centrado vertical */
  }

  .close {
    position: absolute;
    right: 15px;
    top: 10px;
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .close:hover {
    color: black;
  }

  /* Ajustes para móviles en orientación vertical */
  @media (max-width: 576px) and (orientation: portrait) {
    .card {
    max-width: 100%;
}
    .modal-content {
      width: 95%; /* Ocupa casi todo el ancho */
      padding: 15px;
      top: 10px; /* Posición más alta */
      transform: none; /* Eliminamos transform para móviles */
      margin: 10px auto; /* Margen reducido */
      max-height: 90vh; /* Altura máxima */
      overflow-y: auto; /* Scroll si es necesario */
    }
    
    .modal {
      padding-top: 10px; /* Menos espacio arriba */
    }
  }
  form {
    max-width: 300px;
    margin: 180px auto;
    padding: 10px 20px;
    background: #f4f7f8;
    border-radius: 8px;
  }
  
  input[type="text"],
  input[type="password"],
  input[type="date"],
  input[type="datetime"],
  input[type="email"],
  input[type="number"],
  input[type="search"],
  input[type="tel"],
  input[type="time"],
  input[type="url"],
  textarea,
  select {
    background: rgba(255,255,255,0.1);
    border: none;
    font-size: 16px;
    height: auto;
    margin: 0;
    outline: 0;
    padding: 15px;
    width: 100%;
    background-color: #e8eeef;
    color: #8a97a0;
    box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
    margin-bottom: 30px;
  }
  
  input[type="radio"],
  input[type="checkbox"] {
    margin: 0 4px 8px 0;
  }
  
  select {
    padding: 6px;
    height: 32px;
    border-radius: 2px;
  }
  
  form button {
    padding: 19px 39px 18px 39px;
    color: #FFF;
    background-color: #4bc970;
    font-size: 18px;
    text-align: center;
    font-style: normal;
    border-radius: 5px;
    width: 100%;
    border: 1px solid #3ac162;
    border-width: 1px 1px 3px;
    box-shadow: 0 -1px 0 rgba(255,255,255,0.1) inset;
    margin-bottom: 10px;
  }
  
  fieldset {
    margin-bottom: 30px;
    border: none;
  }
  
  legend {
    font-size: 1.4em;
    margin-bottom: 10px;
  }
  
  label {
    display: block;
    margin-bottom: 8px;
  }
  
  label.light {
    font-weight: 300;
    display: inline;
  }
  
  .number {
    background-color: #5fcf80;
    color: #fff;
    height: 30px;
    width: 30px;
    display: inline-block;
    font-size: 0.8em;
    margin-right: 4px;
    line-height: 30px;
    text-align: center;
    text-shadow: 0 1px 0 rgba(255,255,255,0.2);
    border-radius: 100%;
  }
  
  @media screen and (min-width: 480px) {
  
    form {
      max-width: 480px;
    }
  
  }
</style>

<!-- Modal -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="row">
    <div class="col-md-12">
    <form id="createConfigForm" >
    <h2>Crear Configuración</h2>
      <input type="hidden" value="<?php echo $id;?>" name="id">
      <fieldset>
        <label for="temperatura" class="form-label">Temperatura</label>
        <input type="number" class="form-control" id="temperatura" name="temperatura" min="0" max="255" required>
      </fieldset>
      <fieldset>
        <label for="swing" class="form-label">Swing</label>
        <select class="form-control" id="swing" name="swing" required>
          <option value="auto">Auto</option>
          <option value="on">On</option>
          <option value="off">Off</option>
        </select>
      </fieldset>
      <fieldset>
        <label for="modo" class="form-label">Modo</label>
        <select class="form-control" id="modo" name="modo" required>
          <option value="cool">Cool</option>
          <option value="heat">Heat</option>
          <option value="fan">Fan</option>
          <option value="dry">Dry</option>
          <option value="auto">Auto</option>
        </select>
      </fieldset>
      <fieldset>
        <label for="fanspeed" class="form-label">Fan Speed</label>
        <select class="form-control" id="fanspeed" name="fanspeed" required>
          <option value="auto">Auto</option>
          <option value="low">Low</option>
          <option value="mid">Mid</option>
          <option value="high">High</option>
        </select>
      </fieldset>
      <button type="submit">Crear</button>
    </form>
    </div>
    </div>
  </div>
</div>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>
<script>
  // Obtener el modal
  var modal = document.getElementById("myModal");

  // Obtener el botón que abre el modal
  var btn = document.getElementsByClassName("button2")[0];

  // Obtener el elemento <span> que cierra el modal
  var span = document.getElementsByClassName("close")[0];

  // Cuando el usuario hace clic en el botón, abre el modal 
  function openForm() {
    modal.style.display = "block";
  }

  // Cuando el usuario hace clic en <span> (x), cierra el modal
  span.onclick = function() {
    modal.style.display = "none";
  }

  // Cuando el usuario hace clic en cualquier lugar fuera del modal, lo cierra
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }

  // Manejo del formulario de creación de configuración
  document.getElementById("createConfigForm").addEventListener("submit", function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    fetch('<?php echo base_url('/crear_config');?>', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      // Manejar la respuesta del servidor
      if (data.success) {
        alert(data.success);
        window.onbeforeunload = null;
        location.reload();
      } else if (data.error) {
        alert(data.error);
      }
      modal.style.display = "none";
      // Actualizar la vista con la nueva configuración si es necesario
    })
    .catch(error => {
      console.error("Error:", error);
    });
  });

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
  window.onkeypress = resetInactivityTimer; //
  window.addEventListener('scroll', resetInactivityTimer, true); // Detecta desplazamiento

  // Iniciar el temporizador de inactividad al cargar la página
  resetInactivityTimer();

  function deleteSignal(url) {
    if (confirm("¿Estás seguro de que deseas eliminar esta configuración?")) {
        fetch(url, { method: "GET" }) // Se ejecuta la ruta sin recargar la página
            .then(response => response.json()) // Espera una respuesta JSON
            .then(data => {
                if (data.success) {
                    alert("Configuración eliminada correctamente. Actualiza la página para ver los cambios");
                    // Opcionalmente, actualizar la vista sin recargar
                    document.getElementById("fila_" + data.id).remove();
                    window.onbeforeunload = null;
                    location.reload();

                } 
            })
            .catch(error => {
                console.error("Error:", error);
              
            });
    }
}


</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const remoteControl = document.querySelector('.remote-control');
    const sendSignalUrl = remoteControl.getAttribute('data-url-send-signal'); // URL para leer señales

    // Seleccionar todos los botones que tienen el atributo "data-id"
    const buttons = document.querySelectorAll('[data-id]');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const configId = this.getAttribute('data-id'); // ID de la función
            const deviceId = document.getElementById('deviceId').value;
            const action_id = document.getElementById('actionId').value; // ID del dispositivo            // Llamar a la función que verifica continuamente el CSV
            waitForSignal(configId, deviceId, action_id);
        });
    });

    // Función para verificar continuamente el CSV
    async function waitForSignal(configId, deviceId, action_id) {
        try {
          const num=1;

            const response = await fetch(sendSignalUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ deviceId, configId, action_id, num }),
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
                  body: new URLSearchParams({ action_id }),
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
