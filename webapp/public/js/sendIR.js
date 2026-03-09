function enviarIR(signal, ip) {
    // Crea un cuerpo de la solicitud con las dos señales
    const data = new URLSearchParams();
    data.append('signal', signal);
    data.append('ip', ip);

    // Realiza la solicitud a la ruta del controlador
    fetch('http://localhost/pagina_web/pagina_web/public/sendIR', {
        method: 'POST',
        //Se configura el encabezado Content-Type de la siguiente manera. Es un metodo de codificacion de datos usado en solicitudes POST
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: data
    })
    
}
