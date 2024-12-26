<?php
// Mostrar mensajes de éxito o error
if (isset($_GET['success'])) {
    echo '<div class="container alert alert-success">Cita agendada exitosamente.</div>';
}

if (isset($_GET['deleted'])) {
    echo '<div class="container alert alert-success">Cita eliminada exitosamente.</div>';
}

if (isset($_GET['updated'])) {
    echo '<div class="container alert alert-success">Cita actualizada exitosamente.</div>';
}

// Procesar agendar cita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agendar'])) {
    $fecha_cita = htmlspecialchars(trim($_POST['fecha_cita'] ?? ''));
    $motivo_cita = htmlspecialchars(trim($_POST['motivo_cita'] ?? ''));

    // Verificar que la fecha_cita no sea anterior a la fecha actual
    if (empty($fecha_cita) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_cita)) {
        echo "<div class='container alert alert-danger'>El motivo y la fecha de cita son obligatorios para ser agendados.</div>";
    } elseif ($fecha_cita < date('Y-m-d')) {
        echo "<div class='container alert alert-danger'>No se pueden agendar citas en fechas pasadas.</div>";
    } elseif (empty($motivo_cita) || !preg_match("/^[\w\s\.,\-áéíóúÁÉÍÓÚñÑ]+$/", $motivo_cita)) {
        echo "<div class='container alert alert-danger'>El motivo de la cita es obligatorio y solo puede contener letras, números y ciertos signos.</div>";
    } else {
        $query = "INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES ('$idUser', '$fecha_cita', '$motivo_cita')";

        if ($conn->query($query) === TRUE) {
            // Después de agendar la cita, redirigir para evitar duplicación en caso de recarga
            header("Location: ../views/citas.php?success=true");
            exit();
        } else {
            echo "<div class='container alert alert-danger'>Error al agendar la cita: " . $conn->error . "</div>";
        }
    }
}

// Procesar eliminación de cita
if (isset($_POST['eliminar'])) {
    $idCita = htmlspecialchars(trim($_POST['idCita'] ?? ''));

    if (empty($idCita) || !preg_match("/^\d+$/", $idCita)) {
        echo "<div class='container alert alert-danger'>El ID de la cita es obligatorio y debe ser un número válido.</div>";
    } else {
        $query = "DELETE FROM citas WHERE idCita = '$idCita' AND idUser = '$idUser'";  // Asegurar que solo elimina sus propias citas

        if ($conn->query($query) === TRUE) {
            header("Location: ../views/citas.php?deleted=true");
            exit();
        } else {
            echo "<div class='container alert alert-danger'>Error al eliminar la cita: " . $conn->error . "</div>";
        }
    }
}

// Procesar edición de cita
if (isset($_POST['guardarEdicion'])) {
    $idCita = htmlspecialchars(trim($_POST['idCita'] ?? ''));
    $fecha_cita = htmlspecialchars(trim($_POST['fecha_cita'] ?? ''));
    $motivo_cita = htmlspecialchars(trim($_POST['motivo_cita'] ?? ''));

    // Validar el ID de la cita
    if (empty($idCita) || !preg_match("/^\d+$/", $idCita)) {
        echo "<div class='container alert alert-danger'>El ID de la cita es obligatorio y debe ser un número válido.</div>";
    } elseif (empty($fecha_cita) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_cita)) {
        echo "<div class='container alert alert-danger'>El motivo y la fecha de cita son obligatorios para ser agendados.</div>";
    } elseif ($fecha_cita < date('Y-m-d')) {
        echo "<div class='container alert alert-danger'>No se pueden editar citas a fechas pasadas.</div>";
    } elseif (empty($motivo_cita) || !preg_match("/^[\w\s\.,\-]+$/", $motivo_cita)) {
        echo "<div class='container alert alert-danger'>El motivo de la cita es obligatorio y solo puede contener letras, números y ciertos signos.</div>";
    } else {
        $query = "UPDATE citas SET fecha_cita='$fecha_cita', motivo_cita='$motivo_cita' WHERE idCita='$idCita' AND idUser = '$idUser'";  // Asegurar que solo edita sus propias citas

        if ($conn->query($query) === TRUE) {
            header("Location: ../views/citas.php?updated=true");
            exit();
        } else {
            echo "<div class='container alert alert-danger'>Error al actualizar la cita: " . $conn->error . "</div>";
        }
    }
}

// Obtener las citas del usuario logueado
$query = "SELECT * FROM citas 
    WHERE idUser = '$idUser' 
    ORDER BY fecha_cita";
$result = $conn->query($query);
