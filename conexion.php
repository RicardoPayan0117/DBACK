<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "5211";  // Cambia esto por tu contraseña real
$dbname = "DBACK";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8mb4");

// Función para limpiar datos de entrada
function limpiarDatos($conn, $data) {
    return $conn->real_escape_string(trim($data));
}
?>