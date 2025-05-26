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
    $vehiculo_id = isset($_POST['vehiculo_id']) ? intval($_POST['vehiculo_id']) : null;
    $fecha_servicio = $conn->real_escape_string($_POST['fecha_servicio']);
    $ubicacion_origen = $conn->real_escape_string($_POST['ubicacion_origen']);
    $ubicacion_destino = $conn->real_escape_string($_POST['ubicacion_destino']);
    $distancia_km = floatval($_POST['distancia_km']);
    $tipo_servicio = $conn->real_escape_string($_POST['tipo_servicio']);
    $urgencia = $conn->real_escape_string($_POST['urgencia']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    
    // Calcular costo basado en distancia y tipo de servicio
    $costo_estimado = calcularCosto($distancia_km, $tipo_servicio, $urgencia);
    
    // Insertar solicitud (CORREGIDO: cambiado cliente_id por id_cliente)
    $query = "INSERT INTO servicios (id_cliente, vehiculo_id, ubicacion_origen, ubicacion_destino, tipo_servicio, descripcion, urgencia, distancia_km, costo_estimado, estado, fecha_servicio) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisssssds", $id_cliente, $vehiculo_id, $ubicacion_origen, $ubicacion_destino, $tipo_servicio, $descripcion, $urgencia, $distancia_km, $costo_estimado, $fecha_servicio);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Solicitud creada correctamente";
        $_SESSION['tipo_mensaje'] = "exito";
        header("Location: panel-solicitud.php");
        exit();
    } else {
        $error = "Error al crear la solicitud: " . $conn->error;
    }
}

// Función para calcular costo (CORREGIDA para usar los valores correctos del ENUM)
function calcularCosto($distancia_km, $tipo_servicio, $urgencia) {
    $costo_base = 500; // Costo base en pesos
    
    // Ajustar por distancia
    $costo = $costo_base + ($distancia_km * 50);
    
    // Ajustar por tipo de servicio (CORREGIDO para usar los valores del ENUM)
    switch ($tipo_servicio) {
        case 'remolque':
            $costo *= 1.5;
            break;
        case 'arranque':
            $costo *= 1.2;
            break;
        case 'llanta':
            $costo += 200;
            break;
        case 'bateria':
            $costo *= 1.1;
            break;
        case 'gasolina':
            $costo += 150;
            break;
        case 'otro':
            $costo *= 1.0;
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

// Obtener lista de vehículos (CORREGIDO - sin campo placa)
$query_vehiculos = "SELECT id, marca, modelo FROM vehiculos ORDER BY marca, modelo";
$vehiculos_result = $conn->query($query_vehiculos);
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
        <a href="Empleados.html" class="sidebar_link">
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
              
              <!-- Selección de vehículo (AGREGADO) -->
              <div class="col-md-6">
                <label for="vehiculo_id" class="form-label">Vehículo (Opcional)</label>
                <select class="form-select" id="vehiculo_id" name="vehiculo_id">
                  <option value="">Seleccionar vehículo...</option>
                  <?php if ($vehiculos_result && $vehiculos_result->num_rows > 0): ?>
                    <?php while ($vehiculo = $vehiculos_result->fetch_assoc()): ?>
                      <option value="<?php echo $vehiculo['id']; ?>">
                        <?php echo htmlspecialchars($vehiculo['marca'] . ' ' . $vehiculo['modelo']); ?>
                      </option>
                    <?php endwhile; ?>
                  <?php endif; ?>
                </select>
              </div>
              
              <!-- Fecha y hora del servicio -->
              <div class="col-md-6">
                <label for="fecha_servicio" class="form-label">Fecha y hora del servicio</label>
                <input type="datetime-local" class="form-control" id="fecha_servicio" name="fecha_servicio" required>
              </div>
              
              <!-- Ubicación de origen -->
              <div class="col-md-6">
                <label for="ubicacion_origen" class="form-label">Ubicación de origen</label>
                <input type="text" class="form-control" id="ubicacion_origen" name="ubicacion_origen" required>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="obtenerUbicacion('ubicacion_origen')">
                  <i class="bi bi-geo-alt"></i> Obtener ubicación actual
                </button>
              </div>
              
              <!-- Ubicación de destino -->
              <div class="col-md-6">
                <label for="ubicacion_destino" class="form-label">Ubicación de destino</label>
                <input type="text" class="form-control" id="ubicacion_destino" name="ubicacion_destino" required>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="obtenerUbicacion('ubicacion_destino')">
                  <i class="bi bi-geo-alt"></i> Obtener ubicación actual
                </button>
              </div>
              
              <!-- Distancia -->
              <div class="col-md-4">
                <label for="distancia_km" class="form-label">Distancia (km)</label>
                <input type="number" class="form-control" id="distancia_km" name="distancia_km" step="0.01" min="0" required>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="calcularDistancia()">
                  <i class="bi bi-calculator"></i> Calcular distancia
                </button>
              </div>
              
              <!-- Tipo de servicio (CORREGIDO para usar los valores del ENUM) -->
              <div class="col-md-4">
                <label for="tipo_servicio" class="form-label">Tipo de servicio</label>
                <select class="form-select" id="tipo_servicio" name="tipo_servicio" required>
                  <option value="">Seleccionar...</option>
                  <option value="remolque">Remolque</option>
                  <option value="bateria">Servicio de batería</option>
                  <option value="gasolina">Suministro de gasolina</option>
                  <option value="llanta">Cambio de llanta</option>
                  <option value="arranque">Servicio de arranque</option>
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
                        <p><strong>Distancia:</strong> <span id="resumen-distancia_km">0</span> km</p>
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
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          
          // Guardar coordenadas en atributos data para usar después
          input.setAttribute('data-lat', lat);
          input.setAttribute('data-lng', lng);
          input.value = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
          input.disabled = false;
          
          // Si ambos campos tienen coordenadas, calcular distancia automáticamente
          const origen = document.getElementById('ubicacion_origen');
          const destino = document.getElementById('ubicacion_destino');
          
          if (origen.getAttribute('data-lat') && destino.getAttribute('data-lat')) {
            calcularDistanciaReal();
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
    
    // Función para calcular distancia real usando coordenadas
    function calcularDistanciaReal() {
      const origen = document.getElementById('ubicacion_origen');
      const destino = document.getElementById('ubicacion_destino');
      
      const lat1 = parseFloat(origen.getAttribute('data-lat'));
      const lng1 = parseFloat(origen.getAttribute('data-lng'));
      const lat2 = parseFloat(destino.getAttribute('data-lat'));
      const lng2 = parseFloat(destino.getAttribute('data-lng'));
      
      if (lat1 && lng1 && lat2 && lng2) {
        const distancia = calcularDistanciaHaversine(lat1, lng1, lat2, lng2);
        document.getElementById('distancia_km').value = distancia.toFixed(2);
        document.getElementById('resumen-distancia_km').textContent = distancia.toFixed(2);
        actualizarResumenCosto();
      }
    }
    
    // Función Haversine para calcular distancia entre dos puntos geográficos
    function calcularDistanciaHaversine(lat1, lng1, lat2, lng2) {
      const R = 6371; // Radio de la Tierra en kilómetros
      const dLat = (lat2 - lat1) * Math.PI / 180;
      const dLng = (lng2 - lng1) * Math.PI / 180;
      
      const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng/2) * Math.sin(dLng/2);
      
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      const distancia = R * c;
      
      return distancia;
    }
    
    // Función para calcular distancia (botón manual)
    function calcularDistancia() {
      const ubicacion_origen = document.getElementById('ubicacion_origen').value;
      const ubicacion_destino = document.getElementById('ubicacion_destino').value;
      
      if (!ubicacion_origen || !ubicacion_destino) {
        alert("Por favor complete ambas ubicaciones");
        return;
      }
      
      // Si hay coordenadas guardadas, usar cálculo real
      const origen = document.getElementById('ubicacion_origen');
      const destino = document.getElementById('ubicacion_destino');
      
      if (origen.getAttribute('data-lat') && destino.getAttribute('data-lat')) {
        calcularDistanciaReal();
      } else {
        // Simular cálculo de distancia si no hay coordenadas
        const distancia_km = (Math.random() * 50 + 5).toFixed(2);
        document.getElementById('distancia_km').value = distancia_km;
        document.getElementById('resumen-distancia_km').textContent = distancia_km;
        actualizarResumenCosto();
      }
    }
    
    // Función para actualizar el resumen de costo (CORREGIDA para usar los valores correctos del ENUM)
    function actualizarResumenCosto() {
      const distancia_km = parseFloat(document.getElementById('distancia_km').value) || 0;
      const tipoServicio = document.getElementById('tipo_servicio').value;
      const urgencia = document.getElementById('urgencia').value;
      
      // Actualizar resumen de distancia
      document.getElementById('resumen-distancia_km').textContent = distancia_km.toFixed(2);
      
      // Actualizar resumen de tipo de servicio
      const tipoSelect = document.getElementById('tipo_servicio');
      document.getElementById('resumen-tipo').textContent = tipoServicio ? 
        tipoSelect.options[tipoSelect.selectedIndex].text : 
        'No seleccionado';
        
      // Actualizar resumen de urgencia
      const urgenciaSelect = document.getElementById('urgencia');
      document.getElementById('resumen-urgencia').textContent = 
        urgenciaSelect.options[urgenciaSelect.selectedIndex].text;
      
      // Calcular costo estimado (CORREGIDO para usar los valores correctos del ENUM)
      let costo = 500; // Base
      costo += distancia_km * 50; // Por km
      
      // Ajustar por tipo de servicio
      switch(tipoServicio) {
        case 'remolque': 
          costo *= 1.5; 
          break;
        case 'arranque': 
          costo *= 1.2; 
          break;
        case 'llanta': 
          costo += 200; 
          break;
        case 'bateria':
          costo *= 1.1;
          break;
        case 'gasolina':
          costo += 150;
          break;
        case 'otro':
          costo *= 1.0;
          break;
      }
      
      // Ajustar por urgencia
      switch(urgencia) {
        case 'urgente': 
          costo *= 1.3; 
          break;
        case 'emergencia': 
          costo *= 1.5; 
          break;
      }
      
      document.getElementById('resumen-costo').textContent = costo.toFixed(2);
    }
    
    // Event listeners para actualizar el resumen
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('distancia_km').addEventListener('input', actualizarResumenCosto);
      document.getElementById('tipo_servicio').addEventListener('change', actualizarResumenCosto);
      document.getElementById('urgencia').addEventListener('change', actualizarResumenCosto);
      
      // Sidebar para dispositivos móviles
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