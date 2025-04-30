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
            width: 80px;
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
            padding: 20px 0;
        }

        .sidebar_element {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .sidebar_element:hover {
            background-color: #34495e;
        }

        .sidebar_icon {
            width: 30px;
            height: 30px;
            margin-right: 15px;
            color: white;
        }

        .sidebar_icon--logo {
            width: 40px;
            height: 40px;
        }

        .sidebar_text {
            white-space: nowrap;
            font-size: 16px;
        }

        .sidebar_title {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .sidebar_info {
            font-size: 12px;
            opacity: 0.8;
        }

        /* Contenido principal */
        .main-content {
            flex: 1;
            margin-left: 80px;
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
                width: 60px;
            }
            .main-content {
                margin-left: 60px;
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
                <div class="sidebar_hide">
                    <p class="sidebar_text">Grúas DBACK</p>
                </div>
            </li>

            <li class="sidebar_element" onclick="showSection('dashboard')">
                <img src="Elementos/bxs-home.svg" class="sidebar_icon">
                <div class="sidebar_hide">
                    <p class="sidebar_text">Inicio</p>
                </div>
            </li>
            
            <li class="sidebar_element" onclick="showSection('gruas')">
                <img src="Elementos/bxs-car-mechanic.svg" class="sidebar_icon">
                <div class="sidebar_hide">
                    <p class="sidebar_text">Grúas</p>
                </div>
            </li>
            
            <li class="sidebar_element" onclick="showSection('gastos')">
                <img src="Elementos/bx-money-withdraw.svg" class="sidebar_icon">
                <div class="sidebar_hide">
                    <p class="sidebar_text">Gastos</p>
                </div>
            </li>
            
            <li class="sidebar_element" onclick="showSection('empleados')">
                <img src="Elementos/bx-male-female.svg" class="sidebar_icon">
                <div class="sidebar_hide">
                    <p class="sidebar_text">Empleados</p>
                </div>
            </li>
            
            <li class="sidebar_element sidebar_element--avatar">
                <img src="Elementos/bx-male-female.svg" class="sidebar_icon">
                <div class="sidebar_hide">
                    <h3 class="sidebar_title">Ricardo Payán</h3>
                    <p class="sidebar_info">Ingeniero de Software</p>
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

        <!-- Sección de Empleados -->
        <section id="empleados" class="content-section">
            <h1>Gestión de Empleados</h1>
            <p>Administra la información del personal.</p>
            <!-- Contenido de empleados -->
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

        // Carga inicial
        document.addEventListener('DOMContentLoaded', function() {
            // Por defecto muestra el dashboard
            showSection('dashboard');
        });
    </script>
</body>

</html>