<?php
session_start();

// 1. Configuración y verificación de sesión
if (!isset($_SESSION['usuario_nombre'])) {
    header('Location: login.php');
    exit;
}

// Mostrar errores durante el desarrollo (eliminar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '5211');
define('DB_NAME', 'dback');

// 3. Función para conectar a la base de datos
function conectarDB() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    $conexion->set_charset("utf8");
    return $conexion;
}

// 4. Función para obtener iconos según categoría
function obtenerIconoCategoria($categoria) {
    $iconos = [
        'Reparacion' => 'fa-tools',
        'Gasto_Oficina' => 'fa-money-bill-wave',
        'Gasolina' => 'fa-gas-pump'
    ];
    return $iconos[$categoria] ?? 'fa-money-bill-wave';
}

// 5. Conectar a la base de datos
$conexion = conectarDB();

// 5.1 Crear gasto (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_gasto'])) {
    $errores = [];

    $tipo = $_POST['tipo'] ?? '';
    $idGrua = $_POST['id_grua'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $costo = $_POST['costo'] ?? '';

    // Validaciones básicas
    $tiposValidos = ['Reparacion','Gasto_Oficina','Gasolina'];
    if (!in_array($tipo, $tiposValidos, true)) $errores[] = 'Tipo inválido';
    if (!ctype_digit((string)$idGrua)) $errores[] = 'Grúa inválida';
    if ($descripcion === '' || mb_strlen($descripcion) > 400) $errores[] = 'Descripción requerida (máx 400)';
    if (!DateTime::createFromFormat('Y-m-d', $fecha)) $errores[] = 'Fecha inválida (YYYY-MM-DD)';
    if (!DateTime::createFromFormat('H:i', $hora)) $errores[] = 'Hora inválida (HH:MM)';
    if (!is_numeric($costo) || $costo < 0) $errores[] = 'Costo inválido';

    if (empty($errores)) {
        $stmt = $conexion->prepare("INSERT INTO `reparacion-servicio` (ID_Grua, Tipo, Descripcion, Fecha, Hora, Costo) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issssd", $idGrua, $tipo, $descripcion, $fecha, $hora, $costo);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Gasto creado correctamente";
            } else {
                $_SESSION['error'] = "No se pudo crear el gasto: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Error preparando inserción: " . $conexion->error;
        }
    } else {
        $_SESSION['error'] = implode(' · ', $errores);
    }

    header("Location: Gastos.php");
    exit;
}

// 6. Validar y sanitizar parámetros de filtro
$fecha_inicio = isset($_GET['fecha_inicio']) && DateTime::createFromFormat('Y-m-d', $_GET['fecha_inicio']) !== false ? 
    $_GET['fecha_inicio'] : date('Y-m-01');

$fecha_fin = isset($_GET['fecha_fin']) && DateTime::createFromFormat('Y-m-d', $_GET['fecha_fin']) !== false ? 
    $_GET['fecha_fin'] : date('Y-m-d');

$tipo_gasto = isset($_GET['tipo_gasto']) ? $conexion->real_escape_string($_GET['tipo_gasto']) : '';
$grua = isset($_GET['grua']) ? $conexion->real_escape_string($_GET['grua']) : '';
$orden = isset($_GET['orden']) && in_array($_GET['orden'], ['fecha_desc', 'fecha_asc', 'costo_desc', 'costo_asc']) ? 
    $_GET['orden'] : 'fecha_desc';

// 7. Construir consulta SQL con filtros
$sql = "SELECT rs.ID_Gasto, rs.ID_Grua, rs.Tipo, rs.Descripcion, rs.Fecha, rs.Hora, rs.Costo,
               g.Placa AS grua_placa, g.Marca AS grua_marca, g.Modelo AS grua_modelo
        FROM `reparacion-servicio` rs
        JOIN gruas g ON rs.ID_Grua = g.ID
        WHERE rs.Fecha BETWEEN ? AND ?";

$params = [$fecha_inicio, $fecha_fin];
$types = "ss";

if (!empty($tipo_gasto)) {
    $sql .= " AND rs.Tipo = ?";
    $params[] = $tipo_gasto;
    $types .= "s";
}

if (!empty($grua)) {
    $sql .= " AND g.Placa = ?";
    $params[] = $grua;
    $types .= "s";
}

// Ordenación
switch ($orden) {
    case 'fecha_asc': $sql .= " ORDER BY rs.Fecha ASC"; break;
    case 'costo_desc': $sql .= " ORDER BY rs.Costo DESC"; break;
    case 'costo_asc': $sql .= " ORDER BY rs.Costo ASC"; break;
    default: $sql .= " ORDER BY rs.Fecha DESC";
}

// 8. Ejecutar consulta principal
$gastos = [];
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result) {
            $gastos = $result->fetch_all(MYSQLI_ASSOC);
        }
    } else {
        $_SESSION['error'] = "Error al ejecutar la consulta: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "Error al preparar la consulta: " . $conexion->error;
}

// 9. Consulta para totales
$totales = ['total_gastado' => 0, 'total_registros' => 0];
$sql_totales = "SELECT SUM(rs.Costo) AS total_gastado, COUNT(*) AS total_registros
                FROM `reparacion-servicio` rs
                WHERE rs.Fecha BETWEEN ? AND ?";
$stmt = $conexion->prepare($sql_totales);
if ($stmt) {
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result) {
            $totales = $result->fetch_assoc() ?? $totales;
        }
    }
    $stmt->close();
}

// 10. Consulta para totales por tipo de gasto (anteriormente categoría)
$tipos_totales = [];
$sql_tipos = "SELECT Tipo as nombre, SUM(Costo) AS total
               FROM `reparacion-servicio`
               WHERE Fecha BETWEEN ? AND ?
               GROUP BY Tipo";
$stmt = $conexion->prepare($sql_tipos);
if ($stmt) {
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result) {
            $tipos_totales = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
    $stmt->close();
}

// 11. Consultas para opciones de filtros
$tipos_filtro = [];
$gruas_filtro = [];

// Obtener Tipos de gasto directamente de la enum en la tabla (o si tuvieras una tabla de tipos)
// Por simplicidad, asumimos los tipos directamente de la definición ENUM o de datos ya existentes
$result = $conexion->query("SELECT DISTINCT Tipo FROM `reparacion-servicio` ORDER BY Tipo");
if ($result) $tipos_filtro = $result->fetch_all(MYSQLI_ASSOC);
    
$result = $conexion->query("SELECT ID, Placa, CONCAT(Placa, ' - ', Marca, ' ', Modelo) AS descripcion FROM gruas ORDER BY Placa");
if ($result) $gruas_filtro = $result->fetch_all(MYSQLI_ASSOC);
    
// Quitar proveedores, ya no aplica

// 12. Cerrar conexión
$conexion->close();

// 13. Funciones para generar reportes
function generarPDF($gastos, $filtros) {
    // Verificar si hay datos para el reporte
    if (empty($gastos)) {
        $_SESSION['error'] = "No hay datos para generar el reporte PDF";
        return false;
    }

    // Ruta a TCPDF (ajusta según tu estructura de directorios)
    $tcpdfPath = 'tcpdf/tcpdf.php';
    if (!file_exists($tcpdfPath)) {
        $_SESSION['error'] = "Error: No se encontró la librería TCPDF en $tcpdfPath";
        return false;
    }

    require_once($tcpdfPath);
    
    try {
        // Evitar constantes PDF_* y el uso directo del tipo para no romper el linter si TCPDF no está instalado
        $tcpdfClass = class_exists('TCPDF') ? 'TCPDF' : null;
        if ($tcpdfClass === null) {
            $_SESSION['error'] = "TCPDF no disponible. Instale la librería para exportar a PDF.";
            return false;
        }
        $pdf = new $tcpdfClass('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Grúas DBACK');
        $pdf->SetAuthor('Sistema de Gastos');
        $pdf->SetTitle('Reporte de Gastos');
        $pdf->AddPage();
        
        // Cabecera
        $html = '<h1 style="text-align:center;">Reporte de Gastos</h1>';
        $html .= '<p><strong>Período:</strong> '.date('d/m/Y', strtotime($filtros['fecha_inicio'])).' al '.date('d/m/Y', strtotime($filtros['fecha_fin'])).'</p>';
        
        if (!empty($filtros['tipo_gasto'])) {
            $html .= '<p><strong>Tipo de Gasto:</strong> '.htmlspecialchars($filtros['tipo_gasto']).'</p>';
        }
        if (!empty($filtros['grua'])) {
            $html .= '<p><strong>Grúa:</strong> '.htmlspecialchars($filtros['grua']).'</p>';
        }
        
        // Tabla de gastos
        $html .= '<table border="1" cellpadding="5">
                    <tr style="background-color:#f2f2f2;">
                        <th width="15%">Fecha</th>
                        <th width="35%">Concepto</th>
                        <th width="15%">Monto</th>
                        <th width="20%">Categoría</th>
                        <th width="15%">Vehículo</th>
                    </tr>';
        
        $total = 0;
        foreach ($gastos as $gasto) {
            $html .= '<tr>
                        <td>'.date('d/m/Y', strtotime($gasto['Fecha'])).'</td>
                        <td>'.htmlspecialchars($gasto['Descripcion']).'</td>
                        <td>$'.number_format($gasto['Costo'], 2).'</td>
                        <td>'.htmlspecialchars($gasto['Tipo']).'</td>
                        <td>'.htmlspecialchars($gasto['grua_placa']).'</td>
                    </tr>';
            $total += $gasto['Costo'];
        }
        
        $html .= '<tr style="background-color:#f2f2f2;">
                    <td colspan="2"><strong>Total</strong></td>
                    <td colspan="3"><strong>$'.number_format($total, 2).'</strong></td>
                  </tr></table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('reporte_gastos.pdf', 'D');
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al generar PDF: " . $e->getMessage();
        return false;
    }
}

function generarExcel($gastos, $filtros) {
    if (empty($gastos)) {
        $_SESSION['error'] = "No hay datos para generar el reporte Excel";
        return false;
    }

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reporte_gastos.xls"');
    header('Cache-Control: max-age=0');
    
    echo '<table border="1">
            <tr><th colspan="5" style="background-color:#cccccc;">Reporte de Gastos</th></tr>
            <tr><th colspan="5">Período: '.date('d/m/Y', strtotime($filtros['fecha_inicio'])).' al '.date('d/m/Y', strtotime($filtros['fecha_fin'])).'</th></tr>';
    
    if (!empty($filtros['tipo_gasto'])) {
        echo '<tr><th colspan="5">Tipo de Gasto: '.htmlspecialchars($filtros['tipo_gasto']).'</th></tr>';
    }
    if (!empty($filtros['grua'])) {
        echo '<tr><th colspan="5">Grúa: '.htmlspecialchars($filtros['grua']).'</th></tr>';
    }
    
    echo '<tr style="background-color:#f2f2f2;">
            <th>Fecha</th>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Categoría</th>
            <th>Vehículo</th>
          </tr>';
    
    $total = 0;
    foreach ($gastos as $gasto) {
        echo '<tr>
                <td>'.date('d/m/Y', strtotime($gasto['Fecha'])).'</td>
                <td>'.htmlspecialchars($gasto['Descripcion']).'</td>
                <td>$'.number_format($gasto['Costo'], 2).'</td>
                <td>'.htmlspecialchars($gasto['Tipo']).'</td>
                <td>'.htmlspecialchars($gasto['grua_placa']).'</td>
              </tr>';
        $total += $gasto['Costo'];
    }
    
    echo '<tr style="background-color:#f2f2f2;">
            <td colspan="2"><strong>Total</strong></td>
            <td colspan="3"><strong>$'.number_format($total, 2).'</strong></td>
          </tr></table>';
    exit;
}

// 14. Procesar exportación de reportes
if (isset($_GET['export'])) {
    $filtros = [
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin,
        'tipo_gasto' => $tipo_gasto,
        'grua' => $grua
    ];
    
    if ($_GET['export'] == 'pdf') {
        generarPDF($gastos, $filtros);
    } elseif ($_GET['export'] == 'excel') {
        generarExcel($gastos, $filtros);
    }
    
    // Si llegamos aquí es que hubo un error
    header('Location: Gastos.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Gastos | Grúas DBACK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="./CSS/Gastos.CSS">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
        .badge { padding: 5px 10px; border-radius: 20px; color: white; font-size: 12px; }
        .summary-card { background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .summary-card h3 { margin-top: 0; color: #333; }
        .summary-card .value { font-size: 24px; font-weight: bold; margin: 10px 0; }
        .chart-container { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:hover { background-color: #f5f5f5; }
        .no-results { padding: 20px; text-align: center; color: #666; }
        .btn { padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-primary { background-color: #3498db; color: white; }
        .btn-secondary { background-color: #95a5a6; color: white; }
    </style>
</head>
<body>
    <nav class="sidebar">
        <div class="sidebar_header">
            <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK">
            <span class="sidebar_text">Grúas DBACK</span>
        </div>

        <ul class="sidebar_list">
            <li class="sidebar_element">
                <i class="fas fa-home sidebar_icon"></i>
                <span class="sidebar_text">Inicio</span>
            </li>
            <li class="sidebar_element">
                <a href="Gruas.php" class="sidebar_link">
                    <i class="fas fa-truck sidebar_icon"></i>
                    <span class="sidebar_text">Grúas</span>
                </a>
            </li>
            <li class="sidebar_element">
                <a href="Gastos.php" class="sidebar_link">
                    <i class="fas fa-money-bill-wave sidebar_icon"></i>
                    <span class="sidebar_text">Gastos</span>
                </a>
            </li>
            <li class="sidebar_element">
                <a href="Empleados.php" class="sidebar_link">
                    <i class="fas fa-users sidebar_icon"></i>
                    <span class="sidebar_text">Empleados</span>
                </a>
            </li>
            <li class="sidebar_element">
                <a href="procesar-solicitud.php" class="sidebar_link">
                    <i class="fas fa-clipboard-list sidebar_icon"></i>
                    <span class="sidebar_text">Panel de solicitud</span>
                </a>
            </li>
        </ul>

        <div class="sidebar_footer">
            <div class="sidebar_element">
                <i class="fas fa-user-circle sidebar_icon"></i>
                <div>
                    <div class="sidebar_text sidebar_title"><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario') ?></div>
                    <div class="sidebar_text sidebar_info"><?= htmlspecialchars($_SESSION['usuario_cargo'] ?? '') ?></div>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert" style="background:#d4edda; color:#155724; border-color:#c3e6cb;">
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
       <header class="admin-header">
    <nav aria-label="Navegación administrativa">
        <a href="MenuAdmin.PHP" class="back-button" aria-label="Volver al menú administrativo">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            <span>Volver al Menú</span>
        </a>
    </nav>
</header>

<style>
    .admin-header {
        background-color: #f8f9fa;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .back-button:hover, .back-button:focus {
        background-color: #e9ecef;
        color: #0056b3;
    }
    
    .back-button svg {
        transition: transform 0.2s ease;
    }
    
    .back-button:hover svg {
        transform: translateX(-2px);
    }
</style>
        <div class="container">
            <div class="header">
                <h1><i class="fas fa-file-invoice-dollar"></i> Reportes de Gastos</h1>
                <div class="header-actions">
                    <a href="?export=pdf&<?= http_build_query($_GET) ?>" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                    <a href="?export=excel&<?= http_build_query($_GET) ?>" class="btn btn-secondary">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
                </div>
            </div>

            <div class="filters-panel" style="margin-bottom: 18px;">
                <h2 class="filters-title"><i class="fas fa-plus-circle"></i> Registrar Gasto</h2>
                <form method="post" style="display:grid; gap:12px;">
                    <input type="hidden" name="crear_gasto" value="1">
                    <div class="filter-row">
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select id="tipo" name="tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="Reparacion">Reparación</option>
                                <option value="Gasto_Oficina">Gasto de Oficina</option>
                                <option value="Gasolina">Gasolina</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_grua">Grúa</label>
                            <select id="id_grua" name="id_grua" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($gruas_filtro as $gru): ?>
                                    <option value="<?= htmlspecialchars($gru['ID']) ?>">
                                        <?= htmlspecialchars($gru['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="flex:1">
                            <label for="descripcion">Descripción</label>
                            <input type="text" id="descripcion" name="descripcion" maxlength="400" required placeholder="Ej. Cambio de aceite, compra de gasolina...">
                        </div>
                    </div>

                    <div class="filter-row">
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars(date('Y-m-d')) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="hora">Hora</label>
                            <input type="time" id="hora" name="hora" value="<?= htmlspecialchars(date('H:i')) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="costo">Costo</label>
                            <input type="number" id="costo" name="costo" step="0.01" min="0" placeholder="0.00" required>
                        </div>
                        <div class="form-group" style="align-self:end">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="filters-panel">
                <h2 class="filters-title"><i class="fas fa-filter"></i> Filtros del Reporte</h2>
                <form id="reportForm" method="get">
                    <div class="filter-row">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
                        </div>
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de Fin</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
                        </div>
                        <div class="form-group">
                            <label for="categoria">Tipo de Gasto</label>
                            <select id="tipo_gasto" name="tipo_gasto">
                                <option value="">Todos los tipos</option>
                                <?php foreach ($tipos_filtro as $tipo): ?>
                                <option value="<?= htmlspecialchars($tipo['Tipo'] ?? $tipo['nombre']) ?>" <?= ((isset($tipo['Tipo']) && $tipo['Tipo'] == ($tipo_gasto ?? '')) || (isset($tipo['nombre']) && $tipo['nombre'] == ($tipo_gasto ?? ''))) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tipo['Tipo'] ?? $tipo['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="form-group">
                            <label for="grua">Grúa</label>
                            <select id="grua" name="grua">
                                <option value="">Todas las grúas</option>
                                <?php foreach ($gruas_filtro as $gru): ?>
                                <option value="<?= htmlspecialchars($gru['Placa']) ?>" <?= ($gru['Placa'] == $grua) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($gru['descripcion']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="orden">Ordenar por</label>
                            <select id="orden" name="orden">
                                <option value="fecha_desc" <?= ($orden == 'fecha_desc') ? 'selected' : '' ?>>Fecha (más reciente)</option>
                                <option value="fecha_asc" <?= ($orden == 'fecha_asc') ? 'selected' : '' ?>>Fecha (más antigua)</option>
                                <option value="costo_desc" <?= ($orden == 'costo_desc') ? 'selected' : '' ?>>Monto (mayor a menor)</option>
                                <option value="costo_asc" <?= ($orden == 'costo_asc') ? 'selected' : '' ?>>Monto (menor a mayor)</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-row">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Generar Reporte
                        </button>
                        <a href="Gastos.php" class="btn btn-secondary">
                            <i class="fas fa-broom"></i> Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>

            <div class="summary-cards">
                <div class="summary-card">
                    <h3><i class="fas fa-dollar-sign"></i> Total Gastado</h3>
                    <div class="value">$<?= number_format($totales['total_gastado'] ?? 0, 2) ?></div>
                    <div class="description">En el período seleccionado</div>
                </div>
                <div class="summary-card">
                    <h3><i class="fas fa-list-ol"></i> Registros</h3>
                    <div class="value"><?= $totales['total_registros'] ?? 0 ?></div>
                    <div class="description">Transacciones encontradas</div>
                </div>
                <?php foreach (array_slice($tipos_totales, 0, 4) as $tipo): ?>
                <div class="summary-card">
                    <h3><i class="fas <?= obtenerIconoCategoria($tipo['nombre']) ?>"></i> <?= htmlspecialchars($tipo['nombre']) ?></h3>
                    <div class="value">$<?= number_format($tipo['total'], 2) ?></div>
                    <div class="description">
                        <?= round(($tipo['total'] / ($totales['total_gastado'] ?: 1)) * 100, 1) ?>% del total
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="chart-container">
                <h2 class="chart-title"><i class="fas fa-chart-bar"></i> Distribución de Gastos</h2>
                <canvas id="gastosChart" height="100"></canvas>
            </div>

            <div class="chart-container">
                <h2 class="chart-title"><i class="fas fa-chart-line"></i> Evolución Mensual</h2>
                <canvas id="evolucionChart" height="100"></canvas>
            </div>

            <div class="table-container">
                <h2><i class="fas fa-table"></i> Detalle de Gastos</h2>
                <?php if (!empty($gastos)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Categoría</th>
                            <th>Vehículo</th>
                            <th>Proveedor</th>
                            <th>Factura</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($gastos as $gasto): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($gasto['Fecha'])) ?></td>
                            <td><?= htmlspecialchars($gasto['Descripcion']) ?></td>
                            <td>$<?= number_format($gasto['Costo'], 2) ?></td>
                            <td><span class="badge" style="background-color: #6c757d;"><?= $gasto['Tipo'] ?></span></td>
                            <td><?= htmlspecialchars($gasto['grua_placa']) ?></td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td class="actions-cell">
                                <button class="action-btn" title="Ver detalle" onclick="verDetalle(<?= $gasto['ID_Gasto'] ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn" title="Editar" onclick="editarGasto(<?= $gasto['ID_Gasto'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete" title="Eliminar" onclick="eliminarGasto(<?= $gasto['ID_Gasto'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <button class="pagination-btn"><i class="fas fa-angle-double-left"></i></button>
                    <button class="pagination-btn">1</button>
                    <button class="pagination-btn active">2</button>
                    <button class="pagination-btn">3</button>
                    <button class="pagination-btn"><i class="fas fa-angle-double-right"></i></button>
                </div>
                <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-info-circle"></i> No se encontraron gastos con los filtros seleccionados
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de distribución de gastos
            const gastosCtx = document.getElementById('gastosChart');
            if (gastosCtx) {
                new Chart(gastosCtx, {
                    type: 'doughnut',
                    data: {
                        labels: <?= json_encode(array_column($tipos_totales, 'nombre')) ?>,
                        datasets: [{
                            data: <?= json_encode(array_column($tipos_totales, 'total')) ?>,
                            backgroundColor: <?= json_encode(array_column($tipos_totales, 'color') ?? []) ?>,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'right' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Gráfico de evolución mensual (datos de ejemplo)
            const evolucionCtx = document.getElementById('evolucionChart');
            if (evolucionCtx) {
                const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                const mesActual = new Date().getMonth();
                const combustible = Array(12).fill(0);
                const mantenimiento = Array(12).fill(0);
                const total = Array(12).fill(0);
                
                // Simular datos para el mes actual
                if (mesActual >= 0) {
                    combustible[mesActual] = <?= $totales['total_gastado'] ?? 0 ?> * 0.6;
                    mantenimiento[mesActual] = <?= $totales['total_gastado'] ?? 0 ?> * 0.3;
                    total[mesActual] = <?= $totales['total_gastado'] ?? 0 ?>;
                }
                
                new Chart(evolucionCtx, {
                    type: 'line',
                    data: {
                        labels: meses,
                        datasets: [
                            {
                                label: 'Combustible',
                                data: combustible,
                                borderColor: '#3498db',
                                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Mantenimiento',
                                data: mantenimiento,
                                borderColor: '#2ecc71',
                                backgroundColor: 'rgba(46, 204, 113, 0.1)',
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Total',
                                data: total,
                                borderColor: '#e74c3c',
                                backgroundColor: 'rgba(231, 76, 60, 0.1)',
                                fill: true,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Evolución de Gastos Mensuales <?= date("Y") ?>'
                            },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Monto ($)' }
                            }
                        },
                        interaction: { mode: 'nearest', axis: 'x', intersect: false }
                    }
                });
            }

            // Funciones para acciones
            window.verDetalle = function(id) {
                alert('Mostrando detalle del gasto ID: ' + id);
                // Implementar lógica para mostrar detalles
            };

            window.editarGasto = function(id) {
                alert('Editando gasto ID: ' + id);
                // Implementar lógica para editar
            };

            window.eliminarGasto = function(id) {
                if (confirm('¿Está seguro que desea eliminar este gasto?')) {
                    alert('Eliminando gasto ID: ' + id);
                    // Implementar lógica para eliminar
                }
            };
        });
    </script>
</body>
</html>