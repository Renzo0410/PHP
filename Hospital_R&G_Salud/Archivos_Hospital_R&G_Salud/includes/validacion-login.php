<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/db.php';

    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Validar que los campos no estén vacíos
    if (empty($usuario) || empty($password)) {
        $_SESSION['error_message'] = "Por favor, ingresa tu usuario y contraseña.";
        header("Location: ../views/login.php"); // Redirigir a la página de login
        exit();
    }

    // Consulta para obtener los datos del usuario
    $stmt = $conn->prepare("SELECT * FROM users_login WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Iniciar sesión y redirigir según el rol
        $_SESSION['idUser'] = $user['idUser'];
        $_SESSION['rol'] = $user['rol'];

        // Redirigir siempre a home.php, que construirá el menú según el rol
        header("Location: ../views/home.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Usuario o contraseña incorrectos.";
        header("Location: ../views/login.php"); // Redirigir a la página de login
        exit();
    }
}
