
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url("/css/form.css") . '?v=' . time(); ?>">
    <link rel="icon" type="image/png" href="<?php echo base_url("/img/logo1.png") ;?>">

    <title>Reestablecer contraseña</title>
</head>
<body>

    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>

    
   
    
    <div class="row">
<div class="col-md-12">
        
        
        <form method="post" action="<?php echo base_url("/generate2/recuperar_contrasena");?>">
        <h1>Reestablece tu contraseña</h1>

            <fieldset>
                <label for="mail">Ingrese el email asociado a su cuenta</label>
                <input type="email" name="mail" required id="mail">
            </fieldset>

          <button type="submit">Enviar</button>

        </form>

        </div>

        </div>


    
   
    
   

  

</body>
</html>