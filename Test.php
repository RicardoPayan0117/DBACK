<?php
// Conexión a la base de datos
$host = "localhost";
$usuario = "root";
$clave = "5211";
$bd = "dback";

$conexion = new mysqli($host, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta de solicitudes
$sql = "SELECT nombre_completo, telefono, correo, distancia, costo, fecha_hora, tipo_servicio, urgencia, estado 
        FROM solicitudes
        ORDER BY fecha_hora DESC";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes de Grúa - Grúas DBACK</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .main-content {
      padding: 20px;
      margin-left: 260px; /* espacio para el sidebar */
    }
    .request-card {
      border-left: 5px solid #0d6efd;
    }
    .request-icon {
      margin-right: 5px;
    }
    .card-title {
      margin-bottom: 10px;
    }
    .sidebar {
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      background-color: #fff;
      border-right: 1px solid #dee2e6;
      padding: 20px;
    }
    .sidebar_icon {
      margin-right: 10px;
    }
    .sidebar_text {
      font-weight: 500;
    }
  </style>
</head>
<body>
  <!-- Barra lateral -->
  <nav class="sidebar" id="sidebar" aria-label="Menú principal">
    <div class="sidebar_header mb-4">
      <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK" style="height: 40px;">
      <span class="sidebar_text">Grúas DBACK</span>
    </div>

    <ul class="list-unstyled">
      <li><a href="MenuAdmin.php" class="d-flex align-items-center mb-2"><i class="bi bi-house sidebar_icon"></i> Inicio</a></li>
      <li><a href="Gruas.php" class="d-flex align-items-center mb-2"><i class="bi bi-truck sidebar_icon"></i> Grúas</a></li>
      <li><a href="Gastos.php" class="d-flex align-items-center mb-2"><i class="bi bi-cash-coin sidebar_icon"></i> Gastos</a></li>
      <li><a href="Empleados.html" class="d-flex align-items-center mb-2"><i class="bi bi-people sidebar_icon"></i> Empleados</a></li>
      <li class="fw-bold text-primary"><i class="bi bi-clipboard2-check sidebar_icon"></i> Panel de Solicitud</li>
    </ul>

    <div class="mt-4">
      <i class="bi bi-person-circle sidebar_icon"></i>
      <div>
        <div class="sidebar_text">Ricardo Payán</div>
        <div class="text-muted small">Ingeniero de Software</div>
      </div>
    </div>
  </nav>

  <!-- Contenido principal -->
  <main class="main-content">
    <div class="container-fluid">
      <header class="py-4">
        <div class="d-flex align-items-center">
          <a href="MenuAdmin.php" class="btn btn-outline-primary me-3">
            <i class="bi bi-arrow-left"></i> Volver al Menú
          </a>
          <h1 class="h2 mb-0"><i class="bi bi-truck me-2"></i> Solicitudes de Grúa</h1>
        </div>
      </header>

      <!-- Listado de solicitudes -->
      <section class="card mt-4">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0"><i class="bi bi-list-check"></i> Solicitudes recientes</h2>
        </div>
        <div class="card-body p-0">
          <?php if ($resultado->num_rows > 0): ?>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
              <?php
                $estado = strtolower($fila['estado']);
                $badge = match($estado) {
                    'pendiente' => 'bg-warning text-dark',
                    'proceso' => 'bg-primary text-white',
                    'completado' => 'bg-success',
                    'cancelado' => 'bg-danger',
                    default => 'bg-secondary'
                };
              ?>
              <article class="request-card card mb-3">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-md-8">
                      <h3 class="h5 card-title">
                        <i class="bi bi-person request-icon"></i> 
                        <?= htmlspecialchars($fila['nombre_completo']) ?> 
                        <small class="text-muted">(<?= htmlspecialchars($fila['telefono']) ?>)</small>
                      </h3>
                      <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-envelope request-icon"></i> <?= htmlspecialchars($fila['correo']) ?></li>
                        <li><i class="bi bi-calendar3 request-icon"></i> <strong>Fecha:</strong> <?= $fila['fecha_hora'] ?></li>
                        <li><i class="bi bi-pin-map request-icon"></i> <strong>Distancia:</strong> <?= $fila['distancia'] ?> km</li>
                        <li><i class="bi bi-currency-dollar request-icon"></i> <strong>Costo:</strong> $<?= number_format($fila['costo'], 2) ?> MXN</li>
                        <li><i class="bi bi-tools request-icon"></i> <strong>Tipo:</strong> <?= $fila['tipo_servicio'] ?> - <strong>Urgencia:</strong> <?= $fila['urgencia'] ?></li>
                      </ul>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                      <span class="badge rounded-pill <?= $badge ?> mb-2">
                        <?= ucfirst($estado) ?>
                      </span>
                      <div class="d-grid gap-2 d-md-block">
                        <button class="btn btn-sm btn-outline-primary">
                          <i class="bi bi-eye"></i> Ver Detalles
                        </button>
                        <?php if ($estado === 'pendiente'): ?>
                          <button class="btn btn-sm btn-success ms-md-2 mt-2 mt-md-0">
                            <i class="bi bi-check-circle"></i> Aceptar
                          </button>
                        <?php elseif ($estado === 'proceso'): ?>
                          <button class="btn btn-sm btn-danger ms-md-2 mt-2 mt-md-0">
                            <i class="bi bi-x-circle"></i> Cancelar
                          </button>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="p-3">
              <p class="text-center text-muted">No hay solicitudes registradas.</p>
            </div>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
