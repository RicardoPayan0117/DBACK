<?php
session_start();

// Configuración de conexión
$servername = "localhost";
$username = "root";
$password = "5211";  // Cambia esto por tu contraseña real
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
               // $passwordErrorMessage = "Contraseña incorrecta.";
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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 350px;
            padding: 30px;
            text-align: center;
        }
        
        .login-header {
            margin-bottom: 30px;
        }
        
        .logo {
            font-size: 50px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        h1 {
            color: #2c3e50;
            font-size: 24px;
        }
        
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }
        
        input {
            width: 100%;
            padding: 12px 20px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .forgot-password a {
            color: #7f8c8d;
            text-decoration: none;
            font-size: 14px;
        }
        
        button {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 12px 20px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #1a252f;
        }
        
        button:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
        }
        
        button i {
            margin-right: 8px;
        }
        
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            text-align: left;
        }
        
        .connection-message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
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