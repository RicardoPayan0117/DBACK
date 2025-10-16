<?php
require_once 'conexion.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar ID de solicitud
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['mensaje'] = "ID de solicitud no válido";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: procesar-solicitud.php");
    exit();
}

$solicitud_id = (int)$_GET['id'];
$modo_edicion = isset($_GET['editar']) && $_GET['editar'] == '1';

// Procesar formulario de edición si se envió
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_cambios'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $tipo_servicio = $conn->real_escape_string($_POST['tipo_servicio']);
    $vehiculo = $conn->real_escape_string($_POST['vehiculo'] ?? '');
    $marca = $conn->real_escape_string($_POST['marca'] ?? '');
    $modelo = $conn->real_escape_string($_POST['modelo'] ?? '');
    $ubicacion_origen = $conn->real_escape_string($_POST['ubicacion_origen']);
    $ubicacion_destino = $conn->real_escape_string($_POST['ubicacion_destino'] ?? '');
    $descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');
    $costo = (float)$_POST['costo'];
    $estado = $conn->real_escape_string($_POST['estado']);

    $query = "UPDATE solicitudes SET 
              nombre_completo = ?, 
              telefono = ?, 
              email = ?, 
              tipo_servicio = ?, 
              tipo_vehiculo = ?, 
              marca_vehiculo = ?, 
              modelo_vehiculo = ?, 
              ubicacion = ?, 
              descripcion_problema = ?, 
              costo_estimado = ?, 
              estado = ? 
              WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssdsi", 
        $nombre, $telefono, $email, $tipo_servicio, 
        $vehiculo, $marca, $modelo, $ubicacion_origen, 
        $descripcion, $costo, $estado, 
        $solicitud_id);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Solicitud actualizada correctamente";
        $_SESSION['tipo_mensaje'] = "exito";
        header("Location: detalle-solicitud.php?id=$solicitud_id");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la solicitud: " . $conn->error;
        $_SESSION['tipo_mensaje'] = "error";
    }
}

// Obtener datos de la solicitud
$query = "SELECT * FROM solicitudes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $solicitud_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['mensaje'] = "Solicitud no encontrada";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: procesar-solicitud.php");
    exit();
}

$solicitud = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $modo_edicion ? 'Editar' : 'Detalle de'; ?> Solicitud #<?php echo $solicitud['id']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* Estilos generales */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
            line-height: 1.6;
        }

        .container-fluid {
            padding: 0 2rem;
        }

        /* Mejoras en las cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Estilos para los badges de estado mejorados */
        .estado-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .estado-pendiente { 
            background-color: #FFF3CD; 
            color: #856404;
            border: 1px solid #FFEEBA;
        }

        .estado-asignada { 
            background-color: #D1ECF1; 
            color: #0C5460;
            border: 1px solid #BEE5EB;
        }

        .estado-en_proceso { 
            background-color: #CCE5FF; 
            color: #004085;
            border: 1px solid #B8DAFF;
        }

        .estado-completada { 
            background-color: #D4EDDA; 
            color: #155724;
            border: 1px solid #C3E6CB;
        }

        .estado-cancelada { 
            background-color: #E2E3E5; 
            color: #383D41;
            border: 1px solid #D6D8DB;
        }

        /* Mejoras en los formularios */
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        /* Botones mejorados */
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-lg {
            padding: 12px 24px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5c636a;
            border-color: #565e64;
        }

        /* Alertas mejoradas */
        .alert {
            border-radius: 8px;
            padding: 1rem 1.5rem;
            border: none;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
        }

        /* Mejoras en la tipografía */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: #212529;
        }

        /* Espaciados mejorados */
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .mb-5 {
            margin-bottom: 2rem !important;
        }

        /* Diseño responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 0 1rem;
            }
            
            .card-body {
                padding: 1.25rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        /* Efectos para los campos del formulario */
        .form-group {
            margin-bottom: 1.25rem;
            transition: all 0.3s ease;
        }

        .form-group:focus-within {
            transform: translateY(-2px);
        }

        /* Estilos para los textareas */
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        /* Mejoras en la visualización de datos */
        p strong {
            color: #495057;
            min-width: 150px;
            display: inline-block;
        }

        /* Efecto hover para las cards interactivas */
        .card-interactive {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .card-interactive:hover {
            background-color: #f8f9fa;
        }

        /* Estilo para el título principal */
        .page-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .page-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 4px;
            background: #0d6efd;
            border-radius: 2px;
        }

        /* Mejoras en los tooltips */
        .tooltip-inner {
            border-radius: 6px;
            padding: 6px 12px;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <main class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="page-title"><?php echo $modo_edicion ? 'Editar' : 'Detalle de'; ?> Solicitud #<?php echo $solicitud['id']; ?></h1>
                    <div>
                        <?php if ($modo_edicion): ?>
                            <a href="detalle-solicitud.php?id=<?php echo $solicitud_id; ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        <?php else: ?>
                            <a href="detalle-solicitud.php?id=<?php echo $solicitud_id; ?>&editar=1" class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="procesar-solicitud.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-<?php echo $_SESSION['tipo_mensaje'] == 'exito' ? 'success' : 'danger'; ?> alert-dismissible fade show">
                    <?php echo $_SESSION['mensaje']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php 
                unset($_SESSION['mensaje']);
                unset($_SESSION['tipo_mensaje']);
                endif; ?>

                <?php if ($modo_edicion): ?>
                <!-- Formulario de Edición -->
                <form method="POST" action="detalle-solicitud.php?id=<?php echo $solicitud_id; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Información del Cliente</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nombre" class="form-label">Nombre:</label>
                                        <input type="text" id="nombre" name="nombre" class="form-control" 
                                               value="<?php echo htmlspecialchars($solicitud['nombre_completo']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono" class="form-label">Teléfono:</label>
                                        <input type="text" id="telefono" name="telefono" class="form-control" 
                                               value="<?php echo htmlspecialchars($solicitud['telefono']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" id="email" name="email" class="form-control" 
                                               value="<?php echo htmlspecialchars($solicitud['email'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Detalles del Servicio</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="tipo_servicio" class="form-label">Tipo de Servicio:</label>
                                        <select id="tipo_servicio" name="tipo_servicio" class="form-select" required>
                                            <option value="remolque" <?php echo $solicitud['tipo_servicio'] == 'remolque' ? 'selected' : ''; ?>>Remolque</option>
                                            <option value="bateria" <?php echo $solicitud['tipo_servicio'] == 'bateria' ? 'selected' : ''; ?>>Cambio de batería</option>
                                            <option value="gasolina" <?php echo $solicitud['tipo_servicio'] == 'gasolina' ? 'selected' : ''; ?>>Suministro de gasolina</option>
                                            <option value="llanta" <?php echo $solicitud['tipo_servicio'] == 'llanta' ? 'selected' : ''; ?>>Cambio de llanta</option>
                                            <option value="arranque" <?php echo $solicitud['tipo_servicio'] == 'arranque' ? 'selected' : ''; ?>>Servicio de arranque</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="vehiculo" class="form-label">Vehículo:</label>
                                        <input type="text" id="vehiculo" name="vehiculo" class="form-control" 
                                               value="<?php echo htmlspecialchars($solicitud['vehiculo'] ?? ''); ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="marca" class="form-label">Marca:</label>
                                                <input type="text" id="marca" name="marca" class="form-control" 
                                                       value="<?php echo htmlspecialchars($solicitud['marca'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="modelo" class="form-label">Modelo:</label>
                                                <input type="text" id="modelo" name="modelo" class="form-control" 
                                                       value="<?php echo htmlspecialchars($solicitud['modelo'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Ubicaciones</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="ubicacion_origen" class="form-label">Ubicación Origen:</label>
                                        <textarea id="ubicacion_origen" name="ubicacion_origen" class="form-control" required><?php echo htmlspecialchars($solicitud['ubicacion']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="ubicacion_destino" class="form-label">Ubicación Destino:</label>
                                        <textarea id="ubicacion_destino" name="ubicacion_destino" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Otros Detalles</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="descripcion" class="form-label">Descripción:</label>
                                        <textarea id="descripcion" name="descripcion" class="form-control"><?php echo htmlspecialchars($solicitud['descripcion_problema'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="costo" class="form-label">Costo (MXN):</label>
                                        <input type="number" step="0.01" id="costo" name="costo" class="form-control" 
                                               value="<?php echo number_format($solicitud['costo_estimado'] ?? 0, 2, '.', ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="estado" class="form-label">Estado:</label>
                                        <select id="estado" name="estado" class="form-select" required>
                                            <option value="pendiente" <?php echo $solicitud['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                            <option value="asignada" <?php echo $solicitud['estado'] == 'asignada' ? 'selected' : ''; ?>>Asignada</option>
                                            <option value="en_proceso" <?php echo $solicitud['estado'] == 'en_proceso' ? 'selected' : ''; ?>>En proceso</option>
                                            <option value="completada" <?php echo $solicitud['estado'] == 'completada' ? 'selected' : ''; ?>>Completada</option>
                                            <option value="cancelada" <?php echo $solicitud['estado'] == 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <button type="submit" name="guardar_cambios" class="btn btn-success btn-lg">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                        <a href="detalle-solicitud.php?id=<?php echo $solicitud_id; ?>" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>

                <?php else: ?>
                <!-- Vista de Detalles (sin edición) -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Información del Cliente</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($solicitud['nombre_completo']); ?></p>
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($solicitud['telefono']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($solicitud['email'] ?? 'No especificado'); ?></p>
                                <p><strong>Fecha de solicitud:</strong> <?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])); ?></p>
                                <p>
                                    <strong>Estado:</strong> 
                                    <span class="estado-badge estado-<?php echo str_replace('_', '-', strtolower($solicitud['estado'])); ?>">
                                        <?php 
                                        $estados = [
                                            'pendiente' => 'Pendiente',
                                            'asignada' => 'Asignada',
                                            'en_proceso' => 'En proceso',
                                            'completada' => 'Completada',
                                            'cancelada' => 'Cancelada'
                                        ];
                                        echo $estados[strtolower($solicitud['estado'])] ?? 'Pendiente';
                                        ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Detalles del Servicio</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Tipo de servicio:</strong> <?php echo htmlspecialchars($solicitud['tipo_servicio']); ?></p>
                                <p><strong>Vehículo:</strong> <?php echo htmlspecialchars($solicitud['tipo_vehiculo'] ?? 'No especificado'); ?> (<?php echo htmlspecialchars($solicitud['marca_vehiculo'] ?? ''); ?> <?php echo htmlspecialchars($solicitud['modelo_vehiculo'] ?? ''); ?>)</p>
                                <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($solicitud['ubicacion']); ?></p>
                                <p><strong>Costo estimado:</strong> $<?php echo number_format($solicitud['costo_estimado'] ?? 0, 2); ?> MXN</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Descripción del Problema</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($solicitud['descripcion_problema'])): ?>
                                    <p><?php echo nl2br(htmlspecialchars($solicitud['descripcion_problema'])); ?></p>
                                <?php else: ?>
                                    <p class="text-muted">No se proporcionó descripción.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <a href="detalle-solicitud.php?id=<?php echo $solicitud_id; ?>&editar=1" class="btn btn-primary btn-lg">
                        <i class="bi bi-pencil"></i> Editar Solicitud
                    </a>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activar tooltips de Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Validación de formulario
            if (document.querySelector('form')) {
                document.querySelector('form').addEventListener('submit', function(e) {
                    let valid = true;
                    
                    // Validar campos requeridos
                    this.querySelectorAll('[required]').forEach(function(field) {
                        if (!field.value.trim()) {
                            valid = false;
                            field.classList.add('is-invalid');
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });
                    
                    if (!valid) {
                        e.preventDefault();
                        alert('Por favor complete todos los campos requeridos');
                    }
                });
            }
        });
    </script>
</body>
</html>