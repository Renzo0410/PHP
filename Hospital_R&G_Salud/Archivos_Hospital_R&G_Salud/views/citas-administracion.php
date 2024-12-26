    <?php include('../layout/header.php'); ?>
    <?php require('../config/db.php'); ?>
    <?php require('../includes/validacion-citas-admin.php'); ?>

    <!-- Mensaje de error o éxito -->
    <?php if (!empty($error_message)): ?>
        <div class="container alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="container alert alert-success"><?php echo htmlspecialchars($_SESSION['success_message']);
                                                    unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <div class="container">

        <hr>
        <h2>Agendar cita</h2>
        <hr>

        <?php
        // Consulta para obtener todos los usuarios con sus nombres
        $sqlUsuarios = "SELECT ud.idUser, ul.usuario 
                FROM users_data AS ud 
                JOIN users_login AS ul ON ud.idUser = ul.idUser";
        $resultUsuarios = $conn->query($sqlUsuarios);
        ?>

        <form action="citas-administracion.php" method="POST">
            <div class="container row">
                <div class="mb-3 col-md-6 col-12">
                    <label for="id_usuario" class="form-label">Seleccionar Usuario</label>
                    <select class="select-rol" id="id_usuario" name="id_usuario">
                        <option value="">Selecciona un usuario</option>
                        <?php while ($rowUsuario = $resultUsuarios->fetch_assoc()) { ?>
                            <option value="<?php echo $rowUsuario['idUser']; ?>" <?php if ($rowUsuario['idUser'] == $idUser) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($rowUsuario['usuario']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3 col-md-6 col-12">
                    <label for="fecha_cita" class="form-label">Fecha de la Cita</label>
                    <input type="date" class="form-control" id="fecha_cita" name="fecha_cita" min="<?php echo $fecha_actual; ?>" value="<?php echo htmlspecialchars($fecha_cita); ?>">
                </div>
                <div class="mb-3 col-12">
                    <label for="motivo_cita" class="form-label">Motivo de la Cita</label>
                    <input type="text" class="form-control" id="motivo_cita" name="motivo_cita" value="<?php echo htmlspecialchars($motivo_cita); ?>">
                </div>
            </div>
            <div class="container text-center row">
                <div class="mx-auto col-12 d-grid gap-2 w-50">
                    <button type="submit" class="p-3 fw-medium btn btn-primary" name="agregar">Agendar cita</button>
                </div>
            </div>
        </form>

        <hr>
        <h3>Citas registradas</h3>
        <hr>

        <div class="list-group">
            <?php
            // Ejecutar la consulta actualizada para obtener citas con nombre de usuario
            $sqlCitas = "SELECT c.idCita, c.fecha_cita, c.motivo_cita, u.usuario 
                FROM citas AS c
                JOIN users_login AS u ON c.idUser = u.idUser";
            $result = $conn->query($sqlCitas);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <div class="list-group-item">
                        <h5><?php echo htmlspecialchars($row['motivo_cita']); ?></h5>
                        <!-- Mostrar el nombre de usuario al que pertenece la cita -->
                        <p>Fecha: <?php echo date("d/m/Y", strtotime($row['fecha_cita'])); ?> | Usuario: <?php echo htmlspecialchars($row['usuario']); ?></p>

                        <!-- Formularios de eliminar y editar permanecen iguales -->
                        <form action="citas-administracion.php" method="POST" style="display:inline;">
                            <input type="hidden" name="idCita" value="<?php echo $row['idCita']; ?>">

                            <button type="button" class="btn btn-danger" onclick="openDeleteCitaAdminModal(<?php echo $row['idCita']; ?>)">Eliminar</button>
                        </form>

                        <!-- Botón para abrir el modal de edición -->
                        <form action="citas-administracion.php" method="POST" style="display:inline;">

                            <input type="hidden" name="idCita" value="<?php echo $row['idCita']; ?>">
                            <input type="hidden" name="motivo_cita" value="<?php echo htmlspecialchars($row['motivo_cita']); ?>">
                            <input type="hidden" name="fecha_cita" value="<?php echo date("Y-m-d", strtotime($row['fecha_cita'])); ?>">
                            <button type="button" class="btn btn-warning" onclick="openEditCitaAdminModal(<?php echo $row['idCita']; ?>, '<?php echo htmlspecialchars($row['motivo_cita']); ?>', '<?php echo date('Y-m-d', strtotime($row['fecha_cita'])); ?>')">Editar</button>
                        </form>

                    </div>
            <?php
                }
            } else {
                echo "<p>No hay citas disponibles.</p>";
            }
            ?>
        </div>

    </div>

    <!-- Modal para editar cita -->
    <div class="modal fade" id="editModalCitaAdmin" tabindex="-1" aria-labelledby="editModalCitaAdminLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="citas-administracion.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar Cita</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="idCita" id="editIdCita" value="">
                        <div class="mb-3">
                            <label for="edit_fecha_cita" class="form-label">Fecha de la Cita</label>
                            <input type="date" class="form-control" id="edit_fecha_cita" name="fecha_cita" min="<?php echo $fecha_actual; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="edit_motivo_cita" class="form-label">Motivo de la Cita</label>
                            <input type="text" class="form-control" id="edit_motivo_cita" name="motivo_cita">
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

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteModalCitaAdmin" tabindex="-1" aria-labelledby="deleteModalCitaAdminLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="citas-administracion.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalCitaAdminLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar esta cita?
                        <input type="hidden" name="idCita" id="deleteIdCita" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" name="eliminar">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('../layout/footer.php') ?>