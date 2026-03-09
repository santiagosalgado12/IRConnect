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
    <title>Grabar señales</title>
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
<input type="hidden" id="deviceId" value="<?php echo $id;?>" />
     <input type="hidden" id="actionId" value="<?php echo session()->get('action_id');?>" /> <!-- Reemplaza 12345 con el ID real del dispositivo -->
 
     <input type="hidden" id="deleteAction" value="<?php echo base_url('/front/eliminar_accion') ?>" />
 <!-- Reemplaza 12345 con el ID real del dispositivo -->
     <div class="remote-control" data-url-receive-code="<?= base_url('/air/enviar_senal') ?>" 
     data-url-save-signal="<?= base_url('/air/insertar_senal'); ?>" data-url-verify-signal="<?= base_url('/js/verificar_grabacion');?>"
     data-url-verify-record="<?= base_url('/air/verificar_grabacion'); ?>"></div>



    <h1>Configuraciones del aire seleccionado</h1>
    <?php if($permiso==1):?>
      <div class="acciones" style="margin-bottom: 20px; margin-top: 20px;">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style=" font-size: 1.75rem;">
        Crear configuración
      </button>      
      

        
    </div>
    </div>
    <center><button class="btn btn-primary" onclick="abrirEnOtraPestania()" style=" font-size: 1.75rem; align-items: center; margin-top: 20px; margin-bottom: 20px;">Instrucciones</button></center>
    <?php endif;?>
        <center>
          <button type="button" class="button2" data-id="0" style=" font-size: 1.75rem; margin-bottom: 20px; margin-top: 20px;">
        Apagar
      </button> 
      </center>
<?php
if(!empty($config)):?>
    
    <div class="container">
        <div class="row justify-content-center">
            <?php 
            $contador=1;
            foreach($config as $c):?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Configuración <?php echo $contador;?></h3>
                        <p class="card-text"><b>Temperatura:</b> <?php echo $c['temperatura'];?> °C</p>
                        <p class="card-text"><b>Swing:</b>  <?php echo $c['swing'];?></p>
                        <p class="card-text"><b>Modo:</b>  <?php echo $c['modo'];?></p>
                        <p class="card-text"><b>Fan speed:</b>  <?php echo $c['fanspeed'];?></p>
                        <button class="button2" data-id="<?php echo $c['ID_configuracion'];?>" >Grabar</button>
                        <?php if($permiso==1):?>
                        <button class="button2" onclick="deleteSignal('<?php echo base_url('/deleteConfig/'.$c['ID_senal']);?>')">Eliminar</button>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <?php $contador+=1; endforeach; else:?>
        </div>
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
h1{
  text-align: center;
  margin-top: 2rem ;
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
    background-color: #0d83fd;
    font-size: 18px;
    text-align: center;
    font-style: normal;
    border-radius: 5px;
    width: 100%;
    border: 1px solid #0d83fd;
    border-width: 1px 1px 3px;
    box-shadow: 0 -1px 0 rgba(255,255,255,0.1) inset;
    margin-bottom: 10px;
  }

  form button:hover {
    background-color: #0369d6;
    border: 1px solid #0369d6;
    border-width: 1px 1px 3px;
    box-shadow: 0 -1px 0 rgba(255,255,255,0.1) inset;
    cursor: pointer;
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
  .acciones {
    display: flex;
    justify-content: center;
    font-size: 100px;
}
.button {
    margin: 2px;
    width: 9em;
    height: 3em;
    border-radius: 30em;
    font-size: 20px;
    font-family: inherit;
    border: none;
    position: relative;
    overflow: hidden;
    z-index: 1;
    color: #FFFFFF; /* Blanco para el texto */
    background: #0d83fd; /* Azul marino oscuro */
    box-shadow: 1px 1px 4px #c5c5c5, 0px 0px 3px #ffffff;
}
.button::before {
    content: '';
    width: 0;
    height: 3em;
    border-radius: 30em;
    position: absolute;
    top: 0;
    left: 0;
    background-image: linear-gradient(to right, #3e8ee4 20%, #0369d6 100%); /* Azul profundo a azul petróleo */
    transition: .5s ease;
    display: block;
    z-index: -1;
}
  
  .button:hover::before {
   width: 9em;
  }
</style>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Crear configuración</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createConfigForm" method="post">
                    <h2>Crear Configuración</h2>
                      <input type="hidden" value="<?php echo $id;?>" name="id">
                      <fieldset>
                        <label for="temperatura" class="form-label">Temperatura (en °C)</label>
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
<script>
 const myModal = document.getElementById('exampleModal') // ✅
const myInput = document.getElementById('myInput')

myModal.addEventListener('shown.bs.modal', () => {
  myInput.focus();
 
})

document.getElementById("createConfigForm").addEventListener("submit", function(event) {
  event.preventDefault();

  const formData = new FormData(this);

  fetch('<?php echo base_url('/crear_config');?>', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert(data.success);
          window.onbeforeunload = null;
          location.reload();
      } else if (data.error) {
          alert(data.error);
      }
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
    const receiveCodeUrl = remoteControl.getAttribute('data-url-receive-code'); // URL para leer señales
    const saveSignalUrl = remoteControl.getAttribute('data-url-save-signal');
    const verifySignalUrl = remoteControl.getAttribute('data-url-verify-signal'); // URL para guardar señal
    const verifyRecordUrl = remoteControl.getAttribute('data-url-verify-record'); // URL para verificar si la señal ya está grabada

    const buttons = document.querySelectorAll('[data-id]');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const configId = this.getAttribute('data-id'); // ID de la función
            const deviceId = document.getElementById('deviceId').value; // ID del dispositivo
            const action_id = document.getElementById('actionId').value;

            // Mostrar mensaje de espera
            alert('Pulse "Aceptar" y luego pulse el botón de su control original apuntando hacia el receptor de su IRConnect. Tenga en cuenta que tiene 3 minutos para grabar la señal.');

            // Llamar a la función que verifica continuamente el CSV
            waitForSignal(configId, deviceId, action_id);
        });
    });

    // Función para verificar continuamente el CSV
    async function waitForSignal(configId, deviceId, action_id) {
        try {

            const num=2;

            const response = await fetch(receiveCodeUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ deviceId, configId, action_id, num }),
            });

            if (response.status !== 200) {
                throw new Error('Error al verificar la señal.');
            }

            let irCode=null;
            let protocolo=null;
            let bits=null;

            while (irCode === null){
              const verifyResponse =await fetch(verifySignalUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action_id, configId }),
                });
                if(verifyResponse.status === 400){
                    alert('La señal no fue leída correctamente. Es posible que se haya agotado el tiempo de espera de 3 minutos o que haya abandonado la pagina. Intentelo nuevamente.');
                    return;
                }
              if(verifyResponse.status === 200){
                const verifyData = await verifyResponse.json();
                if (verifyData.hexadecimal) {
                  protocolo= verifyData.protocolo;
                  bits= verifyData.bits;
                  irCode = verifyData.hexadecimal;
                }
              }
            }
                  const verifyRecord =await fetch(verifyRecordUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ deviceId, configId }),
                });

                if (verifyRecord.status === 200) { 
            // Mostrar un confirm al usuario
                    const userConfirmed = confirm("Esta señal ya está grabada, ¿deseas sobreescribirla?");
            // Si el usuario confirma
                    if (userConfirmed) {
                        const saveResponse = await fetch(saveSignalUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ irCode, protocolo, bits, deviceId, configId }),
                        });

                        alert(`Señal actualizada correctamente`);
                    }else{
                      alert('la señal no sera sobreescrita');
                    }
                }else{
                    const saveResponse = await fetch(saveSignalUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ irCode, protocolo, bits, deviceId, configId }),
                    });

                    alert(`Señal grabada correctamente`);
                    
                }
                }        catch (error) {
            console.error(error);
            alert(error.message);
        }
              }
              

            
                
            } 
 
     // Aquí se cierra la función waitForSignal

);

</script>
<script>
        function abrirEnOtraPestania() {
  window.open('https://irconnect.site/intrucciones_air', '_blank');
}
    </script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>

