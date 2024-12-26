<!-- Mostrar mensajes de éxito o error -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php
        echo htmlspecialchars($_SESSION['success_message']); // Escapar el mensaje para evitar XSS
        unset($_SESSION['success_message']); // Eliminar el mensaje después de mostrarlo
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <?php
        echo htmlspecialchars($_SESSION['error_message']); // Escapar el mensaje para evitar XSS
        unset($_SESSION['error_message']); // Eliminar el mensaje después de mostrarlo
        ?>
    </div>
<?php endif; ?>