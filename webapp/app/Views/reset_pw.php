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
    <title>Reestablecer contraseña</title>
</head>
<body>

<div class="row">
<div class="col-md-12">
    
    <form action="<?php echo base_url("/change"); ?>" method="post">
    <h1>Reestablecer contraseña</h1>

    <fieldset>
        <label for="password">Ingrese nueva contraseña</label>
        <div class="input-box">
            <input type="password" name="password" required id="password">
            <div class="tooltip-container">
                La contraseña debe tener al menos 8 caracteres, incluir al menos una letra mayúscula y un número
            </div>
        </div>
    </fieldset>

    <fieldset>
    <label for="pw-confirm">Confirme su contraseña</label>
    <input type="password" name="pw-confirm" required id="pw-confirm">
    </fieldset>
    <button type="submit">Reestablecer</button>

    </form>
    </div>
</div>

<style>

.tooltip-container {
    display: none;
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 0.95em;
    margin-top: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    width: 100%;
    box-sizing: border-box;
    position: relative;
    z-index: 2;
    /* Evita que se superponga sobre el input */
}

.input-box:focus-within .tooltip-container {
    display: block;
}

/* Responsive para pantallas pequeñas */
@media (max-width: 600px) {
    .tooltip-container {
        font-size: 0.88em;
        padding: 8px 8px;
        margin-top: 6px;
    }
}


  </style>

</body>
</html>