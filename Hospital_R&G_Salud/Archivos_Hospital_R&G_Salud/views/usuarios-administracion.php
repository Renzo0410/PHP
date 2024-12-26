<?php include('../layout/header.php'); ?>
<?php require('../config/db.php'); ?>
<?php require('../includes/validacion-user-admin.php'); ?>

<div class="container c-admin-user">

    <hr>
    <h2>Administración De Usuarios</h2>
    <hr>

    <!-- Formulario para crear usuario -->
    <div class="form-create-user">
        <div class="container row">
            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>" . htmlspecialchars($_SESSION['error']) . "</div>";
                unset($_SESSION['error']); // Limpia el mensaje después de mostrarlo
            }
            if (isset($_SESSION['message'])) {
                echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
                unset($_SESSION['message']); // Limpia el mensaje después de mostrarlo
            }
            ?>
        </div>

        <h5>Crear usuarios nuevos</h5>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="accion" value="crear">
            <div class="row">
                <div class="col-md-6 col-12">
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">
                </div>
                <div class="col-md-6 col-12">
                    <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" value="<?php echo isset($apellidos) ? htmlspecialchars($apellidos) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <input type="tel" class="form-control" name="telefono" placeholder="Teléfono" value="<?php echo isset($telefono) ? htmlspecialchars($telefono) : ''; ?>">
                </div>
                <div class="col-md-6 col-12">
                    <input type="date" class="form-control" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" value="<?php echo isset($fecha_nacimiento) ? htmlspecialchars($fecha_nacimiento) : ''; ?>">
                </div>
            </div>
            <input type="text" class="form-control" name="direccion" placeholder="Dirección" value="<?php echo isset($direccion) ? htmlspecialchars($direccion) : ''; ?>">
            <input type="email" class="form-control" name="email" placeholder="Correo electrónico" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="input-group">
                        <input type="password" class="form-control" name="passw1" id="passw1" placeholder="Contraseña" value="<?php echo isset($passw1) ? htmlspecialchars($passw1) : ''; ?>">
                        <button class="form-control btn btn-outline-secondary" type="button" id="togglePassw1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="input-group">
                        <input type="password" class="form-control" name="passw2" id="passw2" placeholder="Repetir contraseña" value="<?php echo isset($passw2) ? htmlspecialchars($passw2) : ''; ?>">
                        <button class="form-control btn btn-outline-secondary" type="button" id="togglePassw2">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-12">
                    <input type="text" value="<?php if (isset($nombreUsuario)) echo $nombreUsuario ?>" name="nombreUsuario" class="form-control" id="nombreUsuario" placeholder="Nombre de usuario">
                </div>
                <div class="col-md-6 col-12">
                    <select class="form-control" name="sexo" placeholder="SEXO">
                        <option value="" disabled selected>Sexo de usuario</option>
                        <option value="M" <?php echo isset($sexo) && $sexo == 'M' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="F" <?php echo isset($sexo) && $sexo == 'F' ? 'selected' : ''; ?>>Femenino</option>
                        <option value="O" <?php echo isset($sexo) && $sexo == 'O' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <select class="select-rol" name="rol" placeholder="ROL">
                        <option value="" disabled selected>Rol de usuario</option>
                        <option value="user" <?php echo isset($rol) && $rol == 'user' ? 'selected' : ''; ?>>Usuario</option>
                        <option value="admin" <?php echo isset($rol) && $rol == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>
                <div class="col-md-6 col-12">
                    <div class="col-12 d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Crear Usuario</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Editar y eliminar un usuario -->

    <hr>
    <h4>Usuarios registrados</h4>
    <hr>

    <?php
    $sqlUsuarios = "SELECT ud.*, ul.usuario, ul.rol 
    FROM users_data ud 
    JOIN users_login ul ON ud.idUser = ul.idUser";

    $result = $conn->query($sqlUsuarios);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
            <div class="table-user row">
                <!-- Mostrar el nombre de usuario en vez del nombre -->
                <div class="list-users fila-user-edit col-4"><?php echo htmlspecialchars($row['usuario']); ?></div>

                <div class="list-users fila-user-edit col-4">
                    <button class="btn btn-warning" onclick="openEditUserModal(
                        '<?php echo $row['idUser']; ?>',
                        '<?php echo htmlspecialchars($row['nombre']); ?>',
                        '<?php echo htmlspecialchars($row['apellidos']); ?>',
                        '<?php echo htmlspecialchars($row['email']); ?>',
                        '<?php echo htmlspecialchars($row['telefono']); ?>',
                        '<?php echo htmlspecialchars($row['direccion']); ?>',
                        '<?php echo htmlspecialchars($row['fecha_nacimiento']); ?>',
                        '<?php echo htmlspecialchars($row['rol']); ?>'
                        )">Editar
                    </button>

                </div>

                <div class="list-users fila-user-edit col-4">
                    <button class="btn btn-danger" type="button" onclick="openDeleteUserModal('<?php echo $row['idUser']; ?>')">Eliminar</button>
                </div>
            </div>
    <?php
        }
    }
    ?>

</div>

<!-- Modal para editar usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="usuarios-administracion.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idUser" id="editIdUser" value="">
                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editApellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="editApellidos" name="apellidos" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTelefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="editTelefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="editDireccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="editDireccion" name="direccion">
                    </div>
                    <div class="mb-3">
                        <label for="editFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="editFechaNacimiento" name="fecha_nacimiento">
                    </div>
                    <div class="mb-3">
                        <label for="editRol" class="form-label">Rol</label>
                        <select class="select-rol" id="editRol" name="rol" required>
                            <option value="user">Usuario</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="newPassword" name="new_password" placeholder="Dejar en blanco para mantener la actual">
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="guardarEdicion">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para confirmar eliminación de usuario -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="usuarios-administracion.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Eliminar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este usuario?</p>
                    <input type="hidden" name="idUser" id="deleteIdUser" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" name="accion" value="eliminar">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php include('../layout/footer.php'); ?>