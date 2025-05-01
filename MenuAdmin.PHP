<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grúas DBACK - Administración</title>
    <style>
        /* Estilos básicos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        /* Sidebar */
        .sidebar {
            width: 60px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            transition: width 0.3s ease;
            overflow: hidden;
            position: fixed;
        }

        .sidebar:hover {
            width: 250px;
        }

        .sidebar_list {
            list-style: none;
            padding: 15px 0;
        }

        .sidebar_element {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.3s;
            white-space: nowrap;
        }

        .sidebar_element:hover {
            background-color: #34495e;
        }

        .sidebar_icon {
            width: 24px;
            height: 24px;
            margin-right: 15px;
            color: white;
            flex-shrink: 0;
        }

        .sidebar_icon--logo {
            width: 30px;
            height: 30px;
            margin-left: 3px;
        }

        .sidebar_text {
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .sidebar_text {
            opacity: 1;
        }

        .sidebar_title {
            font-size: 16px;
            margin-bottom: 3px;
        }

        .sidebar_info {
            font-size: 11px;
            opacity: 0.8;
        }

        /* Contenido principal */
        .main-content {
            flex: 1;
            margin-left: 60px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px;
        }

        .content-section {
            display: none;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .active {
            display: block;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 50px;
            }
            .main-content {
                margin-left: 50px;
            }
            .sidebar:hover {
                width: 200px;
            }
            .sidebar:hover ~ .main-content {
                margin-left: 200px;
            }
        }
    </style>
</head>

<body>
    <!-- Barra lateral -->
    <aside class="sidebar">
        <ul class="sidebar_list">
            <li class="sidebar_element">
                <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo">
                <span class="sidebar_text">Grúas DBACK</span>
            </li>

            <li class="sidebar_element" onclick="confirmRedirect()">
                <img src="Elementos/bxs-home.svg" class="sidebar_icon">
                <span class="sidebar_text">Inicio</span>
            </li>
            
            <li class="sidebar_element">
                <a href="Gruas.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                    <img src="Elementos/bxs-car-mechanic.svg" class="sidebar_icon">
                    <span class="sidebar_text">Grúas</span>
                </a>
            </li>
            
            <li class="sidebar_element" onclick="showSection('gastos')">
                <img src="Elementos/bx-money-withdraw.svg" class="sidebar_icon">
                <span class="sidebar_text">Gastos</span>
            </li>
            
            <li class="sidebar_element">
                <a href="Empleados.html" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                    <img src="Elementos/bx-male-female.svg" class="sidebar_icon">
                    <span class="sidebar_text">Empleados</span>
                </a>
            </li>
            
            <li class="sidebar_element">
                <img src="Elementos/bx-male-female.svg" class="sidebar_icon">
                <div>
                    <div class="sidebar_text sidebar_title">Ricardo Payán</div>
                    <div class="sidebar_text sidebar_info">Ingeniero de Software</div>
                </div>
            </li>
        </ul>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content">
        <!-- Sección de Inicio -->
        <section id="dashboard" class="content-section active">
            <h1>Bienvenido al Sistema de Grúas DBACK</h1>
            <p>Selecciona una opción del menú para comenzar.</p>
        </section>

        <!-- Sección de Grúas -->
        <section id="gruas" class="content-section">
            <h1>Administración de Grúas</h1>
            <p>Aquí puedes gestionar el inventario de grúas.</p>
            <!-- Contenido de grúas -->
        </section>

        <!-- Sección de Gastos -->
        <section id="gastos" class="content-section">
            <h1>Registro de Gastos</h1>
            <p>Administra los gastos de la empresa.</p>
            <!-- Contenido de gastos -->
        </section>
    </main>

    <script>
        // Función para mostrar secciones
        function showSection(sectionId) {
            // Oculta todas las secciones
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Muestra la sección seleccionada
            document.getElementById(sectionId).classList.add('active');
        }

        // Función para confirmar redirección
        function confirmRedirect() {
            if(confirm('¿Desea regresar al inicio? Esto cerrará su sesión actual.')) {
                window.location.href = 'index.html';
            }
        }

        // Carga inicial
        document.addEventListener('DOMContentLoaded', function() {
            // Por defecto muestra el dashboard
            showSection('dashboard');
        });
    </script>
</body>

</html>