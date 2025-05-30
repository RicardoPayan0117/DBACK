<body?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: login.php");
    exit();
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
        <link rel="stylesheet" href=".\CSS\Gastos.CSS">
    
</head>

<img>
   <!-- Barra lateral mejorada con ARIA -->
    <nav class="sidebar" aria-label="Menú principal">
        <div class="sidebar_header">
            <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK" width="30" height="30">
            <span class="sidebar_text">Grúas DBACK</span>
        </div>

        <ul class="sidebar_list" role="menubar">
            
            <li class="sidebar_element" role="menuitem" onclick="showSection('Volver al login')" tabindex="0" aria-label="Volver al Login">
                <a href="index.html" class="sidebar_link">
                    <i class="fas fa-truck sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Volver a login</span>
                </a>
            </li>

            <li class="sidebar_element" role="menuitem" onclick="showSection('dashboard')" tabindex="0" aria-label="Inicio">
                <a href="MenuAdmin.PHP" class="sidebar_link">
                <i class="fas fa-home sidebar_icon" aria-hidden="true"></i>
                <span class="sidebar_text">Inicio</span>
                </a>
            </li>
            
            <li class="sidebar_element" role="menuitem" onclick="showSection('gruas')" tabindex="0" aria-label="Grúas">
                <a href="Gruas.php" class="sidebar_link">
                    <i class="fas fa-truck sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Grúas</span>
                </a>
            </li>
            
            <li class="sidebar_element" role="menuitem" onclick="showSection('gastos')" tabindex="0" aria-label="Gastos">
                <a href="Gastos.php" class="sidebar_link">
                    <i class="fas fa-money-bill-wave sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Gastos</span>
                </a>
            </li>
            
            <li class="sidebar_element" role="menuitem" onclick="showSection('empleados')" tabindex="0" aria-label="Empleados">
                <a href="Empleados.php" class="sidebar_link">
                    <i class="fas fa-users sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Empleados</span>
                </a>
            </li>

            <li class="sidebar_element" role="menuitem" onclick="showSection('panel-solicitud')" tabindex="0" aria-label="Panel de solicitud">
                <a href="MenuSolicitudes.PHP" class="sidebar_link">
                    <i class="fas fa-clipboard-list sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Panel de solicitud</span>
                </a>
            </li>
        </ul>
<div class="sidebar_footer">
    <div class="sidebar_element" role="contentinfo">
        <i class="fas fa-user-circle sidebar_icon" aria-hidden="true"></i>
        <div>
            <div class="sidebar_text sidebar_title"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
            <div class="sidebar_text sidebar_info"><?php echo htmlspecialchars($_SESSION['usuario_cargo']); ?></div>
        </div>
    </div>
</div>

    </nav>
<img src="tmcj.jpg" height="50%" width="150%"/>
</body>
</html>