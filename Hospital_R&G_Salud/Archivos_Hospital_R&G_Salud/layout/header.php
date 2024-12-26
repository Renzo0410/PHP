<?php
session_start();
require('../config/db.php');
ob_start(); // Inicia el almacenamiento en búfer de salida
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabajo final</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/registro.css">
    <link rel="stylesheet" href="../css/noticias.css">
    <link rel="stylesheet" href="../css/servicios.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/politicas.css">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../css/administracion.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>

<body>
    <!-- c_h = Container Header -->
    <div class="container-md c_h">
        <header class="row">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <!-- title_h -->
                    <a class="title_h navbar-brand" href="../views/home.php">R&G Salud</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <?php
                            // Obtener la página actual
                            $currentPage = basename($_SERVER['SCRIPT_NAME']);

                            // Enlaces comunes para todos los usuarios
                            ?>
                            <!-- INICIO -->
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($currentPage == 'home.php') ? 'active' : ''; ?>" href="../views/home.php">Inicio</a>
                            </li>
                            <!-- NOTICIAS -->
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($currentPage == 'noticias.php') ? 'active' : ''; ?>" href="../views/noticias.php">Noticias</a>
                            </li>

                            <?php
                            // Verificar si hay sesión iniciada y el rol del usuario
                            if (isset($_SESSION['rol'])) {
                                if ($_SESSION['rol'] == 'user') {
                                    // Menú para usuarios comunes
                                    echo '<li><a class="nav-link ' . ($currentPage == 'citas.php' ? 'active' : '') . '" href="../views/citas.php">Citaciones</a></li>';
                                    echo '<li><a class="nav-link ' . ($currentPage == 'perfil.php' ? 'active' : '') . '" href="../views/perfil.php">Perfil</a></li>';
                                    echo '<li><a class="nav-link" href="../includes/logout.php">Cerrar Sesión</a></li>';
                                } elseif ($_SESSION['rol'] == 'admin') {
                                    // Menú desplegable para administradores
                                    echo '
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle ' . (in_array($currentPage, ['noticias-administracion.php', 'usuarios-administracion.php', 'citas-administracion.php']) ? 'active' : '') . '" href="#" id="navbarAdminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Administración
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarAdminDropdown">
                                            <li><a class="dropdown-item ' . ($currentPage == 'noticias-administracion.php' ? 'active' : '') . '" href="../views/noticias-administracion.php">Administración de noticias</a></li>
                                            <li><a class="dropdown-item ' . ($currentPage == 'usuarios-administracion.php' ? 'active' : '') . '" href="../views/usuarios-administracion.php">Administración de usuarios</a></li>
                                            <li><a class="dropdown-item ' . ($currentPage == 'citas-administracion.php' ? 'active' : '') . '" href="../views/citas-administracion.php">Administración de citas</a></li>
                                        </ul>
                                    </li>
                                    ';
                                    echo '<li><a class="nav-link ' . ($currentPage == 'citas.php' ? 'active' : '') . '" href="../views/citas.php">Citaciones</a></li>';
                                    echo '<li><a class="nav-link ' . ($currentPage == 'perfil.php' ? 'active' : '') . '" href="../views/perfil.php">Perfil</a></li>';
                                    echo '<li><a class="nav-link" href="../includes/logout.php">Cerrar Sesión</a></li>';
                                }
                            } else {
                                // Menú para usuarios no registrados
                            ?>
                                <!-- SERVICIOS -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?php echo in_array($currentPage, ['servicios-cardiologia.php', 'servicios-vacunacion.php', 'servicios-pedriatria.php', 'servicios-neumologia.php']) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Servicios destacados
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item <?php echo ($currentPage == 'servicios-cardiologia.php') ? 'active' : ''; ?>" href="../views/servicios-cardiologia.php">Cardiología</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item <?php echo ($currentPage == 'servicios-vacunacion.php') ? 'active' : ''; ?>" href="../views/servicios-vacunacion.php">Vacunación</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item <?php echo ($currentPage == 'servicios-pedriatria.php') ? 'active' : ''; ?>" href="../views/servicios-pedriatria.php">Pediatría</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item <?php echo ($currentPage == 'servcios-neumologia.php') ? 'active' : ''; ?>" href="../views/servcios-neumologia.php">Neumología</a></li>
                                    </ul>
                                </li>
                                <!-- REGISTRO -->
                                <li class="nav-item">
                                    <a class="nav-link <?php echo ($currentPage == 'registro.php') ? 'active' : ''; ?>" href="../views/registro.php">Registro</a>
                                </li>
                                <!-- LOGIN -->
                                <li class="nav-item">
                                    <a class="nav-link <?php echo ($currentPage == 'login.php') ? 'active' : ''; ?>" href="../views/login.php">Iniciar sesión</a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>

                </div>
            </nav>
        </header>
    </div>