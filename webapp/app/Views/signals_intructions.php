<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url("/css/instructions.css") . '?v=' . time(); ?>">
    <link rel="icon" type="image/png" href="<?php echo base_url("/img/logo1.png") ;?>">
    
    <title>Instrucciones de señales</title>
</head>
<body>

<h2 style="color:black;">Sigue los siguientes pasos para grabar una nueva señal</h2>

<div class="acciones">
<a href="<?php echo base_url('/intrucciones_air');?>"><button class="button2">Ver instrucciones para aires acondicionados</button></a>
</div>

<ul>
    <b>
    <li>Selecciona el boton que deseas grabarle una señal 
    <img src="<?php echo base_url("/img/control.png" ); ?>" alt="Aplicacion"> <img src="<?php echo base_url("/img/controlventilador.png"); ?>" alt="Aplicacion">
    </li>
    <li>Le aparecera este mensaje donde solo debe tocar aceptar:
    <img src="<?php echo base_url("/img/mensajegrabar.png"); ?>" alt="Aplicacion">
    </li>
    <li>Apuntar el control remoto al receptor ubicado en la parte trasera de su IRConnect</li>
    <li>Apretar el boton del control remoto que selecciono anteriormente en la pagina </li>
    <li>Aguarde hasta ver un mensaje como este: 
    <img src="<?php echo base_url("/img/señalgrabada.png"); ?>" alt="Mensaje de exito">
    </li>
    <li>Luego de ver el mensaje puede volver tranquilamente al inicio</li>
    </b>

</ul>



</body>
</html>