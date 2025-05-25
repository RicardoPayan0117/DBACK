<?php
require_once 'conexion.php';

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Procesar formulario si se envió
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar y limpiar datos
    $cliente_id = intval($_POST['cliente_id']);
    $fecha_servicio = $conn->real_escape_string($_POST['fecha_servicio']);
    $origen = $conn->real_escape_string($_POST['origen']);
    $destino = $conn->real_escape_string($_POST['destino']);
    $distancia = floatval($_POST['distancia']);
    $tipo_servicio = $conn->real_escape_string($_POST['tipo_servicio']);
    $urgencia = $conn->real_escape_string($_POST['urgencia']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    
    // Calcular costo basado en distancia y tipo de servicio
    $costo = calcularCosto($distancia, $tipo_servicio, $urgencia);
    
    // Insertar solicitud
    $query = "INSERT INTO solicitudes (cliente_id, fecha_servicio, origen, destino, distancia, tipo_servicio, urgencia, estado, costo, descripcion) 
              VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente', ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssdssds", $cliente_id, $fecha_servicio, $origen, $destino, $distancia, $tipo_servicio, $urgencia, $costo, $descripcion);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Solicitud creada correctamente";
        $_SESSION['tipo_mensaje'] = "exito";
        header("Location: panel-solicitud.php");
        exit();
    } else {
        $error = "Error al crear la solicitud: " . $conn->error;
    }
}

// Función para calcular costo (simplificada)
function calcularCosto($distancia, $tipo_servicio, $urgencia) {
    $costo_base = 500; // Costo base en pesos
    
    // Ajustar por distancia
    $costo = $costo_base + ($distancia * 50);
    
    // Ajustar por tipo de servicio
    switch ($tipo_servicio) {
        case 'remolque':
            $costo *= 1.5;
            break;
        case 'arranque':
            $costo *= 1.2;
            break;
        case 'cambio_llanta':
            $costo += 200;
            break;
    }
    
    // Ajustar por urgencia
    switch ($urgencia) {
        case 'urgente':
            $costo *= 1.3;
            break;
        case 'emergencia':
            $costo *= 1.5;
            break;
    }
    
    return $costo;
}

// Obtener lista de clientes
$query = "SELECT id, nombre, telefono FROM clientes ORDER BY nombre";
$clientes_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nueva Solicitud - Grúas DBACK</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./CSS/panel-solicitud.CSS">
</head>
<body>
  <!-- Barra lateral -->
  <nav class="sidebar" id="sidebar" aria-label="Menú principal">
    <div class="sidebar_header">
      <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK">
      <span class="sidebar_text">Grúas DBACK</span>
    </div>

    <ul class="sidebar_list" role="menubar">
      <li class="sidebar_element" role="menuitem">
        <a href="MenuAdmin.PHP" class="sidebar_link">
          <i class="bi bi-house sidebar_icon"></i>
          <span class="sidebar_text">Inicio</span>
        </a>
      </li>
      
      <li class="sidebar_element" role="menuitem">
        <a href="Gruas.php" class="sidebar_link">
          <i class="bi bi-truck sidebar_icon"></i>
          <span class="sidebar_text">Grúas</span>
        </a>
      </li>
      
      <li class="sidebar_element" role="menuitem">
        <a href="Gastos.php" class="sidebar_link">
          <i class="bi bi-cash-coin sidebar_icon"></i>
          <span class="sidebar_text">Gastos</span>
        </a>
      </li>
      
      <li class="sidebar_element" role="menuitem">
        <a href="Empleados.php" class="sidebar_link">
          <i class="bi bi-people sidebar_icon"></i>
          <span class="sidebar_text">Empleados</span>
        </a>
      </li>

      <li class="sidebar_element active" role="menuitem" aria-current="page">
        <a href="panel-solicitud.php" class="sidebar_link">
          <i class="bi bi-clipboard2-check sidebar_icon"></i>
          <span class="sidebar_text">Panel de Solicitud</span>
        </a>
      </li>
    </ul>

    <div class="sidebar_footer">
      <div class="sidebar_element" role="contentinfo">
        <i class="bi bi-person-circle sidebar_icon"></i>
        <div class="sidebar_user-info">
          <div class="sidebar_text sidebar_title"><?php echo $_SESSION['usuario_nombre']; ?></div>
          <div class="sidebar_text sidebar_info"><?php echo ucfirst($_SESSION['usuario_rol']); ?></div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Contenido principal -->
  <main class="main-content">
    <div class="container-fluid">
      <!-- Encabezado -->
      <header class="py-4">
        <div class="d-flex align-items-center">
          <a href="panel-solicitud.php" class="btn btn-outline-primary me-3">
            <i class="bi bi-arrow-left"></i> Volver
          </a>
          <h1 class="h2 mb-0"><i class="bi bi-plus-circle me-2"></i> Nueva Solicitud</h1>
        </div>
      </header>

      <!-- Formulario -->
      <section class="card" aria-labelledby="formulario-heading">
        <div class="card-header bg-primary text-white">
          <h2 class="h5 mb-0" id="formulario-heading"><i class="bi bi-clipboard-plus"></i> Datos de la Solicitud</h2>
        </div>
        <div class="card-body">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>
          
          <form method="POST" action="">
            <div class="row g-3">
              <!-- Selección de cliente -->
              <div class="col-md-6">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select class="form-select" id="cliente_id" name="cliente_id" required>
                  <option value="">Seleccionar cliente...</option>
                  <?php while ($cliente = $clientes_result->fetch_assoc()): ?>
                    <option value="<?php echo $cliente['id']; ?>">
                      <?php echo htmlspecialchars($cliente['nombre']); ?> (<?php echo htmlspecialchars($cliente['telefono']); ?>)
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
              
              <!-- Fecha y hora del servicio -->
              <div class="col-md-6">
                <label for="fecha_servicio" class="form-label">Fecha y hora del servicio</label>
                <input type="datetime-local" class="form-control" id="fecha_servicio" name="fecha_servicio" required>
              </div>
              
              <!-- Ubicación de origen -->
              <div class="col-md-6">
                <label for="origen" class="form-label">Ubicación de origen</label>
                <input type="text" class="form-control" id="origen" name="origen" required>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="obtenerUbicacion('origen')">
                  <i class="bi bi-geo-alt"></i> Obtener ubicación actual
                </button>
              </div>
              
              <!-- Ubicación de destino -->
              <div class="col-md-6">
                <label for="destino" class="form-label">Ubicación de destino</label>
                <input type="text" class="form-control" id="destino" name="destino" required>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="obtenerUbicacion('destino')">
                  <i class="bi bi-geo-alt"></i> Obtener ubicación actual
                </button>
              </div>
              
              <!-- Distancia -->
              <div class="col-md-4">
                <label for="distancia" class="form-label">Distancia (km)</label>
                <input type="number" class="form-control" id="distancia" name="distancia" step="0.01" min="0" required>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="calcularDistancia()">
                  <i class="bi bi-calculator"></i> Calcular distancia
                </button>
              </div>
              
              <!-- Tipo de servicio -->
              <div class="col-md-4">
                <label for="tipo_servicio" class="form-label">Tipo de servicio</label>
                <select class="form-select" id="tipo_servicio" name="tipo_servicio" required>
                  <option value="">Seleccionar...</option>
                  <option value="remolque">Remolque</option>
                  <option value="arranque">Servicio de arranque</option>
                  <option value="cambio_llanta">Cambio de llanta</option>
                  <option value="suministro_gasolina">Suministro de gasolina</option>
                  <option value="otro">Otro servicio</option>
                </select>
              </div>
              
              <!-- Nivel de urgencia -->
              <div class="col-md-4">
                <label for="urgencia" class="form-label">Nivel de urgencia</label>
                <select class="form-select" id="urgencia" name="urgencia" required>
                  <option value="normal">Normal</option>
                  <option value="urgente">Urgente</option>
                  <option value="emergencia">Emergencia</option>
                </select>
              </div>
              
              <!-- Descripción -->
              <div class="col-12">
                <label for="descripcion" class="form-label">Descripción del problema</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
              </div>
              
              <!-- Resumen y costo estimado -->
              <div class="col-12 mt-4">
                <div class="card">
                  <div class="card-header bg-light">
                    <h3 class="h6 mb-0"><i class="bi bi-calculator"></i> Resumen y costo estimado</h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <p><strong>Distancia:</strong> <span id="resumen-distancia">0</span> km</p>
                        <p><strong>Tipo de servicio:</strong> <span id="resumen-tipo">No seleccionado</span></p>
                        <p><strong>Urgencia:</strong> <span id="resumen-urgencia">Normal</span></p>
                      </div>
                      <div class="col-md-6">
                        <p><strong>Costo estimado:</strong> $<span id="resumen-costo">0.00</span> MXN</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Botón de envío -->
              <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save"></i> Guardar Solicitud
                </button>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </main>

  <!-- JS Bootstrap y lógica -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Función para obtener la ubicación actual
    function obtenerUbicacion(campo) {
      if (!navigator.geolocation) {
        alert("La geolocalización no es soportada por tu navegador");
        return;
      }
      
      const input = document.getElementById(campo);
      input.disabled = true;
      input.value = "Obteniendo ubicación...";
      
      navigator.geolocation.getCurrentPosition(
        function(position) {
          // En una implementación real, usarías una API de geocodificación inversa
          // para obtener una dirección a partir de las coordenadas
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          input.value = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
          input.disabled = false;
          
          // Si ambos campos de ubicación están llenos, calcular distancia
          if (document.getElementById('origen').value && document.getElementById('destino').value) {
            calcularDistancia();
          }
        },
        function(error) {
          console.error("Error al obtener la ubicación:", error);
          input.value = "";
          input.disabled = false;
          alert("Error al obtener la ubicación: " + error.message);
        }
      );
    }
    
    // Función para calcular distancia (simulada)
    function calcularDistancia() {
      const origen = document.getElementById('origen').value;
      const destino = document.getElementById('destino').value;
      
      if (!origen || !destino) {
        alert("Por favor complete ambas ubicaciones");
        return;
      }
      
      // Simular cálculo de distancia (en una implementación real usarías la API de Google Maps)
      const distancia = (Math.random() * 50 + 5).toFixed(2); // Entre 5 y 55 km
      document.getElementById('distancia').value = distancia;
      document.getElementById('resumen-distancia').textContent = distancia;
      
      // Actualizar resumen de costo
      actualizarResumenCosto();
    }
    
    // Función para actualizar el resumen de costo
    function actualizarResumenCosto() {
      const distancia = parseFloat(document.getElementById('distancia').value) || 0;
      const tipoServicio = document.getElementById('tipo_servicio').value;
      const urgencia = document.getElementById('urgencia').value;
      
      // Actualizar resumen
      document.getElementById('resumen-tipo').textContent = tipoServicio ? 
        document.getElementById('tipo_servicio').options[document.getElementById('tipo_servicio').selectedIndex].text : 
        'No seleccionado';
      document.getElementById('resumen-urgencia').textContent = 
        document.getElementById('urgencia').options[document.getElementById('urgencia').selectedIndex].text;
      
      // Calcular costo estimado (simplificado)
      let costo = 500; // Base
      costo += distancia * 50; // Por km
      
      // Ajustar por tipo de servicio
      switch(tipoServicio) {
        case 'remolque': costo *= 1.5; break;
        case 'arranque': costo *= 1.2; break;
        case 'cambio_llanta': costo += 200; break;
      }
      
      // Ajustar por urgencia
      switch(urgencia) {
        case 'urgente': costo *= 1.3; break;
        case 'emergencia': costo *= 1.5; break;
      }
      
      document.getElementById('resumen-costo').textContent = costo.toFixed(2);
    }
    
    // Event listeners para actualizar el resumen
    document.getElementById('distancia').addEventListener('change', actualizarResumenCosto);
    document.getElementById('tipo_servicio').addEventListener('change', actualizarResumenCosto);
    document.getElementById('urgencia').addEventListener('change', actualizarResumenCosto);
    
    // Mejora para dispositivos móviles
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      
      if ('ontouchstart' in window) {
        let isExpanded = false;
        
        sidebar.addEventListener('click', function(e) {
          if (e.target.closest('.sidebar_element') || e.target.closest('.sidebar_footer')) {
            return;
          }
          
          isExpanded = !isExpanded;
          this.style.width = isExpanded ? '250px' : '70px';
          document.querySelector('.main-content').style.marginLeft = 
            isExpanded ? '250px' : '70px';
        });
      }
    });
  </script>
</body>
</html>