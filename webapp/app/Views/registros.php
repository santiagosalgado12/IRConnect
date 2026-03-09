<?php
    $session = session();

    $permiso = $session->get("tipo");
?>  

<!DOCTYPE html>
<html lang="es">
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
  <link rel="stylesheet" href="<?php echo base_url("/css/userstyle.css") . '?v=' . time(); ?>">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
    <title>Registros del dispositivo seleccionado (Úlitmos 30 días)</title>
</head>
<body style="background-color: rgb(207, 226, 255); ">

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

 <div class="container mt-5 pt-5">
        <center><h1 class="mb-4">Registros del dispositivo (Úlitmos 30 días)</h1></center>
        
        <!-- Filtro por fecha y controles de paginación -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 acciones" id="acciones">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <label for="searchFecha" class="form-label mb-0 me-2">Buscar por fecha:</label>
                <input type="date" id="searchFecha" class="form-control" style="max-width: 300px;" >
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="registrosPorPagina" class="form-label mb-0 me-2">Mostrar:</label>
                <select id="registrosPorPagina" class="form-select" style="max-width: 80px;">
                    <option value="10">10</option>
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="mb-0">por página</span>
            </div>
        </div>

        <?php if (!empty($registros)): ?>
            <div class="table-responsive">
          <table class="table table-striped table-hover" id="tablaRegistros">
                    <thead>
                        <tr>
                            <th scope="col" style="background-color: #0357b1; color: white">Usuario</th>
                            <th scope="col" style="background-color: #0357b1; color:white">Función / COnfiguración</th>
                            <th scope="col" style="background-color: #0357b1; color:white">Fecha</th>
                        </tr>
                    </thead>
                    <tbody style="background-color white">
                        <?php foreach($registros as $r): ?>
                            <tr style="background-color: #0357b1;">
                                <td data-label="Nombre de Usuario" style="background-color: #0d83fd; color:white"><?php echo $r["nombre_usuario"]; ?></td>
                                <td data-label="Dirección de E-Mail" style="background-color: #0d83fd; color:white"><?php echo $r['funcion']!==null ? $r['funcion'] : $r['configuracion']; ?></td>
                                <td data-label="Fecha de Creación" style="background-color: #0d83fd; color:white"><?php echo $r["fecha"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Controles de paginación -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-3">
                <div class="text-muted">
                    <span id="infoRegistros">Mostrando 1 a 25 de 100 registros</span>
                </div>
                <nav aria-label="Paginación de registros">
                    <ul class="pagination pagination-sm mb-0 flex-wrap justify-content-center" id="paginacion">
                        <li class="page-item" id="btnAnterior">
                            <a class="page-link" href="#" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item active" id="pagina1">
                            <a class="page-link" href="#" data-pagina="1">1</a>
                        </li>
                        <li class="page-item" id="btnSiguiente">
                            <a class="page-link" href="#" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">No hay registros disponibles.</div>
        <?php endif; ?>
    </div>


    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>

<script>
    // Variables para la paginación
    let paginaActual = 1;
    let registrosPorPagina = 25;
    let registrosFiltrados = [];
    
    // Obtener todas las filas de la tabla
    const todasLasFilas = Array.from(document.querySelectorAll("#tablaRegistros tbody tr"));
    
    // Función para aplicar filtro de fecha
    function aplicarFiltroFecha() {
        const filtro = document.getElementById("searchFecha").value;
        
        if (filtro === "") {
            registrosFiltrados = todasLasFilas;
        } else {
            registrosFiltrados = todasLasFilas.filter(fila => {
                const fechaCelda = fila.children[2].innerText;
                const fechaParts = fechaCelda.split(' ');
                const fechaFormateada = fechaParts[0].split('/').reverse().join('-');
                return fechaFormateada === filtro;
            });
        }
        
        paginaActual = 1;
        mostrarPagina();
        actualizarPaginacion();
    }
    
    // Función para mostrar una página específica
    function mostrarPagina() {
        const inicio = (paginaActual - 1) * registrosPorPagina;
        const fin = inicio + registrosPorPagina;
        
        // Ocultar todas las filas
        todasLasFilas.forEach(fila => {
            fila.style.display = "none";
        });
        
        // Mostrar solo las filas de la página actual
        for (let i = inicio; i < fin && i < registrosFiltrados.length; i++) {
            registrosFiltrados[i].style.display = "";
        }
        
        actualizarInfoRegistros();
    }
    
    // Función para actualizar la información de registros
    function actualizarInfoRegistros() {
        const totalRegistros = registrosFiltrados.length;
        const inicio = totalRegistros === 0 ? 0 : (paginaActual - 1) * registrosPorPagina + 1;
        const fin = Math.min(paginaActual * registrosPorPagina, totalRegistros);
        
        document.getElementById("infoRegistros").textContent = 
            `Mostrando ${inicio} a ${fin} de ${totalRegistros} registros`;
    }
    
    // Función para actualizar los controles de paginación
    function actualizarPaginacion() {
        const totalPaginas = Math.ceil(registrosFiltrados.length / registrosPorPagina);
        const paginacion = document.getElementById("paginacion");
        
        // Limpiar paginación existente
        paginacion.innerHTML = "";
        
        // Botón anterior
        const btnAnterior = document.createElement("li");
        btnAnterior.className = `page-item ${paginaActual === 1 ? 'disabled' : ''}`;
        btnAnterior.innerHTML = `
            <a class="page-link" href="#" aria-label="Anterior">
                <span aria-hidden="true">&laquo;</span>
            </a>
        `;
        btnAnterior.addEventListener("click", (e) => {
            e.preventDefault();
            if (paginaActual > 1) {
                paginaActual--;
                mostrarPagina();
                actualizarPaginacion();
            }
        });
        paginacion.appendChild(btnAnterior);
        
        // Números de página
        const maxPaginasVisible = window.innerWidth < 576 ? 3 : 5;
        let inicioRango = Math.max(1, paginaActual - Math.floor(maxPaginasVisible / 2));
        let finRango = Math.min(totalPaginas, inicioRango + maxPaginasVisible - 1);
        
        // Ajustar el rango si estamos cerca del final
        if (finRango - inicioRango + 1 < maxPaginasVisible) {
            inicioRango = Math.max(1, finRango - maxPaginasVisible + 1);
        }
        
        for (let i = inicioRango; i <= finRango; i++) {
            const pagina = document.createElement("li");
            pagina.className = `page-item ${i === paginaActual ? 'active' : ''}`;
            pagina.innerHTML = `<a class="page-link" href="#" data-pagina="${i}">${i}</a>`;
            pagina.addEventListener("click", (e) => {
                e.preventDefault();
                paginaActual = i;
                mostrarPagina();
                actualizarPaginacion();
            });
            paginacion.appendChild(pagina);
        }
        
        // Botón siguiente
        const btnSiguiente = document.createElement("li");
        btnSiguiente.className = `page-item ${paginaActual === totalPaginas || totalPaginas === 0 ? 'disabled' : ''}`;
        btnSiguiente.innerHTML = `
            <a class="page-link" href="#" aria-label="Siguiente">
                <span aria-hidden="true">&raquo;</span>
            </a>
        `;
        btnSiguiente.addEventListener("click", (e) => {
            e.preventDefault();
            if (paginaActual < totalPaginas) {
                paginaActual++;
                mostrarPagina();
                actualizarPaginacion();
            }
        });
        paginacion.appendChild(btnSiguiente);
    }
    
    // Event listeners
    document.getElementById("searchFecha").addEventListener("input", aplicarFiltroFecha);
    
    document.getElementById("registrosPorPagina").addEventListener("change", function() {
        registrosPorPagina = parseInt(this.value);
        paginaActual = 1;
        mostrarPagina();
        actualizarPaginacion();
    });
    
    // Responsive: actualizar paginación cuando cambie el tamaño de ventana
    window.addEventListener("resize", actualizarPaginacion);
    
    // Inicializar
    document.addEventListener("DOMContentLoaded", function() {
        registrosFiltrados = todasLasFilas;
        mostrarPagina();
        actualizarPaginacion();
    });
</script>

</body>
</html>