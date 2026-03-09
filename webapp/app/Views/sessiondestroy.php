<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url("/css/form.css") . '?v=' . time(); ?>">
    <link rel="icon" type="image/png" href="<?php echo base_url("/img/logo1.png") ;?>">
    <title>Eliminar sesiones</title>
</head>
<body>
    

<div class="row">
<div class="col-md-12">
        
        
        <form method="post" action="<?php echo base_url("/sessiondestroy");?>">
        <h1>Elimina las sesiones de tu cuenta</h1>

        <fieldset>
            <label for="mail">Ingresa el E-mail asociado a tu cuenta</label>
            <input type="email" name="mail" id="mail" required>
        </fieldset>

        <button type="submit">Eliminar sesiones</button>

        </form>
</div>
</div>


</body>
</html>