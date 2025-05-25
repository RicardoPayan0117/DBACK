<?php
require_once 'conexion.php';

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['accion']) || !isset($_GET['id'])) {
    header("Location: panel-solicitud.php");
    exit();
}

$accion = $_GET['accion'];
$solicitud_id = intval($_GET['id']);

// Verificar que la solicitud existe
$query = "SELECT * FROM solicitudes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $solicitud_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['mensaje'] = "La solicitud no existe";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: panel-solicitud.php");
    exit();
}

$solicitud = $result->fetch_assoc();

// Procesar la acción
switch ($accion) {
    case 'aceptar':
        // Verificar que la solicitud esté pendiente
        if ($solicitud['estado'] != 'pendiente') {
            $_SESSION['mensaje'] = "La solicitud no está pendiente";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: panel-solicitud.php");
            exit();
        }
        
        // Actualizar estado a "asignada"
        $query = "UPDATE solicitudes SET estado = 'asignada' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $solicitud_id);
        
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Solicitud aceptada correctamente";
            $_SESSION['tipo_mensaje'] = "exito";
        } else {
            $_SESSION['mensaje'] = "Error al aceptar la solicitud";
            $_SESSION['tipo_mensaje'] = "error";
        }
        break;
        
    case 'cancelar':
        // Verificar que la solicitud esté asignada o en proceso
        if ($solicitud['estado'] != 'asignada' && $solicitud['estado'] != 'en_proceso') {
            $_SESSION['mensaje'] = "No se puede cancelar la solicitud en su estado actual";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: panel-solicitud.php");
            exit();
        }
        
        // Actualizar estado a "cancelada"
        $query = "UPDATE solicitudes SET estado = 'cancelada' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $solicitud_id);
        
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Solicitud cancelada correctamente";
            $_SESSION['tipo_mensaje'] = "exito";
        } else {
            $_SESSION['mensaje'] = "Error al cancelar la solicitud";
            $_SESSION['tipo_mensaje'] = "error";
        }
        break;
        
    default:
        $_SESSION['mensaje'] = "Acción no válida";
        $_SESSION['tipo_mensaje'] = "error";
        break;
}

header("Location: panel-solicitud.php");
exit();
?>