<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    asd











<script>
window.onpopstate = function(event) {
    // Redirigir a la nueva ruta
    window.location.href = "<?php echo base_url('/index.php/devices');?>";
};

// Para agregar una entrada al historial del navegador que no redirija inmediatamente
history.pushState(null, null, location.href);

</script>

</body>
</html>