<?php

if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../views/login.php");
    exit();
}

// Variables para mantener los datos ingresados
$tituloGuardado = '';
$textoGuardado = '';
$imagenGuardada = ''; // Variable para guardar la imagen

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        // Validar campos obligatorios
        if (empty($_POST['titulo']) || empty($_POST['texto']) || empty($_FILES['imagen']['name'])) {
            $_SESSION['error_message'] = "Todos los campos son obligatorios para agregar una nueva noticia.";
            // Guardar los datos ingresados
            $tituloGuardado = $_POST['titulo'];
            $textoGuardado = $_POST['texto'];
            $imagenGuardada = $_FILES['imagen']['name']; // Mantener el nombre de la imagen
        } else {
            $titulo = $conn->real_escape_string($_POST['titulo']);
            $texto = $conn->real_escape_string($_POST['texto']);
            $fecha = date("Y-m-d");
            $idUser = $_SESSION['idUser'];
            // Asignar datos ingresados a las variables
            $tituloGuardado = $titulo;
            $textoGuardado = $texto;

            // Verificar si el título ya existe
            $checkTitleQuery = "SELECT * FROM noticias WHERE titulo = '$titulo'";
            $resultCheck = $conn->query($checkTitleQuery);
            if ($resultCheck->num_rows > 0) {
                $_SESSION['error_message'] = "El título de la noticia ya está registrado. Por favor, elige un título diferente.";
                // Mantener los datos ingresados
            } else {
                // Manejo de la subida de imagen
                $imagen = $_FILES['imagen']['name'];
                $target_dir = "../img/noticias/";
                $target_file = $target_dir . basename($imagen);
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Validar tipo de archivo y tamaño
                if (!in_array($imageFileType, $allowed_types)) {
                    $_SESSION['error_message'] = "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
                    // Mantener los datos ingresados
                } elseif ($_FILES['imagen']['size'] > 20000000) {
                    $_SESSION['error_message'] = "El archivo es demasiado grande. Máximo 20MB.";
                    // Mantener los datos ingresados
                } else {
                    // Subir la imagen
                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
                        $query = "INSERT INTO noticias (titulo, imagen, texto, fecha, idUser) VALUES ('$titulo', '$imagen', '$texto', '$fecha', '$idUser')";
                        if ($conn->query($query) === TRUE) {
                            $_SESSION['success_message'] = "Noticia agregada exitosamente.";
                            // Resetear variables al éxito
                            $tituloGuardado = '';
                            $textoGuardado = '';
                            $imagenGuardada = ''; // Resetear también la imagen guardada
                        } else {
                            $_SESSION['error_message'] = "Error al agregar la noticia: " . $conn->error;
                        }
                    } else {
                        $_SESSION['error_message'] = "Error al subir la imagen.";
                    }
                }
            }
        }
    }

    // Procesar eliminación de noticias
    if (isset($_POST['eliminar'])) {
        $idNoticia = $conn->real_escape_string($_POST['idNoticia']);
        $query = "DELETE FROM noticias WHERE idNoticia = '$idNoticia'";
        if ($conn->query($query) === TRUE) {
            $_SESSION['success_message'] = "Noticia eliminada exitosamente.";
        } else {
            $_SESSION['error_message'] = "Error al eliminar la noticia." . $conn->error;
        }
    }

    // Procesar la edición de noticias
    if (isset($_POST['guardarEdicion'])) {
        $idNoticia = $conn->real_escape_string($_POST['idNoticia']);
        $titulo = $conn->real_escape_string($_POST['titulo']);
        $texto = $conn->real_escape_string($_POST['texto']);
        $fecha = date("Y-m-d");
        // Variable para almacenar la imagen
        $imagen = ''; // Inicializar la variable imagen
        // Verificar si se ha subido una nueva imagen
        if (!empty($_FILES['imagen']['tmp_name'])) {
            $imagen = $_FILES['imagen']['name'];
            $target_dir = "../img/noticias/";
            $target_file = $target_dir . basename($imagen);
            // Mover la nueva imagen al directorio
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
                // La imagen se ha subido correctamente
            } else {
                $_SESSION['error_message'] = "Error al subir la nueva imagen.";
                return; // Salir si hay un error en la carga
            }
        } else {
            // Si no hay una nueva imagen, usar la imagen actual
            $query_imagen_actual = "SELECT imagen FROM noticias WHERE idNoticia='$idNoticia'";
            $result_imagen_actual = $conn->query($query_imagen_actual);
            if ($result_imagen_actual->num_rows > 0) {
                $row_imagen = $result_imagen_actual->fetch_assoc();
                $imagen = $row_imagen['imagen']; // Mantener la imagen actual
            }
        }

        // Actualizar la noticia
        if (!empty($titulo) && !empty($texto)) {
            $query = "UPDATE noticias SET titulo='$titulo', imagen='$imagen', texto='$texto', fecha='$fecha' WHERE idNoticia='$idNoticia'";
            if ($conn->query($query) === TRUE) {
                $_SESSION['success_message'] = "Noticia actualizada exitosamente.";
            } else {
                $_SESSION['error_message'] = "Error al actualizar la noticia." . $conn->error;
            }
        } else {
            $_SESSION['error_message'] = "El título y el texto son obligatorios.";
        }
    }
}

// Obtener todas las noticias, ahora incluyendo el nombre del autor
$query = "SELECT n.*, u.nombre AS autor 
    FROM noticias n
    LEFT JOIN users_data u ON n.idUser = u.idUser 
    ORDER BY n.fecha DESC";
$result = $conn->query($query);
