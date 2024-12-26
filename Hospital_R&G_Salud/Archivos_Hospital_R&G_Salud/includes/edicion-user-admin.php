<?php
// Obtener usuarios de la base de datos y listarlos
$result = $conn->query("SELECT * FROM users_data");
while ($row = $result->fetch_assoc()) {
    echo "<div class='row'>";
    echo "<div class='col-6 list-users fila-user-edit'>" . htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellidos']) . "</div>";
    echo "<div class='col-6 list-users fila-user-edit'>";
    echo "<button class='btn btn-primary' onclick='mostrarFormularioEdicion(" . $row['idUser'] . ")'>Editar</button>";
    echo "</div>";
    echo "</div>";

    // Agregar un formulario oculto para editar los datos del usuario
    echo "<div id='formulario-edicion-" . $row['idUser'] . "' class='formulario-edicion' style='display: none;'>";
    echo "<form action=' . BASE_URL . /../usuarios-administracion.php' method='post'>";
    echo "<input type='hidden' name='accion' value='editar'>";
    echo "<input type='hidden' name='idUser' value='" . $row['idUser'] . "'>";
    echo "<div class='row'><div class='col-12 col-md-6'><input type='text' class='form-control' name='nombre' value='" . htmlspecialchars($row['nombre']) . "' required></div>";
    echo "<div class='col-12 col-md-6'><input type='text' class='form-control' name='apellidos' value='" . htmlspecialchars($row['apellidos']) . "' required></div></div>";
    echo "<div class='row'><div class='col-12 col-md-6'><input type='email' class='form-control' name='email' value='" . htmlspecialchars($row['email']) . "' required></div>";
    echo "<div class='col-12 col-md-6'><input type='tel' class='form-control' name='telefono' value='" . htmlspecialchars($row['telefono']) . "'></div></div>";
    echo "<input type='date' class='form-control' name='fecha_nacimiento' value='" . htmlspecialchars($row['fecha_nacimiento']) . "'>";
    echo "<input type='text' class='form-control' name='direccion' value='" . htmlspecialchars($row['direccion']) . "'>";
    echo "<select class='form-control' name='sexo'>";
    echo "<option value='M' " . ($row['sexo'] == 'M' ? 'selected' : '') . ">Masculino</option>";
    echo "<option value='F' " . ($row['sexo'] == 'F' ? 'selected' : '') . ">Femenino</option>";
    echo "<option value='O' " . ($row['sexo'] == 'O' ? 'selected' : '') . ">Otro</option>";
    echo "</select>";
    echo "<div>
                <button type='submit' class='btn btn-success mt-2'>Guardar Cambios</button> 
                <button type='button' class='btn btn-secondary mt-2' onclick='ocultarFormularioEdicion(" . $row['idUser'] . ")'>Cancelar</button>
                </div>";
    echo "</form>";
    echo "</div>";
}
