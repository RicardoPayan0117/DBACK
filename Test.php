<?php
require_once 'conexion.php';
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Configuración de paginación
$registros_por_pagina = 8;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Filtros
$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$filtro_estado = isset($_GET['estado']) ? $_GET['estado'] : '';

// Construir la consulta base con filtros
$query = "SELECT SQL_CALC_FOUND_ROWS id, nombre, telefono, tipo_servicio, ubicacion_origen, 
          fecha_solicitud, IFNULL(estado, 'pendiente') as estado 
          FROM solicitudes_servicio 
          WHERE 1=1";

$params = [];
$types = '';

// Aplicar filtros
if (!empty($filtro_tipo)) {
    $query .= " AND tipo_servicio = ?";
    $params[] = $filtro_tipo;
    $types .= 's';
}

if (!empty($filtro_estado)) {
    $query .= " AND estado = ?";
    $params[] = $filtro_estado;
    $types .= 's';
}

// Ordenar por ID en lugar de fecha
$query .= " ORDER BY id DESC LIMIT ? OFFSET ?";
$types .= 'ii';
$params[] = $registros_por_pagina;
$params[] = $offset;

// Preparar y ejecutar consulta
$stmt = $conn->prepare($query);

if ($types) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Obtener total de registros
$total_registros = $conn->query("SELECT FOUND_ROWS()")->fetch_row()[0];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Tipos de servicio disponibles
$tipos_servicio = [
    '' => 'Seleccione una opción',
    'remolque' => 'Remolque',
    'bateria' => 'Cambio de batería',
    'gasolina' => 'Suministro de gasolina',
    'llanta' => 'Cambio de llanta',
    'arranque' => 'Servicio de arranque',
    'otro' => 'Otro servicio'
];

// Estados posibles con clases de Bootstrap
$estados = [
    'pendiente' => ['text' => 'Pendiente', 'class' => 'bg-warning text-dark'],
    'asignada' => ['text' => 'Asignada', 'class' => 'bg-info text-white'],
    'en_proceso' => ['text' => 'En proceso', 'class' => 'bg-primary text-white'],
    'completada' => ['text' => 'Completada', 'class' => 'bg-success text-white'],
    'cancelada' => ['text' => 'Cancelada', 'class' => 'bg-secondary text-white']
];

// Mensajes flash
$mensaje = $_SESSION['mensaje'] ?? null;
$tipo_mensaje = $_SESSION['tipo_mensaje'] ?? null;
unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Solicitudes - Grúas DBACK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .border-bottom {
            border-bottom: 2px solid #dee2e6 !important;
        }
        .card-solicitud {
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.075);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .card-solicitud:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 123, 255, 0.2);
        }
        .card-header {
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 5px 10px;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .pagination {
            margin-top: 2rem;
        }
        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .page-link {
            color: #0d6efd;
        }
        .filtros-container {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        .form-select {
            border-radius: 5px;
        }
        @media (max-width: 768px) {
            .row-cols-md-2 > * {
                flex: 0 0 auto;
                width: 100%;
            }
            .filtros-container .col-md-4 {
                margin-bottom: 1rem;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .col {
            animation: fadeIn 0.3s ease forwards;
            opacity: 0;
        }
        .col:nth-child(1) { animation-delay: 0.1s; }
        .col:nth-child(2) { animation-delay: 0.2s; }
        .col:nth-child(3) { animation-delay: 0.3s; }
        .col:nth-child(4) { animation-delay: 0.4s; }
        .col:nth-child(5) { animation-delay: 0.5s; }
        .col:nth-child(6) { animation-delay: 0.6s; }
        .col:nth-child(7) { animation-delay: 0.7s; }
        .col:nth-child(8) { animation-delay: 0.8s; }
        .alert {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .card-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .card-body div {
            margin-bottom: 0.5rem;
        }
        .fw-bold {
            color: #495057;
        }
        .id-solicitud {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <main class="col-md-12 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Procesar Solicitudes</h1>
                    <div class="id-solicitud">Mostrando solicitudes ordenadas por ID</div>
                </div>

                <?php if ($mensaje): ?>
                <div class="alert alert-<?= $tipo_mensaje == 'exito' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($mensaje) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="filtros-container mb-4">
                    <form method="get" class="row g-3">
                        <div class="col-md-4">
                            <label for="tipo" class="form-label">Tipo de servicio</label>
                            <select id="tipo" name="tipo" class="form-select">
                                <?php foreach ($tipos_servicio as $valor => $texto): ?>
                                    <option value="<?= $valor ?>" <?= $filtro_tipo == $valor ? 'selected' : '' ?>>
                                        <?= $texto ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select id="estado" name="estado" class="form-select">
                                <option value="">Todos los estados</option>
                                <?php foreach ($estados as $valor => $info): ?>
                                    <option value="<?= $valor ?>" <?= $filtro_estado == $valor ? 'selected' : '' ?>>
                                        <?= $info['text'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Filtrar
                            </button>
                            <a href="?" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($solicitud = $result->fetch_assoc()): 
                            $estado = strtolower($solicitud['estado']);
                            $info_estado = $estados[$estado] ?? $estados['pendiente'];
                            $tipo_servicio = $tipos_servicio[strtolower($solicitud['tipo_servicio'])] ?? $solicitud['tipo_servicio'];
                            $fecha_formateada = date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud']));
                        ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm card-solicitud">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span class="badge <?= $info_estado['class'] ?> rounded-pill">
                                        <?= $info_estado['text'] ?>
                                    </span>
                                    <small class="fw-bold">ID: <?= $solicitud['id'] ?></small>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($solicitud['nombre']) ?></h5>
                                    <div class="mb-2">
                                        <span class="fw-bold">Servicio:</span>
                                        <span class="ms-2"><?= htmlspecialchars($tipo_servicio) ?></span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="fw-bold">Teléfono:</span>
                                        <span class="ms-2"><?= htmlspecialchars($solicitud['telefono']) ?></span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="fw-bold">Ubicación:</span>
                                        <span class="ms-2"><?= htmlspecialchars($solicitud['ubicacion_origen']) ?></span>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="bi bi-clock"></i> <?= $fecha_formateada ?>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <div class="d-flex justify-content-between">
                                        <a href="detalle-solicitud.php?id=<?= $solicitud['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Ver detalles">
                                            <i class="bi bi-eye"></i> Detalles
                                        </a>
                                        
                                        <?php if ($estado == 'pendiente'): ?>
                                            <a href="gestion-solicitud.php?accion=aceptar&id=<?= $solicitud['id'] ?>" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Aceptar solicitud">
                                                <i class="bi bi-check-circle"></i> Aceptar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center py-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                No se encontraron solicitudes con los filtros aplicados
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($total_paginas > 1): ?>
                <nav aria-label="Navegación de páginas">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item <?= $pagina_actual <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" 
                               href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual - 1])) ?>" 
                               aria-label="Anterior">
                                &laquo;
                            </a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                            <a class="page-link" 
                               href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?>">
                            <a class="page-link" 
                               href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual + 1])) ?>" 
                               aria-label="Siguiente">
                                &raquo;
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }, 5000);
            });
        });
    </script>
</body>
</html>