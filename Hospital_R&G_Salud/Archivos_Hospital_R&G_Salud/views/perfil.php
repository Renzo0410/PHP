<?php
include('../layout/header.php');
require('../config/db.php');

// Obtener el ID del usuario logueado desde la sesión
$idUser = $_SESSION['idUser'];

// Obtener los datos del usuario desde la base de datos, uniendo las tablas users_data y users_login
$stmt = $conn->prepare("
    SELECT ud.*, ul.usuario 
    FROM users_data ud 
    JOIN users_login ul ON ud.idUser = ul.idUser 
    WHERE ud.idUser = ?
");
$stmt->bind_param("i", $idUser);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

// Formatear la fecha de nacimiento si es necesario
$fechaNacimiento = date('Y-m-d', strtotime($userData['fecha_nacimiento']));

// Ruta de la imagen de perfil
$profileImage = !empty($userData['profile_image']) ? '../img/avatar/' . $userData['profile_image'] : '../img/avatar/default.jpg';
?>

<main class="container container-perfil">
    <hr>
    <h2>Perfil de Usuario</h2>
    <hr>

    <?php
    require('../includes/validacion-perfil.php');
    ?>

    <!-- Mostrar la imagen de perfil -->
    <div class="row">

        <!-- c-img-p = Container Img Perfil -->
        <div class="col-lg-4 col-12 mb-3 text-center c-img-p">
            <img class="img-perfil img-fluid" src="<?php echo htmlspecialchars($profileImage); ?>" alt="Imagen de Perfil">
        </div>

        <!-- Contenedor de la información de perfil -->
        <div class="container-info-perfil col-lg-8 col-12">
            <form action="../includes/update_profile.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3 container row">

                    <div class="col-12">
                        <!-- Campo para subir nueva imagen de perfil -->
                        <label for="profileImage">Actualizar Imagen de Perfil</label>
                        <input type="file" class="form-control" id="profileImage" name="profileImage">
                    </div>

                    <div class="col-12">
                        <!-- Mostrar nombre de usuario -->
                        <label for="nombreUsuario">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" value="<?php echo htmlspecialchars($userData['usuario']); ?>" readonly>
                    </div>

                    <!-- Mostrar nombre -->
                    <div class="col-sm-6 col-12">
                        <label for="nombre">Nombre(s)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($userData['nombre']); ?>">
                    </div>

                    <!-- Mostrar apellidos -->
                    <div class="col-sm-6 col-12">
                        <label for="apellidos">Apellido(s)</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($userData['apellidos']); ?>">
                    </div>

                    <!-- Mostrar fecha de nacimiento -->
                    <div class="col-sm-6 col-12">
                        <label for="fechanacimiento">Fecha de nacimiento</label>
                        <input type="date" class="form-control" id="fechanacimiento" name="fechaNacimiento" value="<?php echo htmlspecialchars($fechaNacimiento); ?>">
                    </div>

                    <!-- Mostrar teléfono móvil -->
                    <div class="col-sm-6 col-12">
                        <label for="telefono">Teléfono móvil</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($userData['telefono']); ?>">
                    </div>

                    <!-- Mostar correo electrónico -->
                    <div class="col-12">
                        <label for="email">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>
                    </div>


                    <!-- Mostar dirección de referencia -->
                    <div class="col-12">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($userData['direccion']); ?>">
                    </div>

                    <!-- Campos para cambiar la contraseña -->
                    <hr>
                    <h4>Cambiar Contraseña</h4>

                    <div class="col-12">
                        <label for="currentPassword">Contraseña Actual</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword">
                    </div>

                    <div class="col-md-6 col-12">
                        <label for="newPassword">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                    </div>

                    <div class="col-md-6 col-12">
                        <label for="confirmPassword">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                    </div>
                </div>

                <div class="container row text-center ">
                    <div class="col-12 d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Actualizar datos</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include('../layout/footer.php'); ?>