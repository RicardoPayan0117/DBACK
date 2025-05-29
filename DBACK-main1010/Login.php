<?php
session_start();

// Configuración de conexión
$servername = "localhost";
$username = "root";
$password = "Admin2024ñ";  // Cambia esto por tu contraseña real
$dbname = "dback";

$connectionMessage = "";
$connectionStatus = false;
$userErrorMessage = "";
$passwordErrorMessage = "";
$lastUsername = "";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    $connectionStatus = true;

    if (isset($_POST['Login'])) {
        $usuario = $conn->real_escape_string($_POST['IngresarUsuario']);
        $clave = $conn->real_escape_string($_POST['IngresarContraseña']);
        $lastUsername = $usuario;

        // Verificar usuario
        $stmt = $conn->prepare("SELECT * FROM personal WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $userResult = $stmt->get_result();

        if ($userResult->num_rows > 0) {
            // Verificar contraseña
            $stmt = $conn->prepare("SELECT * FROM personal WHERE usuario = ? AND contraseña = SHA1(?)");
            $stmt->bind_param("ss", $usuario, $clave);
            $stmt->execute();
            $passwordResult = $stmt->get_result();

            if ($passwordResult->num_rows > 0) {
                $userData = $passwordResult->fetch_assoc();
                $_SESSION['usuario_id'] = $userData['id'];
                $_SESSION['usuario_nombre'] = $userData['nombre'];
                $_SESSION['usuario_cargo'] = $userData['cargo'];
                $_SESSION['usuario_usuario'] = $userData['usuario'];

                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'MenuAdmin.php';
                    }, 2000);
                </script>";
                $connectionMessage = "<p style='color: green; text-align: center;'>Login exitoso! Redirigiendo...</p>";
            } else {
                $passwordErrorMessage = "Contraseña incorrecta.";
            }
        } else {
            $userErrorMessage = "Credenciales incorrectas.";
        }
        $stmt->close();
    }
} catch (Exception $e) {
    $connectionMessage = "<p style='color: red; text-align: center;'>Error: " . $e->getMessage() . "</p>";
    $connectionStatus = false;
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grúas DBACK - Login</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <link rel="stylesheet" href="./CSS/Login.CSS" />
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-truck-pickup"></i>
            </div>
            <h1>Grúas D'BACK</h1>
        </div>
        
        <?php if (!empty($connectionMessage)): ?>
            <div class="connection-message <?php echo $connectionStatus ? 'success' : 'error'; ?>">
                <?php echo $connectionMessage; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="input-group">
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                </div>
                <input type="text" name="IngresarUsuario" placeholder="Usuario" value="<?php echo htmlspecialchars($lastUsername); ?>" required>
                <?php if (!empty($userErrorMessage)): ?>
                    <div class="error-message"><?php echo $userErrorMessage; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="input-group">
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <input type="password" name="IngresarContraseña" placeholder="Contraseña" required>
                <?php if (!empty($passwordErrorMessage)): ?>
                    <div class="error-message"><?php echo $passwordErrorMessage; ?></div>
                <?php endif; ?>
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