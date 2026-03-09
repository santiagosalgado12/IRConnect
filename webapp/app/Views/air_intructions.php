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
<a href="<?php echo base_url('/intrucciones');?>"><button class="button2">Ver instrucciones para televisores y ventiladores</button></a>
</div>

<ul>
    <b>
    <li>Selecciona el boton crear configuracion, en caso de ya tener una configuracion creada pase al paso 3
    <img src="<?php echo base_url("/img/crearconfiguracion.png" ); ?>" alt="Aplicacion"> 
    </li>
    <li>Haga la configuracion que desee y toque el boton crear 
    <img src="<?php echo base_url("/img/configurar.png"); ?>" alt="Aplicacion" >
    </li>
    <li>Con la configuracion creada presione el boton grabar en la card de la configuracion 
    <img src="<?php echo base_url("/img/grabar.png"); ?>" alt="Aplicacion">
    </li>
    <li>Le aparecera este mensaje donde solo debe tocar aceptar:
    <img src="<?php echo base_url("/img/mensajegrabar.png"); ?>" alt="Aplicacion">
    </li>
    <li>Antes de grabar una señal, asegurece de que el control remoto esté configurado exactamente como se desea que quede grabada la señal (por ejemplo, temperatura en 24°C, modo "Swing" activado, etc.). Tener en cuenta que si usted para grabar la señal presiona el boton de subir temp, se grabara una configuracion con una temp distinta por eso si usted quiere 24° y tocara el boton subir temp para grabar la señal tenga en su control configurado una temp de 23°</li>
    <li>Apunte el control remoto al receptor ubicado en la parte trasera de su IRConnect y segidamente apriete algun boton,excepto el de encender ya que este grabara la señal de apagar </li>
    <li>Aguarde hasta ver un mensaje como este: 
    <img src="<?php echo base_url("/img/señalgrabada.png"); ?>" alt="Mensaje de exito">
    </li>
    <li>Luego de ver el mensaje puede volver tranquilamente al inicio</li>
    </b>

</ul>
 


</body>
</html>