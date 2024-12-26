<?php

// Verificar si el usuario es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/login.php");
    exit();
}

// Obtener la fecha actual en formato YYYY-MM-DD
$fecha_actual = (new DateTime())->format('Y-m-d');

// Variables para mantener los datos del formulario
$idUser = '';
$fecha_cita = '';
$motivo_cita = '';
$error_message = '';
$success_message = '';

// Función para validar campos mediante expresiones regulares
function validarCampo($campo, $expresion)
{
    return preg_match($expresion, $campo);
}

// Procesar agregar cita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    // Sanitizar los campos
    $idUser = htmlspecialchars(trim($_POST['id_usuario']));
    $fecha_cita = htmlspecialchars(trim($_POST['fecha_cita']));
    $motivo_cita = htmlspecialchars(trim($_POST['motivo_cita']));

    // Validar campos
    $idUserValido = validarCampo($idUser, '/^\d+$/'); // Solo números
    $fechaValida = validarCampo($fecha_cita, '/^\d{4}-\d{2}-\d{2}$/'); // Formato YYYY-MM-DD
    $motivoValido = validarCampo($motivo_cita, '/^[\w\s\.,\-áéíóúÁÉÍÓÚñÑ]+$/'); // Letras, números y algunos signos

    // Verificar que los campos son válidos
    if (!$idUserValido || !$fechaValida || !$motivoValido) {
        $error_message = "Por favor, verifique haber completado todos los campos y que tengan el formato correcto.";
    } elseif ($fecha_cita < $fecha_actual) {
        // Verificar que la fecha_cita no sea anterior a la fecha actual
        $error_message = "No se pueden agendar citas en fechas pasadas.";
    } else {
        // Preparar la consulta para insertar la cita
        $stmt = $conn->prepare("INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $idUser, $fecha_cita, $motivo_cita);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Cita agregada exitosamente.";
            // Redirigir para evitar duplicación (Patrón PRG)
            header("Location: ../views/citas-administracion.php");
            exit();
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

// Procesar eliminación de cita
if (isset($_POST['eliminar'])) {
    $idCita = htmlspecialchars(trim($_POST['idCita']));

    // Validar que el ID de la cita sea un número
    if (!validarCampo($idCita, '/^\d+$/')) {
        $error_message = "El ID de la cita no es válido.";
    } else {
        // Verificar si la cita existe
        $stmt = $conn->prepare("SELECT * FROM citas WHERE idCita = ?");
        $stmt->bind_param("i", $idCita);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $query = "DELETE FROM citas WHERE idCita = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $idCita);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Cita eliminada exitosamente.";
                header("Location: ../views/citas-administracion.php");
                exit();
            } else {
                $error_message = "Error al eliminar la cita: " . $conn->error;
            }
        } else {
            $error_message = "Error: Cita no encontrada.";
        }
    }
}

// Procesar edición de cita
if (isset($_POST['guardarEdicion'])) {
    $idCita = htmlspecialchars(trim($_POST['idCita']));
    $fecha_cita = htmlspecialchars(trim($_POST['fecha_cita']));
    $motivo_cita = htmlspecialchars(trim($_POST['motivo_cita']));

    // Validar campos
    $idCitaValido = validarCampo($idCita, '/^\d+$/'); // Solo números
    $fechaValida = validarCampo($fecha_cita, '/^\d{4}-\d{2}-\d{2}$/'); // Formato YYYY-MM-DD
    $motivoValido = validarCampo($motivo_cita, '/^[\w\s\.,\-]+$/'); // Letras, números y algunos signos

    // Verificar que los campos son válidos
    if (!$idCitaValido || !$fechaValida || !$motivoValido) {
        $error_message = "Por favor, verifique haber completado todos los campos y que tengan el formato correcto.";
    } elseif ($fecha_cita < $fecha_actual) {
        // Verificar que la fecha_cita no sea anterior a la fecha actual
        $error_message = "No se pueden editar citas a fechas pasadas.";
    } else {
        // Verificar si la cita existe
        $stmt = $conn->prepare("SELECT * FROM citas WHERE idCita = ?");
        $stmt->bind_param("i", $idCita);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $query = "UPDATE citas SET fecha_cita=?, motivo_cita=? WHERE idCita=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $fecha_cita, $motivo_cita, $idCita);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "¡La cita ha sido actualizada!.";
                header("Location: ../views/citas-administracion.php");
                exit();
            } else {
                $error_message = "Error al actualizar la cita: " . $conn->error;
            }
        } else {
            $error_message = "Cita no encontrada.";
        }
    }
}

// Obtener todas las citas con el nombre del usuario al que pertenecen
$query = "SELECT c.idCita, c.fecha_cita, c.motivo_cita, u.nombre AS usuario 
            FROM citas c 
            JOIN users_data u ON c.idUser = u.idUser 
            ORDER BY c.fecha_cita";
$result = $conn->query($query);
