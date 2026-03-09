<?php
    $session = session();

    $permiso = $session->get("tipo");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido <?php echo $session->get('username');?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url("/img/logo1.png") ;?>">
    <!-- CSS PARA NAV -->
    <link href="assets/img/logo1.png" rel="icon">
  <link href="assets/img/logo1.png" rel="apple-touch-icon">

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
  <!-- HASTA ACA. MODIFICAR LOS CSS DE C/ VISTA PARA QUE EL NAV QUEDE BIEN -->
    <link rel="stylesheet" href="<?php echo base_url("/css/style.css") . '?v=' . time(); ?>">
</head>
<body class="p-3 mb-2 bg-primary-subtle text-primary-emphasis">


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

            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
          </nav>
          <a class="btn-getstarted" href="#" onclick="handleCompraClick(event)">Compra aquí</a>

    
        </div>
      </header>
    


    
    <h1 style="margin-top: 80px;" class="titulos welcome-title">Bienvenido <span class="username-span"><?php echo $session->get("username");?></span></h1>

   <!-- Botón de control por voz -->
<div class="voice-control-container" style="text-align: center; margin: 20px 0;">
    <button id="voice-control-btn" class="button2" style="padding: 10px 20px;">
        <i class="bi bi-mic-fill"></i> Control por Voz
    </button>
    <div id="voice-feedback" style="display: none; margin-top: 10px;">
        <p>Escuchando... <i class="bi bi-mic"></i></p>
        <div class="progress" style="height: 5px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
        </div>
    </div>
    <div id="voice-result" class="mt-2 alert" style="display: none;"></div>
</div>

<?php
$success2=$session->getFlashdata("success");
if($success2!=null){
    echo "<center><div style='background-color: green; border: 1px solid green; padding: 10px; border-radius: 5px; height:70px; width: 220px; '>
    ✔ $success2
    </div></center>";
}

?>
<?php
$error=$session->getFlashdata("error");
if($error!=null){?>
    <center><h2>
    ❌ <?php echo $error;?></h2>
    </center>
<?php
  }
?>
    <?php if($permiso== 1):?>
      <div class="center-button-wrapper"><a href="<?php echo base_url("/new_esp"); ?>" class="button2">Añadir nuevo IRConnect</a></div><br><br>
    <?php endif;?>
    <?php if (isset($datos) && !empty($datos)): ?>
    <h1 class="titulos">Tus IRConnects disponibles</h1>
    <div class="mb-4" style="text-align: center;"> <!-- Centrar el input -->
    <input type="text" id="searchInput" class="form-control mx-auto" placeholder="Buscar por ubicación" 
           style="max-width: 100%; width: 35rem;"> <!-- Ajustar el ancho -->
</div>
    <ul id="espList">
        <?php foreach ($datos as $esp): ?>
            <li>
              <form action="<?php echo base_url("/esp32"); ?>" method="post">

                <input type="hidden" name="esp_id" value="<?php echo $esp["ID_dispositivo"];?> ">

                <input type="hidden" name="esp_ip" value="<?php echo $esp["direccion_ip"];?> ">

                <input type="hidden" name="esp_code" value="<?php echo $esp["codigo"];?> ">

                <button class="button2" type="submit" id="esp"> <?php  echo $esp["ubicacion"];?> </button>

              </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <h2>No hay IRConnects disponibles.</h2>
<?php endif; ?>


</main>
<script>
    // Filtrado en tiempo real para la lista de IRConnect
    const barraBusqueda = document.getElementById('searchInput');
    const listaESP = document.querySelectorAll('#espList li'); // Selecciona los elementos de la lista

    barraBusqueda.addEventListener('input', function() {
        const valorBusqueda = barraBusqueda.value.toLowerCase();

        listaESP.forEach(item => {
            const ubicacionESP = item.querySelector('button').innerText.toLowerCase(); // Obtiene el texto del botón (ubicación)

            // Mostrar u ocultar el elemento según el valor de búsqueda
            if (ubicacionESP.includes(valorBusqueda)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
<!-- SCRIPTS PARA NAV -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const voiceBtn = document.getElementById('voice-control-btn');
    const feedbackDiv = document.getElementById('voice-feedback');
    const resultDiv = document.getElementById('voice-result');
    
    // Verificar compatibilidad
    if (!('webkitSpeechRecognition' in window)) {
        voiceBtn.disabled = true;
        voiceBtn.innerHTML = '<i class="bi bi-mic-mute"></i> Voz no soportada';
        return;
    }
    
    voiceBtn.addEventListener('click', function() {
        feedbackDiv.style.display = 'block';
        resultDiv.style.display = 'none';
        
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.lang = 'es-ES';
        recognition.interimResults = false;
        
        recognition.onresult = function(event) {
            const command = event.results[0][0].transcript.toLowerCase();
            feedbackDiv.style.display = 'none';
            resultDiv.style.display = 'block';
            
            // Mapeo de comandos centralizado y simplificado
            const commandMap = {
                // Comandos para "Mi usuario"
                'mi usuario': '/userInfo',
                'ver mi usuario': '/userInfo',
                'mi perfil': '/userInfo',
                'mis datos': '/userInfo',
                'configurar usuario': '/userInfo',
                'editar perfil': '/userInfo',
                'información personal': '/userInfo',
                
                // Comandos para "Administrar usuarios" (solo para admin)
                <?php if($permiso==1): ?>
                'administrar usuarios': '/showUsers',
                'gestionar usuarios': '/showUsers',
                'lista de usuarios': '/showUsers',
                'control de usuarios': '/showUsers',
                'ver todos los usuarios': '/showUsers',
              
                // Comandos para "Añadir IRConnect"
                'añadir dispositivo': '/new_esp',
                'nuevo irconnect': '/new_esp',
                'vincular dispositivo': '/new_esp',
                'agregar nuevo': '/new_esp',
                'registrar dispositivo': '/new_esp',
                <?php endif; ?>
                
                // Comandos para encender dispositivos (centralizados)
                'encender': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'encender el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'encender la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'enciende': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'enciende el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'enciende la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'prender': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'prender el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'prender la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'prende': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'prende el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'prende la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'activar': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'activar el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'activar la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'activa': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'activa el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                'activa la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'encender' },
                
                // Comandos para apagar dispositivos (centralizados)
                'apagar': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'apagar el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'apagar la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'apaga': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'apaga el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'apaga la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'desactivar': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'desactivar el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'desactivar la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'desactiva': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'desactiva el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'desactiva la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'cerrar': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'cerrar el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'cerrar la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'cierra': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'cierra el': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                'cierra la': { endpoint: '/voice/sendsignal', function_id: 1, needsDeviceParams: true, signal_action: 'apagar' },
                
                // Comandos para subir volumen
                'subir volumen': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'subir el volumen': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'subir volumen del': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'subir volumen de la': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'subir el volumen del': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'subir el volumen de la': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'sube volumen': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'sube el volumen': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'sube volumen del': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'sube volumen de la': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'sube el volumen del': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'sube el volumen de la': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'aumentar volumen': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'aumentar el volumen': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'aumentar volumen del': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'aumentar volumen de la': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'aumentar el volumen del': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'aumentar el volumen de la': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'más volumen': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'más volumen del': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                'más volumen de la': { endpoint: '/voice/sendsignal', function_id: 10, needsDeviceParams: true, signal_action: 'subir_volumen' },
                
                // Comandos para bajar volumen  
                'bajar volumen': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'bajar el volumen': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'bajar volumen del': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'bajar volumen de la': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'bajar el volumen del': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'bajar el volumen de la': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'baja volumen': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'baja el volumen': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'baja volumen del': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'baja volumen de la': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'baja el volumen del': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'baja el volumen de la': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'disminuir volumen': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'disminuir el volumen': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'disminuir volumen del': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'disminuir volumen de la': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'disminuir el volumen del': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'disminuir el volumen de la': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'menos volumen': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'menos volumen del': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                'menos volumen de la': { endpoint: '/voice/sendsignal', function_id: 11, needsDeviceParams: true, signal_action: 'bajar_volumen' },
                
                // Comandos para cambiar velocidad (ventilador)
                'cambiar velocidad': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'cambiar la velocidad': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'cambiar velocidad del': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'cambiar velocidad de la': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'cambiar la velocidad del': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'cambiar la velocidad de la': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'velocidad': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'velocidad del': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'velocidad de la': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'regular velocidad': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'regular la velocidad': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'regular velocidad del': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'regular velocidad de la': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'regular la velocidad del': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                'regular la velocidad de la': { endpoint: '/voice/sendsignal', function_id: 30, needsDeviceParams: true, signal_action: 'velocidad' },
                
                // Comandos para "Cerrar sesión"
                'cerrar sesión': '/logout',
                'salir del sistema': '/logout',
                'desconectar': '/logout',
                'terminar sesión': '/logout'
            };
            
            // Función centralizada para extraer parámetros de cualquier dispositivo
            function extractDeviceParams(command, foundCommand) {
                // Remover el comando de acción del inicio
                let remainingText = command.replace(foundCommand, '').trim();
                
                let deviceName = '';
                let location = '';
                let temperatura = null;
                let modo = null;
                
                // Extraer temperatura (si está presente)
                const tempPatterns = [
                    /(?:a|en)\s+(\d+)\s*(?:grados?|°)/i,
                    /temperatura\s+(\d+)/i,
                    /(\d+)\s*grados?/i,
                    /(\d+)\s*°/i
                ];
                
                let tempText = '';
                for (let pattern of tempPatterns) {
                    const tempMatch = remainingText.match(pattern);
                    if (tempMatch) {
                        temperatura = parseInt(tempMatch[1]);
                        tempText = tempMatch[0];
                        break;
                    }
                }
                
                // Extraer modo (si está presente)
                const modePatterns = [
                    /(?:y\s+)?(?:en\s+)?modo\s+(frío|frio|calor|ventilación|ventilacion|automático|automatico|auto|heat|cool|fan|dry|seco)/i,
                    /(?:y\s+)?en\s+(frío|frio|calor|ventilación|ventilacion|automático|automatico|auto|heat|cool|fan|dry|seco)/i,
                    /(?:y\s+)?(frío|frio|calor|ventilación|ventilacion|automático|automatico|auto|heat|cool|fan|dry|seco)/i
                ];
                
                let modeText = '';
                for (let pattern of modePatterns) {
                    const modeMatch = remainingText.match(pattern);
                    if (modeMatch) {
                        modo = modeMatch[1].toLowerCase();
                        modeText = modeMatch[0];
                        // Normalizar modos a valores estándar
                        switch(modo) {
                            case 'frío':
                            case 'frio':
                            case 'cool':
                                modo = 'cool';
                                break;
                            case 'calor':
                            case 'heat':
                                modo = 'heat';
                                break;
                            case 'ventilación':
                            case 'ventilacion':
                            case 'fan':
                                modo = 'fan';
                                break;
                            case 'automático':
                            case 'automatico':
                            case 'auto':
                                modo = 'auto';
                                break;
                            case 'seco':
                            case 'dry':
                                modo = 'dry';
                                break;
                        }
                        break;
                    }
                }
                
                // Limpiar el texto de parámetros extraídos
                let cleanText = remainingText;
                if (tempText) cleanText = cleanText.replace(tempText, '').trim();
                if (modeText) cleanText = cleanText.replace(modeText, '').trim();
                
                // Limpiar conectores sobrantes (solo cuando son palabras independientes)
                cleanText = cleanText.replace(/\s+\b(y|en)\b\s+/gi, ' ').trim();
                cleanText = cleanText.replace(/^\b(y|en)\b\s+/gi, '').trim();
                cleanText = cleanText.replace(/\s+\b(y|en)\b$/gi, '').trim();
                cleanText = cleanText.replace(/\s+/g, ' ').trim();
                
                // Detectar si es un comando de volumen/velocidad que puede venir después del comando principal
                const isVolumeOrSpeedCommand = foundCommand.includes('volumen') || foundCommand.includes('velocidad');
                
                if (isVolumeOrSpeedCommand) {
                    // Para comandos como "subir volumen del televisor de mi pieza"
                    // Primero remover preposiciones iniciales
                    let processedText = cleanText.replace(/^(del?|de\s+la)\s+/i, '').trim();
                    
                    // Buscar patrones de ubicación más específicos
                    const locationIndicators = [
                        // Patrones con preposiciones explícitas
                        /^(.+?)\s+(del?|de\s+la)\s+(.+)$/i,
                        /^(.+?)\s+en\s+(el\s+|la\s+)?(.+)$/i,
                        /^(.+?)\s+de\s+(.+)$/i,
                        // Patrones con palabras clave de ubicación
                        /^(.+?)\s+(mi\s+\w+)$/i,
                        /^(.+?)\s+(la\s+\w+)$/i,
                        /^(.+?)\s+(el\s+\w+)$/i,
                        // Palabras de ubicación comunes
                        /^(.+?)\s+(sala|cocina|dormitorio|living|comedor|baño|pieza|habitación|cuarto)$/i
                    ];
                    
                    let found = false;
                    for (let pattern of locationIndicators) {
                        const match = processedText.match(pattern);
                        if (match) {
                            deviceName = match[1].trim();
                            if (match[3]) {
                                // Patrón con preposición (match[2] es la preposición)
                                location = match[3].trim();
                            } else {
                                // Patrón directo
                                location = match[2].trim();
                            }
                            found = true;
                            break;
                        }
                    }
                    
                    // Si no se encontró patrón de ubicación, todo es el dispositivo
                    if (!found && processedText) {
                        deviceName = processedText;
                        location = '';
                    }
                    
                } else {
                    // Para comandos tradicionales como "encender el televisor de la sala"
                    const locationPatterns = [
                        /(?:del?|de\s+la)\s+(.+)$/i,
                        /(?:en\s+(?:el|la)\s+)(.+)$/i,
                        /(?:ubicado|ubicada)\s+en\s+(.+)$/i
                    ];
                    
                    for (let pattern of locationPatterns) {
                        const locationMatch = cleanText.match(pattern);
                        if (locationMatch) {
                            location = locationMatch[1].trim();
                            deviceName = cleanText.replace(pattern, '').trim();
                            break;
                        }
                    }
                    
                    // Si no se encontró ubicación, todo el texto limpio es el nombre del dispositivo
                    if (!location && cleanText) {
                        deviceName = cleanText;
                    }
                }
                
                // Limpiar artículos del nombre del dispositivo
                deviceName = deviceName.replace(/^(el|la)\s+/i, '').trim();
                deviceName = deviceName.replace(/\s+(el|la)$/i, '').trim();
                
                // Limpiar ubicación
                location = location.replace(/^(el|la)\s+/i, '').trim();
                location = location.replace(/\s+(el|la)$/i, '').trim();
                
                return { deviceName, location, temperatura, modo };
            }
            
            // Buscar el comando más adecuado (busca el más específico primero)
            let commandFound = null;
            let commandData = null;
            
            // Buscar el comando que coincida, priorizando los más largos (más específicos)
            const sortedCommands = Object.keys(commandMap).sort((a, b) => b.length - a.length);
            for (let cmd of sortedCommands) {
                if (command.includes(cmd)) {
                    commandFound = cmd;
                    commandData = commandMap[commandFound];
                    break;
                }
            }
            
            if (commandFound && commandData) {
                // Si es un comando simple (string), redirigir directamente
                if (typeof commandData === 'string') {
                    resultDiv.innerHTML = `Redirigiendo a ${commandFound}...`;
                    resultDiv.className = 'alert alert-success';
                    setTimeout(() => {
                        window.location.href = "<?php echo base_url(); ?>" + commandData;
                    }, 800);
                } 
                // Si es un comando de dispositivo (objeto con needsDeviceParams)
                else if (commandData.needsDeviceParams) {
                    const { deviceName, location, temperatura, modo } = extractDeviceParams(command, commandFound);
                    
                    // Debug: Log extracted parameters
                    console.log('Voice Command Debug:', {
                        originalCommand: command,
                        foundCommand: commandFound,
                        extractedParams: { deviceName, location, temperatura, modo }
                    });
                    
                    // Validación básica: solo verificar que tenga nombre de dispositivo
                    if (!deviceName) {
                        resultDiv.innerHTML = `Por favor especifica el nombre del dispositivo. Ejemplo: "${commandFound} televisión de la sala" o "${commandFound} climatizador del dormitorio a 22 grados en frío"`;
                        resultDiv.className = 'alert alert-warning';
                        return;
                    }
                    
                    // Construir mensaje informativo
                    let messageText = `Enviando señal para ${commandData.signal_action} el dispositivo "${deviceName}"`;
                    messageText += `${location ? ` en ${location}` : ''}`;
                    if (temperatura) messageText += ` a ${temperatura}°`;
                    if (modo) messageText += ` en modo ${modo}`;
                    messageText += '...';
                    
                    resultDiv.innerHTML = messageText;
                    resultDiv.className = 'alert alert-info';
                    
                    // Preparar datos para enviar (siempre incluir todos los parámetros encontrados)
                    const postData = {
                        'device_name': deviceName,
                        'location': location,
                        'function_id': commandData.function_id,
                        'signal_action': commandData.signal_action
                    };
                    
                    // Incluir temperatura y modo si se encontraron
                    if (temperatura !== null) {
                        postData.temperatura = temperatura;
                    }
                    if (modo !== null) {
                        postData.modo = modo;
                    }
                    
                    // Enviar request usando fetch
                    fetch("<?php echo base_url(); ?>" + commandData.endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(postData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            resultDiv.innerHTML = `✅ ${data.message || 'Señal enviada correctamente'}`;
                            resultDiv.className = 'alert alert-success';
                        } else {
                            resultDiv.innerHTML = `❌ ${data.message || 'Error al enviar la señal'}`;
                            resultDiv.className = 'alert alert-danger';
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        resultDiv.innerHTML = `❌ Error de conexión: ${error.message}`;
                        resultDiv.className = 'alert alert-danger';
                    });
                }
            } else {
                resultDiv.innerHTML = `Comando no reconocido: "${command}"<br><br>Ejemplos de comandos válidos:<br>
                    <strong>Navegación:</strong><br>
                    - "Mi usuario" o "Mi perfil"<br>
                    <?php if($permiso==1): ?>
                    - "Administrar usuarios"<br>
                    - "Añadir dispositivo"<br>
                    <?php endif; ?>
                    - "Cerrar sesión"<br><br>
                    <strong>Dispositivos simples:</strong><br>
                    - "Encender televisión de la sala"<br>
                    - "Apagar ventilador del dormitorio"<br>
                    - "Encender TV del living"<br><br>
                    <strong>Aires acondicionados:</strong><br>
                    - "Encender aire de la cocina a 22 grados en frío"<br>
                    - "Prender aire del dormitorio a 24 grados en calor"<br>
                    - "Activar climatizador de la sala a 20 grados"<br>
                    - "Apagar aire de la cocina"`;
                resultDiv.className = 'alert alert-warning';
            }
        };
        
        recognition.onerror = function(event) {
            feedbackDiv.style.display = 'none';
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = 'Error en el reconocimiento: ' + 
                (event.error === 'no-speech' ? 'No se detectó voz' : 
                 event.error === 'audio-capture' ? 'No se pudo acceder al micrófono' : 
                 'Intenta nuevamente');
            resultDiv.className = 'alert alert-danger';
        };
        
        recognition.start();
    });
});
</script>
<style>
  /* Estilos para el control por voz */
#voice-control-btn {
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

#voice-control-btn.listening {
    background-color: #dc3545;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

#voice-result {
    max-width: 80%;
    margin: 10px auto;
    text-align: center;
}

/* Estilos para el modal personalizado */
.custom-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.custom-modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: none;
    border-radius: 10px;
    width: 80%;
    max-width: 500px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-buttons {
    margin-top: 20px;
}

.modal-btn {
    padding: 10px 20px;
    margin: 0 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s ease;
}

.modal-btn-yes {
    background-color: #28a745;
    color: white;
}

.modal-btn-yes:hover {
    background-color: #218838;
}

.modal-btn-no {
    background-color: #dc3545;
    color: white;
}

.modal-btn-no:hover {
    background-color: #c82333;
}

/* Estilos responsive para el título de bienvenida */
.welcome-title {
    word-wrap: break-word;
    word-break: break-word;
    hyphens: auto;
    line-height: 1.2;
    padding: 0 15px;
}

.username-span {
    display: inline-block;
    max-width: 100%;
    word-wrap: break-word;
    word-break: break-word;
    hyphens: auto;
}

/* Media queries para dispositivos móviles */
@media (max-width: 768px) {
    .welcome-title {
        font-size: 2.5rem !important;
        line-height: 1.3;
        text-align: center;
        padding: 0 10px;
        margin-bottom: 20px;
    }
    
    .username-span {
        display: block;
        margin-top: 5px;
        font-size: 2.3rem;
        color: #007bff;
        word-break: break-all;
    }
}

@media (max-width: 480px) {
    .welcome-title {
        font-size: 2.1rem !important;
        padding: 0 5px;
    }
    
    .username-span {
        font-size: 1.9rem;
        word-break: break-all;
        overflow-wrap: anywhere;
    }
}

@media (max-width: 320px) {
    .welcome-title {
        font-size: 1.8rem !important;
    }
    
    .username-span {
        font-size: 1.6rem;
    }
}

/* Wrapper para centrar el enlace-botón */
.center-button-wrapper {
    display: block !important;
    text-align: center !important;
    width: 100% !important;
    margin: 15px 0 !important;
}

/* El enlace se comporta exactamente como un botón */
a[href*="/new_esp"].button2 {
    display: inline-block !important;
    text-decoration: none !important;
    margin: 0 !important;
    color: white !important;
}

/* Mantener el texto blanco en hover, focus y visited */
a[href*="/new_esp"].button2:hover,
a[href*="/new_esp"].button2:focus,
a[href*="/new_esp"].button2:active,
a[href*="/new_esp"].button2:visited {
    color: white !important;
    text-decoration: none !important;
}
</style>

<!-- Modal personalizado -->
<div id="compraModal" class="custom-modal">
    <div class="custom-modal-content">
        <h4 style="color: #dc3545; margin-bottom: 15px;">⚠️ Aviso Importante</h4>
        <p style="margin-bottom: 15px;">
            Si compras un IRConnect tendrás que crearte otro usuario administrador registrándolo por tu cuenta, 
            no podrás vincularlo directamente a este usuario ya que no tienes permiso.
        </p>
        <p style="font-weight: bold; margin-bottom: 20px;">¿Deseas continuar?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-yes" onclick="confirmarCompra()">Sí</button>
            <button class="modal-btn modal-btn-no" onclick="cerrarModal()">No</button>
        </div>
    </div>
</div>

<script>
function handleCompraClick(event) {
    event.preventDefault();
    
    const permiso = <?php echo $permiso; ?>;
    const compraUrl = "<?php echo base_url('/comprar'); ?>";
    
    if (permiso === 1) {
        // Si tiene permiso de administrador, abrir directamente en nueva pestaña
        window.open(compraUrl, '_blank');
    } else {
        // Si no tiene permiso, mostrar modal de confirmación
        document.getElementById('compraModal').style.display = 'block';
    }
}

function confirmarCompra() {
    const compraUrl = "<?php echo base_url('/comprar'); ?>";
    window.open(compraUrl, '_blank');
    cerrarModal();
}

function cerrarModal() {
    document.getElementById('compraModal').style.display = 'none';
}

// Cerrar modal al hacer click fuera de él
window.onclick = function(event) {
    const modal = document.getElementById('compraModal');
    if (event.target === modal) {
        cerrarModal();
    }
}

// Cerrar modal con la tecla Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        cerrarModal();
    }
});
</script>

</body>
</html>