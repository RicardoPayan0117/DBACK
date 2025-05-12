<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de administración para Grúas DBACK">
    <title>Grúas DBACK - Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variables CSS */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --light-color: #f5f5f5;
            --text-color: #333;
            --text-light: #fff;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        /* Estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--light-color);
            color: var(--text-color);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Sidebar mejorado */
        .sidebar {
            width: 60px;
            height: 100vh;
            background-color: var(--primary-color);
            color: var(--text-light);
            transition: var(--transition);
            overflow: hidden;
            position: fixed;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .sidebar:hover {
            width: 250px;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar_header {
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar_list {
            list-style: none;
            padding: 15px 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar_element {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            margin: 5px 10px;
            border-radius: var(--border-radius);
        }

        .sidebar_element:hover {
            background-color: var(--secondary-color);
            transform: translateX(5px);
        }

        .sidebar_element.active {
            background-color: var(--accent-color);
        }

        .sidebar_icon {
            width: 24px;
            height: 24px;
            margin-right: 15px;
            color: var(--text-light);
            flex-shrink: 0;
            text-align: center;
        }

        .sidebar_icon--logo {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .sidebar_text {
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.2s ease;
            font-weight: 500;
        }

        .sidebar:hover .sidebar_text {
            opacity: 1;
        }

        .sidebar_title {
            font-size: 16px;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .sidebar_info {
            font-size: 11px;
            opacity: 0.8;
        }

        .sidebar_footer {
            padding: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Contenido principal mejorado */
        .main-content {
            flex: 1;
            margin-left: 60px;
            padding: 30px;
            transition: var(--transition);
            min-height: 100vh;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px;
        }

        .content-section {
            display: none;
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .active {
            display: block;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        h1 {
            color: var(--primary-color);
            font-size: 28px;
            margin: 0;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 20px;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        /* Mejoras de accesibilidad */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* Responsive mejorado */
        @media (max-width: 992px) {
            .main-content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 50px;
            }
            
            .main-content {
                margin-left: 50px;
                padding: 15px;
            }
            
            .sidebar:hover {
                width: 220px;
            }
            
            .sidebar:hover ~ .main-content {
                margin-left: 220px;
            }
            
            .card-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                height: 60px;
                bottom: 0;
                top: auto;
                flex-direction: row;
                overflow-x: auto;
            }
            
            .sidebar:hover {
                width: 100%;
                height: 60px;
            }
            
            .sidebar_header, 
            .sidebar_footer {
                display: none;
            }
            
            .sidebar_list {
                display: flex;
                padding: 0;
                flex-grow: 1;
            }
            
            .sidebar_element {
                flex-direction: column;
                padding: 10px;
                margin: 0 5px;
                min-width: 60px;
            }
            
            .sidebar_icon {
                margin-right: 0;
                margin-bottom: 5px;
            }
            
            .sidebar_text {
                font-size: 10px;
                opacity: 1;
            }
            
            .sidebar:hover .sidebar_text {
                opacity: 1;
            }
            
            .main-content {
                margin-left: 0;
                margin-bottom: 60px;
            }
            
            .sidebar:hover ~ .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Barra lateral mejorada -->
    <aside class="sidebar">
        <div class="sidebar_header">
            <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK">
            <span class="sidebar_text">Grúas DBACK</span>
        </div>

        <ul class="sidebar_list">
            <li class="sidebar_element" onclick="showSection('dashboard')">
                <i class="fas fa-home sidebar_icon"></i>
                <span class="sidebar_text">Inicio</span>
            </li>
            
            <li class="sidebar_element" onclick="showSection('gruas')">
                <a href="Gruas.php" class="sidebar_link">
                    <i class="fas fa-truck sidebar_icon"></i>
                    <span class="sidebar_text">Grúas</span>
                </a>
            </li>
            
            <li class="sidebar_element" onclick="showSection('gastos')">
                <a href="Gastos.php" class="sidebar_link">
                    <i class="fas fa-money-bill-wave sidebar_icon"></i>
                    <span class="sidebar_text">Gastos</span>
                </a>
            </li>
            
            <li class="sidebar_element" onclick="showSection('empleados')">
                <a href="Empleados.html" class="sidebar_link">
                    <i class="fas fa-users sidebar_icon"></i>
                    <span class="sidebar_text">Empleados</span>
                </a>
            </li>

             <li class="sidebar_element" onclick="showSection('panel-solicitud')">
                <a href="panel-solicitud.html" class="sidebar_link">
                    <i class="fas fa-users sidebar_icon"></i>
                    <span class="sidebar_text">panel-solicitud</span>
                </a>
            </li>

        </ul>

        <div class="sidebar_footer">
            <div class="sidebar_element">
                <i class="fas fa-user-circle sidebar_icon"></i>
                <div>
                    <div class="sidebar_text sidebar_title">Ricardo Payán</div>
                    <div class="sidebar_text sidebar_info">Ingeniero de Software</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Contenido principal mejorado -->
    <main class="main-content">
        <!-- Sección de Inicio -->
        <section id="dashboard" class="content-section active">
            <div class="section-header">
                <h1>Bienvenido al Sistema de Grúas DBACK</h1>
                <button class="btn btn-primary" onclick="showHelp()">
                    <i class="fas fa-question-circle"></i> Ayuda
                </button>
            </div>
            
            <p>Selecciona una opción del menú para comenzar.</p>
            
            <div class="card-container">
                <div class="card">
                    <h3 class="card-title"><i class="fas fa-truck mr-2"></i> Grúas Registradas</h3>
                    <p class="card-value" id="gruas-count">12</p>
                </div>
                
                <div class="card">
                    <h3 class="card-title"><i class="fas fa-money-bill-wave mr-2"></i> Gastos del Mes</h3>
                    <p class="card-value" id="gastos-mes">$45,670.00</p>
                </div>
                
                <div class="card">
                    <h3 class="card-title"><i class="fas fa-users mr-2"></i> Empleados</h3>
                    <p class="card-value" id="empleados-count">8</p>
                </div>
            </div>
        </section>

        <!-- Sección de Grúas -->
        <section id="gruas" class="content-section">
            <div class="section-header">
                <h1>Administración de Grúas</h1>
            </div>
            <p>Gestiona el inventario de grúas de la empresa.</p>
            <!-- Contenido de grúas mejorado iría aquí -->
        </section>

        <!-- Sección de Gastos -->
        <section id="gastos" class="content-section">
            <div class="section-header">
                <h1>Registro de Gastos</h1>
              
            </div>
            <p>Administra los gastos operativos de la empresa.</p>
            <!-- Contenido de gastos mejorado iría aquí -->
        </section>

        <!-- Sección de Empleados -->
        <section id="empleados" class="content-section">
            <div class="section-header">
                <h1>Gestión de Empleados</h1>
               
            </div>
            <p>Administra la información del personal.</p>
            <!-- Contenido de empleados mejorado iría aquí -->
        </section>

        <!-- Sección de Reportes -->
        <section id="reportes" class="content-section">
            <h1>Reportes y Estadísticas</h1>
            <p>Genera reportes y visualiza estadísticas de la empresa.</p>
            <!-- Contenido de reportes iría aquí -->
        </section>

        <!-- Sección de Configuración -->
        <section id="configuracion" class="content-section">
            <h1>Configuración del Sistema</h1>
            <p>Ajustes y preferencias del sistema.</p>
            <!-- Contenido de configuración iría aquí -->
        </section>
    </main>

    <script>
        // Variables globales
        const sections = {
            'dashboard': 'Inicio',
            'gruas': 'Grúas',
            'gastos': 'Gastos',
            'empleados': 'Empleados',
            'reportes': 'Reportes',
            'configuracion': 'Configuración'
        };

        // Función para mostrar secciones
        function showSection(sectionId) {
            // Oculta todas las secciones
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Muestra la sección seleccionada
            const section = document.getElementById(sectionId);
            if (section) {
                section.classList.add('active');
                updateActiveNavItem(sectionId);
                document.title = `Grúas DBACK - ${sections[sectionId]}`;
            }
        }

        // Actualiza el ítem activo en la navegación
        function updateActiveNavItem(sectionId) {
            document.querySelectorAll('.sidebar_element').forEach(item => {
                item.classList.remove('active');
            });
            
            // Encuentra el elemento correspondiente en el sidebar
            const navItems = document.querySelectorAll('.sidebar_element');
            navItems.forEach(item => {
                if (item.textContent.includes(sections[sectionId])) {
                    item.classList.add('active');
                }
            });
        }

        // Funciones simuladas para las acciones
        function addGrua() {
            alert('Funcionalidad para agregar nueva grúa');
        }

        function addGasto() {
            alert('Funcionalidad para agregar nuevo gasto');
        }

        function addEmpleado() {
            alert('Funcionalidad para agregar nuevo empleado');
        }

        function exportGastos() {
            alert('Exportando datos de gastos...');
        }

        function showHelp() {
            alert('Mostrando ayuda del sistema...');
        }

        // Carga inicial
        document.addEventListener('DOMContentLoaded', function() {
            // Por defecto muestra el dashboard
            showSection('dashboard');
            
            // Actualizar datos del dashboard (simulado)
            setTimeout(() => {
                document.getElementById('gruas-count').textContent = '15';
                document.getElementById('gastos-mes').textContent = '$52,340.00';
                document.getElementById('empleados-count').textContent = '9';
            }, 1000);
            
            // Mejorar accesibilidad del sidebar
            const sidebarElements = document.querySelectorAll('.sidebar_element');
            sidebarElements.forEach(element => {
                element.setAttribute('role', 'button');
                element.setAttribute('tabindex', '0');
                
                element.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        element.click();
                    }
                });
            });
        });
    </script>
</body>

</html>