<?php
    $session=session();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo base_url("/img/logo1.png") ;?>">

    <link rel="stylesheet" href="<?php echo base_url("/css/form.css") . '?v=' . time(); ?>">

    <title>Verificación</title>
</head>
<body>

<div class="row">
<div class="col-md-12">
        
        <form action="<?php echo base_url("/verification");?>" method="post">
        <h1>Digite el codigo enviado a su direccion email</h1>
        <fieldset>
            <label for="code">Código de 6 dígitos</label>
            <input type="text" name="code" required id="code">
        </fieldset>
        <button type="submit">Verificar</button>
        </form>
    </div>
        </div>

</body>
</html>