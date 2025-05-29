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

    $query = "UPDATE solicitudes_servicio SET 
              nombre = ?, 
              telefono = ?, 
              email = ?, 
              tipo_servicio = ?, 
              vehiculo = ?, 
              marca = ?, 
              modelo = ?, 
              ubicacion_origen = ?, 
              ubicacion_destino = ?, 
              descripcion = ?, 
              costo = ?, 
              estado = ? 
              WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssdsi", 
        $nombre, $telefono, $email, $tipo_servicio, 
        $vehiculo, $marca, $modelo, $ubicacion_origen, 
        $ubicacion_destino, $descripcion, $costo, $estado, 
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
$query = "SELECT * FROM solicitudes_servicio WHERE id = ?";
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
        .estado-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        .estado-pendiente { background-color: #FFC107; color: #000; }
        .estado-asignada { background-color: #17A2B8; color: #fff; }
        .estado-en_proceso { background-color: #007BFF; color: #fff; }
        .estado-completada { background-color: #28A745; color: #fff; }
        .estado-cancelada { background-color: #6C757D; color: #fff; }
        .form-group { margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <main class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><?php echo $modo_edicion ? 'Editar' : 'Detalle de'; ?> Solicitud #<?php echo $solicitud['id']; ?></h1>
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
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" id="nombre" name="nombre" class="form-control" 
                                               value="<?php echo htmlspecialchars($solicitud['nombre']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" id="telefono" name="telefono" class="form-control" 
                                               value="<?php echo htmlspecialchars($solicitud['telefono']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
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
                                        <label for="tipo_servicio">Tipo de Servicio:</label>
                                        <select id="tipo_servicio" name="tipo_servicio" class="form-control" required>
                                            <option value="remolque" <?php echo $solicitud['tipo_servicio'] == 'remolque' ? 'selected' : ''; ?>>Remolque</option>
                                            <option value="bateria" <?php echo $solicitud['tipo_servicio'] == 'bateria' ? 'selected' : ''; ?>>Cambio de batería</option>
                                            <option value="gasolina" <?php echo $solicitud['tipo_servicio'] == 'gasolina' ? 'selected' : ''; ?>>Suministro de gasolina</option>
                                            <option value="llanta" <?php echo $solicitud['tipo_servicio'] == 'llanta' ? 'selected' : ''; ?>>Cambio de llanta</option>
                                            <option value="arranque" <?php echo $solicitud['tipo_servicio'] == 'arranque' ? 'selected' : ''; ?>>Servicio de arranque</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="vehiculo">Vehículo:</label>
                                        <input type="text" id="vehiculo" name="vehiculo" class="form-control" 
                                               value="<?php echo htmlspecialchars($solicitud['vehiculo'] ?? ''); ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="marca">Marca:</label>
                                                <input type="text" id="marca" name="marca" class="form-control" 
                                                       value="<?php echo htmlspecialchars($solicitud['marca'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="modelo">Modelo:</label>
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
                                        <label for="ubicacion_origen">Ubicación Origen:</label>
                                        <textarea id="ubicacion_origen" name="ubicacion_origen" class="form-control" required><?php echo htmlspecialchars($solicitud['ubicacion_origen']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="ubicacion_destino">Ubicación Destino:</label>
                                        <textarea id="ubicacion_destino" name="ubicacion_destino" class="form-control"><?php echo htmlspecialchars($solicitud['ubicacion_destino'] ?? ''); ?></textarea>
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
                                        <label for="descripcion">Descripción:</label>
                                        <textarea id="descripcion" name="descripcion" class="form-control"><?php echo htmlspecialchars($solicitud['descripcion'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="costo">Costo (MXN):</label>
                                        <input type="number" step="0.01" id="costo" name="costo" class="form-control" 
                                               value="<?php echo number_format($solicitud['costo'] ?? 0, 2, '.', ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="estado">Estado:</label>
                                        <select id="estado" name="estado" class="form-control" required>
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
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($solicitud['nombre']); ?></p>
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($solicitud['telefono']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($solicitud['email'] ?? 'No especificado'); ?></p>
                                <p><strong>Fecha de solicitud:</strong> <?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])); ?></p>
                                <p>
                                    <strong>Estado:</strong> 
                                    <span class="estado-badge estado-<?php echo strtolower($solicitud['estado']); ?>">
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
                                <p><strong>Vehículo:</strong> <?php echo htmlspecialchars($solicitud['vehiculo'] ?? 'No especificado'); ?> (<?php echo htmlspecialchars($solicitud['marca'] ?? ''); ?> <?php echo htmlspecialchars($solicitud['modelo'] ?? ''); ?>)</p>
                                <p><strong>Ubicación origen:</strong> <?php echo htmlspecialchars($solicitud['ubicacion_origen']); ?></p>
                                <p><strong>Ubicación destino:</strong> <?php echo htmlspecialchars($solicitud['ubicacion_destino'] ?? 'No especificado'); ?></p>
                                <p><strong>Costo estimado:</strong> $<?php echo number_format($solicitud['costo'] ?? 0, 2); ?> MXN</p>
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
                                <?php if (!empty($solicitud['descripcion'])): ?>
                                    <p><?php echo nl2br(htmlspecialchars($solicitud['descripcion'])); ?></p>
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
</body>
</html>