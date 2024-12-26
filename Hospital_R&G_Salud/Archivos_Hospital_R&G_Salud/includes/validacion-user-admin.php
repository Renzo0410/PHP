<?php

if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../views/login.php");
    exit();
}

// Crear nuevo usuario
if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    if ($accion === 'crear') {
        // Recoger y sanitizar datos del formulario
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
        $apellidos = htmlspecialchars(trim($_POST['apellidos'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
        $fecha_nacimiento = htmlspecialchars(trim($_POST['fecha_nacimiento'] ?? ''));
        $direccion = htmlspecialchars(trim($_POST['direccion'] ?? ''));
        $sexo = htmlspecialchars(trim($_POST['sexo'] ?? ''));
        $passw1 = htmlspecialchars(trim($_POST['passw1'] ?? ''));
        $passw2 = htmlspecialchars(trim($_POST['passw2'] ?? ''));
        $rol = htmlspecialchars(trim($_POST['rol'] ?? ''));
        $nombreUsuario = htmlspecialchars(trim($_POST['nombreUsuario'] ?? ''));

        // Validación de campos
        $nombreValido = preg_match("/^[a-zA-Z\s]+$/", $nombre);
        $apellidosValido = preg_match("/^[a-zA-Z\s]+$/", $apellidos);
        $emailValido = filter_var($email, FILTER_VALIDATE_EMAIL);
        $telefonoValido = preg_match("/^\d{9}$/", $telefono); // Se asume un formato de 9 dígitos
        $fechaNacimientoValida = preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_nacimiento); // Formato YYYY-MM-DD
        $direccionValida = preg_match("/^[\w\s\.,\-áéíóúÁÉÍÓÚñÑ]+$/", $direccion); // Letras, números y algunos signos
        $sexo = trim(htmlspecialchars($_POST['sexo'] ?? ''));
        $sexoValido = preg_match("/^(M|F|O)$/", $sexo);
        $passwValido = preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/", $passw1); // Requisitos de contraseña
        $nombreUsuarioValido = preg_match("/^[\w\-\.]{3,}$/", $nombreUsuario); // Al menos 3 caracteres, puede incluir puntos, guiones y guiones bajos

        if (empty($nombreValido) || empty($apellidosValido) || empty($emailValido) || empty($telefonoValido) || empty($fechaNacimientoValida) || empty($direccionValida) || empty($sexoValido) || empty($passwValido) || empty($nombreUsuarioValido) || empty($rol)) {
            echo "<p class='container alert alert-danger'>Por favor, verifique que todos los campos estén completos y tengan el formato correcto.</p>";
        } elseif (!$sexoValido) {
            echo "<p class='container alert alert-danger'>Por favor, seleccione un sexo válido.</p>";
            if ($sexo === 'O') {
                $sexo = 'OTRO';
            }
        } elseif ($passw1 !== $passw2) {
            echo "<p class='container alert alert-danger'>Las contraseñas no coinciden.</p>";
        } else {
            // Verificar si el email ya existe en la base de datos
            $emailCheckQuery = $conn->prepare("SELECT * FROM users_data WHERE email = ?");
            $emailCheckQuery->bind_param("s", $email);
            $emailCheckQuery->execute();
            $result = $emailCheckQuery->get_result();

            if ($result->num_rows > 0) {
                echo "<p class='container alert alert-danger'>El email que intentas usar ya está registrado.</p>";
            } else {
                // Verificar si el nombre de usuario ya existe en la base de datos
                $usuarioCheckQuery = $conn->prepare("SELECT * FROM users_login WHERE usuario = ?");
                $usuarioCheckQuery->bind_param("s", $nombreUsuario);
                $usuarioCheckQuery->execute();
                $usuarioResult = $usuarioCheckQuery->get_result();

                if ($usuarioResult->num_rows > 0) {
                    echo "<p class='container alert alert-danger'>El nombre de usuario que intentas usar ya está registrado.</p>";
                } else {
                    // Insertar datos en la tabla `users_data`
                    $sql = $conn->prepare("INSERT INTO users_data (nombre, apellidos, email, telefono, direccion, fecha_nacimiento, sexo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $sql->bind_param("sssssss", $nombre, $apellidos, $email, $telefono, $direccion, $fecha_nacimiento, $sexo);

                    if ($sql->execute()) {
                        $idUser = $conn->insert_id; // Obtener el último ID generado

                        // Insertar en la tabla `users_login`
                        $hashed_password = password_hash($passw1, PASSWORD_DEFAULT);
                        $sqlUser = $conn->prepare("INSERT INTO users_login (idUser, usuario, password, rol) VALUES (?, ?, ?, ?)");
                        $sqlUser->bind_param("isss", $idUser, $nombreUsuario, $hashed_password, $rol);

                        if ($sqlUser->execute()) {
                            echo "<p class='container alert alert-success'>Usuario creado con éxito.</p>";
                        } else {
                            echo "<p class='container alert alert-danger'>Error al registrar el usuario en USERS_LOGIN: " . $sqlUser->error . "</p>";
                        }

                        $sqlUser->close();
                    } else {
                        echo "<p class='container alert alert-danger'>Error al crear el usuario en USERS_DATA: " . $sql->error . "</p>";
                    }

                    $sql->close();
                }

                $usuarioCheckQuery->close();
            }

            $emailCheckQuery->close();
        }
    } elseif ($accion === 'eliminar') {
        // Validar y procesar la eliminación de un usuario por `idUser`
        $idUser = $_POST['idUser'] ?? '';

        if (empty($idUser) || !preg_match("/^\d+$/", $idUser)) {
            echo "<p class='container alert alert-danger'>El ID de usuario es obligatorio para eliminar y debe ser un número válido.</p>";
        } else {
            // Verificar si el usuario existe
            $userCheckQuery = $conn->prepare("SELECT * FROM users_data WHERE idUser = ?");
            $userCheckQuery->bind_param("i", $idUser);
            $userCheckQuery->execute();
            $result = $userCheckQuery->get_result();

            if ($result->num_rows > 0) {
                // Eliminar el usuario
                $deleteUserQuery = $conn->prepare("DELETE FROM users_data WHERE idUser = ?");
                $deleteUserQuery->bind_param("i", $idUser);

                if ($deleteUserQuery->execute()) {
                    echo "<p class='container alert alert-success'>Usuario eliminado con éxito.</p>";
                } else {
                    echo "<p class='container alert alert-danger'>Error al eliminar el usuario: " . $deleteUserQuery->error . "</p>";
                }

                $deleteUserQuery->close();
            } else {
                echo "<p class='container alert alert-danger'>El usuario no existe.</p>";
            }

            $userCheckQuery->close();
        }
    }
}

// Edición de usuario
if (isset($_POST['guardarEdicion'])) {
    $idUser = $_POST['idUser'] ?? '';
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $apellidos = htmlspecialchars(trim($_POST['apellidos'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
    $direccion = htmlspecialchars(trim($_POST['direccion'] ?? ''));
    $fecha_nacimiento = htmlspecialchars(trim($_POST['fecha_nacimiento'] ?? ''));
    $rol = htmlspecialchars(trim($_POST['rol'] ?? ''));
    $new_password = htmlspecialchars(trim($_POST['new_password'] ?? ''));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password'] ?? ''));

    // Validación de campos
    if (empty($idUser) || !preg_match("/^\d+$/", $idUser)) {
        echo "<p class='container alert alert-danger'>El ID de usuario es obligatorio y debe ser un número válido.</p>";
    } else {
        // Verificar si el email ya existe para otro usuario
        $emailCheckQuery = $conn->prepare("SELECT * FROM users_data WHERE email = ? AND idUser != ?");
        $emailCheckQuery->bind_param("si", $email, $idUser);
        $emailCheckQuery->execute();
        $result = $emailCheckQuery->get_result();

        if ($result->num_rows > 0) {
            echo "<p class='container alert alert-danger'>El email ya está registrado para otro usuario.</p>";
        } else {
            // Actualizar la información del usuario en users_data
            $sqlUpdate = "UPDATE users_data SET 
            nombre=?, 
            apellidos=?, 
            email=?, 
            telefono=?, 
            direccion=?, 
            fecha_nacimiento=? 
            WHERE idUser=?";

            $stmt = $conn->prepare($sqlUpdate);
            $stmt->bind_param("ssssssi", $nombre, $apellidos, $email, $telefono, $direccion, $fecha_nacimiento, $idUser);

            if ($stmt->execute()) {
                // Actualizar el rol en users_login
                $updateRoleQuery = $conn->prepare("UPDATE users_login SET rol=? WHERE idUser=?");
                $updateRoleQuery->bind_param("si", $rol, $idUser);
                $updateRoleQuery->execute();
                $updateRoleQuery->close();

                // Verificar si se debe actualizar la contraseña
                if (!empty($new_password)) {
                    if ($new_password === $confirm_password) {
                        // Validar nueva contraseña
                        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/", $new_password)) {
                            echo "<p class='container alert alert-danger'>La nueva contraseña no cumple con los requisitos.</p>";
                            exit();
                        } else {
                            // Hashear nueva contraseña
                            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $updatePasswordQuery = $conn->prepare("UPDATE users_login SET password=? WHERE idUser=?");
                            $updatePasswordQuery->bind_param("si", $hashed_password, $idUser);
                            $updatePasswordQuery->execute();
                            $updatePasswordQuery->close();
                        }
                    } else {
                        echo "<p class='container alert alert-danger'>Las contraseñas no coinciden.</p>";
                        exit();
                    }
                }
                echo "<p class='container alert alert-success'>Usuario actualizado correctamente.</p>";
            } else {
                echo "<p class='container alert alert-danger'>Error al actualizar usuario: " . $conn->error . "</p>";
            }
        }

        $emailCheckQuery->close();
    }
}
