<?php
session_save_path('C:\php\php_sessiones');

// Variable para el mensaje de conexión
$connectionMessage = "";
$connectionStatus = false;

// Conectar a la base de datos al cargar la página
$servername = "localhost";
$username = "root";
$password = "5211";  
$dbname = "DBACK";

// Variable para manejar la conexión sin detener la ejecución
try {
    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        $connectionMessage = "<p style='color: red; text-align: center; margin-bottom: 20px;'>Error de Conexión: " . $conn->connect_error . "</p>";
        $connectionStatus = false;
    } else {
        $connectionMessage = "<p style='color: green; text-align: center; margin-bottom: 20px;'>Conexión Exitosa a la Base de Datos</p>";
        $connectionStatus = true;
    }
} catch (Exception $e) {
    $connectionMessage = "<p style='color: red; text-align: center; margin-bottom: 20px;'>Error al conectar: " . $e->getMessage() . "</p>";
    $connectionStatus = false;
}

// Inicializar las variables para los mensajes
$userErrorMessage = "";
$passwordErrorMessage = "";
$lastUsername = ""; // Variable para mantener el último nombre de usuario

// Procesar el formulario de inicio de sesión
if (isset($_POST['Login'])) {
    // Solo intentar consultas si la conexión es exitosa
    if ($connectionStatus) {
        // Obtener los valores del formulario
        $usuario = $_POST['IngresarUsuario'];
        $clave = $_POST['IngresarContraseña'];
        
        // Guardar el nombre de usuario para mantenerlo en caso de error
        $lastUsername = $usuario;

        // Primero verificamos si el usuario existe
        $checkUser = "SELECT * FROM usuarios WHERE USUARIO = '$usuario'";
        $userResult = $conn->query($checkUser);

        if ($userResult->num_rows > 0) {
            // El usuario existe, ahora verificamos la contraseña
            $checkPassword = "SELECT * FROM usuarios WHERE USUARIO = '$usuario' AND CONTRASEÑA = '$clave'";
            $passwordResult = $conn->query($checkPassword);
            
            if ($passwordResult->num_rows > 0) {
                // Usuario y contraseña correctos, iniciar sesión
                header("Refresh: 2; url=MenuAdmin.PHP");  // Redirigir después de 2 segundos
                exit(); // Terminar el script después de la redirección
            } else {
                // Contraseña incorrecta
                $passwordErrorMessage = "<p style='color: red; margin-top: 5px; font-size: 0.8em;'> Contraseña incorrecta.</p>";
            }
        } else {
            // Usuario no encontrado
            $userErrorMessage = "<p style='color: red; margin-top: 5px; font-size: 0.8em;'> El usuario no existe.</p>";
        }
    }
}

// Cerrar la conexión si está abierta
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grúas DBACK-Login</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <link rel="stylesheet" href=".\CSS\Login.CSS">
    <style>
        .connection-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header del login -->
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-truck-pickup"></i>
            </div>
            <h1>Grúas D'BACK</h1>
        </div>

        <!-- Mostrar mensaje de conexión -->
        <?php 
        if (!$connectionStatus) {
            echo "<div class='connection-error'>
                    <strong>Error de Conexión</strong>
                    <p>No se pudo establecer conexión con la base de datos. Verifique sus credenciales.</p>
                  </div>";
        }
        ?>

        <!-- Formulario de login -->
        <form action="" method="post">
            <div class="input-group">
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                </div>
                <input type="text" name="IngresarUsuario" placeholder="Usuario" value="<?php echo htmlspecialchars($lastUsername); ?>" required>
                <?php if (!empty($userErrorMessage)) echo $userErrorMessage; ?>
            </div>

            <div class="input-group">
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <input type="password" name="IngresarContraseña" placeholder="Contraseña" required>
                <?php if (!empty($passwordErrorMessage)) echo $passwordErrorMessage; ?>
            </div>

            <div class="forgot-password">
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" name="Login" <?php echo $connectionStatus ? '' : 'disabled'; ?>>
                <i class="fas fa-sign-in-alt"></i>
                Iniciar Sesión
            </button>
        </form>
    </div>
</body>
</html>