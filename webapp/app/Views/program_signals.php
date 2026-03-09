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
    <link rel="stylesheet" href="<?php echo base_url("/css/form.css") . '?v=' . time(); ?>">
    <title>Programación de envío de señales</title>
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
    
      <div class="row">
      <div class="col-md-12">
        
    <form method="post" action="<?php echo base_url("/program_signals/insert"); ?>">
    <h1>Programación de envío de señales</h1>

            <fieldset>
                <label for="device">Seleccione el dispositivo que desee</label>
                <select name="device_type" required>
                <option value="" selected disabled style="color: gray;">Seleccione un dispositivo</option>

                    <?php foreach($devices as $device):?>
                    <option value="<?php echo $device['ID_dispositivo'];?>" data-led="<?php echo $device['led'];?>" device-type="<?php echo $device['ID_tipo'];?>"><?php echo $device['nombre'];?> </option>

                    <?php endforeach;?>
                </select>
            </fieldset>
            <fieldset>
    <label for="signals">Seleccione la señal que desea enviar</label>
    <select name="signal" id="signals" required>
        <!-- Este select se llenará dinámicamente -->
    </select>

<div id="hidden_fields"></div>
</fieldset>

            <fieldset>
                <label for="schedule_type">Seleccione el tipo de programación</label>
                <select name="schedule_type" id="schedule_type" required>
                <option value="" selected disabled style="color: gray;">Seleccione el tipo de programación</option>

                    <option value="unica_vez">Única vez</option>
                    <option value="periodica">Periódica</option>
                </select>
            </fieldset>

            <fieldset id="once_schedule" style="display: none;">
                <label for="once_date">Fecha</label>
                <input type="date" name="once_date" id="once_date">
                <label for="once_time">Hora</label>
                <input type="time" name="once_time" id="once_time">
            </fieldset>

            <fieldset id="recurring_schedule" style="display: none;">
    <label for="recurring_time">Hora</label>
    <input type="time" name="recurring_time" id="recurring_time">
    <label for="recurring_days">Días</label>
    <div id="recurring_days">
        <label><input type="checkbox" name="recurring_days[]" value="lunes"> Lunes</label>
        <label><input type="checkbox" name="recurring_days[]" value="martes"> Martes</label>
        <label><input type="checkbox" name="recurring_days[]" value="miercoles"> Miércoles</label>
        <label><input type="checkbox" name="recurring_days[]" value="jueves"> Jueves</label>
        <label><input type="checkbox" name="recurring_days[]" value="viernes"> Viernes</label>
        <label><input type="checkbox" name="recurring_days[]" value="sabado"> Sábado</label>
        <label><input type="checkbox" name="recurring_days[]" value="domingo"> Domingo</label>
    </div>
</fieldset>

            <script>
document.querySelector('select[name="device_type"]').addEventListener('change', function () {
    const deviceId = this.value;
    const deviceType = this.options[this.selectedIndex].getAttribute('device-type');
    const ledValue = this.options[this.selectedIndex].getAttribute('data-led');

    // Agrega el campo oculto para 'led'
    document.getElementById('hidden_fields').innerHTML = `<input type="hidden" name="led" value="${ledValue}">`;
    fetch(`<?php echo base_url(relativePath: "/get_signals"); ?>/${deviceId}/${deviceType}`)
        .then(response => response.json())
        .then(data => {
            const signalsSelect = document.getElementById('signals');
            signalsSelect.innerHTML = '';

            // Limpia los campos ocultos si ya existen

            data.forEach(signal => {
                // Crea una opción para el select
                const option = document.createElement('option');
                option.value = signal.ID_senal; // ID de la señal
                option.textContent = signal.nombre; // Nombre de la función
 // Bits
                signalsSelect.appendChild(option);
            });
        });
});

// Agrega un evento para actualizar los campos ocultos al seleccionar una señal


                document.getElementById('schedule_type').addEventListener('change', function() {
                    const onceSchedule = document.getElementById('once_schedule');
                    const recurringSchedule = document.getElementById('recurring_schedule');
                    if (this.value === 'unica_vez') {
                        onceSchedule.style.display = 'block';
                        recurringSchedule.style.display = 'none';
                    } else if (this.value === 'periodica') {
                        onceSchedule.style.display = 'none';
                        recurringSchedule.style.display = 'block';
                    } else {
                        onceSchedule.style.display = 'none';
                        recurringSchedule.style.display = 'none';
                    }
                });
            </script>

            <button type="submit">Registrar</button>

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