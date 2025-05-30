<?php
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
    <meta name="description" content="Sistema de administración para Grúas DBACK">
    <title>Grúas DBACK - Administración</title>
    <link rel="stylesheet" href="./CSS/MenuAdmin.CSS">
    <!-- Añadir Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Barra lateral mejorada con ARIA -->
    <nav class="sidebar" aria-label="Menú principal">
        <div class="sidebar_header">
            <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK" width="30" height="30">
            <span class="sidebar_text">Grúas DBACK</span>
        </div>

        <ul class="sidebar_list" role="menubar">
            <li class="sidebar_element" role="menuitem" onclick="showSection('dashboard')" tabindex="0" aria-label="Inicio">
                <i class="fas fa-home sidebar_icon" aria-hidden="true"></i>
                <span class="sidebar_text">Inicio</span>
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
                <a href="solicitud.html" class="sidebar_link">
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

    <!-- Contenido principal mejorado -->
    <main class="main-content" id="main-content">
        <!-- Sección de Inicio -->
        <section id="dashboard" class="content-section active" aria-labelledby="dashboard-heading">
            <header class="section-header">
                <h1 id="dashboard-heading">Bienvenido al Sistema de Grúas DBACK</h1>
                <button class="btn btn-primary" onclick="showHelp()" aria-label="Mostrar ayuda">
                    <i class="fas fa-question-circle" aria-hidden="true"></i> Ayuda
                </button>
            </header>
            
            <p>Selecciona una opción del menú para comenzar.</p>
            
            <div class="card-container" role="region" aria-label="Resumen del sistema">
                <article class="card" aria-labelledby="gruas-card-title">
                    <h3 class="card-title" id="gruas-card-title"><i class="fas fa-truck mr-2" aria-hidden="true"></i> Grúas Registradas</h3>
                    <p class="card-value" id="gruas-count">12</p>
                </article>
                
                <article class="card" aria-labelledby="gastos-card-title">
                    <h3 class="card-title" id="gastos-card-title"><i class="fas fa-money-bill-wave mr-2" aria-hidden="true"></i> Gastos del Mes</h3>
                    <p class="card-value" id="gastos-mes">$45,670.00</p>
                </article>
                
                <article class="card" aria-labelledby="empleados-card-title">
                    <h3 class="card-title" id="empleados-card-title"><i class="fas fa-users mr-2" aria-hidden="true"></i> Empleados</h3>
                    <p class="card-value" id="empleados-count">8</p>
                </article>
            </div>
        </section>

        <!-- Sección de Grúas -->
        <section id="gruas" class="content-section" aria-labelledby="gruas-heading" hidden>
            <header class="section-header">
                <h1 id="gruas-heading">Administración de Grúas</h1>
            </header>
            <p>Gestiona el inventario de grúas de la empresa.</p>
            <!-- Contenido de grúas mejorado iría aquí -->
        </section>

        <!-- Sección de Gastos -->
        <section id="gastos" class="content-section" aria-labelledby="gastos-heading" hidden>
            <header class="section-header">
                <h1 id="gastos-heading">Registro de Gastos</h1>
            </header>
            <p>Administra los gastos operativos de la empresa.</p>
            <!-- Contenido de gastos mejorado iría aquí -->
        </section>

        <!-- Sección de Empleados -->
        <section id="empleados" class="content-section" aria-labelledby="empleados-heading" hidden>
            <header class="section-header">
                <h1 id="empleados-heading">Gestión de Empleados</h1>
            </header>
            <p>Administra la información del personal.</p>
            <!-- Contenido de empleados mejorado iría aquí -->
        </section>

        <!-- Sección de Panel de Solicitud -->
        <section id="panel-solicitud" class="content-section" aria-labelledby="panel-solicitud-heading" hidden>
            <header class="section-header">
                <h1 id="panel-solicitud-heading">Panel de Solicitud</h1>
            </header>
            <p>Gestiona las solicitudes de servicio de grúas.</p>
            <!-- Contenido del panel de solicitud iría aquí -->
        </section>
    </main>

    <script>
        // Variables globales
        const sections = {
            'dashboard': {
                title: 'Inicio',
                heading: 'dashboard-heading'
            },
            'gruas': {
                title: 'Grúas',
                heading: 'gruas-heading'
            },
            'gastos': {
                title: 'Gastos',
                heading: 'gastos-heading'
            },
            'empleados': {
                title: 'Empleados',
                heading: 'empleados-heading'
            },
            'panel-solicitud': {
                title: 'Panel de Solicitud',
                heading: 'panel-solicitud-heading'
            }
        };

        // Función para mostrar secciones
        function showSection(sectionId) {
            // Oculta todas las secciones
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
                section.hidden = true;
                section.setAttribute('aria-hidden', 'true');
            });
            
            // Muestra la sección seleccionada
            const section = document.getElementById(sectionId);
            if (section) {
                section.classList.add('active');
                section.hidden = false;
                section.setAttribute('aria-hidden', 'false');
                
                // Enfocar el encabezado de la sección para lectores de pantalla
                const heading = document.getElementById(sections[sectionId].heading);
                if (heading) {
                    heading.focus();
                }
                
                updateActiveNavItem(sectionId);
                updateDocumentTitle(sections[sectionId].title);
            }
        }

        // Actualiza el ítem activo en la navegación
        function updateActiveNavItem(sectionId) {
            document.querySelectorAll('.sidebar_element').forEach(item => {
                item.classList.remove('active');
                item.setAttribute('aria-current', 'false');
            });
            
            // Encuentra el elemento correspondiente en el sidebar
            const navItems = document.querySelectorAll('.sidebar_element');
            navItems.forEach(item => {
                const textElement = item.querySelector('.sidebar_text');
                if (textElement && textElement.textContent.includes(sections[sectionId].title)) {
                    item.classList.add('active');
                    item.setAttribute('aria-current', 'page');
                }
            });
        }

        // Actualiza el título del documento
        function updateDocumentTitle(sectionTitle) {
            document.title = `Grúas DBACK - ${sectionTitle}`;
        }

        // Funciones simuladas para las acciones
        function showHelp() {
            alert('Mostrando ayuda del sistema...');
        }

        // Carga inicial
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración inicial
            initializeSidebar();
            initializeDashboard();
            showSection('dashboard');
        });

        // Inicializa la barra lateral
        function initializeSidebar() {
            const sidebarElements = document.querySelectorAll('.sidebar_element');
            
            sidebarElements.forEach(element => {
                element.setAttribute('role', 'menuitem');
                element.setAttribute('tabindex', '0');
                
                // Manejar eventos de teclado
                element.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        element.click();
                    }
                    
                    // Navegación con teclado
                    if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                        e.preventDefault();
                        const items = Array.from(document.querySelectorAll('.sidebar_element'));
                        const currentIndex = items.indexOf(element);
                        let nextIndex;
                        
                        if (e.key === 'ArrowUp' && currentIndex > 0) {
                            nextIndex = currentIndex - 1;
                        } else if (e.key === 'ArrowDown' && currentIndex < items.length - 1) {
                            nextIndex = currentIndex + 1;
                        }
                        
                        if (nextIndex !== undefined) {
                            items[nextIndex].focus();
                        }
                    }
                });
            });
        }

        // Inicializa el dashboard con datos simulados
        function initializeDashboard() {
            // Simular carga de datos
            setTimeout(() => {
                document.getElementById('gruas-count').textContent = '15';
                document.getElementById('gastos-mes').textContent = '$52,340.00';
                document.getElementById('empleados-count').textContent = '9';
            }, 1000);
            
            // Mejorar accesibilidad de las tarjetas
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.setAttribute('tabindex', '0');
                card.setAttribute('role', 'article');
            });
        }
    </script>
</body>
</html>