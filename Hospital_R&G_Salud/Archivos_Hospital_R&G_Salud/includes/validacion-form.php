<?php

// Obtener la acción del formulario
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if (isset($_POST['submit'])) {
    $errorMessage = ''; // Variable para almacenar mensajes de error

    if ($accion === 'registrar') {
        // DATOS PERSONALES
        $nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : '';
        $apellidos = isset($_POST['apellidos']) ? htmlspecialchars(trim($_POST['apellidos'])) : '';
        $fechaNacimiento = isset($_POST['fechaNacimiento']) ? htmlspecialchars(trim($_POST['fechaNacimiento'])) : '';
        $numPhone = isset($_POST['numPhone']) ? htmlspecialchars(trim($_POST['numPhone'])) : '';
        $sexo = isset($_POST['sexo']) ? htmlspecialchars(trim($_POST['sexo'])) : '';

        // DATOS DE CONTACTO
        $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
        $direccion = isset($_POST['direccion']) ? htmlspecialchars(trim($_POST['direccion'])) : '';

        // PASSWORD
        $passw1 = isset($_POST['passw1']) ? htmlspecialchars(trim($_POST['passw1'])) : '';
        $passw2 = isset($_POST['passw2']) ? htmlspecialchars(trim($_POST['passw2'])) : '';

        // CONSENTIMIENTO
        $consentimiento = isset($_POST['consentimiento']) ? $_POST['consentimiento'] : '';

        // Obtener el nombre de usuario
        $nombreUsuario = isset($_POST['nombreUsuario']) ? htmlspecialchars(trim($_POST['nombreUsuario'])) : '';

        //------------------------------------------------------

        // Validar campos obligatorios
        if (empty($nombre) || empty($apellidos) || empty($fechaNacimiento) || empty($sexo) || empty($email) || empty($numPhone) || empty($direccion) || empty($passw1) || empty($passw2) || empty($nombreUsuario) || !isset($_POST['consentimiento'])) {
            $errorMessage = "<div class='container alert alert-danger'>Los campos con *(asterisco) son OBLIGATORIOS.</div>";
        } else {
            // Validar nombre de usuario
            if (empty($nombreUsuario)) {
                $errorMessage = "<div class='container alert alert-danger'>El nombre de usuario es obligatorio.</div>";
            }
            // Validar nombre
            elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
                $errorMessage = "<div class='container alert alert-danger'>El nombre solo debe contener letras y espacios.</div>";
            }
            // Validar apellidos
            elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellidos)) {
                $errorMessage = "<div class='container alert alert-danger'>Los apellidos solo deben contener letras y espacios.</div>";
            }
            // Validar email
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "<div class='container alert alert-danger'>El email no es válido.</div>";
            }
            // Validar número de teléfono
            elseif (!preg_match("/^\+?[0-9]{7,15}$/", $numPhone)) { // Asegura que sea un número entre 7 y 15 dígitos
                $errorMessage = "<div class='container alert alert-danger'>El número de teléfono no es válido. Debe contener entre 7 y 15 dígitos.</div>";
            }
            // Validar contraseñas
            elseif ($passw1 !== $passw2) {
                $errorMessage = "<div class='container alert alert-danger'>Las contraseñas no coinciden.</div>";
            }
            // Validar requisitos de la contraseña
            elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/", $passw1)) {
                $errorMessage = "<div class='container alert alert-danger'>La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula, un número y un carácter especial.</div>";
            }

            // Si todas las validaciones son correctas
            else {
                if (empty($errorMessage)) {
                    // Verificar si la conexión es exitosa
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    // Convertir el valor de sexo a "M", "F" o "OTRO"
                    switch ($sexo) {
                        case 'masculino':
                            $sexo = 'M';
                            break;
                        case 'femenino':
                            $sexo = 'F';
                            break;
                        case 'otro':
                            $sexo = 'OTRO';
                            break;
                        default:
                            $sexo = 'OTRO';
                            break;
                    }

                    // Capturar los datos del formulario (previniendo SQL Injection)
                    $nombre = $conn->real_escape_string($nombre);
                    $apellidos = $conn->real_escape_string($apellidos);
                    $email = $conn->real_escape_string($email);
                    $telefono = $conn->real_escape_string($numPhone);
                    $fecha_nacimiento = $conn->real_escape_string($fechaNacimiento);
                    $direccion = $conn->real_escape_string($direccion);
                    $nombreUsuario = $conn->real_escape_string($nombreUsuario);

                    // Verificar si el nombre de usuario ya existe
                    $usuarioCheckQuery = $conn->prepare("SELECT * FROM users_login WHERE usuario = ?");
                    $usuarioCheckQuery->bind_param("s", $nombreUsuario);
                    $usuarioCheckQuery->execute();
                    $resultUsuario = $usuarioCheckQuery->get_result();

                    $emailCheckQuery = $conn->prepare("SELECT * FROM users_data WHERE email = ?");
                    $emailCheckQuery->bind_param("s", $email);
                    $emailCheckQuery->execute();
                    $resultEmail = $emailCheckQuery->get_result();

                    if ($resultUsuario->num_rows > 0) {
                        $errorMessage = "<div class='container alert alert-danger'>El nombre de usuario ya está registrado. Por favor, elija otro.</div>";
                    } elseif ($resultEmail->num_rows > 0) {
                        $errorMessage = "<div class='container alert alert-danger'>El correo ya está registrado. Por favor, utilice otro.</div>";
                    } else {
                        // Insertar en la tabla USERS_DATA
                        $sql = $conn->prepare("INSERT INTO users_data (nombre, apellidos, email, telefono, fecha_nacimiento, direccion, sexo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $sql->bind_param("sssssss", $nombre, $apellidos, $email, $telefono, $fecha_nacimiento, $direccion, $sexo);

                        if ($sql->execute()) {
                            // Obtener el último id insertado en USERS_DATA
                            $idUser = $conn->insert_id;

                            // Encriptar la contraseña
                            $passwordHash = password_hash($passw1, PASSWORD_DEFAULT);
                            $rol = "user"; // Asignar un rol por defecto, puede ser dinámico si lo necesitas

                            // Insertar en la tabla USERS_LOGIN con el nombre de usuario
                            $loginSql = $conn->prepare("INSERT INTO users_login (idUser, usuario, password, rol) VALUES (?, ?, ?, ?)");
                            $loginSql->bind_param("isss", $idUser, $nombreUsuario, $passwordHash, $rol);

                            if ($loginSql->execute()) {
                                echo '
                                    <div class="container alert alert-success" role="alert" id="success-message">
                                    ¡Usuario registrado con éxito! Redirigiendo a Inicio de sesión :)
                                    </div>
                                    <script>
                                        setTimeout(function() {
                                            window.location.href = "../views/login.php";
                                        }, 5000);
                                    </script>';
                            } else {
                                $errorMessage = "Error al registrar el usuario en USERS_LOGIN: " . $loginSql->error;
                            }

                            $loginSql->close();
                        } else {
                            $errorMessage = "Error al registrar datos personales: " . $sql->error;
                        }

                        $sql->close();
                    }

                    $usuarioCheckQuery->close();
                    $emailCheckQuery->close();
                }
            }
        }

        // Mostrar el mensaje de error si existe
        if (!empty($errorMessage)) {
            echo $errorMessage;
        }
    }
}
