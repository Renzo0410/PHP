<?php
session_start(); // Asegúrate de que la sesión esté iniciada
require('../config/db.php');

// Obtener el ID del usuario logueado desde la sesión
$idUser = $_SESSION['idUser'];

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Verificar si se subió una nueva imagen de perfil
    if (!empty($_FILES['profileImage']['name'])) {
        $profileImage = $_FILES['profileImage']['name'];
        $targetDir = '../img/avatar/'; // Ruta en el sistema de archivos
        $targetFile = $targetDir . basename($profileImage);
        move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFile);

        // Actualizar la imagen de perfil en la base de datos
        $stmt = $conn->prepare("UPDATE users_data SET profile_image = ? WHERE idUser = ?");
        $stmt->bind_param("si", $profileImage, $idUser);
        $stmt->execute();
    }

    // Actualizar otros datos del perfil
    $stmt = $conn->prepare("UPDATE users_data SET nombre = ?, apellidos = ?, fecha_nacimiento = ?, telefono = ?, direccion = ? WHERE idUser = ?");
    $stmt->bind_param("sssssi", $nombre, $apellidos, $fechaNacimiento, $telefono, $direccion, $idUser);
    $stmt->execute();

    // Verificar si se va a cambiar la contraseña
    if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmPassword)) {

        // Obtener la contraseña actual del usuario de la base de datos
        $stmt = $conn->prepare("SELECT password FROM users_login WHERE idUser = ?");
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verificar si la contraseña actual es correcta
        if (password_verify($currentPassword, $user['password'])) {

            // Verificar que la nueva contraseña y la confirmación coincidan
            if ($newPassword === $confirmPassword) {

                // Hashear la nueva contraseña
                $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

                // Actualizar la nueva contraseña en la base de datos
                $stmt = $conn->prepare("UPDATE users_login SET password = ? WHERE idUser = ?");
                $stmt->bind_param("si", $newPasswordHashed, $idUser);
                $stmt->execute();

                $_SESSION['success_message'] = "Contraseña actualizada exitosamente.";
            } else {
                $_SESSION['error_message'] = "La nueva contraseña y la confirmación no coinciden.";
            }
        } else {
            $_SESSION['error_message'] = "La contraseña actual es incorrecta.";
        }
    }

    // Si todo salió bien, mostrar un mensaje de éxito
    if (!isset($_SESSION['error_message'])) {
        $_SESSION['success_message'] = "Perfil actualizado exitosamente.";
    }

    // Redirigir al perfil
    header('Location: ../views/perfil.php');
    exit;
}
