<?php
include('../layout/header.php');
require('../config/db.php');
require('../includes/validacion-notice-admin.php');
?>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="container alert alert-success">
        <?php
        echo htmlspecialchars($_SESSION['success_message']);
        unset($_SESSION['success_message']);
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="container alert alert-danger">
        <?php
        echo htmlspecialchars($_SESSION['error_message']);
        unset($_SESSION['error_message']);
        ?>
    </div>
<?php endif; ?>


<main class="container">
    <hr>
    <h2>Agregar Nueva Noticia</h2>
    <hr>
    <form action="noticias-administracion.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($tituloGuardado); ?>">
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen">
        </div>
        <div class="mb-3">
            <label for="texto" class="form-label">Texto</label>
            <textarea class="form-control" id="texto" name="texto" rows="5"><?php echo htmlspecialchars($textoGuardado); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Agregar Noticia</button>
    </form>
</main>

<main class="container" id="edit-section">
    <hr>
    <h2>Noticias publicadas</h2>
    <div class="container c-admin-notice">
        <?php
        $sqlNoticias = "SELECT * FROM noticias"; // Asegúrate de que esta consulta sea correcta para tus necesidades
        $result = $conn->query($sqlNoticias);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
                <div class="row text-center">
                    <div class="fw-medium col-md-4 col-12 notice-edit_delete">
                        <?php echo htmlspecialchars($row['titulo']); ?>
                    </div>
                    <div class="fw-medium col-md-4 col-6 notice-edit_delete">
                        <button type="button" class="btn btn-warning" onclick="openEditModalNoticias('<?php echo $row['idNoticia']; ?>', '<?php echo addslashes($row['titulo']); ?>', '<?php echo addslashes($row['texto']); ?>', '<?php echo htmlspecialchars($row['imagen']); ?>')">
                            Editar
                        </button>
                    </div>
                    <div class="fw-medium col-md-4 col-6 notice-edit_delete">
                        <button type="button" class="btn btn-danger" onclick="openDeleteNoticeModal('<?php echo $row['idNoticia']; ?>')">
                            Eliminar
                        </button>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<div class='alert alert-info'>No hay noticias disponibles.</div>";
        }
        ?>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="editModalNoticias" tabindex="-1" aria-labelledby="editModalNoticiasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Noticia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idNoticia" id="modalIdNoticiaEdit">
                        <div class="mb-3">
                            <label for="tituloEdit" class="form-label">Título</label>
                            <input type="text" class="form-control" id="tituloEdit" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagenEdit" class="form-label">Imagen Actual</label><br>
                            <img id="modalImagenActual" src="" alt="Imagen Actual" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                            <label for="imagenEdit" class="form-label">Cambiar Imagen:</label>
                            <input type="file" class="form-control" id="imagenEdit" name="imagen">
                        </div>
                        <div class="mb-3">
                            <label for="textoEdit" class="form-label">Texto</label>
                            <textarea class="form-control" id="textoEdit" name="texto" rows="5" required></textarea>
                        </div>
                        <input type="hidden" name="imagenActual" id="modalImagenActual">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="fw-medium btn btn-primary" name="guardarEdicion">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="deleteModalNoticias" tabindex="-1" aria-labelledby="deleteModalNoticiasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalNoticiasLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta noticia? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="confirmDeleteForm" action="noticias-administracion.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="idNoticia" id="modalIdNoticia">
                        <button type="submit" class="btn btn-danger" name="eliminar">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</main>

<?php include('../layout/footer.php'); ?>