<?php
require_once 'conexion.php';

// Verificar sesión (debes implementar tu sistema de autenticación)
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener solicitudes
$query = "SELECT s.*, c.nombre as cliente_nombre, c.telefono as cliente_telefono 
          FROM solicitudes s
          JOIN clientes c ON s.cliente_id = c.id
          ORDER BY s.fecha_solicitud DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes de Grúa - Grúas DBACK</title>
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
          <a href="MenuAdmin.PHP" class="btn btn-outline-primary me-3">
            <i class="bi bi-arrow-left"></i> Volver al Menú
          </a>
          <h1 class="h2 mb-0"><i class="bi bi-truck me-2"></i> Solicitudes de Grúa</h1>
        </div>
      </header>

      <!-- Filtros -->
      <section class="card mb-4" aria-labelledby="filtros-heading">
        <div class="card-header bg-primary text-white">
          <h2 class="h5 mb-0" id="filtros-heading"><i class="bi bi-funnel"></i> Filtros</h2>
        </div>
        <div class="card-body">
          <form method="GET" action="">
            <div class="row g-3">
              <div class="col-md-4">
                <label for="filtroEstado" class="form-label">Estado</label>
                <select id="filtroEstado" name="estado" class="form-select">
                  <option value="">Todos</option>
                  <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                  <option value="proceso" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'proceso') ? 'selected' : ''; ?>>En proceso</option>
                  <option value="completado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'completado') ? 'selected' : ''; ?>>Completado</option>
                  <option value="cancelado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="filtroFecha" class="form-label">Fecha</label>
                <input type="date" id="filtroFecha" name="fecha" class="form-control" value="<?php echo isset($_GET['fecha']) ? $_GET['fecha'] : ''; ?>">
              </div>
              <div class="col-md-4">
                <label for="filtroBusqueda" class="form-label">Buscar</label>
                <div class="input-group">
                  <input type="text" id="filtroBusqueda" name="busqueda" class="form-control" placeholder="Nombre, teléfono..." value="<?php echo isset($_GET['busqueda']) ? $_GET['busqueda'] : ''; ?>">
                  <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </section>

      <!-- Listado de solicitudes -->
      <section class="card" aria-labelledby="solicitudes-heading">
        <div class="card-header bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0" id="solicitudes-heading"><i class="bi bi-list-check"></i> Solicitudes recientes</h2>
            <a href="nueva-solicitud.php" class="btn btn-success btn-sm">
              <i class="bi bi-plus-circle"></i> Nueva Solicitud
            </a>
          </div>
        </div>
        <div class="card-body p-0">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($solicitud = $result->fetch_assoc()): ?>
              <article class="request-card card mb-3 status-<?php echo str_replace('_', '-', $solicitud['estado']); ?>" data-status="<?php echo $solicitud['estado']; ?>" data-fecha="<?php echo date('Y-m-d', strtotime($solicitud['fecha_solicitud'])); ?>">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-md-8">
                      <h3 class="h5 card-title">
                        <i class="bi bi-person request-icon"></i> 
                        <?php echo htmlspecialchars($solicitud['cliente_nombre']); ?> 
                        <small class="text-muted">(<?php echo htmlspecialchars($solicitud['cliente_telefono']); ?>)</small>
                      </h3>
                      <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-calendar3 request-icon"></i> <strong>Fecha:</strong> <?php echo date('Y-m-d H:i', strtotime($solicitud['fecha_solicitud'])); ?></li>
                        <li><i class="bi bi-pin-map request-icon"></i> <strong>Distancia:</strong> <?php echo $solicitud['distancia'] ? $solicitud['distancia'] . ' km' : 'No especificada'; ?></li>
                        <li><i class="bi bi-currency-dollar request-icon"></i> <strong>Costo:</strong> <?php echo $solicitud['costo'] ? '$' . number_format($solicitud['costo'], 2) . ' MXN' : 'Por determinar'; ?></li>
                        <li><i class="bi bi-tools request-icon"></i> <strong>Tipo:</strong> <?php echo ucfirst(str_replace('_', ' ', $solicitud['tipo_servicio'])); ?> - <strong>Urgencia:</strong> <?php echo ucfirst($solicitud['urgencia']); ?></li>
                      </ul>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                      <?php 
                        $badge_class = '';
                        $badge_icon = '';
                        switch($solicitud['estado']) {
                          case 'pendiente':
                            $badge_class = 'bg-warning text-dark';
                            $badge_icon = 'bi-hourglass-split';
                            break;
                          case 'asignada':
                          case 'en_proceso':
                            $badge_class = 'bg-primary';
                            $badge_icon = 'bi-arrow-repeat';
                            break;
                          case 'completada':
                            $badge_class = 'bg-success';
                            $badge_icon = 'bi-check-circle';
                            break;
                          case 'cancelada':
                            $badge_class = 'bg-danger';
                            $badge_icon = 'bi-x-circle';
                            break;
                        }
                      ?>
                      <span class="badge rounded-pill <?php echo $badge_class; ?> mb-2">
                        <i class="bi <?php echo $badge_icon; ?>"></i> <?php echo ucfirst(str_replace('_', ' ', $solicitud['estado'])); ?>
                      </span>
                      <div class="d-grid gap-2 d-md-block">
                        <button class="btn btn-sm btn-outline-primary" onclick="verDetalles(
                          '<?php echo addslashes($solicitud['cliente_nombre']); ?>',
                          '<?php echo addslashes($solicitud['cliente_telefono']); ?>',
                          '<?php echo addslashes($solicitud['distancia']); ?>',
                          '<?php echo addslashes($solicitud['costo']); ?>',
                          '<?php echo date('Y-m-d H:i', strtotime($solicitud['fecha_solicitud'])); ?>',
                          '<?php echo addslashes(ucfirst(str_replace('_', ' ', $solicitud['tipo_servicio']))); ?>',
                          '<?php echo addslashes(ucfirst($solicitud['urgencia'])); ?>'
                        )">
                          <i class="bi bi-eye"></i> Ver Detalles
                        </button>
                        
                        <?php if ($solicitud['estado'] == 'pendiente'): ?>
                          <a href="procesar-solicitud.php?accion=aceptar&id=<?php echo $solicitud['id']; ?>" class="btn btn-sm btn-success ms-md-2 mt-2 mt-md-0">
                            <i class="bi bi-check-circle"></i> Aceptar
                          </a>
                        <?php elseif ($solicitud['estado'] == 'asignada' || $solicitud['estado'] == 'en_proceso'): ?>
                          <a href="procesar-solicitud.php?accion=cancelar&id=<?php echo $solicitud['id']; ?>" class="btn btn-sm btn-danger ms-md-2 mt-2 mt-md-0">
                            <i class="bi bi-x-circle"></i> Cancelar
                          </a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="alert alert-info m-3">No hay solicitudes registradas</div>
          <?php endif; ?>
        </div>
        <div class="card-footer bg-white">
          <nav aria-label="Navegación de solicitudes">
            <ul class="pagination justify-content-center mb-0">
              <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
              </li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#">Siguiente</a>
              </li>
            </ul>
          </nav>
        </div>
      </section>
    </div>
  </main>

  <!-- Modal de detalles -->
  <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h2 class="modal-title h5" id="modalDetallesLabel">Detalles de la Solicitud</h2>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <dl class="row">
            <dt class="col-sm-4"><i class="bi bi-person"></i> Nombre:</dt>
            <dd class="col-sm-8" id="detNombre"></dd>
            
            <dt class="col-sm-4"><i class="bi bi-telephone"></i> Teléfono:</dt>
            <dd class="col-sm-8" id="detTelefono"></dd>
            
            <dt class="col-sm-4"><i class="bi bi-calendar"></i> Fecha:</dt>
            <dd class="col-sm-8" id="detFecha"></dd>
            
            <dt class="col-sm-4"><i class="bi bi-tools"></i> Tipo de servicio:</dt>
            <dd class="col-sm-8" id="detTipo"></dd>
            
            <dt class="col-sm-4"><i class="bi bi-exclamation-triangle"></i> Urgencia:</dt>
            <dd class="col-sm-8" id="detUrgencia"></dd>
            
            <dt class="col-sm-4"><i class="bi bi-signpost"></i> Distancia:</dt>
            <dd class="col-sm-8" id="detDistancia"></dd>
            
            <dt class="col-sm-4"><i class="bi bi-cash-stack"></i> Costo:</dt>
            <dd class="col-sm-8" id="detCosto"></dd>
          </dl>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cerrar
          </button>
          <button type="button" class="btn btn-primary">
            <i class="bi bi-printer"></i> Imprimir
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- JS Bootstrap y lógica -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Función para mostrar detalles en el modal
    function verDetalles(nombre, telefono, distancia, costo, fecha, tipo, urgencia) {
      document.getElementById("detNombre").textContent = nombre;
      document.getElementById("detTelefono").textContent = telefono;
      document.getElementById("detFecha").textContent = fecha;
      document.getElementById("detTipo").textContent = tipo;
      document.getElementById("detUrgencia").textContent = urgencia;
      document.getElementById("detDistancia").textContent = distancia ? distancia + ' km' : 'No especificada';
      document.getElementById("detCosto").textContent = costo ? '$' + parseFloat(costo).toFixed(2) + ' MXN' : 'Por determinar';

      const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
      modal.show();
    }

    // Filtrado de solicitudes
    document.getElementById('filtroEstado').addEventListener('change', function() {
      const estado = this.value;
      const solicitudes = document.querySelectorAll('.request-card');
      
      solicitudes.forEach(solicitud => {
        if (estado === '' || solicitud.dataset.status === estado) {
          solicitud.style.display = '';
        } else {
          solicitud.style.display = 'none';
        }
      });
    });

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